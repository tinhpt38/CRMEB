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
declare (strict_types=1);

namespace app\services\user;

use app\dao\user\UserRechargeDao;
use app\services\BaseServices;
use app\services\order\StoreOrderCreateServices;
use app\services\pay\PayServices;
use app\services\pay\RechargeServices;
use app\services\statistic\CapitalFlowServices;
use app\services\system\config\SystemGroupDataServices;
use app\services\wechat\WechatUserServices;
use crmeb\exceptions\AdminException;
use crmeb\exceptions\ApiException;
use crmeb\services\FormBuilder as Form;
use crmeb\services\pay\Pay;
use think\facade\Route as Url;

/**
 *
 * Class UserRechargeServices
 * @package app\services\user
 * @method be($map, string $field = '') Truy vấn xem một phần dữ liệu có tồn tại không
 * @method getDistinctCount(array $where, $field, ?bool $search = true)
 * @method getTrendData($time, $type, $timeType)
 */
class UserRechargeServices extends BaseServices
{

    /**
     * UserRechargeServices constructor.
     * @param UserRechargeDao $dao
     */
    public function __construct(UserRechargeDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Nhận một phần dữ liệu
     * @param int $id
     * @param array $field
     */
    public function getRecharge(int $id, array $field = [])
    {
        return $this->dao->get($id, $field);
    }

    /**
     * Nhận số liệu thống kê
     * @param array $where
     * @param string $field
     * @return float
     */
    public function getRechargeSum(array $where, string $field = '')
    {
        $whereData = [];
        if (isset($where['data'])) {
            $whereData['time'] = $where['data'];
        }
        if (isset($where['paid']) && $where['paid'] != '') {
            $whereData['paid'] = $where['paid'];
        }
        if (isset($where['nickname']) && $where['nickname']) {
            $whereData['like'] = $where['nickname'];
        }
        if (isset($where['recharge_type']) && $where['recharge_type']) {
            $whereData['recharge_type'] = $where['recharge_type'];
        }
        return $this->dao->getWhereSumField($whereData, $field);
    }

    /**
     * Nhận danh sách nạp tiền
     * @param array $where
     * @param string $field
     * @return array
     */
    public function getRechargeList(array $where, string $field = '*', $is_page = true)
    {
        $whereData = [];
        if (isset($where['data'])) {
            $whereData['time'] = $where['data'];
        }
        if (isset($where['paid']) && $where['paid'] != '') {
            $whereData['paid'] = $where['paid'];
        }
        if (isset($where['nickname']) && $where['nickname']) {
            $whereData['like'] = $where['nickname'];
        }
        [$page, $limit] = $this->getPageValue($is_page);
        $list = $this->dao->getList($whereData, $field, $page, $limit);
        $count = $this->dao->count($whereData);

        foreach ($list as &$item) {
            switch ($item['recharge_type']) {
                case PayServices::WEIXIN_PAY:
                    $item['_recharge_type'] = 'nạp tiền WeChat';
                    break;
                case 'system':
                    $item['_recharge_type'] = 'Nạp tiền hệ thống';
                    break;
                case PayServices::ALIAPY_PAY:
                    $item['_recharge_type'] = 'nạp tiền Alipay';
                    break;
                default:
                    $item['_recharge_type'] = 'Nạp tiền khác';
                    break;
            }
            $item['_pay_time'] = $item['pay_time'] ? date('Y-m-d H:i:s', $item['pay_time']) : 'Chưa có';
            $item['_add_time'] = $item['add_time'] ? date('Y-m-d H:i:s', $item['add_time']) : 'Chưa có';
            $item['paid_type'] = $item['paid'] ? 'trả' : 'Chưa thanh toán';
            $item['avatar'] = strpos($item['avatar'] ?? '', 'http') === false ? (sys_config('site_url') . $item['avatar']) : $item['avatar'];
            unset($item['user']);
        }
        return compact('list', 'count');
    }

    /**
     * Nhận dữ liệu nạp tiền của người dùng
     * @return array
     */
    public function user_recharge(array $where)
    {
        $data = [];
        $data['sumPrice'] = $this->getRechargeSum($where, 'price');
        $data['sumRefundPrice'] = $this->getRechargeSum($where, 'refund_price');
        $where['recharge_type'] = 'alipay';
        $data['sumAlipayPrice'] = $this->getRechargeSum($where, 'price');
        $where['recharge_type'] = 'weixin';
        $data['sumWeixinPrice'] = $this->getRechargeSum($where, 'price');
        return [
            [
                'name' => 'Tổng số tiền nạp lại',
                'field' => 'Nhân dân tệ',
                'count' => $data['sumPrice'],
                'className' => 'iconjiaoyijine',
                'col' => 6,
            ],
            [
                'name' => 'Nạp lại số tiền hoàn lại',
                'field' => 'Nhân dân tệ',
                'count' => $data['sumRefundPrice'],
                'className' => 'iconshangpintuikuanjine',
                'col' => 6,
            ],
            [
                'name' => 'Số tiền nạp Alipay',
                'field' => 'Nhân dân tệ',
                'count' => $data['sumAlipayPrice'],
                'className' => 'iconzhifubao',
                'col' => 6,
            ],
            [
                'name' => 'Số tiền nạp WeChat',
                'field' => 'Nhân dân tệ',
                'count' => $data['sumWeixinPrice'],
                'className' => 'iconweixinzhifu',
                'col' => 6,
            ],
        ];
    }

    /**
     * Hình thức hoàn tiền
     * @param int $id
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2023/03/24
     */
    public function refund_edit(int $id)
    {
        $UserRecharge = $this->getRecharge($id);
        if (!$UserRecharge) {
            throw new AdminException('Dữ liệu không tồn tại');
        }
        if ($UserRecharge['paid'] != 1) {
            throw new AdminException('Đơn hàng chưa được thanh toán');
        }
        if ($UserRecharge['price'] == $UserRecharge['refund_price']) {
            throw new AdminException('Số tiền thanh toán đã được hoàn lại và không thể hoàn lại được nữa.');
        }
        if ($UserRecharge['recharge_type'] == 'balance') {
            throw new AdminException('Hoa hồng được chuyển vào số dư và không thể hoàn lại');
        }
        $f = array();
        $f[] = Form::input('order_id', 'Số đơn hàng hoàn tiền', $UserRecharge->getData('order_id'))->disabled(true);
        $f[] = Form::radio('refund_price', 'Trạng thái', 1)->options([['label' => 'hiệu trưởng(Trừ đi số dư quà tặng)', 'value' => 1], ['label' => 'Chỉ có hiệu trưởng', 'value' => 0]]);
        return create_form('Sửa', $f, Url::buildUrl('/finance/recharge/' . $id), 'PUT');
    }

    /**
     * Hoạt động hoàn tiền
     * @param int $id
     * @param string $refund_price
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function refund_update(int $id, string $refund_price)
    {
        $UserRecharge = $this->getRecharge($id);
        if (!$UserRecharge) {
            throw new AdminException('Dữ liệu không tồn tại');
        }
        if ($UserRecharge['price'] == $UserRecharge['refund_price']) {
            throw new AdminException('Số tiền thanh toán đã được hoàn lại và không thể hoàn lại được nữa.');
        }
        if ($UserRecharge['recharge_type'] == 'balance') {
            throw new AdminException('Hoa hồng được chuyển vào số dư và không thể hoàn lại');
        }
        $data['refund_price'] = $UserRecharge['price'];
        $refund_data['pay_price'] = $UserRecharge['price'];
        $refund_data['refund_price'] = $UserRecharge['price'];
        if ($refund_price == 1) {
            $number = bcadd($UserRecharge['price'], $UserRecharge['give_price'], 2);
        } else {
            $number = $UserRecharge['price'];
        }

        try {
            $recharge_type = $UserRecharge['recharge_type'];
            if ($recharge_type == 'weixin') {
                $refund_data['wechat'] = true;
            } else {
                $refund_data['trade_no'] = $UserRecharge['trade_no'];
                $refund_data['order_id'] = $UserRecharge['order_id'];
                /** @var WechatUserServices $wechatUserServices */
                $wechatUserServices = app()->make(WechatUserServices::class);
                $refund_data['open_id'] = $wechatUserServices->uidToOpenid((int)$UserRecharge['uid'], 'routine') ?? '';
                $refund_data['pay_new_weixin_open'] = sys_config('pay_new_weixin_open');
                /** @var StoreOrderCreateServices $storeOrderCreateServices */
                $storeOrderCreateServices = app()->make(StoreOrderCreateServices::class);
                $refund_data['refund_no'] = $storeOrderCreateServices->getNewOrderId('tk');
            }
            if ($recharge_type == 'allinpay') {
                $drivers = 'allin_pay';
                $trade_no = $UserRecharge['trade_no'];
            } elseif (sys_config('pay_wechat_type')) {
                $drivers = 'v3_wechat_pay';
                $trade_no = $UserRecharge['trade_no'];
            } else {
                $drivers = 'wechat_pay';
                $trade_no = $UserRecharge['order_id'];
            }
            /** @var Pay $pay */
            $pay = app()->make(Pay::class, [$drivers]);
            $pay->refund($trade_no, $refund_data);
        } catch (\Exception $e) {
            throw new AdminException($e->getMessage());
        }
        if (!$this->dao->update($id, $data)) {
            throw new AdminException('Sửa đổi không thành công');
        }

        //Sửa đổi số dư người dùng
        /** @var UserServices $userServices */
        $userServices = app()->make(UserServices::class);
        $userInfo = $userServices->getUserInfo($UserRecharge['uid']);
        if ($userInfo['now_money'] > $number) {
            $now_money = bcsub((string)$userInfo['now_money'], $number, 2);
        } else {
            $number = $userInfo['now_money'];
            $now_money = 0;
        }
        $userServices->update((int)$UserRecharge['uid'], ['now_money' => $now_money], 'uid');

        //Viết dòng vốn
        /** @var CapitalFlowServices $capitalFlowServices */
        $capitalFlowServices = app()->make(CapitalFlowServices::class);
        $UserRecharge['nickname'] = $userInfo['nickname'];
        $UserRecharge['phone'] = $userInfo['phone'];
        $capitalFlowServices->setFlow($UserRecharge, 'refund_recharge');

        //Lưu hồ sơ số dư
        /** @var UserMoneyServices $userMoneyServices */
        $userMoneyServices = app()->make(UserMoneyServices::class);
        $userMoneyServices->income('user_recharge_refund', $UserRecharge['uid'], $number, $now_money, $id);

        //Đẩy lời nhắc
        event('NoticeListener', [['user_type' => strtolower($userInfo['user_type']), 'data' => $data, 'UserRecharge' => $UserRecharge, 'now_money' => $refund_price], 'recharge_order_refund_status']);

        //Thông báo tùy chỉnh-nạp tiền và hoàn tiền
        $UserRecharge['now_money'] = $now_money;
        $UserRecharge['time'] = date('Y-m-d H:i:s');
        event('NoticeListener', [$UserRecharge['uid'], $UserRecharge, 'recharge_refund']);

        //Sự kiện tùy chỉnh - nạp tiền và hoàn tiền nền
        event('CustomEventListener', ['admin_recharge_refund', [
            'uid' => $UserRecharge['uid'],
            'refund_price' => $UserRecharge['price'],
            'now_money' => $now_money,
            'nickname' => $UserRecharge['price'],
            'phone' => $UserRecharge['phone'],
            'refund_time' => date('Y-m-d H:i:s')
        ]]);

        return true;
    }

    /**
     * xóa bỏ
     * @param int $id
     * @return bool
     */
    public function delRecharge(int $id)
    {
        $rechargInfo = $this->getRecharge($id);
        if (!$rechargInfo) throw new AdminException('Dữ liệu không tồn tại');
        if ($rechargInfo->paid) {
            throw new AdminException('Không thể xóa hồ sơ đơn hàng đã thanh toán');
        }
        if ($this->dao->delete($id))
            return true;
        else
            throw new AdminException('Xóa không thành công');
    }

    /**
     * Tạo số thứ tự nạp tiền
     * @return bool|string
     */
    public function getOrderId()
    {
        return 'wx' . date('YmdHis', time()) . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }

    /**
     * Nhập hoa hồng để cân bằng
     * @param int $uid
     * @param $price
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function importNowMoney(int $uid, $price)
    {
        /** @var UserServices $userServices */
        $userServices = app()->make(UserServices::class);
        $user = $userServices->getUserInfo($uid);
        if (!$user) {
            throw new ApiException('Lỗi tham số');
        }
        /** @var UserBrokerageServices $frozenPrices */
        $frozenPrices = app()->make(UserBrokerageServices::class);
        $broken_commission = $frozenPrices->getUserFrozenPrice($uid);
        $commissionCount = bcsub((string)$user['brokerage_price'], (string)$broken_commission, 2);
        if ($price > $commissionCount) {
            throw new ApiException('Số tiền chuyển không thể lớn hơn hoa hồng có thể rút');
        }
        $edit_data = [];
        $edit_data['now_money'] = bcadd((string)$user['now_money'], (string)$price, 2);
        $edit_data['brokerage_price'] = $user['brokerage_price'] > $price ? bcsub((string)$user['brokerage_price'], (string)$price, 2) : 0;
        if (!$userServices->update($uid, $edit_data, 'uid')) {
            throw new ApiException('Sửa đổi không thành công');
        }

        //Viết hồ sơ nạp tiền
        $rechargeInfo = [
            'uid' => $uid,
            'order_id' => app()->make(StoreOrderCreateServices::class)->getNewOrderId('cz'),
            'recharge_type' => 'balance',
            'price' => $price,
            'give_price' => 0,
            'paid' => 1,
            'pay_time' => time(),
            'add_time' => time()
        ];
        if (!$re = $this->dao->save($rechargeInfo)) {
            throw new ApiException('Không thể ghi số dư nạp lại');
        }

        //Hồ sơ số dư
        /** @var UserMoneyServices $userMoneyServices */
        $userMoneyServices = app()->make(UserMoneyServices::class);
        $userMoneyServices->income('brokerage_to_nowMoney', $uid, $price, $edit_data['now_money'], $re['id']);

        //Viết biên bản rút tiền
        $extractInfo = [
            'uid' => $uid,
            'real_name' => $user['nickname'],
            'extract_type' => 'balance',
            'extract_price' => $price,
            'balance' => $user['brokerage_price'],
            'add_time' => time(),
            'status' => 1
        ];
        /** @var UserExtractServices $userExtract */
        $userExtract = app()->make(UserExtractServices::class);
        $userExtract->save($extractInfo);

        //Hồ sơ rút tiền hoa hồng
        /** @var UserBrokerageServices $userBrokerageServices */
        $userBrokerageServices = app()->make(UserBrokerageServices::class);
        $userBrokerageServices->income('brokerage_to_nowMoney', $uid, $price, $edit_data['brokerage_price'], $re['id']);
        return true;
    }

    /**
     * Đăng ký nạp tiền
     * @param int $uid
     * @param $price
     * @param $recharId
     * @param $type
     * @param $from
     * @param bool $renten
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function recharge(int $uid, $price, $recharId, $type, $from, bool $renten = false)
    {
        /** @var UserServices $userServices */
        $userServices = app()->make(UserServices::class);
        $user = $userServices->getUserInfo($uid);
        if (!$user) {
            throw new ApiException('Người dùng không tồn tại');
        }
        switch ((int)$type) {
            case 0: //Thanh toán số dư nạp lại
                $paid_price = 0;
                if ($recharId) {
                    /** @var SystemGroupDataServices $systemGroupData */
                    $systemGroupData = app()->make(SystemGroupDataServices::class);
                    $data = $systemGroupData->getDateValue($recharId);
                    if ($data === false) {
                        throw new ApiException('Phương thức nạp tiền bạn chọn đã bị xóa khỏi kệ');
                    } else {
                        $paid_price = $data['give_money'] ?? 0;
                        $price = $data['price'] ?? 0;
                    }
                }
                $recharge_data = [];
                $recharge_data['order_id'] = app()->make(StoreOrderCreateServices::class)->getNewOrderId('cz');
                $recharge_data['uid'] = $uid;
                $recharge_data['price'] = $price;
                $recharge_data['recharge_type'] = $from;
                $recharge_data['paid'] = 0;
                $recharge_data['add_time'] = time();
                $recharge_data['give_price'] = $paid_price;
                $recharge_data['channel_type'] = $user['user_type'];
                if (!$rechargeOrder = $this->dao->save($recharge_data)) {
                    throw new ApiException('Tạo lệnh nạp tiền không thành công');
                }
                try {
                    /** @var RechargeServices $recharge */
                    $recharge = app()->make(RechargeServices::class);
                    $order_info = $recharge->recharge($rechargeOrder);
                } catch (\Exception $e) {
                    throw new ApiException($e->getMessage());
                }
                if ($renten) {
                    return $order_info;
                }
                return ['msg' => '', 'type' => $from, 'data' => $order_info];
            case 1: //Hoa hồng được chuyển vào số dư
                $this->importNowMoney($uid, $price);
                return ['msg' => 'Số dư được chuyển thành công', 'type' => $from, 'data' => []];
            default:
                throw new ApiException('Lỗi tham số');
        }
    }

    /**
     * Sau khi người dùng nạp tiền thành công
     * @param $orderId
     * @param array $other
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function rechargeSuccess($orderId, array $other = [])
    {
        $order = $this->dao->getOne(['order_id' => $orderId, 'paid' => 0]);
        if (!$order) {
            throw new ApiException('Đơn hàng không tồn tại');
        }
        /** @var UserServices $userServices */
        $userServices = app()->make(UserServices::class);
        $user = $userServices->getUserInfo((int)$order['uid']);
        if (!$user) {
            throw new ApiException('Người dùng không tồn tại');
        }
        $price = bcadd((string)$order['price'], (string)$order['give_price'], 2);
        if (!$this->dao->update($order['id'], ['paid' => 1, 'recharge_type' => $other['pay_type'], 'pay_time' => time(), 'trade_no' => $other['trade_no'] ?? ''], 'id')) {
            throw new ApiException('Không thể sửa đổi thứ tự');
        }
        $now_money = bcadd((string)$user['now_money'], (string)$price, 2);
        /** @var UserMoneyServices $userMoneyServices */
        $userMoneyServices = app()->make(UserMoneyServices::class);
        $userMoneyServices->income('user_recharge', $user['uid'], ['number' => $price, 'price' => $order['price'], 'give_price' => $order['give_price']], $now_money, $order['id']);
        if (!$userServices->update((int)$order['uid'], ['now_money' => $now_money], 'uid')) {
            throw new ApiException('Không thể sửa đổi thông tin người dùng');
        }

        /** @var CapitalFlowServices $capitalFlowServices */
        $capitalFlowServices = app()->make(CapitalFlowServices::class);
        $order['nickname'] = $user['nickname'];
        $order['phone'] = $user['phone'];
        $capitalFlowServices->setFlow($order, 'recharge');

        //Đẩy lời nhắc
        event('NoticeListener', [['order' => $order, 'now_money' => $now_money], 'recharge_success']);

        //Thông báo tùy chỉnh - Đơn hàng bị từ chối để được hoàn tiền
        $order['now_money'] = $now_money;
        $order['time'] = date('Y-m-d H:i:s');
        event('CustomNoticeListener', [$order['uid'], $order, 'recharge_success']);

        $order['pay_type'] = $other['pay_type'];
        // Dịch vụ đặt hàng chương trình nhỏ
        event('OrderShippingListener', ['recharge', $order, 3, '', '']);

        //Nạp tiền cho người dùng sự kiện tùy chỉnh
        event('CustomEventListener', ['user_recharge', [
            'uid' => $order['uid'],
            'id' => (int)$order['id'],
            'order_id' => $orderId,
            'nickname' => $order['nickname'],
            'phone' => $order['phone'],
            'price' => $order['price'],
            'give_price' => $order['give_price'],
            'now_money' => $order['now_money'],
            'recharge_time' => date('Y-m-d H:i:s'),
        ]]);

        return true;
    }

    /**
     * Theo số tiền nạp lại của người dùng truy vấn
     * @param array $where
     * @param string $rechargeSumField
     * @param string $selectType
     * @param string $group
     * @return float|int
     */
    public function getRechargeMoneyByWhere(array $where, string $rechargeSumField, string $selectType, string $group = "")
    {
        switch ($selectType) {
            case "sum" :
                return $this->dao->getWhereSumField($where, $rechargeSumField);
            case "group" :
                return $this->dao->getGroupField($where, $rechargeSumField, $group);
        }
    }
}
