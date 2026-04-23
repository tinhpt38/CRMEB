// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

import { AccountLogoutKefu } from '@/api/kefu';
import { getCookies, removeCookies, setCookies } from '@/libs/util';
import router from '@/router';
import { Socket } from '@/libs/socket';
export default {
  namespaced: true,
  state: {
    kefuInfo: null,
  },
  mutations: {
    setInfo(state, val) {
      state.kefuInfo = val;
    },
  },
  actions: {
    /**
     * @description Đăng xuất
     * */
    logoutKefu({ commit, dispatch }, { confirm = false, vm } = {}) {
      async function logout() {
        AccountLogoutKefu()
          .then(() => {
            Socket.then((ws) => {
              ws.send({
                type: 'logout',
                data: { uid: getCookies('kefu_uuid') },
              });
            });
            // localStorage.clear();
            removeCookies('kefu_token');
            removeCookies('kefu_expires_time');
            removeCookies('kefuInfo');
            removeCookies('kefu_uuid');
            // Xóa bộ nhớ cục bộ
            // Xóa thông tin người dùng vuex
            // Tuyến đường nhảy
            router.push({
              path: '/kefu',
            });
          })
          .catch((res) => {
            console.log(res);
          });
      }
      logout();
    },
  },
};
