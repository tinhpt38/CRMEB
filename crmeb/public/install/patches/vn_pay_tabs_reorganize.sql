-- -----------------------------------------------------------------------------
-- Patch: Tái cấu trúc tab thanh toán VN + ẩn WeChat / Alipay / Tonglian
-- Đường dẫn: Cài đặt → Cấu hình thanh toán cửa hàng
--
-- Cấu hình cơ bản (109): COD + số dư
-- Tab riêng: VietQR (139), VNPay (140), MoMo (141), ZaloPay (142)
-- Idempotent. Sau khi chạy: php think clear
-- -----------------------------------------------------------------------------

-- Ẩn tab Trung Quốc / tab gộp cũ
UPDATE `eb_system_config_tab`
SET `status` = 0
WHERE `id` IN (4, 63, 108, 138);

-- Tab thanh toán VN (mỗi cổng một tab)
INSERT INTO `eb_system_config_tab` (`id`, `pid`, `title`, `eng_title`, `status`, `info`, `icon`, `type`, `sort`, `menus_id`) VALUES
(139, 23, 'Chuyển khoản / VietQR', 'vn_vietqr', 1, 0, '', 3, 90, 1063),
(140, 23, 'VNPay', 'vn_vnpay', 1, 0, '', 3, 80, 1063),
(141, 23, 'MoMo', 'vn_momo', 1, 0, '', 3, 70, 1063),
(142, 23, 'ZaloPay', 'vn_zalopay', 1, 0, '', 3, 60, 1063)
ON DUPLICATE KEY UPDATE
  `pid` = VALUES(`pid`),
  `title` = VALUES(`title`),
  `eng_title` = VALUES(`eng_title`),
  `status` = VALUES(`status`),
  `sort` = VALUES(`sort`),
  `menus_id` = VALUES(`menus_id`);

-- Ẩn PTTT không liên quan trong tab cơ bản
UPDATE `eb_system_config`
SET `status` = 0,
    `value` = CASE `menu_name`
        WHEN 'pay_weixin_open' THEN '"0"'
        WHEN 'ali_pay_status' THEN '"0"'
        WHEN 'friend_pay_status' THEN '0'
        WHEN 'offline_pay_status' THEN '"2"'
        ELSE `value`
    END
WHERE `config_tab_id` = 109
  AND `menu_name` IN (
    'pay_weixin_open',
    'ali_pay_status',
    'friend_pay_status',
    'offline_pay_status'
  );

-- Cấu hình cơ bản: COD + hạn mức COD
UPDATE `eb_system_config`
SET `config_tab_id` = 109
WHERE `menu_name` IN ('vn_cod_pay_status', 'vn_cod_max_amount');

-- VietQR / chuyển khoản
UPDATE `eb_system_config`
SET `config_tab_id` = 139
WHERE `menu_name` IN (
  'vn_bank_pay_status',
  'vn_bank_pay_guide',
  'vn_bank_pay_qr_image',
  'vn_bank_transfer_prefix',
  'vn_bank_account_no',
  'vn_bank_bin',
  'vn_bank_account_name'
);

-- VNPay
UPDATE `eb_system_config`
SET `config_tab_id` = 140
WHERE `menu_name` IN (
  'vn_vnpay_pay_status',
  'vn_vnpay_tmn_code',
  'vn_vnpay_hash_secret',
  'vn_vnpay_sandbox'
);

-- MoMo
UPDATE `eb_system_config`
SET `config_tab_id` = 141
WHERE `menu_name` IN (
  'vn_momo_pay_status',
  'vn_momo_partner_code',
  'vn_momo_access_key',
  'vn_momo_secret_key',
  'vn_momo_sandbox'
);

-- ZaloPay
UPDATE `eb_system_config`
SET `config_tab_id` = 142
WHERE `menu_name` IN (
  'vn_zalopay_pay_status',
  'vn_zalopay_app_id',
  'vn_zalopay_key1',
  'vn_zalopay_key2',
  'vn_zalopay_sandbox'
);
