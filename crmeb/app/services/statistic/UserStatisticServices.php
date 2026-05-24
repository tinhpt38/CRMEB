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

use app\services\BaseServices;
use app\services\other\export\ExportServices;
use app\services\order\OtherOrderServices;
use app\services\order\StoreOrderServices;
use app\services\user\UserRechargeServices;
use app\services\user\UserServices;
use app\services\user\UserVisitServices;
use app\services\user\UserWechatuserServices;
use app\services\wechat\WechatUserServices;
use crmeb\exceptions\AdminException;

/**
 * Class UserStatisticServices
 * @package app\services\statistic
 */class UserStatisticServices extends BaseServices
{
    /**
     * Tổng quan cơ bản
     * @param $where
     * @return mixed
     */    public function getBasic($where)
    {
        $time = explode('-', $where['time']);
        if (count($time) != 2) throw new AdminException('Vui lòng chọn thời gian');
        /** @var UserVisitServices $userVisit */        $userVisit = app()->make(UserVisitServices::class);
        /** @var UserServices $user */        $user = app()->make(UserServices::class);
        /** @var StoreOrderServices $order */        $order = app()->make(StoreOrderServices::class);
        /** @var OtherOrderServices $otherOrder */        $otherOrder = app()->make(OtherOrderServices::class);

        $toEndTime = implode('-', [0, $time[1]]);
        $cumulativeUserWhere = ['time' => $toEndTime, 'user_type' => $where['channel_type']];

        $now['people'] = $userVisit->getDistinctCount($where, 'uid');//Số lượng khách truy cập
        $now['browse'] = $userVisit->count($where);//lượt truy cập
        $now['newUser'] = $user->count($where + ['user_type' => $where['channel_type']]);//Số lượng Khách hàng mới
        $now['payPeople'] = $order->getDistinctCount($where + ['paid' => 1], 'uid');//Số lượng Khách hàng đã thực hiện giao dịch
        $now['payUser'] = $otherOrder->getDistinctCount($where + ['member_type' => -1], 'uid');//Kích hoạt thành viên trả phí
        $now['cumulativeUser'] = $user->count($cumulativeUserWhere);//Số lượng Khách hàng tích lũy


        $dayNum = (strtotime($time[1]) - strtotime($time[0])) / 86400 + 1;
        $lastTime = [
            date("Y/m/d", strtotime("-$dayNum days", strtotime($time[0]))),
            date("Y/m/d", strtotime("-1 days", strtotime($time[0])))
        ];
        $where['time'] = implode('-', $lastTime);
        $toEndTime = implode('-', [0, $lastTime[1]]);
        $last['people'] = $userVisit->getDistinctCount($where, 'uid');//Số lượng khách truy cập
        $last['browse'] = $userVisit->count($where);//lượt truy cập
        $last['newUser'] = $user->count($where + ['user_type' => $where['channel_type']]);//Số lượng Khách hàng mới
        $last['payPeople'] = $order->getDistinctCount($where + ['paid' => 1], 'uid');//Số lượng Khách hàng đã thực hiện giao dịch
        $last['payUser'] = $otherOrder->getDistinctCount($where + ['member_type' => -1], 'uid');//Kích hoạt thành viên trả phí
        $cumulativeUserWhere['time'] = $toEndTime;
        $last['cumulativeUser'] = $user->count($cumulativeUserWhere);//Số lượng Khách hàng tích lũy

        //Kết hợp dữ liệu và tính tỷ lệ chuỗi
        $data = [];
        foreach ($now as $key => $item) {
            $data[$key]['num'] = $item;
            $data[$key]['last_num'] = $last[$key];
            $num = $last[$key] > 0 ? $last[$key] : 1;
            $data[$key]['percent'] = bcmul((string)bcdiv((string)($item - $last[$key]), (string)$num, 4), 100, 2);
        }
        return $data;
    }

    /**
     * Xu hướng Khách hàng
     * @param $where
     * @param $excel
     * @return mixed
     */    public function getTrend($where, $excel = false)
    {
        $time = explode('-', $where['time']);
        $channelType = $where['channel_type'];
        if (count($time) != 2) throw new AdminException('Lỗi tham số');
        $dayCount = bcadd(bcdiv(bcsub(strtotime($time[1]), strtotime($time[0])), '86400'), '1');
        $data = [];
        if ($dayCount == 1) {
            $data = $this->trend($time, $channelType, 0, $excel);
        } elseif ($dayCount > 1 && $dayCount <= 31) {
            $data = $this->trend($time, $channelType, 1, $excel);
        } elseif ($dayCount > 31 && $dayCount <= 92) {
            $data = $this->trend($time, $channelType, 3, $excel);
        } elseif ($dayCount > 92) {
            $data = $this->trend($time, $channelType, 30, $excel);
        }
        return $data;
    }

    /**
     * Xu hướng Khách hàng
     * @param $time
     * @param $channelType
     * @param $num
     * @param $excel
     * @return array
     */    public function trend($time, $channelType, $num, $excel)
    {
        /** @var UserServices $user */        $user = app()->make(UserServices::class);
        /** @var UserVisitServices $userVisit */        $userVisit = app()->make(UserVisitServices::class);
        /** @var StoreOrderServices $order */        $order = app()->make(StoreOrderServices::class);
        /** @var OtherOrderServices $otherOrder */        $otherOrder = app()->make(OtherOrderServices::class);

        $newPeople = $visitPeople = $paidPeople = $rechargePeople = $vipPeople = [];
        $newPeople['name'] = 'Số lượng Khách hàng mới';
        $visitPeople['name'] = 'Số lượng khách truy cập';
        $paidPeople['name'] = 'Số lượng Khách hàng đã thực hiện giao dịch';
        $rechargePeople['name'] = 'Nạp tiền cho Khách hàng';
        $vipPeople['name'] = 'Số lượng Khách hàng trả phí mới';
        if ($num == 0) {
            $xAxis = ['00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'];
            $timeType = '%H';
        } elseif ($num != 0) {
            $dt_start = strtotime($time[0]);
            $dt_end = strtotime($time[1]);
            while ($dt_start <= $dt_end) {
                if ($num == 30) {
                    $xAxis[] = date('Y-m', $dt_start);
                    $dt_start = strtotime("+1 month", $dt_start);
                    $timeType = '%Y-%m';
                } else {
                    $xAxis[] = date('m-d', $dt_start);
                    $dt_start = strtotime("+$num day", $dt_start);
                    $timeType = '%m-%d';
                }
            }
        }
        $visitPeople = array_column($userVisit->getTrendData($time, $channelType, $timeType, 'count(distinct(uid))'), 'num', 'days');
        $newPeople = array_column($user->getTrendData($time, $channelType, $timeType), 'num', 'days');
        $paidPeople = array_column($order->getTrendData($time, $channelType, $timeType, 'count(distinct(uid))'), 'num', 'days');
        $visitNum = array_column($userVisit->getTrendData($time, $channelType, $timeType, 'count(id)'), 'num', 'days');
        $vipPeople = array_column($otherOrder->getTrendData($time, $channelType, $timeType), 'num', 'days');
        if ($excel) {
            $data = [];
            $browsePeople = array_column($userVisit->getTrendData($time, $channelType, $timeType, 'count(id)'), 'num', 'days');
            foreach ($xAxis as &$item) {
                $data[] = [
                    'time' => $item,
                    'user' => $visitPeople[$item] ?? 0,
                    'browse' => $browsePeople[$item] ?? 0,
                    'new' => $newPeople[$item] ?? 0,
                    'paid' => $paidPeople[$item] ?? 0,
                    'vip' => $vipPeople[$item] ?? 0,
                ];
            }
            /** @var ExportServices $exportService */            $exportService = app()->make(ExportServices::class);
            $url = $exportService->userTrade($data);
            return compact('url');
        } else {
            $data = $series = [];
            foreach ($xAxis as $item) {
                $data['Số lượng Khách hàng mới'][] = isset($newPeople[$item]) ? intval($newPeople[$item]) : 0;
                $data['Số lượng khách truy cập'][] = isset($visitPeople[$item]) ? intval($visitPeople[$item]) : 0;
                $data['Lượt xem'][] = isset($visitNum[$item]) ? intval($visitNum[$item]) : 0;
                $data['Số lượng Khách hàng đã thực hiện giao dịch'][] = isset($paidPeople[$item]) ? intval($paidPeople[$item]) : 0;
                $data['Số lượng Khách hàng trả phí mới'][] = isset($vipPeople[$item]) ? intval($vipPeople[$item]) : 0;
            }
            foreach ($data as $key => $item) {
                $series[] = ['name' => $key, 'value' => $item];
            }
            return compact('xAxis', 'series');
        }
    }

    /**
     * Thông tin Khách hàng WeChat
     * @param $where
     * @return array
     */    public function getWechat($where)
    {
        $time = explode('-', $where['time']);
        if (count($time) != 2) throw new AdminException('Lỗi tham số');
        /** @var WechatUserServices $user */        $user = app()->make(WechatUserServices::class);

        $now['subscribe'] = $user->getCount([
            ['subscribe', '=', 1],
            ['user_type', '=', 'wechat'],
            ['subscribe_time', '>=', strtotime($time[0])],
            ['subscribe_time', '<', strtotime($time[1])]
        ]);
        $now['unSubscribe'] = $user->getCount([
            ['subscribe', '=', 0],
            ['user_type', '=', 'wechat'],
            ['subscribe_time', '<>', ''],
            ['subscribe_time', '>=', strtotime($time[0])],
            ['subscribe_time', '<', strtotime($time[1])]
        ]);
        $now['increaseSubscribe'] = $now['subscribe'] - $now['unSubscribe'];
        $now['cumulativeSubscribe'] = $user->getCount([['subscribe', '=', 1], ['user_type', '=', 'wechat']]);
        $now['cumulativeUnSubscribe'] = $user->getCount([
            ['subscribe', '=', 0],
            ['user_type', '=', 'wechat'],
            ['subscribe_time', '<>', '']
        ]);

        $dayNum = bcadd(bcdiv(bcsub(strtotime($time[1]), strtotime($time[0])), '86400'), '1');
        $lastTime = array(
            date("Y/m/d", strtotime("-$dayNum days", strtotime($time[0]))),
            date("Y/m/d", strtotime("-1 days", strtotime($time[0])))
        );
        $last['subscribe'] = $user->getCount([
            ['subscribe', '=', 1],
            ['user_type', '=', 'wechat'],
            ['subscribe_time', '>=', strtotime($lastTime[0])],
            ['subscribe_time', '<', strtotime($lastTime[1])]
        ]);
        $last['unSubscribe'] = $user->getCount([
            ['subscribe', '=', 0],
            ['user_type', '=', 'wechat'],
            ['subscribe_time', '<>', ''],
            ['subscribe_time', '>=', strtotime($lastTime[0])],
            ['subscribe_time', '<', strtotime($lastTime[1])]
        ]);
        $last['increaseSubscribe'] = $last['subscribe'] - $last['unSubscribe'];
        $last['cumulativeSubscribe'] = $user->getCount([['subscribe', '=', 1], ['user_type', '=', 'wechat']]);
        $last['cumulativeUnSubscribe'] = $user->getCount([
            ['subscribe', '=', 0],
            ['user_type', '=', 'wechat'],
            ['subscribe_time', '<>', '']
        ]);

        //Kết hợp dữ liệu và tính toán tỷ lệ chuỗi
        $data = [];
        foreach ($now as $key => $item) {
            $data[$key]['num'] = $item;
            $num = $last[$key] ?: 1;
            $data[$key]['percent'] = bcmul(bcdiv(($item - $last[$key]), $num, 4), 100, 2);
        }
        return $data;
    }

    /**
     * Xu hướng Khách hàng WeChat
     * @param $where
     * @return array
     */    public function getWechatTrend($where)
    {
        $time = explode('-', $where['time']);
        if (count($time) != 2) throw new AdminException('Lỗi tham số');
        $dayCount = bcadd(bcdiv(bcsub(strtotime($time[1]), strtotime($time[0])), '86400'), '1');
        $data = [];
        if ($dayCount == 1) {
            $data = $this->wechatTrend($time, 0);
        } elseif ($dayCount > 1 && $dayCount <= 31) {
            $data = $this->wechatTrend($time, 1);
        } elseif ($dayCount > 31 && $dayCount <= 92) {
            $data = $this->wechatTrend($time, 3);
        } elseif ($dayCount > 92) {
            $data = $this->wechatTrend($time, 30);
        }
        return $data;
    }

    /**
     * Xu hướng Khách hàng WeChat
     * @param $time
     * @param $num
     * @return array
     */    public function wechatTrend($time, $num)
    {
        /** @var WechatUserServices $user */        $user = app()->make(WechatUserServices::class);

        $subscribe = $unSubscribe = $increaseSubscribe = $cumulativeSubscribe = $cumulativeUnSubscribe = [];
        if ($num == 0) {
            $xAxis = ['00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'];
            $subscribe = array_column($user->getWechatTrendData($time, [
                ['subscribe', '=', 1],
                ['subscribe_time', '>=', strtotime($time[0])],
                ['subscribe_time', '<', strtotime($time[1])]
            ], '%H', 'subscribe'), 'subscribe', 'days');
            $unSubscribe = array_column($user->getWechatTrendData($time, [
                ['subscribe', '=', 0],
                ['subscribe_time', '<>', ''],
                ['subscribe_time', '>=', strtotime($time[0])],
                ['subscribe_time', '<', strtotime($time[1])]
            ], '%H', 'unSubscribe'), 'unSubscribe', 'days');
            $cumulativeSubscribe = array_column($user->getWechatTrendData($time, [
                ['subscribe', '=', 1]
            ], '%H', 'cumulativeSubscribe'), 'cumulativeSubscribe', 'days');
            $cumulativeUnSubscribe = array_column($user->getWechatTrendData($time, [
                ['subscribe', '=', 0], ['subscribe_time', '<>', '']
            ], '%H', 'cumulativeUnSubscribe'), 'cumulativeUnSubscribe', 'days');
        } elseif ($num != 0) {
            $dt_start = strtotime($time[0]);
            $dt_end = strtotime($time[1]);
            $timeType = '%m-%d';
            while ($dt_start <= $dt_end) {
                if ($num == 30) {
                    $xAxis[] = date('Y-m', $dt_start);
                    $dt_start = strtotime("+1 month", $dt_start);
                    $timeType = '%Y-%m';
                } else {
                    $xAxis[] = date('m-d', $dt_start);
                    $dt_start = strtotime("+$num day", $dt_start);
                    $timeType = '%m-%d';
                }
            }
            $subscribe = array_column($user->getWechatTrendData($time, [
                ['subscribe', '=', 1],
                ['subscribe_time', '>=', strtotime($time[0])],
                ['subscribe_time', '<', strtotime($time[1])]
            ], $timeType, 'subscribe'), 'subscribe', 'days');
            $unSubscribe = array_column($user->getWechatTrendData($time, [
                ['subscribe', '=', 0],
                ['subscribe_time', '<>', ''],
                ['subscribe_time', '>=', strtotime($time[0])],
                ['subscribe_time', '<', strtotime($time[1])]
            ], $timeType, 'unSubscribe'), 'unSubscribe', 'days');
            $cumulativeSubscribe = array_column($user->getWechatTrendData($time, [
                ['subscribe', '=', 1]
            ], $timeType, 'cumulativeSubscribe'), 'cumulativeSubscribe', 'days');
            $cumulativeUnSubscribe = array_column($user->getWechatTrendData($time, [
                ['subscribe', '=', 0], ['subscribe_time', '<>', '']
            ], $timeType, 'cumulativeUnSubscribe'), 'cumulativeUnSubscribe', 'days');
        }
        $data = $series = [];
        foreach ($xAxis as $item) {
            $data['Thêm khách hàng mới theo dõi'][] = $subscribe[$item] ?? 0;
            $data['Thêm khách hàng mới được bỏ chặn'][] = $unSubscribe[$item] ?? 0;
            $data['Người dùng theo dõi tích lũy'][] = $cumulativeSubscribe[$item] ?? 0;
            $data['Tích lũy Khách hàng được bỏ chặn'][] = $cumulativeUnSubscribe[$item] ?? 0;
        }
        foreach ($data['Thêm khách hàng mới theo dõi'] as $keys => $items) {
            $data['Người dùng được thêm ròng'][] = $data['Thêm khách hàng mới theo dõi'][$keys] - $data['Thêm khách hàng mới được bỏ chặn'][$keys];
        }
        foreach ($data as $key => $item) {
            $series[] = ['name' => $key, 'value' => $item];
        }
        return compact('xAxis', 'series');
    }

    /**
     * Biểu đồ khu vực Khách hàng
     * @param $where
     * @return array
     */    public function getRegion($where)
    {
        $time = explode('-', $where['time']);
        $channelType = $where['channel_type'];
        if (count($time) != 2) throw new AdminException('Lỗi tham số');

        /** @var UserVisitServices $userVisit */        $userVisit = app()->make(UserVisitServices::class);
        /** @var UserServices $userService */        $userService = app()->make(UserServices::class);
        /** @var StoreOrderServices $order */        $order = app()->make(StoreOrderServices::class);
        /** @var WechatUserServices $user */        $wechatUser = app()->make(WechatUserServices::class);

        $all = $wechatUser->getRegionAll($time, $channelType);
        $new = $wechatUser->getRegionNew($time, $channelType);
        $visit = $userVisit->getRegion($time, $channelType);
        $payPrice = $order->getRegion($time, $channelType);
        foreach ($all as $key1 => $item1) {
            foreach ($new as $key2 => $item2) {
                if ($item1['province'] == $item2['province']) {
                    $all[$key1]['newNum'] = $item2['newNum'];
                    unset($new[$key2]);
                }
            }
        }
        $all = array_merge($all, $new);
        foreach ($all as $key1 => $item1) {
            foreach ($visit as $key3 => $item3) {
                if ($item1['province'] == $item3['province']) {
                    $all[$key1]['visitNum'] = $item3['visitNum'];
                    unset($visit[$key3]);
                }
            }
        }
        $all = array_merge($all, $visit);
        foreach ($all as $key1 => $item1) {
            foreach ($payPrice as $key3 => $item3) {
                if ($item1['province'] == $item3['province']) {
                    $all[$key1]['payPrice'] = $item3['payPrice'];
                    unset($payPrice[$key3]);
                }
            }
        }
        $all = array_merge($all, $payPrice);
        foreach ($all as &$item) {
            if ($item['province'] == '') $item['province'] = 'không rõ';
            if (!isset($item['allNum'])) $item['allNum'] = 0;
            if (!isset($item['newNum'])) $item['newNum'] = 0;
            if (!isset($item['visitNum'])) $item['visitNum'] = 0;
            if (!isset($item['payPrice'])) {
                $item['payPrice'] = 0;
            } else {
                $item['payPrice'] = floatval($item['payPrice']);
            }
        }
        $data = array_values($all);
        $last_names = array_column($data, $where['sort']);
        array_multisort($last_names, SORT_DESC, $data);
        return $data;
    }

    /**
     * Giới tính Khách hàng
     * @param $where
     * @return mixed
     */    public function getSex($where)
    {
        $time = explode('-', $where['time']);
        $channelType = $where['channel_type'];
        if (count($time) != 2) throw new AdminException('Lỗi tham số');

        /** @var UserWechatuserServices $user */        $wechatUser = app()->make(UserWechatuserServices::class);

        $data = $wechatUser->getSex($time, $channelType);
        $oneData = [
            ['value' => 0, 'name' => 'không rõ', 'name_key' => 0],
            ['value' => 0, 'name' => 'Nam', 'name_key' => 1],
            ['value' => 0, 'name' => 'nữ giới', 'name_key' => 2],
        ];
        foreach ($oneData as &$value) {
            foreach ($data as $item) {
                if ($item['name'] == $value['name_key']) {
                    $value['value'] = $item['value'];
                    break;
                }
            }
        }
        return $oneData;
    }
}
