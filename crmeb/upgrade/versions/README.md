crmeb/upgrade/versionsVai trò chính của thư mục trong dự án CRMEB là lưu trữ các tệp phiên bản và các tập lệnh nâng cấp liên quan đến nâng cấp hệ thống.

Trong quá trình cập nhật lặp đi lặp lại của hệ thống CRMEB, cần có cơ chế nâng cấp hoàn chỉnh để đảm bảo khả năng di chuyển dữ liệu và khả năng tương thích của phiên bản. Thư mục này chịu trách nhiệm quản lý tập trung các tài nguyên nâng cấp cho từng phiên bản.

Cụ thể:

- Lưu trữ tài liệu nâng cấp cho từng phiên bản và ghi lại các thay đổi của phiên bản
- Đặt các tập lệnh di chuyển cơ sở dữ liệu để xử lý các thay đổi cấu trúc bảng và di chuyển dữ liệu
- Lưu trữ các tệp tài nguyên tĩnh cần thiết để nâng cấp
- Ghi lại sự phụ thuộc của phiên bản và trình tự nâng cấp
- Lưu file nhận dạng trạng thái nâng cấp và phát hiện phiên bản

Sử dụng thư mục này có những ưu điểm sau:

- Triển khai quản lý mô-đun nâng cấp phiên bản để tạo điều kiện truy xuất nguồn gốc và khôi phục
- Tách biệt khỏi mã nghiệp vụ cốt lõi để giảm rủi ro nâng cấp
- Hỗ trợ nâng cấp gia tăng nhiều phiên bản và thích ứng linh hoạt với các phiên bản khởi đầu khác nhau
- Tạo điều kiện cho các công cụ triển khai tự động để xác định và thực hiện quá trình nâng cấp

Nói chung, nó chịu trách nhiệm điều phối việc lặp lại phiên bản hệ thống và di chuyển cơ sở dữ liệu.

Bằng cách tiêu chuẩn hóa việc sử dụng thư mục này, bạn có thể đảm bảo rằng quá trình nâng cấp diễn ra an toàn, có thể kiểm soát và theo dõi được.。
