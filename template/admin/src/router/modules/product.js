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

const pre = 'product_';

export default {
  path: routePre + '/product',
  name: 'product',
  header: 'product',
  meta: {
    title: 'hàng hóa',
    // ID ủy quyền
    auth: ['admin-store-index'],
  },
  redirect: {
    name: `${pre}productList`,
  },
  component: LayoutMain,
  children: [
    {
      path: 'product_list',
      name: `${pre}productList`,
      meta: {
        title: 'Quản lý sản phẩm',
        auth: ['admin-store-storeProuduct-index'],
        keepAlive: true,
      },
      component: () => import('@/pages/product/productList'),
    },
    {
      path: 'product_classify',
      name: `${pre}productClassify`,
      meta: {
        title: 'Phân loại sản phẩm',
        auth: ['admin-store-storeCategory-index'],
      },
      component: () => import('@/pages/product/productClassify'),
    },
    {
      path: 'add_product/:id?',
      name: `${pre}productAdd`,
      meta: {
        auth: ['admin-store-storeProuduct-index'],
        title: 'Bổ sung sản phẩm',
        activeMenu: routePre + '/product/product_list',
      },
      component: () => import('@/pages/product/productAdd'),
    },
    {
      path: 'product_reply/:id?',
      name: `${pre}productEvaluate`,
      meta: {
        auth: ['admin-store-storeProuduct-index'],
        title: 'đánh giá sản phẩm',
      },
      component: () => import('@/pages/product/productReply'),
    },
    {
      path: 'product_attr',
      name: `${pre}productAttr`,
      meta: {
        auth: ['admin-store-storeProuduct-index'],
        title: 'Thông số sản phẩm',
      },
      component: () => import('@/pages/product/productAttr'),
    },
    {
      path: 'param/list',
      name: `${pre}paramList`,
      meta: {
        auth: ['admin-product-param-list'],
        title: 'Thông số sản phẩm',
      },
      component: () => import('@/pages/product/paramList'),
    },
    {
      path: 'label/list',
      name: `${pre}labelList`,
      meta: {
        auth: ['admin-product-label-list'],
        title: 'Thẻ sản phẩm',
      },
      component: () => import('@/pages/product/labelList'),
    },
    {
      path: 'protection/list',
      name: `${pre}labelList`,
      meta: {
        auth: ['admin-product-protection-list'],
        title: 'Bảo vệ sản phẩm',
      },
      component: () => import('@/pages/product/protectionList'),
    },
  ],
};
