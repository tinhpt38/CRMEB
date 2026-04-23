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

const pre = 'division_';
const meta = {
  auth: true,
};
export default {
  path: routePre + '/division',
  name: 'division',
  header: 'division',
  redirect: {
    name: `${pre}division`,
  },
  meta,
  component: LayoutMain,
  children: [
    {
      path: 'index',
      name: `${pre}division`,
      meta: {
        auth: ['agent-division-index'],
        title: 'Danh sách đơn vị kinh doanh',
      },
      component: () => import('@/pages/division/list/index'),
    },
    
    {
      path: 'agent/index',
      name: `${pre}agent`,
      meta: {
        auth: ['agent-division-agent-index'],
        title: 'Danh sách đại lý',
      },
      component: () => import('@/pages/division/agent/index'),
    },
    {
      path: 'agent/statistics',
      name: `${pre}agent`,
      meta: {
        auth: ['agent-division-statistics'],
        title: 'Thống kê đơn vị kinh doanh',
      },
      component: () => import('@/pages/division/agent/statistics'),
    },
    {
      path: 'agent/applyList',
      name: `${pre}agent`,
      meta: {
        auth: ['agent-division-agent-applyList'],
        title: 'Ứng dụng đại lý',
      },
      component: () => import('@/pages/division/agent/applyList'),
    },
    {
      path: 'agent/agreement',
      name: `${pre}agent`,
      meta: {
        auth: ['agent-division-agent-agreement'],
        title: 'Nội quy đại lý',
      },
      component: () => import('@/pages/division/agent/agreement'),
    },
  ],
};
