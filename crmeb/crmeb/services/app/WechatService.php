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

namespace crmeb\services\app;

use app\services\message\wechat\MessageServices;
use app\services\pay\PayServices;
use app\services\system\SystemPemServices;
use app\services\wechat\WechatMessageServices;
use app\services\wechat\WechatReplyServices;
use crmeb\exceptions\AdminException;
use crmeb\exceptions\ApiException;
use crmeb\services\CacheService;
use crmeb\services\easywechat\Application;
use EasyWeChat\Message\Article;
use EasyWeChat\Message\Image;
use EasyWeChat\Message\Material;
use EasyWeChat\Message\News;
use EasyWeChat\Message\Text;
use EasyWeChat\Message\Video;
use EasyWeChat\Message\Voice;
use EasyWeChat\Payment\Order;
use EasyWeChat\Payment\Payment;
use EasyWeChat\Server\Guard;
use Symfony\Component\HttpFoundation\Request;
use think\facade\Event;
use think\Response;
use crmeb\services\SystemConfigService;
use think\facade\Env;

/**
 * Tài khoản công khai WeChat
 * Class WechatService
 * @package crmeb\services\app
 */
class WechatService
{
    /**
     * @var Application
     */
    protected static $instance;

    /**
     * @return array
     */
    public static function options()
    {
        $wechat = SystemConfigService::more(['wechat_appid', 'wechat_app_appid', 'wechat_app_appsecret', 'wechat_appsecret', 'wechat_token', 'wechat_encodingaeskey', 'wechat_encode']);
        $payment = SystemConfigService::more(['pay_weixin_mchid',
            'pay_weixin_client_cert',
            'pay_weixin_client_key',
            'pay_weixin_key',
            'pay_weixin_open',
            'pay_sub_app_id',
            'pay_sub_merchant_id',
            'mer_type'
        ]);

        if (request()->isApp()) {
            $appId = isset($wechat['wechat_app_appid']) ? trim($wechat['wechat_app_appid']) : '';
            $appsecret = isset($wechat['wechat_app_appsecret']) ? trim($wechat['wechat_app_appsecret']) : '';
        } else {
            $appId = isset($wechat['wechat_appid']) ? trim($wechat['wechat_appid']) : '';
            $appsecret = isset($wechat['wechat_appsecret']) ? trim($wechat['wechat_appsecret']) : '';
        }
        $config = [
            'app_id' => $appId,
            'secret' => $appsecret,
            'token' => isset($wechat['wechat_token']) ? trim($wechat['wechat_token']) : '',
            'guzzle' => [
                'timeout' => 10.0, // Thời gian chờ (giây）
                'verify' => false
            ],
        ];
        if (isset($wechat['wechat_encode']) && (int)$wechat['wechat_encode'] > 0 && isset($wechat['wechat_encodingaeskey']) && !empty($wechat['wechat_encodingaeskey']))
            $config['aes_key'] = $wechat['wechat_encodingaeskey'];
        if (isset($payment['pay_weixin_open'])) {
            $config['payment'] = [
                'app_id' => $appId,
                'merchant_id' => trim($payment['pay_weixin_mchid']),
                'key' => trim($payment['pay_weixin_key']),
                'cert_path' => self::getPemPath('pay_weixin_client_cert'),
                'key_path' => self::getPemPath('pay_weixin_client_key'),
                'notify_url' => trim(sys_config('site_url')) . '/api/pay/notify/wechat'
            ];

            if (isset($payment['mer_type']) && $payment['mer_type']) {
                $config['payment']['sub_mch_id'] = trim($payment['pay_sub_merchant_id']);
            }
        }
//        if (Env::get('cache.driver', 'file') == 'redis') {
//            $cache = new \Doctrine\Common\Cache\RedisCache();
//            $cache->setRedis(\think\facade\Cache::store('redis')->handler());
//            $config['cache'] = $cache;
//        }
        return $config;
    }

    public static function getPemPath(string $name)
    {
        $systemPemServices = app()->make(SystemPemServices::class);
        $path = $systemPemServices->getPemPath($name);
        if ($path) return $path;
        $path = sys_config($name);
        if (strstr($path, 'http://') || strstr($path, 'https://')) {
            $path = parse_url($path)['path'] ?? '';
        }
        $path = root_path('runtime/pem') . ltrim($path, '/');
        if (!file_exists($path)) {
            $path = public_path('uploads') . ltrim($path, '/');
        }
        return $path;
    }

    /**
     * @param bool $cache
     * @return Application
     */
    public static function application($cache = false)
    {
        (self::$instance === null || $cache === true) && (self::$instance = new Application(self::options()));
        return self::$instance;
    }

    /**
     * @return Response
     * @throws \EasyWeChat\Server\BadRequestException
     */
    public static function serve(): Response
    {
        $wechat = self::application(true);
        $server = $wechat->server;
        self::hook($server);
        $response = $server->serve();
        return response($response->getContent());
    }

    /**
     * Giám sát hành vi(WeChat)
     * @param Guard $server
     * @throws \EasyWeChat\Core\Exceptions\InvalidArgumentException
     */
    private static function hook($server)
    {
        /** @var MessageServices $messageService */
        $messageService = app()->make(MessageServices::class);
        /** @var WechatReplyServices $wechatReplyService */
        $wechatReplyService = app()->make(WechatReplyServices::class);
        $server->setMessageHandler(function ($message) use ($messageService, $wechatReplyService) {
            /** @var WechatMessageServices $wechatMessage */
            $wechatMessage = app()->make(WechatMessageServices::class);
            $wechatMessage->wechatMessageBefore($message);
            switch ($message->MsgType) {
                case 'event':
                    switch (strtolower($message->Event)) {
                        case 'subscribe':
                            $response = $messageService->wechatEventSubscribe($message);
                            break;
                        case 'unsubscribe':
                            $messageService->wechatEventUnsubscribe($message);
                            break;
                        case 'scan':
                            $response = $messageService->wechatEventScan($message);
                            break;
                        case 'location':
                            $response = $messageService->wechatEventLocation($message);
                            break;
                        case 'click':
                            $response = $wechatReplyService->reply($message->EventKey);
                            break;
                        case 'view':
                            $response = $messageService->wechatEventView($message);
                            break;
                    }
                    break;
                case 'text':
                    $response = $wechatReplyService->reply($message->Content, $message->FromUserName);
                    break;
                case 'image':
                    $response = $messageService->wechatMessageImage($message);
                    break;
                case 'voice':
                    $response = $messageService->wechatMessageVoice($message);
                    break;
                case 'video':
                    $response = $messageService->wechatMessageVideo($message);
                    break;
                case 'location':
                    $response = $messageService->wechatMessageLocation($message);
                    break;
                case 'link':
                    $response = $messageService->wechatMessageLink($message);
                    break;
                // ... Tin tức khác
                default:
                    $response = $messageService->wechatMessageOther($message);
                    break;
            }

            return $response ?? false;
        });
    }


    /**
     * Chuyển tiếp nhiều tin nhắn dịch vụ khách hàng
     * @param string $account
     * @return \EasyWeChat\Message\Transfer
     */
    public static function transfer($account = '')
    {
        $transfer = new \EasyWeChat\Message\Transfer();
        return empty($account) ? $transfer : $transfer->to($account);
    }


    /**
     * Tải lên giao diện vật liệu vĩnh viễn
     * @return \EasyWeChat\Material\Material
     */
    public static function materialService()
    {
        return self::application()->material;
    }

    /**
     * Tải lên giao diện vật liệu tạm thời
     * @return \EasyWeChat\Material\Temporary
     */
    public static function materialTemporaryService()
    {
        return self::application()->material_temporary;
    }

    /**
     * giao diện người dùng
     * @return \EasyWeChat\User\User
     */
    public static function userService()
    {
        return self::application()->user;
    }


    /**
     * Giao diện tin nhắn chăm sóc khách hàng
     * @param null $to
     * @param null $message
     */
    public static function staffService()
    {
        return self::application()->staff;
    }

    /**
     * Giao diện menu tài khoản công khai WeChat
     * @return \EasyWeChat\Menu\Menu
     */
    public static function menuService()
    {
        return self::application()->menu;
    }

    /**
     * Giao diện tạo mã QR WeChat
     * @return \EasyWeChat\QRCode\QRCode
     */
    public static function qrcodeService()
    {
        return self::application()->qrcode;
    }

    /**
     * Giao diện tạo liên kết ngắn
     * @return \EasyWeChat\Url\Url
     */
    public static function urlService()
    {
        return self::application()->url;
    }

    /**
     * Ủy quyền người dùng
     * @return \Overtrue\Socialite\Providers\WeChatProvider
     */
    public static function oauthService()
    {
        return self::application()->oauth;
    }

    /**
     * Ủy quyền trang web
     * @return easywechat\oauth2\wechat\WechatOauth2Provider
     */
    public static function oauth2Service()
    {
        $request = app()->request;
        self::application()->oauth2->setRequest(new Request($request->get(), $request->post(), [], [], [], $request->server(), $request->getContent()));
        return self::application()->oauth2;
    }

    /**
     * Gửi tin nhắn mẫu
     * @param $openid
     * @param $templateId
     * @param array $data
     * @param null $url
     * @param null $defaultColor
     * @return mixed
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/17
     */
    public static function sendTemplate($openid, $templateId, array $data, $url = null, $defaultColor = null, int $wechatToRoutine = 0)
    {
        $notice = self::application()->new_notice->to($openid)->template($templateId)->andData($data);
        if ($wechatToRoutine && sys_config('routine_appId')) {
            $notice->setMiniprogram([
                'appid' => sys_config('routine_appId'),
                'pagepath' => str_replace(sys_config('site_url'), '', $url)
            ]);
        }
        if ($url !== null) $notice->url($url);
        if ($defaultColor !== null) $notice->defaultColor($defaultColor);
        return $notice->send();
    }


    /**
     * chi trả
     * @return Payment
     */
    public static function paymentService()
    {
        return self::application()->payment;
    }

    public static function userTagService()
    {
        return self::application()->user_tag;
    }

    public static function userGroupService()
    {
        return self::application()->user_group;
    }


    /**
     * Thanh toán kinh doanh để thay đổi
     * @param string $openid openid
     * @param string $orderId Số đơn hàng
     * @param string $amount Số lượng
     * @param string $desc minh họa
     */
    public static function merchantPay(string $openid, string $orderId, string $amount, string $desc)
    {
        $options = self::options();
        if (!isset($options['payment']['cert_path'])) {
            throw new ApiException('Cần có chứng chỉ thanh toán để thanh toán số tiền lẻ nhỏ qua WeChat của công ty. Nó được phát hiện là bạn không tải nó lên.');
        }
        if (!$options['payment']['cert_path']) {
            throw new ApiException('Cần có chứng chỉ thanh toán để thanh toán số tiền lẻ nhỏ qua WeChat của công ty. Nó được phát hiện là bạn không tải nó lên.');
        }
        $merchantPayData = [
            'partner_trade_no' => $orderId, //Một chuỗi ngẫu nhiên được sử dụng làm số đơn hàng, khái niệm này tương tự như phong bì màu đỏ và thanh toán.。
            'openid' => $openid, //của người nhận thanh toánopenid
            'check_name' => 'NO_CHECK',  //Có ba phương pháp để xác minh tên thật trong tài liệu NO_CHECK OPTION_CHECK FORCE_CHECK
            'amount' => (int)bcmul($amount, '100', 0),  //Đơn vị là xu
            'desc' => $desc,
            'spbill_create_ip' => request()->ip(),  //Địa chỉ IP đã bắt đầu giao dịch
        ];
        $result = self::application()->merchant_pay->send($merchantPayData);
        if ($result->return_code == 'SUCCESS' && $result->result_code != 'FAIL') {
            return true;
        } else {
            throw new ApiException($result->err_code_des ?? 'Thanh toán doanh nghiệp không nhận được thay đổi, vui lòng thử lại sau.');
        }
    }

    /**
     * Tạo đối tượng lệnh thanh toán
     * @param $openid
     * @param $out_trade_no
     * @param $total_fee
     * @param $attach
     * @param $body
     * @param string $detail
     * @param string $trade_type
     * @param array $options
     * @return Order
     */
    protected static function paymentOrder($openid, $out_trade_no, $total_fee, $attach, $body, $detail = '', $trade_type = 'JSAPI', $options = [])
    {
        $total_fee = bcmul($total_fee, 100, 0);
        $order = array_merge(compact('out_trade_no', 'total_fee', 'attach', 'body', 'detail', 'trade_type'), $options);
        if (!is_null($openid)) $order['openid'] = $openid;
        if ($order['detail'] == '') unset($order['detail']);
        return new Order($order);
    }

    /**
     * Nhận đơn đặt hàngID
     * @param $openid
     * @param $out_trade_no
     * @param $total_fee
     * @param $attach
     * @param $body
     * @param string $detail
     * @param string $trade_type
     * @param array $options
     * @return mixed
     */
    public static function paymentPrepare($openid, $out_trade_no, $total_fee, $attach, $body, $detail = '', $trade_type = 'JSAPI', $options = [])
    {
        $key = 'pay_' . $out_trade_no;
        $result = CacheService::get($key);
        if ($result) {
            return $result;
        } else {
            $order = self::paymentOrder($openid, $out_trade_no, $total_fee, $attach, $body, $detail, $trade_type, $options);
            $result = self::paymentService()->prepare($order);
            if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS') {
                CacheService::set($key, $result, 7000);
                return $result;
            } else {
                if ($result->return_code == 'FAIL') {
                    exception('Trả lại lỗi thanh toán WeChat：' . $result->return_msg);
                } else if (isset($result->err_code)) {
                    exception('Trả lại lỗi thanh toán WeChat：' . $result->err_code_des);
                } else {
                    exception('Không nhận được ID thanh toán trước cho WeChat Pay, vui lòng bắt đầu lại thanh toán.!');
                }
                exit;
            }
        }
    }

    /**
     * Nhận ID đơn hàng và thanh toán bằng chương trình mini mới
     * @param $openid
     * @param $out_trade_no
     * @param $total_fee
     * @param $attach
     * @param $body
     * @param string $detail
     * @param string $trade_type
     * @param array $options
     * @return mixed
     */
    public static function newPaymentPrepare($openid, $out_trade_no, $total_fee, $attach, $body, $detail = '', $options = [])
    {
        $key = 'pay_' . $out_trade_no;
        $result = CacheService::get($key);
        if ($result) {
            return $result;
        } else {
            $order = self::paymentOrder($openid, $out_trade_no, $total_fee, $attach, $body, $detail, $options);
            $result = self::application()->minipay->createorder($order);
            if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS') {
                CacheService::set($key, $result, 7000);
                return $result;
            } else {
                if ($result->return_code == 'FAIL') {
                    exception('Trả lại lỗi thanh toán WeChat：' . $result->return_msg);
                } else if (isset($result->err_code)) {
                    exception('Trả lại lỗi thanh toán WeChat：' . $result->err_code_des);
                } else {
                    exception('Không nhận được ID thanh toán trước cho WeChat Pay, vui lòng bắt đầu lại thanh toán.!');
                }
                exit;
            }
        }
    }

    /**
     * Nhận thông số thanh toán jsSdk
     * @param $openid
     * @param $out_trade_no
     * @param $total_fee
     * @param $attach
     * @param $body
     * @param string $detail
     * @param string $trade_type
     * @param array $options
     * @return array|string
     */
    public static function jsPay($openid, $out_trade_no, $total_fee, $attach, $body, $detail = '', $trade_type = 'JSAPI', $options = [])
    {
        $paymentPrepare = self::paymentPrepare($openid, $out_trade_no, $total_fee, $attach, $body, $detail, $trade_type, $options);
        return self::paymentService()->configForJSSDKPayment($paymentPrepare->prepay_id);
    }

    /**
     * Nhận thông số thanh toán jsSdk Thanh toán chương trình nhỏ mới
     * @param $openid
     * @param $out_trade_no
     * @param $total_fee
     * @param $attach
     * @param $body
     * @param string $detail
     * @param string $trade_type
     * @param array $options
     * @return array|string
     */
    public static function newJsPay($openid, $out_trade_no, $total_fee, $attach, $body, $detail = '', $options = [])
    {
        $paymentPrepare = self::newPaymentPrepare($openid, $out_trade_no, $total_fee, $attach, $body, $detail, $options);
        return self::paymentService()->configForJSSDKPayment($paymentPrepare->prepay_id);
    }

    /**
     * Nhận thông số thanh toán APP
     * @param $openid
     * @param $out_trade_no
     * @param $total_fee
     * @param $attach
     * @param $body
     * @param string $detail
     * @param string $trade_type
     * @param array $options
     * @return array|string
     */
    public static function appPay($openid, $out_trade_no, $total_fee, $attach, $body, $detail = '', $trade_type = Order::APP, $options = [])
    {
        $paymentPrepare = self::paymentPrepare($openid, $out_trade_no, $total_fee, $attach, $body, $detail, $trade_type, $options);
        return self::paymentService()->configForAppPayment($paymentPrepare->prepay_id);
    }

    /**
     * Nhận thông số thanh toán gốc
     * @param $openid
     * @param $out_trade_no
     * @param $total_fee
     * @param $attach
     * @param $body
     * @param string $detail
     * @param string $trade_type
     * @param array $options
     * @return array|string
     */
    public static function nativePay($openid, $out_trade_no, $total_fee, $attach, $body, $detail = '', $trade_type = 'NATIVE', $options = [])
    {
        $data = self::paymentPrepare($openid, $out_trade_no, $total_fee, $attach, $body, $detail, $trade_type, $options);
        if ($data) {
            $res['code_url'] = $data['code_url'];
            $res['invalid'] = time() + 60;
            $res['logo'] = sys_config('wap_login_logo');
        } else $res = [];
        return $res;
    }


    /**
     * Hoàn tiền bằng số đơn đặt hàng của người bán
     * @param $orderNo
     * @param $refundNo
     * @param $totalFee
     * @param null $refundFee
     * @param null $opUserId
     * @param string $refundReason
     * @param string $type
     * @param string $refundAccount
     */
    public static function refund($orderNo, $refundNo, $totalFee, $refundFee = null, $opUserId = null, $refundReason = '', $type = 'out_trade_no', $refundAccount = 'REFUND_SOURCE_UNSETTLED_FUNDS')
    {
        $totalFee = floatval($totalFee);
        $refundFee = floatval($refundFee);
        if ($type == 'out_trade_no') {
            return self::paymentService()->refund($orderNo, $refundNo, $totalFee, $refundFee, $opUserId, $type, $refundAccount, $refundReason);
        } else {
            return self::paymentService()->refundByTransactionId($orderNo, $refundNo, $totalFee, $refundFee, $opUserId, $refundAccount, $refundReason);
        }
    }


    public static function payOrderRefund($orderNo, array $opt)
    {
        if (!isset($opt['pay_price'])) throw new AdminException('Thiếupay_price');
        $totalFee = floatval(bcmul($opt['pay_price'], 100, 0));
        $refundFee = isset($opt['refund_price']) ? floatval(bcmul($opt['refund_price'], 100, 0)) : null;
        $refundReason = $opt['desc'] ?? '';
        $refundNo = $opt['refund_id'] ?? $orderNo;
        $opUserId = $opt['op_user_id'] ?? null;
        $type = $opt['type'] ?? 'out_trade_no';
        /*Chỉ dành cho người bán dòng tiền cũ
        REFUND_SOURCE_UNSETTLED_FUNDS---Hoàn tiền cho các khoản tiền chưa thanh toán (hoàn trả các khoản tiền chưa thanh toán được sử dụng theo mặc định)
        REFUND_SOURCE_RECHARGE_FUNDS---Hoàn lại số dư khả dụng*/
        $refundAccount = $opt['refund_account'] ?? 'REFUND_SOURCE_UNSETTLED_FUNDS';
        try {
            $res = (self::refund($orderNo, $refundNo, $totalFee, $refundFee, $opUserId, $refundReason, $type, $refundAccount));
            if ($res->return_code == 'FAIL') throw new AdminException('Hoàn tiền không thành công:{:msg}', ['msg' => $res->return_msg]);
            if (isset($res->err_code)) throw new AdminException('Hoàn tiền không thành công:{:msg}', ['msg' => $res->err_code_des]);
        } catch (\Exception $e) {
            throw new AdminException($e->getMessage());
        }
        return true;
    }

    /**
     * Giao diện gọi lại thanh toán thành công của WeChat
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \EasyWeChat\Core\Exceptions\FaultException
     */
    public static function handleNotify()
    {
        return self::paymentService()->handleNotify(function ($notify, $successful) {

            if ($successful) {

                $data = [
                    'attach' => $notify->attach,
                    'out_trade_no' => $notify->out_trade_no,
                    'transaction_id' => $notify->transaction_id
                ];

                return Event::until('NotifyListener', [$data, PayServices::WEIXIN_PAY]);
            }

            return false;
        });
    }

    /**
     * jsSdk
     * @return \EasyWeChat\Js\Js
     */
    public static function jsService()
    {
        return self::application()->js;
    }

    /**
     * Nhận jsSDK
     * @param string $url
     * @return array|string
     */
    public static function jsSdk($url = '')
    {
        $apiList = ['openAddress', 'updateTimelineShareData', 'updateAppMessageShareData', 'onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo', 'onMenuShareQZone', 'startRecord', 'stopRecord', 'onVoiceRecordEnd', 'playVoice', 'pauseVoice', 'stopVoice', 'onVoicePlayEnd', 'uploadVoice', 'downloadVoice', 'chooseImage', 'previewImage', 'uploadImage', 'downloadImage', 'translateVoice', 'getNetworkType', 'openLocation', 'getLocation', 'hideOptionMenu', 'showOptionMenu', 'hideMenuItems', 'showMenuItems', 'hideAllNonBaseMenuItem', 'showAllNonBaseMenuItem', 'closeWindow', 'scanQRCode', 'chooseWXPay', 'openProductSpecificView', 'addCard', 'chooseCard', 'openCard', 'requestMerchantTransfer'];
        $jsService = self::jsService();
        if ($url) $jsService->setUrl($url);
        try {
            return $jsService->config($apiList);
        } catch (\Exception $e) {
            return '{}';
        }

    }


    /**
     * Trả lời tin nhắn văn bản
     * @param string $content nội dung văn bản
     * @return Text
     */
    public static function textMessage($content)
    {
        return new Text(compact('content'));
    }

    /**
     * Trả lời tin nhắn hình ảnh
     * @param string $media_id tài nguyên truyền thông ID
     * @return Image
     */
    public static function imageMessage($media_id)
    {
        return new Image(compact('media_id'));
    }

    /**
     * Trả lời tin nhắn video
     * @param string $media_id tài nguyên truyền thông ID
     * @param string $title tiêu đề
     * @param string $description mô tả
     * @param null $thumb_media_id bao gồm tài nguyên ID
     * @return Video
     */
    public static function videoMessage($media_id, $title = '', $description = '...', $thumb_media_id = null)
    {
        return new Video(compact('media_id', 'title', 'description', 'thumb_media_id'));
    }

    /**
     * Trả lời tin nhắn thoại
     * @param string $media_id tài nguyên truyền thông ID
     * @return Voice
     */
    public static function voiceMessage($media_id)
    {
        return new Voice(compact('media_id'));
    }

    /**
     * Trả lời tin nhắn đồ họa
     * @param string|array $title tiêu đề
     * @param string $description mô tả
     * @param string $url URL
     * @param string $image Liên kết hình ảnh
     */
    public static function newsMessage($title, $description = '...', $url = '', $image = '')
    {
        if (is_array($title)) {
            if (isset($title[0]) && is_array($title[0])) {
                $newsList = [];
                foreach ($title as $news) {
                    $newsList[] = self::newsMessage($news);
                }
                return $newsList;
            } else {
                $data = $title;
            }
        } else {
            $data = compact('title', 'description', 'url', 'image');
        }
        return new News($data);
    }

    /**
     * Trả lời tin nhắn bài viết
     * @param string|array $title tiêu đề
     * @param string $thumb_media_id Id tài liệu ảnh bìa của thông điệp đồ họa (phải cố định media_ID）
     * @param string $source_url Địa chỉ ban đầu của thông báo đồ họa, tức là nhấp vào“Đọc bài viết gốc”sau đóURL
     * @param string $content Nội dung cụ thể của thông báo đồ họa hỗ trợ các thẻ HTML. Nó phải ít hơn 20.000 ký tự và dưới 1M và sẽ bị xóa tại đây.JS
     * @param string $author tác giả
     * @param string $digest Tóm tắt các tin nhắn đồ họa và văn bản. Chỉ có tin nhắn đồ họa và văn bản duy nhất có tóm tắt. Nhiều tin nhắn đồ họa và văn bản được để trống ở đây.
     * @param int $show_cover_pic Có hiển thị bìa hay không, 0 là sai, tức là không hiển thị, 1 là đúng, tức là hiển thị.
     * @param int $need_open_comment Có mở bình luận thì 0 không mở, 1 mở
     * @param int $only_fans_can_comment Chỉ người hâm mộ mới có thể bình luận, 0 người có thể bình luận, chỉ 1 người hâm mộ có thể bình luận
     * @return Article
     */
    public static function articleMessage($title, $thumb_media_id, $source_url, $content = '', $author = '', $digest = '', $show_cover_pic = 0, $need_open_comment = 0, $only_fans_can_comment = 1)
    {
        $data = is_array($title) ? $title : compact('title', 'thumb_media_id', 'source_url', 'content', 'author', 'digest', 'show_cover_pic', 'need_open_comment', 'only_fans_can_comment');
        return new Article($data);
    }

    /**
     * Trả lời tin nhắn quan trọng
     * @param string $type [mpnews、 mpvideo、voice、image]
     * @param string $media_id vật liệu ID
     * @return Material
     */
    public static function materialMessage($type, $media_id)
    {
        return new Material($type, $media_id);
    }

    /**
     * Gửi dưới dạng tin nhắn dịch vụ khách hàng
     * @param $to
     * @param $message
     * @return bool
     */
    public static function staffTo($to, $message)
    {
        $staff = self::staffService();
        $staff = is_callable($message) ? $staff->message($message()) : $staff->message($message);
        $res = $staff->to($to)->send();
        return $res;
    }

    /**
     * Lấy thông tin người dùng
     * @param array|string $openid
     * @return \EasyWeChat\Support\Collection
     */
    public static function getUserInfo($openid)
    {
        $userService = self::userService();
        $userInfo = [];
        try {
            if (is_array($openid)) {
                $res = $userService->batchGet($openid);
                if (isset($res['user_info_list'])) {
                    $userInfo = $res['user_info_list'];
                } else {
                    throw new AdminException('Không lấy được thông tin người hâm mộ WeChat');
                }
            } else {
                $userInfo = $userService->get($openid);
            }
        } catch (\Throwable $e) {
            throw new AdminException(self::getMessage($e->getMessage()));
        }
        return $userInfo;
    }


    /**
     * Lấy danh sách người dùng
     * @param null $next_openid
     * @return array
     */
    public static function getUsersList($next_openid = null)
    {
        $userService = self::userService();
        $list = [];
        try {
            $res = $userService->lists($next_openid);
            $list['data'] = $res['data']['openid'] ?? [];
            $list['next_openid'] = $res['next_openid'] ?? null;
            return $list;
        } catch (\Exception $e) {
            throw new AdminException(self::getMessage($e->getMessage()));
        }
        return $list;
    }

    /**
     * Lời khuyên thân thiện để xử lý các thông báo lỗi trả về
     * @param string $message
     * @return array|mixed|string
     */
    public static function getMessage(string $message)
    {
        if (strstr($message, 'Request AccessToken fail') !== false) {
            $message = str_replace('Request AccessToken fail. response:', '', $message);
            $message = json_decode($message, true) ?: [];
            $errcode = $message['errcode'] ?? false;
            if ($errcode) {
                $message = $errcode;
            }
        }
        return $message;
    }

    /**
     * Đặt ngành tin nhắn mẫu
     */
    public static function setIndustry($industryOne, $industryTwo)
    {
        return self::application()->new_notice->setIndustry($industryOne, $industryTwo);
    }

    /**
     * Nhận thêm mẫuID
     * @param $key
     * @param $name
     * @return mixed
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/16
     */
    public static function addTemplateId($key, $name)
    {
        try {
            return self::application()->new_notice->addTemplate($key, $name);
        } catch (\Exception $e) {
            throw new AdminException(self::getMessage($e->getMessage()));
        }
    }

    /**
     * Nhận danh sách mẫu
     * @return \EasyWeChat\Support\Collection
     */
    public static function getPrivateTemplates()
    {
        try {
            return self::application()->new_notice->getPrivateTemplates();
        } catch (\Exception $e) {
            throw new AdminException(self::getMessage($e->getMessage()));
        }
    }

    /*
     * Xóa mẫu dựa trên ID mẫu
     */
    public static function deleleTemplate($template_id)
    {
        try {
            return self::application()->new_notice->deletePrivateTemplate($template_id);
        } catch (\Exception $e) {
            throw new AdminException(self::getMessage($e->getMessage()));
        }

    }
}
