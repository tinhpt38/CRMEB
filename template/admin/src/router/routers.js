// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

import index from './modules/index';
import product from './modules/product';
import order from './modules/order';
import user from './modules/user';
// import echarts from './modules/echarts'
import setting from './modules/setting';
import agent from './modules/agent';
import finance from './modules/finance';
import cms from './modules/cms';
import marketing from './modules/marketing';
import app from './modules/app';
import system from './modules/system';
import LayoutMain from '@/layout';
import statistic from './modules/statistic';
import frameOut from './modules/frameOut';
import division from './modules/division';
import settings from '@/setting';
import crud from './modules/crud';

const modulesFiles = require.context('./modules/crud', true, /\.js$/);

const routers = [];
// Thêm thông tin định tuyến được quét vào mảng định tuyến
modulesFiles.keys().forEach((modulePath) => {
  const value = modulesFiles(modulePath);
  routers.push(value.default);
});

let routePre = settings.routePre;
/**
 * Hiển thị trong khung chính
 */

const frameIn = [
  {
    path: '/',
    meta: {
      title: 'CRMEB',
    },
    redirect: {
      name: 'home_index',
    },
    component: LayoutMain,
    children: [
      // {
      //   path: '/admin/system/log',
      //   name: 'log',
      //   meta: {
      //     title: 'Nhật ký giao diện người dùng',
      //     auth: true
      //   },
      //   component: () => import('@/pages/system/log')
      // },
      {
        path: routePre + '/system/user',
        name: `systemUser`,
        meta: {
          auth: true,
          title: 'Trung tâm cá nhân',
        },
        component: () => import('@/pages/setting/user/index'),
      },
      {
        path: routePre + '/system/files',
        name: `systemFiles`,
        meta: {
          auth: ['admin-setting-files'],
          title: 'Quản lý tập tin',
        },
        component: () => import('@/pages/setting/userFile/index'),
      },
      // Làm mới trang và phải giữ nó
      {
        path: 'refresh',
        name: 'refresh',
        hidden: true,
        component: {
          beforeRouteEnter(to, from, next) {
            next((instance) => instance.$router.replace(from.fullPath));
          },
          render: (h) => h(),
        },
      },
      // Chuyển hướng trang phải được giữ lại
      {
        path: 'redirect/:route*',
        name: 'redirect',
        hidden: true,
        component: {
          beforeRouteEnter(to, from, next) {
            next((instance) => instance.$router.replace(JSON.parse(from.params.route)));
          },
          render: (h) => h(),
        },
      },
    ],
  },
  {
    path: routePre,
    meta: {
      title: 'CRMEB',
    },
    redirect: {
      name: 'home_index',
    },
    component: LayoutMain,
  },
  {
    path: routePre + '/widget.images/index.html',
    name: `images`,
    meta: {
      auth: ['admin-user-user-index'],
      title: 'Tải ảnh lên',
    },
    component: () => import('@/components/uploadPictures/widgetImg'),
  },
  {
    path: routePre + '/widget.widgets/icon.html',
    name: `imagesIcon`,
    meta: {
      auth: ['admin-user-user-index'],
      title: 'biểu tượng tải lên',
    },
    component: () => import('@/components/iconFrom/index'),
  },
  {
    path: routePre + '/store.StoreProduct/index.html',
    name: `storeProduct`,
    meta: {
      title: 'Chọn sản phẩm',
    },
    component: () => import('@/components/goodsList/index'),
  },
  {
    path: routePre + '/system.User/list.html',
    name: `changeUser`,
    meta: {
      title: 'Chọn người dùng',
    },
    component: () => import('@/components/customerInfo/index'),
  },
  {
    path: routePre + '/widget.video/index.html',
    name: `video`,
    meta: {
      title: 'Tải video lên',
    },
    component: () => import('@/components/uploadVideo/index'),
  },
  index,
  agent,
  cms,
  product,
  marketing,
  order,
  user,
  finance,
  setting,
  system,
  app,
  statistic,
  division,
  ...routers,
  crud,
];

/**
 * Hiển thị bên ngoài khung chính
 */

const frameOuts = frameOut;

/**
 * trang lỗi
 */

const errorPage = [
  {
    path: routePre + '/403',
    name: '403',
    meta: {
      title: '403',
    },
    component: () => import('@/pages/system/error/403'),
  },
  {
    path: routePre + '/500',
    name: '500',
    meta: {
      title: '500',
    },
    component: () => import('@/pages/system/error/500'),
  },
  {
    path: routePre + '/*',
    name: '404',
    meta: {
      title: '404',
    },
    component: () => import('@/pages/system/error/404'),
  },
];

// Xuất cần hiển thị menu
export const frameInRoutes = frameIn;

// Xuất khẩu sau khi tổ chức lại
export default [...frameIn, ...frameOuts, ...errorPage];
