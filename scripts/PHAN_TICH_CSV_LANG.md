# Phân tích dữ liệu đa ngôn ngữ (từ CSV xuất SQL)

Nguồn: `eb_lang_type.csv`, `eb_lang_country.csv`, `eb_lang_code.csv` (xuất từ database).

---

## 1. eb_lang_type.csv — Loại ngôn ngữ hệ thống

| Cột | Ý nghĩa |
|-----|---------|
| id | PK, dùng làm type_id ở bảng khác |
| language_name | Tên hiển thị (vd: Tiếng Việt, 中文, English) |
| file_name | Mã locale (zh-CN, vi-VN, en-US, …) |
| status | 1 = bật |
| is_default | 1 = ngôn ngữ mặc định |
| is_del | 0 = chưa xóa |

**Dữ liệu hiện tại (10 ngôn ngữ):**

| id | language_name | file_name | is_default |
|----|---------------|-----------|------------|
| 1 | 中文 | zh-CN | 0 |
| 2 | English | en-US | 0 |
| 3 | 繁體中文 | zh-Hant | 0 |
| 4 | Français | fr-FR | 0 |
| 5 | Italiano | it-IT | 0 |
| 6 | 日本語 | ja-JP | 0 |
| 7 | 한국어 | ko-KR | 0 |
| 8 | Монгол | mn-MN | 0 |
| 9 | ภาษาไทย | th-TH | 0 |
| **10** | **Tiếng Việt** | **vi-VN** | **1** |

**Kết luận:** Tiếng Việt (id=10, file_name=vi-VN) đang là **ngôn ngữ mặc định** (is_default=1). Cấu hình đúng cho dùng mặc định tiếng Việt.

---

## 2. eb_lang_country.csv — Ánh xạ mã trình duyệt → type_id

| Cột | Ý nghĩa |
|-----|---------|
| id | PK |
| type_id | Trỏ tới eb_lang_type.id; **0** = chưa gán ngôn ngữ hỗ trợ |
| code | Mã locale (vi-VN, zh-CN, en-US, vi, …) |
| name | Tên hiển thị (vd: Tiếng Việt (Việt Nam)) |

**Một số bản ghi quan trọng:**

- **type_id = 10 (tiếng Việt):**
  - id=340: code=`vi`, name=`Tiếng Việt`
  - id=341: code=`vi-VN`, name=`Tiếng Việt (Việt Nam)` ← dùng cho header **cb-lang: vi-VN**
- **Các ngôn ngữ khác:** type_id=1 (zh-CN), 2 (en-US), 3 (zh-Hant), 4 (fr-FR), 5 (it-IT), 6 (ja-JP), 7 (ko-KR), 8 (mn-MN), 9 (th-TH) đều có ít nhất một dòng code tương ứng (vd: zh-CN, en-US).

**Luồng runtime:** Client gửi header **cb-lang: vi-VN** → backend tra `eb_lang_country` (code=vi-VN) → type_id=10 → lấy bản dịch từ `eb_lang_code` với type_id=10.

---

## 3. eb_lang_code.csv — Bản dịch theo mã (code) và ngôn ngữ (type_id)

| Cột | Ý nghĩa |
|-----|---------|
| id | PK |
| type_id | eb_lang_type.id (1=zh-CN, 10=vi-VN, …) |
| code | Mã thông báo (vd: 100000, 100010, "选择地址") — dùng trong getLang(code) |
| remarks | Ghi chú / bản gốc (thường tiếng Trung) |
| lang_explain | **Nội dung hiển thị** theo ngôn ngữ (bản dịch) |
| is_admin | **1** = dùng cho Admin/API (接口语言), **0** = dùng cho user/H5 (页面语言) |

**Thống kê từ file:**

- Tổng số dòng (có dữ liệu): khoảng **25.212** (trừ header).
- Số bản ghi **type_id = 10 (tiếng Việt):** **2.521** dòng.
- Cùng một `code` có thể có nhiều dòng (mỗi type_id một dòng); một số code có cả is_admin=0 và is_admin=1 cho cùng type_id.

**Ví dụ bản dịch tiếng Việt (type_id=10):**

| code | remarks (gốc) | lang_explain (tiếng Việt) | is_admin |
|------|----------------|----------------------------|----------|
| 100000 | 保存成功 | Lưu thành công | 1 |
| 100001 | 修改成功 | Sửa đổi thành công | 1 |
| 100002 | 删除成功 | Xóa thành công | 1 |
| 100010 | 操作成功 | Thao tác thành công | 1 |
| 100011 | 暂无数据 | Không có dữ liệu | 1 |
| 选择地址 | 选择地址 | Chọn địa chỉ | 0 |
| 优惠券 | 优惠券 | Phiếu thưởng | 0 |
| 已售罄 | 已售罄 | Hết hàng | 0 |

**Kết luận:** Kho bản dịch tiếng Việt (2.521 bản ghi) đã có sẵn cho cả **Admin/API** (is_admin=1) và **user/H5** (is_admin=0). Backend dùng getLang(code) với type_id lấy từ cb-lang → trả đúng lang_explain tiếng Việt.

---

## 4. Tóm tắt luồng dữ liệu

```
Client gửi header: cb-lang: vi-VN
        ↓
eb_lang_country: code = 'vi-VN' → type_id = 10
        ↓
eb_lang_code: type_id = 10, code = <mã> → lang_explain (tiếng Việt)
        ↓
getLang(code) trả về chuỗi tiếng Việt
```

- **eb_lang_type:** Định nghĩa ngôn ngữ (id=10 = Tiếng Việt, đang mặc định).
- **eb_lang_country:** Ánh xạ vi-VN → type_id=10.
- **eb_lang_code:** Lưu bản dịch (code + type_id + is_admin → lang_explain).

---

## 5. Gợi ý sử dụng CSV

- **Kiểm tra thiếu bản dịch:** So sánh danh sách `code` có type_id=1 (zh-CN) với type_id=10 (vi-VN); code nào chỉ có type_id=1 mà không có type_id=10 thì cần bổ sung.
- **Bổ sung / sửa bản dịch:** Cập nhật hoặc thêm dòng trong `eb_lang_code` (type_id=10, lang_explain = bản tiếng Việt), sau đó import lại hoặc dùng API Admin (lang_code/save) để đồng bộ.
- **Đổi ngôn ngữ mặc định:** Sửa `eb_lang_type` (is_default=1 cho id=10 đã đúng nếu muốn mặc định tiếng Việt).
