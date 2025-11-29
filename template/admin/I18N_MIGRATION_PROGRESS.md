# Tiến độ chuyển đổi i18n cho Admin

## 📊 Tổng quan

- **Tổng số file cần xử lý**: 699 files (Vue + JS)
- **Đã hoàn thành**: 1 file (systemMenus/index.vue)
- **Đang xử lý**: Các file trong module system

## ✅ Đã hoàn thành

### 1. File systemMenus/index.vue
- ✅ Tạo file i18n cho module system (zh-cn, en, zh-tw, vi)
- ✅ Thay thế tất cả text hardcode bằng `$t()`
- ✅ Cập nhật i18n/index.js để import module system

**Các key i18n đã tạo:**
- `message.systemMenus.ruleStatus`
- `message.systemMenus.pleaseSelect`
- `message.systemMenus.show/hide`
- `message.systemMenus.buttonName`
- `message.systemMenus.query`
- `message.systemMenus.addRule`
- `message.systemMenus.frontendAuth`
- `message.systemMenus.route`
- `message.systemMenus.menu/button/api`
- `message.systemMenus.ruleState`
- `message.systemMenus.remark`
- `message.systemMenus.operation`
- `message.systemMenus.selectAuth`
- `message.systemMenus.addSubMenu`
- `message.systemMenus.edit/delete`
- `message.systemMenus.authList`
- `message.systemMenus.alertTip1/2/3`
- `message.systemMenus.searchKeyword`
- `message.systemMenus.search/reset`
- `message.systemMenus.apiName/requestMethod/apiAddress`
- `message.systemMenus.cancel/confirm`

## 🔄 Đang xử lý

### Các file ưu tiên cần xử lý tiếp theo:

1. **Pages trong system module** (ưu tiên cao)
   - `pages/system/systemUser/index.vue`
   - `pages/system/systemMenus/components/menusFrom.vue`
   - Các file system khác

2. **Pages trong finance module** (ưu tiên cao)
   - `pages/finance/commission/index.vue` (đã phát hiện nhiều text tiếng Trung)

3. **Layout components** (ưu tiên trung bình)
   - `layout/navBars/breadcrumb/search.vue`
   - `layout/navMenu/*.vue`
   - `layout/component/*.vue`

4. **Common components** (ưu tiên thấp)
   - `components/mobilePage/*.vue`

## 📝 Hướng dẫn tiếp tục

### Cách xử lý một file:

1. **Tạo/Update file i18n** cho module tương ứng:
   ```javascript
   // src/i18n/pages/{module}/{lang}.js
   export default {
     moduleName: {
       key1: 'Text tiếng Trung',
       key2: 'Text khác',
     }
   };
   ```

2. **Import vào i18n/index.js**:
   ```javascript
   import pagesModuleZhcn from '@/i18n/pages/{module}/zh-cn.js';
   // ... và thêm vào messages
   ```

3. **Thay thế trong Vue file**:
   - Template: `{{ $t('message.moduleName.key') }}`
   - Attributes: `:label="$t('message.moduleName.key')"`
   - Placeholder: `:placeholder="$t('message.moduleName.key')"`
   - JS: `this.$t('message.moduleName.key')`

### Pattern cần thay thế:

1. **Label trong form-item**:
   ```vue
   <!-- Trước -->
   <el-form-item label="规则状态：">
   
   <!-- Sau -->
   <el-form-item :label="$t('message.systemMenus.ruleStatus')">
   ```

2. **Placeholder**:
   ```vue
   <!-- Trước -->
   <el-input placeholder="请输入">
   
   <!-- Sau -->
   <el-input :placeholder="$t('message.module.key')">
   ```

3. **Button text**:
   ```vue
   <!-- Trước -->
   <el-button>查询</el-button>
   
   <!-- Sau -->
   <el-button>{{ $t('message.module.query') }}</el-button>
   ```

4. **Table column title**:
   ```vue
   <!-- Trước -->
   <vxe-table-column title="按钮名称">
   
   <!-- Sau -->
   <vxe-table-column :title="$t('message.module.buttonName')">
   ```

5. **Text trong template**:
   ```vue
   <!-- Trước -->
   <span>接口名称：{{ item.name }}</span>
   
   <!-- Sau -->
   <span>{{ $t('message.module.apiName') }}{{ item.name }}</span>
   ```

6. **Text trong JS**:
   ```javascript
   // Trước
   this.$message.success('保存成功');
   
   // Sau
   this.$message.success(this.$t('message.module.saveSuccess'));
   ```

## 🎯 Kế hoạch tiếp theo

### Phase 1: Core System Pages (Ưu tiên cao)
- [ ] systemUser
- [ ] systemMenus/components
- [ ] Các page system khác

### Phase 2: Finance Module (Ưu tiên cao)
- [ ] commission/index.vue
- [ ] Các page finance khác

### Phase 3: Layout Components (Ưu tiên trung bình)
- [ ] navBars components
- [ ] navMenu components
- [ ] layout components

### Phase 4: Other Pages (Ưu tiên thấp)
- [ ] Product pages
- [ ] User pages
- [ ] Order pages
- [ ] Other modules

## 📌 Lưu ý

1. **Comment trong code**: Giữ nguyên comment tiếng Trung, chỉ thay text hiển thị cho user
2. **Console log**: Có thể giữ nguyên hoặc dịch tùy nhu cầu
3. **Error messages**: Nên dịch để user hiểu
4. **API response messages**: Thường từ backend, cần kiểm tra xem có cần dịch không

## 🔧 Tools hỗ trợ

Có thể sử dụng script để tự động tìm các pattern:
```bash
# Tìm tất cả label="..."
grep -r 'label="[^"]*[\u4e00-\u9fff]' src/

# Tìm tất cả placeholder="..."
grep -r 'placeholder="[^"]*[\u4e00-\u9fff]' src/

# Tìm text trong template
grep -r '>[\s]*[^<]*[\u4e00-\u9fff]' src/
```

