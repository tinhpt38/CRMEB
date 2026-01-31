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

namespace app\dao\system\config;

use app\dao\BaseDao;
use app\model\system\config\SystemConfigTabLang;

/**
 * 配置分类多语言标题
 * Class SystemConfigTabLangDao
 * @package app\dao\system\config
 */
class SystemConfigTabLangDao extends BaseDao
{
    protected function setModel(): string
    {
        return SystemConfigTabLang::class;
    }

    /**
     * 按 ngôn ngữ lấy map tab_id => title
     * @param int $langTypeId eb_lang_type.id (vd: 10 = vi-VN)
     * @return array [tab_id => title]
     */
    public function getTitlesByLangTypeId(int $langTypeId): array
    {
        $list = $this->getModel()->where('lang_type_id', $langTypeId)->column('title', 'tab_id');
        return $list ?: [];
    }
}
