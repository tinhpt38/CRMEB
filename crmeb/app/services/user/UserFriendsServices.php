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


use app\dao\user\UserFriendsDao;
use app\services\BaseServices;

/**
 * Nhận danh sách bạn bè
 * Class UserFriendsServices
 * @package app\services\user
 */
class UserFriendsServices extends BaseServices
{

    public function __construct(UserFriendsDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Nhận danh sách bạn bè
     * @param array $where
     * @param array $with
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getFriendList(array $where, array $with = [])
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getFriendList($where, $page, $limit, $with);
        $count = $this->dao->count($where);
        return compact('list', 'count');
    }

    /**
     * Lưu giữ mối quan hệ bạn bè
     * @param array $data
     * @return bool|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function saveFriend(array $data)
    {
        $userFriend = $this->dao->get(['uid' => $data['uid']]);
        if ($userFriend) {
            return true;
        } else {
            return $this->dao->save($data);
        }
    }

}
