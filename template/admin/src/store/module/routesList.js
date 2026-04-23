const routesListModule = {
  namespaced: true,
  state: {
    routesList: [],
  },
  mutations: {
    // Đặt định tuyến, được sử dụng trong menu
    getRoutesList(state, data) {
      state.routesList = data;
    },
  },
  actions: {
    // Đặt định tuyến, được sử dụng trong menu
    async setRoutesList({ commit }, data) {
      commit('getRoutesList', data);
    },
  },
};

export default routesListModule;
