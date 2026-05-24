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

namespace app\kefuapi\controller;


use app\Request;
use app\services\system\attachment\SystemAttachmentServices;
use app\services\user\UserGroupServices;
use crmeb\services\CacheService;
use app\services\other\UploadService;
use think\facade\App;
use app\services\kefu\UserServices;
use app\services\user\UserLabelCateServices;
use app\services\user\UserLabelRelationServices;
use app\services\kefu\service\StoreServiceRecordServices;
use think\facade\Config;

/**
 * Class User
 * @package app\kefuapi\controller
 */class User extends AuthController
{
    /**
     * User constructor.
     * @param App $app
     * @param StoreServiceRecordServices $services
     */    public function __construct(App $app, StoreServiceRecordServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Nhận lịch sử trò chuyện của CSKH và Khách hàng hiện tại
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function recordList(string $nickname = '', $is_tourist = 0)
    {
        return app('json')->success($this->services->getServiceList($this->kefuInfo['uid'], $nickname, (int)$is_tourist));
    }

    /**
     * Lấy thông tin Khách hàng
     * @param UserServices $services
     * @param $uid
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function userInfo(UserServices $services, $uid)
    {
        return app('json')->success($services->getUserInfo((int)$uid));
    }

    /**
     * Phân loại thẻ
     * @param UserLabelCateServices $services
     * @return mixed
     */    public function getUserLabel(UserLabelCateServices $services, $uid)
    {
        return app('json')->success($services->getUserLabel((int)$uid));
    }

    /**
     * Nhận nhóm Khách hàng
     * @param UserGroupServices $services
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getUserGroup(UserGroupServices $services)
    {
        return app('json')->success($services->getGroupList());
    }

    /**
     * Thiết lập nhóm
     * @param UserGroupServices $services
     * @param UserServices $userServices
     * @param $uid
     * @param $id
     * @return mixed
     */    public function setUserGroup(UserGroupServices $services, UserServices $userServices, $uid, $id)
    {
        if (!$services->count(['id' => $id])) {
            return app('json')->fail('Dữ liệu không tồn tại');
        }
        if (!($userInfo = $userServices->get($uid))) {
            return app('json')->fail('Người dùng không tồn tại');
        }
        if ($userInfo->group_id == $id) {
            return app('json')->fail('Đã sở hữu nhóm này');
        }
        $userInfo->group_id = $id;
        if ($userInfo->save()) {
            return app('json')->success('Thiết lập thành công');
        } else {
            return app('json')->fail('Thiết lập không thành công');
        }
    }

    /**
     * Đặt nhãn Khách hàng
     * @param UserLabelRelationServices $services
     * @param $uid
     * @return mixed
     */    public function setUserLabel(UserLabelRelationServices $services, $uid)
    {
        [$labels, $unLabelIds] = $this->request->postMore([
            ['label_ids', []],
            ['un_label_ids', []]
        ], true);
        if (!count($labels) && !count($unLabelIds)) {
            return app('json')->fail('thiếu nhãnid');
        }
        if ($services->setUserLabel($uid, $labels) && $services->unUserLabel($uid, $unLabelIds)) {
            return app('json')->success('Thiết lập thành công');
        } else {
            return app('json')->fail('Thiết lập không thành công');
        }
    }

    /**
     * Đăng xuất
     * @return mixed
     */    public function logout()
    {
        $key = trim(ltrim($this->request->header(Config::get('cookie.token_name')), 'Bearer'));
        CacheService::delete(md5($key));
        return app('json')->success();
    }

    /**
     * Tải lên hình ảnh
     * @param Request $request
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */    public function upload(Request $request, SystemAttachmentServices $services)
    {
        $data = $request->postMore([
            ['filename', 'file'],
        ]);
        if (!$data['filename']) return app('json')->fail('Lỗi tham số');
        if (CacheService::has('start_uploads_' . $request->kefuId()) && CacheService::get('start_uploads_' . $request->kefuId()) >= 100) return app('json')->fail('Hoạt động trái phép');
        $upload = UploadService::init();
        $info = $upload->to('store/comment')->validate()->move($data['filename']);
        if ($info === false) {
            return app('json')->fail($upload->getError());
        }
        $res = $upload->getUploadInfo();
        $services->attachmentAdd($res['name'], $res['size'], $res['type'], $res['dir'], $res['thumb_path'], 1, (int)sys_config('upload_type', 1), $res['time'], 2);
        if (CacheService::has('start_uploads_' . $request->kefuId()))
            $start_uploads = (int)CacheService::get('start_uploads_' . $request->kefuId());
        else
            $start_uploads = 0;
        $start_uploads++;
        CacheService::set('start_uploads_' . $request->kefuId(), $start_uploads, 86400);
        $res['dir'] = path_to_url($res['dir']);
        if (strpos($res['dir'], 'http') === false) $res['dir'] = $request->domain() . $res['dir'];
        return app('json')->success('Hình ảnh được tải lên thành công', ['name' => $res['name'], 'url' => $res['dir']]);
    }

}
