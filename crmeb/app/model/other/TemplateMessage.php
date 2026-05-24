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

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 *  Mẫu tin nhắnModel
 * Class TemplateMessage
 * @package app\model\other
 */class TemplateMessage extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'template_message';

    /**
     * Trình tìm kiếm ID mẫu
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchTempIdAttr($query, $value, $data)
    {
        $query->where('temp_id', $value);
    }

    /**
     * @param Model $query
     * @param $value
     */    public function searchTypeAttr($query, $value)
    {
        if (in_array($value,[0,1])){
            $query->where('type', $value);
        }
    }

    /**
     * @param Model $query
     * @param $value
     */    public function searchStatusAttr($query, $value)
    {
        if ($value) {
            $query->where('status', $value);
        }
    }

    /**
     * @param Model $query
     * @param $value
     */    public function searchNameAttr($query, $value)
    {
        if ($value) {
            $query->where('name', 'LIKE',"%$value%");
        }
    }
}
