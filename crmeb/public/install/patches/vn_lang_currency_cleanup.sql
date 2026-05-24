-- -----------------------------------------------------------------------------
-- Patch Phase 6: Chuẩn hóa đơn vị tiền tệ trong eb_lang_code (type_id = Tiếng Việt)
-- Thay ￥ / «Nhân dân tệ» → đ / VND trên bundle vi-vn (type_id = 1)
-- Idempotent. Sau khi chạy: php think clear
-- -----------------------------------------------------------------------------

UPDATE `eb_lang_code`
SET
  `remarks` = REPLACE(REPLACE(REPLACE(`remarks`, '￥', 'đ'), 'Nhân dân tệ', 'VND'), 'nhân dân tệ', 'VND'),
  `lang_explain` = REPLACE(REPLACE(REPLACE(`lang_explain`, '￥', 'đ'), 'Nhân dân tệ', 'VND'), 'nhân dân tệ', 'VND')
WHERE `type_id` = 1
  AND (
    `remarks` LIKE '%￥%'
    OR `remarks` LIKE '%Nhân dân tệ%'
    OR `remarks` LIKE '%nhân dân tệ%'
    OR `lang_explain` LIKE '%￥%'
    OR `lang_explain` LIKE '%Nhân dân tệ%'
    OR `lang_explain` LIKE '%nhân dân tệ%'
  );

-- Một số mã API rút tiền hay gặp
UPDATE `eb_lang_code`
SET
  `remarks` = 'Số tiền rút không thể ít hơn {:money}đ',
  `lang_explain` = 'Số tiền rút không thể ít hơn {:money}đ'
WHERE `type_id` = 1 AND `code` = '400662';

UPDATE `eb_lang_code`
SET
  `remarks` = 'Hoa hồng rút tiền không đủ {:money}đ',
  `lang_explain` = 'Hoa hồng rút tiền không đủ {:money}đ'
WHERE `type_id` = 1 AND `code` = '400663';

-- Chuỗi đơn lẻ trong bảng lang (symbol)
UPDATE `eb_lang_code`
SET `remarks` = 'đ', `lang_explain` = 'đ'
WHERE `type_id` = 1 AND (`remarks` = '￥' OR `lang_explain` = '￥');

UPDATE `eb_lang_code`
SET `remarks` = 'VND', `lang_explain` = 'VND'
WHERE `type_id` = 1
  AND (`remarks` = 'Nhân dân tệ' OR `lang_explain` = 'Nhân dân tệ');
