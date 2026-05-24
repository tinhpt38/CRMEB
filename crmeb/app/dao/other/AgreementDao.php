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

namespace app\dao\other;


use app\dao\BaseDao;
use app\model\other\Agreement;

/**
 * Class AgreementDao
 * @package app\dao\other
 */class AgreementDao extends BaseDao
{

    /**
     * @return string
     */    public function setModel(): string
    {
        return Agreement::class;
    }

    /**Sửa đổi Nội dung thỏa thuận
     * @param array $where
     * @param $agreement
     * @return bool|\crmeb\basic\BaseModel
     */    public function saveAgreement(array $agreement, $id = 0)
    {
        if (!$agreement) return false;
        $agreement['add_time'] = time();
        if($id){
            return $this->getModel()->update($agreement,['id' => $id]);
        }
        return $this->getModel()->save($agreement);
    }

}
