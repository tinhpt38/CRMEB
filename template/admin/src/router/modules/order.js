// +---------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +---------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +---------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +---------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +---------------------------------------------------------------------

import LayoutMain from '@/layout';
import setting from '@/setting';
let routePre = setting.routePre;

const pre = 'order_';

export default {
  path: routePre + '/order',
  name: 'order',
  header: 'order',
  redirect: {
    name: `${pre}list`,
  },
  component: LayoutMain,
  children: [
    {
      path: 'list',
      name: `${pre}list`,
      meta: {
        auth: ['admin-order-storeOrder-index'],
        title: 'Quản lý đơn hàng',
      },
      component: () => import('@/pages/order/orderList/index'),
    },
    {
      path: 'offline',
      name: `${pre}offline`,
      meta: {
        auth: ['admin-order-offline'],
        title: 'Thu ngân đặt hàng',
      },
      component: () => import('@/pages/order/offline/index'),
    },
    {
      path: 'refund',
      name: `${pre}refund`,
      meta: {
        auth: ['admin-order-refund'],
        title: 'Đơn hàng sau bán hàng',
      },
      component: () => import('@/pages/order/refund/index'),
    },
    {
      path: 'invoice/list',
      name: `${pre}invoice`,
      meta: {
        auth: ['admin-order-startOrderInvoice-index'],
        title: 'Quản lý hóa đơn',
      },
      component: () => import('@/pages/order/invoice/index'),
    },
  ],
};
