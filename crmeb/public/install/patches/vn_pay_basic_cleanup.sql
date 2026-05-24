-- -----------------------------------------------------------------------------
-- Patch: Ẩn PTTT không liên quan trong tab «Cấu hình cơ bản» (pay_basic, tab 109)
-- Lưu ý: vn_pay_tabs_reorganize.sql đã gộp bước này; file giữ để tương thích cài cũ.
-- Sau khi chạy: php think clear
-- -----------------------------------------------------------------------------

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
