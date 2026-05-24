# CRMEB Rollout Checklist (f-chan) — Phase 5 Production

## Preconditions

1. Copy `app-config.production.example.json` → `app-config.json` và điền giá trị thật.
2. Set `template.apiUrl` tới CRMEB API (ví dụ `https://shop.example.com/api`).
3. Bật feature flags theo giai đoạn rollout:
   - `catalog`: banner / danh mục / sản phẩm
   - `orders`: danh sách & chi tiết đơn
   - `checkout`: giỏ hàng → đặt hàng → thanh toán VN

Khi một flag **tắt** (hoặc thiếu `apiUrl`), app dùng dữ liệu mẫu trong `src/mock/*.json` thay vì gọi CRMEB.

## Feature Flags & Expected Behavior

| Flag | CRMEB bật | Flag tắt / thiếu apiUrl |
|------|-----------|-------------------------|
| `catalog` | `/home/banner`, `/category`, `/products`, `/product/detail` | Mock JSON catalog |
| `orders` | `/order/list`, `/order/detail/:uni` | Mock JSON orders |
| `checkout` | Cart sync + `/order/*` + `/pay/config` + cửa hàng `/store_list` | Mock stations; nút Thanh toán báo lỗi cấu hình |

## Admin CRMEB (trước go-live)

- [ ] **Zalo Mini App**: App ID, App Secret, OA ID (`template.admin` → Ứng dụng → Zalo)
- [ ] **Thanh toán VN**: COD, VietQR, VNPay/MoMo/ZaloPay (patch SQL Phase 4)
- [ ] **Banner trang chủ**: Cài đặt → Cấu hình dữ liệu → `routine_home_banner`
- [ ] **Sản phẩm**: ít nhất 1 SP vật lý, có ảnh, tồn kho, vận chuyển
- [ ] **Địa chỉ VN**: patch `vn_city_migration.sql` đã import

## Auth E2E

Theo [`fchan-crmeb-auth-e2e-checklist.md`](./fchan-crmeb-auth-e2e-checklist.md):

- [ ] Login Zalo → `POST /api/zalo/auth` trả JWT
- [ ] Token gọi được `/userinfo`, `/address/list`, `/order/list`
- [ ] Bind SĐT (nếu bật) qua `/api/zalo/bind_phone`

## Manual E2E Flow

### 1. Home / Catalog

- Mở `/` — carousel banner, danh mục, sản phẩm không lỗi console
- Ảnh hiển thị đúng (CRMEB trả URL tương đối → app ghép `apiUrl` origin)
- Vào `/product/:id` — mô tả + gallery tải từ `/product/detail/:id/0`

### 2. Địa chỉ & SĐT

- `/shipping-address` — chọn tỉnh/phường từ `/city_list`
- SĐT không hợp lệ → toast tiếng Việt (regex khớp backend VN)
- Lưu → `POST /address/edit` thành công

### 3. Checkout (giao hàng)

- Thêm SP vào giỏ → Cart → chọn địa chỉ
- Chọn PTTT (COD / VietQR / VNPay / MoMo / ZaloPay)
- Thanh toán — thứ tự API:
  1. `POST /cart/add`
  2. `POST /order/confirm`
  3. `POST /order/computed/:key`
  4. `POST /order/create/:key`
  5. `POST /order/pay`
- **COD/VietQR**: toast hướng dẫn, chuyển `/orders`
- **Cổng online**: redirect `pay_url`, không nhảy `/orders` trước khi rời trang
- **VietQR detail**: nội dung CK, QR, countdown `stop_time`, hướng dẫn trên `/orders/:id`

### 4. Orders

- Tab pending / shipping / completed khớp `_status` CRMEB
- Chi tiết đơn: trạng thái VN, hoàn tiền, đánh giá (nếu `_type = 3`)

## Deploy Mini App

```bash
cd f-chan
npm install
# Sửa app-config.json (apiUrl production, features = true)
zmp login
zmp deploy
```

## Troubleshooting

| Triệu chứng | Kiểm tra |
|-------------|----------|
| `Missing apiUrl` | `app-config.json → template.apiUrl` |
| Catalog trống | Flag `catalog`, banner/SP trên admin, JWT hợp lệ |
| Ảnh vỡ | CRMEB `pic`/`image` — app dùng `resolveImageUrl()` |
| Thanh toán không có PTTT | Admin → thanh toán VN; `GET /pay/config` |
| Cổng online không redirect | Cấu hình TMN/secret/sandbox; log IPN |
| SĐT lỗi khi lưu địa chỉ | Format `0xxxxxxxxx` hoặc `+84…` |
| ngrok interstitial | Client tự gửi header `ngrok-skip-browser-warning` |

## Monitor sau go-live

- Log callback: `/api/pay/notify/{vnpay|momo|zalopay}`
- Đơn VietQR chờ xác nhận: admin duyệt `paid` thủ công hoặc auto (nếu cấu hình)
- Theo dõi tỷ lệ lỗi `POST /zalo/auth` và timeout checkout
