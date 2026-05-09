# Flow đơn hàng tối ưu cho người Việt (CRMEB)

Tài liệu định hướng nghiệp vụ và UX cho luồng đặt hàng – thanh toán – giao – hoàn tất / hoàn tiền, bám theo mô hình hiện có của CRMEB (ThinkPHP Services/DAO) và tham chiếu nguyên lý trạng thái đơn của WooCommerce.

**Mục tiêu:**

- Khách hàng hiểu đơn đang ở đâu, cần làm gì tiếp theo.
- Vận hành (admin/CS) ít nhầm lẫn giữa “chưa thanh toán”, “chờ đối soát chuyển khoản”, “COD”, “đã giao”.
- State nội bộ vẫn dùng các trường hiện có (`paid`, `status`, `refund_status`, `pay_type`, …); phía user dùng **trạng thái hiển thị** gọn, nhất quán tiếng Việt.

---

## 1. Nguyên lý tham chiếu (WooCommerce)

WooCommerce phân tầng trạng thái theo thanh toán và fulfillment, ví dụ:

| WooCommerce (ý nghĩa) | Ý nghĩa ngắn |
|----------------------|--------------|
| Pending payment | Đã tạo đơn, chưa nhận được thanh toán |
| On hold | Chờ xác minh (ck, COD, …); thường cần thao tác thủ công |
| Processing | Đã thanh toán / đã chốt nhánh thanh toán, đang xử lý giao |
| Completed | Đã hoàn tất giao dịch (đã fulfillment theo định nghĩa site) |
| Cancelled | Hủy |
| Refunded | Đã hoàn tiền |

**Chúng ta áp dụng tinh thần này** nhưng đặt tên và bước hiển thị phù hợp thói quen VN (Zalo/SMS/CK/COD/VietQR).

---

## 2. Hai lớp trạng thái (đề xuất)

### 2.1. Trạng thái nội bộ (CRMEB — giữ nguyên khái niệm)

- `paid`: 0 / 1
- `status`: lifecycle giao hàng / nhận hàng / hoàn tất đánh giá (theo nghiệp vụ CRMEB hiện tại)
- `refund_status` + `refund_type`: luồng hoàn tiền / đổi trả
- `pay_type` / các cờ cổng như `vn_cod`, `vn_bank`, ví dụ cổng realtime, số dư, …

Chi tiết mapping code nên cập nhật trong bảng riêng (mục 7) khi implement.

### 2.2. Trạng thái hiển thị cho khách (8–10 nhãn, tiếng Việt)

| # | Nhãn hiển thị (đề xuất) | Khi nào dùng |
|---|-------------------------|--------------|
| 1 | Chờ thanh toán | Đơn tạo xong, chưa thanh toán online / chưa hết hạn giữ đơn |
| 2 | Chờ xác nhận chuyển khoản | `vn_bank` — user đã chọn CK, shop chưa xác nhận tiền vào |
| 3 | Đặt hàng thành công (COD) | `vn_cod` — đơn hợp lệ, thu tiền khi nhận hàng |
| 4 | Đang xử lý | Đã thanh toán / đã chốt nhánh thanh toán, shop đang soạn |
| 5 | Đang giao | Đã bàn giao ĐVVC / có mã vận đơn |
| 6 | Đã giao | Giao đến tay, chờ khách xác nhận (hoặc auto xác nhận sau N ngày) |
| 7 | Hoàn tất | Kết thúc tích điểm/đánh giá theo rule hệ thống |
| 8 | Đã hủy | Hủy trong điều kiện cho phép |
| 9 | Đang hoàn tiền | Có yêu cầu hoàn / đổi trả đang xử lý |
| 10 | Đã hoàn tiền | Tiền đã hoàn (toàn phần / một phần — nên ghi rõ số tiền) |

Admin có thể thêm cột “trạng thái vận hành” chi tiết hơn (soạn hàng, chờ in tem, …) nếu cần.

---

## 3. Flow theo giai đoạn (best-fit VN)

### 3.1. Đặt hàng

1. Xác nhận giỏ: địa chỉ, phí ship, voucher, điểm, ghi chú.
2. Tạo mã đơn ngắn, dễ đọc khi gọi CS / chat Zalo.
3. **Giữ đơn tạm** (countdown 30 phút) nếu “chờ thanh toán online” — giảm đơn ảo.

### 3.2. Thanh toán (phân nhánh)

| Phương thức | Hành vi mong muốn | Trạng thái hiển thị gợi ý |
|-------------|-------------------|---------------------------|
| Online (VNPay / MoMo / ZaloPay / thẻ …) | Thanh toán thành công → vào xử lý | Chờ thanh toán → Đang xử lý |
| Chuyển khoản (`vn_bank`) | User chọn CK, hiện thông tin + hạn; shop đối soát | Chờ xác nhận chuyển khoản → Đang xử lý |
| COD (`vn_cod`) | Không thu tiền trước; rule hàng ảo / giới hạn giá trị nếu có | Đặt hàng thành công (COD) → … |
| Số dư / 0đ | Theo logic hiện có CRMEB | Map vào nhánh tương ứng sau khi `paid` chốt |

### 3.3. Xử lý & giao hàng

1. **Đang xử lý**: xác nhận tồn (cần gọi cho khách nữa), soạn, đóng gói,
2. **Đang giao**: mã vận đơn, link tra cứu, ước lượng ngày giao (nếu có).
3. **Đã giao**: khách xác nhận nhận hoặc auto sau N ngày.
4. **Hoàn tất**: sau đánh giá / rule “order over” của hệ thống.

### 3.4. Nhánh lỗi / ngoại lệ

- Giao thất bại / hoàn hàng từ ĐVVC → trạng thái vận hành riêng + thông báo khách.
- Khách từ chối nhận (COD) → quy trình CS + có thể hủy / hoàn phí ship tùy policy.
- Timeout thanh toán / CK không khớp → hủy hoặc nhắc (theo cấu hình).

### 3.5. Đổi / trả / hoàn tiền

1. Khách gửi yêu cầu (full / partial — nếu hỗ trợ).
2. Shop duyệt / từ chối / yêu cầu bổ sung bằng chứng.
3. Nếu cần trả hàng: khách điền đơn vị + mã vận đơn trả.
4. Shop nhận hàng trả → hoàn tiền (ghi rõ một phần / toàn phần).

---

## 4. Quy tắc chuyển trạng thái (tóm tắt QA)

Ánh xạ dưới đây là **logic hiển thị**, không thay thế trực tiếp các số trong DB:

- **Chờ thanh toán → Đã hủy**: hết hạn giữ đơn, user chủ động hủy, hoặc admin hủy (theo policy).
- **Chờ thanh toán → Đang xử lý**: thanh toán online thành công.
- **Chờ xác nhận chuyển khoản → Đang xử lý**: admin / auto đối soát xác nhận (set `paid` + xử lý tiếp).
- **Đặt hàng thành công (COD) → Đang xử lý**: shop xác nhận đơn (nếu có bước này) hoặc khi chuyển sang soạn/giao.
- **Đang xử lý → Đang giao**: đã có thông tin giao / đơn vị vận chuyển.
- **Đang giao → Đã giao**: webhook ĐVVC / cập nhật thủ công admin / POD.
- **Đã giao → Hoàn tất**: user xác nhận nhận + đánh giá (hoặc auto theo SLA).
- Bất kỳ giai đoạn fulfillment cho phép → **Đang hoàn tiền** khi có `refund` hợp lệ → **Đã hoàn tiền** hoặc quay lại nhánh giao hàng tùy kết quả.

---

## 5. Tính năng nên bổ sung / củng cố (ưu tiên VN)

| Ưu tiên | Tính năng | Mô tả |
|---------|-----------|-------|
| Cao | Thông báo đa điểm | Push app + SMS/Zalo OA tại: tạo đơn, thanh toán thành/bại, CK cần xác nhận, đã gửi hàng, sắp giao, hoàn tiền. |
| Cao | Countdown | Chờ thanh toán / chờ CK với một CTA duy nhất rõ (“Thanh toán ngay”, “Đã chuyển — chờ shop xác nhận”). |
| Cao | CK thân thiện | QR VietQR/bank info, copy nội dung CK theo **mã đơn**, gợi ý ghép khớp số tiền + ảnh UNC (nếu cần). |
| Trung | Admin xác nhận CK nhanh | Bộ lọc “chưa đối soát”, gợi ý đơn theo số tiền + giờ giao dịch. |
| Trung | Auto hoàn / nhắc | Quá X giờ không xác nhận CK → nhắc CS / auto hủy (config). |
| Trung | SLA hiển thị | “Shop cam kết xử lý trong 24h” trên chi tiết đơn khi chờ duyệt. |
| Thấp | Chống tranh chấp COD | Xác nhận SĐT, ghi chú shipper, POD khi có. |

---

## 6. KPI theo dõi

- Tỷ lệ thoát ở bước **Chờ thanh toán**.
- Thời gian trung bình **CK → xác nhận** và tỉ lệ hết hạn.
- Tỉ lệ **ghép sai CK** hoặc cần can thiệp CS.
- Tỉ lệ hoàn COD / đổi trả theo SKU / khu vực.
- Ti le giao lại / giao thất bại (nếu tích hợp ĐVVC).

---

## 7. Mapping nội bộ CRMEB

Bảng truth-table, checklist QA và backlog giai đoạn 4: **[order-flow-vietnam-mapping.md](./order-flow-vietnam-mapping.md)** (phiên bản mapping ghi trong file đó).

**Client triển khai UX:** Mini App **f-chan** (`f-chan/src/…`). Không dùng `template/uni-app` trong rollout flow VN này.

**Code chỉnh chính:** `StoreOrderServices::tidyOrder`, `OutStoreOrderServices` (`status_name`), filter admin đơn hàng, `orderDetails.vue`, và mapper đơn trong `f-chan/src/state.ts`.

---

## 8. Lịch sử chỉnh sửa tài liệu

| Ngày | Người | Ghi chú |
|------|-------|---------|
| 2026-05-09 | — | Khởi tạo bản nháp flow VN + tham chiếu WooCommerce |
| 2026-05-09 | — | §7 trỏ `order-flow-vietnam-mapping.md`; client f-chan |

---

*Nếu cần chia nhỏ: có thể tách riêng file `docs/order-flow-vietnam-mapping.md` chỉ chứa bảng mapping và checklist QA.*
