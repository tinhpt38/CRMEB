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

const pre = 'finance_';
export default {
  path: routePre + '/finance',
  name: 'finance',
  header: 'finance',
  meta: {
    // ID ủy quyền
    auth: ['admin-finance'],
  },
  redirect: {
    name: `${pre}cashApply`,
  },
  component: LayoutMain,
  children: [
    {
      path: 'billing_records/index',
      name: `${pre}billingRecords`,
      meta: {
        auth: ['finance-billing_records-index'],
        title: 'hồ sơ thanh toán',
      },
      component: () => import('@/pages/finance/billingRecords/index'),
    },
    {
      path: 'capital_flow/index',
      name: `${pre}capitalFlow`,
      meta: {
        auth: ['finance-capital_flow-index'],
        title: 'Dòng vốn',
      },
      component: () => import('@/pages/finance/capitalFlow/index'),
    },
    {
      path: 'user_extract/index',
      name: `${pre}cashApply`,
      meta: {
        auth: ['finance-user_extract'],
        title: 'Đơn xin rút tiền',
      },
      component: () => import('@/pages/finance/userExtract/index'),
    },
    {
      path: 'user_recharge/index',
      name: `${pre}recharge`,
      meta: {
        auth: ['finance-user-recharge'],
        title: 'Kỷ lục nạp tiền',
      },
      component: () => import('@/pages/finance/financialRecords/recharge'),
    },
    {
      path: 'finance/bill',
      name: `${pre}bill`,
      meta: {
        auth: ['finance-finance-bill'],
        title: 'Hồ sơ tài trợ',
      },
      component: () => import('@/pages/finance/financialRecords/bill'),
    },
    {
      path: 'finance/commission',
      name: `${pre}commissionRecord`,
      meta: {
        auth: ['finance-finance-commission'],
        title: 'hồ sơ ủy ban',
      },
      component: () => import('@/pages/finance/commission/index'),
    },
    {
      path: 'balance/balance',
      name: `${pre}balance`,
      meta: {
        auth: ['finance-user-balance'],
        title: 'Hồ sơ số dư',
      },
      component: () => import('@/pages/finance/balance/index'),
    },
  ],
};
