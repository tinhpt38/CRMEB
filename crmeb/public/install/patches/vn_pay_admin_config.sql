-- -----------------------------------------------------------------------------
-- Patch Phase 4 (bước 1): COD (tab cơ bản) + VietQR (tab riêng)
-- Đường dẫn: Cài đặt → Cấu hình thanh toán cửa hàng
-- Chạy kèm: vn_pay_gateways.sql, vn_pay_tabs_reorganize.sql
-- Idempotent: không gán id cố định cho eb_system_config.
-- Sau khi chạy: php think clear
-- -----------------------------------------------------------------------------

DELETE FROM `eb_system_config` WHERE `menu_name` IN (
  'vn_cod_pay_status', 'vn_cod_max_amount',
  'vn_bank_pay_status', 'vn_bank_pay_guide', 'vn_bank_pay_qr_image',
  'vn_bank_transfer_prefix', 'vn_bank_account_no', 'vn_bank_bin', 'vn_bank_account_name'
);

INSERT INTO `eb_system_config` (`menu_name`, `type`, `input_type`, `config_tab_id`, `parameter`, `upload_type`, `required`, `width`, `high`, `value`, `info`, `desc`, `sort`, `status`, `level`, `link_id`, `link_value`) VALUES
('vn_cod_pay_status', 'radio', 'input', 109, '1=>Hoạt động\n2=>Ngưng hoạt động', 1, '', 0, 0, '\"2\"', 'COD (thanh toán khi nhận)', 'Bật phương thức COD cho đơn giao hàng vật lý (không áp dụng hàng ảo).', 94, 1, 0, 0, 0),
('vn_cod_max_amount', 'text', 'number', 109, '', 0, '', 100, 0, '\"0\"', 'Hạn mức COD (đ)', '0 = không giới hạn. Từ chối đơn COD vượt hạn mức.', 93, 1, 0, 0, 0),

('vn_bank_pay_status', 'radio', 'input', 139, '1=>Hoạt động\n2=>Ngưng hoạt động', 1, '', 0, 0, '\"2\"', 'Chuyển khoản / VietQR', 'Bật phương thức chuyển khoản; xác nhận thanh toán thủ công ở quản trị.', 87, 1, 0, 0, 0),
('vn_bank_pay_guide', 'textarea', '', 139, '', 0, '', 100, 5, '\"\"', 'Hướng dẫn CK/VietQR', 'Hiển thị cho khách: STK, chủ TK, ngân hàng, nội dung CK, link QR… Hỗ trợ placeholder: {order_id}, {pay_price}, {transfer_content}, {amount}', 86, 1, 0, 0, 0),
('vn_bank_pay_qr_image', 'upload', 'input', 139, '', 1, '', 0, 0, '\"\"', 'Ảnh mã QR chuyển khoản', 'Tải ảnh mã QR nhận tiền. Nếu để trống và đã cấu hình STK + BIN, hệ thống tạo VietQR động.', 85, 1, 0, 0, 0),
('vn_bank_transfer_prefix', 'text', 'input', 139, '', 0, '', 100, 0, '\"DH\"', 'Tiền tố nội dung CK', 'VD: DH → nội dung «DH cp123456».', 84, 1, 0, 0, 0),
('vn_bank_account_no', 'text', 'input', 139, '', 0, '', 100, 0, '\"\"', 'Số tài khoản nhận CK', 'Dùng tạo VietQR động (img.vietqr.io) nếu chưa upload QR tĩnh.', 83, 1, 0, 0, 0),
('vn_bank_bin', 'text', 'input', 139, '', 0, '', 100, 0, '\"\"', 'Mã BIN ngân hàng', 'VD: 970422 (MB), 970436 (VCB).', 82, 1, 0, 0, 0),
('vn_bank_account_name', 'text', 'input', 139, '', 0, '', 100, 0, '\"\"', 'Chủ tài khoản', 'Hiển thị trên VietQR động.', 81, 1, 0, 0, 0);
