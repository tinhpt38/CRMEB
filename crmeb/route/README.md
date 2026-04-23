crmeb/routeVai trò chính của thư mục trong dự án CRMEB là xác định các quy tắc định tuyến của dự án.

1. Xác định một Route::miss Phương pháp xử lý trường hợp tuyến đường không khớp

2. Lấy tên ứng dụng dựa trên đường dẫn yêu cầu(Chẳng hạn như quản trị viên, ứng dụng, v.v.)

3. Trả về các tệp xem khác nhau dựa trên tên ứng dụng

   - Quầy lễ tân và quầy lễ tân lần lượt quay về các lối vào khác nhau

   - app/kefu xác định chế độ xem tương ứng

   - Trang chủ bao gồm cổng thông tin di động và PC

   - Trong các trường hợp khác, hãy xác định xem thiết bị đầu cuối di động có trả về chế độ xem khác hay không

4. Xác định đầy đủ tất cả các mục định tuyến có thể có cho dự án

5. Kết hợp thông minh các tệp tài nguyên xem dựa trên thông tin được yêu cầu

Chức năng chính:

- Xử lý tất cả các tuyến đường phù hợp một cách thống nhất
- Ẩn mục điều khiển thực tế
- Phân phối các trang dựa trên tên ứng dụng
- Thực hiện chuyển đổi tự động giữa PC và thiết bị đầu cuối di động

Thiết kế này có thể:

- Bảo hiểm đầy đủ tất cả các tình huống định tuyến
-Ẩn hệ thống phân cấp định tuyến thực tế
- Thực hiện phân phối trang thông minh

là một ví dụ điển hình về thiết kế định tuyến động。