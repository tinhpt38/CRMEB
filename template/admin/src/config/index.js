// +---------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +---------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +---------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +---------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +---------------------------------------------------------------------

export default {
  s: `1`,
  /**
   * @description Cấu hình các tab hiển thị trên trình duyệttitle
   */
  title: '',
  /**
   * @description tokenSố ngày lưu trữ trong cookie, mặc định là 1 ngày
   */
  cookieExpires: 1,
  /**
   * @description Có sử dụng quốc tế hóa hay không, mặc định là sai
   * Nếu không sử dụng, bạn cần đặt cài đặt định tuyến cần hiển thị trong menu.meta: {title: 'xxx'}
   *              Dùng để hiển thị văn bản trong menu
   */
  useI18n: false,
  /**
   * @description apiYêu cầu đường dẫn cơ sở
   */
  baseUrl: {
    dev: '',
    pro: '',
  },
  /**
   * @description Giá trị tên tuyến đường của trang chủ được mở theo mặc định. Mặc định làhome
   */
  homeName: 'home_index',
  /**
   * @description Các plug-in cần được tải
   */
  plugin: {
    'error-store': {
      showInHeader: true, // Đặt thành false để không hiển thị logo nhật ký lỗi ở trên cùng
      developmentOff: false, // Sau khi thiết lập thành true, thông tin lỗi sẽ không được thu thập trong môi trường phát triển, giúp việc khắc phục lỗi trong quá trình phát triển trở nên dễ dàng hơn.
    },
  },
};
