# Đánh giá Giao diện Tiếng Việt (CRMEB Admin)

Dựa trên quá trình thu thập thông tin và duyệt qua các trang như Trang chủ, Quản lý người dùng, Quản lý sản phẩm và Quản lý đơn hàng, dưới đây là nhận định chi tiết về chất lượng dịch thuật và hiển thị của giao diện Tiếng Việt:

![Video Thao Tác](/Users/tinhp/.gemini/antigravity/brain/041f784b-2920-4931-8fa7-faf96a29aa16/analyze_vietnamese_ui_1777002689396.webp)

## 1. Vấn đề về Bố cục & Giao diện (Layout)
* **Sidebar bị cắt chữ (Truncation):** Chiều rộng của menu bên trái đang quá hẹp so với độ dài từ vựng Tiếng Việt. Rất nhiều menu bị ẩn chữ do tràn viền. Ví dụ:
  * "ngườ..." (người dùng)
  * "Đặt..." (Đặt hàng)
  * "tiếp..." (Tiếp thị)
  * "Phân..." (Phân phối)
* **Viết hoa/viết thường không đồng nhất:** Các nút bấm hoặc nhãn hiển thị chữ thường ở đầu câu, trông không chuyên nghiệp. Ví dụ: `"tất cả"`, `"cài lại"`, `"trong kho"`, `"tình trạng"`.
* **Sót từ Tiếng Anh:** Một số thành phần như phân trang vẫn hiển thị tiếng Anh (ví dụ: `"Go to"`, `"Total"`).

## 2. Lỗi Dịch thuật ở Trang Chủ (Dashboard)
Các nhãn thống kê trên trang chủ bị dịch theo nghĩa đen (word-by-word) hoặc nhầm ngữ cảnh một cách nghiêm trọng:
* **"30 bầu trời"**: Dịch sai từ "30 days" (từ "day" bị nhầm thành "bầu trời" - sky/thiên). Sửa thành: **"30 ngày"**.
* **"mặt trăng"**: Dịch sai từ "month" (nhầm sang "mặt trăng" - moon/nguyệt). Sửa thành: **"tháng"**.
* **"0 Nhân dân tệ"**: Đơn vị tiền tệ vẫn đang hardcode bằng tiếng Trung. Nên đổi thành **"VNĐ"** hoặc ký hiệu tiền tệ chung.
* **"0 một" & "0 mọi người"**: Dịch hậu tố số lượng bị cấn. Nên là **"0 đơn hàng"** và **"0 người dùng"**.

## 3. Các Lỗi Dịch Thuật Tại Các Trang Quản Lý
Nhiều thuật ngữ đang được dịch giống như máy dịch chưa qua chỉnh sửa. Dưới đây là các đề xuất điều chỉnh:

### Thao tác chung
| Từ hiện tại (Đang sai) | Đề xuất sửa thành | Lý do |
| :--- | :--- | :--- |
| **Truy vấn** | **Tìm kiếm** | "Truy vấn" quá nặng về kỹ thuật (Query). |
| **vận hành** | **Thao tác / Hành động** | Cột action của bảng không nên để là "vận hành". |
| **cài lại** | **Đặt lại / Làm mới** | Dùng cho nút Reset. |
| **Xuất khẩu** | **Xuất dữ liệu / Xuất file**| "Xuất khẩu" dùng cho thương mại, không dùng cho xuất file (Export). |
| **biên tập** | **Sửa / Chỉnh sửa** | Nút Edit nên dùng "Sửa". |

### Quản lý Người dùng
| Từ hiện tại (Đang sai) | Đề xuất sửa thành |
| :--- | :--- |
| **Thành lập nhóm theo đợt** | **Phân nhóm hàng loạt** |
| **Đặt nhãn theo lô** | **Gắn nhãn hàng loạt** |

### Quản lý Sản phẩm / Hàng hóa
| Từ hiện tại (Đang sai) | Đề xuất sửa thành |
| :--- | :--- |
| **hàng hóaID** | **ID sản phẩm** |

### Quản lý Đơn hàng
| Từ hiện tại (Đang sai) | Đề xuất sửa thành |
| :--- | :--- |
| **Loại lệnh** | **Loại đơn hàng** |
| **Được trả tiền** | **Đã thanh toán** |
| **Đang chờ xóa sổ** | **Chờ đối soát / Chờ xử lý** |
| **Lô hàng số lượng lớn** | **Giao hàng hàng loạt** |

## Tổng kết & Đề xuất
Giao diện hiện tại có thể sử dụng được nhưng chất lượng ngôn ngữ Tiếng Việt khá kém, mang đậm dấu ấn "Google Dịch" (hoặc dịch tự động từ Tiếng Trung sang mà không qua review ngữ cảnh). 

**Thứ tự ưu tiên cần khắc phục:**
1. **Mở rộng chiều rộng (width) của Sidebar** hoặc thêm tooltip/collapse phù hợp để chữ không bị cắt.
2. **Sửa các từ ngữ thống kê sai nghiêm trọng ở Trang chủ** (bầu trời, mặt trăng, nhân dân tệ).
3. **Chuẩn hóa các nút thao tác (Action buttons)** trên toàn hệ thống (Sửa, Xóa, Tìm kiếm, Đặt lại).
4. **Đồng bộ hóa việc viết hoa chữ cái đầu** để giao diện trông chỉn chu và chuyên nghiệp hơn.
