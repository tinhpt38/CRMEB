# SQL Patches — Việt hóa CRMEB

Thư mục patch idempotent cho DB **đang vận hành**. Cài mới từ `crmeb.sql` đã gồm nhiều giá trị VN Phase 6; vẫn nên chạy patch thanh toán/ngôn ngữ nếu bản SQL cũ hơn.

## Thứ tự khuyến nghị

| # | File | Phase | Mô tả |
|---|------|-------|--------|
| 1 | `vn_lang_defaults.sql` | P2 | Ngôn ngữ mặc định vi-VN |
| 2 | `vn_city_migration.sql` | P1 | Tỉnh/thành/phường VN (`eb_system_city`) |
| 3 | `vn_sys_config_defaults.sql` | P6 | Cấu hình hệ thống VN |
| 4 | `vn_lang_currency_cleanup.sql` | P6 | ￥/Nhân dân tệ → đ/VND trong lang |
| 5 | `vn_group_data_defaults.sql` | P6 | Điểm danh, liên kết DIY |
| 6 | `vn_status_labels_global.sql` | P4+ | Hoạt động / Ngưng hoạt động |
| 7 | `vn_pay_basic_cleanup.sql` | P4 | Dọn tab thanh toán cũ |
| 8 | `vn_pay_tabs_reorganize.sql` | P4 | Tab COD/VietQR/VNPay/MoMo/ZaloPay |
| 9 | `vn_pay_admin_config.sql` | P4 | Config COD + VietQR |
| 10 | `vn_pay_gateways.sql` | P4 | Config VNPay/MoMo/ZaloPay |
| 11 | `vn_diy_label_migration.sql` | P2/P6 | Việt hóa JSON theme/DIY (lớn, tùy chọn) |
| 12 | `vn_notice_channel_registry.sql` | — | Kênh thông báo (nếu dùng) |
| 13 | `vn_notification_telegram.sql` | — | Telegram notify (nếu dùng) |

Sau mỗi lần chạy patch: `cd crmeb && php think clear`

## Chạy nhanh

```bash
# Cách 1: script (truyền lệnh mysql đầy đủ)
bash crmeb/public/install/patches/apply-vn-patches.sh "mysql -uUSER -pPASS DBNAME"

# Cách 2: từng file
mysql -uUSER -pPASS DBNAME < crmeb/public/install/patches/vn_sys_config_defaults.sql
```

## Ghi chú

- Patch **không gán id cứng** cho config mới (tránh lỗi `#1062 Duplicate PRIMARY`).
- `vn_diy_label_migration.sql` ~1400 dòng REPLACE — chạy off-peak; backup trước.
- `DiyHomeLabelMap.php` vẫn là fallback runtime nếu JSON DB còn nhãn Trung.
