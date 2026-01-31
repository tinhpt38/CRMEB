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
| 1.1 | Kiểm tra header `cb-lang` | Client (uni-app) đã gửi `Cb-lang` = `uni.getStorageSync('locale')` trong `template/uni-app/utils/request.js`. Người dùng chọn tiếng Việt tại Trang cá nhân → locale = vi-VN → mọi request sau có header vi-VN. | ☑ |
| 1.2 | Đặt mặc định ngôn ngữ (nếu cần) | Admin đặt Tiếng Việt (id=10) is_default=1; uni-app khi chưa có locale gọi `get_default_lang_type` và set locale theo backend (đã thêm trong App.vue + api/user.js getDefaultLangType). | ☑ |
| 1.3 | Kiểm thử API | Gọi API với header `cb-lang: vi-VN` (chọn Tiếng Việt trong app), xác nhận response `msg` tiếng Việt (vd: "Lưu thành công"). Backend: `eb_lang_country` có (341, 10, 'vi-VN'), `eb_lang_code` có type_id=10. | ☑ |

**Kết quả:** API trả về thông báo tiếng Việt khi client gửi `cb-lang: vi-VN`. Chi tiết: [HUONG-DAN-TIENG-VIET-ADMIN.md](HUONG-DAN-TIENG-VIET-ADMIN.md) mục 1b.

---

## Giai đoạn 2: Cấu hình mặc định & tên ngôn ngữ

**Mục tiêu:** Tên hiển thị "Tiếng Việt" đúng và cấu hình nhất quán.

| # | Công việc | Chi tiết | Trạng thái |
|---|-----------|----------|------------|
| 2.1 | Sửa tên ngôn ngữ trong DB | Trong `eb_lang_type`, sửa bản ghi id = 10: `language_name` từ `ViệtName` → **`Tiếng Việt`**. Chạy file **`crmeb/public/install/patch_lang_type_vi.sql`** sau khi import crmeb.sql, hoặc qua Admin. | ☐ |
| 2.2 | (Tùy chọn) Cấu hình .env | Nếu dùng ThinkPHP lang: trong `.env` đặt `[LANG] default_lang = vi-vn` (sau khi hoàn thành giai đoạn 6). | ☐ |

**Kết quả:** Trong danh sách ngôn ngữ hiển thị "Tiếng Việt" thay vì "ViệtName".

---

## Giai đoạn 3: Giao diện Admin – Vue i18n (locale vi-vn)

**Mục tiêu:** Admin có thể chọn ngôn ngữ Tiếng Việt và hiển thị phần đã dịch.

| # | Công việc | Chi tiết | Trạng thái |
|---|-----------|----------|------------|
| 3.1 | Tạo file i18n tiếng Việt – framework | Tạo `template/admin/src/i18n/lang/vi-vn.js`. Copy từ `zh-cn.js`, dịch toàn bộ value sang tiếng Việt (router, user, staticRoutes, …). | ☑ |
| 3.2 | Tạo file i18n tiếng Việt – pages | Tạo `template/admin/src/i18n/pages/home/vi-vn.js` và `template/admin/src/i18n/pages/login/vi-vn.js`. Copy từ zh-cn rồi dịch. | ☑ |
| 3.3 | Đăng ký locale vi-vn | Trong `template/admin/src/i18n/index.js`: import các file vi-vn, thêm key `'vi-vn'` vào `messages`, cấu trúc giống `zh-cn`. Element UI: dùng tạm `enLocale`. | ☑ |
| 3.4 | Thêm lựa chọn ngôn ngữ trong Admin | Trong drawer cấu hình (setings.vue): thêm dropdown "语言切换" với option Tiếng Việt (vi-vn). user.vue: thêm case `vi-vn` trong initI18n. | ☑ |
| 3.5 | Build & kiểm thử | Chạy build admin, mở Cài đặt (icon bánh răng) → chọn "Tiếng Việt" trong "语言切换", kiểm tra menu/trang home đã dịch. | ☐ |

**Kết quả:** Admin có locale vi-vn; người dùng chọn Tiếng Việt thì phần đã có trong vi-vn.js hiển thị tiếng Việt.

---

## Giai đoạn 4: Tab & menu Admin (DB + hardcode)

**Mục tiêu:** Tab và menu sidebar hiển thị tiếng Việt.

### 4A – Tab lấy từ database (eb_system_config_tab)

| # | Công việc | Chi tiết | Trạng thái |
|---|-----------|----------|------------|
| 4.1 | Chuẩn bị dữ liệu tiếng Việt cho config tab | Liệt kê các `id` và `title` hiện tại trong `eb_system_config_tab`. Soạn bản dịch tiếng Việt (vd: 基础配置 → Cấu hình cơ bản, 接口设置 → Cài đặt giao diện). | ☑ |
| 4.2 | Cập nhật DB | File patch: **`crmeb/public/install/patch_system_config_tab_vi.sql`**. Chạy sau khi cài (MySQL): `mysql -u user -p database < patch_system_config_tab_vi.sql` hoặc qua Admin/phpMyAdmin. | ☑ |
| 4.3 | Kiểm thử trang Cài đặt hệ thống | Mở trang Cài đặt hệ thống, xác nhận tab hiển thị tiếng Việt (sau khi chạy patch). | ☐ |

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
| 4.8 | Thu thập title menu từ backend | Xem API/response trả về menu với `title` (chuỗi tiếng Trung). Liệt kê các giá trị title thường gặp. | ☑ |
| 4.9 | Thêm bản dịch menu vào i18n | Trong `vi-vn.js`: export `menuTitlesViVn` (map tiếng Trung → tiếng Việt). Trong `i18n/index.js`: merge `...menuTitlesViVn` vào locale `vi-vn` để `$t(val.title)` hiển thị tiếng Việt. | ☑ |
| 4.10 | Kiểm thử menu | Đổi locale sang vi-vn, reload, kiểm tra menu trái hiển thị tiếng Việt. | ☐ |

**Kết quả:** Tab (Cài đặt hệ thống + các trang hardcode) và menu sidebar hiển thị tiếng Việt khi chọn locale vi-vn (và với tab từ DB đã cập nhật title).

---

## Giai đoạn 5: Dữ liệu mặc định (giao thức, danh mục, …)

**Mục tiêu:** Nội dung mặc định (giao thức, danh mục, cấp đại lý, …) hiển thị tiếng Việt sau khi cài đặt.

| # | Công việc | Chi tiết | Trạng thái |
|---|-----------|----------|------------|
| 5.1 | Liệt kê bảng & cột cần dịch | Xác định: eb_agreement (title, content), eb_agent_level (name), eb_category (name). | ☑ |
| 5.2 | Soạn nội dung tiếng Việt | Dịch title cho agreement (8 bản ghi), name cho agent_level (5), category (6). Nội dung dài (agreement.content) sửa sau trong Admin. | ☑ |
| 5.3 | Tạo patch SQL | File **`crmeb/public/install/patch_default_data_vi.sql`**: UPDATE title (eb_agreement), name (eb_agent_level, eb_category). Chạy sau khi import crmeb.sql. | ☑ |
| 5.4 | Kiểm thử | Import crmeb.sql + chạy patch, vào Admin kiểm tra Thỏa thuận, Cấp phân phối, Nhãn/tag khách hàng hiển thị tiếng Việt. | ☐ |

**Kết quả:** Dữ liệu mặc định (agreement title, agent_level name, category name) có bản tiếng Việt. Nội dung chi tiết thỏa thuận (HTML) chỉnh trong Admin → Cài đặt → Thỏa thuận.

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
