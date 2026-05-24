# Cấu hình thanh toán Việt Nam (CRMEB + f-chan)

Đường dẫn Admin: **Cài đặt → Cấu hình thanh toán cửa hàng**

Sau Phase 4/6, tab được tổ chức:

| Tab | Nội dung |
|-----|----------|
| **109** Cấu hình cơ bản | COD, hạn mức COD, số dư ví |
| **139** VietQR / CK | Hướng dẫn CK, QR tĩnh, STK, BIN, tiền tố nội dung |
| **140** VNPay | TMN Code, Hash Secret, sandbox/production |
| **141** MoMo | Partner Code, Access/Secret Key |
| **142** ZaloPay | App ID, Key1, Key2 |

Tab WeChat / Alipay / Tonglian **ẩn** (`vn_pay_tabs_reorganize.sql`).

## Patch SQL (chạy một lần)

```bash
mysql ... < crmeb/public/install/patches/vn_pay_basic_cleanup.sql
mysql ... < crmeb/public/install/patches/vn_pay_tabs_reorganize.sql
mysql ... < crmeb/public/install/patches/vn_pay_admin_config.sql
mysql ... < crmeb/public/install/patches/vn_pay_gateways.sql
mysql ... < crmeb/public/install/patches/vn_status_labels_global.sql
cd crmeb && php think clear
```

## COD (`vn_cod`)

1. Tab **109** → **COD (thanh toán khi nhận)** = Hoạt động
2. **Hạn mức COD**: `0` = không giới hạn; hoặc số VND tối đa
3. f-chan: chọn COD tại checkout → đơn `paid=0`, giao hàng thu tiền

## VietQR / Chuyển khoản (`vn_bank`)

1. Tab **139** → bật **Chuyển khoản / VietQR**
2. Điền **Hướng dẫn CK** (STK, chủ TK, ngân hàng). Placeholder:
   - `{order_id}`, `{pay_price}`, `{transfer_content}`
3. **QR tĩnh**: upload ảnh QR, hoặc để trống và điền:
   - Số TK, BIN, Chủ TK → backend tạo VietQR động (`img.vietqr.io`)
4. **Tiền tố nội dung CK**: mặc định `DH` → `DH cp1234567890`
5. Admin xác nhận CK thủ công (hoặc auto nếu cấu hình sau này)
6. f-chan: sau đặt hàng hiển thị QR, nội dung CK, countdown `stop_time` (mặc định 24h qua `vn_sys_config_defaults.sql`)

## VNPay

1. Tab **140** → Hoạt động
2. Điền TMN Code, Hash Secret từ merchant VNPay
3. **Sandbox** = 1 khi test; **0** khi production
4. URL IPN (Notify): `https://your-domain.com/api/pay/notify/vnpay`
5. f-chan: `POST /order/pay` trả `pay_url` → redirect Mini App

## MoMo

1. Tab **141** → Hoạt động + Partner Code, Access Key, Secret Key
2. IPN: `https://your-domain.com/api/pay/notify/momo`

## ZaloPay

1. Tab **142** → Hoạt động + App ID, Key1, Key2
2. IPN: `https://your-domain.com/api/pay/notify/zalopay`

## API f-chan

| Endpoint | Mục đích |
|----------|----------|
| `GET /api/pay/config` | Danh sách PTTT bật |
| `POST /api/order/pay` | Khởi tạo thanh toán |
| `POST /api/pay/notify/{gateway}` | Callback cổng online |

## Troubleshooting

| Vấn đề | Gợi ý |
|--------|--------|
| f-chan không thấy PTTT | Kiểm tra flag Hoạt động + `php think clear` |
| VietQR không hiện | STK + BIN hoặc upload QR tĩnh |
| VNPay redirect lỗi | Sandbox vs production; TMN/Secret; `site_url` đúng |
| IPN không cập nhật `paid` | Log `crmeb/runtime/log`; firewall cho IP cổng |
| Nhãn «bật lên»/«đóng cửa» | Chạy `vn_status_labels_global.sql` |

## Luồng đơn (tóm tắt)

Xem chi tiết: [`order-flow-vietnam.md`](./order-flow-vietnam.md)

- **COD**: Đặt hàng → chờ giao → thu tiền → hoàn tất
- **VietQR**: Đặt hàng → CK trong 24h → admin xác nhận → giao hàng
- **Online**: Redirect cổng → IPN `paid=1` → giao hàng
