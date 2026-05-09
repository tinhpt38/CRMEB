-- -----------------------------------------------------------------------------
-- Patch: Tab + cấu hình thanh toán Việt Nam (COD, chuyển khoản/VietQR) trong Admin
-- Đường dẫn: Cài đặt → Cấu hình thanh toán cửa hàng (/setting/other_config/pay/2/23)
-- Chạy một lần trên DB đang vận hành, sau đó: xóa cache CRMEB (runtime/cache) hoặc php think clear
-- -----------------------------------------------------------------------------

-- Tab con (cùng cấp với «Cấu hình cơ bản», WeChat Pay, Alipay…)
INSERT IGNORE INTO `eb_system_config_tab` (`id`, `pid`, `title`, `eng_title`, `status`, `info`, `icon`, `type`, `sort`, `menus_id`) VALUES
(138, 23, 'Thanh toán Việt Nam (COD & VietQR)', 'vn_pay', 1, 0, '', 3, 98, 1063);

-- Gỡ bản ghi trùng menu_name (nếu chạy lại patch)
DELETE FROM `eb_system_config` WHERE `menu_name` IN ('vn_cod_pay_status', 'vn_bank_pay_status', 'vn_bank_pay_guide', 'vn_bank_pay_qr_image');

INSERT INTO `eb_system_config` (`id`, `menu_name`, `type`, `input_type`, `config_tab_id`, `parameter`, `upload_type`, `required`, `width`, `high`, `value`, `info`, `desc`, `sort`, `status`, `level`, `link_id`, `link_value`) VALUES
(50150, 'vn_cod_pay_status', 'radio', 'input', 138, '1=>Bật\n2=>Tắt', 1, '', 0, 0, '\"2\"', 'COD (thanh toán khi nhận)', 'Bật phương thức COD cho đơn giao hàng vật lý (không áp dụng hàng ảo).', 88, 1, 0, 0, 0),
(50151, 'vn_bank_pay_status', 'radio', 'input', 138, '1=>Bật\n2=>Tắt', 1, '', 0, 0, '\"2\"', 'Chuyển khoản / VietQR', 'Bật phương thức chuyển khoản; xác nhận thanh toán thủ công ở quản trị.', 87, 1, 0, 0, 0),
(50152, 'vn_bank_pay_guide', 'textarea', '', 138, '', 0, '', 100, 5, '\"\"', 'Hướng dẫn CK/VietQR', 'Hiển thị cho khách: STK, chủ TK, ngân hàng, nội dung CK, link QR…', 86, 1, 0, 0, 0),
(50153, 'vn_bank_pay_qr_image', 'upload', 'input', 138, '', 1, '', 0, 0, '\"\"', 'Ảnh mã QR chuyển khoản', 'Tải ảnh mã QR nhận tiền để hiển thị cho khách ở trang thanh toán chuyển khoản.', 85, 1, 0, 0, 0);
