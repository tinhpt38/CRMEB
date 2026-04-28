# Phân tích Chuyên sâu: Lỗi Giao diện & Dịch thuật
*(Đánh giá tại các trang Chi tiết đơn hàng, Sửa sản phẩm, Chỉnh sửa Theme)*

Dựa trên việc kiểm tra và phân tích trực tiếp ba màn hình được yêu cầu, dưới đây là báo cáo chi tiết về tình trạng bản dịch tiếng Việt và lỗi giao diện (UI) của hệ thống CRMEB Admin:

## 1. Quản lý đơn hàng & Chi tiết đơn hàng
- **Lỗi dịch thuật nghiêm trọng:**
    - **"Thứ tự thông thường"**: Lỗi dịch từ "Order" (trong tiếng Anh có nghĩa là cả "Thứ tự" và "Đơn hàng"). Đúng phải là **"Đơn hàng thông thường"**.
    - **"Loại lệnh"**: Dịch từ "Order Type", nhưng dùng từ "lệnh" (command) là sai ngữ cảnh. Nên dùng **"Loại đơn hàng"**.
    - **"Ràng buộc điện thoại"**: Dịch word-by-word từ "Phone binding". Nên dùng **"Số điện thoại liên kết"**.
    - **"Hơn"**: Nút thao tác mở rộng bị dịch thành "Hơn" (More). Nên dùng **"Thêm" / "Khác"**.
    - **"vận hành"**: Cột "Action" bị dịch thành "vận hành". Nên dùng **"Thao tác"**.
- **Lỗi Giao diện (UI):**
    - Các tab trong phần chi tiết (Drawer) bị xuống hàng lộn xộn (VD: "Thông" nằm trên, "tin đặt" nằm dưới) do chữ tiếng Việt quá dài so với khung hiển thị vốn thiết kế cho tiếng Trung.

![Click Chi Tiết Đơn Hàng](/Users/tinhp/.gemini/antigravity/brain/041f784b-2920-4931-8fa7-faf96a29aa16/.system_generated/click_feedback/click_feedback_1777003075874.png)

## 2. Quản lý sản phẩm & Biên tập
- **Lỗi dịch thuật "ngớ ngẩn":**
    - **"Viên thuốc"**: Đây là lỗi cực kỳ nặng. Từ "Tablet" (Máy tính bảng) đã bị dịch tự động thành "Viên thuốc" (Medicine pill). Bạn sẽ thấy dòng *"di động kỹ thuật số / Viên thuốc"*.
    - **"đặc điểm kỹ thuật cổ phiếu"**: Dịch từ "Stock Specification". Từ "Stock" bị hiểu nhầm sang chứng khoán (Cổ phiếu) thay vì Kho hàng. Đúng phải là **"Thuộc tính tồn kho" / "Phân loại sản phẩm"**.
    - **"Băng chuyền sản phẩm"**: Dịch từ "Product Carousel". Trong UI nên dùng **"Ảnh slider sản phẩm"**.
    - **"Cài đặt hậu cần"**: Dịch từ "Logistics settings". Nên dùng **"Cấu hình vận chuyển"**.
- **Lỗi Giao diện (UI):**
    - Các nhãn (label) thường xuyên viết hoa/viết thường không đồng nhất (VD: "đơn vị", "thêm video" viết thường hoàn toàn).

![Click Biên tập sản phẩm](/Users/tinhp/.gemini/antigravity/brain/041f784b-2920-4931-8fa7-faf96a29aa16/.system_generated/click_feedback/click_feedback_1777003110927.png)

## 3. Chỉnh sửa giao diện (Theme Editing)
- **Lỗi dịch thuật:**
    - **"cứu"**: Nút "Save" bị dịch thành "Cứu" (như cứu người/cứu mạng). Phải sửa thành **"Lưu"**.
    - **"Băng hình"**: Dịch từ "Video", nhưng dùng từ "Băng hình" rất cổ. Nên dùng **"Video"**.
    - **"hiện hànhchủ đề"**: Thiếu dấu cách và dùng từ không tự nhiên. Nên dùng **"Chủ đề hiện tại"**.
- **Chưa dịch (Sót tiếng Trung):**
    - Ở phần xem trước Mobile, thanh điều hướng bên dưới vẫn còn tiếng Trung: `分类` (Danh mục), `购物车` (Giỏ hàng), `我的` (Cá nhân).
- **Lỗi Giao diện (UI):**
    - **Sidebar bị vỡ:** Các menu như "Trang chủ trung tâm mua sắm" bị cắt cụt. Sidebar hiện tại quá hẹp cho các cụm từ tiếng Việt dài.

## Tổng kết & Đề xuất
Các lỗi trên cho thấy file đa ngôn ngữ (i18n) của dự án đã bị cho chạy qua Google Translate toàn bộ mà không có người kiểm tra lại (Proofreading), gây ra các lỗi dịch cực kỳ ngây ngô và sai ngữ cảnh thương mại điện tử.

**Giải pháp:**
1. Tìm toàn bộ file `*.js`, `*.json` chứa ngôn ngữ tiếng Việt trong thư mục `src/i18n` hoặc tương đương.
2. Tìm và thay thế các từ khóa bị sai trầm trọng (Cứu -> Lưu, Viên thuốc -> Máy tính bảng, Cổ phiếu -> Tồn kho, Lệnh -> Đơn hàng, v.v.).
3. Tìm các file `.vue` có chứa tiếng Trung hardcode (như menu ở màn chỉnh sửa theme) để bọc vào hàm `$t()` hoặc thay trực tiếp bằng tiếng Việt.
4. Điều chỉnh file CSS/SCSS ở layout chính để nới rộng sidebar thêm khoảng 30-50px.
