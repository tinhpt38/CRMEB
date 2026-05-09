# Mapping trạng thái đơn hàng VN ↔ CRMEB (`tidyOrder`)

**Phiên bản mapping:** 1.0 (2026-05-09)  
**Nguồn:** [order-flow-vietnam.md](./order-flow-vietnam.md) §2.2  
**Client chính:** Mini App [f-chan](../f-chan/) (`template/uni-app` không thuộc rollout này).

## Giữ nguyên `_type` (hợp đồng UI)

`_status._type` dùng cho ảnh `order_details_images` và logic tab đơn trên f-chan — **không đổi ý nghĩa số**:

| `_type` | Ý nghĩa kỹ thuật |
|---------|------------------|
| `-2` | Đã hoàn tiền |
| `-1` | Đang xử lý hoàn tiền / đổi trả |
| `0` | Chờ thanh toán (online / chưa hoàn tất thanh toán tức thì) |
| `1` | Đang xử lý / chờ giao (đã thanh toán hoặc COD đã chốt luồng `paid`, chưa nhận hàng — gồm chờ shop gửi, giao một phần split) |
| `2` | Đang giao — chờ khách nhận xác nhận |
| `3` | Đã nhận — chờ đánh giá |
| `4` | Hoàn tất **hoặc** đã hủy (đơn hủy vẫn dùng `4` như CRMEB gốc) |
| `9` | Thanh toán hoãn / chờ đối soát: `vn_bank`, `vn_cod`, hoặc `offline` (chưa `paid`) |

## Truth-table → nhãn hiển thị (`_title` / §2.2)

Điều kiện đọc theo thứ tự ưu tiên trong code: `is_cancel` → `!paid` (theo `pay_type`) → hoàn tiền → fulfillment.

| Điều kiện | `_type` | Nhãn §2.2 (`_title`) |
|-----------|---------|------------------------|
| `is_cancel = 1` | `4` | Đã hủy |
| `paid = 0`, `pay_type = vn_bank` | `9` | Chờ xác nhận chuyển khoản |
| `paid = 0`, `pay_type = vn_cod` | `9` | Đặt hàng thành công (COD) |
| `paid = 0`, `pay_type = offline`, `status < 2` | `9` | Chờ xác nhận thanh toán (ngoại tuyến) |
| `paid = 0`, còn lại | `0` | Chờ thanh toán |
| `paid = 1`, hoàn tiền đang xử lý (`refund_status` / `refund_type` theo CRMEB) | `-1` | Đang hoàn tiền |
| `paid = 1`, đã hoàn xong | `-2` | Đã hoàn tiền |
| `paid = 1`, `status = 0`, đơn hồng / Flash Sale / v.v. | `1` | Đang xử lý (nhánh messaging chi tiết giữ theo loại đơn) |
| `paid = 1`, `status = 4` (split một phần) | `1` | Đang giao (đơn chia lô) |
| `paid = 1`, `status = 1` | `2` | Chờ nhận hàng |
| `paid = 1`, `status = 2` | `3` | Chờ đánh giá |
| `paid = 1`, `status = 3` | `4` | Hoàn tất |

## File code liên quan

| Layer | File |
|-------|------|
| API `_status` | [crmeb/app/services/order/StoreOrderServices.php](../crmeb/app/services/order/StoreOrderServices.php) — `tidyOrder` |
| Out API | [crmeb/app/services/order/OutStoreOrderServices.php](../crmeb/app/services/order/OutStoreOrderServices.php) — `status_name` |
| Admin filter CK | [crmeb/app/dao/order/StoreOrderDao.php](../crmeb/app/dao/order/StoreOrderDao.php) — `pay_type = 7`; [template/admin/.../tableFrom.vue](../template/admin/src/pages/order/orderList/components/tableFrom.vue) |
| Mini App | [f-chan/src/state.ts](../f-chan/src/state.ts), [f-chan/src/pages/cart/pay.tsx](../f-chan/src/pages/cart/pay.tsx), [f-chan/src/pages/orders/order-summary.tsx](../f-chan/src/pages/orders/order-summary.tsx), [order-info.tsx](../f-chan/src/pages/orders/order-info.tsx) |

---

## Checklist QA (f-chan + admin)

1. **Chờ thanh toán:** đơn WeChat/Alipay chưa trả — `_type = 0`, countdown `stop_time`.
2. **vn_bank chưa đối soát:** `_type = 9`, tiêu đề CK, QR/guide hiển thị chi tiết đơn.
3. **vn_cod:** `_type = 9`, copy COD đúng; sau khi shop xác nhận flow thanh toán → fulfillment.
4. **offline:** `_type = 9`, nhãn ngoại tuyến.
5. **Đang xử lý:** `paid = 1`, `status = 0` — tiêu đề Đang xử lý.
6. **Chờ nhận:** `status = 1` — tiêu đề Chờ nhận hàng + thời điểm giao từ log (định dạng ngày).
7. **Hoàn tất / hủy:** không làm lệch `_type` ảnh trạng thái.
8. **Hoàn tiền:** `-1` / `-2` hiển thị đúng trên list chip và chi tiết.
9. **Admin:** preset “CK/VietQR — chưa đối soát” chỉ ra đơn `vn_bank` + `paid = 0`.

---

## Giai đoạn 4 (backlog — chưa code trong rollout)

- Push / SMS / Zalo theo mốc đơn.
- Timer nhắc CK / auto hủy theo SLA riêng `vn_bank`.
- Upload ảnh UNC khớp lệnh.
