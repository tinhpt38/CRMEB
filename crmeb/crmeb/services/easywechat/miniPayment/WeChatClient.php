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
namespace crmeb\services\easywechat\miniPayment;

use EasyWeChat\Core\AbstractAPI;
use EasyWeChat\Core\AccessToken;
use EasyWeChat\Kernel\Support;
use EasyWeChat\Kernel\Support\Collection;
use EasyWeChat\Kernel\Traits\HasHttpRequests;
use EasyWeChat\Payment\Application;
use EasyWeChat\Payment\Kernel\BaseClient;
use EasyWeChat\Payment\Merchant;

class WeChatClient extends AbstractAPI
{
    private $expire_time = 7000;


    /**
     * Tạo đơn hàng Thanh toán
     */
    const API_SET_CREATE_ORDER = 'https://api.weixin.qq.com/shop/pay/createorder';
    /**
     * Đền bù
     */
    const API_SET_REFUND_ORDER = 'https://api.weixin.qq.com/shop/pay/refundorder';


    /**
     * Merchant instance.
     *
     * @var \EasyWeChat\Payment\Merchant
     */
    protected $merchant;

    /**
     * ProgramSubscribeService constructor.
     * @param AccessToken $accessToken
     */
    public function __construct(AccessToken $accessToken, Merchant $merchant)
    {
        parent::__construct($accessToken);
        $this->merchant = $merchant;
    }

    /**
     * chi trả
     * @param array $params [
     *                      'openid'=>'Người trả tiềnopenid',
     *                      'out_trade_no'=>'Tổng số giao dịch thanh toán đơn hàng kết hợp của người bán',
     *                      'total_fee'=>'Số tiền thanh toán',
     *                      'wx_out_trade_no'=>'Số giao dịch của người bán',
     *                      'body'=>'Mô tả sản phẩm',
     *                      'attach'=>'Hình thức thanh toán',  //product thành viên thành viên sản phẩm
     *                      ]
     * @param $isContract
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createorder($order)
    {
        $params = [
            'openid' => $order['openid'],    // Người trả tiềnopenid
            'combine_trade_no' => $order['out_trade_no'],  // Tổng số giao dịch thanh toán đơn hàng kết hợp của người bán
            'expire_time' => time() + $this->expire_time,
            'sub_orders' => [
                [
                    'mchid' => $this->merchant->merchant_id,
                    'amount' => (int)$order['total_fee'],
                    'trade_no' => $order['out_trade_no'],
                    'description' => $order['body']
                ]
            ]
        ];
        return $this->parseJSON('post', [self::API_SET_CREATE_ORDER, json_encode($params)]);
    }

    /**
     * Đền bù
     * @param array $params [
     *                      'openid'=>'người hoàn trảopenid',
     *                      'trade_no'=>'Số giao dịch của người bán',
     *                      'transaction_id'=>'Số lệnh thanh toán',
     *                      'refund_no'=>'Số đơn hàng hoàn tiền của người bán',
     *                      'total_amount'=>'Tổng số tiền đặt hàng',
     *                      'refund_amount'=>'Số tiền hoàn lại',  //product thành viên thành viên sản phẩm
     *                      ]
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function refundorder(array $order)
    {
        $params = [
            'openid' => $order['openid'],
            'mchid' => $this->merchant->merchant_id,
            'trade_no' => $order['trade_no'],
            'transaction_id' => $order['transaction_id'],
            'refund_no' => $order['refund_no'],
            'total_amount' => $order['total_amount'],
            'refund_amount' => $order['refund_amount'],
        ];
        return $this->parseJSON('post', [self::API_SET_REFUND_ORDER, json_encode($params)]);
    }


}