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

namespace app\model\activity\bargain;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * TODO Giúp thương lượngModel
 * Class StoreBargainUserHelp
 * @package app\model\activity
 */class StoreBargainUserHelp extends BaseModel
{
    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'store_bargain_user_help';

    use ModelTrait;

    /**
     * Người tìm kiếm Khách hàng
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchUidAttr($query, $value, $data)
    {
        $query->where('uid', $value);
    }

    /**
     * Trình tìm kiếm ID sản phẩm
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchBargainIdAttr($query, $value, $data)
    {
        $query->where('bargain_id', $value);
    }

    /**
     * Công cụ tìm ID mặc cả
     * @param $query
     * @param $value
     */    public function searchBargainUserIdAttr($query, $value)
    {
        $query->where('bargain_user_id', $value);
    }

    /**
     * Công cụ tìm ID mặc cả
     * @param $query
     * @param $value
     */    public function searchTypeAttr($query, $value)
    {
        $query->where('type', $value);
    }
}

