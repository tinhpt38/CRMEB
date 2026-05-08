/**
 * Khi sửa đổi cấu hình, bạn cần phải dọn dẹp nó mỗi lần `window.localStorage` Cấu hình sẽ chỉ có hiệu lực nếu trình duyệt lưu nó vào bộ nhớ đệm vĩnh viễn.
 */
const themeConfigModule = {
  namespaced: true,
  state: {
    themeConfig: {
      // Có nên mở ngăn cấu hình bố cục hay không
      isDrawer: false,

      /**
       * chủ đề toàn cầu
       */
      // Màu chủ đề chính mặc định
      primary: '#409eff',
      // Màu nền thực đơn
      menuBgColor: '#282c34',
      // Có bật chế độ tối không
      isIsDark: false,
      themeStyle: 'theme-1',
      /**
       * Menu/thanh trên cùng
       * Xin lưu ý:
       *Cần sửa đổi đồng thời `/@/theme/common/var.scss` Giá trị tương ứng
       */
      // Màu nền điều hướng thanh trên cùng mặc định
      topBar: '#ffffff',
      // Màu phông chữ điều hướng thanh trên cùng mặc định
      topBarColor: '#606266',
      // Màu nền điều hướng menu mặc định
      menuBar: '#282c34',
      // Màu phông chữ điều hướng menu mặc định
      menuBarColor: '#eaeaea',
      // Màu nền menu cột mặc định
      columnsMenuBar: '#282c34',
      // Màu phông chữ menu cột mặc định
      columnsMenuBarColor: '#e6e6e6',

      /**
       * Cài đặt giao diện
       */
      // Có bật hiệu ứng gập ngang của menu hay không
      isCollapse: false,
      // Có bật hiệu ứng đàn accordion trong menu hay không
      isUniqueOpened: true,
      // Có bật tính năng ghim hay không Header
      isFixedHeader: true,

      /**
       * Giao diện hiển thị
       */
      // Có bật thanh bên hay không Logo
      isShowLogo: true,
      // Có nên bật không Breadcrumb
      isBreadcrumb: true,
      // Có bật biểu tượng Breadcrumb hay không
      isBreadcrumbIcon: false,
      // Có nên bật không Tagsview
      isTagsview: true,
      // Có bật biểu tượng Tagsview hay không
      isTagsviewIcon: false,
      // Có bật bộ nhớ đệm TagsView hay không
      isCacheTagsView: false,
      // Có nên bật thông tin bản quyền ở cuối Footer hay không
      isFooter: true,
      // Có bật chế độ màu xám hay không
      isGrayscale: false,
      // Có bật chế độ yếu màu hay không
      isInvert: false,
      /**
       * Các cài đặt khác
       */
      // Kiểu Tagsview mặc định, tùy chọn 1. tags-style-one, tự mở rộng:
      // 1. Cần sửa đổi @/layout/navBars/breadcrumb/setings.vue `getThemeConfig.tagsStyle` el-option
      // 2、Cần sửa đổi kiểu css của phần bình luận ở cuối mã @/layout/navBars/tagsView/tagsView.vue
      tagsStyle: 'tags-style-five',
      // Hoạt ảnh chuyển đổi trang chủ: giá trị tùy chọn"<slide-right|slide-left|opacitys>"，mặc định slide-right
      animation: 'opacitys',
      // Kiểu đánh dấu cột: giá trị tùy chọn"<columns-round|columns-card>"，mặc định columns-round
      columnsAsideStyle: 'columns-card',
      // Kiểu bố cục cột: giá trị tùy chọn"<columns-horizontal|columns-vertical>"，mặc định columns-horizontal
      columnsAsideLayout: 'columns-vertical',

      /**
       * Chuyển đổi bố cục
       * Lưu ý: Để minh họa, khi chuyển đổi bố cục, màu sắc sẽ được khôi phục về mặc định. Vị trí mã: /@/layout/navBars/breadcrumb/settings.vue
       * trong `initSetLayoutChange(Đặt chuyển đổi bố cục và đặt lại kiểu chủ đề)` phương pháp
       */
      // Chuyển đổi bố cục: giá trị tùy chọn"<defaults|classic|transverse|columns>"，mặc định defaults
      layout: 'columns',

      /**
       * Tiêu đề/phụ đề trang web toàn cầu
       */
      // Tiêu đề chính của trang web (điều hướng menu, tiêu đề trang web hiện tại của trình duyệt）
      globalTitle: 'crmeb-admin',
      // Phụ đề trang web (văn bản ở đầu trang đăng nhập）
      globalViceTitle: '',
      // Mô tả trang web (văn bản ở đầu trang đăng nhập）
      globalViceDes: 'vue2',
      // Ngôn ngữ ban đầu mặc định, giá trị tùy chọn"<zh-cn|en|zh-tw|vi>"，mặc định vi
      globalI18n: 'vi',
      // Kích thước thành phần toàn cầu mặc định, giá trị tùy chọn"<|medium|small|mini>"，mặc định ''
      globalComponentSize: '',
    },
  },
  mutations: {
    // Đặt cấu hình bố cục
    getThemeConfig(state, data) {
      state.themeConfig = data;
    },
  },
  actions: {
    // Đặt cấu hình bố cục
    setThemeConfig({ commit }, data) {
      commit('getThemeConfig', data);
    },
  },
};

export default themeConfigModule;
