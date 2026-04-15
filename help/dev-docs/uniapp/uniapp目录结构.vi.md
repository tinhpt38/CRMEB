# Tài liệu cấu trúc thư mục uniapp của dự án CRMEB

## 1. Giới thiệu
- **Mục đích tài liệu**: Mô tả chi tiết cấu trúc thư mục uniapp trong dự án CRMEB và chức năng của từng thư mục
- **Phạm vi áp dụng**: Lập trình viên di động, lập trình viên frontend, nhân sự bảo trì dự án
- **Định nghĩa thuật ngữ**: uniapp - framework phát triển ứng dụng đa nền tảng dựa trên Vue.js

## 2. Cấu trúc thư mục

### 2.1 Cây thư mục

```
uni-app/
├── androidPrivacy.json  # Android 隐私政策配置
├── api/                 # API 接口目录
├── App.vue              # 应用入口组件
├── components/          # 公共组件目录
├── config/              # 配置文件目录
├── libs/                # 第三方库目录
├── main.js              # 应用入口文件
├── manifest.json        # 应用配置文件
├── mixins/              # 混入文件目录
├── package-lock.json    # npm 依赖锁定文件
├── package.json         # npm 依赖配置文件
├── pages/               # 页面目录
├── pages.json           # 页面路由配置文件
├── plugin/              # 插件目录
├── static/              # 静态资源目录
├── store/               # 状态管理目录
├── uni.scss             # 全局样式文件
├── utils/               # 工具函数目录
└── vue.config.js        # Vue 配置文件
```

### 2.2 Mô tả chức năng thư mục

#### 2.2.1 Thư mục lõi
- **api/**: Lưu định nghĩa API, bao gồm toàn bộ lời gọi interface backend
- **components/**: Lưu các component dùng chung, có thể tái sử dụng ở nhiều trang
- **pages/**: Lưu các trang của ứng dụng, mỗi trang tương ứng một thư mục con
- **static/**: Lưu tài nguyên tĩnh như hình ảnh, font, biểu tượng
- **utils/**: Lưu các hàm tiện ích như xử lý ngày tháng, đóng gói request

#### 2.2.2 Thư mục cấu hình
- **config/**: Lưu cấu hình ứng dụng như địa chỉ API, định nghĩa hằng số
- **mixins/**: Lưu các file mixin để tái sử dụng logic component
- **store/**: Lưu các file quản lý trạng thái Vuex
- **libs/**: Lưu thư viện bên thứ ba như SDK, thư viện công cụ

#### 2.2.3 Thư mục plugin
- **plugin/**: Lưu plugin ứng dụng như plugin thanh toán, plugin chia sẻ

## 3. Mô tả file lõi

### 3.1 File đầu vào
- **App.vue**: Component gốc của ứng dụng, quản lý cấu hình toàn cục và vòng đời
- **main.js**: File đầu vào ứng dụng, khởi tạo instance Vue và cấu hình toàn cục

### 3.2 File cấu hình
- **manifest.json**: File cấu hình ứng dụng, chứa tên app, phiên bản, quyền truy cập...
- **pages.json**: File cấu hình định tuyến trang, định nghĩa đường dẫn trang, thanh điều hướng...
- **vue.config.js**: File cấu hình dự án Vue, ví dụ cấu hình proxy, cấu hình build...

### 3.3 File kiểu dáng
- **uni.scss**: File style toàn cục, định nghĩa biến chủ đề và style dùng chung

### 3.4 File phụ thuộc
- **package.json**: File cấu hình dependency npm, quản lý dependency dự án
- **package-lock.json**: File khóa dependency npm, đảm bảo phiên bản dependency nhất quán

## 4. Cấu trúc thư mục trang

### 4.1 Cách tổ chức trang
```
pages/
├── index/              # 首页
│   ├── index.vue       # 页面组件
│   └── main.js         # 页面入口（可选）
├── goods/              # 商品相关页面
│   ├── list.vue        # 商品列表
│   └── detail.vue      # 商品详情
└── user/               # 用户相关页面
    ├── index.vue       # 用户中心
    └── login.vue       # 登录页面
```

### 4.2 Mô tả file trang
- **Component trang (.vue)**: Bao gồm template, script và style
- **Đầu vào trang (main.js)**: Cấu hình khởi tạo ở cấp trang (tùy chọn)

## 5. Quy chuẩn phát triển

### 5.1 Quy chuẩn đặt tên
- **Tên thư mục**: Từ viết thường, nhiều từ ngăn cách bằng dấu gạch nối (`-`)
- **Tên file**: Từ viết thường, nhiều từ ngăn cách bằng dấu gạch nối (`-`)
- **Tên component**: Quy ước PascalCase
- **Tên biến**: Quy ước camelCase
- **Tên hằng số**: Viết hoa toàn bộ, nhiều từ ngăn cách bằng dấu gạch dưới (`_`)

### 5.2 Quy chuẩn mã nguồn
- Tuân theo hướng dẫn phong cách chính thức của Vue
- Sử dụng cú pháp ES6+
- Phát triển theo hướng component hóa để tăng khả năng tái sử dụng mã
- Sử dụng Vuex hợp lý để quản lý trạng thái toàn cục
- Logic trang rõ ràng, tránh lồng quá sâu

### 5.3 Tối ưu hiệu năng
- Tối ưu tài nguyên ảnh, dùng kích thước và định dạng phù hợp
- Giảm số lượng HTTP request, sử dụng cache hợp lý
- Lazy-load component để giảm kích thước gói khởi tạo
- Tránh tính toán phức tạp trong template
- Sử dụng hợp lý các API tối ưu hiệu năng do uni-app cung cấp

## 6. Lưu ý

### 6.1 Tương thích đa nền tảng
- Lưu ý sự khác biệt API giữa các nền tảng
- Tránh dùng tính năng chỉ dành riêng cho một nền tảng
- Khi test cần bao phủ các nền tảng chính (iOS, Android, WeChat Mini Program...)

### 6.2 Cấu hình đóng gói
- Cấu hình tham số build phù hợp theo từng nền tảng
- Lưu ý cấu hình quyền truy cập của ứng dụng
- Cấu hình hợp lý biểu tượng app và trang khởi động

### 6.3 Mẹo debug
- Dùng công cụ nhà phát triển uni-app để debug
- Tận dụng `console.log` để in thông tin debug
- Theo dõi cảnh báo và lỗi trên console

## 7. Tổng kết

### 7.1 Đặc điểm cấu trúc thư mục
- **Cấu trúc rõ ràng**: Tuân theo cấu trúc thư mục được uni-app khuyến nghị
- **Tính mô-đun cao**: Tổ chức mã theo mô-đun chức năng
- **Dễ bảo trì**: Trách nhiệm thư mục rõ ràng, cấu trúc mã hợp lý
- **Khả năng mở rộng tốt**: Thuận tiện khi bổ sung tính năng và trang mới

### 7.2 Kế hoạch tiếp theo
- Hoàn thiện thư viện component để nâng cao khả năng tái sử dụng mã
- Tối ưu hiệu năng trang để cải thiện trải nghiệm người dùng
- Bổ sung kiểm thử tự động để đảm bảo chất lượng mã
- Liên tục cập nhật thư viện phụ thuộc để duy trì tính hiện đại của framework

---

Phiên bản: 1.0
Tác giả: Hệ thống tạo tự động
Ngày cập nhật: 2026-01-23
---

> **Gợi ý**: Tài liệu này do AI tạo, chỉ dùng để tham khảo.
