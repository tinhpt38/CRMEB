// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

import Vue from 'vue';
import Router from 'vue-router';
import routes from './routers';
import Setting from '@/setting';
import store from '@/store';
import { removeCookies, getCookies, setTitle } from '@/libs/util';
import { includeArray } from '@/libs/auth';
import { PrevLoading } from '@/utils/loading.js';

Vue.use(Router);
// gỡ rối `element ui` Xảy ra lỗi khi bấm liên tục vào menu trên thanh điều hướng
const originalPush = Router.prototype.push;
Router.prototype.push = function push(location) {
  return originalPush.call(this, location).catch((err) => Err);
};

const originalReplace = Router.prototype.replace;
Router.prototype.replace = function replace(location) {
  return originalReplace.call(this, location).catch((err) => err);
};

const router = new Router({
  routes,
  mode: Setting.routerMode,
});

// Xác định xem meta.roles định tuyến có chứa trường quyền của người dùng đã đăng nhập hiện tại hay không
export function hasAuth(roles, route) {
  if (route.meta && route.meta.auth) return roles.some((role) => route.meta.auth.includes(role));
  else return true;
}

// Lọc đệ quy các tuyến đường được ủy quyền
export function setFilterMenuFun(routes, role) {
  const menu = [];
  routes.forEach((route) => {
    const item = { ...route };
    if (hasAuth(role, item)) {
      if (item.children) item.children = setFilterMenuFun(item.children, role);
      menu.push(item);
    }
  });
  return menu;
}

// Xử lý dự phòng đệ quy layout : <router-view>，Giữ các thành phần cần được truy cập trong lớp bố cục đầu tiên.
// bởi vì `keep-alive` Chỉ các tuyến phụ mới có thể được lưu vào bộ nhớ đệm
// Được thực thi trong quá trình khởi tạo theo mặc định
export function keepAliveSplice(to) {
  if (to.matched && to.matched.length > 2) {
    to.matched.map((v, k) => {
      if (v.components.default instanceof Function) {
        v.components.default().then((components) => {
          if (components.default.name === 'parent') {
            to.matched.splice(k, 1);
            router.push({ path: to.path, query: to.query });
            keepAliveSplice(to);
          }
        });
      } else {
        if (v.components.default.name === 'parent') {
          to.matched.splice(k, 1);
          keepAliveSplice(to);
        }
      }
    });
  }
}

// Chỉnh sửa mô-đun
export function editRouterFun(to, from) {
  const onRoutes = to.meta.activeMenu ? to.meta.activeMenu : to.meta.path;
  store.commit('menu/setActivePath', onRoutes);
  if (to.name == 'crud_crud') {
    store.state.menus.oneLvRoutes.map((e) => {
      if (e.path === to.path) {
        to.meta.title = e.title;
      }
    });
  }
  if (
    [
      'product_productAdd',
      'marketing_bargainCreate',
      'marketing_storeSeckillCreate',
      'marketing_storeIntegralCreate',
      'marketing_storeCouponCreate',
    ].includes(to.name)
  ) {
    let route = to.matched[1].path.split(':')[0];
    store.state.menus.oneLvRoutes.map((e) => {
      if (route.indexOf(e.path) != -1) {
        to.meta.title = `${to.params.id ? e.title + 'ID: ' + to.params.id : 'Thêm mới' + e.title}`;
      }
    });
  }
}

// Trì hoãn thanh tiến trình đóng
export function delayNProgressDone(time = 300) {
  setTimeout(() => {
    NProgress.done();
  }, time);
}

/**
 * Chặn tuyến đường
 * Xác minh quyền
 */

router.beforeEach(async (to, from, next) => {
  // PrevLoading.start();
  keepAliveSplice(to);
  editRouterFun(to, from);
  if (to.fullPath.indexOf('kefu') != -1 || to.name == 'mobile_upload') {
    return next();
  }
  // Xác định xem bạn có cần đăng nhập trước khi vào không
  if (to.matched.some((_) => _.meta.auth)) {
    // Tại đây, người ta đánh giá xem có nên đăng nhập hay không dựa trên mã thông báo, mã thông báo này có thể được sửa đổi tùy theo tình huống.
    const token = getCookies('token');
    if (token && token !== 'undefined') {
      const access = store.state.userInfo.uniqueAuth;
      const isPermission = includeArray(to.meta.auth, access); //  Xác định xem có sự cho phép hay không  TODO
      if (access.length) {
        next();
      } else {
        if (access.length == 0) {
          next({
            name: 'login',
            query: {
              redirect: to.fullPath,
            },
          });
          localStorage.clear();
          removeCookies('token');
          removeCookies('expires_time');
          removeCookies('uuid');
        } else {
          next({
            name: '403',
          });
        }
      }
      // next();
    } else {
      // Chuyển sang giao diện đăng nhập khi chưa đăng nhập
      // Mang đường dẫn đầy đủ đến trang cần chuyển hướng sau khi đăng nhập thành công.
      next({
        name: 'login',
        query: {
          redirect: to.fullPath,
        },
      });
      localStorage.clear();
      removeCookies('token');
      removeCookies('expires_time');
      removeCookies('uuid');
    }
  } else {
    // Không cần xác minh danh tính, chuyển trực tiếp
    next();
  }
});
router.afterEach((to) => {
  // Thay đổi tiêu đề
  setTitle(to, router.app);
  // Quay lại đầu trang
  window.scrollTo(0, 0);
  PrevLoading.done();
});
export default router;
