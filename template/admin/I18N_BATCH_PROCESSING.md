# Hướng dẫn xử lý hàng loạt i18n cho 693 files còn lại

## 📊 Tiến độ hiện tại

- ✅ **Đã xử lý**: 4 files
  - `pages/system/systemMenus/index.vue`
  - `pages/finance/commission/index.vue`
  - `pages/order/orderList/index.vue`
  - `pages/order/orderList/components/tableFrom.vue`

- ⏳ **Còn lại**: ~693 files

## 🎯 Chiến lược xử lý

### Phase 1: Tạo file i18n cho tất cả module chính (Ưu tiên cao)

Các module cần tạo file i18n:
1. ✅ system (đã có)
2. ✅ finance (đã có)
3. ✅ order (đã có)
4. ⏳ product (29 files)
5. ⏳ user (18 files)
6. ⏳ setting (49 files)
7. ⏳ marketing (58 files)
8. ⏳ kefu (32 files)
9. ⏳ app (14 files)
10. ⏳ notify (9 files)
11. ⏳ statistic (14 files)
12. ⏳ division (5 files)
13. ⏳ cms (4 files)
14. ⏳ agent (3 files)
15. ⏳ account (1 file)
16. ⏳ index (5 files)
17. ⏳ crud (1 file)

### Phase 2: Xử lý các file theo thứ tự ưu tiên

**Ưu tiên cao** (pages trong các module chính):
- product/* (29 files)
- user/* (18 files)
- setting/* (49 files)
- marketing/* (58 files)

**Ưu tiên trung bình** (layout và components):
- layout/* (~30 files)
- components/* (~50 files)

**Ưu tiên thấp** (các file khác):
- Các file còn lại

## 📝 Pattern xử lý

### 1. Template attributes
```vue
<!-- Trước -->
<el-form-item label="订单类型：">
<el-input placeholder="请输入">

<!-- Sau -->
<el-form-item :label="$t('message.module.key')">
<el-input :placeholder="$t('message.module.key')">
```

### 2. Text trong template
```vue
<!-- Trước -->
<span>接口名称：{{ item.name }}</span>

<!-- Sau -->
<span>{{ $t('message.module.apiName') }}{{ item.name }}</span>
```

### 3. Data trong JS
```javascript
// Trước
data() {
  return {
    tabs: [
      { label: '全部订单' },
    ]
  }
}

// Sau
data() {
  return {
    tabs: [
      { label: this.$t('message.module.allOrders') },
    ]
  }
}
```

### 4. Message trong JS
```javascript
// Trước
this.$message.error('请先选择删除的订单！');

// Sau
this.$message.error(this.$t('message.module.pleaseSelectOrder'));
```

## 🔧 Công cụ hỗ trợ

### Script Python đã tạo
File: `i18n_migration_helper.py`
- Tìm tất cả text tiếng Trung trong Vue files
- Phân loại theo type (label, placeholder, title, text)

### Cách sử dụng
```bash
cd template/admin
python3 i18n_migration_helper.py
```

## 📋 Checklist cho mỗi file

- [ ] Đọc file và xác định tất cả text tiếng Trung
- [ ] Tạo/update file i18n cho module tương ứng
- [ ] Thay thế label, placeholder, title trong template
- [ ] Thay thế text trong template
- [ ] Thay thế text trong data() và methods
- [ ] Thay thế message trong JS
- [ ] Import file i18n vào i18n/index.js
- [ ] Test và kiểm tra lỗi

## 🚀 Cách tiếp tục

1. **Tạo file i18n cho module tiếp theo** (ví dụ: product)
2. **Xử lý từng file trong module đó**
3. **Lặp lại cho các module khác**

## ⚠️ Lưu ý

1. **Comment**: Giữ nguyên comment tiếng Trung
2. **Console.log**: Có thể giữ nguyên
3. **Error messages**: Nên dịch để user hiểu
4. **API response**: Thường từ backend, kiểm tra xem có cần dịch không

## 📊 Thống kê theo module

| Module | Số files | Trạng thái |
|--------|----------|------------|
| marketing | 58 | ⏳ Chưa xử lý |
| setting | 49 | ⏳ Chưa xử lý |
| system | 39 | ✅ Đã xử lý 1 file |
| kefu | 32 | ⏳ Chưa xử lý |
| product | 29 | ⏳ Chưa xử lý |
| user | 18 | ⏳ Chưa xử lý |
| order | 16 | ✅ Đã xử lý 2 files |
| statistic | 14 | ⏳ Chưa xử lý |
| app | 14 | ⏳ Chưa xử lý |
| finance | 10 | ✅ Đã xử lý 1 file |
| notify | 9 | ⏳ Chưa xử lý |
| index | 5 | ⏳ Chưa xử lý |
| division | 5 | ⏳ Chưa xử lý |
| cms | 4 | ⏳ Chưa xử lý |
| agent | 3 | ⏳ Chưa xử lý |
| crud | 1 | ⏳ Chưa xử lý |
| account | 1 | ⏳ Chưa xử lý |

