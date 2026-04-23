// Yêu cầu địa chỉ giao diện Nếu không được định cấu hình, tự động lấy đường dẫn URL hiện tại
const VUE_APP_API_URL = process.env.VUE_APP_API_URL || `${location.origin}/adminapi`;

const Setting = {
  // tiền tố định tuyến
  routePre: '/admin',
  // Địa chỉ yêu cầu giao diện
  apiBaseURL: VUE_APP_API_URL,
  // Chế độ định tuyến, giá trị tùy chọn là lịch sử hoặc hash
  routerMode: 'history',
  // Có hiển thị thanh tiến trình mô phỏng khi chuyển trang hay không
  showProgressBar: true,
};

export default Setting;
