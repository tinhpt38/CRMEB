const keepAliveNamesModule = {
  namespaced: true,
  state: {
    keepAliveNames: [],
  },
  mutations: {
    // Đặt bộ đệm tuyến đường (trường tên）
    getCacheKeepAlive(state, data) {
      state.keepAliveNames = data;
    },
  },
  actions: {
    // Đặt bộ đệm tuyến đường (trường tên）
    async setCacheKeepAlive({ commit }, data) {
      commit('getCacheKeepAlive', data);
    },
  },
};

export default keepAliveNamesModule;
