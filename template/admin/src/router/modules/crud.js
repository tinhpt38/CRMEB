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

const pre = 'crud_';

export default {
  path: routePre + '/crud',
  name: 'crud',
  header: 'crud',
  redirect: {
    name: `${pre}crud`,
  },
  meta: {
    auth: true,
  },
  component: LayoutMain,
  children: [
    {
      path: ':table_name',
      name: `${pre}crud`,
      meta: {
        auth: true,
        title: 'Thêm, xóa, sửa đổi và kiểm tra',
      },
      component: () => import('@/pages/crud/index'),
    },
  ],
};
