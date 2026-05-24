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

namespace app\dao\shipping;

use app\dao\BaseDao;
use app\model\other\Express;

/**
 * Thông tin hậu cần
 * Class ExpressDao
 * @package app\dao\other
 */class ExpressDao extends BaseDao
{
    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return Express::class;
    }

    /**
     * Nhận danh sách hậu cần
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getExpressList(array $where, string $field, int $page, int $limit)
    {
        return $this->search($where)->field($field)->order('sort DESC,is_show DESC,id ASC')
            ->when($page > 0 && $limit > 0, function ($query) use ($page, $limit) {
                $query->page($page, $limit);
            })->select()->toArray();
    }

    /**
     * Nhận thông tin hậu cần theo các điều kiện cụ thể và gửi lại dưới dạng mảng
     * @param array $where
     * @param string $field
     * @param string $key
     * @return array
     */    public function getExpress(array $where, string $field, string $key)
    {
        return $this->search($where)->order('id DESC')->column($field, $key);
    }

    /**
     * Nhận một phần thông tin thông qua mã
     * @param string $code
     * @param string $field
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getExpressByCode(string $code, string $field = '*')
    {
        return $this->getModel()->field($field)->where('code', $code)->find();
    }
}
