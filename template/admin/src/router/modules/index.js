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

const pre = 'home_';

export default {
  path: routePre + '/home',
  name: 'home',
  header: 'home',
  redirect: {
    name: `${pre}index`,
  },
  meta,
  component: LayoutMain,
  children: [
    {
      path: routePre + '/index',
      name: `${pre}index`,
      header: 'home',
      meta: {
        auth: ['admin-index-index'],
        title: 'Trang chủ',
        isAffix: false,
      },
      component: () => import('@/pages/index/index'),
    },
  ],
};
