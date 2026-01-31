-- Cập nhật tên hiển thị ngôn ngữ Tiếng Việt trong eb_lang_type
-- Chạy file này sau khi đã import crmeb.sql (ví dụ: mysql -u root -p ten_database < patch_lang_type_vi.sql)

UPDATE `eb_lang_type` SET `language_name` = 'Tiếng Việt' WHERE `id` = 10 AND `file_name` = 'vi-VN';
