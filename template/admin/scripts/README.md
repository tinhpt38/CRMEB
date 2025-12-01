# i18n Automation Tools

Bộ công cụ tự động hóa quá trình dịch và i18n hóa cho Vue.js project.

## 📦 Cài đặt

Không cần cài đặt thêm dependencies, chỉ cần Node.js.

## 🚀 Sử dụng

### 1. Quét toàn bộ project

```bash
node scripts/i18n-scanner.js
```

### 2. Quét một module cụ thể

```bash
# Quét module system
node scripts/i18n-scanner.js --path src/pages/system

# Quét module setting
node scripts/i18n-scanner.js --path src/pages/setting

# Quét module order
node scripts/i18n-scanner.js --path src/pages/order
```

### 3. Xuất ra file tùy chỉnh

```bash
node scripts/i18n-scanner.js --output custom-report.json
```

### 4. Sử dụng npm scripts (nếu đã thêm vào package.json)

```bash
npm run i18n:scan
npm run i18n:scan:system
npm run i18n:scan:setting
```

## 📊 Output

Tool sẽ tạo 2 files:

1. **i18n-report.json** - Báo cáo chi tiết dạng JSON
2. **i18n-report.csv** - Báo cáo dạng CSV (dễ import vào Excel)

## 📋 Format báo cáo

Mỗi finding bao gồm:

- **File Path**: Đường dẫn file
- **Line**: Số dòng
- **Context**: Loại (label, title, placeholder, button, table-header, modal, form-item, alert, text)
- **Chinese Text**: Text tiếng Trung cần dịch
- **Suggested Key**: Key gợi ý cho i18n
- **i18n Key**: Key đầy đủ (message.context.key)
- **Replacement**: Code thay thế gợi ý

## 🔍 Ví dụ output

```
📊 i18n Scanner Report

📁 Total Files Scanned: 24
🔍 Total Findings: 336
⏰ Generated At: 2024-01-15T10:30:00.000Z

════════════════════════════════════════════════════════════════════════════════

📄 src/pages/system/configTab/index.vue
────────────────────────────────────────────────────────────────────────────────

  Line 13 [label]:
  Original: <el-form-item label="是否显示：">
  Chinese Text: 是否显示
  Suggestions:
    - Key: 是否显示
      Replace: 是否显示 → $t('message.label.是否显示')

  Line 29 [button]:
  Original: <el-button type="primary">查询分类</el-button>
  Chinese Text: 查询分类
  Suggestions:
    - Key: 查询分类
      Replace: 查询分类 → $t('message.button.查询分类')
```

## 💡 Workflow đề xuất

1. **Quét codebase**:
   ```bash
   node scripts/i18n-scanner.js --path src/pages/system
   ```

2. **Xem báo cáo**: 
   - Mở `i18n-report.json` để xem chi tiết
   - Hoặc mở `i18n-report.csv` trong Excel để sắp xếp và filter

3. **Dịch từng file**:
   - Sắp xếp theo file path
   - Dịch từng text theo context
   - Thêm keys vào file i18n tương ứng (zh-cn.js, en.js, zh-tw.js, vi.js)

4. **Thay thế trong code**:
   - Sử dụng replacement suggestion từ báo cáo
   - Hoặc tự động hóa với script khác

## ⚠️ Lưu ý

- Tool sẽ **bỏ qua** các text đã có `$t()` hoặc `:label="$t()"`
- Tool sẽ **bỏ qua** comment lines
- Cần **review kỹ** các suggestion trước khi thay thế tự động
- Một số text có thể là **dynamic content**, cần xử lý thủ công
- Các text trong **comment** sẽ không được quét (để tránh false positive)

## 🎯 Tips

1. **Quét từng module**: Quét từng module một để dễ quản lý
2. **Sử dụng CSV**: Import CSV vào Excel để filter và sort dễ dàng
3. **Group by file**: Dịch theo từng file để dễ track progress
4. **Review suggestions**: Key suggestions chỉ là gợi ý, cần review và điều chỉnh

## 📝 Thêm vào package.json

Thêm vào `package.json`:

```json
{
  "scripts": {
    "i18n:scan": "node scripts/i18n-scanner.js",
    "i18n:scan:system": "node scripts/i18n-scanner.js --path src/pages/system",
    "i18n:scan:setting": "node scripts/i18n-scanner.js --path src/pages/setting",
    "i18n:scan:order": "node scripts/i18n-scanner.js --path src/pages/order",
    "i18n:scan:product": "node scripts/i18n-scanner.js --path src/pages/product"
  }
}
```

## 🔧 Tùy chỉnh

Có thể chỉnh sửa file `i18n-scanner.js` để:

- Thay đổi pattern tìm kiếm
- Thêm exclude patterns
- Thay đổi format output
- Thêm auto-replace feature

## 📚 Xem thêm

- `i18n-helper.md` - Hướng dẫn chi tiết
- `i18n-scanner.js` - Source code của tool

