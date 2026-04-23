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

const pre = 'marketing_';

export default {
  path: routePre + '/marketing',
  name: 'marketing',
  header: 'marketing',
  redirect: {
    name: `${pre}storeCouponIssue`,
  },
  component: LayoutMain,
  children: [
    {
      path: 'store_combination/index',
      name: `${pre}combinalist`,
      meta: {
        auth: ['marketing-store_combination'],
        title: 'Nhóm sản phẩm',
        keepAlive: true,
      },
      component: () => import('@/pages/marketing/storeCombination/index'),
    },
    {
      path: 'store_combination/combina_list',
      name: `${pre}combinaList`,
      meta: {
        auth: ['marketing-store_combination-combina_list'],
        title: 'Danh sách nhóm nhóm',
      },
      component: () => import('@/pages/marketing/storeCombination/combinaList'),
    },
    {
      path: 'store_combination/create/:id?/:copy?',
      name: `${pre}storeCombinationCreate`,
      meta: {
        auth: ['marketing-store_combination-create'],
        title: 'Thêm chuyến tham quan theo nhóm',
        activeMenu: routePre + '/marketing/store_combination/index',
      },
      component: () => import('@/pages/marketing/storeCombination/create'),
    },
    {
      path: 'store_combination/statistics/:id?',
      name: `${pre}storeCombinationStatistics`,
      meta: {
        title: 'Thống kê nhóm nhóm',
        activeMenu: routePre + '/marketing/store_combination/index',
      },
      component: () => import('@/pages/marketing/storeCombination/statistics'),
    },
    {
      path: 'store_coupon/index',
      name: `${pre}storeCoupon`,
      meta: {
        auth: ['marketing-store_coupon'],
        title: 'mẫu phiếu giảm giá',
      },
      component: () => import('@/pages/marketing/storeCoupon/index'),
    },
    {
      path: 'store_coupon_issue/index',
      name: `${pre}storeCouponIssue`,
      meta: {
        auth: ['marketing-store_coupon_issue'],
        title: 'Danh sách phiếu giảm giá',
        keepAlive: true,
      },
      component: () => import('@/pages/marketing/storeCouponIssue/index'),
    },
    {
      path: 'store_coupon_issue/create/:id?/:edit?',
      name: `${pre}storeCouponCreate`,
      meta: {
        auth: ['marketing-store_coupon_issue-create'],
        title: 'thêm phiếu giảm giá',
        activeMenu: routePre + '/marketing/store_coupon_issue/index',
      },
      component: () => import('@/pages/marketing/storeCouponIssue/create'),
    },
    {
      path: 'store_coupon_user/index',
      name: `${pre}storeCouponUser`,
      meta: {
        auth: ['marketing-store_coupon_user'],
        title: 'Bản ghi bộ sưu tập của người dùng',
      },
      component: () => import('@/pages/marketing/storeCouponUser/index'),
    },
    {
      path: 'coupon/system_config/:type?/:tab_id?',
      name: `${pre}coupon`,
      meta: {
        auth: ['admin-order-storeOrder-index'],
        title: 'Cấu hình phiếu giảm giá',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'store_bargain/index',
      name: `${pre}storeBargain`,
      meta: {
        auth: ['marketing-store_bargain'],
        title: 'mặt hàng giá hời',
        keepAlive: true,
      },
      component: () => import('@/pages/marketing/storeBargain/index'),
    },
    {
      path: 'store_bargain/bargain_list',
      name: `${pre}bargainList`,
      meta: {
        auth: ['marketing-store_bargain-bargain_list'],
        title: 'Danh sách mặc cả',
      },
      component: () => import('@/pages/marketing/storeBargain/bargainList'),
    },
    {
      path: 'store_bargain/create/:id?/:copy?',
      name: `${pre}bargainCreate`,
      meta: {
        auth: ['marketing-store_bargain-create'],
        title: 'Thêm món hời',
        activeMenu: routePre + '/marketing/store_bargain/index',
      },
      component: () => import('@/pages/marketing/storeBargain/create'),
    },
    {
      path: 'store_bargain/statistics/:id?',
      name: `${pre}storeBargainStatistics`,
      meta: {
        title: 'Thống kê mặc cả',
        activeMenu: routePre + '/marketing/store_bargain/index',
      },
      component: () => import('@/pages/marketing/storeBargain/statistics'),
    },
    {
      path: 'store_seckill/index',
      name: `${pre}storeSeckill`,
      meta: {
        auth: ['marketing-store_seckill'],
        title: 'mặt hàng flash sale',
        keepAlive: true,
      },
      component: () => import('@/pages/marketing/storeSeckill/index'),
    },
    {
      path: 'store_seckill_data/index/:id',
      name: `${pre}storeSeckillData`,
      meta: {
        auth: ['marketing-store_seckill-data'],
        title: 'Cấu hình bán flash',
      },
      component: () => import('@/pages/system/group/list'),
    },
    {
      path: 'store_seckill/create/:id?/:copy?',
      name: `${pre}storeSeckillCreate`,
      meta: {
        auth: ['marketing-store_seckill-create'],
        title: 'Thêm khuyến mại chớp nhoáng',
        activeMenu: routePre + '/marketing/store_seckill/list',
      },
      component: () => import('@/pages/marketing/storeSeckill/create'),
    },
    {
      path: 'store_seckill/create_more/:id?/:copy?',
      name: `${pre}storeSeckillCreate`,
      meta: {
        auth: ['marketing-store_seckill-create-more'],
        title: 'Thêm khuyến mại chớp nhoáng',
        activeMenu: routePre + '/marketing/store_seckill/list',
      },
      component: () => import('@/pages/marketing/storeSeckill/createMore'),
    },
    {
      path: 'store_seckill/list',
      name: `${pre}marketing-store_seckill-list`,
      meta: {
        title: 'danh sách bán chớp nhoáng',
      },
      component: () => import('@/pages/marketing/storeSeckill/list'),
    },
    {
      path: 'store_seckill/statistics/:id?',
      name: `${pre}storeSeckillStatistics`,
      meta: {
        title: 'Thống kê tiêu diệt chớp nhoáng',
        activeMenu: routePre + '/marketing/store_seckill/index',
      },
      component: () => import('@/pages/marketing/storeSeckill/statistics'),
    },
    {
      path: `integral/system_config/:type?/:tab_id?`,
      name: `${pre}integral`,
      meta: {
        auth: ['marketing-integral-system_config'],
        title: 'Cấu hình điểm',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: `model/system_config/:type?/:tab_id?`,
      name: `${pre}model`,
      meta: {
        auth: ['system-model-system_config'],
        title: 'Cấu hình mô-đun',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'store_integral/index',
      name: `${pre}storeIntegral`,
      meta: {
        auth: ['marketing-store_integral'],
        title: 'Sản phẩm tích điểm',
        keepAlive: true,
      },
      component: () => import('@/pages/marketing/storeIntegral/index'),
    },
    {
      path: 'store_integral/create/:id?/:copy?',
      name: `${pre}storeIntegralCreate`,
      meta: {
        auth: ['marketing-store_integral-create'],
        title: 'Thêm điểm sản phẩm',
        activeMenu: routePre + '/marketing/store_integral/index',
      },
      component: () => import('@/pages/marketing/storeIntegral/create'),
    },
    {
      path: 'store_integral/order_list',
      name: `${pre}storeIntegralOrder`,
      meta: {
        auth: ['marketing-store_integral-order'],
        title: 'Đổi đơn hàng',
      },
      component: () => import('@/pages/marketing/storeIntegralOrder/index'),
    },
    {
      path: 'user_point/index',
      name: `${pre}userPoint`,
      meta: {
        auth: ['marketing-user_point'],
        title: 'Nhật ký điểm',
      },
      component: () => import('@/pages/marketing/userPoint/index'),
    },
    {
      path: 'live/live_room',
      name: `${pre}live_room`,
      meta: {
        auth: true,
        title: 'Quản lý phòng phát sóng trực tiếp',
      },
      component: () => import('@/pages/marketing/live/index'),
    },
    {
      path: 'live/add_live_room',
      name: `${pre}add_live_room`,
      meta: {
        auth: true,
        title: 'Quản lý phòng phát sóng trực tiếp',
        activeMenu: routePre + '/marketing/live/live_room',
      },
      component: () => import('@/pages/marketing/live/creat_live'),
    },
    {
      path: 'live/live_goods',
      name: `${pre}live_goods`,
      meta: {
        auth: true,
        title: 'Quản lý sản phẩm phòng phát sóng trực tiếp',
      },
      component: () => import('@/pages/marketing/live/live_goods'),
    },
    {
      path: 'live/add_live_goods',
      name: `${pre}add_live_goods`,
      meta: {
        auth: true,
        title: 'Quản lý sản phẩm phòng phát sóng trực tiếp',
        activeMenu: routePre + '/marketing/live/live_goods',
      },
      component: () => import('@/pages/marketing/live/add_goods'),
    },
    {
      path: 'live/anchor',
      name: `${pre}anchor`,
      meta: {
        auth: true,
        title: 'Quản lý neo',
      },
      component: () => import('@/pages/marketing/live/anchor'),
    },
    {
      path: 'presell/index',
      name: `${pre}storePresell`,
      meta: {
        auth: ['marketing-presell'],
        title: 'Các mặt hàng bán trước',
      },
      component: () => import('@/pages/marketing/storePresell/index'),
    },
    {
      path: 'presell/presell_list',
      name: `${pre}presellList`,
      meta: {
        auth: ['marketing-presell-presell_list'],
        title: 'Danh sách bán trước',
      },
      component: () => import('@/pages/marketing/storePresell/presellList'),
    },
    {
      path: 'presell/create/:id?/:copy?',
      name: `${pre}storePresellCreate`,
      meta: {
        auth: ['marketing-presell-create'],
        title: 'Thêm bán trước',
      },
      component: () => import('@/pages/marketing/storePresell/create'),
    },
    {
      path: 'lottery/index',
      name: `${pre}lottery`,
      meta: {
        auth: true,
        title: 'Danh sách xổ số',
      },
      component: () => import('@/pages/marketing/lottery/index'),
    },
    {
      path: 'lottery/create',
      name: `${pre}create`,
      meta: {
        auth: true,
        title: 'Tạo xổ số',
        activeMenu: routePre + '/marketing/lottery/list',
      },
      component: () => import('@/pages/marketing/lottery/create'),
    },
    {
      path: 'lottery/recording_list',
      name: `${pre}recording_list`,
      meta: {
        auth: true,
        title: 'Kỷ lục xổ số',
        activeMenu: routePre + '/marketing/lottery/list',
      },
      component: () => import('@/pages/marketing/lottery/recordingList'),
    },
    {
      path: 'lottery/config',
      name: `${pre}lottery_config`,
      meta: {
        auth: ['admin-marketing-lottery-config'],
        title: 'Cấu hình xổ số',
      },
      component: () => import('@/pages/marketing/lottery/config'),
    },
    {
      path: 'lottery/list',
      name: `${pre}list`,
      meta: {
        auth: true,
        title: 'Danh sách xổ số',
      },
      component: () => import('@/pages/marketing/lottery/lotteryList'),
    },
    {
      path: 'channel_code/channelCodeIndex',
      name: `${pre}channel_code`,
      meta: {
        auth: true,
        title: 'Mã kênh tài khoản chính thức',
        keepAlive: true,
      },
      component: () => import('@/pages/marketing/channelCode/channelCodeIndex'),
    },
    {
      path: 'channel_code/create',
      name: `${pre}create_code`,
      meta: {
        auth: ['marketing-channel_code-create'],
        title: 'Mã kênh',
        activeMenu: routePre + '/marketing/channel_code/channelCodeIndex',
      },
      component: () => import('@/pages/marketing/channelCode/createCode'),
    },
    {
      path: 'channel_code/code_statistic',
      name: `${pre}code_statistic`,
      meta: {
        auth: ['marketing-channel_code-statistic'],
        title: 'Thống kê mã QR',
        activeMenu: routePre + '/marketing/channel_code/channelCodeIndex',
      },
      component: () => import('@/pages/marketing/channelCode/codeStatistic'),
    },
    {
      path: 'point_record',
      name: `${pre}point_record`,
      meta: {
        auth: ['marketing-point_record-index'],
        title: 'Kỷ lục điểm',
      },
      component: () => import('@/pages/marketing/point_record/index'),
    },
    {
      path: 'point_statistic',
      name: `${pre}point_statistic`,
      meta: {
        auth: ['marketing-point_statistic-index'],
        title: 'Thống kê điểm',
      },
      component: () => import('@/pages/marketing/point_statistic/index'),
    },
    {
      path: 'recharge',
      name: `${pre}recharge`,
      meta: {
        title: 'Cấu hình nạp tiền',
      },
      component: () => import('@/pages/marketing/recharge/index'),
    },
    {
      path: 'sign',
      name: `${pre}sign`,
      meta: {
        title: 'Cấu hình đăng nhập',
      },
      component: () => import('@/pages/marketing/sign/index'),
    },
    {
      path: 'sign_rewards',
      name: `${pre}sign_rewards`,
      meta: {
        title: 'Phần thưởng đăng nhập',
      },
      component: () => import('@/pages/marketing/sign/rewards'),
    },
    {
      path: `member_config/:type?/:tab_id?`,
      name: `${pre}member_config`,
      meta: {
        title: 'Cấu hình thành viên',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: `newuser/gift`,
      name: `${pre}gift`,
      meta: {
        title: 'Lễ tân hôn',
        auth: ['admin-marketing-new-user-gift'],
      },
      component: () => import('@/pages/marketing/newuser/gift'),
    },
  ],
};
