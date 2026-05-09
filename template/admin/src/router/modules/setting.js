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
        title: 'Quản lý danh tính',
      },
      component: () => import('@/pages/setting/systemRole/index'),
    },
    {
      path: 'system_admin/index',
      name: `${pre}systemAdmin`,
      meta: {
        auth: ['setting-system-list'],
        title: 'Tài khoản quản trị',
      },
      component: () => import('@/pages/setting/systemAdmin/index'),
    },
    {
      path: 'system_menus/index',
      name: `${pre}systemMenus`,
      meta: {
        auth: ['setting-system-menus'],
        title: 'Quy tắc cấp phép',
      },
      component: () => import('@/pages/setting/systemMenus/index'),
    },
    {
      path: 'system_config',
      name: `${pre}setSystem`,
      meta: {
        auth: ['setting-system-config'],
        title: 'Cài đặt hệ thống',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'system_config/:type?/:tab_id?',
      name: `${pre}setApp`,
      meta: {
        title: 'Cài đặt hệ thống',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'system_config_retail/:type?/:tab_id?',
      name: `${pre}distributionSet`,
      meta: {
        ...meta,
        title: 'cấu hình phân phối',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'membership_level/index',
      name: `${pre}membershipLevel`,
      meta: {
        ...meta,
        title: 'Cấp bậc Affiliate',
      },
      component: () => import('@/pages/setting/membershipLevel/index'),
    },
    {
      path: 'system_config_message/:type?/:tab_id?',
      name: `${pre}message`,
      meta: {
        auth: ['setting-system-config-message'],
        title: 'chuyển đổi tin nhắn SMS',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'notification/index',
      name: `${pre}notification`,
      meta: {
        auth: ['setting-notification'],
        title: 'Quản lý thông báo',
      },
      component: () => import('@/pages/setting/notification/index'),
    },
    {
      path: 'notification/notificationEdit',
      name: `${pre}notificationEdit`,
      meta: {
        auth: ['setting-notification'],
        title: 'Trình chỉnh sửa tin nhắn',
        activeMenu: routePre + '/setting/notification/index',
      },
      component: () => import('@/pages/setting/notification/notificationEdit'),
    },
    {
      path: 'system_config_logistics/:type?/:tab_id?',
      name: `${pre}logistics`,
      meta: {
        auth: ['setting-system-config-logistics'],
        title: 'Cấu hình hậu cần',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'sms/sms_config/index',
      name: `${pre}config`,
      meta: {
        auth: ['setting-sms-sms-config'],
        title: 'Tài khoản Pass một số',
      },
      component: () => import('@/pages/notify/smsConfig/index'),
    },
    {
      path: 'elec_invoice',
      name: `${pre}elec_invoice`,
      meta: {
        auth: ['setting-elec_invoice'],
        title: 'Cấu hình hoá đơn điện tử',
      },
      component: () => import('@/pages/notify/smsConfig/elecInvoice'),
    },
    {
      path: 'sms/sms_template_apply/index',
      name: `${pre}smsTemplateApply`,
      meta: {
        auth: ['setting-sms-config-template'],
        title: 'mẫu tin nhắn',
      },
      component: () => import('@/pages/notify/smsTemplateApply/index'),
    },
    {
      path: 'sms/sms_pay/index',
      name: `${pre}smsPay`,
      meta: {
        auth: ['setting-sms-sms-template'],
        title: 'mua hàng qua tin nhắn SMS',
      },
      component: () => import('@/pages/notify/smsPay/index'),
    },
    {
      path: 'sms/sms_template_apply/commons',
      name: `${pre}commons`,
      meta: {
        ...meta,
        title: 'Mẫu SMS công khai',
      },
      component: () => import('@/pages/notify/smsTemplateApply/index'),
    },
    {
      path: 'system_group_data/index/:id',
      name: `${pre}groupDataIndex`,
      meta: {
        auth: ['setting-system-group_data-index'],
        title: 'Các nút điều hướng trang chủ',
      },
      component: () => import('@/pages/system/group/list'),
    },
    {
      path: 'system_group_data/slide/:id',
      name: `${pre}groupDataSlide`,
      meta: {
        auth: ['setting-system-group_data-slide'],
        title: 'Trang chủ trình chiếu',
      },
      component: () => import('@/pages/system/group/list'),
    },
    {
      path: 'system_group_data/sign/:id',
      name: `${pre}groupDataSign`,
      meta: {
        auth: ['setting-system-group_data-sign'],
        title: 'Cấu hình ngày nhận phòng',
      },
      component: () => import('@/pages/system/group/list'),
    },
    // {
    //   path: 'system_group_data/order/:id',
    //   name: `${pre}groupDataOrder`,
    //   meta: {
    //     auth: ['setting-system-group_data-order'],
    //     title: 'Chi tiết đơn hàng biểu đồ động'
    //   },
    //   component: () => import('@/pages/system/group/list')
    // },
    // {
    //   path: 'system_group_data/user/:id',
    //   name: `${pre}groupDataUser`,
    //   meta: {
    //     auth: ['setting-system-group_data-user'],
    //     title: 'Menu trung tâm cá nhân'
    //   },
    //   component: () => import('@/pages/system/group/list')
    // },
    {
      path: 'system_group_data/new/:id',
      name: `${pre}groupDataNew`,
      meta: {
        auth: ['setting-system-group_data-new'],
        title: 'Trang chủ tung tin tức',
      },
      component: () => import('@/pages/system/group/list'),
    },
    {
      path: 'system_group_data/search/:id',
      name: `${pre}groupDataNew`,
      meta: {
        auth: ['setting-system-group_data-search'],
        title: 'Tìm kiếm phổ biến',
      },
      component: () => import('@/pages/system/group/list'),
    },
    {
      path: 'system_group_data/hot/:id',
      name: `${pre}groupDataHot`,
      meta: {
        auth: ['setting-system-group_data-hot'],
        title: 'Đề xuất danh sách phổ biến',
      },
      component: () => import('@/pages/system/group/list'),
    },
    {
      path: 'system_group_data/new_product/:id',
      name: `${pre}groupDataNewProduct`,
      meta: {
        auth: ['setting-system-group_data-new_product'],
        title: 'Sản phẩm mới được đề xuất lần đầu tiên',
      },
      component: () => import('@/pages/system/group/list'),
    },
    {
      path: 'system_group_data/promotion/:id',
      name: `${pre}groupDataPromotion`,
      meta: {
        auth: ['setting-system-group_data-promotion'],
        title: 'Mặt hàng khuyến mãi được đề xuất',
      },
      component: () => import('@/pages/system/group/list'),
    },
    {
      path: 'system_group_data/poster/:id',
      name: `${pre}groupDataPoster`,
      meta: {
        auth: ['setting-system-group_data-poster'],
        title: 'Poster phân phối trung tâm cá nhân',
      },
      component: () => import('@/pages/system/group/list'),
    },
    {
      path: 'system_group_data/best/:id',
      name: `${pre}groupDataBest`,
      meta: {
        auth: ['setting-system-group_data-best'],
        title: 'Sản phẩm được đề xuất',
      },
      component: () => import('@/pages/system/group/list'),
    },
    {
      path: 'system_group_data/activity/:id',
      name: `${pre}groupDataActivity`,
      meta: {
        auth: ['setting-system-group_data-activity'],
        title: 'Hình ảnh khu vực sự kiện trang chủ',
      },
      component: () => import('@/pages/system/group/list'),
    },
    {
      path: 'system_group_data/system/:id',
      name: `${pre}groupDataSystem`,
      meta: {
        auth: ['setting-system-group_data-system'],
        title: 'Cấu hình trang chủ',
      },
      component: () => import('@/pages/system/group/list'),
    },
    {
      path: 'system_group_data/hot_money/:id',
      name: `${pre}groupDataHotMoney`,
      meta: {
        auth: ['admin-setting-system_group_data-hot_money'],
        title: 'Trang chủ siêu giá trị đồ hot',
      },
      component: () => import('@/pages/system/group/list'),
    },
    {
      path: 'merchant/system_store/index',
      name: `${pre}systemStore`,
      meta: {
        auth: ['setting-system-config-merchant'],
        title: 'Cài đặt cửa hàng',
      },
      component: () => import('@/pages/setting/systemStore/index'),
    },
    {
      path: 'freight/express/index',
      name: `${pre}freight`,
      meta: {
        auth: ['setting-freight-express'],
        title: 'Công ty giao nhận',
      },
      component: () => import('@/pages/setting/freight/index'),
    },
    {
      path: 'store_service/index',
      name: `${pre}service`,
      meta: {
        auth: ['setting-store-service'],
        title: 'Quản lý dịch vụ khách hàng',
      },
      component: () => import('@/pages/setting/storeService/index'),
    },
    {
      path: 'freight/city/list',
      name: `${pre}dada`,
      meta: {
        auth: ['setting-system-city'],
        title: 'dữ liệu thành phố',
      },
      component: () => import('@/pages/setting/cityDada/index'),
    },
    {
      path: 'freight/shipping_templates/list',
      name: `${pre}templates`,
      meta: {
        auth: ['setting-shipping-templates'],
        title: 'Mẫu vận chuyển sản phẩm',
      },
      component: () => import('@/pages/setting/shippingTemplates/index'),
    },
    {
      path: 'merchant/system_store/list',
      name: `${pre}store`,
      meta: {
        auth: ['setting-merchant-system-store'],
        title: 'Điểm đón',
      },
      component: () => import('@/pages/setting/storeList/index'),
    },
    {
      path: 'merchant/system_store_staff/index',
      name: `${pre}staff`,
      meta: {
        auth: ['setting-merchant-system-store-staff'],
        title: 'người bảo lãnh',
      },
      component: () => import('@/pages/setting/clerkList/index'),
    },
    {
      path: 'merchant/system_verify_order/index',
      name: `${pre}order`,
      meta: {
        auth: ['setting-merchant-system-verify-order'],
        title: 'Xác nhận đơn hàng',
      },
      component: () => import('@/pages/setting/verifyOrder/index'),
    },
    {
      path: 'theme_style',
      name: `${pre}themeStyle`,
      meta: {
        auth: ['admin-setting-theme_style'],
        title: 'phong cách chủ đề',
      },
      component: () => import('@/pages/setting/themeStyle/index'),
    },
    {
      path: 'theme/micro_page',
      name: `${pre}microPage`,
      meta: {
        auth: ['setting-theme-micro_page'],
        title: 'Trang vi mô',
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
        title: 'trang trí cửa hàng',
      },
      component: () => import('@/pages/setting/devise/list'),
    },
    {
      path: 'pages/user_page/:type',
      name: `${pre}user`,
      meta: {
        auth: ['admin-setting-pages-user'],
        title: 'Trung tâm cá nhân',
      },
      component: () => import('@/pages/setting/devise/list'),
    },
    {
      path: 'pages/link',
      name: `${pre}link`,
      meta: {
        auth: ['admin-setting-pages-link'],
        title: 'Quản lý liên kết',
      },
      component: () => import('@/pages/setting/link'),
    },
    {
      path: 'pages/cate_page/:type',
      name: `${pre}cate`,
      meta: {
        auth: ['admin-setting-pages-cate'],
        title: 'Danh mục sản phẩm',
      },
      component: () => import('@/pages/setting/devise/list'),
    },
    {
      path: 'pages/diy',
      name: `${pre}diy`,
      meta: {
        auth: ['admin-setting-pages-diy'],
        title: 'Thiết kế trang',
        activeMenu: routePre + '/setting/pages/devise',
      },
      component: () => import('@/pages/setting/devisePage/index'),
    },
    {
      path: 'pages/diy_index',
      name: `${pre}index_diy`,
      meta: {
        auth: ['admin-setting-pages-diy'],
        title: 'Thiết kế trang chủ',
        fullScreen: true, //Có hiển thị khu vực chính ở chế độ toàn màn hình hay không
      },
      component: () => import('@/pages/setting/devise/diyIndex'),
    },
    {
      path: 'pages/links',
      name: `${pre}links`,
      meta: {
        auth: ['admin-setting-pages-links'],
        title: 'Liên kết trang',
      },
      component: () => import('@/pages/setting/devise/links'),
    },
    {
      path: 'store_service/speechcraft',
      name: `${pre}speechcraft`,
      meta: {
        auth: ['admin-setting-store_service-speechcraft'],
        title: 'Kỹ năng phục vụ khách hàng',
      },
      component: () => import('@/pages/setting/storeService/speechcraft'),
    },
    {
      path: 'store_service/feedback',
      name: `${pre}feedback`,
      meta: {
        auth: ['admin-setting-store_service-feedback'],
        title: 'Tin nhắn người dùng',
      },
      component: () => import('@/pages/setting/storeService/feedback'),
    },
    {
      path: 'store_service/auto_reply',
      name: `${pre}auto_reply`,
      meta: {
        auth: ['admin-setting-store_service-auto_reply'],
        title: 'trả lời tự động',
      },
      component: () => import('@/pages/setting/storeService/autoReply'),
    },
    {
      path: 'system_group_data/pc/:id',
      name: `${pre}groupDataPc`,
      meta: {
        auth: ['setting-system-group_data-pc'],
        title: 'PCBăng chuyền trang chủ',
      },
      component: () => import('@/pages/system/group/list'),
    },
    {
      path: 'system_config_member_right/:type?/:tab_id?',
      name: `${pre}right`,
      meta: {
        auth: ['setting-system-config-member-right'],
        title: 'Quyền thành viên',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'delivery_service/index',
      name: `${pre}deliveryService`,
      meta: {
        auth: ['setting-delivery-service'],
        title: 'Danh sách người giao hàng',
      },
      component: () => import('@/pages/setting/deliveryService/index'),
    },
    {
      path: 'pc_group_data',
      name: `${pre}systemPcGroupData`,
      meta: {
        auth: ['setting-system-pc_data'],
        title: 'PCTrung tâm mua sắm',
      },
      component: () => import('@/pages/system/group/pc'),
    },
    {
      path: 'system_visualization_data',
      name: `${pre}systemGroupData`,
      meta: {
        auth: ['admin-setting-system_visualization_data'],
        title: 'Cấu hình dữ liệu',
      },
      component: () => import('@/pages/system/group/visualization'),
    },
    {
      path: 'storage',
      name: `${pre}storage`,
      meta: {
        auth: ['setting-storage'],
        title: 'Lưu cấu hình',
      },
      component: () => import('@/pages/setting/storage'),
    },
    {
      path: 'wechat_config/:type?/:tab_id?',
      name: `${pre}wechat_config`,
      meta: {
        ...meta,
        title: 'Cấu hình tài khoản chính thức',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'routine_config/:type?/:tab_id?',
      name: `${pre}routine_config`,
      meta: {
        ...meta,
        title: 'Cấu hình chương trình nhỏ',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'app_config/:type?/:tab_id?',
      name: `${pre}app_config`,
      meta: {
        ...meta,
        title: 'appCấu hình',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'pc_config/:type?/:tab_id?',
      name: `${pre}pc_config`,
      meta: {
        ...meta,
        title: 'PCCấu hình',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'other_config/print/:type?/:tab_id?',
      name: `${pre}other_print`,
      meta: {
        auth: ['setting-other-print'],
        title: 'Cấu hình in hóa đơn',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'other_config/copy/:type?/:tab_id?',
      name: `${pre}other_copy`,
      meta: {
        auth: ['setting-other-copy'],
        title: 'Cấu hình bộ sưu tập sản phẩm',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'other_config/logistics/:type?/:tab_id?',
      name: `${pre}other_logistics`,
      meta: {
        auth: ['setting-other-logistics'],
        title: 'Cấu hình truy vấn hậu cần',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'other_config/electronic/:type?/:tab_id?',
      name: `${pre}other_electronic`,
      meta: {
        auth: ['setting-other-electronic'],
        title: 'Cấu hình biểu mẫu điện tử',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'other_config/sms/:type?/:tab_id?',
      name: `${pre}other_sms`,
      meta: {
        auth: ['setting-other-sms'],
        title: 'Cấu hình chức năng SMS',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'other_config/pay/:type?/:tab_id?',
      name: `${pre}other_pay`,
      meta: {
        auth: ['setting-other-pay'],
        title: 'Cấu hình thanh toán cửa hàng',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'agreement',
      name: `${pre}notification`,
      meta: {
        auth: ['setting-agreement'],
        title: 'Điều khoản & chính sách',
      },
      component: () => import('@/pages/setting/agreement/index'),
    },
    {
      path: 'other_config/out/:type?/:tab_id?',
      name: `${pre}other_print`,
      meta: {
        auth: ['setting-other-out'],
        title: 'Cài đặt giao diện bên ngoài',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'system_out_account/index',
      name: `${pre}systemOutAccount`,
      meta: {
        auth: ['setting-system-out-account-index'],
        title: 'Danh sách tài khoản',
      },
      component: () => import('@/pages/setting/systemOutAccount/index'),
    },
    {
      path: 'system_out_interface/index',
      name: `${pre}systemOutAccount`,
      meta: {
        auth: ['setting-system-out-interface-index'],
        title: 'Tài liệu giao diện',
      },
      component: () => import('@/pages/setting/systemOutInterface/index'),
    },
    {
      path: 'lang/list',
      name: `${pre}langList`,
      meta: {
        auth: ['admin-lang-list'],
        title: 'Danh sách ngôn ngữ',
      },
      component: () => import('@/pages/setting/multiLanguage/list'),
    },
    {
      path: 'lang/info',
      name: `${pre}langInfo`,
      meta: {
        auth: ['admin-lang-info'],
        title: 'Chi tiết ngôn ngữ',
      },
      component: () => import('@/pages/setting/multiLanguage/langList'),
    },
    {
      path: 'lang/country',
      name: `${pre}langCountry`,
      meta: {
        auth: ['admin-lang-country'],
        title: 'Ngôn ngữ liên quan đến khu vực',
      },
      component: () => import('@/pages/setting/multiLanguage/country'),
    },
    {
      path: 'yihaotong_config/:type?/:tab_id?',
      name: `${pre}yihaotong_config`,
      meta: {
        ...meta,
        title: 'Cấu hình vượt qua một số',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'lang_config/:type?/:tab_id?',
      name: `${pre}lang_config`,
      meta: {
        ...meta,
        title: 'Cấu hình dịch',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'kefu_config/:type?/:tab_id?',
      name: `${pre}kefu_config`,
      meta: {
        ...meta,
        title: 'Cấu hình dịch vụ khách hàng',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'recharge_config/:type?/:tab_id?',
      name: `${pre}recharge_config`,
      meta: {
        ...meta,
        title: 'Cấu hình nạp tiền',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'member_config/:type?/:tab_id?',
      name: `${pre}member_config`,
      meta: {
        ...meta,
        title: 'Cấu hình thành viên trả phí',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'user_config/:type?/:tab_id?',
      name: `${pre}user_config`,
      meta: {
        ...meta,
        title: 'Cài đặt khách hàng',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'order_config/:type?/:tab_id?',
      name: `${pre}order_config`,
      meta: {
        ...meta,
        title: 'Cấu hình đơn hàng',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'sign_config/:type?/:tab_id?',
      name: `${pre}sign_config`,
      meta: {
        ...meta,
        title: 'Cấu hình đăng nhập',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'ticket',
      name: `${pre}document`,
      meta: {
        ...meta,
        auth: ['admin-setting-ticket'],
        title: 'Cài đặt máy in',
      },
      component: () => import('@/pages/setting/ticket'),
    },
    {
      path: 'ticket/content',
      name: `${pre}content`,
      meta: {
        ...meta,
        auth: ['admin-setting-ticket-content'],
        title: 'Cài đặt in ấn',
        activeMenu: routePre + '/setting/ticket',
      },
      component: () => import('@/pages/setting/ticket/content'),
    },
    {
      path: 'my_theme',
      name: `${pre}myTheme`,
      meta: {
        title: 'chủ đề của tôi',
      },
      component: () => import('@/pages/setting/theme/myTheme/index'),
    },
    {
      path: 'mall_theme',
      name: `${pre}mallTheme`,
      meta: {
        title: 'Chủ đề trung tâm mua sắm',
      },
      component: () => import('@/pages/setting/theme/mallTheme/index'),
    },
    {
      path: 'edit_theme',
      name: `${pre}editTheme`,
      meta: {
        title: 'phong cách chủ đề',
        fullScreen: true, //Có hiển thị khu vực chính ở chế độ toàn màn hình hay không
      },
      component: () => import('@/pages/setting/theme/editTheme/index'),
    },
  ],
};
