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

namespace app\model\system\config;


use crmeb\basic\BaseModel;

/**
 * lưu trữ đám mây
 * Class SystemStorage
 * @package app\model\system\config
 */class SystemStorage extends BaseModel
{

    /**
     * @var string
     */    protected $name = 'system_storage';

    /**
     * @var string
     */    protected $pk = 'id';

    /**
     * @var bool
     */    protected $autoWriteTimestamp = false;

    /**
     * @param $query
     * @param $value
     */    public function searchNameAttr($query, $value)
    {
        $query->where('name', $value);
    }

    /**
     * Nhập trình tìm kiếm
     * @param $query
     * @param $value
     */    public function searchTypeAttr($query, $value)
    {
        if ($value) $query->where('type', $value);
    }

    /**
     * công cụ tìm trạng thái
     * @param $query
     * @param $value
     */    public function searchStatusAttr($query, $value)
    {
        if ($value !== '') $query->where('status', $value);
    }
}
