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
declare (strict_types=1);

namespace app\dao\user;


use app\dao\BaseDao;
use app\model\user\UserInvoice;

/**
 * Class UserInvoiceDao
 * @package app\dao\user
 */class UserInvoiceDao extends BaseDao
{

    protected function setModel(): string
    {
        return UserInvoice::class;
    }

    /**
     * @param array $where
     * @param string $field
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getList(array $where, string $field = '*', int $page, int $limit)
    {
        return $this->search($where)->field($field)->page($page, $limit)->order('is_default desc,id desc')->select()->toArray();
    }

    /**
     * Đặt mặc định(Cá nhân trung bình|Doanh nghiệp ở mức trung bình|Chỉ dành cho doanh nghiệp)
     * @param int $uid
     * @param int $id
     * @param $header_type
     * @param $type
     * @return bool
     */    public function setDefault(int $uid, int $id, $header_type, $type)
    {
        if (false === $this->getModel()->where('uid', $uid)->where('header_type', $header_type)->where('type', $type)->update(['is_default' => 0])) {
            return false;
        }
        if (false === $this->getModel()->where('id', $id)->update(['is_default' => 1])) {
            return false;
        }
        return true;
    }
}
