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
// import router from '@/router';
// import Setting from '@/setting';

export default {
  namespaced: true,
  state: {
    taskId: 0,
    levelId: 0,
    categoryId: 0, // Phân loại bài viếtid
  },
  mutations: {
    /**
     * @description Đặt loại thiết bị
     * @param {Object} state vuex state
     * @param {String} type Loại thiết bị, giá trị tùy chọn là Di động, Máy tính bảng, Máy tính để bàn
     */

    /**
     * @description nhiệm vụ của thành viênid
     */
    getTaskId(state, taskId) {
      state.taskId = taskId;
    },

    /**
     * @description Cấp độ thành viênid
     */
    getlevelId(state, levelId) {
      state.levelId = levelId;
    },

    /**
     * @description Phân loại bài viếtid
     */
    getCategoryId(state, categoryId) {
      state.categoryId = categoryId;
    },
  },
  actions: {
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
