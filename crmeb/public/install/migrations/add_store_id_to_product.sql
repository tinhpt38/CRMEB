-- =====================================================
-- Migration: Thêm cột store_id vào bảng eb_store_product
-- Mục đích: Liên kết sản phẩm với cửa hàng cụ thể
-- Ngày: 2026-05-13
-- =====================================================

-- Thêm cột store_id
ALTER TABLE `eb_store_product` 
ADD COLUMN `store_id` int(11) NOT NULL DEFAULT '0' 
COMMENT 'ID cửa hàng (0 = tất cả cửa hàng)' 
AFTER `mer_id`;

-- Thêm index để tối ưu truy vấn
ALTER TABLE `eb_store_product` 
ADD INDEX `idx_store_id` (`store_id`);
