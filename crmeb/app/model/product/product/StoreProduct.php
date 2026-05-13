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

namespace app\model\product\product;

use app\model\product\sku\StoreProductAttrValue;
use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 *  hàng hóaModel
 * Class StoreProduct
 * @package app\model\product\product
 */
class StoreProduct extends BaseModel
{
    use  ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */
    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */
    protected $name = 'store_product';

    /**
     * hiệp hội một-một
     *Chi tiết sản phẩm của các sản phẩm liên quan đến sản phẩm
     * @return \think\model\relation\HasOne
     */
    public function description()
    {
        return $this->hasOne(StoreDescription::class, 'product_id', 'id')->where('type', 0)->bind(['description']);
    }

    /**
     * liên kết một-nhiều
     * Mẫu phiếu giảm giá liên quan đến sản phẩmid
     * @return \think\model\relation\HasMany
     */
    public function couponId()
    {
        return $this->hasMany(StoreProductCoupon::class, 'product_id', 'id');
    }

    /**
     * Tên phiếu giảm giá từ một đến nhiều
     * @return \think\model\relation\HasMany
     */
    public function coupons()
    {
        return $this->hasMany(StoreProductCoupon::class, 'product_id', 'id');
    }

    /**
     * Bình luận một đến nhiều
     * @return \think\model\relation\HasMany
     */
    public function star()
    {
        return $this->hasMany(StoreProductReply::class, 'product_id', 'id')->where('is_del', 0)->field('product_score,product_id');
    }

    /**
     * Phân loại một đến nhiều
     * @return \think\model\relation\HasMany
     */
    public function cateName()
    {
        return $this->hasMany(StoreProductCate::class, 'product_id', 'id')->with('cateName');
    }

    public function attrs()
    {
        return $this->hasMany(StoreProductAttrValue::class, 'product_id', 'id')->where('type', 0);
    }


    /**
     * Trình lấy hình ảnh băng chuyền
     * @param $value
     * @return array|mixed
     */
    public function getSliderImageAttr($value)
    {
        return is_string($value) ? json_decode($value, true) : [];
    }

    /**
     * Có hiển thị cho người tìm kiếm hay không
     * @param $query
     * @param $value
     */
    public function searchIsShowAttr($query, $value)
    {
        if ($value != -1) $query->where('is_show', $value ?? 1);
    }

    /**
     * @param Model $query
     * @param $value
     */
    public function searchIdAttr($query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('id', $value);
        } else {
            $query->where('id', $value);
        }
    }

    /**
     * Có nên xóa người tìm kiếm hay không
     * @param Model $query
     * @param $value
     */
    public function searchIsDelAttr($query, $value)
    {
        $query->where('is_del', $value ?: 0);
    }

    /**
     * Trình tìm ID người bán
     * @param Model $query
     * @param $value
     */
    public function searchMerIdAttr($query, $value)
    {
        $query->where('mer_id', $value ?? 0);
    }

    /**
     * Trình tìm ID cửa hàng
     * @param Model $query
     * @param $value
     */
    public function searchStoreIdAttr($query, $value)
    {
        if ($value !== '' && $value !== null) {
            $query->where('store_id', $value);
        }
    }

    /**
     * Quan hệ một-một với cửa hàng
     * @return \think\model\relation\HasOne
     */
    public function storeBranch()
    {
        return $this->hasOne(\app\model\system\store\SystemStore::class, 'id', 'store_id')
            ->field(['id', 'name'])
            ->bind(['store_branch_name' => 'name']);
    }

    /**
     * keywordNgười tìm kiếm
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchStoreNameAttr($query, $value, $data)
    {
        if ($value != '') {
            $field = 'keyword|store_name|store_info|id|bar_code';
            if (is_string($value)) {
                $query->whereLike($field, htmlspecialchars("%" . trim($value) . "%"));
            } elseif (is_array($value) && count($value) > 0) {
                $query->where(function ($q) use ($value, $field) {
                    $data = [];
                    foreach ($value as $k) {
                        $data[] = [$field, 'like', "%" . trim($k) . "%"];
                    }
                    $q->whereOr($data);
                });
            }
        }
    }

    /**
     * Công cụ tìm sản phẩm mới
     * @param Model $query
     * @param int $value
     */
    public function searchIsNewAttr($query, $value)
    {
        if ($value) $query->where('is_new', $value);
    }

    /**
     * Công cụ tìm kiếm sản phẩm giảm giá
     * @param Model $query
     * @param int $value
     */
    public function searchIsBenefitAttr($query, $value)
    {
        $query->where('is_benefit', $value ?? 1);
    }

    /**
     * Công cụ tìm vật phẩm nóng
     * @param Model $query
     * @param int $value
     */
    public function searchIsHotAttr($query, $value)
    {
        $query->where('is_hot', $value ?? 1);
    }

    /**
     * Công cụ tìm sản phẩm cao cấp
     * @param Model $query
     * @param int $value
     */
    public function searchIsBestAttr($query, $value)
    {
        $query->where('is_best', $value ?? 1);
    }

    /**
     * Công cụ tìm sản phẩm cao cấp
     * @param Model $query
     * @param int $value
     */
    public function searchIsGoodAttr($query, $value)
    {
        $query->where('is_good', $value ?? 1);
    }

    /**
     * Gắn thẻ công cụ tìm sản phẩm
     * @param Model $query
     * @param int $value
     */
    public function searchLabelIdAttr($query, $value)
    {
        $query->whereFindInSet('label_id', $value);
    }

    /**
     * SPUNgười tìm kiếm
     * @param Model $query
     * @param int $value
     */
    public function searchSpuAttr($query, $value)
    {
        $query->where('spu', $value);
    }

    /**
     * Công cụ tìm hàng tồn kho
     * @param Model $query
     * @param int $value
     */
    public function searchStockAttr($query, $value)
    {
        $query->where('stock', $value);
    }

    /**
     * Công cụ tìm sản phẩm chỉ dành cho thành viên
     * @param Model $query
     * @param int $value
     */
    public function searchVipUserAttr($query, $value)
    {
        if ($value === 0) {
            $query->where('vip_product', 0)->whereOr(function ($query) {
                $query->where('vip_product', 1)->where('vip_product_type', 1);
            });
        }
    }

    /**
     * Nó có phải là một công cụ tìm hàng ảo?
     * @param $query
     * @param $value
     */
    public function searchIsVirtualAttr($query, $value)
    {
        if ($value == 0) {
            $query->where('virtual_type', 0)->where('vip_product', 0)->where('presale', 0);
        }
    }

    /**
     * Có nên bán trước sản phẩm hay không
     * @param $query
     * @param $value
     */
    public function searchIsPresaleAttr($query, $value)
    {
        if ($value >= 0) {
            $query->where('presale', $value);
        }
    }

    /**
     * Trình tìm kiếm danh mục
     * @param Model $query
     * @param int $value
     */
    public function searchCateIdAttr($query, $value)
    {
        if ($value) {
            if (is_array($value)) {
                $query->whereIn('id', function ($query) use ($value) {
                    $query->name('store_product_cate')->where('cate_id', 'IN', $value)->whereOr('cate_pid', 'IN', $value)->field('product_id')->select();
                });
            } else {
                $query->whereFindInSet('cate_id', $value);
            }
        }
    }

    /**
     * Trình tìm kiếm điều kiện số lượng sản phẩm
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchTypeAttr($query, $value, $data)
    {
        switch ((int)$value) {
            case 1:
                $query->where(['is_show' => 1, 'is_del' => 0]);
                break;
            case 2:
                $query->where(['is_show' => 0, 'is_del' => 0]);
                break;
            case 3:
                $query->where(['is_del' => 0, 'vip_product' => 0]);
                break;
            case 4:
                $query->where(['is_del' => 0])->where(function ($query) {
                    $query->whereIn('id', function ($query) {
                        $query->name('store_product_attr_value')->where('stock', 0)->where('type', 0)->field('product_id')->select();
                    })->whereOr('stock', 0);
                });
                break;
            case 5:
                if (isset($data['store_stock']) && $data['store_stock']) {
                    $store_stock = $data['store_stock'];
                    $query->whereIn('id', function ($query) use ($store_stock) {
                        $query->name('store_product_attr_value')->where('stock', '<', $store_stock)->where('stock', '>', 0)->where('type', 0)->field('product_id')->select();
                    });
                } else {
                    $query->where(['is_show' => 1, 'is_del' => 0])->where('stock', '>', 0);
                }
                break;
            case 6:
                $query->where(['is_del' => 1]);
                break;
            case 7:
                $query->where(['is_del' => 0, 'vip_product' => 0, 'virtual_type' => 0]);
                break;
        }
    }

    /**
     * Truy vấn trong id hiện tại
     * @param $query
     * @param $value
     */
    public function searchIdsAttr($query, $value)
    {
        if (is_string($value)) {
            if ($value !== '') {
                $value = explode(',', $value);
            } else {
                $value = [];
            }
        }
        if (count($value)) $query->whereIn('id', $value);
    }

    /**
     * Không truy vấn trong id hiện tại
     * @param $query
     * @param $value
     */
    public function searchNotIdsAttr($query, $value)
    {
        if ($value != '') $query->whereNotIn('id', $value);
    }

    /**
     * Trình tìm kiếm biểu mẫu tùy chỉnh
     * @param $query
     * @param $value
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2025/1/14
     */
    public function searchCustomFormAttr($query, $value)
    {
        if ($value !== '') $query->whereLike('custom_form', '%' . $value . '%');
    }

    /**
     * công cụ tìm kiếm kiểu ảo
     * @param $query
     * @param $value
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2025/1/14
     */
    public function searchVirtualTypeAttr($query, $value)
    {
        if ($value !== '') $query->where('virtual_type', $value);
    }

    /**
     * Trình tìm kiếm loại thông số kỹ thuật
     * @param $query
     * @param $value
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2025/1/14
     */
    public function searchSpecTypeAttr($query, $value)
    {
        if ($value !== '') $query->where('spec_type', $value);
    }

    /**
     * Dù là người tìm quà
     * @param $query
     * @param $value
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2025/1/14
     */
    public function searchIsGiftAttr($query, $value)
    {
        if ($value !== '') $query->where('is_gift', $value);
    }

    /**
     * Công cụ tìm sản phẩm chỉ dành cho thành viên
     * @param $query
     * @param $value
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2025/1/14
     */
    public function searchVipProductAttr($query, $value)
    {
        if ($value !== '') $query->where('vip_product', $value);
    }

    /**
     * công cụ tìm phạm vi giá
     * @param $query
     * @param $value
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2025/1/14
     */
    public function searchPriceSAttr($query, $value)
    {
        if (count($value) == 2 && ($value[0] !== '' || $value[1] !== '')) {
            if ($value[0] !== '' && $value[1] !== '') {
                $query->whereBetween('price', [$value[0], $value[1]]);
            } elseif ($value[0] !== '') {
                $query->where('price', '>=', $value[0]);
            } elseif ($value[1] !== '') {
                $query->where('price', '<=', $value[1]);
            }
        }
    }

    /**
     * Công cụ tìm kiếm phạm vi chứng khoán
     * @param $query
     * @param $value
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2025/1/14
     */
    public function searchStockSAttr($query, $value)
    {
        if (count($value) == 2 && ($value[0] !== '' || $value[1] !== '')) {
            if ($value[0] !== '' && $value[1] !== '') {
                $query->whereBetween('stock', [$value[0], $value[1]]);
            } elseif ($value[0] !== '') {
                $query->where('stock', '>=', $value[0]);
            } elseif ($value[1] !== '') {
                $query->where('stock', '<=', $value[1]);
            }
        }
    }

    /**
     * Công cụ tìm kiếm phạm vi bán hàng
     * @param $query
     * @param $value
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2025/1/14
     */
    public function searchSalesSAttr($query, $value)
    {
        if (count($value) == 2 && ($value[0] !== '' || $value[1] !== '')) {
            if ($value[0] !== '' && $value[1] !== '') {
                $query->whereBetween('sales', [$value[0], $value[1]]);
            } elseif ($value[0] !== '') {
                $query->where('sales', '>=', $value[0]);
            } elseif ($value[1] !== '') {
                $query->where('sales', '<=', $value[1]);
            }
        }
    }

    /**
     * người tìm kiếm thẻ
     * @param $query
     * @param $value
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2025/2/19
     */
    public function searchStoreLabelIdAttr($query, $value)
    {
        if (count($value)) {
            $query->where(function ($query) use ($value) {
                foreach ($value as $item) {
                    $query->whereOr('FIND_IN_SET(:value, label_list)', ['value' => $item]);
                }
            });
        }
    }

    /**
     * Công cụ tìm phương thức vận chuyển
     * @param $query
     * @param $value
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2025/2/19
     */
    public function searchLogisticsAttr($query, $value)
    {
        if ($value !== '') $query->whereFindInSet('logistics', $value);
    }

    /**
     * Trình tìm kiếm loại sản phẩm
     * @param $query
     * @param $value
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2025/2/19
     */
    public function searchVirtualeTypeAttr($query, $value)
    {
        if ($value !== '') $query->where('virtual_type', $value);
    }


}
