# Kế Hoạch Đại Tu Giao Diện Tiếng Việt CRMEB Admin

Báo cáo này tổng hợp tất cả các vấn đề liên quan đến ngôn ngữ và giao diện (UI) dựa trên quá trình phân tích hệ thống CRMEB Admin, đồng thời đề xuất giải pháp và quy trình thực hiện cụ thể để khắc phục.

---

## Phần 1: Phân Tích Chi Tiết Các Lỗi Hiện Tại

Sau khi kiểm tra Trang chủ, Quản lý đơn hàng, Sửa sản phẩm và Chỉnh sửa Theme, hệ thống hiện đang gặp 3 nhóm lỗi chính:

### 1.1. Lỗi dịch máy "ngớ ngẩn" (Word-by-word)
Các file ngôn ngữ đang được dịch bằng công cụ tự động mà không qua hiệu đính, dẫn đến sai hoàn toàn ngữ cảnh thương mại điện tử.
* **Sai ngữ cảnh nghiêm trọng:**
  * `Save` -> **"Cứu"** (Đúng: *Lưu / Lưu lại*)
  * `Tablet` -> **"Viên thuốc"** (Đúng: *Máy tính bảng*)
  * `Stock Specification` -> **"Đặc điểm kỹ thuật cổ phiếu"** (Đúng: *Thuộc tính tồn kho / Phân loại hàng*)
  * `Video` -> **"Băng hình"** (Đúng: *Video*)
  * `Order` -> **"Thứ tự thông thường"** (Đúng: *Đơn hàng*)
  * `Order Type` -> **"Loại lệnh"** (Đúng: *Loại đơn hàng*)
* **Sai về hiển thị số liệu / thời gian (Trang chủ):**
  * `30 days` -> **"30 bầu trời"** (Đúng: *30 ngày*)
  * `month` -> **"mặt trăng"** (Đúng: *Tháng*)
* **Thuật ngữ thao tác không tự nhiên:**
  * `Action` -> **"vận hành"** (Đúng: *Thao tác*)
  * `Reset` -> **"cài lại"** (Đúng: *Làm mới / Đặt lại*)
  * `Export` -> **"Xuất khẩu"** (Đúng: *Xuất file / Xuất dữ liệu*)

### 1.2. Lỗi Bố cục (Layout & UI)
Tiếng Việt thường dài hơn tiếng Trung/Anh khoảng 30-50%, dẫn đến hiện tượng vỡ khung:
* **Sidebar bị cắt cụt (Truncate):** Các menu dài như "Quản lý người dùng", "Trang chủ trung tâm mua sắm" bị cắt thành `"ngườ..."` hoặc nhảy dòng gây mất thẩm mỹ.
* **Rớt dòng trong Tab/Modal:** Ở trang Chi tiết đơn hàng, các tab ngang bị rớt xuống thành 2 dòng (Ví dụ: "Thông" trên, "tin đặt" dưới).
* **Không đồng nhất chữ Hoa/Thường:** Rất nhiều nhãn hiển thị là chữ thường (`"tất cả"`, `"đơn vị"`), làm giảm sự chuyên nghiệp của admin panel.

### 1.3. Lỗi Sót Ngôn Ngữ (Hardcode)
* **Tiếng Trung:** Ở phần xem trước Mobile của trang Thiết kế Theme, thanh điều hướng đáy (Bottom Nav) vẫn còn tiếng Trung: `分类` (Danh mục), `购物车` (Giỏ hàng), `我的` (Cá nhân).
* **Tiếng Anh:** Các phần phân trang của bảng hiển thị `"Go to"`, `"Total"`.

---

## Phần 2: Cách Thức Sửa Đổi (Quy Trình & Kỹ Thuật)

Để xử lý dứt điểm, chúng ta cần can thiệp vào 3 thành phần của source code Vue.js:

1. **File ngôn ngữ (i18n):** Nằm trong thư mục `src/i18n` hoặc `src/utils/lang`.
2. **File Vue Components (`.vue`):** Tìm các hardcode chưa được bọc biến `$t()`.
3. **File CSS/SCSS:** Nới rộng Sidebar và các phần bị cắt chữ.

### Phương Pháp Thay Thế
* Không dịch lại từ đầu để tiết kiệm thời gian. Chúng ta sẽ dùng tính năng **Find & Replace** trong IDE (hoặc qua dòng lệnh) để sửa trực tiếp các cụm từ bị dịch ngớ ngẩn nhất trong file từ điển tiếng Việt (thường là `vi.js` hoặc `vi.json`).
* Quét các thành phần UI framework (như Element UI hoặc iView) để khai báo ngôn ngữ tiếng Việt cho phân trang (đổi "Go to" thành "Đi tới").

---

## Phần 3: Hướng Dẫn Thực Thi Từng Bước (Step-by-step)

### Bước 1: Đại tu file ngôn ngữ Tiếng Việt
* **Hành động:** Mở thư mục chứa file ngôn ngữ (ví dụ `src/i18n/vi.js`).
* **Sửa các từ khóa chính:**
  * Tìm `"Cứu"` -> Thay thành `"Lưu"`
  * Tìm `"Viên thuốc"` -> Thay thành `"Máy tính bảng"`
  * Tìm `"cổ phiếu"` -> Thay thành `"tồn kho"`
  * Tìm `"bầu trời"` -> Thay thành `"ngày"`
  * Tìm `"mặt trăng"` -> Thay thành `"tháng"`
  * Tìm `"Thứ tự"` -> Thay thành `"Đơn hàng"`
  * Tìm `"lệnh"` -> Thay thành `"đơn hàng"`
  * Tìm `"vận hành"` -> Thay thành `"Thao tác"`
* *Lưu ý:* Khi Find & Replace, nên tìm kiếm phân biệt hoa/thường (Case-sensitive) để không ảnh hưởng các cụm từ khác.

### Bước 2: Khắc phục lỗi Layout (Sidebar & Tab)
* **Sidebar:**
  * Tìm file cấu hình layout (thường là `src/layout/components/Sidebar/index.vue` hoặc file SCSS tương ứng).
  * Chỉnh sửa biến width của sidebar:
    ```css
    /* Tăng chiều rộng thêm 30-50px */
    .sidebar-container {
       width: 250px !important; /* Thay vì 200px hoặc 210px cũ */
    }
    ```
* **Tab chi tiết đơn hàng:**
  * Tìm file `src/pages/order/list` hoặc components tương ứng.
  * Tăng `min-width` cho các `el-tab-pane` hoặc thêm CSS `white-space: nowrap;` để chữ không bị rớt dòng.

### Bước 3: Xử lý Text Hardcode
* **Mobile Preview (Theme Editing):**
  * Dùng chức năng Global Search (Tìm kiếm toàn bộ project) cho từ khóa `购物车`.
  * Nó sẽ nằm trong một file `.vue` ở thư mục `src/pages/setting/theme`.
  * Sửa trực tiếp từ `购物车` thành `Giỏ hàng`, `分类` thành `Danh mục`, `我的` thành `Cá nhân`.
* **Phân trang (Pagination):**
  * Kiểm tra file `src/main.js`. 
  * Cần import locale tiếng Việt của thư viện UI (Ví dụ: `import viLocale from 'element-ui/lib/locale/lang/vi'`) để dịch các chữ "Go to", "Total".

### Bước 4: Chuẩn hóa UI và Review
* Tìm các nút "reset", "export" và chỉnh lại thành "Làm mới", "Xuất file".
* Thêm rule CSS `#app .el-button { text-transform: capitalize; }` hoặc sửa trực tiếp trong file i18n để các nút luôn viết hoa chữ cái đầu (VD: thay vì "tất cả" sẽ thành "Tất cả").
* Khởi động lại watcher (`npm run dev`) và duyệt qua lại các trang để đảm bảo UI không còn vỡ và câu cú đã mượt mà theo chuẩn thương mại điện tử.
