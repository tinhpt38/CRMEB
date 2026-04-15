# Tài liệu giao diện API CRMEB

## 📍 Bộ phận đầu vào API
- **Quản lý hậu trường**: `/adminapi/controller/v1/` (yêu cầu quyền quản trị viên)
- **Người dùng giao diện người dùng**: `/api/controller/v1/` (yêu cầu người dùng đăng nhập)
- **Giao diện công cộng**: `/api/controller/publics/` (không cần đăng nhập)

##🔐 Cơ chế xác thực
```php
// JWT Token 认证
'middleware' => [
    AuthTokenMiddleware::class,  // 用户token验证
    AdminAuthTokenMiddleware::class  // 管理员token验证
]
```

## 📋 Ví dụ về giao diện cốt lõi

###Giao diện quản lý người dùng
```
GET  /adminapi/v1/user/list      # 用户列表
POST /adminapi/v1/user/edit      # 编辑用户
GET  /api/v1/user/info          # 获取用户信息
```

###Giao diện quản lý đơn hàng
```
GET  /adminapi/v1/order/list     # 订单列表
POST /api/v1/order/create       # 创建订单
GET  /api/v1/order/detail       # 订单详情
```

## 🏷️ Định dạng phản hồi thống nhất
```json
{
    "status": 200,
    "msg": "success", 
    "data": {...},
    "time": "2024-01-01 10:00:00"
}
```

##⚠️ Thông số mã lỗi
- 200: Thành công
- 400: Lỗi tham số
- 401: Không được phép
- 403: Không đủ quyền
- 500: Lỗi máy chủ

---

> **Mẹo**: Tài liệu này được tạo bởi AI và chỉ mang tính chất tham khảo.
