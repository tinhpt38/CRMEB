-- Patch: Đặt ngôn ngữ mặc định hệ thống = English (en-US)
-- Tránh trình duyệt tự động dịch trang qua Google khi mặc định là zh-CN/vi-VN.
-- eb_lang_type: id=1 中文(zh-CN), id=2 English(en-US). is_default=1 = ngôn ngữ mặc định.

UPDATE eb_lang_type SET is_default = 0 WHERE 1=1;
UPDATE eb_lang_type SET is_default = 1 WHERE id = 2;
