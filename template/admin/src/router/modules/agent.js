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

const pre = 'agent_';
const meta = {
  auth: true,
};
export default {
  path: `${routePre}/agent`,
  name: 'agent',
  header: 'agent',
  redirect: {
    name: `${pre}agentManage`,
  },
  meta,
  component: LayoutMain,
  children: [
    {
      path: 'agent_manage/index',
      name: `${pre}agentManage`,
      meta: {
        auth: ['agent-agent-manage'],
        title: 'Quản lý nhà phân phối',
      },
      component: () => import('@/pages/agent/agentManage'),
    },
    {
      path: 'spread/apply',
      name: `${pre}agentManage`,
      meta: {
        auth: ['admin-agent-spread-apply'],
        title: 'Ứng dụng phân phối',
      },
      component: () => import('@/pages/agent/spread/apply'),
    },
  ],
};
