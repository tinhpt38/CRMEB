-- -----------------------------------------------------------------------------
-- Patch Phase 4: Cổng thanh toán VN (VNPay, MoMo, ZaloPay) — mỗi cổng một tab
-- Tab: Cài đặt → Cấu hình thanh toán cửa hàng → VNPay / MoMo / ZaloPay
-- Chạy kèm: vn_pay_admin_config.sql, vn_pay_tabs_reorganize.sql
-- Idempotent. Sau khi chạy: php think clear
-- -----------------------------------------------------------------------------

DELETE FROM `eb_system_config` WHERE `menu_name` IN (
  'vn_vnpay_pay_status', 'vn_vnpay_tmn_code', 'vn_vnpay_hash_secret', 'vn_vnpay_sandbox',
  'vn_momo_pay_status', 'vn_momo_partner_code', 'vn_momo_access_key', 'vn_momo_secret_key', 'vn_momo_sandbox',
  'vn_zalopay_pay_status', 'vn_zalopay_app_id', 'vn_zalopay_key1', 'vn_zalopay_key2', 'vn_zalopay_sandbox'
);

INSERT INTO `eb_system_config` (`menu_name`, `type`, `input_type`, `config_tab_id`, `parameter`, `upload_type`, `required`, `width`, `high`, `value`, `info`, `desc`, `sort`, `status`, `level`, `link_id`, `link_value`) VALUES
('vn_vnpay_pay_status', 'radio', 'input', 140, '1=>Hoạt động\n2=>Ngưng hoạt động', 1, '', 0, 0, '\"2\"', 'VNPay', 'Bật thanh toán qua VNPay (thẻ / QR). Cần TMN Code + Hash Secret.', 90, 1, 0, 0, 0),
('vn_vnpay_tmn_code', 'text', 'input', 140, '', 0, '', 100, 0, '\"\"', 'VNPay TMN Code', 'Mã website merchant trên cổng VNPay.', 89, 1, 0, 0, 0),
('vn_vnpay_hash_secret', 'text', 'input', 140, '', 0, '', 100, 0, '\"\"', 'VNPay Hash Secret', 'Chuỗi bí mật ký HMAC SHA512.', 88, 1, 0, 0, 0),
('vn_vnpay_sandbox', 'radio', 'input', 140, '1=>Môi trường thử\n0=>Môi trường thật', 1, '', 0, 0, '\"1\"', 'VNPay môi trường', '1 = sandbox (test), 0 = production.', 87, 1, 0, 0, 0),

('vn_momo_pay_status', 'radio', 'input', 141, '1=>Hoạt động\n2=>Ngưng hoạt động', 1, '', 0, 0, '\"2\"', 'MoMo', 'Bật thanh toán ví MoMo.', 90, 1, 0, 0, 0),
('vn_momo_partner_code', 'text', 'input', 141, '', 0, '', 100, 0, '\"\"', 'MoMo Partner Code', '', 89, 1, 0, 0, 0),
('vn_momo_access_key', 'text', 'input', 141, '', 0, '', 100, 0, '\"\"', 'MoMo Access Key', '', 88, 1, 0, 0, 0),
('vn_momo_secret_key', 'text', 'input', 141, '', 0, '', 100, 0, '\"\"', 'MoMo Secret Key', '', 87, 1, 0, 0, 0),
('vn_momo_sandbox', 'radio', 'input', 141, '1=>Môi trường thử\n0=>Môi trường thật', 1, '', 0, 0, '\"1\"', 'MoMo môi trường', '', 86, 1, 0, 0, 0),

('vn_zalopay_pay_status', 'radio', 'input', 142, '1=>Hoạt động\n2=>Ngưng hoạt động', 1, '', 0, 0, '\"2\"', 'ZaloPay', 'Bật thanh toán ví ZaloPay.', 90, 1, 0, 0, 0),
('vn_zalopay_app_id', 'text', 'input', 142, '', 0, '', 100, 0, '\"\"', 'ZaloPay App ID', '', 89, 1, 0, 0, 0),
('vn_zalopay_key1', 'text', 'input', 142, '', 0, '', 100, 0, '\"\"', 'ZaloPay Key1', 'Dùng ký create order.', 88, 1, 0, 0, 0),
('vn_zalopay_key2', 'text', 'input', 142, '', 0, '', 100, 0, '\"\"', 'ZaloPay Key2', 'Dùng xác minh callback.', 87, 1, 0, 0, 0),
('vn_zalopay_sandbox', 'radio', 'input', 142, '1=>Môi trường thử\n0=>Môi trường thật', 1, '', 0, 0, '\"1\"', 'ZaloPay môi trường', '', 86, 1, 0, 0, 0);
