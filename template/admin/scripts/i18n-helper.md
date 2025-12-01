# i18n Helper Tools

## Công cụ quét và dịch tự động

### 1. i18n-scanner.js

Tool tự động quét và tìm các hardcoded Chinese strings trong Vue files.

#### Cài đặt và sử dụng:

```bash
# Quét toàn bộ src/pages
node scripts/i18n-scanner.js

# Quét một module cụ thể
node scripts/i18n-scanner.js --path src/pages/system

# Quét và xuất ra file tùy chỉnh
node scripts/i18n-scanner.js --output custom-report.json

# Xem help
node scripts/i18n-scanner.js --help
```

#### Output:

Tool sẽ tạo 2 files:
1. `i18n-report.json` - Báo cáo chi tiết dạng JSON
2. `i18n-report.csv` - Báo cáo dạng CSV để import vào Excel

#### Format báo cáo:

Mỗi finding bao gồm:
- **File Path**: Đường dẫn file
- **Line**: Số dòng
- **Context**: Loại (label, title, placeholder, button, table-header, modal, form-item, alert)
- **Chinese Text**: Text tiếng Trung cần dịch
- **Suggested Key**: Key gợi ý cho i18n
- **i18n Key**: Key đầy đủ (message.context.key)
- **Replacement**: Code thay thế gợi ý

### 2. Cách sử dụng báo cáo

1. **Xem báo cáo trong console**: Tool sẽ in ra console với format dễ đọc
2. **Import CSV vào Excel**: Mở file CSV để xem và sắp xếp dễ dàng
3. **Sử dụng JSON**: Parse JSON để tự động hóa việc thay thế

### 3. Workflow đề xuất

1. **Quét codebase**:
   ```bash
   node scripts/i18n-scanner.js --path src/pages/system
   ```

2. **Xem báo cáo**: Mở `i18n-report.json` hoặc `i18n-report.csv`

3. **Dịch từng file**:
   - Sắp xếp theo file path
   - Dịch từng text theo context
   - Thêm keys vào file i18n tương ứng

4. **Thay thế trong code**:
   - Sử dụng replacement suggestion từ báo cáo
   - Hoặc tự động hóa với script khác

### 4. Lưu ý

- Tool sẽ bỏ qua các text đã có `$t()` hoặc `:label="$t()"`
- Tool sẽ bỏ qua comment lines
- Cần review kỹ các suggestion trước khi thay thế tự động
- Một số text có thể là dynamic content, cần xử lý thủ công

### 5. Ví dụ output

```
📊 i18n Scanner Report

📁 Total Files Scanned: 15
🔍 Total Findings: 42
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
  Original: <el-button type="primary" v-db-click @click="userSearchs">查询分类</el-button>
  Chinese Text: 查询分类
  Suggestions:
    - Key: 查询分类
      Replace: 查询分类 → $t('message.button.查询分类')
```

### 6. Tích hợp vào package.json

Thêm vào `package.json`:

```json
{
  "scripts": {
    "i18n:scan": "node scripts/i18n-scanner.js",
    "i18n:scan:system": "node scripts/i18n-scanner.js --path src/pages/system",
    "i18n:scan:setting": "node scripts/i18n-scanner.js --path src/pages/setting"
  }
}
```

Sau đó chạy:
```bash
npm run i18n:scan
```

