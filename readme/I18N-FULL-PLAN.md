# Kế hoạch i18n toàn bộ Admin (CRMEB)

Mục tiêu: chuyển toàn bộ text tiếng Trung trong `template/admin/src` sang i18n (zh-cn.js + vi-vn.js), hiển thị qua `$t()`.

---

## 1. Tổng quan

| Hạng mục | Giá trị |
|----------|--------|
| Phạm vi quét | `template/admin/src` (`.vue`, `.js`), bỏ qua `node_modules`, `dist`, `i18n` |
| Chuỗi unique (sau báo cáo) | ~7.239 (trong đó ~3.428 chuỗi ≥ 4 ký tự) |
| Locale đích | `i18n/lang/zh-cn.js`, `i18n/lang/vi-vn.js` |
| Sidebar / menu | `menuTitlesViVn` trong vi-vn.js (backend trả về title tiếng Trung → dịch sang Việt) |

---

## 2. Công cụ

| Công cụ | Mô tả |
|---------|--------|
| **scripts/find_chinese_i18n.py** | Tìm chuỗi tiếng Trung, xuất báo cáo, hỗ trợ thay thế bằng `$t()` |
| **scripts/i18n_report/** | Thư mục báo cáo sau khi chạy `--report` |

### Lệnh thường dùng

```bash
# Báo cáo toàn bộ (chuỗi unique + gợi ý dịch)
python3 scripts/find_chinese_i18n.py --skip-comments --report

# Chỉ quét một module
python3 scripts/find_chinese_i18n.py --dir pages/kefu --skip-comments --output text

# Xuất JSON để xử lý
python3 scripts/find_chinese_i18n.py --skip-comments --output json
```

### File báo cáo

- `scripts/i18n_report/translate_report.json` – Chi tiết từng chuỗi (zh, vi_suggested, sample, count)
- `scripts/i18n_report/zh_to_vi_flat.json` – Bảng zh → vi
- `scripts/i18n_report/translate_report.txt` – Báo cáo dạng text (chuỗi ≥ 4 ký tự)

---

## 3. Quy ước key i18n

| Ngữ cảnh | Prefix key | Ví dụ |
|----------|------------|--------|
| Trang trong `pages/<module>/` | `message.pages.<module>.<sub>.<key>` | `message.pages.kefu.mobile.orderDelivery.deliveryCompany` |
| Setting | `message.pages.setting.<trang>.<key>` | `message.pages.setting.ticket.content.save` |
| Router / menu | `router.<module>.<key>` hoặc key = chuỗi Trung trong `menuTitlesViVn` | `router.setting.devise.home` |
| Dùng chung (common) | `message.common.<key>` | `message.common.on`, `message.common.save` |
| User / layout | `message.user.<key>` | `message.user.logOutSuccess` |

---

## 4. Thứ tự thực hiện (phase)

### Phase 1 – Đã xong

- **Setting**: toàn bộ trang setting (xem `readme/I18N-SETTING-PLAN.md`)
- **Account / Login**: login/index.vue (warmTip, loginFail, validateError, queue/timer/socket msg)
- **Layout / Sidebar**: menuTitlesViVn, user bar (mallPage, fullscreenNotSupport, logOutSuccess)
- **Kefu (một phần)**: rightMenu, mobile/user, orderDelivery

### Phase 2 – Kefu (tiếp)

| # | Trang / file | Ước lượng | Trạng thái |
|---|--------------|-----------|------------|
| 1 | kefu/pc/index.vue, appChat, components | Nhiều | Chưa |
| 2 | kefu/mobile/orderList (orderDetail, index), goods | Nhiều | orderDelivery ✅ |
| 3 | kefu/pc/components (delivery, msgWindow, chatList, …) | Nhiều | rightMenu ✅ |

### Phase 3 – Marketing

| # | Trang / file | Ước lượng | Trạng thái |
|---|--------------|-----------|------------|
| 1 | marketing/lottery (addGoods, create, config, …) | Nhiều | Chưa |
| 2 | marketing/storeCoupon, storeCouponIssue | Trung bình | Chưa |
| 3 | marketing/storeSeckill, storeBargain, storeCombination | Nhiều | Chưa |
| 4 | marketing/storeIntegralOrder, storePresell | Nhiều | Chưa |
| 5 | marketing/channelCode, live, recharge, sign | Nhiều | Chưa |

### Phase 4 – Product

| # | Trang / file | Ước lượng | Trạng thái |
|---|--------------|-----------|------------|
| 1 | product/productList, productAdd, components | Nhiều | Chưa |
| 2 | product/productAttr, productReply, labelList, paramList | Trung bình | Chưa |

### Phase 5 – Order

| # | Trang / file | Ước lượng | Trạng thái |
|---|--------------|-----------|------------|
| 1 | order/orderList (tableList, handle, …) | Nhiều | Chưa |
| 2 | order/invoice, offline, print, refund | Ít–trung bình | Chưa |

### Phase 6 – System

| # | Trang / file | Ước lượng | Trạng thái |
|---|--------------|-----------|------------|
| 1 | system/codeGeneration, configTab, crontab, event | Nhiều | configTab list ✅ |
| 2 | system/backendRouting, auth, clear | Nhiều | Chưa |
| 3 | system/maintain (systemFile, systemLog, …) | Nhiều | Chưa |
| 4 | system/group, systemMenus, onlineUpgrade, error | Trung bình | Chưa |

### Phase 7 – Finance, Statistic, User, Division, Agent

| Module | Ước lượng | Trạng thái |
|--------|-----------|------------|
| finance/ | Nhiều | Chưa |
| statistic/ | Trung bình | Chưa |
| user/ (list, level, grade, group, label, cancel) | Nhiều | Chưa |
| division/ | Trung bình | Chưa |
| agent/ | Ít | Chưa |

### Phase 8 – App, Notify, CMS, Index

| Module | Ước lượng | Trạng thái |
|--------|-----------|------------|
| app/ (wechat, routine, upload, version) | Trung bình | Chưa |
| notify/ (smsConfig, smsPay, …) | Trung bình | Chưa |
| cms/ | Ít | Chưa |
| index/ (trang chủ, components) | Trung bình | Chưa |

### Phase 9 – Components dùng chung

| Thư mục | Ghi chú |
|---------|--------|
| components/mobilePage, mobilePageDiy, mobileConfig, mobileConfigRight | Nhiều chuỗi, dùng cho DIY / cấu hình |
| components/diyComponents, linkaddress, uploadPictures, cropperImg | Nhiều | Chưa |
| components/couponList, sendCoupons, verifition, hotpotModal | Trung bình | Chưa |

### Phase 10 – Layout, Store, Router, Libs, Utils

| Hạng mục | Ưu tiên | Ghi chú |
|----------|---------|--------|
| layout/ | Trung bình | Một phần đã dùng $t() |
| store/module | Thấp | Chuỗi trong code logic |
| router/modules | Thấp | Title đã map qua menuTitlesViVn |
| libs/, utils/, config/, api/ | Rất thấp | Comment, JSDoc; có thể bỏ qua hoặc làm sau |

---

## 5. Quy trình làm từng module

1. **Chạy báo cáo** (có thể theo thư mục):
   ```bash
   python3 scripts/find_chinese_i18n.py --dir pages/<module> --skip-comments --report
   ```
2. **Đọc báo cáo**: `scripts/i18n_report/translate_report.txt` hoặc `.json`, lọc chuỗi cần dịch (bỏ comment nếu đã dùng `--skip-comments`).
3. **Thêm key** vào `i18n/lang/zh-cn.js` và `i18n/lang/vi-vn.js` theo quy ước (ví dụ `message.pages.<module>.<sub>.<key>`).
4. **Thay chuỗi trong code**: template dùng `{{ $t('...') }}`, script dùng `this.$t('...')`.
5. **Kiểm tra**: chạy linter/build, mở trang trên trình duyệt (đổi locale vi-vn) xem còn sót hoặc key sai.

---

## 6. Thống kê nguồn (từ báo cáo mẫu)

Số file/vị trí có chuỗi tiếng Trung (ước lượng theo thư mục):

| Thư mục | Số file ước lượng | Ghi chú |
|---------|-------------------|--------|
| pages/system | 443 | Lớn nhất |
| components/freightTemplate | 389 | |
| pages/marketing | 337 | |
| components/mobilePage | 328 | |
| pages/kefu | 251 | Một phần đã xong |
| pages/product | 151 | |
| store/module | 123 | |
| router/modules | 120 | Title → menuTitlesViVn |
| pages/setting | 96 | Đã xong |
| … | … | |

*(Cập nhật lại số liệu sau mỗi lần chạy `--report` nếu cần.)*

---

## 7. Tài liệu liên quan

- **readme/I18N-SETTING-PLAN.md** – Kế hoạch chi tiết module Setting (đã hoàn thành).
- **scripts/find_chinese_i18n.py** – Docstring trong file: cách dùng `--dir`, `--output`, `--report`, `--apply`, `--skip-comments`.

Sau khi hoàn thành từng phase hoặc từng module, nên cập nhật lại bảng trạng thái trong file này và trong `I18N-SETTING-PLAN.md` (nếu liên quan setting).
