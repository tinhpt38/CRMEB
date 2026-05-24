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
 * @author wuhaotian
 * @email 442384644@qq.com
 * @date 2024/5/20
 */class SystemCrudList extends BaseModel
{
    /**
     * @var string
     */    protected $name = 'system_crud_list';

    /**
     * @var string
     */    protected $pk = 'id';

    public function searchStatusAttr($query, $value)
    {
        if ($value !== '') {
            $query->where('status', $value);
        }
    }
}