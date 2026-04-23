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

namespace app\model\system;


use crmeb\basic\BaseModel;

/**
 * Class SystemCrud
 * @author Chờ gió tới
 * @email 136327134@qq.com
 * @date 2023/4/6
 * @package app\model\system
 */
class SystemCrud extends BaseModel
{

    /**
     * @var string
     */
    protected $name = 'system_crud';

    /**
     * @var string
     */
    protected $pk = 'id';

    public function getAddTimeAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    public function getFieldAttr($value)
    {
        return json_decode($value, true);
    }

    public function getMenuIdsAttr($value)
    {
        return json_decode($value, true);
    }

    public function getMakePathAttr($value)
    {
        return json_decode($value, true);
    }

    public function getRouteIdsAttr($value)
    {
        return json_decode($value, true);
    }
}
