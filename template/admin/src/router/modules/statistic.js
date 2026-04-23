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

const meta = {
  auth: true,
};

const pre = 'statistic_';

export default {
  path: routePre + '/statistic',
  name: 'statistic',
  header: 'statistic',
  redirect: {
    name: `${pre}product`,
  },
  component: LayoutMain,
  children: [
    {
      path: 'product',
      name: `${pre}product`,
      meta: {
        // auth: ['setting-system-role'],
        title: 'Thống kê sản phẩm',
      },
      component: () => import('@/pages/statistic/product/index'),
    },
    {
      path: 'user',
      name: `${pre}user`,
      meta: {
        // auth: ['setting-system-role'],
        title: 'Thống kê người dùng',
      },
      component: () => import('@/pages/statistic/user/index'),
    },
    {
      path: 'transaction',
      name: `${pre}transaction`,
      meta: {
        // auth: ['setting-system-role'],
        title: 'Thống kê giao dịch',
      },
      component: () => import('@/pages/statistic/transaction/index'),
    },
    {
      path: 'integral',
      name: `${pre}integral`,
      meta: {
        // auth: ['setting-system-role'],
        title: 'Thống kê điểm',
      },
      component: () => import('@/pages/statistic/integral/index'),
    },
    {
      path: 'order',
      name: `${pre}order`,
      meta: {
        // auth: ['setting-system-role'],
        title: 'Thống kê đơn hàng',
      },
      component: () => import('@/pages/statistic/order/index'),
    },
    {
      path: 'balance',
      name: `${pre}balance`,
      meta: {
        // auth: ['setting-system-role'],
        title: 'Thống kê số dư',
      },
      component: () => import('@/pages/statistic/balance/index'),
    },
  ],
};
