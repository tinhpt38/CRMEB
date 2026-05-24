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
declare (strict_types=1);

namespace app\model\service;

use app\model\other\Category;
use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * Kỹ năng phục vụ khách hàng
 * @mixin Model
 */class StoreServiceSpeechcraft extends BaseModel
{
    use ModelTrait;

    /**
     * tên bảng
     * @var string
     */    protected $name = 'store_service_speechcraft';

    /**
     * khóa chính
     * @var string
     */    protected $pk = 'id';

    /**
     * định dạng thời gian
     * @param $value
     * @param $data
     * @return false|string
     */    public function getAddTimeAttr($value, $data)
    {
        return date('Y-m-d H:i:s', $value);
    }

    /**
     * Phân loại thẻ liên quan
     * @return \think\model\relation\HasOne
     */    public function cateName()
    {
        return $this->hasOne(Category::class, 'id', 'cate_id')->where('type', 1)->field(['id', 'name'])->bind(['cate_name' => 'name']);
    }

    /**
     * Tìm kiếm từ
     * @param Model $query
     * @param $value
     */    public function searchTitleAttr($query, $value)
    {
        if ($value !== '') $query->whereLike('title', '%' . $value . '%');
    }

    /**
     * Tìm kiếm CSKH được phân bổ
     * @param Model $query
     * @param $value
     */    public function searchKefuIdAttr($query, $value)
    {
        if ($value !== '') {
            $query->where('kefu_id', $value);
        }
    }

    /**
     * Tìm kiếm danh mục
     * @param Model $query
     * @param $value
     */    public function searchCateIdAttr($query, $value)
    {
        if ($value !== '') {
            $query->where('cate_id', $value);
        }
    }

    /**
     * @param Model $query
     * @param $value
     */    public function searchMessageAttr($query, $value)
    {
        if ($value !== '') $query->where('message', $value);

    }
}
