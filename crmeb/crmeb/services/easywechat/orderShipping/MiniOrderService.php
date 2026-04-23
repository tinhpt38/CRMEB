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
namespace crmeb\services\easywechat\orderShipping;

use crmeb\services\easywechat\Application;
use crmeb\services\SystemConfigService;
use EasyWeChat\Core\Exceptions\HttpException;

class MiniOrderService
{
    /**
     * @var Application
     */
    protected static $instance;

    /**
     * @param array $config
     * @return array[]
     *
     * @date 2023/05/09
     * @author yyw
     */
    protected static function options(array $config = [])
    {
        $payment = SystemConfigService::more(['routine_appId', 'routine_appsecret', 'pay_weixin_mchid', 'pay_new_weixin_open', 'pay_new_weixin_mchid', 'wechat_token', 'wechat_encodingaeskey']);
        return [
            'mini_program' => [
                'app_id' => $payment['routine_appId'] ?? '',
                'secret' => $payment['routine_appsecret'] ?? '',
                'merchant_id' => empty($payment['pay_new_weixin_open']) ? trim($payment['pay_weixin_mchid']) : trim($payment['pay_new_weixin_mchid']),
            ]
        ];
    }

    /**
     * khởi tạo
     * @param bool $cache
     * @return Application
     */
    protected static function application($cache = false)
    {
        (self::$instance === null || $cache === true) && (self::$instance = new Application(self::options()));
        return self::$instance;
    }

    protected static function order()
    {
        return self::application()->order_ship;
    }


    /**
     * Tải lên thứ tự
     * @param string $out_trade_no Số đơn hàng(Đơn hàng trung tâm mua sắm tốt)
     * @param int $logistics_type Chế độ hậu cần, giá trị liệt kê phương thức giao hàng: 1. Phân phối hậu cần vật lý áp dụng hình thức phân phối hậu cần vật lý của các công ty chuyển phát nhanh 2. Giao hàng trong cùng thành phố 3. Hàng hóa ảo, hàng hóa ảo, chẳng hạn như nạp tiền điện thoại, thẻ điểm, v.v., không có hình thức giao hàng thực tế 4. Người dùng tự nhận hàng
     * @param array $shipping_list Danh sách thông tin hậu cần, danh sách đơn hàng hậu cần giao hàng, hỗ trợ giao hàng thống nhất (đơn hàng hậu cần đơn) và giao hàng chia nhỏ (nhiều đơn hàng hậu cần), đa dạng: [1, 10]
     * @param string $payer_openid Người trả tiền, thông tin người trả tiền
     * @param int $delivery_mode Chế độ phân phối, giá trị liệt kê của chế độ phân phối: 1. UNIFIED_DELIVERY (phân phối thống nhất) 2. SPLIT_DELIVERY (phân phối chia tách) Các giá trị ví dụ: UNIFIED_DELIVERY
     * @param bool $is_all_delivered Cần thiết khi sử dụng chế độ vận chuyển chia nhỏ. Nó được sử dụng để xác định xem tất cả các lô hàng đã được hoàn thành ở chế độ vận chuyển chia nhỏ hay chưa. Chỉ khi tất cả các lô hàng được hoàn thành thì thông báo hoàn thành vận chuyển mới được gửi đến người dùng. Giá trị mẫu: true/false
     * @return array
     *
     * @throws HttpException
     * @date 2023/05/09
     * @author yyw
     */
    public static function shippingByTradeNo(string $out_trade_no, int $logistics_type, array $shipping_list, string $payer_openid, string $path, int $delivery_mode = 1, bool $is_all_delivered = true)
    {
        return self::order()->shippingByTradeNo($out_trade_no, $logistics_type, $shipping_list, $payer_openid, $path, $delivery_mode, $is_all_delivered);
    }

    /**
     * Nhận danh sách đơn hàng quản lý giao hàng chương trình mini
     * @param array $params
     * @return array
     * @throws HttpException
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2025/8/14
     */
    public static function shippingOrderList($params = [])
    {
        return self::order()->shippingOrderList($params);
    }

    /**
     * thứ tự kết hợp
     * @param string $out_trade_no
     * @param int $logistics_type
     * @param array $sub_orders
     * @param string $payer_openid
     * @param int $delivery_mode
     * @param bool $is_all_delivered
     * @return array
     * @throws HttpException
     *
     * @date 2023/05/10
     * @author yyw
     */
    public static function combinedShippingByTradeNo(string $out_trade_no, int $logistics_type, array $sub_orders, string $payer_openid, int $delivery_mode = 2, bool $is_all_delivered = false)
    {
        return self::order()->combinedShippingByTradeNo($out_trade_no, $logistics_type, $sub_orders, $payer_openid, $delivery_mode, $is_all_delivered);
    }

    /**
     * Ký nhận thông báo
     * @param string $merchant_trade_no
     * @param string $received_time
     * @return array
     *
     * @date 2023/05/09
     * @author yyw
     */
    public static function notifyConfirmByTradeNo(string $merchant_trade_no, string $received_time)
    {
        return self::order()->notifyConfirmByTradeNo($merchant_trade_no, $received_time);
    }

    /**
     * Xác định xem có nên mở không
     * @return bool
     * @throws HttpException
     *
     * @date 2023/05/17
     * @author yyw
     */
    public static function isManaged()
    {
        return self::order()->checkManaged();
    }


    /**
     * Đặt đường dẫn nhảy sửa chữa nhỏ
     * @param $path
     * @return array
     * @throws HttpException
     *
     * @date 2023/05/10
     * @author yyw
     */
    public static function setMesJumpPathAndCheck($path)
    {
        return self::order()->setMesJumpPathAndCheck($path);
    }


}
