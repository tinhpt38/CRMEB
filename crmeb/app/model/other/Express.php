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

use crmeb\traits\ModelTrait;
use crmeb\basic\BaseModel;
use think\Model;

/**
 * Công ty hậu cầnModel
 * Class Express
 * @package app\model\other
 */class Express extends BaseModel
{

    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'express';

    /**
     * Công ty hậu cần có hiển thị
     * @param Model $query
     * @param $value
     */    public function searchIsShowAttr($query, $value)
    {
        if ($value !== '') $query->where('is_show', $value);
    }

    /**
     * Thông tin công ty logistics có đầy đủ không?
     * @param Model $query
     * @param $value
     */    public function searchStatusAttr($query, $value)
    {
        $query->where('status', $value);
    }

    /**
     * keyword Người tìm kiếm
     * @param Model $query
     * @param $value
     */    public function searchKeywordAttr($query, $value)
    {
        if ($value) {
            $query->whereLike('name|code', '%' . $value . '%');
        }
    }

    public function searchCodeAttr($query, $value)
    {
        if ($value !== '') {
            $query->where('code', $value);
        }
    }
}
