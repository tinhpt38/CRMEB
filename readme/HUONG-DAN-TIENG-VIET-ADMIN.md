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

## 1b. Giai đoạn 1: API / H5 / App dùng tiếng Việt (getLang)

**Mục tiêu:** Client (H5, App, uni-app) nhận thông báo API bằng tiếng Việt (vd: "Lưu thành công", "Vui lòng đăng nhập") khi gửi header **cb-lang: vi-VN**.

**Cơ chế đã có sẵn:**

1. **Client (uni-app):** `template/uni-app/utils/request.js` tự gắn header **Cb-lang** = `uni.getStorageSync('locale')` cho mọi request khi đã có locale. Người dùng chọn ngôn ngữ tại **Cá nhân → Trang cá nhân** (user_info): gọi `get_lang_type_list` lấy danh sách ngôn ngữ (name, value = file_name như vi-VN), khi đổi → `Cache.set('locale', value)` rồi gọi `get_lang_json` để cập nhật bản dịch phía client.
2. **Backend:** Hàm `getLang($code)` trong `crmeb/app/common.php` đọc header **cb-lang** → tra `eb_lang_country` (code = vi-VN → type_id = 10) → lấy bản dịch từ `eb_lang_code` (type_id = 10, is_admin = 1). Bản cài sẵn đã có bản ghi vi-VN trong `eb_lang_country` và bản dịch tiếng Việt trong `eb_lang_code` (type_id = 10).
3. **Kết quả:** Khi người dùng chọn **Tiếng Việt (vi-VN)** trong app, mọi API sau đó gửi **cb-lang: vi-VN** → response `msg` sẽ là tiếng Việt (vd: getLang('100010') → "Thao tác thành công").

**Việc cần làm / kiểm tra:**

- Trong app (H5/App), vào **Trang cá nhân** → chọn ngôn ngữ **Tiếng Việt** (hoặc mục tương ứng trong cài đặt). Sau đó gọi API (đăng nhập, lưu, xóa, …) và xác nhận response `msg` trả về tiếng Việt.
- (Tùy chọn) Nếu muốn **mặc định** app dùng tiếng Việt cho người dùng mới: trong Admin → Cài đặt → Đa ngôn ngữ đặt Tiếng Việt (vi-VN, id = 10) làm mặc định (is_default = 1). Trong uni-app đã bổ sung: khi chưa có `locale` trong storage, app gọi `get_default_lang_type` và set locale theo `lang_type` trả về (App.vue + api/user.js getDefaultLangType).

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
| **Cài đặt máy in** | 设置 → **打印机设置** | `/setting/ticket` | Tiêu đề cột: Tên máy in, Nền tảng, Tài khoản ứng dụng, Số liên in, Thời gian tạo, Bật in; Nút: Thêm máy in, Thiết kế, Sửa, Xóa; Form: Tên máy in, Chọn nền tảng, Tìm kiếm |
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
   Đã thêm bản dịch tên menu (tiếng Trung → tiếng Việt) trong `vi-vn.js` (export `menuTitlesViVn`) và merge vào locale. Khi chọn Tiếng Việt, menu sidebar sẽ hiển thị tiếng Việt cho các mục đã có trong map (vd: 打印机设置 → Cài đặt máy in, 小票配置 → Cấu hình phiếu). Nếu thêm menu mới từ backend, cần bổ sung key tương ứng vào `menuTitlesViVn`.

5. **Tab / header bảng tiếng Trung:**  
   Các tab menu (vd: 打印机设置) và header bảng (vd: 打印机名称, 平台, 操作) cần chuyển sang i18n: (1) Thêm title vào `menuTitlesViVn` trong `vi-vn.js` để menu trái hiển thị tiếng Việt; (2) Thêm nhóm key cho trang (vd: `pages.setting.ticket`) trong `zh-cn.js` và `vi-vn.js`; (3) Trong file `.vue` thay chuỗi cứng bằng `$t('pages.setting.ticket.xxx')`. Trang **Cài đặt máy in** (`setting/ticket/index.vue`) đã làm mẫu: form label, placeholder, nút, cột bảng đều dùng `$t('pages.setting.ticket.*')`. Các trang khác (đơn hàng, sản phẩm, …) làm tương tự.

---

## 5. Tab “Cài đặt hệ thống” tiếng Việt (từ database)

Các tab trong trang **Cài đặt hệ thống** (vd: Cấu hình cơ bản, Cài đặt giao diện, …) lấy `title` từ bảng `eb_system_config_tab`. Để hiển thị tiếng Việt:

1. Chạy file patch SQL sau khi đã cài CRMEB:
   - File **INSERT (khuyến nghị):** **`patch_system_config_tab_lang_vi.sql`** — tạo bảng `eb_system_config_tab_lang`, INSERT bản dịch (lang_type_id=10). PREFIX/DATABASE theo `crmeb/.env`.
   - Ví dụ: `mysql -u root -p -h 127.0.0.1 -P 8889 crmeb < crmeb/public/install/patch_system_config_tab_lang_vi.sql`
2. Admin gửi header **cb-lang: vi-VN** khi gọi header_basics (đã cấu hình trong api/setting.js). Reload trang Cài đặt hệ thống với locale Tiếng Việt; các tab hiển thị tiếng Việt.

*Cách cũ (UPDATE): `patch_system_config_tab_vi.sql` — không khuyến nghị.*

---

## 5b. Dữ liệu mặc định (thỏa thuận, cấp phân phối, nhãn)

Để tiêu đề thỏa thuận, tên cấp phân phối, tên nhóm phân loại hiển thị tiếng Việt sau khi cài:

- Chạy **`crmeb/public/install/patch_default_data_vi.sql`** (sau khi đã import crmeb.sql).
- Ví dụ: `mysql -u root -p -h 127.0.0.1 -P 8889 crmeb < crmeb/public/install/patch_default_data_vi.sql`
- Patch cập nhật: **eb_agreement** (title), **eb_agent_level** (name), **eb_category** (name). Nội dung chi tiết thỏa thuận (HTML) chỉnh trong Admin → Cài đặt → Thỏa thuận.

---

## 6. Đổi mặc định sang tiếng Trung hoặc tiếng Việt

- **Mặc định tiếng Trung:** `themeConfig.js` → `globalI18n: 'zh-cn'`; backend `.env` → `default_lang = zh-cn`; DB: `UPDATE eb_lang_type SET is_default=0; UPDATE eb_lang_type SET is_default=1 WHERE id=1;`
- **Mặc định tiếng Việt:** `globalI18n: 'vi-vn'`; `.env` → `default_lang = vi-vn`; DB: is_default=1 cho id=10 (vi-VN).
- Sau đổi: build lại admin, (tùy chọn) xóa `themeConfigPrev` trong Local Storage.

---

*Tài liệu tham chiếu: [DA-NGON-NGU-TIENG-VIET.md](DA-NGON-NGU-TIENG-VIET.md), [KE-HOACH-TIENG-VIET.md](KE-HOACH-TIENG-VIET.md).*
*Patch SQL: `patch_lang_default_en.sql`, `patch_system_config_tab_lang_vi.sql`, `patch_lang_type_vi.sql`, **`patch_default_data_vi.sql`** (tiêu đề thỏa thuận, tên cấp phân phối, tên nhóm phân loại). Database/PREFIX theo `crmeb/.env`.*

**Kiểm tra đã có bản dịch trong database chưa:** Xem [KIEM-TRA-BAN-DICH-DATABASE.md](KIEM-TRA-BAN-DICH-DATABASE.md) — các câu SQL để kiểm tra `eb_lang_type`, `eb_system_config_tab_lang`, `eb_lang_code` và dữ liệu mặc định (agreement, agent_level, category).

**Quy tắc đa ngôn ngữ (không hardcode):** Xem [QUY-TAC-I18N.md](QUY-TAC-I18N.md) — nguyên tắc, cấu trúc key, cách thêm bản dịch và checklist khi sửa/thêm trang. Mục tiêu: toàn bộ mã nguồn Admin đa ngôn ngữ, không chuỗi hiển thị hardcode.
