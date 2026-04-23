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
import screenfull from 'screenfull';
import { integralGetOrdes } from '@/api/marketing';

// function today () {
//     const end = new Date();
//     const start = new Date();
//     var datetimeStart = start.getFullYear() + '/' + (start.getMonth() + 1) + '/' + start.getDate();
//     var datetimeEnd = end.getFullYear() + '/' + (end.getMonth() + 1) + '/' + end.getDate();
//     return [datetimeStart, datetimeEnd];
// }
export default {
  namespaced: true,
  state: {
    orderStatus: '', // Trạng thái đơn hàng
    // orderTime: today().join('-'), // thời gian đặt hàng
    orderTime: '',
    orderNum: '',
    orderType: 0, // Trạng thái đơn hàng
    fieldKey: '',
    orderChartType: {},
    isDels: false,
    delIdList: [],
    iconsaaaa: '',
    orderPayType: '',
    // modelLists: function
  },
  mutations: {
    /**
     * @description Đặt loại thiết bị
     * @param {Object} state vuex state
     * @param {String} type Loại thiết bị, giá trị tùy chọn là Di động, Máy tính bảng, Máy tính để bàn
     */

    /**
     * @description Tìm kiếm số thứ tự
     */
    getOrderStatus(state, orderStatus) {
      state.orderStatus = orderStatus;
    },

    /**
     * @description Tìm kiếm trạng thái đơn hàng
     */
    getOrderType(state, orderPayType) {
      state.orderPayType = orderPayType;
    },

    /**
     * @description trạng thái thời gian
     */
    getOrderTime(state, orderTime) {
      state.orderTime = orderTime;
    },

    /**
     * @description Trạng thái lựa chọn đơn hàng
     */
    getOrderNum(state, orderNum) {
      state.orderNum = orderNum;
    },

    getfieldKey(state, fieldKey) {
      state.fieldKey = fieldKey;
    },

    /**
     * @description tabChuyển đổi, chọn trạng thái đơn hàng
     */
    onChangeTabs(state, orderType) {
      state.orderType = orderType;
    },

    /**
     * @description  Trạng thái đơn hàng Tất cả đối tượng
     */
    onChangeChart(state, orderChartType) {
      state.orderChartType = orderChartType;
    },

    /**
     * @description  Có nên xóa đơn hàng theo đợt không
     */
    getIsDel(state, isDels) {
      state.isDels = isDels;
    },

    /**
     * @description  Bộ sưu tập id đơn hàng xóa hàng loạt
     */
    getisDelIdListl(state, delIdList) {
      state.delIdList = delIdList;
    },
  },
  actions: {
    /**
     * @description Trạng thái đơn hàng
     */
    getOrderTabs({ commit }, data) {
      return new Promise((resolve, reject) => {
        resolve(true);
      });
    },
    /**
     * @description Khởi tạo giám sát trạng thái toàn màn hình
     */
    listenFullscreen({ commit }) {
      return new Promise((resolve) => {
        if (screenfull.enabled) {
          screenfull.on('change', () => {
            if (!screenfull.isFullscreen) {
              commit('setFullscreen', false);
            }
          });
        }
        // end
        resolve();
      });
    },
    /**
     * @description Chuyển đổi toàn màn hình
     */
    toggleFullscreen({ commit }) {
      return new Promise((resolve) => {
        if (screenfull.isFullscreen) {
          screenfull.exit();
          commit('setFullscreen', false);
        } else {
          screenfull.request();
          commit('setFullscreen', true);
        }
        // end
        resolve();
      });
    },
  },
};
