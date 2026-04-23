<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2026 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------
namespace app\services\user;

use app\dao\user\UserCancelDao;
use app\services\BaseServices;
use app\services\kefu\service\StoreServiceServices;
use app\services\wechat\WechatUserServices;
use crmeb\services\CacheService;

class UserCancelServices extends BaseServices
{
    protected $status = ['Đang chờ xem xét', 'Đi qua', 'Vật bị loại bỏ'];

    /**
     * UserExtractServices constructor.
     * @param UserCancelDao $dao
     */
    public function __construct(UserCancelDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Gửi đăng xuất người dùng
     * @param $userInfo
     * @return mixed
     */
    public function SetUserCancel($uid)
    {
        /** @var UserServices $userServices */
        $userServices = app()->make(UserServices::class);
        /** @var WechatUserServices $wechatUserServices */
        $wechatUserServices = app()->make(WechatUserServices::class);
        /** @var StoreServiceServices $ServiceServices */
        $ServiceServices = app()->make(StoreServiceServices::class);
        $userServices->update($uid, ['is_del' => 1]);
        $userServices->update(['spread_uid' => $uid], ['spread_uid' => 0, 'spread_time' => 0]);
        $wechatUserServices->update(['uid' => $uid], ['is_del' => 1]);
        $ServiceServices->delete(['uid' => $uid]);

        $user = $userServices->getUserInfo($uid);

        //Đăng xuất người dùng sự kiện tùy chỉnh
        event('CustomEventListener', ['user_cancel', [
            'uid' => $uid,
            'nickname' => $user['nickname'],
            'phone' => $user['phone'],
            'add_time' => date('Y-m-d H:i:s', $user['add_time']),
            'cancel_time' => date('Y-m-d H:i:s'),
            'user_type' => $user['user_type'],
        ]]);

        return true;
    }

    /**
     * Nhận danh sách đăng xuất
     * @param $where
     * @return array
     */
    public function getCancelList($where)
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getList($where, $page, $limit);
        foreach ($list as &$item) {
            $item['add_time'] = date('Y-m-d H:i:s', $item['add_time']);
            $item['up_time'] = $item['up_time'] != 0 ? date('Y-m-d H:i:s', $item['add_time']) : '';
            $item['status'] = $this->status[$item['status']];
        }
        $count = $this->dao->count($where);
        return compact('list', 'count');
    }

    /**
     * Nhận xét
     * @param $id
     * @param $mark
     * @return mixed
     */
    public function serMark($id, $mark)
    {
        return $this->dao->update($id, ['remark' => $mark]);
    }
}
