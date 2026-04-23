/*
 * @Author: From-wh from-wh@hotmail.com
 * @Date: 2023-02-21 09:14:27
 * @FilePath: /admin/src/router/modules/echarts.js
 * @Description:
 */
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

const pre = 'echarts_';

export default {
  path: routePre + '/echarts',
  name: 'echarts',
  header: 'echarts',
  redirect: {
    name: `${pre}/trade/order`,
  },
  component: LayoutMain,
  children: [
    // {
    //   path: 'trade/order',
    //   name: `${pre}/trade/order`,
    //   meta: {
    //     auth: ['admin-order-storeOrder-index'],
    //     title: 'Thống kê giao dịch',
    //   },
    //   component: () => import('@/pages/echarts/trade/order'),
    // },
    // {
    //   path: 'trade/product',
    //   name: `${pre}/trade/product`,
    //   meta: {
    //     auth: ['admin-order-storeOrder-index'],
    //     title: 'Thống kê sản phẩm',
    //   },
    //   component: () => import('@/pages/echarts/trade/product'),
    // },
  ],
};
