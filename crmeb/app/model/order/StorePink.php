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
namespace app\model\order;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;


/**
 * Class StorePink
 * @package app\model\order
 */class StorePink extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'store_pink';

    protected $insert = ['add_time'];

    /**
     * Tạo công cụ sửa đổi thời gian
     * @return int
     */    protected function setAddTimeAttr()
    {
        return time();
    }
    /**
     * Trình tìm kiếm id đơn hàng nhóm
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchOrderIdKeyAttr($query, $value, $data)
    {
        $query->whereIn('order_id_key', $value);
    }


}
