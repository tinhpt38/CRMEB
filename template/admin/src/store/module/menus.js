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
 * Cấu hình menu bố cục
 * */
import { menusApi } from '@/api/account';
function getMenusName() {
  let storage = window.localStorage;
  let menuList = JSON.parse(storage.getItem('menuList'));
  if (typeof menuList !== 'object' || menuList === null) {
    menuList = [];
  }
  return menuList;
}
export default {
  namespaced: true,
  state: {
    menusName: getMenusName(),
    openMenus: [],
    childMenuList: [],
    oneLvMenus: [],
    oneLvRoutes: [],
  },
  mutations: {
    getmenusNav(state, menuList) {
      state.menusName = menuList;
    },
    // getopenMenus (state, openList) {
    //   state.openMenus = openList
    // }
    setopenMenus(state, openList) {
      state.openMenus = openList;
    },
    setOneLvMenus(state, oneLvMenus) {
      state.oneLvMenus = oneLvMenus;
    },
    setOneLvRoute(state, oneLvMenus) {
      state.oneLvRoutes = oneLvMenus;
    },
    childMenuList(state, list) {
      state.childMenuList = list;
    },
  },
  actions: {
    getMenusNavList({ commit }) {
      return new Promise((resolve, reject) => {
        menusApi()
          .then(async (res) => {
            resolve(res);
            commit('getmenusNav', res.data.menus);
          })
          .catch((res) => {
            reject(res);
          });
      });
    },
  },
};
