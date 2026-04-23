const tagsViewRoutesModule = {
  namespaced: true,
  state: {
    tagsViewRoutes: [],
  },
  mutations: {
    // Thiết lập định tuyến TagsView
    getTagsViewRoutes(state, data) {
      state.tagsViewRoutes = data;
    },
  },
  actions: {
    // Thiết lập định tuyến TagsView
    async setTagsViewRoutes({ commit }, data) {
      commit('getTagsViewRoutes', data);
    },
  },
};

export default tagsViewRoutesModule;
