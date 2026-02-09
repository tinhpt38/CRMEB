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

namespace app\dao\system;

use app\dao\BaseDao;
use app\model\system\SystemMenusLang;

/**
 * 菜单多语言
 * Class SystemMenusLangDao
 * @package app\dao\system
 */
class SystemMenusLangDao extends BaseDao
{
    protected function setModel(): string
    {
        return SystemMenusLang::class;
    }

    /**
     * 按 menu_id + lang_type_id 取 bản dịch menu_name, header, mark
     * @param array $menuIds eb_system_menus.id
     * @param int $langTypeId eb_lang_type.id (vd: 10 = vi-VN)
     * @return array [ menu_id => ['menu_name'=>,'header'=>,'mark'=>], ... ]
     */
    public function getByMenuIdsAndLang(array $menuIds, int $langTypeId): array
    {
        if (empty($menuIds)) {
            return [];
        }
        $list = $this->getModel()
            ->whereIn('menu_id', $menuIds)
            ->where('lang_type_id', $langTypeId)
            ->field('menu_id,menu_name,header,mark')
            ->select()
            ->toArray();
        $result = [];
        foreach ($list as $row) {
            $id = (int)($row['menu_id'] ?? 0);
            $result[$id] = [
                'menu_name' => $row['menu_name'] ?? '',
                'header' => $row['header'] ?? '',
                'mark' => $row['mark'] ?? '',
            ];
        }
        return $result;
    }
}
