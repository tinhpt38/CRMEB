<?php
declare(strict_types=1);

namespace crmeb\utils;

/**
 * Hỗ trợ hiển thị hướng dẫn CK/VietQR theo từng đơn hàng.
 */
class VnBankPayHelper
{
    /**
     * Nội dung chuyển khoản mặc định: tiền tố + mã đơn.
     */
    public static function transferContent(string $orderId): string
    {
        $prefix = trim((string)sys_config('vn_bank_transfer_prefix', 'DH'));
        $orderId = trim($orderId);
        if ($prefix === '') {
            return $orderId;
        }
        return $prefix . ' ' . $orderId;
    }

    /**
     * Thay placeholder trong mẫu hướng dẫn CK.
     * Hỗ trợ: {order_id}, {pay_price}, {transfer_content}, {amount}
     */
    public static function renderGuide(string $template, string $orderId, $payPrice): string
    {
        $transfer = self::transferContent($orderId);
        $amount = is_numeric($payPrice) ? number_format((float)$payPrice, 0, ',', '.') : (string)$payPrice;
        $replacements = [
            '{order_id}' => $orderId,
            '{pay_price}' => $amount,
            '{amount}' => $amount,
            '{transfer_content}' => $transfer,
        ];
        return strtr($template, $replacements);
    }

    /**
     * URL ảnh VietQR động (img.vietqr.io) khi đã cấu hình STK + BIN ngân hàng.
     * Trả rỗng nếu thiếu cấu hình hoặc đã có ảnh QR tĩnh.
     */
    public static function dynamicQrUrl(string $orderId, $payPrice): string
    {
        $accountNo = trim((string)sys_config('vn_bank_account_no', ''));
        $bankBin = trim((string)sys_config('vn_bank_bin', ''));
        if ($accountNo === '' || $bankBin === '') {
            return '';
        }
        $amount = (int)round((float)$payPrice);
        if ($amount <= 0) {
            return '';
        }
        $accountName = trim((string)sys_config('vn_bank_account_name', ''));
        $transfer = rawurlencode(self::transferContent($orderId));
        $url = sprintf(
            'https://img.vietqr.io/image/%s-%s-compact.png?amount=%d&addInfo=%s',
            $bankBin,
            $accountNo,
            $amount,
            $transfer
        );
        if ($accountName !== '') {
            $url .= '&accountName=' . rawurlencode($accountName);
        }
        return $url;
    }

    /**
     * QR ưu tiên: ảnh tĩnh admin upload, không thì VietQR động.
     */
    public static function resolveQrImage(string $orderId, $payPrice): string
    {
        $static = trim((string)sys_config('vn_bank_pay_qr_image', ''));
        if ($static !== '') {
            return $static;
        }
        return self::dynamicQrUrl($orderId, $payPrice);
    }
}
