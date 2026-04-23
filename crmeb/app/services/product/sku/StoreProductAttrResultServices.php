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

namespace app\services\product\sku;


use app\dao\product\sku\StoreProductAttrResultDao;
use app\services\BaseServices;
use crmeb\exceptions\AdminException;

/**
 * Class StoreProductAttrResultService
 * @package app\services\product\sku
 * @method value(array $where, string $field)
 */
class StoreProductAttrResultServices extends BaseServices
{
    /**
     * StoreProductAttrResultServices constructor.
     * @param StoreProductAttrResultDao $dao
     */
    public function __construct(StoreProductAttrResultDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Nhận thông số kỹ thuật thuộc tính
     * @param array $where
     * @return mixed
     */
    public function getResult(array $where)
    {
        return json_decode($this->dao->value($where, 'result'), true);
    }

    /**
     * Xóa thuộc tính
     * @param int $id
     * @param int $type
     * @return bool
     */
    public function del(int $id, int $type)
    {
        return $this->dao->del($id, $type);
    }

    /**
     * Sửa đổi thuộc tính
     * @param array $data
     * @param int $id
     * @param int $type
     */
    public function setResult(array $data, int $id, int $type)
    {
        $res = $this->dao->save(['product_id' => $id, 'result' => json_encode($data), 'change_time' => time(), 'type' => $type]);
        if (!$res) throw new AdminException('Lưu không thành công');
    }
}
