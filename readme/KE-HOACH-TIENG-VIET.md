# Kế hoạch làm việc: Chuyển CRMEB sang tiếng Việt

Kế hoạch thực hiện theo từng giai đoạn, dựa trên tài liệu [DA-NGON-NGU-TIENG-VIET.md](DA-NGON-NGU-TIENG-VIET.md).

---

## Tổng quan

| Giai đoạn | Mục tiêu | Ưu tiên |
|-----------|----------|---------|
| **1** | API / H5 / App dùng tiếng Việt (getLang) | Cao |
| **2** | Cấu hình mặc định & tên hiển thị ngôn ngữ | Cao |
| **3** | Giao diện Admin – Vue i18n (locale vi-vn) | Cao |
| **4** | Tab & menu Admin (DB + hardcode) | Trung bình |
| **5** | Dữ liệu mặc định (giao thức, danh mục, …) | Trung bình |
| **6** | Backend ThinkPHP lang (tùy chọn) | Thấp |
| **7** | Kiểm tra & tài liệu | Toàn bộ |

---

## Giai đoạn 1: API / H5 / App dùng tiếng Việt

**Mục tiêu:** Client (H5, App, API) nhận thông báo tiếng Việt qua `getLang()`.

| # | Công việc | Chi tiết | Trạng thái |
|---|-----------|----------|------------|
| 1.1 | Kiểm tra header `cb-lang` | Đảm bảo client (H5/App) gửi `cb-lang: vi-VN` khi người dùng chọn tiếng Việt. Kiểm tra request interceptor / axios. | ☐ |
| 1.2 | Đặt mặc định ngôn ngữ (nếu cần) | Trong Admin → Cài đặt → Đa ngôn ngữ: đặt Tiếng Việt (vi-VN) làm ngôn ngữ mặc định (is_default = 1). Hoặc cấu hình mặc định trong code client. | ☐ |
| 1.3 | Kiểm thử API | Gọi vài API (login, lưu, xóa, …) với header `cb-lang: vi-VN`, xác nhận response message tiếng Việt (vd: "Lưu thành công"). | ☐ |

**Kết quả:** API trả về thông báo tiếng Việt khi client gửi `cb-lang: vi-VN`. Không cần sửa DB vì `eb_lang_code` đã có type_id = 10.

---

## Giai đoạn 2: Cấu hình mặc định & tên ngôn ngữ

**Mục tiêu:** Tên hiển thị "Tiếng Việt" đúng và cấu hình nhất quán.

| # | Công việc | Chi tiết | Trạng thái |
|---|-----------|----------|------------|
| 2.1 | Sửa tên ngôn ngữ trong DB | Trong `eb_lang_type`, sửa bản ghi id = 10: `language_name` từ `ViệtName` → **`Tiếng Việt`**. Có thể chạy SQL: `UPDATE eb_lang_type SET language_name = 'Tiếng Việt' WHERE id = 10;` hoặc qua Admin. | ☐ |
| 2.2 | (Tùy chọn) Cấu hình .env | Nếu dùng ThinkPHP lang: trong `.env` đặt `[LANG] default_lang = vi-vn` (sau khi hoàn thành giai đoạn 6). | ☐ |

**Kết quả:** Trong danh sách ngôn ngữ hiển thị "Tiếng Việt" thay vì "ViệtName".

---

## Giai đoạn 3: Giao diện Admin – Vue i18n (locale vi-vn)

**Mục tiêu:** Admin có thể chọn ngôn ngữ Tiếng Việt và hiển thị phần đã dịch.

| # | Công việc | Chi tiết | Trạng thái |
|---|-----------|----------|------------|
| 3.1 | Tạo file i18n tiếng Việt – framework | Tạo `template/admin/src/i18n/lang/vi-vn.js`. Copy từ `zh-cn.js`, dịch toàn bộ value sang tiếng Việt (router, user, staticRoutes, …). | ☐ |
| 3.2 | Tạo file i18n tiếng Việt – pages | Tạo `template/admin/src/i18n/pages/home/vi-vn.js` và `template/admin/src/i18n/pages/login/vi-vn.js`. Copy từ zh-cn rồi dịch. | ☐ |
| 3.3 | Đăng ký locale vi-vn | Trong `template/admin/src/i18n/index.js`: import các file vi-vn, thêm key `'vi-vn'` vào `messages`, cấu trúc giống `zh-cn`. Element UI: dùng tạm `zh-CN` hoặc thêm locale tiếng Việt nếu có. | ☐ |
| 3.4 | Thêm lựa chọn ngôn ngữ trong Admin | Trong store/themeConfig (hoặc nơi lưu `globalI18n`): thêm option Tiếng Việt với value `vi-vn`. Đảm bảo dropdown/chọn ngôn ngữ có thể chọn vi-vn. | ☐ |
| 3.5 | Build & kiểm thử | Chạy build admin, đổi ngôn ngữ sang Tiếng Việt, kiểm tra menu/trang home/login đã dịch. | ☐ |

**Kết quả:** Admin có locale vi-vn; người dùng chọn Tiếng Việt thì phần đã có trong vi-vn.js hiển thị tiếng Việt.

---

## Giai đoạn 4: Tab & menu Admin (DB + hardcode)

**Mục tiêu:** Tab và menu sidebar hiển thị tiếng Việt.

### 4A – Tab lấy từ database (eb_system_config_tab)

| # | Công việc | Chi tiết | Trạng thái |
|---|-----------|----------|------------|
| 4.1 | Chuẩn bị dữ liệu tiếng Việt cho config tab | Liệt kê các `id` và `title` hiện tại trong `eb_system_config_tab`. Soạn bản dịch tiếng Việt (vd: 基础配置 → Cấu hình cơ bản, 接口设置 → Cài đặt giao diện). | ☐ |
| 4.2 | Cập nhật DB | Cách 1: Vào Admin → Cấu hình → Cấu hình phân loại, sửa từng "分类名称" (title) sang tiếng Việt. Cách 2: Viết file patch SQL với các `UPDATE eb_system_config_tab SET title = '...' WHERE id = ...;` và chạy sau khi cài. | ☐ |
| 4.3 | Kiểm thử trang Cài đặt hệ thống | Mở trang Cài đặt hệ thống, xác nhận tab hiển thị tiếng Việt. | ☐ |

### 4B – Tab hardcode trong Vue

| # | Công việc | Chi tiết | Trạng thái |
|---|-----------|----------|------------|
| 4.4 | Thêm key i18n cho tab | Trong `i18n/lang/vi-vn.js` (và zh-cn.js nếu cần), thêm nhóm key cho tab, ví dụ: `tabs: { agreement: { vip: 'Thỏa thuận hội viên trả phí', agent: 'Thỏa thuận đại lý', ... }, backendRouting: { adminapi: 'Giao diện quản trị', ... } }`. | ☐ |
| 4.5 | Sửa component – agreement | `setting/agreement/index.vue`: thay `headerList` dùng `label: this.$t('tabs.agreement.vip')` hoặc dùng mảng với key rồi `$t()`. Tương tự cho từng tab. | ☐ |
| 4.6 | Sửa component – notification, storage | `setting/notification/index.vue`, `setting/storage/index.vue`: thay label tab bằng `$t('...')` + key trong i18n. | ☐ |
| 4.7 | Sửa component – backendRouting, crontab, event, codeGeneration | `system/backendRouting/index.vue`: thay `label="管理端接口"` bằng `:label="$t('tabs.backendRouting.adminapi')"`. Làm tương tự cho crontab, event, codeGeneration. | ☐ |

### 4C – Menu sidebar

| # | Công việc | Chi tiết | Trạng thái |
|---|-----------|----------|------------|
| 4.8 | Thu thập title menu từ backend | Xem API/response trả về menu với `title` (chuỗi tiếng Trung). Liệt kê các giá trị title thường gặp. | ☐ |
| 4.9 | Thêm bản dịch menu vào i18n | Trong vi-vn.js (và có thể zh-cn.js), thêm object map chuỗi tiếng Trung → tiếng Việt, ví dụ: `'配置分类': 'Phân loại cấu hình'`. Hoặc dùng key chuẩn nếu backend đổi trả về key. | ☐ |
| 4.10 | Kiểm thử menu | Đổi locale sang vi-vn, reload, kiểm tra menu trái hiển thị tiếng Việt. | ☐ |

**Kết quả:** Tab (Cài đặt hệ thống + các trang hardcode) và menu sidebar hiển thị tiếng Việt khi chọn locale vi-vn (và với tab từ DB đã cập nhật title).

---

## Giai đoạn 5: Dữ liệu mặc định (giao thức, danh mục, …)

**Mục tiêu:** Nội dung mặc định (giao thức, danh mục, cấp đại lý, …) hiển thị tiếng Việt sau khi cài đặt.

| # | Công việc | Chi tiết | Trạng thái |
|---|-----------|----------|------------|
| 5.1 | Liệt kê bảng & cột cần dịch | Xác định: eb_agreement (title, content), eb_agent_level (name), eb_category (name), và bảng khác có nội dung hiển thị mặc định. | ☐ |
| 5.2 | Soạn nội dung tiếng Việt | Dịch title/content/name cho từng bảng (có thể làm từng phần: trước hết agreement, category, agent_level). | ☐ |
| 5.3 | Tạo patch SQL hoặc hướng dẫn sửa tay | File `crmeb/public/install/crmeb_vn_default_data.sql` (hoặc tên tương tự): các lệnh UPDATE cho eb_agreement, eb_agent_level, eb_category, … Ghi rõ trong readme: chạy sau khi import crmeb.sql. Hoặc chỉ ghi hướng dẫn sửa tay trong Admin. | ☐ |
| 5.4 | Kiểm thử | Import crmeb.sql + chạy patch (nếu có), vào Admin kiểm tra giao thức, danh mục, cấp đại lý hiển thị tiếng Việt. | ☐ |

**Kết quả:** Dữ liệu mặc định (ít nhất agreement, category, agent_level) có bản tiếng Việt khi cài mới.

---

## Giai đoạn 6: Backend ThinkPHP lang (tùy chọn)

**Mục tiêu:** Nếu hệ thống dùng ThinkPHP multi-language (route, validate, …) thì hỗ trợ vi_vn.

| # | Công việc | Chi tiết | Trạng thái |
|---|-----------|----------|------------|
| 6.1 | Tạo file lang vi_vn | Copy `crmeb/app/lang/zh_cn.php` → `crmeb/app/lang/vi_vn.php`, dịch value sang tiếng Việt. | ☐ |
| 6.2 | Cấu hình config/lang.php | Thêm `'vi-vn'` / `'vi_vn'` vào `allow_lang_list`, `extend_list` (`vi_vn` => path to vi_vn.php), `accept_language` nếu cần. | ☐ |
| 6.3 | (Tùy chọn) default_lang | Đặt `default_lang` = `'vi-vn'` trong config hoặc .env nếu muốn mặc định backend là tiếng Việt. | ☐ |

**Kết quả:** ThinkPHP lang hỗ trợ vi_vn; có thể dùng cho message validate, route name, v.v. nếu code gọi Lang.

---

## Giai đoạn 7: Kiểm tra & tài liệu

**Mục tiêu:** Đảm bảo không bỏ sót và người khác có thể vận hành/bảo trì.

| # | Công việc | Chi tiết | Trạng thái |
|---|-----------|----------|------------|
| 7.1 | Kiểm tra tổng thể | Chạy qua: đăng nhập Admin, đổi ngôn ngữ, mở các trang có tab/menu đã sửa; gọi API với cb-lang: vi-VN; xem giao thức/danh mục. Ghi lại lỗi hoặc chỗ còn tiếng Trung. | ☐ |
| 7.2 | Cập nhật tài liệu | Đảm bảo DA-NGON-NGU-TIENG-VIET.md và HUONG-DAN-CAI-DAT-CRMEB.md phản ánh đúng (patch SQL, bước chọn ngôn ngữ, v.v.). Thêm mục "Tiếng Việt" trong README nếu cần. | ☐ |
| 7.3 | Ghi chú bảo trì | Ghi rõ: khi thêm menu/tab mới cần thêm key i18n; khi thêm config tab mới cần nhập title tiếng Việt trong Admin hoặc patch. | ☐ |

---

## Thứ tự thực hiện đề xuất

1. **Giai đoạn 1** (API) – nhanh, không đụng DB/frontend nhiều.  
2. **Giai đoạn 2** (tên ngôn ngữ) – một vài phút.  
3. **Giai đoạn 3** (Vue i18n vi-vn) – nền tảng cho tab/menu.  
4. **Giai đoạn 4** (tab + menu) – có thể làm 4A (DB) song song với 4B (Vue), sau đó 4C (menu).  
5. **Giai đoạn 5** (dữ liệu mặc định) – có thể làm sau khi Admin cơ bản đã tiếng Việt.  
6. **Giai đoạn 6** (ThinkPHP lang) – khi cần dùng message/route backend bằng tiếng Việt.  
7. **Giai đoạn 7** (kiểm tra & tài liệu) – sau mỗi giai đoạn và cuối dự án.

---

## Theo dõi tiến độ

- Đánh dấu ☐ → ☑ khi hoàn thành từng công việc trong bảng.
- Có thể tách file riêng (vd. KE-HOACH-TIENG-VIET-PROGRESS.md) để ghi note, ngày hoàn thành, người thực hiện.

*Tài liệu tham chiếu: [DA-NGON-NGU-TIENG-VIET.md](DA-NGON-NGU-TIENG-VIET.md).*
