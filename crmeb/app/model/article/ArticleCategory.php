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

use crmeb\traits\ModelTrait;
use crmeb\basic\BaseModel;
use think\Model;

/**
 * TODO Phân loại bài viếtModel
 * Class ArticleCategory
 * @package app\model\article
 */
class ArticleCategory extends BaseModel
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
    protected $name = 'article_category';

    /**
     * Nhận điều kiện truy vấn phân loại tập hợp con
     * @return \think\model\relation\HasMany
     */
    public function children()
    {
        return $this->hasMany(self::class, 'pid', 'id')->where(['hidden' => 0, 'is_del' => 0, 'status' => 1])->order('sort DESC,id DESC')->field('id,pid,title');
    }

    /**
     * Trình tìm trạng thái phân loại
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchStatusAttr($query, $value, $data)
    {
        if ($value !== '') $query->where('status', $value);
    }

    /**
     * Trình tìm kiếm tên danh mục
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchTitleAttr($query, $value, $data)
    {
        $query->where('title', 'like', '%' . $value . '%');
    }

    /**
     * Ẩn người tìm kiếm
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchHiddenAttr($query, $value, $data)
    {
        $query->where('hidden', $value);
    }

    /**
     * Xóa người tìm kiếm
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchIsDelAttr($query, $value, $data)
    {
        $query->where('is_del', $value);
    }

    /**
     * Người tìm kiếm cao cấp
     * @param $query
     * @param $value
     */
    public function searchPidAttr($query, $value)
    {
        if ($value !== '') $query->where('pid', $value);
    }
}
