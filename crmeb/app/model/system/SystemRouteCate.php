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

class SystemRouteCate extends BaseModel
{

    /**
     * @var string
     */    protected $name = 'system_route_cate';

    /**
     * @var string
     */    protected $pk = 'id';

    protected $autoWriteTimestamp = false;

    /**
     * @return \think\model\relation\HasMany
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/4/7
     */    public function children()
    {
        return $this->hasMany(SystemRoute::class, 'cate_id', 'id')->field(['id', 'type', 'cate_id', 'name', 'name as real_name', 'path', 'method'])->order('add_time desc');
    }
}
