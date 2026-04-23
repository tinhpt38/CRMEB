crmeb/app/adminapiThư mục này chủ yếu là tệp giao diện API của hệ thống quản lý phụ trợ.

Cụ thể:

- adminapiCác file trong thư mục là bộ điều khiển của hệ thống quản lý nền(Controller)tài liệu,Các bộ điều khiển này được sử dụng để xử lý các yêu cầu khác nhau từ hệ thống nền.

- Mỗi file điều khiển tương ứng với một phân hệ chức năng nhất định của hệ thống quản lý backend,Ví dụ: AuthController xử lý các yêu cầu mô-đun xác thực,StoreProductXử lý các yêu cầu mô-đun sản phẩm, v.v.

- Có nhiều phương pháp khác nhau trong bộ điều khiển,Các phương thức này tương đương với giao diện API,Có thể xử lý các yêu cầu GET và POST,Trả về dữ liệu JSON.

- Khi trình duyệt hoặc APP gọi các giao diện API này,Sẽ gửi yêu cầu đến phương thức điều khiển tương ứng,Ví dụ: giao diện đăng nhập yêu cầu phương thức đăng nhập của file Đăng nhập.

- Sau khi bộ điều khiển đã xử lý yêu cầu,Trả về kết quả xử lý cho trình duyệt hoặc APP bằng cách trả về đối tượng Phản hồi.

Vì vậy, trong điều kiện đơn giản,adminapiThư mục chịu trách nhiệm về tất cả các giao diện API của hệ thống quản lý phụ trợ,Các giao diện này được APP hoặc front-end gọi để hoàn thành các hoạt động quản lý khác nhau.,Chẳng hạn như truy vấn dữ liệu, thêm, sửa và xóa, v.v. Khi nhà phát triển thêm các chức năng nền,Cũng cần thêm các bộ điều khiển và giao diện tương ứng vào thư mục này.

Nó thực sự chịu trách nhiệm về lớp tương tác giao tiếp của hệ thống phụ trợ,Tách rời logic back-end và hiển thị front-end,Được thiết kế bằng cách sử dụng thông số kỹ thuật RESTful.

#adminapi mô tả cấu trúc thư mục

## Cấu trúc thư mục

```
.
├── config/                  # Thư mục cấu hình
├── controller/              # Thư mục điều khiển
├── lang/                    # Thư mục gói ngôn ngữ
├── middleware/              # Thư mục phần mềm trung gian
├── route/                   # Thư mục cấu hình định tuyến
├── validate/                # thư mục xác thực
├── AdminApiExceptionHandle.php # xử lý ngoại lệ
├── common.php               # phương pháp công khai
├── event.php                # cấu hình sự kiện
└── provider.php             # nhà cung cấp dịch vụ
```

## Mô tả danh mục

- **config/** - Cấu hình dành riêng cho quản lý phụ trợ
- **controller/** - bộ điều khiển quản lý nền, xử lý logic nghiệp vụ phía quản lý
- **lang/** - Quản lý backend file đa ngôn ngữ
- **middleware/** - phần mềm trung gian quản lý nền, chẳng hạn như xác minh quyền, ghi nhật ký, v.v.
- **route/** - cấu hình định tuyến quản lý nền
- **validate/** - Trình xác thực dữ liệu quản lý phụ trợ

## Mô tả chức năng

Mô-đun adminapi được sử dụng đặc biệt để xử lý giao diện API của hệ thống quản lý nền, bao gồm:
- Quản lý quyền người dùng
- Quản lý sản phẩm
- Xử lý đơn hàng
- Thống kê
- Cài đặt hệ thống và các chức năng quản lý nền khác