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

namespace app\dao\user;


use app\dao\BaseDao;
use app\model\user\MemberShip;

class MemberShipDao extends BaseDao
{
    /** Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        // TODO: Implement setModel() method.
        return MemberShip::class;
    }

    /**Giao diện hậu trường nhận loại thẻ thành viên
     * @param array $where
     * @param int $page
     * @param int $limit
     * @param array $field
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getSearchList(array $where, int $page = 0, int $limit = 0, array $field = ['*'])
    {
        return $this->search($where)->order('sort desc,id desc')
            ->field($field)
            ->page($page, $limit)
            ->select();

    }

    /**Nhận giao diện api loại thành viên
     * @return mixed
     */    public function getApiList(array $where)
    {
        return $this->search()->where($where)->order('sort desc,id DESC')->select()->toArray();
    }


}
