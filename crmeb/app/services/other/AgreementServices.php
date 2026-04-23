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

namespace app\services\other;


use app\dao\other\AgreementDao;
use app\services\BaseServices;
use crmeb\exceptions\AdminException;

/**
 * Class AgreementServices
 * @package app\services\other
 */
class AgreementServices extends BaseServices
{

    public function __construct(AgreementDao $dao)
    {
        $this->dao = $dao;
    }

    /** Sửa đổi nội dung thỏa thuận
     * @param array $where
     * @param $content
     * @return bool|\crmeb\basic\BaseModel
     */
    public function saveAgreement(array $data, $id = 0)
    {
        if (!$data) return false;
        if (!isset($data['type']) || !$data['type'] || $data['type'] == 0) throw new AdminException('Loại giao thức bị thiếu');
        if (!isset($data['title']) || !$data['title']) throw new AdminException('Vui lòng điền tên thỏa thuận');
        if (!isset($data['content']) || !$data['content']) throw new AdminException('Vui lòng điền nội dung thỏa thuận');
        if (!$id) {
            $getOne = $this->getAgreementBytype($data['type']);
            if ($getOne) throw new AdminException('Loại thỏa thuận này đã tồn tại');
        }
        return $this->dao->saveAgreement($data, $id);
    }

    /**Nhận thỏa thuận thành viên
     * @param $type
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAgreementBytype($type)
    {
        if (!$type) return [];
        $data = $this->dao->getOne(['type' => $type]);
        return $data ? $data->toArray() : [];
    }
}
