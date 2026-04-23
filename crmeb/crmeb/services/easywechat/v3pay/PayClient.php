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

namespace crmeb\services\easywechat\v3pay;


use crmeb\exceptions\PayException;
use crmeb\services\wechat\Payment;
use EasyWeChat\Payment\Order;

/**
 * v3chi trả
 * Class PayClient
 * @package crmeb\services\easywechat\v3pay
 */
class PayClient extends BaseClient
{
    //appchi trả
    const API_APP_APY_URL = 'v3/pay/transactions/app';
    //appMô hình nhà cung cấp dịch vụ thanh toán
    const API_APP_APY_PARTNER_URL = 'v3/pay/partner/transactions/app';
    //NativeĐặt hàngAPI
    const API_NATIVE_URL = 'v3/pay/transactions/native';
    //NativeMô hình nhà cung cấp dịch vụ API đặt hàng
    const API_NATIVE_PARTNER_URL = 'v3/pay/partner/transactions/native';
    //h5Giao diện thanh toán
    const API_H5_URL = 'v3/pay/transactions/h5';
    //h5Mô hình nhà cung cấp dịch vụ giao diện thanh toán
    const API_H5_PARTNER_URL = 'v3/pay/partner/transactions/h5';
    //jsapiGiao diện thanh toán
    const API_JSAPI_URL = 'v3/pay/transactions/jsapi';
    //jsapiMô hình nhà cung cấp dịch vụ giao diện thanh toán
    const API_JSAPI_PARTNER_URL = 'v3/pay/partner/transactions/jsapi';
    //Bắt đầu chuyển nhượng người bánAPI
    const API_BATCHES_URL = 'v3/transfer/batches';
    //Đền bù
    const API_REFUND_URL = 'v3/refund/domestic/refunds';
    //Giao diện truy vấn hoàn tiền
    const API_REFUND_QUERY_URL = 'v3/refund/domestic/refunds/{out_refund_no}';
    //Bắt đầu chuyển khoản
    const API_TRANSFER_BILLS_URL = 'v3/fund-app/mch-transfer/transfer-bills';
    //Hỏi về chuyển khoản
    const API_TRANSFER_QUERY_URL = 'v3/fund-app/mch-transfer/transfer-bills/out-bill-no/{out_bill_no}';

    /**
     * @var string
     */
    protected $type = Order::JSAPI;

    /**
     * @param string $type
     * @return $this
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/2/10
     */
    public function setType(string $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Lệnh thanh toán jsapi tài khoản công cộng
     * @param string $outTradeNo
     * @param string $total
     * @param string $description
     * @param string $attach
     * @return mixed
     */
    public function jsapiPay(string $openid, string $outTradeNo, string $total, string $description, string $attach)
    {
        $appId = $this->app['config']['wechat']['appid'];
        $res = $this->pay('jsapi', $appId, $outTradeNo, $total, $description, $attach, ['openid' => $openid]);
        return $this->configForJSSDKPayment($appId, $res['prepay_id']);
    }

    /**
     * Thanh toán chương trình nhỏ
     * @param string $outTradeNo
     * @param string $total
     * @param string $description
     * @param string $attach
     * @return array|false|string
     */
    public function miniprogPay(string $openid, string $outTradeNo, string $total, string $description, string $attach)
    {
        $appId = $this->app['config']['miniprog']['appid'];
        $res = $this->pay('jsapi', $appId, $outTradeNo, $total, $description, $attach, ['openid' => $openid]);
        return $this->configForJSSDKPayment($appId, $res['prepay_id']);
    }

    /**
     * APPTrả tiền cho đơn hàng
     * @param string $outTradeNo
     * @param string $total
     * @param string $description
     * @param string $attach
     * @return mixed
     */
    public function appPay(string $outTradeNo, string $total, string $description, string $attach)
    {
        $res = $this->pay('app', $this->app['config']['app']['appid'], $outTradeNo, $total, $description, $attach);
        return $this->configForAppPayment($res['prepay_id']);
    }

    /**
     * nativeTrả tiền cho đơn hàng
     * @param string $outTradeNo
     * @param string $total
     * @param string $description
     * @param string $attach
     * @return mixed
     */
    public function nativePay(string $outTradeNo, string $total, string $description, string $attach)
    {
        return $this->pay('native', $this->app['config']['web']['appid'], $outTradeNo, $total, $description, $attach);
    }

    /**
     * h5Trả tiền cho đơn hàng
     * @param string $outTradeNo
     * @param string $total
     * @param string $description
     * @param string $attach
     * @return mixed
     */
    public function h5Pay(string $outTradeNo, string $total, string $description, string $attach)
    {
        return $this->pay('h5', $this->app['config']['wechat']['appid'], $outTradeNo, $total, $description, $attach);
    }

    /**
     * Đặt hàng
     * @param string $type
     * @param string $appid
     * @param string $outTradeNo
     * @param string $total
     * @param string $description
     * @param string $attach
     * @param array $payer
     * @return mixed
     */
    public function pay(string $type, string $appid, string $outTradeNo, string $total, string $description, string $attach, array $payer = [])
    {
        $totalFee = (int)bcmul($total, '100');

        $data = [
            'appid' => $appid,
            'mchid' => $this->app['config']['v3_payment']['mchid'],
            'out_trade_no' => $outTradeNo,
            'attach' => $attach,
            'description' => $description,
            'notify_url' => $this->app['config']['v3_payment']['notify_url'],
            'amount' => [
                'total' => $totalFee,
                'currency' => 'CNY'
            ],
        ];

        if ($payer) {
            $data['payer'] = $payer;
        }

        //Mô hình thanh toán của nhà cung cấp dịch vụ
        if ($this->app['config']['v3_payment']['mer_type']) {

            $mchid = $data['mchid'];
            $appid = $data['appid'];
            unset($data['mchid'], $data['appid'], $data['payer']);
            $data['sp_appid'] = $this->app['config']['v3_payment']['sp_appid'];
            $data['sp_mchid'] = $mchid;
            $data['sub_mchid'] = $this->app['config']['v3_payment']['sub_mch_id'];
            if (!empty($payer['openid'])) {
                $data['payer']['sub_openid'] = $payer['openid'];
                $data['sub_appid'] = $appid;
            }

            $url = '';
            switch ($type) {
                case 'h5':
                    $url = self::API_H5_PARTNER_URL;
                    $data['scene_info'] = [
                        'payer_client_ip' => request()->ip(),
                        'h5_info' => [
                            'type' => 'Wap'
                        ]
                    ];
                    break;
                case 'native':
                    $url = self::API_NATIVE_PARTNER_URL;
                    break;
                case 'app':
                    $url = self::API_APP_APY_PARTNER_URL;
                    break;
                case 'jsapi':
                    $url = self::API_JSAPI_PARTNER_URL;
                    break;
            }

        } else {
            $url = '';
            switch ($type) {
                case 'h5':
                    $url = self::API_H5_URL;
                    $data['scene_info'] = [
                        'payer_client_ip' => request()->ip(),
                        'h5_info' => [
                            'type' => 'Wap'
                        ]
                    ];
                    break;
                case 'native':
                    $url = self::API_NATIVE_URL;
                    break;
                case 'app':
                    $url = self::API_APP_APY_URL;
                    break;
                case 'jsapi':
                    $url = self::API_JSAPI_URL;
                    break;
            }
        }


        if (!$url) {
            throw new PayException('Thiếu địa chỉ yêu cầu');
        }

        $res = $this->request($url, 'POST', ['json' => $data]);

        if (!$res) {
            throw new PayException('WeChat trả tiền:Đặt hàng không thành công');
        }
        if (isset($res['code']) && isset($res['message'])) {
            throw new PayException($res['message']);
        }

        return $res;
    }

    /**
     * Bắt đầu chuyển nhượng người bánAPI
     * @param string $outBatchNo
     * @param string $amount
     * @param string $batchName
     * @param string $remark
     * @param array $transferDetailList
     * @return mixed
     */
    public function batches(string $outBatchNo, string $amount, string $batchName, string $remark, array $transferDetailList)
    {
        $totalFee = '0';
        $amount = bcadd($amount, '0', 2);
        foreach ($transferDetailList as &$item) {
            if ($item['transfer_amount'] >= 2000 && empty($item['user_name'])) {
                throw new PayException('Khi số tiền chi tiết lớn hơn hoặc bằng 2000,Tên người nhận thanh toán là bắt buộc');
            }
            $totalFee = bcadd($totalFee, $item['transfer_amount'], 2);
            $item['transfer_amount'] = (int)bcmul($item['transfer_amount'], 100, 0);
            if (isset($item['user_name'])) {
                $item['user_name'] = $this->encryptor($item['user_name']);
            }
        }

        if ($totalFee !== $amount) {
            throw new PayException('Tổng số tiền chuyển khoản không khớp với tổng số tiền chuyển khoản');
        }

        $amount = (int)bcmul($amount, 100, 0);

        $appid = null;
        if ($this->type === Order::JSAPI) {
            $appid = $this->app['config']['wechat']['appid'];
        } else if ($this->type === 'mini') {
            $appid = $this->app['config']['miniprog']['appid'];
        } else if ($this->type === Order::APP) {
            $appid = $this->app['config']['app']['appid'];
        }

        if (!$appid) {
            throw new PayException('Hiện tại, chỉ người dùng WeChat, người dùng chương trình nhỏ và người dùng đã đăng nhập APP WeChat mới có thể rút tiền mặt.');
        }

        $data = [
            'appid' => $appid,
            'out_batch_no' => $outBatchNo,
            'batch_name' => $batchName,
            'batch_remark' => $remark,
            'total_amount' => $amount,
            'total_num' => count($transferDetailList),
            'transfer_detail_list' => $transferDetailList
        ];

        $res = $this->request(self::API_BATCHES_URL, 'POST', ['json' => $data]);

        if (!$res) {
            throw new PayException('WeChat trả tiền:Không thể bắt đầu chuyển giao người bán');
        }

        if (isset($res['code']) && isset($res['message'])) {
            throw new PayException($res['message']);
        }

        return $res;

    }

    public function transferBills($order_id, $transfer_scene_id, $openid, $user_name, $transfer_amount, $transfer_remark, $notify_url, $user_recv_perception, $transfer_scene_report_infos)
    {
        $appid = '';
        if ($this->type === Order::JSAPI) {
            $appid = $this->app['config']['wechat']['appid'];
        } else if ($this->type === 'mini') {
            $appid = $this->app['config']['miniprog']['appid'];
        } else if ($this->type === Order::APP) {
            $appid = $this->app['config']['app']['appid'];
        }
        if ($appid === '') {
            throw new PayException('Hiện tại, chỉ người dùng WeChat, người dùng chương trình nhỏ và người dùng đã đăng nhập APP WeChat mới có thể rút tiền mặt.');
        }
        if ($transfer_amount > 200000) {
            if ($user_name === '') {
                throw new PayException('Khi số tiền lớn hơn hoặc bằng 2.000 thì phải điền tên người nhận tiền');
            }
            $user_name = $this->encryptor($user_name);
        } else {
            $user_name = '';
        }
        $data = [];
        $data['appid'] = $appid;
        $data['out_bill_no'] = $order_id;
        $data['transfer_scene_id'] = $transfer_scene_id;
        $data['openid'] = $openid;
        $data['user_name'] = $user_name;
        $data['transfer_amount'] = (int)$transfer_amount;
        $data['transfer_remark'] = $transfer_remark;
        $data['notify_url'] = $notify_url;
        $data['user_recv_perception'] = $user_recv_perception;
        $data['transfer_scene_report_infos'] = $transfer_scene_report_infos;
        $res = $this->request(self::API_TRANSFER_BILLS_URL, 'POST', ['json' => $data]);
        if (!$res || isset($res['code'], $res['message'])) {
            throw new PayException($res['message'] ?? 'WeChat trả tiền:Không thể bắt đầu chuyển giao người bán');
        }
        return $res;
    }

    public function queryTransferBills(string $outBillNo)
    {
        $res = $this->request($this->getApiUrl(self::API_TRANSFER_QUERY_URL, ['out_bill_no'], [$outBillNo]), 'GET');

        if (!$res) {
            throw new PayException('Không thể bắt đầu yêu cầu hoàn tiền');
        }

        return $res;
    }

    /**
     * Đền bù
     * @param string $outTradeNo
     * @param array $options
     * @return mixed
     */
    public function refund(string $outTradeNo, array $options = [])
    {
        if (!isset($options['pay_price'])) {
            throw new PayException('Thiếupay_price');
        }
        $totalFee = floatval(bcmul($options['pay_price'], 100, 0));
        $refundFee = isset($options['refund_price']) ? floatval(bcmul($options['refund_price'], 100, 0)) : null;
        $refundReason = $options['desc'] ?? '';
        $refundNo = $options['refund_id'] ?? $outTradeNo;
        /*Chỉ dành cho người bán dòng tiền cũ
        REFUND_SOURCE_UNSETTLED_FUNDS---Hoàn tiền cho các khoản tiền chưa thanh toán (hoàn trả các khoản tiền chưa thanh toán được sử dụng theo mặc định)
        REFUND_SOURCE_RECHARGE_FUNDS---Hoàn lại số dư khả dụng
        */
        $refundAccount = $opt['refund_account'] ?? 'AVAILABLE';

        $data = [
            'transaction_id' => $outTradeNo,
            'out_refund_no' => $refundNo,
            'amount' => [
                'refund' => (int)$refundFee,
                'currency' => 'CNY',
                'total' => (int)$totalFee
            ],
            'funds_account' => $refundAccount
        ];

        if ($refundReason) {
            $data['reason'] = $refundReason;
        }

        //Nhà cung cấp dịch vụ trả tiền hoàn lại
        $merType = $this->app['config']['v3_payment']['mer_type'];
        if ($merType) {
            $data['sub_mchid'] = $this->app['config']['v3_payment']['sub_mch_id'];
        }

        $res = $this->request(self::API_REFUND_URL, 'POST', ['json' => $data]);

        if (!$res) {
            throw new PayException('WeChat trả tiền:Không thể bắt đầu hoàn tiền');
        }

        if (isset($res['code']) && isset($res['message'])) {
            throw new PayException($res['message']);
        }

        return $res;
    }

    /**
     * Yêu cầu hoàn tiền
     * @param string $outRefundNo
     * @return mixed
     */
    public function queryRefund(string $outRefundNo)
    {
        $res = $this->request($this->getApiUrl(self::API_REFUND_QUERY_URL, ['out_refund_no'], [$outRefundNo]), 'GET');

        if (!$res) {
            throw new PayException('Không thể bắt đầu yêu cầu hoàn tiền');
        }

        if (isset($res['code']) && isset($res['message'])) {
            throw new PayException($res['message']);
        }

        return $res;
    }

    /**
     * jsapichi trả
     * @param string $appid
     * @param string $prepayId
     * @param bool $json
     * @return array|false|string
     */
    public function configForPayment(string $appid, string $prepayId, bool $json = true)
    {
        $params = [
            'appId' => $appid,
            'timeStamp' => strval(time()),
            'nonceStr' => uniqid(),
            'package' => "prepay_id=$prepayId",
            'signType' => 'RSA',
        ];
        $message = $params['appId'] . "\n" .
            $params['timeStamp'] . "\n" .
            $params['nonceStr'] . "\n" .
            $params['package'] . "\n";
        openssl_sign($message, $raw_sign, $this->getPrivateKey(), 'sha256WithRSAEncryption');
        $sign = base64_encode($raw_sign);

        $params['paySign'] = $sign;

        return $json ? json_encode($params) : $params;
    }

    /**
     * Generate app payment parameters.
     * @param string $prepayId
     * @return array
     */
    public function configForAppPayment(string $prepayId): array
    {
        $params = [
            'appid' => $this->app['config']['app']['appid'],
            'partnerid' => $this->app['config']['v3_payment']['mchid'],
            'prepayid' => $prepayId,
            'noncestr' => uniqid(),
            'timestamp' => time(),
            'package' => 'Sign=WXPay',
        ];
        $message = $params['appid'] . "\n" .
            $params['timestamp'] . "\n" .
            $params['noncestr'] . "\n" .
            $params['prepayid'] . "\n";
        openssl_sign($message, $raw_sign, $this->getPrivateKey(), 'sha256WithRSAEncryption');
        $sign = base64_encode($raw_sign);

        $params['sign'] = $sign;

        return $params;
    }

    /**
     * Thanh toán chương trình nhỏ
     * @param string $appid
     * @param string $prepayId
     * @return array|false|string
     */
    public function configForJSSDKPayment(string $appid, string $prepayId)
    {
        $config = $this->configForPayment($appid, $prepayId, false);

        $config['timestamp'] = $config['timeStamp'];
        unset($config['timeStamp']);

        return $config;
    }

    /**
     * @param $callback
     * @return \think\Response
     */
    public function handleNotify($callback)
    {
        $request = request();
        $success = $request->post('event_type') === 'TRANSACTION.SUCCESS';
        $data = $this->decrypt($request->post('resource', []));

        $handleResult = call_user_func_array($callback, [json_decode($data), $success]);
        if (is_bool($handleResult) && $handleResult) {
            $response = [
                'code' => 'SUCCESS',
                'message' => 'OK',
            ];
        } else {
            $response = [
                'code' => 'FAIL',
                'message' => $handleResult,
            ];
        }

        return response($response, 200, [], 'json');
    }

    public function handleTransferNotify($callback)
    {
        $request = request();
        $success = $request->post('event_type') === 'MCHTRANSFER.BILL.FINISHED';
        $data = $this->decrypt($request->post('resource', []));

        $handleResult = call_user_func_array($callback, [json_decode($data), $success]);
        if (is_bool($handleResult) && $handleResult) {
            $response = [
                'code' => 'SUCCESS',
                'message' => 'OK',
            ];
        } else {
            $response = [
                'code' => 'FAIL',
                'message' => $handleResult,
            ];
        }

        return response($response, 200, [], 'json');
    }
}
