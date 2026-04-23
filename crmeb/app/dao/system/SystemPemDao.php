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
namespace app\dao\system;

use app\dao\BaseDao;
use app\model\system\SystemPem;

class SystemPemDao extends BaseDao
{
    protected function setModel(): string
    {
        return SystemPem::class;
    }

    public function savePem($data)
    {
        $info = $this->getModel()->where('name', $data['name'])->find();
        if ($info) {
            $info = $info->toArray();
            $this->getModel()->where('id', $info['id'])->update($data);
        } else {
            $data['add_time'] = time();
            $this->getModel()->save($data);
        }
        return true;
    }
}