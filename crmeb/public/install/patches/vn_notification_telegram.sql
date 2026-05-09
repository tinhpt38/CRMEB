-- -----------------------------------------------------------------------------
-- Patch: Thêm kênh Telegram cho module Notification
-- Mục tiêu: hỗ trợ cảnh báo nội bộ (đặc biệt đơn hàng mới) qua Telegram Bot
-- Chạy một lần trên DB đang vận hành, sau đó clear cache CRMEB
-- -----------------------------------------------------------------------------

-- Thêm cột theo kiểu idempotent: nếu đã có thì bỏ qua (tránh lỗi #1060)
SET @sql := IF(
    EXISTS(
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'eb_system_notification'
          AND COLUMN_NAME = 'is_telegram'
    ),
    'SELECT 1',
    'ALTER TABLE `eb_system_notification` ADD COLUMN `is_telegram` tinyint(1) NOT NULL DEFAULT ''0'' COMMENT ''Telegram (0: không tồn tại, 1: bật, 2: tắt)'' AFTER `is_ent_wechat`'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    EXISTS(
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'eb_system_notification'
          AND COLUMN_NAME = 'telegram_bot_token'
    ),
    'SELECT 1',
    'ALTER TABLE `eb_system_notification` ADD COLUMN `telegram_bot_token` varchar(255) NOT NULL DEFAULT '''' COMMENT ''Telegram bot token'' AFTER `is_telegram`'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    EXISTS(
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'eb_system_notification'
          AND COLUMN_NAME = 'telegram_chat_id'
    ),
    'SELECT 1',
    'ALTER TABLE `eb_system_notification` ADD COLUMN `telegram_chat_id` varchar(64) NOT NULL DEFAULT '''' COMMENT ''Telegram chat/group id'' AFTER `telegram_bot_token`'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    EXISTS(
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'eb_system_notification'
          AND COLUMN_NAME = 'telegram_text'
    ),
    'SELECT 1',
    'ALTER TABLE `eb_system_notification` ADD COLUMN `telegram_text` varchar(1024) NOT NULL DEFAULT '''' COMMENT ''Nội dung mẫu Telegram'' AFTER `telegram_chat_id`'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Thiết lập sẵn mẫu Telegram cho 3 sự kiện nội bộ
UPDATE `eb_system_notification`
SET `is_telegram` = 2,
    `telegram_text` = 'Don hang moi #{order_id}\nKhach: {real_name}\nSDT: {user_phone}\nThanh toan: {pay_price}'
WHERE `mark` = 'admin_pay_success_code';

UPDATE `eb_system_notification`
SET `is_telegram` = 2,
    `telegram_text` = 'Yeu cau hoan tien moi\nDon: {order_id}\nMa hoan tien: {refund_no}\nSo tien: {refund_price}\nKhach: {real_name} - {user_phone}'
WHERE `mark` = 'send_order_apply_refund';

UPDATE `eb_system_notification`
SET `is_telegram` = 2,
    `telegram_text` = 'Nguoi dung da xac nhan nhan hang\nDon: {order_id}\nSan pham: {storeTitle}\nKhach: {real_name} - {user_phone}'
WHERE `mark` = 'send_admin_confirm_take_over';
