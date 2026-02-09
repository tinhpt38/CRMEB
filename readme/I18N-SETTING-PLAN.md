# Kế hoạch i18n module Setting (Admin)

Làm lần lượt theo thứ tự dưới đây. Mỗi bước: thêm khóa vào `zh-cn.js` + `vi-vn.js` → cập nhật file Vue dùng `$t()`.

---

## Đã hoàn thành

| # | Trang | File chính | Ghi chú |
|---|--------|------------|--------|
| - | setApp | index.vue | Đã i18n |
| - | link | index.vue | Đã i18n |
| - | systemAdmin | index.vue | Đã i18n |
| - | freight | index.vue | Đã i18n |
| - | systemStore | index.vue | Đã i18n |
| - | verifyOrder | index.vue | Đã i18n |
| - | storage | index.vue | Đã i18n |
| - | notification | index.vue, keysList.vue, notificationEdit.vue | Đã i18n |
| - | storeService | index.vue, autoReply.vue, feedback.vue, speechcraft.vue | Đã i18n |
| - | systemOutInterface | index.vue, debugging.vue (MonacoEditor.vue không chuỗi UI) | Đã i18n |
| - | cityDada | index.vue | Đã i18n |
| - | clerkList | index.vue | Đã i18n |
| - | deliveryService | index.vue | Đã i18n |
| - | shippingTemplates | index.vue | Đã i18n |
| - | storeList | index.vue | Đã i18n |
| - | themeStyle | index.vue | Đã i18n |
| - | user (profile) | user/index.vue | Đã i18n (key: setting.profile) |
| - | userFile | index.vue | Đã i18n |
| - | devise | index.vue, diyIndex.vue, goodClass.vue, links.vue, list.vue, template.vue, users.vue, uploadPic.vue | Đã i18n (key: router.setting.devise) |

---

## Còn lại – thứ tự thực hiện

### Nhóm 1 – Trang đơn giản (1 file, ít chuỗi) ✅

| # | Trang | File | Trạng thái |
|---|--------|------|------------|
| 1 | **agreement** | agreement/index.vue | Đã i18n (dùng tabs + common.save) |
| 2 | **setSystem** | setSystem/index.vue | Chỉ comment, không chuỗi UI |

---

### Nhóm 2 – Trang đơn file, độ phức tạp trung bình ✅

| # | Trang | File | Trạng thái |
|---|--------|------|------------|
| 3 | **membershipLevel** | membershipLevel/index.vue | Đã i18n |
| 4 | **systemRole** | systemRole/index.vue | Đã i18n |
| 5 | **ticket** | ticket/index.vue, ticket/content.vue | Đã i18n (index đã có, content đã bổ sung) |

---

### Nhóm 3 – Multi-file nhỏ (2–3 file) ✅

| # | Trang | File | Trạng thái |
|---|--------|------|------------|
| 6 | **multiLanguage** | list.vue, langList.vue, country.vue | Đã i18n |
| 7 | **devisePage** | index.vue, links.vue, list.vue | Đã i18n |
| 8 | **systemMenus** | index.vue, components/menusFrom.vue | Đã i18n |
| 9 | **systemOutAccount** | index.vue | Đã i18n |

---

### Nhóm 4 – Multi-file + component ✅

| # | Trang | File | Trạng thái |
|---|--------|------|------------|
| 10 | **storeService** | index.vue, autoReply.vue, feedback.vue, speechcraft.vue | Đã i18n |
| 11 | **notification (bổ sung)** | components/keysList.vue, notificationEdit.vue | Đã i18n |
| 12 | **systemOutInterface** | index.vue, debugging.vue (MonacoEditor.vue không chuỗi UI) | Đã i18n |

---

### Nhóm 5 – Devise (nhiều file) ✅

| # | Trang | File | Trạng thái |
|---|--------|------|------------|
| 13 | **devise** | index.vue, diyIndex.vue, goodClass.vue, links.vue, list.vue, template.vue, users.vue, components/uploadPic.vue | Đã i18n |

---

## Cách làm mỗi bước

1. Mở trang tương ứng, grep chuỗi Trung văn: `[\u4e00-\u9fff]+` trong các file Vue của trang đó.
2. Thêm khóa vào `template/admin/src/i18n/lang/zh-cn.js` và `vi-vn.js` dưới `message.pages.setting.<tênTrang>` (ví dụ: `membershipLevel`, `systemRole`, `ticket`, …).
3. Trong từng file Vue: thay chuỗi hiển thị bằng `$t('message.pages.setting.<tênTrang>.<key>')`; form validation/rule message gán trong `created()`/`mounted()` bằng `this.$t(...)`.
4. Chạy linter / build để tránh lỗi.

---

## Ghi chú

- **notification**: index đã i18n; chỉ bổ sung khi `keysList.vue`, `notificationEdit.vue` còn chuỗi cứng.
- **setSystem**: có thể chỉ còn title; kiểm tra rồi mới quyết định thêm key.
- **devise**: khối lượng lớn, nên làm sau khi xong các nhóm 1–4.

Làm xong từng trang thì cập nhật lại bảng “Đã hoàn thành” trong file này.

---

## Kế hoạch i18n toàn dự án

Xem **readme/I18N-FULL-PLAN.md** để có kế hoạch dịch toàn bộ admin (pages, components, layout, …) sang i18n, thứ tự phase và quy trình làm từng module.
