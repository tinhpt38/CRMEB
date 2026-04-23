crmeb/app/daoThư mục là đối tượng truy cập dữ liệu dự án(DAO)thư mục mã của lớp.

Các trách nhiệm và chức năng chính của lớp DAO như sau::

1. Triển khai quyền truy cập lớp lưu giữ dữ liệu,Thực hiện các thao tác CURD trên cơ sở dữ liệu.

2. Dựa vào kết nối cơ sở dữ liệu,Nhận biết các chức năng cơ bản của việc thêm, xóa, sửa và truy vấn bảng.

3. Đóng gói các thao tác cơ sở dữ liệu nguyên thủy(Truy vấn,chèn,Cập nhật, v.v.),Đơn giản hóa khó khăn phát triển.

4. Tách khỏi cơ sở dữ liệu,Cung cấp một giao diện thống nhất,Dễ dàng mở rộng và bảo trì.

Cụ thể:

- daoMỗi file trong thư mục tương ứng với một bảng dữ liệu hoặc module nghiệp vụ
- File gói gọn các thao tác cơ bản trên bảng,Chẳng hạn như tìm kiếm,chèn,Cập nhật, v.v.
- Các kiểu tham số và giá trị trả về của phương thức là đối tượng mô hình(Model),Đạt được sự tách rời dữ liệu và kinh doanh
- Cung cấp các điều kiện truy vấn phong phú để gọi điện dễ dàng
- Lớp dưới cùng sử dụng ActiveRecord của ThinkPHP để thực hiện các thao tác dữ liệu

Lợi ích của việc sử dụng lớp DAO:

- Cung cấp giao diện vận hành dữ liệu hướng đối tượng
- Sự khác biệt về cơ sở dữ liệu mặt nạ,Cải thiện tính di động
- Thuận tiện cho việc thử nghiệm và mở rộng
- Đạt được sự tách biệt giữa lớp kinh doanh và lớp dữ liệu

Do đó, thư mục này chịu trách nhiệm về các hoạt động dữ liệu cơ bản của dự án.,Các doanh nghiệp khác cần gọi nó để vận hành cơ sở dữ liệu。