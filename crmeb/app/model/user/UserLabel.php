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

namespace app\model\user;

use app\model\other\Category;
use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * Class UserLabel
 * @package app\model\user
 */
class UserLabel extends BaseModel
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
    protected $name = 'user_label';

    /**
     * Phân loại thẻ
     * @param \think\Model $query
     * @param $value
     */
    public function searchLabelCateAttr($query, $value)
    {
        if ($value !== '') {
            $query->where('label_cate', $value);
        }
    }

    /**
     * Phân loại thẻ liên quan
     * @return \think\model\relation\HasOne
     */
    public function cateName()
    {
        return $this->hasOne(Category::class, 'id', 'label_cate')->where('type', 0)->field(['id', 'name'])->bind(['cate_name' => 'name']);
    }

    /**
     * idsNgười tìm kiếm
     * @param Model $query
     * @param $value
     */
    public function searchIdsAttr($query, $value)
    {
        if ($value) $query->whereIn('id', $value);
    }
}
