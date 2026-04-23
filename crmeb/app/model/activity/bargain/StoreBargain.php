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

use app\model\product\product\StoreDescription;
use app\model\product\product\StoreProduct;
use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * TODO mặt hàng giá hờiModel
 * Class StoreBargain
 * @package app\model\activity
 */
class StoreBargain extends BaseModel
{
    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */
    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */
    protected $name = 'store_bargain';

    use ModelTrait;

    public function getImagesAttr($value)
    {
        return json_decode($value, true) ?? [];
    }

    /**
     * hiệp hội một-một
     *Chi tiết sản phẩm của các sản phẩm liên quan đến sản phẩm
     * @return \think\model\relation\HasOne
     */
    public function description()
    {
        return $this->hasOne(StoreDescription::class, 'product_id', 'id')->where('type', 2)->bind(['description']);
    }

    /**
     * giá gốc
     * @return \think\model\relation\HasOne
     */
    public function product()
    {
        return $this->hasOne(StoreProduct::class, 'id', 'product_id')->field(['id', 'ot_price', 'cate_id', 'price'])->bind([
            'ot_price' => 'ot_price',
            'product_price' => 'price',
            'cate_id' => 'cate_id'
        ]);
    }

    /**
     * Thêm công cụ lấy thời gian
     * @param $value
     * @return false|string
     */
    protected function getAddTimeAttr($value)
    {
        if ($value) return date('Y-m-d H:i:s', (int)$value);
        return '';
    }

    /**
     * Công cụ tìm kiếm tên sản phẩm mặc cả
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchStoreNameAttr($query, $value, $data)
    {
        if ($value != '') $query->where('title|id', 'like', '%' . $value . '%');
    }

    /**
     * công cụ tìm trạng thái
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchStatusAttr($query, $value, $data)
    {
        if ($value != '') $query->where('status', $value ?? 1);
    }

    /**
     * Bạn có đề xuất một công cụ tìm kiếm?
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchIsHotAttr($query, $value, $data)
    {
        $query->where('is_hot', $value ?? 0);
    }

    /**
     * Có nên xóa người tìm kiếm hay không
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchIsDelAttr($query, $value, $data)
    {
        $query->where('is_del', $value ?? 0);
    }

    /**
     * Trình tìm kiếm ID sản phẩm
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchProductIdAttr($query, $value, $data)
    {
        if ($value) {
            if (is_array($value)) {
                $query->whereIn('product_id', $value);
            } else {
                $query->where('product_id', $value);
            }
        }
    }

    /**
     * Trình tìm kiếm thời gian hợp lệ của sự kiện
     * @param $query
     * @param $value
     */
    public function searchBargainTimeAttr($query, $value)
    {
        if ($value == 1) {
            $time = time();
            $query->where('start_time', '<=', $time)->where('stop_time', '>=', $time);
        }
    }
}
