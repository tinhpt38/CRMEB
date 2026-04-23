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

const pre = 'system_';

export default {
  path: routePre + '/system',
  name: 'system',
  header: 'system',
  redirect: {
    name: `${pre}configTab`,
  },
  meta: {
    auth: ['admin-system'],
  },
  component: LayoutMain,
  children: [
    {
      path: 'code_generation',
      name: `${pre}code_generation`,
      meta: {
        auth: ['system-config-code-generation'],
        title: 'tạo mã',
        activeMenu: routePre + '/system/code_generation_list',
      },
      component: () => import('@/pages/system/codeGeneration/index'),
    },
    {
      path: 'code_data_dictionary',
      name: `${pre}code_data_dictionary`,
      meta: {
        auth: ['system-code-data_dictionary'],
        title: 'từ điển dữ liệu',
        activeMenu: routePre + '/system/code_data_dictionary',
      },
      component: () => import('@/pages/system/codeDataDictionary/index'),
    },
    {
      path: 'code_data_dictionary_datalist',
      name: `${pre}code_data_dictionary_datalist`,
      meta: {
        auth: ['system-code-data_dictionary-dataList'],
        title: 'Trang quản lý dữ liệu',
        activeMenu: routePre + '/system/code_data_dictionary',
      },
      component: () => import('@/pages/system/codeDataDictionary/dataList'),
    },
    {
      path: 'code_generation_list',
      name: `${pre}code_generation_list`,
      meta: {
        auth: ['system-config-code-generation-list'],
        title: 'danh sách tạo mã',
      },
      component: () => import('@/pages/system/codeGeneration/list'),
    },
    {
      path: 'backend_routing',
      name: `${pre}backend_routing`,
      meta: {
        auth: ['system-config-backend-routing'],
        title: 'Quản lý giao diện',
      },
      component: () => import('@/pages/system/backendRouting/index'),
    },
    {
      path: 'file',
      name: `${pre}file`,
      meta: {
        auth: ['system-file'],
        title: 'Quản lý tệp đính kèm',
      },
      component: () => import('@/pages/system/file/index'),
    },
    {
      path: 'maintain/clear/index',
      name: `${pre}clear`,
      meta: {
        auth: ['system-clear'],
        title: 'làm mới bộ đệm',
      },
      component: () => import('@/pages/system/clear/index'),
    },
    {
      path: 'maintain/system_log/index',
      name: `${pre}systemLog`,
      meta: {
        auth: ['system-maintain-system-log'],
        title: 'Nhật ký hệ thống',
      },
      component: () => import('@/pages/system/maintain/systemLog/index'),
    },
    {
      path: 'maintain/system_file/index',
      name: `${pre}systemFile`,
      meta: {
        auth: ['system-maintain-system-file'],
        title: 'Xác minh tập tin',
      },
      component: () => import('@/pages/system/maintain/systemFile/index'),
    },
    {
      path: 'maintain/system_cleardata/index',
      name: `${pre}systemCleardata`,
      meta: {
        auth: ['system-maintain-system-cleardata'],
        title: 'xóa dữ liệu',
      },
      component: () => import('@/pages/system/maintain/systemCleardata/index'),
    },
    {
      path: 'maintain/system_databackup/index',
      name: `${pre}systemDatabackup`,
      meta: {
        auth: ['system-maintain-system-databackup'],
        title: 'Sao lưu dữ liệu',
      },
      component: () => import('@/pages/system/maintain/systemDatabackup/index'),
    },
    {
      path: 'maintain/system_file/opendir',
      name: `${pre}opendir`,
      meta: {
        auth: ['system-maintain-system-file'],
        title: 'Quản lý tập tin',
      },
      component: () => import('@/pages/system/maintain/systemFile/opendir'),
    },
    {
      path: 'maintain/system_file/login',
      name: `${pre}opendir_login`,
      meta: {
        auth: ['system-maintain-system-file'],
        title: 'Cổng quản lý tập tin',
        activeMenu: routePre + '/system/maintain/system_file/opendir',
      },
      component: () => import('@/pages/system/maintain/systemFile/login'),
    },
    {
      path: 'config/system_config_tab/index',
      name: `${pre}configTab`,
      meta: {
        auth: ['system-config-system_config-tab'],
        title: 'Phân loại cấu hình',
      },
      component: () => import('@/pages/system/configTab/index'),
    },
    {
      path: 'config/system_config_tab/list/:id?',
      name: `${pre}configTabList`,
      meta: {
        auth: ['system-config-system_config_tab-list'],
        title: 'Danh sách cấu hình',
        activeMenu: routePre + '/system/config/system_config_tab/index',
      },
      component: () => import('@/pages/system/configTab/list'),
    },
    {
      path: 'config/system_group/index',
      name: `${pre}group`,
      meta: {
        auth: ['system-config-system_config-group'],
        title: 'Dữ liệu kết hợp',
      },
      component: () => import('@/pages/system/group/index'),
    },
    {
      path: 'config/system_group/list/:id?',
      name: `${pre}groupList`,
      meta: {
        auth: ['system-config-system_config-list'],
        title: 'Danh sách dữ liệu kết hợp',
        activeMenu: routePre + '/system/config/system_group/index',
      },
      component: () => import('@/pages/system/group/list'),
    },
    {
      path: 'maintain/auth',
      name: `${pre}auth`,
      meta: {
        auth: ['system-maintain-auth'],
        title: 'ủy quyền thương mại',
      },
      component: () => import('@/pages/system/auth/index'),
    },
    {
      path: 'onlineUpgrade/index',
      name: `${pre}upgradeclient`,
      meta: {
        auth: ['system-onlineUpgrade-index'],
        title: 'Nâng cấp trực tuyến',
      },
      component: () => import('@/pages/system/onlineUpgrade/index'),
    },
    {
      path: 'crossVersionUpgrade/index',
      name: `${pre}crossVersionUpgrade`,
      meta: {
        auth: ['system-crossVersionUpgrade-index'],
        title: 'Nâng cấp nhiều phiên bản',
      },
      component: () => import('@/pages/system/crossVersionUpgrade/index'),
    },
    {
      path: 'crontab',
      name: `${pre}crontab`,
      meta: {
        auth: ['system-crontab-index'],
        title: 'nhiệm vụ theo lịch trình',
      },
      component: () => import('@/pages/system/crontab/index'),
    },
    {
      path: 'event',
      name: `${pre}event`,
      meta: {
        auth: ['system-event-index'],
        title: 'Sự kiện tùy chỉnh',
      },
      component: () => import('@/pages/system/event/index'),
    },
    {
      path: 'system_menus/index',
      name: `${pre}systemMenus`,
      meta: {
        auth: ['system-system-menus'],
        title: 'Quy tắc cấp phép',
      },
      component: () => import('@/pages/system/systemMenus/index'),
    },
  ],
};
