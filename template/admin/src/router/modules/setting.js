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

const meta = {
  auth: true,
};

const pre = 'setting_';

export default {
  path: routePre + '/setting',
  name: 'setting',
  header: 'setting',
  redirect: {
    name: `${pre}setSystem`,
  },
  component: LayoutMain,
  children: [
    {
      path: 'system_role/index',
      name: `${pre}systemRole`,
      meta: {
        auth: ['setting-system-role'],
        title: 'message.router.systemRole',
      },
      component: () => import('@/pages/setting/systemRole/index'),
    },
    {
      path: 'system_admin/index',
      name: `${pre}systemAdmin`,
      meta: {
        auth: ['setting-system-list'],
        title: 'message.router.systemAdmin',
      },
      component: () => import('@/pages/setting/systemAdmin/index'),
    },
    {
      path: 'system_menus/index',
      name: `${pre}systemMenus`,
      meta: {
        auth: ['setting-system-menus'],
        title: 'message.router.systemMenus',
      },
      component: () => import('@/pages/setting/systemMenus/index'),
    },
    {
      path: 'system_config',
      name: `${pre}setSystem`,
      meta: {
        auth: ['setting-system-config'],
        title: 'message.router.system',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'system_config/:type?/:tab_id?',
      name: `${pre}setApp`,
      meta: {
        title: 'message.router.system',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'system_config_retail/:type?/:tab_id?',
      name: `${pre}distributionSet`,
      meta: {
        ...meta,
        title: 'message.router.distributionConfig',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'membership_level/index',
      name: `${pre}membershipLevel`,
      meta: {
        ...meta,
        title: 'message.router.membershipLevel',
      },
      component: () => import('@/pages/setting/membershipLevel/index'),
    },
    {
      path: 'system_config_message/:type?/:tab_id?',
      name: `${pre}message`,
      meta: {
        auth: ['setting-system-config-message'],
        title: 'message.router.smsSwitch',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'notification/index',
      name: `${pre}notification`,
      meta: {
        auth: ['setting-notification'],
        title: 'message.router.notification',
      },
      component: () => import('@/pages/setting/notification/index'),
    },
    {
      path: 'notification/notificationEdit',
      name: `${pre}notificationEdit`,
      meta: {
        auth: ['setting-notification'],
        title: 'message.router.notificationEdit',
        activeMenu: routePre + '/setting/notification/index',
      },
      component: () => import('@/pages/setting/notification/notificationEdit'),
    },
    {
      path: 'system_config_logistics/:type?/:tab_id?',
      name: `${pre}logistics`,
      meta: {
        auth: ['setting-system-config-logistics'],
        title: 'message.router.logisticsConfig',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'sms/sms_config/index',
      name: `${pre}config`,
      meta: {
        auth: ['setting-sms-sms-config'],
        title: 'message.router.smsConfig',
      },
      component: () => import('@/pages/notify/smsConfig/index'),
    },
    {
      path: 'elec_invoice',
      name: `${pre}elec_invoice`,
      meta: {
        auth: ['setting-elec_invoice'],
        title: 'message.router.elecInvoice',
      },
      component: () => import('@/pages/notify/smsConfig/elecInvoice'),
    },
    {
      path: 'sms/sms_template_apply/index',
      name: `${pre}smsTemplateApply`,
      meta: {
        auth: ['setting-sms-config-template'],
        title: 'message.router.smsTemplate',
      },
      component: () => import('@/pages/notify/smsTemplateApply/index'),
    },
    {
      path: 'sms/sms_pay/index',
      name: `${pre}smsPay`,
      meta: {
        auth: ['setting-sms-sms-template'],
        title: 'message.router.smsPay',
      },
      component: () => import('@/pages/notify/smsPay/index'),
    },
    {
      path: 'sms/sms_template_apply/commons',
      name: `${pre}commons`,
      meta: {
        ...meta,
        title: 'message.router.smsTemplateCommon',
      },
      component: () => import('@/pages/notify/smsTemplateApply/index'),
    },
    {
      path: 'system_group_data/index/:id',
      name: `${pre}groupDataIndex`,
      meta: {
        auth: ['setting-system-group_data-index'],
        title: 'message.router.groupDataIndex',
      },
      component: () => import('@/pages/system/group/list'),
    },
    {
      path: 'system_group_data/slide/:id',
      name: `${pre}groupDataSlide`,
      meta: {
        auth: ['setting-system-group_data-slide'],
        title: 'message.router.groupDataSlide',
      },
      component: () => import('@/pages/system/group/list'),
    },
    {
      path: 'system_group_data/sign/:id',
      name: `${pre}groupDataSign`,
      meta: {
        auth: ['setting-system-group_data-sign'],
        title: 'message.router.groupDataSign',
      },
      component: () => import('@/pages/system/group/list'),
    },
    // {
    //   path: 'system_group_data/order/:id',
    //   name: `${pre}groupDataOrder`,
    //   meta: {
    //     auth: ['setting-system-group_data-order'],
    //     title: '订单详情动态图'
    //   },
    //   component: () => import('@/pages/system/group/list')
    // },
    // {
    //   path: 'system_group_data/user/:id',
    //   name: `${pre}groupDataUser`,
    //   meta: {
    //     auth: ['setting-system-group_data-user'],
    //     title: '个人中心菜单'
    //   },
    //   component: () => import('@/pages/system/group/list')
    // },
    {
      path: 'system_group_data/new/:id',
      name: `${pre}groupDataNew`,
      meta: {
        auth: ['setting-system-group_data-new'],
        title: 'message.router.groupDataNew',
      },
      component: () => import('@/pages/system/group/list'),
    },
    {
      path: 'system_group_data/search/:id',
      name: `${pre}groupDataNew`,
      meta: {
        auth: ['setting-system-group_data-search'],
        title: 'message.router.groupDataSearch',
      },
      component: () => import('@/pages/system/group/list'),
    },
    {
      path: 'system_group_data/hot/:id',
      name: `${pre}groupDataHot`,
      meta: {
        auth: ['setting-system-group_data-hot'],
        title: 'message.router.groupDataHot',
      },
      component: () => import('@/pages/system/group/list'),
    },
    {
      path: 'system_group_data/new_product/:id',
      name: `${pre}groupDataNewProduct`,
      meta: {
        auth: ['setting-system-group_data-new_product'],
        title: 'message.router.groupDataNewProduct',
      },
      component: () => import('@/pages/system/group/list'),
    },
    {
      path: 'system_group_data/promotion/:id',
      name: `${pre}groupDataPromotion`,
      meta: {
        auth: ['setting-system-group_data-promotion'],
        title: 'message.router.groupDataPromotion',
      },
      component: () => import('@/pages/system/group/list'),
    },
    {
      path: 'system_group_data/poster/:id',
      name: `${pre}groupDataPoster`,
      meta: {
        auth: ['setting-system-group_data-poster'],
        title: 'message.router.groupDataPoster',
      },
      component: () => import('@/pages/system/group/list'),
    },
    {
      path: 'system_group_data/best/:id',
      name: `${pre}groupDataBest`,
      meta: {
        auth: ['setting-system-group_data-best'],
        title: 'message.router.groupDataBest',
      },
      component: () => import('@/pages/system/group/list'),
    },
    {
      path: 'system_group_data/activity/:id',
      name: `${pre}groupDataActivity`,
      meta: {
        auth: ['setting-system-group_data-activity'],
        title: 'message.router.groupDataActivity',
      },
      component: () => import('@/pages/system/group/list'),
    },
    {
      path: 'system_group_data/system/:id',
      name: `${pre}groupDataSystem`,
      meta: {
        auth: ['setting-system-group_data-system'],
        title: 'message.router.groupDataSystem',
      },
      component: () => import('@/pages/system/group/list'),
    },
    {
      path: 'system_group_data/hot_money/:id',
      name: `${pre}groupDataHotMoney`,
      meta: {
        auth: ['admin-setting-system_group_data-hot_money'],
        title: 'message.router.groupDataHotMoney',
      },
      component: () => import('@/pages/system/group/list'),
    },
    {
      path: 'merchant/system_store/index',
      name: `${pre}systemStore`,
      meta: {
        auth: ['setting-system-config-merchant'],
        title: 'message.router.systemStore',
      },
      component: () => import('@/pages/setting/systemStore/index'),
    },
    {
      path: 'freight/express/index',
      name: `${pre}freight`,
      meta: {
        auth: ['setting-freight-express'],
        title: 'message.router.freight',
      },
      component: () => import('@/pages/setting/freight/index'),
    },
    {
      path: 'store_service/index',
      name: `${pre}service`,
      meta: {
        auth: ['setting-store-service'],
        title: 'message.router.storeService',
      },
      component: () => import('@/pages/setting/storeService/index'),
    },
    {
      path: 'freight/city/list',
      name: `${pre}dada`,
      meta: {
        auth: ['setting-system-city'],
        title: 'message.router.cityDada',
      },
      component: () => import('@/pages/setting/cityDada/index'),
    },
    {
      path: 'freight/shipping_templates/list',
      name: `${pre}templates`,
      meta: {
        auth: ['setting-shipping-templates'],
        title: 'message.router.shippingTemplates',
      },
      component: () => import('@/pages/setting/shippingTemplates/index'),
    },
    {
      path: 'merchant/system_store/list',
      name: `${pre}store`,
      meta: {
        auth: ['setting-merchant-system-store'],
        title: 'message.router.storeList',
      },
      component: () => import('@/pages/setting/storeList/index'),
    },
    {
      path: 'merchant/system_store_staff/index',
      name: `${pre}staff`,
      meta: {
        auth: ['setting-merchant-system-store-staff'],
        title: 'message.router.clerkList',
      },
      component: () => import('@/pages/setting/clerkList/index'),
    },
    {
      path: 'merchant/system_verify_order/index',
      name: `${pre}order`,
      meta: {
        auth: ['setting-merchant-system-verify-order'],
        title: 'message.router.verifyOrder',
      },
      component: () => import('@/pages/setting/verifyOrder/index'),
    },
    {
      path: 'theme_style',
      name: `${pre}themeStyle`,
      meta: {
        auth: ['admin-setting-theme_style'],
        title: 'message.router.themeStyle',
      },
      component: () => import('@/pages/setting/themeStyle/index'),
    },
    {
      path: 'theme/micro_page',
      name: `${pre}microPage`,
      meta: {
        auth: ['setting-theme-micro_page'],
        title: 'message.router.microPage',
      },
      component: () => import('@/pages/setting/theme/micro_page/index'),
    },
    {
      path: 'pages',
      name: `${pre}page`,
      header: 'setting',
      redirect: {
        name: `${pre}devise`,
      },
    },
    {
      path: 'pages/devise/:type',
      name: `${pre}devise`,
      meta: {
        auth: ['admin-setting-pages-devise'],
        title: 'message.router.devise',
      },
      component: () => import('@/pages/setting/devise/list'),
    },
    {
      path: 'pages/user_page/:type',
      name: `${pre}user`,
      meta: {
        auth: ['admin-setting-pages-user'],
        title: 'message.router.user',
      },
      component: () => import('@/pages/setting/devise/list'),
    },
    {
      path: 'pages/link',
      name: `${pre}link`,
      meta: {
        auth: ['admin-setting-pages-link'],
        title: 'message.router.link',
      },
      component: () => import('@/pages/setting/link'),
    },
    {
      path: 'pages/cate_page/:type',
      name: `${pre}cate`,
      meta: {
        auth: ['admin-setting-pages-cate'],
        title: 'message.router.cate',
      },
      component: () => import('@/pages/setting/devise/list'),
    },
    {
      path: 'pages/diy',
      name: `${pre}diy`,
      meta: {
        auth: ['admin-setting-pages-diy'],
        title: 'message.router.diy',
        activeMenu: routePre + '/setting/pages/devise',
      },
      component: () => import('@/pages/setting/devisePage/index'),
    },
    {
      path: 'pages/diy_index',
      name: `${pre}index_diy`,
      meta: {
        auth: ['admin-setting-pages-diy'],
        title: 'message.router.index_diy',
        fullScreen: true, //是否全屏显示main区域
      },
      component: () => import('@/pages/setting/devise/diyIndex'),
    },
    {
      path: 'pages/links',
      name: `${pre}links`,
      meta: {
        auth: ['admin-setting-pages-links'],
        title: 'message.router.links',
      },
      component: () => import('@/pages/setting/devise/links'),
    },
    {
      path: 'store_service/speechcraft',
      name: `${pre}speechcraft`,
      meta: {
        auth: ['admin-setting-store_service-speechcraft'],
        title: 'message.router.speechcraft',
      },
      component: () => import('@/pages/setting/storeService/speechcraft'),
    },
    {
      path: 'store_service/feedback',
      name: `${pre}feedback`,
      meta: {
        auth: ['admin-setting-store_service-feedback'],
        title: 'message.router.feedback',
      },
      component: () => import('@/pages/setting/storeService/feedback'),
    },
    {
      path: 'store_service/auto_reply',
      name: `${pre}auto_reply`,
      meta: {
        auth: ['admin-setting-store_service-auto_reply'],
        title: 'message.router.auto_reply',
      },
      component: () => import('@/pages/setting/storeService/autoReply'),
    },
    {
      path: 'system_group_data/pc/:id',
      name: `${pre}groupDataPc`,
      meta: {
        auth: ['setting-system-group_data-pc'],
        title: 'message.router.groupDataPc',
      },
      component: () => import('@/pages/system/group/list'),
    },
    {
      path: 'system_config_member_right/:type?/:tab_id?',
      name: `${pre}right`,
      meta: {
        auth: ['setting-system-config-member-right'],
        title: 'message.router.right',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'delivery_service/index',
      name: `${pre}deliveryService`,
      meta: {
        auth: ['setting-delivery-service'],
        title: 'message.router.deliveryService',
      },
      component: () => import('@/pages/setting/deliveryService/index'),
    },
    {
      path: 'pc_group_data',
      name: `${pre}systemPcGroupData`,
      meta: {
        auth: ['setting-system-pc_data'],
        title: 'message.router.systemPcGroupData',
      },
      component: () => import('@/pages/system/group/pc'),
    },
    {
      path: 'system_visualization_data',
      name: `${pre}systemGroupData`,
      meta: {
        auth: ['admin-setting-system_visualization_data'],
        title: 'message.router.systemGroupData',
      },
      component: () => import('@/pages/system/group/visualization'),
    },
    {
      path: 'storage',
      name: `${pre}storage`,
      meta: {
        auth: ['setting-storage'],
        title: 'message.router.storage',
      },
      component: () => import('@/pages/setting/storage'),
    },
    {
      path: 'wechat_config/:type?/:tab_id?',
      name: `${pre}wechat_config`,
      meta: {
        ...meta,
        title: 'message.router.wechat_config',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'routine_config/:type?/:tab_id?',
      name: `${pre}routine_config`,
      meta: {
        ...meta,
        title: 'message.router.routine_config',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'app_config/:type?/:tab_id?',
      name: `${pre}app_config`,
      meta: {
        ...meta,
        title: 'message.router.app_config',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'pc_config/:type?/:tab_id?',
      name: `${pre}pc_config`,
      meta: {
        ...meta,
        title: 'message.router.pc_config',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'other_config/print/:type?/:tab_id?',
      name: `${pre}other_print`,
      meta: {
        auth: ['setting-other-print'],
        title: 'message.router.other_print',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'other_config/copy/:type?/:tab_id?',
      name: `${pre}other_copy`,
      meta: {
        auth: ['setting-other-copy'],
        title: 'message.router.other_copy',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'other_config/logistics/:type?/:tab_id?',
      name: `${pre}other_logistics`,
      meta: {
        auth: ['setting-other-logistics'],
        title: 'message.router.other_logistics',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'other_config/electronic/:type?/:tab_id?',
      name: `${pre}other_electronic`,
      meta: {
        auth: ['setting-other-electronic'],
        title: 'message.router.other_electronic',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'other_config/sms/:type?/:tab_id?',
      name: `${pre}other_sms`,
      meta: {
        auth: ['setting-other-sms'],
        title: 'message.router.other_sms',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'other_config/pay/:type?/:tab_id?',
      name: `${pre}other_pay`,
      meta: {
        auth: ['setting-other-sms'],
        title: 'message.router.other_pay',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'agreement',
      name: `${pre}notification`,
      meta: {
        auth: ['setting-agreement'],
        title: 'message.router.agreement',
      },
      component: () => import('@/pages/setting/agreement/index'),
    },
    {
      path: 'other_config/out/:type?/:tab_id?',
      name: `${pre}other_print`,
      meta: {
        auth: ['setting-other-out'],
        title: 'message.router.other_out',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'system_out_account/index',
      name: `${pre}systemOutAccount`,
      meta: {
        auth: ['setting-system-out-account-index'],
        title: 'message.router.systemOutAccount',
      },
      component: () => import('@/pages/setting/systemOutAccount/index'),
    },
    {
      path: 'system_out_interface/index',
      name: `${pre}systemOutAccount`,
      meta: {
        auth: ['setting-system-out-interface-index'],
        title: 'message.router.systemOutInterface',
      },
      component: () => import('@/pages/setting/systemOutInterface/index'),
    },
    {
      path: 'lang/list',
      name: `${pre}langList`,
      meta: {
        auth: ['admin-lang-list'],
        title: 'message.router.langList',
      },
      component: () => import('@/pages/setting/multiLanguage/list'),
    },
    {
      path: 'lang/info',
      name: `${pre}langInfo`,
      meta: {
        auth: ['admin-lang-info'],
        title: 'message.router.langInfo',
      },
      component: () => import('@/pages/setting/multiLanguage/langList'),
    },
    {
      path: 'lang/country',
      name: `${pre}langCountry`,
      meta: {
        auth: ['admin-lang-country'],
        title: 'message.router.langCountry',
      },
      component: () => import('@/pages/setting/multiLanguage/country'),
    },
    {
      path: 'yihaotong_config/:type?/:tab_id?',
      name: `${pre}yihaotong_config`,
      meta: {
        ...meta,
        title: 'message.router.yihaotong_config',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'lang_config/:type?/:tab_id?',
      name: `${pre}lang_config`,
      meta: {
        ...meta,
        title: 'message.router.lang_config',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'kefu_config/:type?/:tab_id?',
      name: `${pre}kefu_config`,
      meta: {
        ...meta,
        title: 'message.router.kefu_config',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'recharge_config/:type?/:tab_id?',
      name: `${pre}recharge_config`,
      meta: {
        ...meta,
        title: 'message.router.recharge_config',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'member_config/:type?/:tab_id?',
      name: `${pre}member_config`,
      meta: {
        ...meta,
        title: 'message.router.member_config',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'user_config/:type?/:tab_id?',
      name: `${pre}user_config`,
      meta: {
        ...meta,
        title: 'message.router.user_config',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'order_config/:type?/:tab_id?',
      name: `${pre}order_config`,
      meta: {
        ...meta,
        title: 'message.router.order_config',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'sign_config/:type?/:tab_id?',
      name: `${pre}sign_config`,
      meta: {
        ...meta,
        title: 'message.router.sign_config',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'ticket',
      name: `${pre}document`,
      meta: {
        ...meta,
        auth: ['admin-setting-ticket'],
        title: 'message.router.ticket',
      },
      component: () => import('@/pages/setting/ticket'),
    },
    {
      path: 'ticket/content',
      name: `${pre}content`,
      meta: {
        ...meta,
        auth: ['admin-setting-ticket-content'],
        title: 'message.router.content',
        activeMenu: routePre + '/setting/ticket',
      },
      component: () => import('@/pages/setting/ticket/content'),
    },
    {
      path: 'my_theme',
      name: `${pre}myTheme`,
      meta: {
        title: 'message.router.my_theme',
      },
      component: () => import('@/pages/setting/theme/myTheme/index'),
    },
    {
      path: 'mall_theme',
      name: `${pre}mallTheme`,
      meta: {
        title: 'message.router.store_theme',
      },
      component: () => import('@/pages/setting/theme/mallTheme/index'),
    },
    {
      path: 'edit_theme',
      name: `${pre}editTheme`,
      meta: {
        title: 'message.router.themeStyle',
        fullScreen: true, //是否全屏显示main区域
      },
      component: () => import('@/pages/setting/theme/editTheme/index'),
    },
  ],
};
