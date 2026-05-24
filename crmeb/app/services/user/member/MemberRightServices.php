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

use app\dao\user\MemberRightDao;
use app\services\BaseServices;
use crmeb\exceptions\AdminException;

/**
 * Class MemberRightServices
 * @package app\services\user
 */class MemberRightServices extends BaseServices
{
    /**
     * MemberCardServices constructor.
     * @param MemberRightDao $memberCardDao
     */    public function __construct(MemberRightDao $memberRightDao)
    {
        $this->dao = $memberRightDao;
    }

    /**
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getSearchList(array $where = [])
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getSearchList($where, $page, $limit);
        foreach ($list as &$item) {
            $item['image'] = set_file_url($item['image']);
        }
        $count = $this->dao->count($where);
        return compact('list', 'count');

    }

    /**
     * Chỉnh sửa và lưu
     * @param int $id
     * @param array $data
     */    public function save(int $id, array $data)
    {
        if (!$data['right_type']) throw new AdminException('Loại lợi ích dành cho thành viên bị thiếu');
        if (!$id) throw new AdminException('Lỗi tham số');
        if (!$data['title'] || !$data['show_title']) throw new AdminException('Vui lòng đặt tên sở thích');
        if (!$data['image']) throw new AdminException('Vui lòng tải lên các biểu tượng quyền thành viên');
        if (mb_strlen($data['show_title']) > 6) throw new AdminException('Tên hiển thị không thể dài hơn 6 ký tự');
        if (mb_strlen($data['explain']) > 8) throw new AdminException('Phần giới thiệu lợi ích không được dài quá 8 từ');
        switch ($data['right_type']) {
            case "integral":
                if (!$data['number']) throw new AdminException('Hãy đặt số điểm trả về bội số');
                if ($data['number'] < 0) throw new AdminException('bội số trả về của điểm không thể là số âm');
                $save['number'] = abs($data['number']);
                break;
            case "express" :
                if (!$data['number']) throw new AdminException('Vui lòng thiết lập giảm giá vận chuyển');
                if ($data['number'] < 0) throw new AdminException('Giảm giá vận chuyển không thể âm');
                $save['number'] = abs($data['number']);
                break;
            case "sign" :
                if (!$data['number']) throw new AdminException('Vui lòng đặt hệ số nhân điểm đăng ký');
                if ($data['number'] < 0) throw new AdminException('Hệ số điểm đăng nhập không được là số âm');
                $save['number'] = abs($data['number']);
                break;
            case "offline" :
                if (!$data['number']) throw new AdminException('Vui lòng thiết lập giảm giá thanh toán ngoại tuyến');
                if ($data['number'] < 0) throw new AdminException('Thanh toán ngoại tuyến không thể âm');
                $save['number'] = abs($data['number']);
        }
        $save['show_title'] = $data['show_title'];
        $save['image'] = $data['image'];
        $save['status'] = $data['status'];
        $save['sort'] = $data['sort'];
        //TODO $savekhông được sử dụng
        return $this->dao->update($id, $data);
    }

    /**
     * Nhận một thông tin duy nhất
     * @param array $where
     * @return array|bool|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getOne(array $where)
    {
        if (!$where) return false;
        return $this->dao->getOne($where);
    }

    /**
     * Kiểm tra xem một lợi ích nhất định có được kích hoạt hay không
     * @param $rightType
     * @return bool
     */    public function getMemberRightStatus($rightType)
    {
        if (!$rightType) return false;
        $status = $this->dao->value(['right_type' => $rightType], 'status');
        if ($status) return true;
        return false;
    }

}
