-- -----------------------------------------------------------------------------
-- Patch: Channel Registry cho Notification (Phase 1 + 2)
-- Mục tiêu:
--   1) Quản lý cấu hình kênh tập trung (notice_channel)
--   2) Mapping event -> channel (notice_event_channel)
--   3) Seed mặc định Telegram Ops cho 3 event nội bộ
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `eb_notice_channel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_key` varchar(64) NOT NULL DEFAULT '' COMMENT 'Khóa kênh duy nhất',
  `channel_type` varchar(32) NOT NULL DEFAULT '' COMMENT 'Loại kênh: telegram/zalo/sms/...',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT 'Tên hiển thị kênh',
  `config` text COMMENT 'JSON cấu hình kênh',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 bật, 0 tắt',
  `is_del` tinyint(1) NOT NULL DEFAULT '0',
  `add_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_channel_key` (`channel_key`),
  KEY `idx_channel_type_status` (`channel_type`, `status`, `is_del`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Danh mục kênh thông báo dùng chung';

CREATE TABLE IF NOT EXISTS `eb_notice_event_channel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_mark` varchar(64) NOT NULL DEFAULT '' COMMENT 'Mark sự kiện thông báo',
  `channel_id` int(11) NOT NULL DEFAULT '0' COMMENT 'FK -> notice_channel.id',
  `enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 bật, 0 tắt',
  `priority` int(11) NOT NULL DEFAULT '100' COMMENT 'Ưu tiên gửi, càng nhỏ càng trước',
  `template_text` varchar(2000) NOT NULL DEFAULT '' COMMENT 'Template theo kênh',
  `template_map` text COMMENT 'JSON map biến tùy chọn',
  `add_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_event_channel` (`event_mark`, `channel_id`),
  KEY `idx_event_enabled` (`event_mark`, `enabled`, `priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Mapping sự kiện với kênh thông báo';

-- Seed kênh Telegram Ops (token/chat_id để trống, admin điền sau)
INSERT INTO `eb_notice_channel` (`channel_key`, `channel_type`, `name`, `config`, `status`, `is_del`, `add_time`, `update_time`)
SELECT 'telegram_ops', 'telegram', 'Telegram Ops', '{"bot_token":"","chat_id":""}', 1, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()
WHERE NOT EXISTS (
  SELECT 1 FROM `eb_notice_channel` WHERE `channel_key` = 'telegram_ops' LIMIT 1
);

-- Gắn 3 event nội bộ vào telegram_ops
INSERT INTO `eb_notice_event_channel`
(`event_mark`, `channel_id`, `enabled`, `priority`, `template_text`, `template_map`, `add_time`, `update_time`)
SELECT 'admin_pay_success_code', c.id, 1, 10,
       'Don hang moi #{order_id}\nKhach: {real_name}\nSDT: {user_phone}\nThanh toan: {pay_price}',
       '{}', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()
FROM `eb_notice_channel` c
WHERE c.`channel_key` = 'telegram_ops'
  AND NOT EXISTS (
    SELECT 1 FROM `eb_notice_event_channel` ec
    WHERE ec.`event_mark` = 'admin_pay_success_code' AND ec.`channel_id` = c.id LIMIT 1
  );

INSERT INTO `eb_notice_event_channel`
(`event_mark`, `channel_id`, `enabled`, `priority`, `template_text`, `template_map`, `add_time`, `update_time`)
SELECT 'send_order_apply_refund', c.id, 1, 10,
       'Yeu cau hoan tien moi\nDon: {order_id}\nMa hoan tien: {refund_no}\nSo tien: {refund_price}\nKhach: {real_name} - {user_phone}',
       '{}', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()
FROM `eb_notice_channel` c
WHERE c.`channel_key` = 'telegram_ops'
  AND NOT EXISTS (
    SELECT 1 FROM `eb_notice_event_channel` ec
    WHERE ec.`event_mark` = 'send_order_apply_refund' AND ec.`channel_id` = c.id LIMIT 1
  );

INSERT INTO `eb_notice_event_channel`
(`event_mark`, `channel_id`, `enabled`, `priority`, `template_text`, `template_map`, `add_time`, `update_time`)
SELECT 'send_admin_confirm_take_over', c.id, 1, 10,
       'Nguoi dung da xac nhan nhan hang\nDon: {order_id}\nSan pham: {storeTitle}\nKhach: {real_name} - {user_phone}',
       '{}', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()
FROM `eb_notice_channel` c
WHERE c.`channel_key` = 'telegram_ops'
  AND NOT EXISTS (
    SELECT 1 FROM `eb_notice_event_channel` ec
    WHERE ec.`event_mark` = 'send_admin_confirm_take_over' AND ec.`channel_id` = c.id LIMIT 1
  );

