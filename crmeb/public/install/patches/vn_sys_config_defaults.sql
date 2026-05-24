-- -----------------------------------------------------------------------------
-- Patch Phase 6: Cấu hình hệ thống mặc định cho thị trường Việt Nam
-- Idempotent. Chạy sau backup. Sau khi chạy: php think clear
-- -----------------------------------------------------------------------------

-- Đơn vị điểm / mô tả admin
UPDATE `eb_system_config`
SET `desc` = 'Tỷ lệ quy đổi điểm (1 điểm tương đương bao nhiêu VND khi trừ vào đơn). Đơn vị: đ'
WHERE `menu_name` = 'integral_ratio';

-- Lý do trả hàng (thay chuỗi tiếng Trung mặc định)
UPDATE `eb_system_config`
SET `value` = '"Địa chỉ nhận hàng sai\nKhông khớp mô tả sản phẩm\nThông tin đặt hàng sai, đặt lại\nHàng hóa bị hư hỏng\nKhông giao đúng hạn\nLý do khác"'
WHERE `menu_name` = 'stor_reason';

-- Ngân hàng rút tiền (thay «中国银行»)
UPDATE `eb_system_config`
SET `value` = '"Vietcombank\nTechcombank\nMB Bank\nACB\nVPBank\nBIDV"'
WHERE `menu_name` = 'user_extract_bank';

-- Miễn phí ship ngưỡng VN (500.000đ) — chỉ khi vẫn là giá trị demo 1.000.000 cũ hoặc trống
UPDATE `eb_system_config`
SET `value` = '"500000"'
WHERE `menu_name` = 'store_free_postage'
  AND (`value` IN ('"1000000"', '""', '"0"') OR `value` IS NULL);

-- Bật nhận tại cửa hàng (f-chan pickup)
UPDATE `eb_system_config`
SET `value` = '"1"'
WHERE `menu_name` = 'store_self_mention';

-- Tắt WeChat Pay trên client (tab admin vẫn ẩn qua vn_pay_tabs_reorganize)
UPDATE `eb_system_config`
SET `value` = '"0"'
WHERE `menu_name` = 'pay_weixin_open';

-- Thời gian hủy đơn chưa thanh toán: 24h (phù hợp CK/VietQR)
UPDATE `eb_system_config`
SET `value` = '"24"'
WHERE `menu_name` = 'order_cancel_time';

UPDATE `eb_system_config`
SET `value` = '"24"'
WHERE `menu_name` = 'order_activity_time';

-- Thông báo nạp tiền / hoàn tiền: thay ￥ và «Nhân dân tệ»
UPDATE `eb_system_notification`
SET
  `system_text` = REPLACE(REPLACE(`system_text`, '￥', ''), 'Nhân dân tệ', 'đ'),
  `sms_text` = REPLACE(REPLACE(`sms_text`, '￥', ''), 'Nhân dân tệ', 'đ'),
  `ent_wechat_text` = REPLACE(REPLACE(`ent_wechat_text`, '￥', ''), 'Nhân dân tệ', 'đ')
WHERE `mark` IN ('recharge_success', 'recharge_order_refund_status')
  AND (
    `system_text` LIKE '%￥%'
    OR `system_text` LIKE '%Nhân dân tệ%'
    OR `sms_text` LIKE '%￥%'
    OR `sms_text` LIKE '%Nhân dân tệ%'
  );

UPDATE `eb_system_notification`
SET `system_text` = 'Bạn đã nạp tiền thành công {price}đ, số dư hiện tại còn lại {now_money}đ'
WHERE `mark` = 'recharge_success'
  AND (`system_text` LIKE '%￥%' OR `system_text` LIKE '%Nhân dân tệ%');

UPDATE `eb_system_notification`
SET `system_text` = 'Số tiền bạn nạp đã được hoàn trả. Khoản hoàn trả này {refund_price}đ'
WHERE `mark` = 'recharge_order_refund_status'
  AND (`system_text` LIKE '%￥%' OR `system_text` LIKE '%Nhân dân tệ%');
