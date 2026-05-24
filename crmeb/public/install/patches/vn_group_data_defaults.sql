-- -----------------------------------------------------------------------------
-- Patch Phase 6: Việt hóa dữ liệu nhóm hệ thống (eb_system_group_data)
-- - Lịch điểm danh (gid 55): Ngày 2, Ngày 3…
-- - Liên kết DIY uni-app (gid 66): tên menu tiếng Việt (admin preview / legacy H5)
-- Idempotent. Sau khi chạy: php think clear
-- -----------------------------------------------------------------------------

UPDATE `eb_system_group_data`
SET `value` = REPLACE(`value`, '\u7b2c\u4e8c\u5929', 'Ngày 2')
WHERE `gid` = 55 AND `value` LIKE '%\u7b2c\u4e8c\u5929%';

UPDATE `eb_system_group_data`
SET `value` = REPLACE(`value`, '\u7b2c\u4e09\u5929', 'Ngày 3')
WHERE `gid` = 55 AND `value` LIKE '%\u7b2c\u4e09\u5929%';

UPDATE `eb_system_group_data`
SET `value` = REPLACE(`value`, '\u7b2c\u56db\u5929', 'Ngày 4')
WHERE `gid` = 55 AND `value` LIKE '%\u7b2c\u56db\u5929%';

UPDATE `eb_system_group_data`
SET `value` = REPLACE(`value`, '\u7b2c\u4e94\u5929', 'Ngày 5')
WHERE `gid` = 55 AND `value` LIKE '%\u7b2c\u4e94\u5929%';

UPDATE `eb_system_group_data`
SET `value` = REPLACE(`value`, '\u7b2c\u516d\u5929', 'Ngày 6')
WHERE `gid` = 55 AND `value` LIKE '%\u7b2c\u516d\u5929%';

UPDATE `eb_system_group_data`
SET `value` = REPLACE(`value`, '\u7b2c\u4e00\u5929', 'Ngày 1')
WHERE `gid` = 55 AND `value` LIKE '%\u7b2c\u4e00\u5929%';

UPDATE `eb_system_group_data`
SET `value` = REPLACE(`value`, '\u7b2c\u4e03\u5929', 'Ngày 7')
WHERE `gid` = 55 AND `value` LIKE '%\u7b2c\u4e03\u5929%';

-- Liên kết trang (gid 66)
UPDATE `eb_system_group_data`
SET `value` = REPLACE(`value`, '\u5546\u57ce\u9996\u9875', 'Trang chủ')
WHERE `gid` = 66 AND `value` LIKE '%\u5546\u57ce\u9996\u9875%';

UPDATE `eb_system_group_data`
SET `value` = REPLACE(`value`, '\u5546\u57ce\u5206\u7c7b', 'Danh mục')
WHERE `gid` = 66 AND `value` LIKE '%\u5546\u57ce\u5206\u7c7b%';

UPDATE `eb_system_group_data`
SET `value` = REPLACE(`value`, '\u8d2d\u7269\u8f66', 'Giỏ hàng')
WHERE `gid` = 66 AND `value` LIKE '%\u8d2d\u7269\u8f66%';

UPDATE `eb_system_group_data`
SET `value` = REPLACE(`value`, '\u4e2a\u4eba\u4e2d\u5fc3', 'Tài khoản')
WHERE `gid` = 66 AND `value` LIKE '%\u4e2a\u4eba\u4e2d\u5fc3%';

UPDATE `eb_system_group_data`
SET `value` = REPLACE(`value`, '\u5206\u7c7b\u5546\u54c1\u5217\u8868', 'Danh sách sản phẩm')
WHERE `gid` = 66 AND `value` LIKE '%\u5206\u7c7b\u5546\u54c1\u5217\u8868%';

UPDATE `eb_system_group_data`
SET `value` = REPLACE(`value`, '\u5546\u54c1\u8be6\u60c5', 'Chi tiết sản phẩm')
WHERE `gid` = 66 AND `value` LIKE '%\u5546\u54c1\u8be6\u60c5%';

UPDATE `eb_system_group_data`
SET `value` = REPLACE(`value`, '\u6587\u7ae0\u5217\u8868', 'Danh sách bài viết')
WHERE `gid` = 66 AND `value` LIKE '%\u6587\u7ae0\u5217\u8868%';

UPDATE `eb_system_group_data`
SET `value` = REPLACE(`value`, '\u4f18\u60e0\u5238\u5217\u8868', 'Danh sách mã giảm giá')
WHERE `gid` = 66 AND `value` LIKE '%\u4f18\u60e0\u5238\u5217\u8868%';

UPDATE `eb_system_group_data`
SET `value` = REPLACE(`value`, '\u6d4b\u8bd5\u5206\u7c7b\u540d\u79f0', 'Danh mục mẫu')
WHERE `gid` = 66 AND `value` LIKE '%\u6d4b\u8bd5\u5206\u7c7b\u540d\u79f0%';

UPDATE `eb_system_group_data`
SET `value` = REPLACE(`value`, '\u5546\u54c1ID', 'ID sản phẩm')
WHERE `gid` = 66 AND `value` LIKE '%\u5546\u54c1ID%';

UPDATE `eb_system_group_data`
SET `value` = REPLACE(`value`, '\u6587\u7ae0ID', 'ID bài viết')
WHERE `gid` = 66 AND `value` LIKE '%\u6587\u7ae0ID%';
