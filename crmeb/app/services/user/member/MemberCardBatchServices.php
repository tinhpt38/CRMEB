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


use app\dao\user\MemberCardBatchDao;
use app\services\BaseServices;
use crmeb\exceptions\AdminException;
use think\App;

class MemberCardBatchServices extends BaseServices
{
    /**
     * Khởi tạo và lấy phần xử lý lớp dao
     * MemberCardServices constructor.
     * @param MemberCardBatchDao $memberCardDao
     */
    public function __construct(MemberCardBatchDao $memberCardBatchDao)
    {
        $this->dao = $memberCardBatchDao;
    }

    /**
     * Lấy danh sách lô thẻ thành viên
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList(array $where = [])
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getList($where, $page, $limit);

        if ($list) {
            foreach ($list as &$v) {
                $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                //$v['qrcode'] = json_decode($v['qrcode'], true);
            }
        }
        $count = $this->dao->count($where);
        return compact('list', 'count');
    }

    /**
     * @param int $id
     * @param array $data
     */
    public function save(int $id, array $data)
    {
        if (!$data['title']) throw new AdminException('Vui lòng điền tên lô');
        if (!$data['total_num']) throw new AdminException('Vui lòng điền số lượng thẻ cần tạo');
        if (!is_numeric($data['total_num']) || $data['total_num'] < 0) throw new AdminException('Số lượng thẻ chỉ có thể là số nguyên dương');
        if ($data['total_num'] > 6000) throw new AdminException('Số lượng thẻ in tối đa trong một lần không vượt quá 6.000.');
        if (!$data['use_day'] || !is_numeric($data['use_day'])) throw new AdminException('Hãy điền số ngày rảnh rỗi');
        if ($data['use_day'] < 0) throw new AdminException('Số ngày sử dụng miễn phí chỉ được là số nguyên dương');
        /**
         * Dùng thử trong một khoảng thời gian cụ thể, doanh nghiệp cần được mở
         */
//        $use_start_time = strtotime($data['use_start_time']);
//        $use_end_time = strtotime($data['use_end_time']);
//        if (!$use_start_time) {
//            $use_start_time = strtotime(date('Y-m-d 00:00:00', strtotime('+1 day')));
//        } else {
//            $use_start_time = strtotime($data['use_start_time']);
//        }
//        if (!$use_end_time) {
//            $use_end_time = strtotime(date('Y-m-d 23:59:59', strtotime('+1 day')));
//        } else {
//            $use_end_time = strtotime($data['use_end_time']);
//        }
//        if ($use_end_time < time()) throw new AdminException("Thời gian kết thúc trải nghiệm không được ít hơn ngày hiện tại");
//        if ($use_end_time < $use_start_time) throw new AdminException("Thời gian kết thúc trải nghiệm không được ít hơn thời gian bắt đầu trải nghiệm");
//        $data['use_start_time'] = $use_start_time;
//        $data['use_end_time'] = $use_end_time;
        $data['use_day'] = abs(ceil($data['use_day']));
        $data['total_num'] = abs(ceil($data['total_num']));
        $data['add_time'] = time();
        $this->transaction(function () use ($id, $data) {
            if ($id) {
                unset($data['total_num']);
                $data['update_time'] = time();
                return $this->dao->update($id, $data);
                //return ['status' => 1, "msg" => "Chỉnh sửa thẻ hàng loạt thành công"];
            } else {
                /** @var MemberCardServices $memberCardService */
                $memberCardService = app()->make(MemberCardServices::class);
                $res = $this->dao->save($data);
                $add_card['card_batch_id'] = $res->id;
                $add_card['total_num'] = $data['total_num'];
                return $memberCardService->addCard($add_card);
                // return ['status' => 2, "msg" => "Đã tạo thẻ lô thành công"];
            }
        });
    }

    /**
     * Liệt kê các thao tác
     * @param int $id
     * @param array $data
     */
    public function setValue(int $id, array $data)
    {
        if (!is_numeric($id) || !$id) throw new AdminException('Lỗi tham số');
        if (!isset($data['field']) || !isset($data['value']) || !$data['field']) throw new AdminException('Lỗi tham số');
        $this->dao->update($id, [$data['field'] => $data['value']]);
        app()->make(MemberCardServices::class)->update(['card_batch_id' => $id], ['status' => $data['value']]);
    }


    /**
     * Nhận tài nguyên hàng loạt thẻ đơn
     * @param array $uid
     * @param string $field
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getOne(int $bid, $field = '*')
    {
        if (is_string($field)) $field = explode(',', $field);
        return $this->dao->get($bid, $field);
    }

    /**
     * Thống kê số lượng thẻ theo lô
     * @param int $id
     * @param string $field
     * @param int $inc
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function useCardSetInc(int $id, string $field, int $inc = 1)
    {
        return $this->dao->bcInc($id, $field, $inc);
    }

}
