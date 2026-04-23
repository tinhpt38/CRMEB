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

namespace app\dao\service;


use app\dao\other\AuxiliaryDao;

/**
 * Mẫu hỗ trợ dịch vụ khách hàng
 * Class StoreServiceAuxiliaryDao
 * @package app\dao\service
 */
class StoreServiceAuxiliaryDao extends AuxiliaryDao
{

    /**
     * tìm kiếm
     * @param array $where
     * @param bool $search
     * @return \crmeb\basic\BaseModel|mixed|\think\Model
     * @throws \ReflectionException
     */
    public function search(array $where = [], bool $search = false)
    {
        return parent::search($where, $search)->where('type', 0);
    }

}
