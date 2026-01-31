# Hướng dẫn hiển thị tiếng Việt trên Admin CRMEB

Sau khi đã thêm locale **Tiếng Việt (vi-vn)** và đổi tab/nút sang i18n, Admin có thể hiển thị tiếng Việt. Tài liệu này hướng dẫn cách **bật tiếng Việt** và **tìm đúng trang** để kiểm tra.

---

## 1. Mặc định là tiếng Anh (tránh Google tự dịch)

**Ngôn ngữ mặc định** của Admin và backend đã đặt là **tiếng Anh (en)** để khi chạy trình duyệt **không tự động dịch trang** qua Google.

- Admin: `template/admin/src/store/module/themeConfig.js` → `globalI18n: 'en'`
- Vue i18n: `template/admin/src/i18n/index.js` → `fallbackLocale: 'en'`
- Backend: `crmeb/.env` → `[LANG] default_lang = en-us`; `crmeb/config/lang.php` → default `en-us`
- Database (ngôn ngữ mặc định API/H5): chạy **`patch_lang_default_en.sql`** để đặt `eb_lang_type.is_default = 1` cho English (id=2).

**Muốn mặc định lại tiếng Việt:** Đổi `globalI18n: 'en'` → `'vi-vn'` trong themeConfig.js, build lại Admin; (tùy chọn) đổi `.env` default_lang và chạy patch đặt is_default cho vi-VN (id=10).

---

## 2. Đổi ngôn ngữ thủ công (sang Tiếng Việt hoặc tiếng Trung)

1. Đăng nhập Admin (mặc định hiển thị tiếng Anh).
2. Ở **góc trên bên phải**, bấm **icon bánh răng (⚙ Cài đặt)**.
3. Drawer mở ra → mục **Đổi ngôn ngữ / 语言切换** → chọn **Tiếng Việt** hoặc **中文**.
4. Giao diện (tab, nút đã dùng i18n) chuyển ngay, không cần reload.

**Lưu ý:** Nếu không thấy dropdown ngôn ngữ, kiểm tra đã build admin mới và copy `dist` vào `crmeb/public/admin`.

---

## 3. Các trang cần kiểm tra (tab / nút tiếng Việt)

**Menu trái** có thể vẫn hiển thị **tên tiếng Trung** (vì lấy từ backend). Dùng bảng dưới để tìm đúng trang (theo tên menu tiếng Trung hoặc đường dẫn):

| Bạn muốn xem | Trong menu Admin (có thể vẫn là tiếng Trung) | Đường dẫn gần đúng | Nội dung đã tiếng Việt khi bật vi-vn |
|--------------|----------------------------------------------|--------------------|--------------------------------------|
| **Giao thức** | 设置 → **协议** | `/setting/agreement` | Tab: Thỏa thuận hội viên trả phí, Thỏa thuận đại lý, …; Nút: **Lưu** |
| **Thông báo** | 设置 → **消息通知** / 通知 | `/setting/notification` | Tab: Thông báo hội viên, Thông báo nền tảng, Thông báo tùy chỉnh |
| **Lưu trữ** | 设置 → **储存配置** / 存储 | `/setting/storage` | Tab: Cấu hình lưu trữ, Qiniu Cloud, … |
| **Định tuyến API** | 系统 → **接口管理** / 后端路由 | `/system/backend_routing` hoặc tương tự | Tab: Giao diện quản trị, Giao diện người dùng, …; Nút: **Thêm danh mục**, **Đồng bộ**; Tên mặc định cây: Thư mục mặc định, Tên giao diện mặc định |
| **Định thời (Crontab)** | 系统 → **定时任务** | `/system/crontab` | Tab: Tác vụ hệ thống, Tác vụ tùy chỉnh |
| **Tạo mã (Code gen)** | 系统 → **代码生成** | `/system/code_generation` | Bước: Thông tin cơ bản, Cấu hình trường, Vị trí lưu trữ; Nút: **Bước trước**, **Bước sau**, **Gửi** |
| **Drawer cấu hình** | Bấm **icon bánh răng** góc phải | — | Tiêu đề drawer và placeholder ô chọn: **Vui lòng chọn** (khi đã chọn vi-vn) |

- **Cách tìm nhanh:** Khi đã chọn Tiếng Việt, menu trái đã hiển thị tiếng Việt (Sản phẩm, Đơn hàng, Người dùng, Cài đặt, Bảo trì, …). Vào **Cài đặt** hoặc **Bảo trì** (Hệ thống), mở lần lượt từng mục con; khi vào đúng trang sẽ thấy **tab** và **nút** như cột cuối bảng.

---

## 4. Nếu vẫn thấy tiếng Trung trên các tab/nút

1. **Đã chọn Tiếng Việt chưa?**  
   Bấm icon bánh răng → chọn **Tiếng Việt** trong 「语言切换」 (hoặc xóa `themeConfigPrev` như mục 1).

2. **Đã build và copy đúng chưa?**  
   Build lại `template/admin` và copy toàn bộ `dist` vào `crmeb/public/admin`, sau đó hard refresh (Ctrl+F5) hoặc xóa cache.

3. **Trang có đúng bản code mới không?**  
   Các file đã sửa: `agreement/index.vue`, `notification/index.vue`, `storage/index.vue`, `backendRouting/index.vue`, `crontab/index.vue`, `codeGeneration/index.vue`, `setings.vue`, và các file i18n `lang/zh-cn.js`, `lang/vi-vn.js`. Cần build lại sau khi sửa.

4. **Menu trái tiếng Việt:**  
   Đã thêm bản dịch tên menu (tiếng Trung → tiếng Việt) trong `vi-vn.js` (export `menuTitlesViVn`) và merge vào locale. Khi chọn Tiếng Việt, menu sidebar sẽ hiển thị tiếng Việt cho các mục đã có trong map. Nếu thêm menu mới từ backend, cần bổ sung key tương ứng vào `menuTitlesViVn`.

---

## 5. Tab “Cài đặt hệ thống” tiếng Việt (từ database)

Các tab trong trang **Cài đặt hệ thống** (vd: Cấu hình cơ bản, Cài đặt giao diện, …) lấy `title` từ bảng `eb_system_config_tab`. Để hiển thị tiếng Việt:

1. Chạy file patch SQL sau khi đã cài CRMEB:
   - File **INSERT (khuyến nghị):** **`patch_system_config_tab_lang_vi.sql`** — tạo bảng `eb_system_config_tab_lang`, INSERT bản dịch (lang_type_id=10). PREFIX/DATABASE theo `crmeb/.env`.
   - Ví dụ: `mysql -u root -p -h 127.0.0.1 -P 8889 crmeb < crmeb/public/install/patch_system_config_tab_lang_vi.sql`
2. Admin gửi header **cb-lang: vi-VN** khi gọi header_basics (đã cấu hình trong api/setting.js). Reload trang Cài đặt hệ thống với locale Tiếng Việt; các tab hiển thị tiếng Việt.

*Cách cũ (UPDATE): `patch_system_config_tab_vi.sql` — không khuyến nghị.*

---

## 6. Đổi mặc định sang tiếng Trung hoặc tiếng Việt

- **Mặc định tiếng Trung:** `themeConfig.js` → `globalI18n: 'zh-cn'`; backend `.env` → `default_lang = zh-cn`; DB: `UPDATE eb_lang_type SET is_default=0; UPDATE eb_lang_type SET is_default=1 WHERE id=1;`
- **Mặc định tiếng Việt:** `globalI18n: 'vi-vn'`; `.env` → `default_lang = vi-vn`; DB: is_default=1 cho id=10 (vi-VN).
- Sau đổi: build lại admin, (tùy chọn) xóa `themeConfigPrev` trong Local Storage.

---

*Tài liệu tham chiếu: [DA-NGON-NGU-TIENG-VIET.md](DA-NGON-NGU-TIENG-VIET.md), [KE-HOACH-TIENG-VIET.md](KE-HOACH-TIENG-VIET.md).*
*Patch SQL: `patch_lang_default_en.sql` (mặc định en-US, tránh Google tự dịch), `patch_system_config_tab_lang_vi.sql` (đa ngôn ngữ tab), `patch_lang_type_vi.sql` (tên Tiếng Việt). Database/PREFIX theo `crmeb/.env`.*
