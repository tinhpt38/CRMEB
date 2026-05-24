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

namespace app\services\shipping;


use app\dao\shipping\ShippingTemplatesNoDeliveryCityDao;
use app\services\BaseServices;

/**
 * Lớp xử lý kinh doanh bảng kết nối dữ liệu thành phố và không phân phối
 * Class ShippingTemplatesNoDeliveryCityServices
 * @package app\services\shipping
 * @method getUniqidList(array $where, bool $group) Nhận danh sách vận chuyển miễn phí theo các điều kiện được chỉ định
 */class ShippingTemplatesNoDeliveryCityServices extends BaseServices
{
    /**
     * Người xây dựng
     * ShippingTemplatesNoDeliveryCityServices constructor.
     * @param ShippingTemplatesNoDeliveryCityDao $dao
     */    public function __construct(ShippingTemplatesNoDeliveryCityDao $dao)
    {
        $this->dao = $dao;
    }
}
