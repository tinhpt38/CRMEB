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

use app\model\product\product\StoreDescription;
use app\model\product\product\StoreProduct;
use crmeb\traits\ModelTrait;
use crmeb\basic\BaseModel;
use think\Model;

/**
 * TODO Sản phẩm mua chungModel
 * Class StoreCombination
 * @package app\model\activity
 */class StoreCombination extends BaseModel
{
    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'store_combination';

    use ModelTrait;

    /**
     * Nhận giá gốc 1-1
     * @return \think\model\relation\HasOne
     */    public function getPrice()
    {
        return $this->hasOne(StoreProduct::class, 'id', 'product_id')->bind(['ot_price', 'product_price' => 'price']);
    }
    /**
     * Nhận phân loại sản phẩm từng cái một
     * @return \think\model\relation\HasOne
     */    public function getCategory()
    {
        return $this->hasOne(StoreProduct::class, 'id', 'product_id')->bind(['cate_id']);
    }
    /**
     * hiệp hội một-một
     *Chi tiết sản phẩm của các sản phẩm liên quan đến sản phẩm
     * @return \think\model\relation\HasOne
     */    public function total()
    {
        return $this->hasOne(StoreProduct::class, 'id', 'product_id')->where('is_show', 1)->where('is_del', 0)->field(['(sales+ficti) as total', 'id', 'price'])->bind([
            'total' => 'total', 'product_price' => 'price'
        ]);
    }

    /**
     * hiệp hội một-một
     *Chi tiết sản phẩm của các sản phẩm liên quan đến sản phẩm
     * @return \think\model\relation\HasOne
     */    public function description()
    {
        return $this->hasOne(StoreDescription::class, 'product_id', 'id')->where('type', 3)->bind(['description']);
    }

    /**
     * Thêm công cụ lấy thời gian
     * @param $value
     * @return false|string
     */    protected function getAddTimeAttr($value)
    {
        if ($value) return date('Y-m-d H:i:s', (int)$value);
        return '';
    }

    /**
     * Trình lấy hình ảnh băng chuyền
     * @param $value
     * @return mixed
     */    public function getImagesAttr($value)
    {
        return json_decode($value, true) ?? [];
    }

    /**
     * Nhóm tìm kiếm tên sản phẩm
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchStoreNameAttr($query, $value, $data)
    {
        if ($value) $query->where('title|id', 'like', '%' . $value . '%');
    }

    /**
     * Bạn có đề xuất một công cụ tìm kiếm?
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchIsHostAttr($query, $value, $data)
    {
        $query->where('is_host', $value ?? 1);
    }

    /**
     * công cụ tìm trạng thái
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchIsShowAttr($query, $value, $data)
    {
        if ($value != '') $query->where('is_show', $value ?: 0);
    }

    /**
     * Có nên xóa người tìm kiếm hay không
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchIsDelAttr($query, $value, $data)
    {
        $query->where('is_del', $value ?? 0);
    }

    /**
     * Trình tìm kiếm ID sản phẩm
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchProductIdAttr($query, $value, $data)
    {
        if ($value) {
            if (is_array($value)) {
                $query->whereIn('product_id', $value);
            } else {
                $query->where('product_id', $value);
            }
        }
    }
}
