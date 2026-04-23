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

namespace app\services\user\member;

use app\dao\user\MemberShipDao;
use app\services\BaseServices;
use crmeb\exceptions\AdminException;

/**
 * Class MemberShipServices
 * @package app\services\user
 */
class MemberShipServices extends BaseServices
{

    public function __construct(MemberShipDao $memberShipDao)
    {
        $this->dao = $memberShipDao;
    }

    /**Nhận loại thành viên trong nền
     * @param array $where
     * @return array
     */
    public function getSearchList(array $where = [])
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getSearchList($where, $page, $limit);
        $count = $this->dao->count($where);
        return compact('list', 'count');

    }

    /**Nhận giao diện api thẻ thành viên
     * @return mixed
     */
    public function getApiList(array $where)
    {
        return $this->dao->getApiList($where);
    }

    /** Lưu chỉnh sửa loại thẻ
     * @param int $id
     * @param array $data
     */
    public function save(int $id, array $data)
    {
        if (!$data['title']) throw new AdminException('Vui lòng điền tên thẻ thành viên');
        if (!$data['type']) throw new AdminException('Thiếu loại thẻ thành viên');
        if ($data['type'] == "ever") {
            $data['vip_day'] = -1;
        } else {
            if (!$data['vip_day']) throw new AdminException('Vui lòng điền thời hạn hiệu lực (ngày）');
            if ($data['vip_day'] < 0) throw new AdminException('Thời hạn hiệu lực (ngày) không thể âm');
        }
        if ($data['type'] == "free") {
            $data['pre_price'] = 0.00;
        } else {
            if ($data['pre_price'] == 0 || $data['price'] == 0) throw new AdminException('Vui lòng điền giá');
        }
        if ($data['pre_price'] < 0 || $data['price'] < 0) throw new AdminException('Giá không thể âm');
        if ($data['pre_price'] > $data['price']) throw new AdminException('Giá chiết khấu không thể lớn hơn giá gốc');
        if ($id){
            return $this->dao->update($id, $data);
        }else{
            return $this->dao->save($data);
        }

    }

    /**Nhận ngày làm thẻ thành viên
     * @param array $where
     * @return mixed
     */
    public function getVipDay(array $where)
    {
        return $this->dao->value($where, 'vip_day');
    }

    /**
     * Sửa đổi trạng thái loại thành viên
     * @param $id
     * @param $is_del
     * @return bool
     */
    public function setStatus($id, $is_del)
    {
        $res = $this->dao->update($id, ['is_del' => $is_del]);
        if ($res) return true;
        return false;
    }
}
