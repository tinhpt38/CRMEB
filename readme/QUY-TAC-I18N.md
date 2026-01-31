# Quy tắc đa ngôn ngữ (i18n) – Không hardcode

Mục tiêu: **dịch toàn bộ mã nguồn Admin thành đa ngôn ngữ**, không để chuỗi hiển thị (label, placeholder, message, nút, option…) hardcode trong code.

---

## 1. Nguyên tắc

- **Không hardcode** bất kỳ chuỗi nào hiển thị cho người dùng (tiếng Trung, tiếng Anh, tiếng Việt…) trong file `.vue` hoặc script Admin.
- **Luôn dùng** Vue I18n: `$t('message.pages.<module>.<section>.<key>')` (hoặc key tương ứng đã có trong `i18n/lang`).
- **Backend**: thông báo API (`msg`) dùng `getLang($code)`; nhãn form Cài đặt hệ thống dùng `admin_form.php` + header `cb-lang`.

---

## 2. Cấu trúc key i18n (Admin)

- File ngôn ngữ: `template/admin/src/i18n/lang/zh-cn.js` (nguồn), `vi-vn.js` (tiếng Việt), `en.js`, `zh-tw.js`.
- Cấu trúc: `message.pages.<module>.<section>.<key>`.
  - **module**: tên module trang, ví dụ `order`, `user`, `setting`, `product`, `marketing`.
  - **section**: nhóm nhỏ trong trang, ví dụ `list`, `details`, `refund`, `invoice`, `send`.
  - **key**: tên ngắn gọn, camelCase, ví dụ `orderNo`, `inputOrderNo`, `query`, `confirm`.

Ví dụ:
```js
// zh-cn.js & vi-vn.js
order: {
  list: { orderNo: '订单号', inputOrderNo: '请输入订单号', query: '查询', all: '全部' },
  refund: { msgSelectRefundNum: '请选择退款数量' },
  invoice: { createTime: '创建时间：', tabAll: '全部发票' }
}
```

---

## 3. Cách dùng trong Vue

| Vị trí | Sai (hardcode) | Đúng (i18n) |
|--------|-----------------|-------------|
| Label form | `label="请输入订单号"` | `:label="$t('message.pages.order.list.inputOrderNo')"` |
| Placeholder | `placeholder="请输入"` | `:placeholder="$t('message.pages.order.list.placeholder')"` |
| Nút / tab | `>查询</el-button>` | `>{{ $t('message.pages.order.list.query') }}</el-button>` |
| Option select | `label="全部"` | `:label="$t('message.pages.order.list.all')"` |
| Empty text | `empty-text="暂无数据"` | `:empty-text="$t('message.pages.order.list.noData')"` |
| Title dialog | `title="订单详情"` | `:title="$t('message.pages.order.details.title')"` |
| Message lỗi | `this.$message.error('请选择')` | `this.$message.error(this.$t('message.pages.order.refund.msgSelectRefundNum'))` |
| Confirm | `this.$confirm('确定？', '提示', { confirmButtonText: '确定' })` | Dùng `$t()` cho từng chuỗi |

Trong **script** (methods, computed): dùng `this.$t('message.pages....')`.

---

## 4. Option select từ API

Nếu backend trả về mảng option với `name` bằng một ngôn ngữ cố định (vd tiếng Trung), **không** hiển thị trực tiếp `item.name`. Nên map index/value sang key i18n ở frontend.

Ví dụ (trạng thái hoàn tiền):
```js
// methods
getRefundStatusFilterLabel(index) {
  const keys = ['all', 'refundOnly', 'returnRefund', 'refuseRefund', 'goodsToReturn', 'returnToReceive', 'refunded'];
  return this.$t('message.pages.order.refundList.' + (keys[index] || 'all'));
}
```
```vue
<el-option v-for="(item, index) in num" :value="index" :key="index" :label="getRefundStatusFilterLabel(index)" />
```

---

## 5. Thêm key mới khi làm trang / tính năng mới

1. Mở `template/admin/src/i18n/lang/zh-cn.js`, tìm hoặc tạo nhánh `message.pages.<module>.<section>` và thêm key (chuỗi tiếng Trung hoặc nguồn).
2. Mở `template/admin/src/i18n/lang/vi-vn.js`, thêm **cùng key** với bản dịch tiếng Việt.
3. (Tùy chọn) Thêm vào `en.js`, `zh-tw.js` nếu cần.
4. Trong file `.vue`: thay mọi chuỗi hiển thị bằng `$t('message.pages.<module>.<section>.<key>')` hoặc `this.$t(...)`.

---

## 6. Backend (API, form hệ thống)

- **Thông báo API**: dùng mã ngôn ngữ trong `eb_lang_code`; client gửi header `cb-lang` (vd `vi-VN`); backend dùng `getLang($code)` để trả `msg` đúng ngôn ngữ.
- **Form Cài đặt hệ thống**: nhãn trường lấy từ backend (`eb_system_config.info`). Để đa ngôn ngữ: thêm bản dịch trong `crmeb/app/lang/admin_form.php` và logic thay `info` theo `cb-lang` trong `SystemConfigServices::getConfigForm()`.
- **Menu trái**: tên menu từ backend; bản dịch tiếng Việt map trong `vi-vn.js` (`menuTitlesViVn`).

---

## 7. Checklist khi sửa / thêm trang

- [ ] Không còn `label="..."`, `placeholder="..."`, `title="..."` chứa chữ hiển thị (Trung/Anh/Việt) trực tiếp; đã đổi sang `:label="$t(...)"` v.v.
- [ ] Nút, tab, cột bảng, empty-text, dialog title đều dùng `$t()`.
- [ ] Message lỗi / thành công và `$message`, `$confirm` dùng `this.$t()`.
- [ ] Option select: hoặc dùng key i18n, hoặc map từ API sang key (method/computed).
- [ ] Key mới đã thêm vào cả `zh-cn.js` và `vi-vn.js` (và locale khác nếu cần).

---

## 8. Tài liệu liên quan

- **Hướng dẫn tiếng Việt Admin**: [HUONG-DAN-TIENG-VIET-ADMIN.md](HUONG-DAN-TIENG-VIET-ADMIN.md)
- **Kiểm tra bản dịch database**: [KIEM-TRA-BAN-DICH-DATABASE.md](KIEM-TRA-BAN-DICH-DATABASE.md)
- **Cursor rule** (AI): `.cursor/rules/i18n-no-hardcode.mdc` – áp dụng khi chỉnh file trong `template/admin/src/**/*.vue`.
