// +---------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +---------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +---------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
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
    title: 'router.product.root',
    // 授权标识
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
        title: 'router.product.manage',
        auth: ['admin-store-storeProuduct-index'],
        keepAlive: true,
      },
      component: () => import('@/pages/product/productList'),
    },
    {
      path: 'product_classify',
      name: `${pre}productClassify`,
      meta: {
        title: 'router.product.category',
        auth: ['admin-store-storeCategory-index'],
      },
      component: () => import('@/pages/product/productClassify'),
    },
    {
      path: 'add_product/:id?',
      name: `${pre}productAdd`,
      meta: {
        auth: ['admin-store-storeProuduct-index'],
        title: 'router.product.add',
        activeMenu: routePre + '/product/product_list',
      },
      component: () => import('@/pages/product/productAdd'),
    },
    {
      path: 'product_reply/:id?',
      name: `${pre}productEvaluate`,
      meta: {
        auth: ['admin-store-storeProuduct-index'],
        title: 'router.product.comment',
      },
      component: () => import('@/pages/product/productReply'),
    },
    {
      path: 'product_attr',
      name: `${pre}productAttr`,
      meta: {
        auth: ['admin-store-storeProuduct-index'],
        title: 'router.product.spec',
      },
      component: () => import('@/pages/product/productAttr'),
    },
    {
      path: 'param/list',
      name: `${pre}paramList`,
      meta: {
        auth: ['admin-product-param-list'],
        title: 'router.product.param',
      },
      component: () => import('@/pages/product/paramList'),
    },
    {
      path: 'label/list',
      name: `${pre}labelList`,
      meta: {
        auth: ['admin-product-label-list'],
        title: 'router.product.label',
      },
      component: () => import('@/pages/product/labelList'),
    },
    {
      path: 'protection/list',
      name: `${pre}labelList`,
      meta: {
        auth: ['admin-product-protection-list'],
        title: 'router.product.protection',
      },
      component: () => import('@/pages/product/protectionList'),
    },
  ],
};
