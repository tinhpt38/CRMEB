crmeb/app/jobsThư mục này là thư mục mã của lớp nhiệm vụ xếp hàng dự án CRMEB.

Nhiệm vụ xếp hàng đóng vai trò quan trọng sau đây trong quá trình phát triển dự án::

1. Xử lý không đồng bộ. Một số tác vụ chạy dài có thể được đưa vào hàng đợi để xử lý không đồng bộ,Không chặn chủ đề chính.

2. Xử lý chậm trễ. Các tác vụ xếp hàng có thể được chỉ định để thực thi không đồng bộ sau một khoảng thời gian nhất định,Chẳng hạn như gửi tin nhắn văn bản hoặc email.

3. Xử lý phân tán. Nhiệm vụ hàng đợi có thể được phân phối đến các máy chủ khác nhau để xử lý,Nâng cao hiệu quả sử dụng máy chủ.

Thư mục này chủ yếu chứa nội dung sau:

- Mỗi lớp nhiệm vụ tương ứng với một nhiệm vụ kinh doanh,Triển khai giao diện Công việc.

- Xác định logic nghiệp vụ nhiệm vụ cụ thể trong lớp nhiệm vụ,Chẳng hạn như gửi tin nhắn văn bản/email, v.v.

- Gửi tác vụ và xử lý không đồng bộ thông qua Nhà môi giới.

- Hỗ trợ các chức năng như trì hoãn nhiệm vụ và thử lại lỗi.

Sử dụng hàng đợi có thể làm cho hiệu suất dự án tốt hơn:

- Các tác vụ chặn bị loại bỏ và thực thi không đồng bộ.

- Mỗi tác vụ chạy độc lập ở chế độ phân tán,Không chặn các quá trình khác.

- Tái sử dụng dịch vụ tương tự thông qua Nhà môi giới,Và nó có khả năng mở rộng tốt.

Vì vậy thư mục này chịu trách nhiệm viết và lập lịch cho tất cả các tác vụ không đồng bộ trong dự án,Đóng vai trò quan trọng trong việc tối ưu hóa hiệu suất và khả năng mở rộng của hệ thống。