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

namespace app\model\activity\combination;

use app\model\user\User;
use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * TODO Chia sẻ nhómModel
 * Class StorePink
 * @package app\model\activity
 */class StorePink extends BaseModel
{
    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'store_pink';

    use ModelTrait;

    /**
     * Liên kết một-một Khách hàng
     * @return \think\model\relation\HasOne
     */    public function getUser()
    {
        return $this->hasOne(User::class, 'uid', 'uid')->bind(['nickname', 'avatar']);
    }

    public function getProduct()
    {
        return $this->hasOne(StoreCombination::class, 'id', 'cid')->bind(['title']);
    }

    /**
     * Công cụ tìm số thứ tự
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchOrderIdAttr($query, $value, $data)
    {
        $query->where('order_id', $value);
    }

    /**
     * Công cụ tìm số thứ tự
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchOrderIdKeyAttr($query, $value, $data)
    {
        $query->where('order_id_key', $value);
    }

    /**
     * Trình tìm kiếm ID sản phẩm nhóm
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchCidAttr($query, $value, $data)
    {
        $query->where('cid', $value);
    }

    /**
     * Trình tìm kiếm ID sản phẩm
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchPidAttr($query, $value, $data)
    {
        $query->where('pid', $value);
    }

    /**
     * Là người tìm kiếm lãnh đạo
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchKIdAttr($query, $value, $data)
    {
        $query->where('k_id', $value);
    }

    /**
     * Hoàn lại tiền hoặc không tìm kiếm
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchIsRefundAttr($query, $value, $data)
    {
        $query->where('is_refund', $value ?? 0);
    }

    /**
     * công cụ tìm trạng thái
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchStatusAttr($query, $value, $data)
    {
        if ($value != '') $query->where('status', $value);
    }
}
