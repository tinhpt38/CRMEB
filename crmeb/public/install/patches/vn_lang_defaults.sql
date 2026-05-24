-- -----------------------------------------------------------------------------
-- Patch: đặt tiếng Việt làm ngôn ngữ mặc định (API mobile lang / getLang)
-- Chạy một lần sau backup. Sau đó xóa cache CRMEB (runtime/cache hoặc php think clear)
-- -----------------------------------------------------------------------------

-- Loại ngôn ngữ nguồn (id=1 trong bản CRMEB gốc)
UPDATE `eb_lang_type`
SET `language_name` = 'Tiếng Việt',
    `file_name` = 'vi-vn',
    `is_default` = 1,
    `status` = 1
WHERE `id` = 1 AND `is_del` = 0;

UPDATE `eb_lang_type` SET `is_default` = 0 WHERE `id` <> 1 AND `is_del` = 0;

-- English giữ bật, không mặc định
UPDATE `eb_lang_type`
SET `language_name` = 'English',
    `file_name` = 'en-us',
    `is_default` = 0,
    `status` = 1
WHERE LOWER(`file_name`) IN ('en-us', 'en_us') AND `is_del` = 0;

-- Ánh xạ quốc gia → type_id (ưu tiên vi-VN)
UPDATE `eb_lang_country`
SET `code` = 'vi-VN',
    `name` = 'Việt Nam',
    `type_id` = 1
WHERE `type_id` = 1
  AND LOWER(`code`) IN ('zh-cn', 'zh_cn', 'vi-vn', 'vi_vn');

INSERT INTO `eb_lang_country` (`code`, `name`, `type_id`)
SELECT 'vi-VN', 'Việt Nam', 1
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM `eb_lang_country` WHERE LOWER(`code`) = 'vi-vn'
);

-- English country row (nếu chưa có)
INSERT INTO `eb_lang_country` (`code`, `name`, `type_id`)
SELECT 'en-US', 'English', lt.`id`
FROM `eb_lang_type` lt
WHERE LOWER(lt.`file_name`) IN ('en-us', 'en_us')
  AND lt.`is_del` = 0
  AND NOT EXISTS (
    SELECT 1 FROM `eb_lang_country` WHERE LOWER(`code`) = 'en-us'
  )
LIMIT 1;
