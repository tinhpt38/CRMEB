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

namespace app\dao\shipping;

use app\dao\BaseDao;
use app\model\shipping\ShippingTemplates;

/**
 *
 * Class ShippingTemplatesDao
 * @package app\dao\shipping
 */class ShippingTemplatesDao extends BaseDao
{

    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return ShippingTemplates::class;
    }

    /**
     * Nhận danh sách các mẫu đã chọn
     * @return array
     */    public function getSelectList()
    {
        return $this->search()->order('sort DESC,id DESC')->column('id,name');
    }

    /**
     * lấy
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getShippingList(array $where, int $page, int $limit)
    {
        return $this->search($where)->order('sort DESC,id DESC')->page($page, $limit)->select()->toArray();
    }

    /**
     * Chèn dữ liệu và trả về khóa chínhid
     * @param array $data
     * @return int|string
     */    public function insertGetId(array $data)
    {
        return $this->getModel()->insertGetId($data);
    }

    /**
     * Nhận dữ liệu theo các điều kiện quy định của mẫu vận chuyển sản phẩm
     * @param array $where
     * @param string $field
     * @param string $key
     * @return array
     */    public function getShippingColumn(array $where, string $field, string $key)
    {
        return $this->search($where)->column($field, $key);
    }
}
