# Cơ chế đa ngôn ngữ và phương án tiếng Việt cho CRMEB

Tài liệu mô tả cơ chế đa ngôn ngữ trong hệ thống CRMEB (cơ sở dữ liệu `crmeb.sql`) và các bước để sử dụng / chuyển sang tiếng Việt.

---

## 1. Cơ chế đa ngôn ngữ trong CRMEB

### 1.1 Ba bảng chính trong database

| Bảng | Mục đích |
|------|----------|
| **eb_lang_type** | Định nghĩa các ngôn ngữ hệ thống hỗ trợ (tên hiển thị, mã file như `zh-CN`, `vi-VN`). |
| **eb_lang_country** | Ánh xạ mã ngôn ngữ trình duyệt (ví dụ `vi-VN`) với `type_id` trong `eb_lang_type`. |
| **eb_lang_code** | Lưu bản dịch: mỗi dòng = một ngôn ngữ (`type_id`) cho một mã thông báo (`code`). Cột `lang_explain` là nội dung hiển thị. |

**Luồng hoạt động:**

1. Client gửi header **`cb-lang`** (ví dụ: `vi-VN`) hoặc hệ thống dùng ngôn ngữ mặc định.
2. Backend tra `eb_lang_country` → lấy `type_id` tương ứng (ví dụ `10` cho `vi-VN`).
3. Hàm **`getLang($code)`** (trong `crmeb/app/common.php`) lấy bản dịch từ `eb_lang_code` theo `type_id` và `code`, trả về `lang_explain`.
4. API / backend dùng `getLang('100000')` thay vì chuỗi cố định → người dùng nhận đúng ngôn ngữ đã chọn.

### 1.2 Ngôn ngữ mặc định trong SQL

Trong **`crmeb.sql`** đã có sẵn:

- **eb_lang_type:** 10 ngôn ngữ, trong đó có **Tiếng Việt** (`id = 10`, `file_name = 'vi-VN'`, tên hiện tại: `ViệtName` – nên sửa thành `Tiếng Việt`).
- **eb_lang_country:** Đã có bản ghi `(341, 10, 'vi-VN', '越南语(越南) ')` → `vi-VN` đã trỏ đúng `type_id = 10`.
- **eb_lang_code:** Đã có **rất nhiều** dòng `type_id = 10` với `lang_explain` bằng tiếng Việt (ví dụ: "Lưu thành công", "Xóa thành công", …).

**Kết luận:** Phần thông báo API/backend (qua `getLang()`) **đã hỗ trợ tiếng Việt** trong database. Chỉ cần client gửi `cb-lang: vi-VN` hoặc đặt tiếng Việt làm ngôn ngữ mặc định.

---

## 2. Những phần vẫn là tiếng Trung / chưa phải tiếng Việt

### 2.1 Comment trong file SQL

- Các **COMMENT** trong `crmeb.sql` (ví dụ: `COMMENT '自增ID'`, `COMMENT '等级名称'`) chỉ dùng cho mô tả cấu trúc trong DB, **không** ảnh hưởng giao diện người dùng. Có thể giữ nguyên hoặc dịch sang tiếng Việt trong bản patch SQL riêng nếu muốn.

### 2.2 Dữ liệu mặc định trong các bảng nghiệp vụ

Một số bảng chứa **nội dung hiển thị mặc định** bằng tiếng Trung (hoặc tiếng Anh), **không** đi qua `eb_lang_code`:

- **eb_agreement:** `title`, `content` (ví dụ: "付费会员协议", "用户协议", "隐私协议", …).
- **eb_agent_level:** `name` (ví dụ: "一级分销", "二级分销", …).
- **eb_category:** `name` (ví dụ: "客户性别", "客户来源", …).
- Các bảng khác có cột `name` / `title` / `content` chứa chuỗi mặc định khi cài đặt.

Đây là **dữ liệu nội dung** khi cài đặt, không phải cơ chế đa ngôn ngữ động. Để hiển thị tiếng Việt có hai hướng:

- **Cách 1:** Sau khi cài đặt, vào **Admin** và sửa tay nội dung (giao thức, phân loại, …) sang tiếng Việt.
- **Cách 2:** Tạo file **patch SQL** (hoặc script) chạy sau khi import `crmeb.sql`, dùng `UPDATE` để thay thế các chuỗi mặc định bằng bản tiếng Việt.

### 2.3 Giao diện Admin (template/admin)

- Admin dùng **Vue I18n** với các file trong `template/admin/src/i18n/`:
  - `lang/`: `zh-cn.js`, `en.js`, `zh-tw.js` (chưa có `vi-vn.js`).
  - `pages/`: `home/`, `login/` cũng chỉ có `zh-cn`, `en`, `zh-tw`.
- File `template/admin/src/i18n/index.js` chỉ đăng ký 3 locale: `zh-cn`, `en`, `zh-tw`. Chưa có **vi** hoặc **vi-vn**.

Để Admin hiển thị tiếng Việt cần **thêm locale tiếng Việt** (xem mục 3.2).

### 2.4 Cấu hình backend ThinkPHP (config/lang.php)

- `crmeb/config/lang.php` chỉ khai báo `zh_cn` và `en_us` trong `extend_list`, **chưa** có `vi_vn`.
- Thư mục `crmeb/app/lang/` có `zh_cn.php`, `en_us.php`, **chưa** có `vi_vn.php`.

Phần này dùng cho **ThinkPHP multi-language** (route/lang, v.v.). Nếu bạn chỉ dùng đa ngôn ngữ qua `eb_lang_*` và `getLang()`, có thể chưa cần thêm; nếu muốn toàn bộ backend nhất quán tiếng Việt thì thêm `vi_vn` (mục 3.3).

### 2.5 Nội dung tab / menu: load từ database, không qua Vue i18n

Một số nội dung hiển thị trên giao diện Admin (đặc biệt **tab**, **menu**) **lấy từ database hoặc hardcode trong Vue**, **không** dùng Vue i18n (`$t()`). Khi chuyển sang tiếng Việt cần xử lý riêng.

#### Tab lấy từ database

- **Trang “系统设置” (Cài đặt hệ thống)** – `template/admin/src/pages/setting/setSystem/index.vue`:
  - Gọi API **`setting/config/header_basics`** → backend trả về **`config_tab`** từ bảng **`eb_system_config_tab`** (cột **`title`** → gán vào `label` của tab).
  - Template dùng `item.label` trực tiếp, **không** gọi `$t(item.label)`.
  - Kết quả: nhãn tab (ví dụ: "基础配置", "公众号配置(H5)", "分销配置", "接口设置", …) là **chuỗi tiếng Trung lưu trong DB**, không đổi theo locale Vue i18n.
- **Bảng `eb_system_config_tab`:** chứa các dòng như `(1, 129, '基础配置', 'basics', ...)`, `(65, 0, '接口设置', 'system_serve', ...)` – toàn bộ `title` mặc định là tiếng Trung.

#### Tab hardcode trong Vue (không i18n)

Các trang sau có **danh sách tab** khai báo **trong component** bằng chuỗi tiếng Trung, **không** dùng `$t()`:

| Trang / file | Nội dung tab (ví dụ) |
|--------------|----------------------|
| `setting/agreement/index.vue` | `headerList`: 付费会员协议, 代理商协议, 隐私协议, 用户协议, 注销协议, 积分协议, 分销协议 |
| `setting/notification/index.vue` | `headerList`: 会员通知, … |
| `setting/storage/index.vue` | `headerList`: 储存配置, … |
| `system/backendRouting/index.vue` | `el-tab-pane` cố định: 管理端接口, 用户端接口, 客服端接口, 对外接口 |
| `system/crontab/index.vue` | `headerList`: 系统任务, … |
| `system/event/index.vue` | `headerList`: 系统任务, … |
| `system/codeGeneration/index.vue` | `headerList`: 基础信息, 字段配置, 存放位置 |

Đây là **chuỗi cố định trong mã Vue**, không load từ API và cũng không qua i18n → đổi ngôn ngữ chỉ bằng cách **sửa code** (thay bằng `$t('key')` và thêm key vào file i18n) hoặc (với setSystem) **sửa dữ liệu trong DB**.

#### Menu sidebar

- Menu trái (sidebar) lấy **danh sách route/menu từ backend** (eb_system_menus, …). Mỗi mục có thuộc tính **`title`** (hoặc tương đương) là chuỗi tiếng Trung.
- Template dùng **`$t(val.title)`** (`layout/navMenu/subItem.vue`). Nghĩa là: **key** đưa vào i18n chính là chuỗi từ backend (ví dụ "配置分类"). Nếu trong file i18n **không** có key đó thì `$t(val.title)` trả về đúng `val.title` → vẫn hiển thị tiếng Trung. Để menu tiếng Việt cần **hoặc** thêm từng key (chuỗi tiếng Trung) → bản dịch tiếng Việt trong i18n, **hoặc** đổi backend trả về key chuẩn (ví dụ `system.config_tab`) rồi dịch key đó trong i18n.

**Kết luận:** Phần nội dung “tab” và “menu” mà bạn thấy vẫn tiếng Trung là do: (1) **tab Cài đặt hệ thống** lấy từ **database** (`eb_system_config_tab.title`), (2) **nhiều tab khác** **hardcode** trong Vue, (3) **menu** dùng `$t(title)` nhưng `title` từ backend và i18n thường chưa có bản dịch cho từng chuỗi đó. Muốn tiếng Việt: sửa DB (config tab), sửa Vue (thay chuỗi bằng `$t('key')` + thêm key vào i18n), và bổ sung i18n cho menu/sidebar.

---

## 3. Phương án làm thành tiếng Việt

### 3.1 API / H5 / App – Đã sẵn sàng tiếng Việt

- **Client (H5, App, …)** gửi header: **`cb-lang: vi-VN`**.
- Hoặc trong **Admin** → **Cài đặt** → **Đa ngôn ngữ** (Lang Type): thêm/kiểm tra ngôn ngữ **Tiếng Việt** (`vi-VN`) và có thể **đặt làm ngôn ngữ mặc định** (is_default = 1). Khi đó nếu client không gửi `cb-lang`, hệ thống vẫn dùng tiếng Việt (nếu cấu hình mặc định trỏ tới vi-VN).
- Không cần sửa `crmeb.sql` cho phần `eb_lang_code` vì bản dịch tiếng Việt đã có sẵn.

**Sửa tên hiển thị tiếng Việt trong eb_lang_type (tùy chọn):**

- Trong SQL hoặc qua Admin, sửa bản ghi `id = 10` trong `eb_lang_type`:  
  `language_name` từ `ViệtName` → **`Tiếng Việt`**.

### 3.2 Thêm tiếng Việt cho giao diện Admin (Vue I18n)

Để menu, nút, nhãn trong **template Admin** hiển thị tiếng Việt:

1. **Tạo file ngôn ngữ tiếng Việt** (copy từ `zh-cn.js` rồi dịch):
   - `template/admin/src/i18n/lang/vi-vn.js`
   - `template/admin/src/i18n/pages/home/vi-vn.js`
   - `template/admin/src/i18n/pages/login/vi-vn.js`
2. **Đăng ký locale** trong `template/admin/src/i18n/index.js`:
   - Import: `import nextViVn from '@/i18n/lang/vi-vn.js';` và tương tự cho `pages/home/vi-vn.js`, `pages/login/vi-vn.js`.
   - Thêm vào `messages`: key `'vi-vn'` (hoặc `'vi-VN'`) với cấu trúc tương tự `zh-cn` (element locale có thể dùng `zh-CN` tạm hoặc thêm element locale tiếng Việt nếu có).
3. **Cấu hình chọn ngôn ngữ:** Trong phần cài đặt theme/ngôn ngữ (thường lưu trong store, ví dụ `themeConfig.globalI18n`), thêm lựa chọn **Tiếng Việt** với giá trị `vi-vn` (hoặc `vi-VN` tùy cách bạn chuẩn hóa).

Sau khi build lại admin, người dùng có thể chọn Tiếng Việt và toàn bộ chữ trong Admin (phần đã được dịch trong các file trên) sẽ hiển thị tiếng Việt.

**Tab và menu (2.5):** Để tab/menu Admin hiển thị tiếng Việt:

- **Tab “Cài đặt hệ thống” (từ DB):** Sửa cột **`title`** trong bảng **`eb_system_config_tab`** sang tiếng Việt (qua Admin → Cấu hình → Cấu hình phân loại, hoặc patch SQL). Ví dụ: "基础配置" → "Cấu hình cơ bản", "接口设置" → "Cài đặt giao diện".
- **Tab hardcode trong Vue:** Trong từng file (agreement, notification, backendRouting, storage, crontab, event, codeGeneration), thay `label`/`headerList[].label` bằng key i18n, ví dụ `$t('setting.agreement.vip')`, rồi thêm key tương ứng vào file `i18n/lang/vi-vn.js` (và zh-cn.js nếu cần).
- **Menu sidebar:** Backend trả về `title` là chuỗi tiếng Trung; template dùng `$t(val.title)`. Cần thêm vào file i18n từng cặp key = chuỗi tiếng Trung, value = bản dịch tiếng Việt (ví dụ `'配置分类': 'Phân loại cấu hình'`), hoặc sửa backend trả về key chuẩn (ví dụ `system.config_tab`) rồi dịch key đó trong i18n.

### 3.3 Backend ThinkPHP (tùy chọn)

Nếu muốn mặc định backend dùng tiếng Việt theo ThinkPHP:

1. **Thêm file ngôn ngữ:**  
   `crmeb/app/lang/vi_vn.php` (copy từ `zh_cn.php` hoặc `en_us.php`, dịch value sang tiếng Việt).
2. **Sửa** `crmeb/config/lang.php`:
   - `default_lang` → `'vi-vn'` (hoặc `'vi_vn'` tùy quy ước).
   - `allow_lang_list` thêm `'vi-vn'` (hoặc `'vi_vn'`).
   - `extend_list` thêm: `'vi_vn' => app()->getBasePath() . 'lang/vi_vn.php'`.
   - `accept_language` thêm: `'vi-vn' => 'vi_vn'` (và biến thể nếu cần).

### 3.4 Dữ liệu mặc định (giao thức, danh mục, …) bằng tiếng Việt

- **Cách nhanh:** Sau khi cài đặt, vào Admin và sửa tay nội dung các mục: Giao thức (agreement), Phân loại (category), Cấp độ đại lý (agent_level), …
- **Cách có thể tái sử dụng:** Viết file **patch SQL** (ví dụ `crmeb_vn_default_data.sql`) chạy sau `crmeb.sql`, dùng `UPDATE` cho các bảng như `eb_agreement`, `eb_agent_level`, `eb_category` để gán `title`/`name`/`content` tiếng Việt. Có thể đặt tên file rõ ràng trong thư mục `crmeb/public/install/` hoặc trong `readme` để người cài đặt chạy thủ công.

### 3.5 Comment trong crmeb.sql

- Comment trong SQL **không** ảnh hưởng giao diện. Nếu muốn toàn bộ file SQL “sạch” tiếng Trung, có thể tạo bản patch chỉ gồm các `ALTER TABLE ... COMMENT '...'` dịch sang tiếng Việt (hoặc tiếng Anh) và chạy sau khi import. Việc này hoàn toàn tùy chọn.

---

## 4. Tóm tắt

| Thành phần | Trạng thái trong crmeb.sql / code | Hành động đề xuất |
|------------|-----------------------------------|--------------------|
| **API / getLang()** | Đã có tiếng Việt (type_id=10 trong eb_lang_code) | Gửi header `cb-lang: vi-VN` hoặc đặt mặc định tiếng Việt trong Lang Type. |
| **eb_lang_type** | Đã có bản ghi vi-VN (id=10), tên "ViệtName" | Tùy chọn: sửa thành "Tiếng Việt". |
| **Giao diện Admin** | Chỉ có zh-cn, en, zh-tw | Thêm locale vi-vn (file lang + pages + đăng ký trong i18n/index.js). |
| **Config/lang.php** | Chỉ zh_cn, en_us | Tùy chọn: thêm vi_vn và file lang/vi_vn.php nếu dùng ThinkPHP lang. |
| **Dữ liệu mặc định (agreement, category, …)** | Chuỗi tiếng Trung trong bảng | Sửa tay trong Admin hoặc dùng patch SQL tiếng Việt. |
| **Tab / menu Admin** | Tab “Cài đặt hệ thống” từ DB (`eb_system_config_tab.title`); nhiều tab khác hardcode trong Vue; menu dùng `$t(title)` nhưng title từ backend | Tab từ DB: sửa `eb_system_config_tab.title` (Admin hoặc patch SQL). Tab hardcode: thay bằng `$t('key')` và thêm key vào i18n. Menu: thêm bản dịch cho từng title trong i18n hoặc đổi backend trả key chuẩn. |
| **Comment trong SQL** | Tiếng Trung | Tùy chọn: patch ALTER TABLE COMMENT. |

Cơ chế chính để “làm thành tiếng Việt” cho **phần do database điều khiển** là: **dùng sẵn type_id = 10 (vi-VN)** trong `eb_lang_code` và cấu hình client / mặc định ngôn ngữ là **vi-VN**. Các phần còn lại (Admin UI, config ThinkPHP, dữ liệu mặc định, comment SQL) xử lý theo từng bước trên tùy nhu cầu.
