<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

namespace app\model\system;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * 菜单多语言（menu_name, header, mark）
 * Class SystemMenusLang
 * @package app\model\system
 */
class SystemMenusLang extends BaseModel
{
    use ModelTrait;

    protected $pk = 'id';
    protected $name = 'system_menus_lang';

    /**
     * lang_type_id 搜索器
     * @param Model $query
     * @param $value
     */
    public function searchLangTypeIdAttr($query, $value)
    {
        if ($value !== '' && $value !== null) {
            $query->where('lang_type_id', $value);
        }
    }
}
