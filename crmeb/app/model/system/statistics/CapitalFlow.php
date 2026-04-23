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

namespace app\model\system\statistics;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

class CapitalFlow extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */
    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */
    protected $name = 'capital_flow';

    /**
     * Trình tìm loại giao dịch
     * @param $query
     * @param $value
     */
    public function searchTradingTypeAttr($query, $value)
    {
        if ($value) $query->where('trading_type', $value);
    }

    /**
     * người tìm kiếm từ khóa
     * @param $query
     * @param $value
     */
    public function searchKeywordsAttr($query, $value)
    {
        if ($value !== '') $query->where('order_id|uid|nickname|phone', 'like', '%' . $value . '%');
    }

    /**
     * công cụ tìm kiếm id hàng loạt
     * @param $query
     * @param $value
     */
    public function searchIdsAttr($query, $value)
    {
        if ($value != '') $query->whereIn('id', $value);
    }
}
