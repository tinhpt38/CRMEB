# Phân tích đa ngôn ngữ liên quan bảng eb_system_config

Tài liệu mô tả cấu trúc, luồng dữ liệu và cách bổ sung tiếng Việt cho **Cài đặt hệ thống** (system config).

---

## 1. Các bảng liên quan

### 1.1. `eb_system_config` (bảng gốc – trong crmeb.sql)

| Cột | Ý nghĩa |
|-----|--------|
| id | PK |
| menu_name | Tên field (key), ví dụ: site_name, store_free_postage |
| type, input_type | Loại form: text, radio, upload, textarea... |
| config_tab_id | Thuộc tab nào (FK → eb_system_config_tab.id) |
| parameter | Rule cho radio/select: `0=>关闭\n1=>开启` |
| value | Giá trị mặc định (JSON string) |
| **info** | **Tên hiển thị** (label) – mặc định tiếng Trung |
| **desc** | **Mô tả** (placeholder/help) – mặc định tiếng Trung |
| sort, status, level, link_id, link_value | Sắp xếp, ẩn/hiện, cấp, liên kết |

- Dữ liệu gốc trong `crmeb.sql`: **info**, **desc**, **parameter** đều là tiếng Trung.
- Form Cài đặt hệ thống (Admin) lấy danh sách config theo `config_tab_id` → mỗi item có `info`, `desc`, `parameter` hiển thị trực tiếp.

### 1.2. `eb_system_config_tab` (bảng gốc – trong crmeb.sql)

| Cột | Ý nghĩa |
|-----|--------|
| id | PK |
| pid | Tab cha (0 = cấp top) |
| **title** | **Tên tab** – mặc định tiếng Trung (vd: 基础配置, 接口设置) |
| eng_title | Mã tiếng Anh dùng trong routing (basics, system_serve...) |
| status, info, icon, type, sort, menus_id | Trạng thái, icon, sắp xếp... |

- Tab cấu hình (sidebar/header) hiển thị **title**.
- API `setting/config/header_basics` trả về cây tab; frontend dùng `label` (= title) cho từng tab.

### 1.3. `eb_system_config_tab_lang` (bảng dịch tab – **không** có trong crmeb.sql)

Được tạo bởi patch: **`crmeb/public/install/patch_system_config_tab_lang_vi.sql`**.

| Cột | Ý nghĩa |
|-----|--------|
| id | PK |
| tab_id | FK → eb_system_config_tab.id |
| lang_type_id | FK → eb_lang_type.id (10 = vi-VN) |
| title | Tên tab theo ngôn ngữ |

- **UNIQUE (tab_id, lang_type_id)** → mỗi tab mỗi ngôn ngữ một dòng.
- Backend đọc title theo `cb-lang` (vi-VN → lang_type_id=10) và ghi đè lên `label` trả cho frontend.

### 1.4. `eb_system_config_lang` (bảng dịch config – **không** có trong crmeb.sql)

Được **sinh bởi script** `scripts/patch_system_config_vi.py` → file **`patch_system_config_lang_vi.sql`** (hiện chưa có sẵn trong thư mục install; cần chạy script để tạo).

| Cột | Ý nghĩa |
|-----|--------|
| id | PK |
| config_id | FK → eb_system_config.id |
| lang_type_id | eb_lang_type.id (10 = vi-VN) |
| info | Tên hiển thị theo ngôn ngữ |
| desc | Mô tả theo ngôn ngữ |
| parameter | Chuỗi option theo ngôn ngữ (radio/select) |

- **UNIQUE (config_id, lang_type_id)**.
- Backend có **SystemConfigLangDao::getByConfigIdsAndLang()** để lấy info/desc/parameter theo lang_type_id, nhưng **chưa được gọi** trong `SystemConfigServices::getConfigForm()` (xem mục 3).

---

## 2. Luồng đa ngôn ngữ hiện tại

### 2.1. Tab (tiêu đề tab cấu hình)

1. Client gọi API **`/setting/config/header_basics`** với header **`cb-lang: vi-VN`** (hoặc param `lang_type_id`).
2. Controller lấy `langTypeId` từ `cb-lang` (vi-VN → tra `eb_lang_type` → id=10).
3. **SystemConfigTabServices::getConfigTab($pid, $langTypeId)**:
   - Lấy danh sách tab từ `eb_system_config_tab`.
   - Nếu `$langTypeId > 0`: **SystemConfigTabLangDao::getTitlesByLangTypeId(10)** → map `tab_id => title`.
   - Ghi đè `label` = title tiếng Việt từ `eb_system_config_tab_lang` nếu có.
4. Kết quả: cây tab với `label` tiếng Việt khi đã chạy **patch_system_config_tab_lang_vi.sql**.

**Kết luận:** Tab đa ngôn ngữ **đã hoạt động** nhờ bảng `eb_system_config_tab_lang` và logic trong `SystemConfigTabServices::getConfigTab()`.

### 2.2. Form cấu hình (info, desc, parameter)

1. Admin mở một tab cấu hình → gọi API lấy form (vd route liên quan **getConfigForm**).
2. **SystemConfigServices::getConfigForm($url, $tabId)**:
   - Lấy `$list = $this->dao->getConfigTabAllList($tabId)` từ **eb_system_config** (cột info, desc, parameter gốc).
   - **Chỉ với tab đơn hàng** (tabId ∈ {113, 114, 115, 116, 117, 119, 120}): với từng item, gọi **get_admin_form_lang('order_config_' . menu_name)** và ghi đè `info` nếu có bản dịch.
   - Các tab khác: **không** gọi `SystemConfigLangDao` → form vẫn dùng **info, desc, parameter** từ bảng gốc **eb_system_config** (tiếng Trung).

3. **get_admin_form_lang($key)** đọc file **`crmeb/app/lang/admin_form.php`** (mảng `zh_cn`, `vi_vn`), chọn key theo header `cb-lang`. Chỉ áp dụng cho một số key cố định (order_config_*, level_*, label_*, group_*...).

**Kết luận:** Đa ngôn ngữ cho **info/desc/parameter** của từng config item:
- **Chưa** dùng bảng `eb_system_config_lang` trong code hiện tại.
- Chỉ một phần (tab đơn hàng) dùng file PHP `admin_form.php`; phần còn lại vẫn là tiếng Trung từ DB.

---

## 3. Code liên quan (đường dẫn chính)

| Thành phần | File | Ghi chú |
|------------|------|--------|
| API header tab | `crmeb/app/adminapi/controller/v1/setting/SystemConfig.php` → `header_basics()` | Truyền langTypeId vào getConfigTab() |
| Đọc title tab theo lang | `crmeb/app/services/system/config/SystemConfigTabServices.php` → `getConfigTab()` | Dùng SystemConfigTabLangDao khi langTypeId > 0 |
| Đọc config list cho form | `crmeb/app/dao/system/config/SystemConfigDao.php` → `getConfigTabAllList()` | Trả về từ eb_system_config (info, desc, parameter gốc) |
| Tạo form cấu hình | `crmeb/app/services/system/config/SystemConfigServices.php` → `getConfigForm()` | Chỉ ghi đè info bằng get_admin_form_lang cho tab 113,114,115,116,117,119,120 |
| Dao bản dịch config | `crmeb/app/dao/system/config/SystemConfigLangDao.php` → `getByConfigIdsAndLang()` | **Chưa được gọi** từ getConfigForm |
| Model bản dịch config | Tham chiếu **SystemConfigLang** trong Dao | **Model SystemConfigLang chưa tồn tại** trong thư mục model (chỉ có SystemConfigTabLang) |
| Đa ngôn ngữ form (PHP) | `crmeb/app/common.php` → `get_admin_form_lang()` | Đọc `app/lang/admin_form.php` theo cb-lang |

---

## 4. Patch / script hiện có

| File / Script | Nội dung |
|---------------|----------|
| **patch_system_config_tab_lang_vi.sql** | CREATE TABLE `eb_system_config_tab_lang` + INSERT 73 tab tiếng Việt (lang_type_id=10). **Đã có** trong `crmeb/public/install/`. |
| **patch_system_config_tab_vi.sql** | Cách cũ: UPDATE trực tiếp `eb_system_config_tab.title` sang tiếng Việt. Khuyến nghị dùng tab_lang thay vì UPDATE. |
| **patch_system_config_vi.py** | Script Python: đọc `crmeb.sql` → sinh **patch_system_config_tab_lang_vi.sql** và **patch_system_config_lang_vi.sql** (CREATE TABLE eb_system_config_lang + INSERT info/desc/parameter tiếng Việt). File **patch_system_config_lang_vi.sql** do script tạo ra, không commit sẵn. |
| **patch_system_config_lang_vi.sql** | Không nằm trong thư mục install; cần chạy `python3 scripts/patch_system_config_vi.py` (sau khi đã có crmeb.sql trong repo hoặc đường dẫn đúng) để sinh file. |

---

## 5. Kiểm tra database

- Tab tiếng Việt đã có chưa:
  ```sql
  SELECT COUNT(*) FROM eb_system_config_tab_lang WHERE lang_type_id = 10;
  ```
  Kỳ vọng ≥ 73 nếu đã chạy patch_system_config_tab_lang_vi.sql.

- Bảng dịch config (nếu đã chạy patch_system_config_lang_vi.sql):
  ```sql
  SHOW TABLES LIKE 'eb_system_config_lang';
  SELECT COUNT(*) FROM eb_system_config_lang WHERE lang_type_id = 10;
  ```

---

## 6. Khuyến nghị để hoàn thiện đa ngôn ngữ cho form cấu hình

1. **Tạo model SystemConfigLang** (tương tự SystemConfigTabLang), map bảng `eb_system_config_lang`.
2. **Chạy script** `patch_system_config_vi.py` để sinh **patch_system_config_lang_vi.sql** (và cập nhật từ điển zh→vi nếu cần, theo báo cáo trong `system_config_translate_report.json`).
3. **Chạy patch_system_config_lang_vi.sql** trên DB (sau khi import crmeb.sql).
4. **Sửa SystemConfigServices::getConfigForm()**:
   - Lấy danh sách config như hiện tại.
   - Lấy `langTypeId` từ request (header cb-lang hoặc param), tương tự `header_basics`.
   - Nếu `langTypeId > 0`: gọi **SystemConfigLangDao::getByConfigIdsAndLang(configIds, langTypeId)** và ghi đè `info`, `desc`, `parameter` cho từng item có trong kết quả.
   - Giữ nguyên logic dùng `get_admin_form_lang` cho tab đơn hàng nếu muốn (hoặc chuyển hẳn sang eb_system_config_lang khi đã có đủ dữ liệu).

Sau các bước trên, form Cài đặt hệ thống sẽ hiển thị nhãn/mô tả/option theo ngôn ngữ (vi-VN) khi client gửi `cb-lang: vi-VN`.
