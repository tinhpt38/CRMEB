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
namespace app\api\controller\v1\user;

use app\Request;
use app\services\pay\PayServices;
use app\services\user\UserRechargeServices;

/**
 * Loại nạp tiền
 * Class UserRechargeController
 * @package app\api\controller\user
 */
class UserRechargeController
{
    protected $services = NUll;

    /**
     * UserRechargeController constructor.
     * @param UserRechargeServices $services
     */
    public function __construct(UserRechargeServices $services)
    {
        $this->services = $services;
    }

    /**
     * Nạp tiền người dùng
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function recharge(Request $request)
    {
        [$price, $recharId, $type, $from] = $request->postMore([
            ['price', 0],
            ['rechar_id', 0],
            ['type', 0],
            ['from', 'weixin']
        ], true);
        if (!$price || $price <= 0) return app('json')->fail('Số tiền nạp không thể là 0 nhân dân tệ');
        if (!in_array($type, [0, 1])) return app('json')->fail('Phương thức nạp tiền không được hỗ trợ');
        if (!in_array($from, [PayServices::WEIXIN_PAY, 'weixinh5', 'routine', PayServices::ALIAPY_PAY])) return app('json')->fail('Phương thức nạp tiền không được hỗ trợ');
        $storeMinRecharge = sys_config('store_user_min_recharge');
        if (!$recharId && $price < $storeMinRecharge) return app('json')->fail('Số tiền nạp không được ít hơn{:money}', null, ['money' => $storeMinRecharge]);
        $uid = (int)$request->uid();
        $re = $this->services->recharge($uid, $price, $recharId, $type, $from, true);
        if ($re) {
            $payType = $re['pay_type'] ?? '';
            unset($re['pay_type']);
            return app('json')->status($payType, 'Nạp tiền thành công', $re);
        }
        return app('json')->fail('Nạp tiền không thành công');
    }

    /**
     * TODO Nạp tiền chương trình nhỏ không được dùng nữa
     * @param Request $request
     * @return mixed
     */
    public function routine(Request $request)
    {
        list($price, $recharId, $type) = $request->postMore([['price', 0], ['rechar_id', 0], ['type', 0]], true);
        if (!$price || $price <= 0) return app('json')->fail('Số tiền nạp không thể là 0 nhân dân tệ');
        $storeMinRecharge = sys_config('store_user_min_recharge');
        if ($price < $storeMinRecharge) return app('json')->fail('Số tiền nạp không được ít hơn{:money}', null, ['money' => $storeMinRecharge]);
        $from = 'routine';
        $uid = (int)$request->uid();
        $re = $this->services->recharge($uid, $price, $recharId, $type, $from);
        if ($re) {
            unset($re['msg']);
            return app('json')->success('Nạp tiền thành công', $re['data']);
        }
        return app('json')->fail('Nạp tiền không thành công');
    }

    /**
     * TODO Việc nạp tiền vào tài khoản chính thức không được chấp nhận
     * @param Request $request
     * @return mixed
     */
    public function wechat(Request $request)
    {
        list($price, $recharId, $from, $type) = $request->postMore([['price', 0], ['rechar_id', 0], ['from', 'weixin'], ['type', 0]], true);
        if (!$price || $price <= 0) return app('json')->fail('Số tiền nạp không thể là 0 nhân dân tệ');
        $storeMinRecharge = sys_config('store_user_min_recharge');
        if ($price < $storeMinRecharge) return app('json')->fail('Số tiền nạp không được ít hơn{:money}', null, ['money' => $storeMinRecharge]);
        $uid = (int)$request->uid();
        $re = $this->services->recharge($uid, $price, $recharId, $type, $from);
        if ($re) {
            unset($re['msg']);
            return app('json')->success('Nạp tiền thành công', $re);
        }
        return app('json')->fail('Nạp tiền không thành công');
    }

    /**
     * Lựa chọn số tiền nạp
     * @return mixed
     */
    public function index()
    {
        $rechargeQuota = sys_data('user_recharge_quota') ?? [];
        $data['recharge_quota'] = $rechargeQuota;
        $recharge_attention = sys_config('recharge_attention');
        $recharge_attention = explode("\n", $recharge_attention);
        $data['recharge_attention'] = $recharge_attention;
        return app('json')->success($data);
    }
}
