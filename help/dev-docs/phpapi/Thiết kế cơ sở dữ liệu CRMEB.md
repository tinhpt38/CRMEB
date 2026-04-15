# Thiết kế cơ sở dữ liệu CRMEB (bản phân tích tiếng Việt)

## 1) Phạm vi phân tích

Tài liệu này được đối chiếu từ schema cài đặt thực tế tại:

- `crmeb/public/install/crmeb.sql`

Lưu ý: đa số bảng dùng tiền tố `eb_`. Trong code nghiệp vụ thường gọi theo tên rút gọn (ví dụ `user`, `store_order`), nhưng tên vật lý trong DB là `eb_user`, `eb_store_order`.

---

## 2) Phân nhóm bảng theo domain nghiệp vụ

## 2.1 Domain người dùng

- `eb_user`: bảng người dùng trung tâm.
- `eb_user_address`: địa chỉ nhận hàng.
- `eb_user_bill`: nhật ký biến động số dư/điểm.
- `eb_user_brokerage`: lịch sử hoa hồng.
- `eb_user_level`, `eb_user_label`: phân tầng và gắn nhãn người dùng.

**Trường quan trọng trong `eb_user`**:

- Định danh/tài khoản: `uid`, `account`, `pwd`, `phone`, `uniqid`.
- Hồ sơ: `real_name`, `nickname`, `avatar`, `birthday`.
- Trạng thái: `status`, `is_del`, `level`, `user_type`, `login_type`.
- Tài sản: `now_money`, `integral`, `exp`, `brokerage_price`.
- Phân phối: `is_promoter`, `spread_uid`, `spread_time`, `agent_level`.

## 2.2 Domain sản phẩm

- `eb_store_product`: bảng sản phẩm chính.
- `eb_store_product_attr`: định nghĩa thuộc tính (màu, dung lượng...).
- `eb_store_product_attr_value`: giá trị SKU/biến thể.
- `eb_store_product_attr_result`: cache cấu hình thuộc tính.
- `eb_store_category`, `eb_store_product_cate`: danh mục và liên kết danh mục.
- `eb_store_product_reply`: đánh giá sản phẩm.

**Trường quan trọng trong `eb_store_product`**:

- Thông tin bán hàng: `store_name`, `price`, `vip_price`, `ot_price`, `stock`, `sales`.
- Trạng thái hiển thị: `is_show`, `is_del`, `is_hot`, `is_new`, `is_best`, `is_benefit`.
- Loại hình: `is_virtual`, `virtual_type`, `presale`, `is_limit`.
- Logistics: `postage`, `temp_id`, `freight`, `logistics`.
- Nghiệp vụ mở rộng: `is_seckill`, `is_bargain`, `activity`, `vip_product`.

## 2.3 Domain đơn hàng và thanh toán

- `eb_store_order`: bảng đơn hàng chính.
- `eb_store_order_cart_info`: snapshot sản phẩm tại thời điểm đặt hàng.
- `eb_store_order_status`: lịch sử chuyển trạng thái.
- `eb_store_order_refund`: thông tin hoàn tiền/hoàn hàng.
- `eb_store_order_invoice`: thông tin hóa đơn.
- `eb_store_pay`, `eb_other_order`: thanh toán và các đơn loại khác.

**Trường quan trọng trong `eb_store_order`**:

- Định danh: `id`, `order_id`, `trade_no`, `unique`.
- Người mua: `uid`, `real_name`, `user_phone`, `user_address`.
- Tiền tệ: `total_price`, `pay_price`, `coupon_price`, `deduction_price`, `refund_price`.
- Trạng thái: `paid`, `status`, `refund_status`, `is_cancel`, `is_del`.
- Vận chuyển: `delivery_type`, `delivery_name`, `delivery_code`, `delivery_id`, `shipping_type`.
- Nguồn đơn: `seckill_id`, `bargain_id`, `combination_id`, `pink_id`, `advance_id`.
- Phân phối hoa hồng: `spread_uid`, `spread_two_uid`, `one_brokerage`, `two_brokerage`.

## 2.4 Domain marketing

- `eb_store_coupon_issue`, `eb_store_coupon_issue_user`, `eb_store_coupon_user`.
- `eb_store_seckill`, `eb_store_bargain`, `eb_store_combination`, `eb_store_pink`.
- `eb_store_integral`, `eb_store_integral_order`.
- `eb_luck_lottery`, `eb_luck_prize`.

## 2.5 Domain hệ thống quản trị

- `eb_system_admin`: tài khoản quản trị.
- `eb_system_role`, `eb_system_menus`: RBAC.
- `eb_system_config`: cấu hình động toàn hệ thống.
- `eb_system_log`: log thao tác.
- `eb_system_attachment`: tệp đính kèm/media.

**`eb_system_config` là bảng trọng yếu**: lưu cấu hình vận hành theo key (`menu_name`) như cổng thanh toán, ngưỡng miễn phí ship, timeout huỷ đơn, bật/tắt module...

---

## 3) Quan hệ dữ liệu cốt lõi (để code đúng nghiệp vụ)

## 3.1 Trục người dùng - đơn hàng

- `eb_user.uid` (1) -> (N) `eb_store_order.uid`
- `eb_user.uid` (1) -> (N) `eb_user_address.uid`
- `eb_user.uid` (1) -> (N) `eb_user_bill.uid`
- `eb_user.uid` (1) -> (N) `eb_store_coupon_user.uid`

## 3.2 Trục đơn hàng - chi tiết đơn

- `eb_store_order.id` (1) -> (N) `eb_store_order_cart_info.oid`
- `eb_store_order.id` (1) -> (N) `eb_store_order_status.oid` (hoặc theo `order_id` tùy ngữ cảnh service)
- `eb_store_order.order_id` thường liên kết với bảng thanh toán/hoàn tiền.

## 3.3 Trục sản phẩm - SKU

- `eb_store_product.id` (1) -> (N) `eb_store_product_attr.product_id`
- `eb_store_product.id` (1) -> (N) `eb_store_product_attr_value.product_id`
- `eb_store_product.id` (1) -> (N) `eb_store_product_reply.product_id`

---

## 4) Chỉ mục và hiệu năng truy vấn

## 4.1 Chỉ mục đã có và thường dùng

- `eb_user`: index trên `account`, `spread_uid`, `level`, `status`, `is_promoter`.
- `eb_store_order`: index trên `uid`, `add_time`, `pay_time`, `paid`, `status`, `coupon_id`; unique `order_id + uid`.
- `eb_store_product`: index trên `cate_id`, `is_hot`, `is_new`, `is_best`, `is_show`, `sort`, `sales`, `add_time`.
- `eb_system_admin`: index trên `account`, `status`.

## 4.2 Gợi ý truy vấn theo nghiệp vụ

- Danh sách đơn user:
  - lọc `uid`, `status`, `add_time`
- Dashboard đơn hàng:
  - lọc theo `paid`, `pay_time`, `is_del`
- Danh sách sản phẩm:
  - lọc `is_show`, `is_del`, `cate_id`, `is_hot/is_new`

---

## 5) Các điểm cần chú ý khi phát triển

1. **Soft delete phổ biến**: nhiều bảng dùng `is_del`; truy vấn mặc định phải lọc `is_del = 0`.
2. **Trạng thái đơn hàng phức tạp**: `status`, `paid`, `refund_status`, `is_cancel` kết hợp với nhau; không dùng một cột đơn lẻ để suy luận toàn bộ trạng thái.
3. **Khóa ngoại logic, không ràng buộc cứng**: schema hầu như không khai báo FOREIGN KEY; cần đảm bảo toàn vẹn ở tầng service/dao.
4. **`eb_system_config` ảnh hưởng runtime**: đổi key cấu hình có thể tác động thanh toán, callback, upload, timeout đơn hàng.
5. **Bảng lớn theo thời gian**: `order`, `order_status`, `user_bill`, `system_log` cần chiến lược archive hoặc phân vùng khi scale.

---

## 6) Đề xuất chuẩn hóa tài liệu DB cho đội

- Lập data dictionary theo mẫu: `table`, `field`, `type`, `business meaning`, `required`, `default`, `index`, `related services`.
- Với mỗi domain (User/Product/Order/Marketing), bổ sung sequence nghiệp vụ + bảng ghi/đọc theo từng bước.
- Với bảng quan trọng (`eb_user`, `eb_store_order`, `eb_store_product`, `eb_system_config`), viết thêm “quy tắc bất biến” (invariants) để tránh bug logic.

---

## 7) Kết luận ngắn

Cơ sở dữ liệu CRMEB có thiết kế giàu nghiệp vụ thương mại điện tử xã hội, tập trung vào 4 trục chính: **người dùng - sản phẩm - đơn hàng - marketing**. Việc phát triển an toàn phụ thuộc nhiều vào quy tắc tầng service (do không dựa mạnh vào foreign key) và quản trị trạng thái đơn hàng/cấu hình hệ thống.
