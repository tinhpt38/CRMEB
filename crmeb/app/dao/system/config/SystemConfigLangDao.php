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
use app\model\system\config\SystemConfigLang;

/**
 * 系统配置多语言
 * Class SystemConfigLangDao
 * @package app\dao\system\config
 */
class SystemConfigLangDao extends BaseDao
{
    protected function setModel(): string
    {
        return SystemConfigLang::class;
    }

    /**
     * 按 config_id + lang_type_id 取 bản dịch info, desc, parameter
     * @param array $configIds eb_system_config.id
     * @param int $langTypeId eb_lang_type.id (vd: 10 = vi-VN)
     * @return array [ config_id => ['info'=>,'desc'=>,'parameter'=>], ... ]
     */
    public function getByConfigIdsAndLang(array $configIds, int $langTypeId): array
    {
        if (empty($configIds)) {
            return [];
        }
        $list = $this->getModel()
            ->whereIn('config_id', $configIds)
            ->where('lang_type_id', $langTypeId)
            ->field('config_id,info,desc,parameter')
            ->select()
            ->toArray();
        $result = [];
        foreach ($list as $row) {
            $result[(int)$row['config_id']] = [
                'info' => $row['info'] ?? '',
                'desc' => $row['desc'] ?? '',
                'parameter' => $row['parameter'] ?? '',
            ];
        }
        return $result;
    }
}
