// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2021 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------
/**
 * thực đơn
 * */
import { cloneDeep } from 'lodash';
import { includeArray } from '@/libs/system';

// Lọc menu theo quyền được cấu hình bởi menu
function filterMenu(menuList, access, lastList) {
  menuList.forEach((menu) => {
    let menuAccess = menu.auth;

    if (!menuAccess || includeArray(menuAccess, access)) {
      let newMenu = {};
      for (let i in menu) {
        if (i !== 'children') newMenu[i] = cloneDeep(menu[i]);
      }
      if (menu.children && menu.children.length) newMenu.children = [];

      lastList.push(newMenu);
      menu.children && filterMenu(menu.children, access, newMenu.children);
    }
  });
  return lastList;
}
// Xử lý đệ quy các vấn đề về menu trên cùng
function getChilden(data) {
  if (data.children) {
    return getChilden(data.children[0]);
  }
  return data.path;
}

export default {
  namespaced: true,
  state: {
    // thực đơn trên cùng
    header: [],
    // Tên menu cấp độ đầu tiên
    oneMenuName: '',
    // Menu thanh bên
    sider: [],
    // Menu thanh trên cùng hiện tại name
    headerName: '',
    // của menu hiện tại path
    activePath: '',
    // Bộ sưu tập tên menu con mở rộng
    openNames: [],
  },
  getters: {
    /**
     * @description Thực hiện lọc xác thực trên menu bên dựa trên quyền của người dùng đăng nhập trong tài khoản người dùng
     * */
    filterSider(state, getters, rootState) {
      const userInfo = rootState.user.info;
      // @Quyền
      const access = userInfo.access;
      if (access && access.length) {
        return filterMenu(state.sider, access, []);
      } else {
        return filterMenu(state.sider, [], []);
      }
    },
    // Xử lý đệ quy tuyến đường hàng đầu

    /**
     * @description Thực hiện lọc xác thực trên menu thanh trên cùng dựa trên quyền của người dùng đã đăng nhập trong người dùng
     * */
    filterHeader(state, getters, rootState) {
      //  Gọi hàm đệ quy
      state.header.forEach((item) => {
        item.path = getChilden(item);
      });

      // @Quyền
      const userInfo = rootState.admin.user.info;
      const access = userInfo.access;
      if (access && access.length) {
        return state.header.filter((item) => {
          let state = true;
          if (item.auth && !includeArray(item.auth, access)) state = false;
          return state;
        });
      } else {
        return state.header.filter((item) => {
          let state = true;
          if (item.auth && item.auth.length) state = false;
          return state;
        });
      }
    },
    /**
     * @description Tất cả thông tin của tiêu đề hiện tại
     * */
    currentHeader(state) {
      return state.header.find((item) => item.name === state.headerName);
    },
    /**
     * @description Dưới tiêu đề hiện tại, có ẩn sider (và nút gập hay không)）
     * */
    hideSider(state, getters) {
      let visible = false;
      if (getters.currentHeader && 'hideSider' in getters.currentHeader) visible = getters.currentHeader.hideSider;
      return visible;
    },
  },
  mutations: {
    /**
     * @description Thiết lập menu thanh bên
     * @param {Object} state vuex state
     * @param {Array} menu menu
     */
    setSider(state, menu) {
      state.sider = menu;
    },
    /**
     * @description Thiết lập menu thanh bên
     * @param {Object} state vuex state
     * @param {Array} menu menu
     */
    setOpenMenuName(state, menu) {
      state.oneMenuName = menu;
    },
    /**
     * @description Đặt menu thanh trên cùng
     * @param {Object} state vuex state
     * @param {Array} menu menu
     */
    setHeader(state, menu) {
      state.header = menu;
    },
    /**
     * @description Đặt menu thanh trên cùng hiện tại name
     * @param {Object} state vuex state
     * @param {Array} name headerName
     */
    setHeaderName(state, name) {
      state.headerName = name;
    },
    /**
     * @description Đặt đường dẫn của menu hiện tại, dùng để đánh dấu mục hiện tại trong menu thanh bên
     * @param {Object} state vuex state
     * @param {Array} path fullPath
     */
    setActivePath(state, path) {
      state.activePath = path;
    },
    /**
     * @description Đặt bộ sưu tập tên của tất cả các menu cha mở rộng của menu hiện tại
     * @param {Object} state vuex state
     * @param {Array} names openNames
     */
    setOpenNames(state, names) {
      state.openNames = names;
    },
  },
};
