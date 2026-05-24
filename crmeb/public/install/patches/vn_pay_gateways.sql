-- -----------------------------------------------------------------------------
-- Patch Phase 4: Cổng thanh toán VN (VNPay, MoMo, ZaloPay) + củng cố COD/VietQR
-- Tab: Cài đặt → Cấu hình thanh toán cửa hàng → «Thanh toán Việt Nam»
-- Idempotent: xóa theo menu_name, INSERT không gán id (tránh trùng PRIMARY trên DB đang vận hành).
-- Sau khi chạy: php think clear
-- -----------------------------------------------------------------------------

DELETE FROM `eb_system_config` WHERE `menu_name` IN (
  'vn_vnpay_pay_status', 'vn_vnpay_tmn_code', 'vn_vnpay_hash_secret', 'vn_vnpay_sandbox',
  'vn_momo_pay_status', 'vn_momo_partner_code', 'vn_momo_access_key', 'vn_momo_secret_key', 'vn_momo_sandbox',
  'vn_zalopay_pay_status', 'vn_zalopay_app_id', 'vn_zalopay_key1', 'vn_zalopay_key2', 'vn_zalopay_sandbox',
  'vn_cod_max_amount', 'vn_bank_account_no', 'vn_bank_bin', 'vn_bank_account_name', 'vn_bank_transfer_prefix'
);

INSERT INTO `eb_system_config` (`menu_name`, `type`, `input_type`, `config_tab_id`, `parameter`, `upload_type`, `required`, `width`, `high`, `value`, `info`, `desc`, `sort`, `status`, `level`, `link_id`, `link_value`) VALUES
('vn_vnpay_pay_status', 'radio', 'input', 138, '1=>Bật\n2=>Tắt', 1, '', 0, 0, '\"2\"', 'VNPay', 'Bật thanh toán qua VNPay (thẻ / QR). Cần TMN Code + Hash Secret.', 84, 1, 0, 0, 0),
('vn_vnpay_tmn_code', 'text', 'input', 138, '', 0, '', 100, 0, '\"\"', 'VNPay TMN Code', 'Mã website merchant trên cổng VNPay.', 83, 1, 0, 0, 0),
('vn_vnpay_hash_secret', 'text', 'input', 138, '', 0, '', 100, 0, '\"\"', 'VNPay Hash Secret', 'Chuỗi bí mật ký HMAC SHA512.', 82, 1, 0, 0, 0),
('vn_vnpay_sandbox', 'radio', 'input', 138, '1=>Sandbox\n0=>Production', 1, '', 0, 0, '\"1\"', 'VNPay môi trường', '1 = sandbox (test), 0 = production.', 81, 1, 0, 0, 0),

('vn_momo_pay_status', 'radio', 'input', 138, '1=>Bật\n2=>Tắt', 1, '', 0, 0, '\"2\"', 'MoMo', 'Bật thanh toán ví MoMo.', 80, 1, 0, 0, 0),
('vn_momo_partner_code', 'text', 'input', 138, '', 0, '', 100, 0, '\"\"', 'MoMo Partner Code', '', 79, 1, 0, 0, 0),
('vn_momo_access_key', 'text', 'input', 138, '', 0, '', 100, 0, '\"\"', 'MoMo Access Key', '', 78, 1, 0, 0, 0),
('vn_momo_secret_key', 'text', 'input', 138, '', 0, '', 100, 0, '\"\"', 'MoMo Secret Key', '', 77, 1, 0, 0, 0),
('vn_momo_sandbox', 'radio', 'input', 138, '1=>Sandbox\n0=>Production', 1, '', 0, 0, '\"1\"', 'MoMo môi trường', '', 76, 1, 0, 0, 0),

('vn_zalopay_pay_status', 'radio', 'input', 138, '1=>Bật\n2=>Tắt', 1, '', 0, 0, '\"2\"', 'ZaloPay', 'Bật thanh toán ví ZaloPay.', 75, 1, 0, 0, 0),
('vn_zalopay_app_id', 'text', 'input', 138, '', 0, '', 100, 0, '\"\"', 'ZaloPay App ID', '', 74, 1, 0, 0, 0),
('vn_zalopay_key1', 'text', 'input', 138, '', 0, '', 100, 0, '\"\"', 'ZaloPay Key1', 'Dùng ký create order.', 73, 1, 0, 0, 0),
('vn_zalopay_key2', 'text', 'input', 138, '', 0, '', 100, 0, '\"\"', 'ZaloPay Key2', 'Dùng xác minh callback.', 72, 1, 0, 0, 0),
('vn_zalopay_sandbox', 'radio', 'input', 138, '1=>Sandbox\n0=>Production', 1, '', 0, 0, '\"1\"', 'ZaloPay môi trường', '', 71, 1, 0, 0, 0),

('vn_cod_max_amount', 'text', 'number', 138, '', 0, '', 100, 0, '\"0\"', 'Hạn mức COD (đ)', '0 = không giới hạn. Từ chối đơn COD vượt hạn mức.', 70, 1, 0, 0, 0),
('vn_bank_transfer_prefix', 'text', 'input', 138, '', 0, '', 100, 0, '\"DH\"', 'Tiền tố nội dung CK', 'VD: DH → nội dung «DH cp123456».', 69, 1, 0, 0, 0),
('vn_bank_account_no', 'text', 'input', 138, '', 0, '', 100, 0, '\"\"', 'Số tài khoản nhận CK', 'Dùng tạo VietQR động (img.vietqr.io) nếu chưa upload QR tĩnh.', 68, 1, 0, 0, 0),
('vn_bank_bin', 'text', 'input', 138, '', 0, '', 100, 0, '\"\"', 'Mã BIN ngân hàng', 'VD: 970422 (MB), 970436 (VCB).', 67, 1, 0, 0, 0),
('vn_bank_account_name', 'text', 'input', 138, '', 0, '', 100, 0, '\"\"', 'Chủ tài khoản', 'Hiển thị trên VietQR động.', 66, 1, 0, 0, 0);

-- Gợi ý mẫu hướng dẫn CK (cập nhật thủ công nếu đã có nội dung):
-- UPDATE eb_system_config SET value = '"Ngân hàng: ...\nSTK: ...\nChủ TK: ...\nSố tiền: {pay_price} đ\nNội dung: {transfer_content}"'
-- WHERE menu_name = 'vn_bank_pay_guide';
