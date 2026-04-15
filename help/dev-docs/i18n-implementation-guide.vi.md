# Hướng dẫn triển khai i18n CRMEB

## 1. Quy ước key i18n

- Sử dụng key theo namespace, không dùng literal text mới trong code:
  - `common.*`
  - `api.*`
  - `admin.*`
  - `mobile.*`
- Ví dụ:
  - `common.action.confirm`
  - `api.error.invalid_param`
  - `admin.login.username_required`
  - `mobile.login.read_and_agree_first`

## 2. Quy ước theo tầng

- **API/PHP**: ưu tiên `getLang('api.error.invalid_param')`, tránh `fail('参数错误')`.
- **Admin Vue**: dùng `this.$t('admin.xxx')` trong script, `$t('admin.xxx')` trong template.
- **Mobile uni-app**: dùng `this.$t('mobile.xxx')` hoặc `$t('mobile.xxx')`.
- Với code cũ đang dùng key tiếng Trung, cho phép chạy song song trong giai đoạn chuyển đổi nhưng mọi code mới phải dùng key namespace.

## 3. Fallback chuẩn

1. Ưu tiên ngôn ngữ người dùng chọn (`cb-lang` hoặc storage `locale`).
2. Nếu thiếu key, fallback về `zh-CN`.
3. Nếu vẫn thiếu key, hiển thị key gốc để dễ phát hiện trong QA.

## 4. Đồng bộ language package qua CLI

Sử dụng lệnh `php think util lang-package`:

- Export:
  - `php think util lang-package --action=export --out=runtime/lang_packages/lang_20260415_120000.sql`
- Import:
  - `php think util lang-package --action=import --file=runtime/lang_packages/lang_20260415_120000.sql`

Sau import, hệ thống tự clear cache ngôn ngữ qua `LangCodeServices::clearLangCache()`.

## 5. Danh sách kiểm tra khi merge

- Không thêm literal tiếng Trung/Việt/Anh trực tiếp trong component/controller.
- Có key tương ứng ở nguồn ngôn ngữ.
- Kiểm tra ít nhất 2 ngôn ngữ cho các flow chính.
- Kiểm tra fallback khi thiếu key.
