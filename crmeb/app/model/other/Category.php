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

namespace app\model\other;


use app\model\user\UserLabel;
use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * Bảng phân loại
 * Class Category
 * @package app\model\other
 */
class Category extends BaseModel
{

    use ModelTrait;

    /**
     * tên bảng
     * @var string
     */
    protected $name = 'category';

    /**
     * khóa chính
     * @var string
     */
    protected $pk = 'id';

    /**
     * Tìm kiếm tên danh mục
     * @param Model $query
     * @param $value
     */
    public function searchNameAttr($query, $value)
    {
        $query->whereLike('name', '%' . $value . '%');
    }

    /**
     *  Thuộc về
     * @param Model $query
     * @param $value
     */
    public function searchOwnerIdAttr($query, $value)
    {
        $query->where('owner_id', $value);
    }

    /**
     *  kiểu
     * @param Model $query
     * @param $value
     */
    public function searchTypeAttr($query, $value)
    {
        $query->where('type', $value);
    }

    /**
     * liên kết một-nhiều
     * @return \think\model\relation\HasMany
     */
    public function label()
    {
        return $this->hasMany(UserLabel::class, 'label_cate', 'id');
    }
}
