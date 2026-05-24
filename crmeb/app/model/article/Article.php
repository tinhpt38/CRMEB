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
namespace app\model\article;

use app\model\product\product\StoreProduct;
use crmeb\traits\ModelTrait;
use crmeb\basic\BaseModel;
use think\Model;

/**
 * TODO bài báoModel
 * Class Article
 * @package app\model\article
 */class Article extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'article';

    /**
     * Liên kết một-một sản phẩm
     * @return \think\model\relation\HasOne
     */    public function storeInfo()
    {
        return $this->hasOne(StoreProduct::class, 'id', 'product_id')
            ->field('store_name,image,price,id,ot_price');
    }

    /**
     * Liên kết một-một của chi tiết bài viết
     * @return \think\model\relation\HasOne
     */    public function content()
    {
        return $this->hasOne(ArticleContent::class, 'nid', 'id')->bind(['content']);
    }

    /**
     * Liên kết một-một của chi tiết bài viết
     * @return \think\model\relation\HasOne
     */    public function cateName()
    {
        return $this->hasOne(ArticleCategory::class, 'id', 'cid')->bind(['catename' => 'title']);
    }

    /**
     * Trình lấy hình ảnh bài viết
     * @param $value
     * @return array|false|string[]
     */    protected function getImageInputAttr($value)
    {
        return explode(',', $value) ?: [];
    }

    /**
     * Trình tìm kiếm danh mục bài viết
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchCidAttr($query, $value, $data)
    {
        if ($value) {
            if (is_array($value)) {
                $query->whereIn('cid', $value);
            } else {
                $query->where('cid', $value);
            }
        }
    }

    /**
     * Trình tìm kiếm tiêu đề bài viết
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchTitleAttr($query, $value, $data)
    {
        if ($value !== '') {
            $query->where('title', 'like', '%' . $value . '%');
        }
    }

    /**
     * Công cụ tìm bài viết phổ biến
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchIsHotAttr($query, $value, $data)
    {
        if ($value !== '') {
            $query->where('is_hot', $value);
        }
    }

    /**
     * Trình tìm kiếm bài viết băng chuyền
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchIsBannerAttr($query, $value, $data)
    {
        if ($value !== '') {
            $query->where('is_banner', $value);
        }
    }

}
