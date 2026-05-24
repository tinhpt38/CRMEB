# Triển khai CRMEB — Thị trường Việt Nam

Hướng dẫn deploy fork Việt hóa (backend ThinkPHP + Admin Vue + **f-chan** Zalo Mini App). **Không** dùng `template/uni-app` cho rollout chính.

## Yêu cầu hạ tầng

| Thành phần | Phiên bản gợi ý |
|------------|------------------|
| PHP | ≥ 7.4 (khuyến nghị 8.0+) |
| MySQL | ≥ 5.7 / MariaDB 10.3+ |
| Node | ≥ 16 (admin build, f-chan) |
| Redis | Tùy chọn (cache/queue) |

## Cài mới

1. Import `crmeb/public/install/crmeb.sql` (đã Việt hóa mặc định Phase 6).
2. Cấu hình `.env` trong `crmeb/`:
   - `APP_DEFAULT_TIMEZONE=Asia/Ho_Chi_Minh`
   - DB, Redis, `site_url`
3. Chạy patch SQL theo thứ tự trong [`patches/README.md`](../crmeb/public/install/patches/README.md).
4. `cd crmeb && php think clear`
5. Build admin: `cd template/admin && npm install && npm run build`
6. Trỏ web server document root → `crmeb/public`

## Nâng cấp DB đang vận hành

```bash
# Thay biến kết nối
DB="mysql -uUSER -pPASS DATABASE"

# Backup trước
mysqldump -uUSER -pPASS DATABASE > backup-$(date +%F).sql

# Chạy toàn bộ patch VN (xem README patches)
bash crmeb/public/install/patches/apply-vn-patches.sh "$DB"
cd crmeb && php think clear
```

Patch quan trọng Phase 6 (nếu chạy lẻ):

- `vn_sys_config_defaults.sql` — cấu hình VN (ship, hủy đơn 24h, ngân hàng rút tiền…)
- `vn_lang_currency_cleanup.sql` — thay ￥/Nhân dân tệ → đ/VND trong `eb_lang_code`
- `vn_group_data_defaults.sql` — điểm danh, liên kết DIY
- `vn_diy_label_migration.sql` — nhãn theme/DIY trong JSON DB (tùy chọn, file lớn)

## f-chan (Zalo Mini App)

1. Copy `f-chan/app-config.production.example.json` → `app-config.json`
2. `template.apiUrl` = `https://your-domain.com/api`
3. Bật `features.catalog`, `orders`, `checkout`
4. Admin → Ứng dụng → Zalo: App ID, Secret, theme
5. Deploy: `cd f-chan && zmp deploy`

Checklist chi tiết: [`f-chan/docs/crmeb-rollout-checklist.md`](../f-chan/docs/crmeb-rollout-checklist.md)

## Dịch vụ nền (production)

```bash
cd crmeb
php think queue:listen --queue
php think timer start --d
# Workerman (chat/notify) nếu dùng:
php think workerman start --d
```

## Kiểm tra sau deploy

- [ ] Admin login, timezone hiển thị giờ VN
- [ ] API `GET /api/city_list` trả tỉnh VN (sau `vn_city_migration.sql`)
- [ ] Đăng ký/SĐT validate format VN
- [ ] Thanh toán COD/VietQR/VNPay (xem [`vn-payment-setup.md`](./vn-payment-setup.md))
- [ ] f-chan: auth Zalo → catalog → checkout E2E

## Tài liệu liên quan

| File | Nội dung |
|------|----------|
| [`vn-payment-setup.md`](./vn-payment-setup.md) | Cấu hình COD, VietQR, VNPay, MoMo, ZaloPay |
| [`order-flow-vietnam.md`](./order-flow-vietnam.md) | Luồng đơn hàng VN |
| [`order-flow-vietnam-mapping.md`](./order-flow-vietnam-mapping.md) | Mapping trạng thái CRMEB ↔ UX VN |
