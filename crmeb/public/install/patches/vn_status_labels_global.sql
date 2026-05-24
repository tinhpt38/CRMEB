-- -----------------------------------------------------------------------------
-- Patch: Chuẩn hóa nhãn trạng thái bật/tắt toàn hệ thống (eb_system_config)
-- Thay «bật lên» / «đóng cửa» (dịch máy từ 开启/关闭) → «Hoạt động» / «Ngưng hoạt động»
-- Idempotent. Sau khi chạy: php think clear
-- -----------------------------------------------------------------------------

UPDATE `eb_system_config`
SET `parameter` = REPLACE(REPLACE(`parameter`, 'bật lên', 'Hoạt động'), 'đóng cửa', 'Ngưng hoạt động')
WHERE `type` = 'radio'
  AND (`parameter` LIKE '%bật lên%' OR `parameter` LIKE '%đóng cửa%');

-- WAF: «đóng cửa» = tắt chế độ, không phải ngưng hoạt động
UPDATE `eb_system_config`
SET `parameter` = '0=>Tắt\n1=>Chặn\n2=>Lọc'
WHERE `menu_name` = 'param_filter_type';

-- Mô tả còn sót «|đóng cửa» trong desc (phân phối, hóa đơn…)
UPDATE `eb_system_config`
SET `desc` = REPLACE(`desc`, '|đóng cửa', '|Ngưng hoạt động')
WHERE `desc` LIKE '%|đóng cửa%';

UPDATE `eb_system_config`
SET `desc` = REPLACE(`desc`, 'được kích hoạt|đóng cửa', 'được kích hoạt|Ngưng hoạt động')
WHERE `desc` LIKE '%kích hoạt|đóng cửa%';

UPDATE `eb_lang_code`
SET `remarks` = 'Đóng', `lang_explain` = 'Đóng'
WHERE `code` = 'đóng cửa' AND `type_id` = 1;
