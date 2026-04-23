// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

/**
 * cấu hình bố trí
 * */

export default {
  namespaced: true,
  state: {
    isMobile: false, // Có phải là điện thoại di động không?
    isTablet: false, // Nó có phải là một máy tính bảng?
    isDesktop: true, // Đây có phải là máy tính để bàn không?
    isFullscreen: false, // Có nên chuyển sang toàn màn hình không
  },
  mutations: {
    /**
     * @description Đặt loại thiết bị
     * @param {Object} state vuex state
     * @param {String} type Loại thiết bị, giá trị tùy chọn là Mobile、Tablet、Desktop
     */
    setDevice(state, type) {
      state.isMobile = false;
      state.isTablet = false;
      state.isDesktop = false;
      state[`is${type}`] = true;
    },
  },
  actions: {},
};
