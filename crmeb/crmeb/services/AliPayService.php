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

namespace crmeb\services;

use Alipay\EasySDK\Payment\Wap\Models\AlipayTradeWapPayResponse;
use app\services\pay\PayServices;
use app\services\system\SystemPemServices;
use think\facade\Event;
use think\facade\Log;
use think\facade\Route as Url;
use Alipay\EasySDK\Kernel\Config;
use Alipay\EasySDK\Kernel\Factory;
use crmeb\exceptions\PayException;
use Alipay\EasySDK\Kernel\Util\ResponseChecker;

/**
 * Class AliPayService
 * @package crmeb\services
 */
class AliPayService
{

    /**
     * Cấu hình
     * @var array
     */
    protected $config = [
        'appId' => '',
        'merchantPrivateKey' => '',//Áp dụng khóa riêng
        'alipayPublicKey' => '',//Khóa công khai Alipay
        'notifyUrl' => '',//Địa chỉ dịch vụ nhận thông báo không đồng bộ có thể được đặt
        'encryptKey' => '',//Khóa AES có thể được đặt, điều này được yêu cầu khi gọi các giao diện liên quan đến mã hóa và giải mã AES (tùy chọn）
        'alipayCertPath' => '',//Đường dẫn chứng chỉ Alipay(Không bắt buộc)
        'alipayRootCertPath' => '',//Đường dẫn chứng chỉ gốc Alipay(Không bắt buộc)
        'merchantCertPath' => '',//Đường dẫn chứng chỉ người bán(Không bắt buộc)
    ];

    /**
     * @var ResponseChecker
     */
    protected $response;

    /**
     * @var static
     */
    protected static $instance;

    /**
     * AliPayService constructor.
     * @param array $config
     */
    protected function __construct(array $config = [])
    {
        if (!$config) {
            $config = [
                'appId' => sys_config('ali_pay_appid'),
                'merchantPrivateKey' => sys_config('alipay_merchant_private_key'),
                'alipayPublicKey' => sys_config('alipay_public_key'),
                'notifyUrl' => sys_config('site_url') . Url::buildUrl('/api/pay/notify/alipay'),
                'alipayCertPath' => $this->getPemPath('alipay_cert_path'),
                'alipayRootCertPath' => $this->getPemPath('alipay_root_cert_path'),
                'merchantCertPath' => $this->getPemPath('merchant_cert_path'),
            ];
        }
        $this->config = array_merge($this->config, $config);
        $this->initialize();
        $this->response = new ResponseChecker();
    }

    public function getPemPath(string $name)
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
     * Khởi tạo
     * @param array $config
     * @return static
     */
    public static function instance(array $config = [])
    {
        if (is_null(self::$instance)) {
            self::$instance = new static($config);
        }
        return self::$instance;
    }

    /**
     * khởi tạo
     */
    protected function initialize()
    {
        Factory::setOptions($this->getOptions());
    }

    /**
     * Đặt cấu hình
     * @return Config
     */
    protected function getOptions()
    {
        $options = new Config();
        $options->protocol = 'https';
        $options->gatewayHost = 'openapi.alipay.com';
        $options->signType = 'RSA2';

        $options->appId = $this->config['appId'];
        // Để tránh khóa riêng bị rò rỉ cùng với mã nguồn, bạn nên đọc chuỗi khóa riêng tư từ tệp thay vì ghi nó vào mã nguồn.
        $options->merchantPrivateKey = $this->config['merchantPrivateKey'];

        if (sys_config('alipay_sign_type') == 0) {
            // Chế độ phím
            $options->alipayPublicKey = $this->config['alipayPublicKey'];
        } else {
            // Chế độ chứng chỉ
            $options->alipayCertPath = $this->config['alipayCertPath'];
            $options->alipayRootCertPath = $this->config['alipayRootCertPath'];
            $options->merchantCertPath = $this->config['merchantCertPath'];
            $options->alipayPublicKey = '';
        }
        //Bạn có thể đặt địa chỉ dịch vụ nhận thông báo không đồng bộ (tùy chọn）
        $options->notifyUrl = $this->config['notifyUrl'];
        //Khóa AES có thể được đặt, điều này được yêu cầu khi gọi các giao diện liên quan đến mã hóa và giải mã AES (tùy chọn）
        if ($this->config['encryptKey']) {
            $options->encryptKey = $this->config['encryptKey'];
        }

        return $options;
    }

    /**
     * Tạo đơn hàng
     * @param string $title Tên sản phẩm
     * @param string $orderId Số đơn hàng
     * @param string $totalAmount Số tiền thanh toán
     * @param string $passbackParams Nhận xét
     * @param string $quitUrl Địa chỉ nhảy đồng bộ
     * @param string $returnUrl
     * @param bool $isCode
     * @return AlipayTradeWapPayResponse
     */
    public function create(string $title, string $orderId, string $totalAmount, string $passbackParams, string $quitUrl = '', string $returnUrl = '', bool $isCode = false)
    {
        $title = trim($title);
        try {
            if ($isCode) {
                //thanh toán bằng mã QR
                $result = Factory::payment()->faceToFace()->optional('passback_params', $passbackParams)->precreate($title, $orderId, $totalAmount);
            } else if (request()->isApp()) {
                //appchi trả
                $result = Factory::payment()->app()->optional('passback_params', $passbackParams)->pay($title, $orderId, $totalAmount);
            } else {
                //h5chi trả
                $result = Factory::payment()->wap()->optional('passback_params', $passbackParams)->pay($title, $orderId, $totalAmount, $quitUrl, $returnUrl);
            }
            if ($this->response->success($result)) {
                return $result->body ?? $result;
            } else {
                throw new PayException('Lý do thất bại:' . $result->msg . ',' . $result->subMsg);
            }
        } catch (\Exception $e) {
            throw new PayException($e->getMessage());
        }
    }

    /**
     * Hoàn tiền đơn hàng
     * @param string $outTradeNo Số đơn hàng
     * @param string $totalAmount Số tiền hoàn lại
     * @param string $refund_id Số đơn hàng hoàn tiền
     * @return \Alipay\EasySDK\Payment\Common\Models\AlipayTradeRefundResponse
     */
    public function refund(string $outTradeNo, string $totalAmount, string $refund_id)
    {
        try {
            $result = Factory::payment()->common()->refund($outTradeNo, $totalAmount, $refund_id);
            if ($this->response->success($result)) {
                return $result;
            } else {
                throw new PayException('Lý do thất bại:' . $result->msg . ',' . $result->subMsg);
            }
        } catch (\Exception $e) {
            throw new PayException($e->getMessage());
        }
    }

    /**
     * Truy vấn thông tin mã lệnh hoàn tiền giao dịch
     * @param string $outTradeNo
     * @param string $outRequestNo
     * @return \Alipay\EasySDK\Payment\Common\Models\AlipayTradeFastpayRefundQueryResponse
     */
    public function queryRefund(string $outTradeNo, string $outRequestNo)
    {
        try {
            $result = Factory::payment()->common()->queryRefund($outTradeNo, $outRequestNo);
            if ($this->response->success($result)) {
                return $result;
            } else {
                throw new PayException('Lý do thất bại:' . $result->msg . ',' . $result->subMsg);
            }
        } catch (\Exception $e) {
            throw new PayException($e->getMessage());
        }
    }

    /**
     * Trả tiền gọi lại không đồng bộ
     * @return string
     */
    public static function handleNotify()
    {
        return self::instance()->notify(function ($notify) {
            if (isset($notify->out_trade_no)) {

                $data = [
                    'attach' => $notify->attach,
                    'out_trade_no' => $notify->out_trade_no,
                    'transaction_id' => $notify->trade_no
                ];

                return Event::until('NotifyListener', [$data, PayServices::ALIAPY_PAY]);
            }
            return false;
        });
    }

    /**
     * Gọi lại không đồng bộ
     * @param callable $notifyFn
     * @return string
     */
    public function notify(callable $notifyFn)
    {
        app()->request->filter(['trim']);
        $paramInfo = app()->request->param();
        if (isset($paramInfo['type'])) {
            unset($paramInfo['type']);
        }
        //Số đơn đặt hàng của người bán
        $postOrder['out_trade_no'] = $paramInfo['out_trade_no'] ?? '';
        //Số giao dịch Alipay
        $postOrder['trade_no'] = $paramInfo['trade_no'] ?? '';
        //trạng thái giao dịch
        $postOrder['trade_status'] = $paramInfo['trade_status'] ?? '';
        //Nhận xét
        $postOrder['attach'] = isset($paramInfo['passback_params']) ? urldecode($paramInfo['passback_params']) : '';
        if (in_array($paramInfo['trade_status'], ['TRADE_SUCCESS', 'TRADE_FINISHED']) && $this->verifyNotify($paramInfo)) {
            try {
                if ($notifyFn((object)$postOrder)) {
                    return 'success';
                }
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                Log::error('Cuộc gọi lại không đồng bộ của Alipay sẽ thành công,Lỗi chức năng thực thi. Số thứ tự lỗi：' . $postOrder['out_trade_no']);
            }
        }
        return 'fail';

    }

    /**
     * Xác minh
     * @return bool
     */
    protected function verifyNotify(array $param)
    {
        try {
            return Factory::payment()->common()->verifyNotify($param);
        } catch (\Exception $e) {
            Log::error('Gọi lại Alipay thành công,Đã xảy ra lỗi trong quá trình xác minh chữ ký, nguyên nhân gây ra lỗi:' . $e->getMessage());
        }
        return false;
    }

    /**
     * Giao diện thanh toán của người bán
     *
     * @param array $bizParams Thông số kinh doanh
     * @return mixed|false Kết quả thanh toán hoặc sai
     * @throws ngoại lệ thanh toán PayException
     */
    public function merchantPay(array $bizParams, $alipaySignType = 0)
    {
        try {
            // Gọi phương thức chung của lớp nhà máy để thực hiện thao tác chuyển Alipay
            $method = $alipaySignType == 0 ? 'alipay.fund.trans.toaccount.transfer' : 'alipay.fund.trans.uni.transfer';
            $result = Factory::util()->generic()->execute($method, [], $bizParams);
            // Xác định xem thanh toán có thành công hay không
            if ($this->response->success($result)) {
                return $result;
            } else {
                Log::error('Chuyển khoản Alipay không thành công, lý do thất bại:' . $result->msg . ' | ' . $result->subCode . ' | ' . $result->subMsg);
                return false;
            }
        } catch (\Exception $e) {
            // Đăng nhập và trả lạifalse
            Log::error('Chuyển khoản Alipay không thành công, lý do thất bại:' . $e->getMessage());
            return false;
        }
    }

}
