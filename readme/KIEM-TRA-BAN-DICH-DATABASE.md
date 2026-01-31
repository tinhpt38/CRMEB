# Kiểm tra bản dịch trong database

Tài liệu này giúp bạn **kiểm tra đã có bản dịch tiếng Việt trong database chưa** bằng các câu SQL. Chạy trong MySQL/MariaDB (hoặc client tương ứng), thay `eb_` bằng prefix bảng trong `.env` nếu khác.

---

## 1. Ngôn ngữ Tiếng Việt (`eb_lang_type`)

Bảng lưu loại ngôn ngữ. Tiếng Việt thường có `id = 10`, `file_name = 'vi-VN'`.

**Kiểm tra:**

```sql
SELECT id, file_name, language_name, is_default, status
FROM eb_lang_type
WHERE file_name = 'vi-VN';
```

- **Đã có bản dịch:** Có 1 dòng, `language_name` thường là `Tiếng Việt` (nếu đã chạy `patch_lang_type_vi.sql`).
- **Chưa có:** 0 dòng → cần thêm bản ghi vi-VN (theo cấu trúc cài đặt gốc CRMEB). Nếu có dòng nhưng `language_name` vẫn là tiếng Trung/Anh → chạy file `crmeb/public/install/patch_lang_type_vi.sql`.

---

## 2. Tiêu đề tab Cài đặt hệ thống (`eb_system_config_tab_lang`)

Bảng lưu **bản dịch tên tab** cấu hình theo ngôn ngữ (vd: “Cấu hình cơ bản”, “Cấu hình người dùng”). Tiếng Việt dùng `lang_type_id = 10`.

**Kiểm tra bảng có tồn tại và có bản dịch vi-VN:**

```sql
-- Bảng có tồn tại không
SHOW TABLES LIKE 'eb_system_config_tab_lang';

-- Số bản dịch tiếng Việt (lang_type_id = 10)
SELECT COUNT(*) AS so_ban_dich_tab_tieng_viet
FROM eb_system_config_tab_lang
WHERE lang_type_id = 10;
```

- **Đã có bản dịch:** Bảng tồn tại và `COUNT(*)` khoảng 70+ (tương ứng số tab trong patch).
- **Chưa có:** Bảng không tồn tại hoặc `COUNT(*) = 0` → chạy `crmeb/public/install/patch_system_config_tab_lang_vi.sql`.

**Xem vài dòng mẫu:**

```sql
SELECT tab_id, lang_type_id, title
FROM eb_system_config_tab_lang
WHERE lang_type_id = 10
ORDER BY tab_id
LIMIT 10;
```

---

## 3. Mã thông báo API / H5 (`eb_lang_code`)

Bảng lưu **bản dịch thông báo** (vd: “Lưu thành công”, “Vui lòng đăng nhập”) theo `lang_type_id`. Backend dùng khi client gửi header `cb-lang: vi-VN`.

**Kiểm tra:**

```sql
SELECT COUNT(*) AS so_ban_dich_code_tieng_viet
FROM eb_lang_code
WHERE lang_type_id = 10;
```

- **Đã có:** Số lượng lớn (hàng trăm trở lên tùy bản CRMEB).
- **Chưa có:** 0 → cần import hoặc cập nhật dữ liệu tiếng Việt cho `eb_lang_code` (trong Admin: Cài đặt → Đa ngôn ngữ → Ngôn ngữ / Mã ngôn ngữ, hoặc theo tài liệu bản vá của bạn).

---

## 4. Dữ liệu mặc định đã “Việt hóa” (`patch_default_data_vi`)

Các bảng **không** có bảng dịch riêng mà được cập nhật trực tiếp (tiêu đề thỏa thuận, tên cấp đại lý, tên nhóm phân loại).

**Kiểm tra nhanh:**

```sql
-- eb_agreement: 1 dòng có title tiếng Việt (vd id=1)
SELECT id, title FROM eb_agreement WHERE id = 1;

-- eb_agent_level: 1 dòng (vd id=1)
SELECT id, name FROM eb_agent_level WHERE id = 1;

-- eb_category: 1 dòng (vd id=1)
SELECT id, name FROM eb_category WHERE id = 1;
```

- **Đã chạy patch:** `title`/`name` là tiếng Việt (vd: “Thỏa thuận hội viên trả phí”, “Cấp phân phối 1”, “Giới tính khách hàng”).
- **Chưa:** Vẫn là tiếng Trung/Anh → chạy `crmeb/public/install/patch_default_data_vi.sql`.

---

## 5. Tóm tắt: Đã có bản dịch trong database chưa?

| Kiểm tra | Câu SQL / Cách xem | Đã đủ khi |
|----------|--------------------|-----------|
| Ngôn ngữ vi-VN | `SELECT ... FROM eb_lang_type WHERE file_name = 'vi-VN'` | Có 1 dòng, `language_name` = "Tiếng Việt" nếu đã patch |
| Tab cấu hình (tiếng Việt) | `SELECT COUNT(*) FROM eb_system_config_tab_lang WHERE lang_type_id = 10` | Bảng tồn tại, count ≥ 1 (thường 70+) |
| Thông báo API (tiếng Việt) | `SELECT COUNT(*) FROM eb_lang_code WHERE lang_type_id = 10` | Count lớn (hàng trăm+) |
| Dữ liệu mặc định Việt hóa | `SELECT title FROM eb_agreement WHERE id=1` (và agent_level, category) | Giá trị hiển thị là tiếng Việt |

Nếu tất cả các mục trên đều thỏa mãn thì **đã có bản dịch trong database** tương ứng với các patch và cấu hình hiện tại. Nếu thiếu mục nào, chạy đúng file patch tương ứng trong `crmeb/public/install/` (và đảm bảo prefix bảng trong file SQL trùng với `.env`).
