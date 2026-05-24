-- -----------------------------------------------------------------------------
-- Patch: Chuẩn hóa nhãn trạng thái bật/tắt trong tab cấu hình thanh toán
-- Thay «bật lên» / «đóng cửa» (dịch máy) → «Hoạt động» / «Ngưng hoạt động»
-- Tab: 109 (cơ bản), 139 (VietQR), 140–142 (VNPay, MoMo, ZaloPay)
-- Idempotent. Sau khi chạy: php think clear
-- -----------------------------------------------------------------------------

UPDATE `eb_system_config`
SET `parameter` = '1=>Hoạt động\n2=>Ngưng hoạt động'
WHERE `menu_name` IN (
  'yue_pay_status',
  'vn_cod_pay_status',
  'vn_bank_pay_status',
  'vn_vnpay_pay_status',
  'vn_momo_pay_status',
  'vn_zalopay_pay_status',
  'offline_pay_status'
);

UPDATE `eb_system_config`
SET `parameter` = '1=>Hoạt động\n0=>Ngưng hoạt động'
WHERE `menu_name` = 'friend_pay_status';

UPDATE `eb_system_config`
SET `parameter` = '1=>Môi trường thử\n0=>Môi trường thật'
WHERE `menu_name` IN (
  'vn_vnpay_sandbox',
  'vn_momo_sandbox',
  'vn_zalopay_sandbox'
);

-- Gỡ nhãn dịch máy còn sót trong các tab thanh toán VN
UPDATE `eb_system_config`
SET `parameter` = REPLACE(
  REPLACE(`parameter`, 'bật lên', 'Hoạt động'),
  'đóng cửa', 'Ngưng hoạt động'
)
WHERE `config_tab_id` IN (109, 139, 140, 141, 142)
  AND `type` = 'radio'
  AND (`parameter` LIKE '%bật lên%' OR `parameter` LIKE '%đóng cửa%');

UPDATE `eb_system_config`
SET `parameter` = '1=>Hoạt động\n2=>Ngưng hoạt động'
WHERE `config_tab_id` IN (109, 139, 140, 141, 142)
  AND `type` = 'radio'
  AND `parameter` IN ('1=>Bật\n2=>Tắt', '1=>Bật\n0=>Tắt', '0=>Tắt\n1=>Bật');
