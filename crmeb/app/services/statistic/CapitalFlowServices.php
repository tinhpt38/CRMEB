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

namespace app\services\statistic;

use app\dao\system\statistics\CapitalFlowDao;
use app\services\BaseServices;
use app\services\pay\PayServices;
use crmeb\exceptions\AdminException;

class CapitalFlowServices extends BaseServices
{
    /**
     * @param CapitalFlowDao $dao
     */
    public function __construct(CapitalFlowDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Thêm dòng tiền
     * @param $orderInfo
     * @param string $type
     */
    public function setFlow($orderInfo, $type = '')
    {
        $data['flow_id'] = 'ZJ' . date('Ymdhis', time()) . rand('1000', '9999');
        switch ($type) {
            case 'order':
                $data['order_id'] = $orderInfo['order_id'];
                $data['uid'] = $orderInfo['uid'];
                $data['price'] = $orderInfo['pay_price'];
                $data['trading_type'] = 1;
                $data['pay_type'] = $orderInfo['pay_type'];
                break;
            case 'refund':
                $data['order_id'] = $orderInfo['order_id'];
                $data['uid'] = $orderInfo['uid'];
                $data['price'] = bcmul('-1', $orderInfo['refund_price'], 2);
                $data['trading_type'] = 2;
                $data['pay_type'] = $orderInfo['pay_type'];
                break;
            case 'recharge':
                $data['order_id'] = $orderInfo['order_id'];
                $data['uid'] = $orderInfo['uid'];
                $data['price'] = $orderInfo['price'];
                $data['trading_type'] = 3;
                $data['pay_type'] = $orderInfo['recharge_type'];
                break;
            case 'refund_recharge':
                $data['order_id'] = $orderInfo['order_id'];
                $data['uid'] = $orderInfo['uid'];
                $data['price'] = bcmul('-1', $orderInfo['price'], 2);
                $data['trading_type'] = 4;
                $data['pay_type'] = $orderInfo['recharge_type'];
                break;
            case 'luck':
                $data['order_id'] = $orderInfo['order_id'];
                $data['uid'] = $orderInfo['uid'];
                $data['price'] = $orderInfo['price'];
                $data['trading_type'] = 5;
                $data['pay_type'] = $orderInfo['pay_type'];
                break;
            case 'extract':
                $data['order_id'] = $orderInfo['order_id'];
                $data['uid'] = $orderInfo['uid'];
                $data['price'] = $orderInfo['price'];
                $data['trading_type'] = 6;
                $data['pay_type'] = $orderInfo['pay_type'];
                break;
            case 'pay_member':
                $data['order_id'] = $orderInfo['order_id'];
                $data['uid'] = $orderInfo['uid'];
                $data['price'] = $orderInfo['pay_price'];
                $data['trading_type'] = 7;
                $data['pay_type'] = $orderInfo['pay_type'];
                break;
            case 'offline_scan':
                $data['order_id'] = $orderInfo['order_id'];
                $data['uid'] = $orderInfo['uid'];
                $data['price'] = $orderInfo['pay_price'];
                $data['trading_type'] = 8;
                $data['pay_type'] = $orderInfo['pay_type'];
                break;
            default:
                break;
        }
        $data['nickname'] = $orderInfo['nickname'];
        $data['phone'] = $orderInfo['phone'];
        $data['add_time'] = time();
        $this->dao->save($data);
    }

    /**
     * Nhận dòng vốn
     * @param $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getFlowList($where)
    {
        $export = $where['export'] ?? 0;
        unset($where['export']);
        [$page, $limit] = $this->getPageValue();
        $status = ['tất cả', 'Thanh toán đơn hàng', 'Hoàn tiền đơn hàng', 'Lệnh nạp tiền', 'Nạp tiền và hoàn tiền', 'Phong bì đỏ xổ số', 'Rút tiền hoa hồng', 'Mua thành viên', 'Thu ngân ngoại tuyến'];
        $list = $this->dao->getList($where, $page, $limit);
        foreach ($list as &$item) {
            $item['add_time'] = date('Y-m-d H:i:s', $item['add_time']);
            $item['trading_type'] = $status[$item['trading_type']];
            $item['pay_type_name'] = PayServices::PAY_TYPE[$item['pay_type'] != 'routine' ? $item['pay_type'] : 'weixin'] ?? 'những cách khác';
        }
        $count = $this->dao->count($where);
        if ($export) {
            $fileKey = ['flow_id', 'order_id', 'nickname', 'phone', 'price', 'trading_type', 'pay_type_name', 'add_time', 'mark'];
            $header = ['Số giao dịch', 'Đơn hàng liên kết', 'người dùng', 'Điện thoại', 'Số lượng', 'Loại lệnh', 'Hình thức thanh toán', 'giờ giao dịch', 'Nhận xét'];
            $fileName = 'Xuất hóa đơn' . date('YmdHis') . rand(1000, 9999);
            return compact('list', 'fileKey', 'header', 'fileName');
        } else {
            return compact('list', 'count', 'status');
        }
    }

    /**
     * Thêm ghi chú
     * @param $id
     * @param $data
     * @return bool
     */
    public function setMark($id, $data)
    {
        $res = $this->dao->update($id, $data);
        if ($res) {
            return true;
        } else {
            throw new AdminException('Nhận xét không thành công');
        }
    }

    /**
     * Nhận hồ sơ thanh toán
     * @param $where
     * @return array
     */
    public function getFlowRecord($where)
    {
        [$page, $limit] = $this->getPageValue();
        $data = $this->dao->getRecordList($where, $page, $limit);
        $i = 1;
        foreach ($data['list'] as &$item) {
            $item['id'] = $i;
            $i++;
            $item['entry_price'] = bcadd($item['income_price'], $item['exp_price'], 2);
            switch ($where['type']) {
                case "day" :
                    $item['title'] = "hóa đơn hàng ngày";
                    $item['add_time'] = date('Y-m-d', $item['add_time']);
                    break;
                case "week" :
                    $item['title'] = "Hóa đơn hàng tuần";
                    $item['add_time'] = 'KHÔNG.' . $item['day'] . 'tuần(' . date('m', $item['add_time']) . 'mặt trăng)';
                    break;
                case "month" :
                    $item['title'] = "hóa đơn hàng tháng";
                    $item['add_time'] = date('Y-m', $item['add_time']);
                    break;
            }
        }
        return $data;
    }
}
