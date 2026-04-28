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

const pre = 'user_';

export default {
  path: routePre + '/user',
  name: 'user',
  header: 'user',
  redirect: {
    name: `${pre}list`,
  },
  meta,
  component: LayoutMain,
  children: [
    {
      path: 'list',
      name: `${pre}list`,
      meta: {
        auth: ['admin-user-user-index'],
        title: 'Quản lý khách hàng',
      },
      component: () => import('@/pages/user/list/index'),
    },
    {
      path: 'level',
      name: `${pre}level`,
      meta: {
        auth: ['user-user-level'],
        footer: true,
        title: 'Hạng khách hàng',
      },
      component: () => import('@/pages/user/level/index'),
    },
    {
      path: 'group',
      name: `${pre}group`,
      meta: {
        auth: ['user-user-group'],
        footer: true,
        title: 'Nhóm khách hàng',
      },
      component: () => import('@/pages/user/group/index'),
    },
    {
      path: 'label',
      name: `${pre}label`,
      meta: {
        auth: ['user-user-label'],
        footer: true,
        title: 'Thẻ khách hàng',
      },
      component: () => import('@/pages/user/label/index'),
    },
    {
      path: 'cancel',
      name: `${pre}cancel`,
      meta: {
        auth: ['user-user-cancel'],
        footer: true,
        title: 'Thẻ khách hàng',
      },
      component: () => import('@/pages/user/cancel/index'),
    },
    {
      path: 'recharge/:id',
      name: `${pre}recharge`,
      meta: {
        auth: ['user-user-recharge'],
        footer: true,
        title: 'Cấu hình nạp tiền',
      },
      component: () => import('@/pages/system/group/list'),
    },
    {
      path: 'grade/type',
      name: `${pre}type`,
      meta: {
        auth: ['admin-user-member-type'],
        footer: true,
        title: 'Loại thành viên',
      },
      component: () => import('@/pages/user/grade/type/index'),
    },
    {
      path: 'grade/card',
      name: `${pre}card`,
      meta: {
        auth: ['admin-user-grade-card'],
        footer: true,
        title: 'Thành viên thẻ',
      },
      component: () => import('@/pages/user/grade/card/index'),
    },
    {
      path: 'grade/record',
      name: `${pre}record`,
      meta: {
        auth: ['admin-user-grade-record'],
        footer: true,
        title: 'Hồ sơ thành viên',
      },
      component: () => import('@/pages/user/grade/record/index'),
    },
    {
      path: 'grade/right',
      name: `${pre}right`,
      meta: {
        auth: ['admin-user-grade-right'],
        footer: true,
        title: 'Quyền thành viên',
      },
      component: () => import('@/pages/user/grade/right/index'),
    },
    {
      path: 'grade/list/:id',
      name: `${pre}gradelist`,
      meta: {
        auth: ['user-member_card-index'],
        footer: true,
        title: 'Danh sách thẻ thành viên',
      },
      component: () => import('@/pages/user/grade/card/list'),
    },
    {
      path: 'grade/agreement',
      name: `${pre}agreement`,
      meta: {
        auth: ['admin-user-grade-agreement'],
        footer: true,
        title: 'Thỏa thuận thành viên',
      },
      component: () => import('@/pages/user/grade/agreement/index'),
    },
  ],
};
