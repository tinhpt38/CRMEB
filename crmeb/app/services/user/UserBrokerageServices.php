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

namespace app\services\user;

use app\dao\user\UserBrokerageDao;
use app\services\BaseServices;
use crmeb\exceptions\ApiException;

/**
 * Hoa hồng người dùng
 * Class UserBrokerageServices
 * @package app\services\user
 * @method getUserFrozenPrice(int $uid) Nhận hoa hồng cố định của người dùng
 */
class UserBrokerageServices extends BaseServices
{
    /**
     * Mẫu hồ sơ người dùng
     * @var array[]
     */
    protected $incomeData = [
        'get_self_member_brokerage' => [
            'title' => 'Nhận hoa hồng thành viên trả phí khi tự mua hàng',
            'type' => 'self_member_brokerage',
            'mark' => 'Bạn đã tiêu thụ thành công{%pay_price%}Nhân dân tệ,Thưởng hoa hồng tự mua{%number%}',
            'status' => 1,
            'pm' => 1
        ],
        'get_member_brokerage' => [
            'title' => 'Kiếm hoa hồng thành viên trả phí khi mua hàng ở cấp độ thấp hơn',
            'type' => 'one_member_brokerage',
            'mark' => '{%nickname%}tiêu dùng thành công{%pay_price%}Nhân dân tệ,Hoa hồng khuyến mãi thưởng{%number%}',
            'status' => 1,
            'pm' => 1
        ],
        'get_two_member_brokerage' => [
            'title' => 'Nhận hoa hồng liên kết được trả tiền khi mua hàng thứ cấp',
            'type' => 'two_member_brokerage',
            'mark' => 'Nhà quảng bá cấp hai{%nickname%}tiêu dùng thành công{%pay_price%}Nhân dân tệ,Hoa hồng khuyến mãi thưởng{%number%}',
            'status' => 1,
            'pm' => 1
        ],
        'get_self_brokerage' => [
            'title' => 'Nhận hoa hồng cho đơn hàng tự mua',
            'type' => 'self_brokerage',
            'mark' => 'Bạn đã tiêu thụ thành công{%pay_price%}Nhân dân tệ,Thưởng hoa hồng tự mua{%number%}',
            'status' => 1,
            'pm' => 1
        ],
        'get_brokerage' => [
            'title' => 'Nhận hoa hồng cho các đơn hàng khuyến mãi cấp thấp hơn',
            'type' => 'one_brokerage',
            'mark' => '{%nickname%}tiêu dùng thành công{%pay_price%}Nhân dân tệ,Hoa hồng khuyến mãi thưởng{%number%}',
            'status' => 1,
            'pm' => 1
        ],
        'get_two_brokerage' => [
            'title' => 'Nhận hoa hồng cho các đơn hàng khuyến mãi thứ cấp',
            'type' => 'two_brokerage',
            'mark' => 'Nhà quảng bá cấp hai{%nickname%}tiêu dùng thành công{%pay_price%}Nhân dân tệ,Hoa hồng khuyến mãi thưởng{%number%}',
            'status' => 1,
            'pm' => 1
        ],
        'get_user_brokerage' => [
            'title' => 'Nhận hoa hồng khi quảng bá người dùng',
            'type' => 'brokerage_user',
            'mark' => 'Quảng bá người dùng thành công：{%nickname%},Hoa hồng khuyến mãi thưởng{%number%}',
            'status' => 1,
            'pm' => 1
        ],
        'extract' => [
            'title' => 'Rút tiền hoa hồng',
            'type' => 'extract',
            'mark' => '{%mark%}',
            'status' => 1,
            'pm' => 0
        ],
        'extract_fail' => [
            'title' => 'Rút tiền không thành công',
            'type' => 'extract_fail',
            'mark' => 'Rút tiền không thành công,Hoa hồng trả lại{%number%}Nhân dân tệ',
            'status' => 1,
            'pm' => 1
        ],
        'brokerage_to_nowMoney' => [
            'title' => 'Hoa hồng được rút về số dư',
            'type' => 'extract_money',
            'mark' => 'Hoa hồng được rút về số dư{%number%}Nhân dân tệ',
            'status' => 1,
            'pm' => 0
        ],
        'brokerage_refund' => [
            'title' => 'Hoa hồng hoàn tiền',
            'type' => 'refund',
            'mark' => 'Hoàn tiền đơn hàng trừ hoa hồng{%number%}Nhân dân tệ',
            'status' => 1,
            'pm' => 0
        ],
        'get_staff_brokerage' => [
            'title' => 'Nhận hoa hồng theo lệnh thăng tiến của nhân viên',
            'type' => 'staff_brokerage',
            'mark' => '{%nickname%}tiêu dùng thành công{%pay_price%}Nhân dân tệ,Hoa hồng khuyến mãi thưởng{%number%}',
            'status' => 1,
            'pm' => 1
        ],
        'get_agent_brokerage' => [
            'title' => 'Nhận hoa hồng từ đơn hàng khuyến mại của đại lý',
            'type' => 'agent_brokerage',
            'mark' => '{%nickname%}tiêu dùng thành công{%pay_price%}Nhân dân tệ,Hoa hồng khuyến mãi thưởng{%number%}',
            'status' => 1,
            'pm' => 1
        ],
        'get_division_brokerage' => [
            'title' => 'Nhận hoa hồng cho các đơn hàng khuyến mãi của bộ phận kinh doanh',
            'type' => 'division_brokerage',
            'mark' => '{%nickname%}tiêu dùng thành công{%pay_price%}Nhân dân tệ,Hoa hồng khuyến mãi thưởng{%number%}',
            'status' => 1,
            'pm' => 1
        ],
        'get_pink_master_brokerage' => [
            'title' => 'Nhận hoa hồng từ trưởng nhóm',
            'type' => 'pink_master_brokerage',
            'mark' => 'Nếu nhóm ra mắt thành công thì trưởng nhóm sẽ được thưởng hoa hồng{%number%}',
            'status' => 1,
            'pm' => 1
        ],
    ];


    /**
     * UserBrokerageServices constructor.
     * @param UserBrokerageDao $dao
     */
    public function __construct(UserBrokerageDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Viết hồ sơ hoa hồng
     * @param string $type viết kiểu
     * @param int $uid
     * @param int|string|array $number
     * @param int|string $balance
     * @param $linkId
     * @return bool|mixed
     */
    public function income(string $type, int $uid, $number, $balance, $linkId)
    {
        $data = $this->incomeData[$type] ?? null;
        if (!$data) {
            return true;
        }
        $data['uid'] = $uid;
        $data['balance'] = $balance ?? 0;
        $data['link_id'] = $linkId;
        if (is_array($number)) {
            $key = array_keys($number);
            $key = array_map(function ($item) {
                return '{%' . $item . '%}';
            }, $key);
            $value = array_values($number);
            $data['number'] = $number['number'] ?? 0;
            $data['frozen_time'] = $number['frozen_time'] ?? 0;
            $data['mark'] = str_replace($key, $value, $data['mark']);
        } else {
            $data['number'] = $number;
            $data['mark'] = str_replace(['{%number%}'], $number, $data['mark']);
        }
        $data['add_time'] = time();

        return $this->dao->save($data);
    }

    /**
     * Tổng hoa hồng của một người dùng nhất định
     * @param int $uid
     * @param array|string[] $type
     * @param string $time
     * @return float
     * @throws \ReflectionException
     */
    public function getUserBrokerageSum(int $uid, array $type = ['one_brokerage', 'two_brokerage', 'brokerage_user'], $time = '')
    {
        $where = ['uid' => $uid];
        if ($type) $where['type'] = $type;
        if ($time) $where['time'] = $time;
        return $this->dao->sum($where, 'number', true);
    }

    /**
     * Hoàn tiền hoa hồng
     * @param $order
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function orderRefundBrokerageBack($order)
    {
        $id = (int)$order['id'];
        $where = [
            'uid' => [$order['spread_uid'], $order['spread_two_uid'], $order['staff_id'], $order['agent_id'], $order['division_id']],
            'type' => ['self_brokerage', 'one_brokerage', 'two_brokerage', 'staff_brokerage', 'agent_brokerage', 'division_brokerage', 'pink_master_brokerage'],
            'link_id' => $id,
            'pm' => 1
        ];
        $brokerageList = $this->dao->getUserBrokerageList($where);
        //Đơn hàng phụ
        if (!$brokerageList && $order['pid']) {
            $where['link_id'] = $order['pid'];
            $p_brokerageList = $this->dao->getUserBrokerageList($where);
            //Đơn hàng chính đã được chia thành hoa hồng. Kết quả tính toán sẽ được khôi phục sau khi các đơn hàng con được chia theo thứ tự.
            if ($p_brokerageList) {
                $brokerageList = [
                    ['uid' => $order['spread_uid'], 'number' => $order['one_brokerage']],
                    ['uid' => $order['spread_two_uid'], 'number' => $order['two_brokerage']],
                ];
            }
        }
        $res = true;
        if ($brokerageList) {
            /** @var UserServices $userServices */
            $userServices = app()->make(UserServices::class);
            $brokerages = $userServices->getColumn([['uid', 'in', array_column($brokerageList, 'uid')]], 'brokerage_price', 'uid');
            $brokerageData = [];

            foreach ($brokerageList as $item) {
                if (!$item['uid']) continue;
                $usermoney = $brokerages[$item['uid']] ?? 0;
                if ($item['number'] > $usermoney) {
                    $item['number'] = $usermoney;
                }
                $res = $res && $userServices->bcDec($item['uid'], 'brokerage_price', (string)$item['number'], 'uid');
                $brokerageData[] = [
                    'title' => 'Hoa hồng hoàn tiền',
                    'uid' => $item['uid'],
                    'pm' => 0,
                    'add_time' => time(),
                    'type' => 'refund',
                    'number' => $item['number'],
                    'link_id' => $id,
                    'balance' => bcsub((string)$usermoney, (string)$item['number'], 2),
                    'mark' => 'Hoàn tiền đơn hàng trừ hoa hồng' . floatval($item['number']) . 'Nhân dân tệ'
                ];
            }
            if ($brokerageData) {
                $res = $res && $this->dao->saveAll($brokerageData);
            }
            //Sửa đổi thời gian đóng băng hoa hồng
            $this->dao->update($where, ['frozen_time' => 0]);
        }
        return $res;
    }

    /**
     * Xếp hạng hoa hồng
     * @param string $time
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function brokerageRankList(string $time = 'week')
    {
        $where = ['pm' => 1];
        if ($time) {
            $where['time'] = $time;
        }
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->brokerageRankList($where, $page, $limit);
        foreach ($list as $key => &$item) {
            if (!isset($item['user']) || !$item['user'] || $item['brokerage_price'] <= 0) {
                unset($list[$key]);
                continue;
            }
            $item['nickname'] = $item['user']['nickname'] ?? '';
            $item['avatar'] = $item['user']['avatar'] ?? '';
            if ($item['brokerage_price'] == '0.00' || $item['brokerage_price'] == 0 || !$item['brokerage_price']) {
                unset($list[$key]);
            }
            unset($item['user']);
        }
        return array_merge($list);
    }

    /**
     * Nhận xếp hạng người dùng
     * @param int $uid
     * @param string $time
     * @return false|int|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getUserBrokerageRank(int $uid, string $time = 'week')
    {
        $where = ['pm' => 1];
        if ($time) {
            $where['time'] = $time;
        }
        $list = $this->dao->brokerageRankList($where);
        foreach ($list as $key => &$item) {
            if (!isset($item['user']) || !$item['user'] || $item['brokerage_price'] <= 0) {
                unset($list[$key]);
            }
        }
        $position_tmp_one = array_column($list, 'uid');
        $position_tmp_two = array_column($list, 'brokerage_price', 'uid');
        if (!in_array($uid, $position_tmp_one)) {
            $position = 0;
        } else {
            if ($position_tmp_two[$uid] == 0.00) {
                $position = 0;
            } else {
                $position = array_search($uid, $position_tmp_one) + 1;
            }
        }
        return $position;
    }

    /**
     * Dữ liệu khuyến mãi Hoa hồng của ngày hôm qua Số tiền rút tích lũy Hoa hồng hiện tại
     * @param int $uid
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function commission(int $uid)
    {
        /** @var UserServices $userServices */
        $userServices = app()->make(UserServices::class);
        if (!$userServices->getUserInfo($uid)) {
            throw new ApiException('Dữ liệu không tồn tại');
        }
        /** @var UserExtractServices $userExtract */
        $userExtract = app()->make(UserExtractServices::class);
        $data = [];
        $data['uid'] = $uid;
        $data['pm'] = 1;
        $data['commissionSum'] = $this->getUsersBokerageSum($data);
        $extract_fail = $this->dao->sum(['uid' => $uid, 'pm' => 1, 'type' => 'extract_fail'], 'number');
        $data['commissionSum'] = bcadd($data['commissionSum'], $extract_fail, 2);
        $data['pm'] = 0;
        $data['commissionRefund'] = $this->getUsersBokerageSum($data);
        $data['commissionCount'] = $data['commissionSum'] > $data['commissionRefund'] ? bcsub((string)$data['commissionSum'], (string)$data['commissionRefund'], 2) : 0.00;
        $data['lastDayCount'] = $this->getUsersBokerageSum($data, 'yesterday');//hoa hồng của ngày hôm qua
        $data['extractCount'] = $userExtract->getUserExtract($uid);//Số tiền rút tích lũy
        return $data;
    }

    /**
     * Tính hoa hồng
     * @param array $where
     * @param int $time
     * @return mixed
     * @throws \ReflectionException
     */
    public function getUsersBokerageSum(array $where, $time = 0)
    {
        $where_data = [
            'status' => 1,
            'pm' => $where['pm'] ?? '',
            'uid' => $where['uid'] ?? '',
            'time' => $where['time'] ?? 0
        ];
        if ($time) $where_data['time'] = $time;
        return $this->dao->getBrokerageSumColumn($where_data);
    }

    /**
     * Chi tiết hoa hồng
     * @param $uid
     * @param $type
     * @return array
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getBrokerageList($uid, $type)
    {
        $where = [];
        $where['uid'] = $uid;
        [$page, $limit] = $this->getPageValue();
        if ($type == 4) {
            $where['type'] = ['extract', 'extract_money'];
        }
        if ($type == 3) {
            $where['not_type'] = ['extract_fail'];
        }
        /** @var UserExtractServices $userExtractService */
        $userExtractService = app()->make(UserExtractServices::class);
        $userExtract = $userExtractService->getColumn(['uid' => $uid], 'fail_msg,extract_type,state,wechat_order_id,status', 'id');
        $list = $this->dao->getList($where, '*', $page, $limit);
        $count = $this->dao->count($where);
        $times = [];
        if ($list) {
            foreach ($list as &$item) {
                $item['time'] = $item['time_key'] = $item['add_time'] ? date('Y-m', (int)$item['add_time']) : '';
                $item['add_time'] = $item['add_time'] ? date('Y-m-d H:i:s', (int)$item['add_time']) : '';
                $item['fail_msg'] = $item['type'] == 'extract_fail' ? $userExtract[$item['link_id']]['fail_msg'] : '';
                if ($type == 4) {
                    $extract_type = $userExtract[$item['link_id']]['extract_type'] ?? '';
                    if ($extract_type == 'alipay') {
                        $item['extract_type'] = 'Alipay';
                    } elseif ($extract_type == 'weixin') {
                        $item['extract_type'] = 'WeChat';
                    } elseif ($extract_type == 'bank') {
                        $item['extract_type'] = 'thẻ ngân hàng';
                    } else {
                        $item['extract_type'] = 'Sự cân bằng';
                    }
                    $item['state'] = $userExtract[$item['link_id']]['state'] ?? '';
                    $item['fail_msg'] = $userExtract[$item['link_id']]['fail_msg'] ?? '';
                    $item['extract_status'] = $userExtract[$item['link_id']]['status'] ?? '';
                    $item['wechat_order_id'] = $userExtract[$item['link_id']]['wechat_order_id'] ?? '';
                } else {
                    $item['fail_msg'] = $userExtract[$item['link_id']]['fail_msg'] ?? '';
                    $item['extract_status'] = $userExtract[$item['link_id']]['status'] ?? '';
                    $item['extract_type'] = '';
                }
                $item['is_frozen'] = $item['frozen_time'] > time() ? 1 : 0;
                $item['frozen_time'] = $item['frozen_time'] ? date('Y-m-d H:i:s', (int)$item['frozen_time']) : '';
            }
            $times = array_merge(array_unique(array_column($list, 'time_key')));
        }
        return ['list' => $list, 'time' => $times, 'count' => $count];
    }

    /**
     * Dữ liệu trang xếp hạng hoa hồng giao diện người dùng
     * @param int $uid
     * @param $type
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function brokerageRank(int $uid, $type)
    {
        /** @var UserServices $userService */
        $userService = app()->make(UserServices::class);
        if (!$userService->getUserInfo($uid)) {
            throw new ApiException('Dữ liệu không tồn tại');
        }
        return [
            'rank' => $this->brokerageRankList($type),
            'position' => $this->getUserBrokerageRank($uid, $type)
        ];
    }
}
