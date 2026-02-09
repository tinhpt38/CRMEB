# Báo cáo phân tích ngôn ngữ tiếng Việt

*Sinh từ phân tích script và patch (cập nhật khi chạy lại).*

---

## 1. Đã thực hiện

### 1.1 Patch system config (đa ngôn ngữ – INSERT)

- **Script:** `python3 scripts/patch_system_config_vi.py`
- **Kết quả:**
  - `crmeb/public/install/patch_system_config_tab_lang_vi.sql` — **73 tab** (INSERT vào `eb_system_config_tab_lang`, lang_type_id=10).
  - `crmeb/public/install/patch_system_config_lang_vi.sql` — **248 dòng** (CREATE TABLE `eb_system_config_lang` + INSERT info/desc/parameter tiếng Việt).
- **Cơ chế:** Không UPDATE bảng gốc; backend đọc bản dịch theo header `cb-lang` (vi-VN → type_id=10).

### 1.2 Bản dịch eb_system_config (lang)

- Nhiều trường **info** đã dịch (Tên website, Địa chỉ website, LOGO lớn quản trị, Số điện thoại liên hệ, …).
- Một số **desc** và **parameter** vẫn lẫn tiếng Trung do từ điển trong script chưa đủ cụm dài.

---

## 2. Chuỗi còn tiếng Trung (cần bổ sung từ điển)

File: `scripts/system_config_translate_report.json`

- **config_missing_vi:** Danh sách (id, field, zh) — các cấu hình có `info` hoặc `desc` vẫn là tiếng Trung sau khi dịch tự động.
- **tab_missing_vi:** Tab có title vẫn là tiếng Trung (nếu có).

Ví dụ chuỗi còn zh (để thêm vào `CONFIG_ZH_TO_VI` trong `patch_system_config_vi.py`):

| id  | field | Chuỗi tiếng Trung (zh) |
|-----|-------|-------------------------|
| 77  | desc  | 商品库存数量低于多少时，提示库存不足 |
| 173 | desc  | 七牛云accessKey |
| 194 | info  | 商品付费会员价 |
| 195 | info  | 强制手机号登录 |
| 198 | info  | 签到赠送经验 |
| 246 | info  | 发票功能启用 |
| 299 | info/desc | 快递公司 |
| 300 | info/desc | 快递模板 |
| 304 | info  | 打印机编号 |
| 305 | info  | 客服反馈 |
| …   | …     | (xem full trong report JSON) |

**Cách bổ sung:** Thêm cặp zh → vi vào dict `CONFIG_ZH_TO_VI` trong `scripts/patch_system_config_vi.py`, chạy lại script để tái sinh patch.

---

## 3. Lấy bản dịch từ API / DB (lang_code – tiếng Việt)

- **Script:** `scripts/fetch_lang_code_vi.py`
- **Mục đích:** Lấy toàn bộ bản dịch **eb_lang_code** cho type_id=10 (tiếng Việt), lưu ra JSON để bổ sung chi tiết ngôn ngữ.

**Cách chạy:**

1. **Qua API (cần token Admin):**
   ```bash
   export CRMEB_ADMIN_URL="https://crmeb.local:7890/adminapi"
   export ADMIN_TOKEN="<token>"
   python3 scripts/fetch_lang_code_vi.py
   ```

2. **Đọc từ MySQL (dùng crmeb/.env):**
   ```bash
   pip3 install pymysql   # một lần
   python3 scripts/fetch_lang_code_vi.py --from-db
   ```

**Đầu ra:**

- `scripts/lang_code_vi.json` — map code → lang_explain (tiếng Việt).
- `scripts/lang_code_vi_full.json` — danh sách đầy đủ (id, code, remarks, lang_explain, is_admin).

*(Lưu ý: Trên môi trường hiện tại có thể chưa cài được pymysql; cần cài thủ công hoặc dùng API với token.)*

---

## 4. Cấu hình database (crmeb/.env)

- **PREFIX = eb_** — Patch SQL đang dùng đúng prefix này.
- **DATABASE = crmeb** — Khi chạy patch hoặc fetch từ DB cần trùng tên DB và prefix.

---

## 5. Tóm tắt

| Hạng mục | Trạng thái |
|----------|------------|
| Patch tab + config (INSERT đa ngôn ngữ) | Đã sinh; chạy patch SQL sau khi import crmeb.sql |
| Chuỗi còn tiếng Trung trong config | Ghi trong `system_config_translate_report.json`; bổ sung từ điển rồi chạy lại script |
| Lấy lang_code tiếng Việt (API/DB) | Script sẵn sàng; cần pymysql cho --from-db hoặc token cho API |
