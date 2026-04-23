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

namespace crmeb\services\pay;

/**
 * Lớp giao diện thanh toán
 * Interface PayInterface
 * @package crmeb\services\pay
 */
interface PayInterface
{

    /**
     * Đặt loại thanh toán
     * @param string $type Hình thức thanh toán
     * @return $this
     */
    public function setPayType(string $type);

    /**
     * Tạo thanh toán
     * @param string $orderId Số đơn hàng
     * @param string $totalFee Số tiền thanh toán
     * @param string $attach Nội dung gọi lại
     * @param string $body chi trảbody
     * @param string $detail Chi tiết
     * @param string $tradeType Hình thức thanh toán
     * @param array $options Các thông số khác
     * @return mixed
     */
    public function create(string $orderId, string $totalFee, string $attach, string $body, string $detail, array $options = []);

    /**
     * Doanh nghiệp trả tiền thay đổi
     * @param string $openid openid
     * @param string $orderId Đặt hàngid
     * @param string $amount Số tiền thanh toán
     * @param array $options Các thông số khác
     * @return mixed
     */
    public function merchantPay(string $openid, string $orderId, string $amount, array $options = []);

    /**
     * Đền bù
     * @param string $outTradeNo Số đơn hàng hoàn tiền
     * @param string $totalAmount Số tiền hoàn lại
     * @param string $refund_id Đền bù
     * @param array $options Các thông số khác
     * @return mixed
     */
    public function refund(string $outTradeNo, array $options = []);

    /**
     * Thứ tự truy vấn
     * @param string $outTradeNo Số đơn hàng hoàn tiền
     * @param string $outRequestNo Số đơn đặt hàng thanh toán của người bán
     * @param array $other Các thông số khác
     * @return mixed
     */
    public function queryRefund(string $outTradeNo, string $outRequestNo, array $other = []);

    /**
     * Hoàn vốn
     * @return mixed
     */
    public function handleNotify();

}
