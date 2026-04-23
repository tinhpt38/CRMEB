export default {
  namespaced: true,
  state: {
    menuCollapse: false,
  },
  getters: {},
  mutations: {
    /**
     * @description Đặt thanh bên để mở rộng và đóng
     * @param {Object} state vuex state
     * @param {Array} status status
     */
    changeCol(state, status) {
      state.menuCollapse = status;
    },
  },
};
