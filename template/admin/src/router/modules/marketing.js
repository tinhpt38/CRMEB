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
        title: 'router.marketing.groupProduct',
        keepAlive: true,
      },
      component: () => import('@/pages/marketing/storeCombination/index'),
    },
    {
      path: 'store_combination/combina_list',
      name: `${pre}combinaList`,
      meta: {
        auth: ['marketing-store_combination-combina_list'],
        title: 'router.marketing.groupList',
      },
      component: () => import('@/pages/marketing/storeCombination/combinaList'),
    },
    {
      path: 'store_combination/create/:id?/:copy?',
      name: `${pre}storeCombinationCreate`,
      meta: {
        auth: ['marketing-store_combination-create'],
        title: 'router.marketing.groupCreate',
        activeMenu: routePre + '/marketing/store_combination/index',
      },
      component: () => import('@/pages/marketing/storeCombination/create'),
    },
    {
      path: 'store_combination/statistics/:id?',
      name: `${pre}storeCombinationStatistics`,
      meta: {
        title: 'router.marketing.groupStatistics',
        activeMenu: routePre + '/marketing/store_combination/index',
      },
      component: () => import('@/pages/marketing/storeCombination/statistics'),
    },
    {
      path: 'store_coupon/index',
      name: `${pre}storeCoupon`,
      meta: {
        auth: ['marketing-store_coupon'],
        title: 'router.marketing.couponTemplate',
      },
      component: () => import('@/pages/marketing/storeCoupon/index'),
    },
    {
      path: 'store_coupon_issue/index',
      name: `${pre}storeCouponIssue`,
      meta: {
        auth: ['marketing-store_coupon_issue'],
        title: 'router.marketing.couponList',
        keepAlive: true,
      },
      component: () => import('@/pages/marketing/storeCouponIssue/index'),
    },
    {
      path: 'store_coupon_issue/create/:id?/:edit?',
      name: `${pre}storeCouponCreate`,
      meta: {
        auth: ['marketing-store_coupon_issue-create'],
        title: 'router.marketing.couponCreate',
        activeMenu: routePre + '/marketing/store_coupon_issue/index',
      },
      component: () => import('@/pages/marketing/storeCouponIssue/create'),
    },
    {
      path: 'store_coupon_user/index',
      name: `${pre}storeCouponUser`,
      meta: {
        auth: ['marketing-store_coupon_user'],
        title: 'router.marketing.couponReceiveRecord',
      },
      component: () => import('@/pages/marketing/storeCouponUser/index'),
    },
    {
      path: 'coupon/system_config/:type?/:tab_id?',
      name: `${pre}coupon`,
      meta: {
        auth: ['admin-order-storeOrder-index'],
        title: 'router.marketing.couponConfig',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'store_bargain/index',
      name: `${pre}storeBargain`,
      meta: {
        auth: ['marketing-store_bargain'],
        title: 'router.marketing.bargainProduct',
        keepAlive: true,
      },
      component: () => import('@/pages/marketing/storeBargain/index'),
    },
    {
      path: 'store_bargain/bargain_list',
      name: `${pre}bargainList`,
      meta: {
        auth: ['marketing-store_bargain-bargain_list'],
        title: 'router.marketing.bargainList',
      },
      component: () => import('@/pages/marketing/storeBargain/bargainList'),
    },
    {
      path: 'store_bargain/create/:id?/:copy?',
      name: `${pre}bargainCreate`,
      meta: {
        auth: ['marketing-store_bargain-create'],
        title: 'router.marketing.bargainCreate',
        activeMenu: routePre + '/marketing/store_bargain/index',
      },
      component: () => import('@/pages/marketing/storeBargain/create'),
    },
    {
      path: 'store_bargain/statistics/:id?',
      name: `${pre}storeBargainStatistics`,
      meta: {
        title: 'router.marketing.bargainStatistics',
        activeMenu: routePre + '/marketing/store_bargain/index',
      },
      component: () => import('@/pages/marketing/storeBargain/statistics'),
    },
    {
      path: 'store_seckill/index',
      name: `${pre}storeSeckill`,
      meta: {
        auth: ['marketing-store_seckill'],
        title: 'router.marketing.seckillProduct',
        keepAlive: true,
      },
      component: () => import('@/pages/marketing/storeSeckill/index'),
    },
    {
      path: 'store_seckill_data/index/:id',
      name: `${pre}storeSeckillData`,
      meta: {
        auth: ['marketing-store_seckill-data'],
        title: 'router.marketing.seckillConfig',
      },
      component: () => import('@/pages/system/group/list'),
    },
    {
      path: 'store_seckill/create/:id?/:copy?',
      name: `${pre}storeSeckillCreate`,
      meta: {
        auth: ['marketing-store_seckill-create'],
        title: 'router.marketing.seckillCreate',
        activeMenu: routePre + '/marketing/store_seckill/list',
      },
      component: () => import('@/pages/marketing/storeSeckill/create'),
    },
    {
      path: 'store_seckill/create_more/:id?/:copy?',
      name: `${pre}storeSeckillCreate`,
      meta: {
        auth: ['marketing-store_seckill-create-more'],
        title: 'router.marketing.seckillCreate',
        activeMenu: routePre + '/marketing/store_seckill/list',
      },
      component: () => import('@/pages/marketing/storeSeckill/createMore'),
    },
    {
      path: 'store_seckill/list',
      name: `${pre}marketing-store_seckill-list`,
      meta: {
        title: 'router.marketing.seckillList',
      },
      component: () => import('@/pages/marketing/storeSeckill/list'),
    },
    {
      path: 'store_seckill/statistics/:id?',
      name: `${pre}storeSeckillStatistics`,
      meta: {
        title: 'router.marketing.seckillStatistics',
        activeMenu: routePre + '/marketing/store_seckill/index',
      },
      component: () => import('@/pages/marketing/storeSeckill/statistics'),
    },
    {
      path: `integral/system_config/:type?/:tab_id?`,
      name: `${pre}integral`,
      meta: {
        auth: ['marketing-integral-system_config'],
        title: 'router.marketing.integralConfig',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: `model/system_config/:type?/:tab_id?`,
      name: `${pre}model`,
      meta: {
        auth: ['system-model-system_config'],
        title: 'router.marketing.moduleConfig',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: 'store_integral/index',
      name: `${pre}storeIntegral`,
      meta: {
        auth: ['marketing-store_integral'],
        title: 'router.marketing.integralProduct',
        keepAlive: true,
      },
      component: () => import('@/pages/marketing/storeIntegral/index'),
    },
    {
      path: 'store_integral/create/:id?/:copy?',
      name: `${pre}storeIntegralCreate`,
      meta: {
        auth: ['marketing-store_integral-create'],
        title: 'router.marketing.integralProductCreate',
        activeMenu: routePre + '/marketing/store_integral/index',
      },
      component: () => import('@/pages/marketing/storeIntegral/create'),
    },
    {
      path: 'store_integral/order_list',
      name: `${pre}storeIntegralOrder`,
      meta: {
        auth: ['marketing-store_integral-order'],
        title: 'router.marketing.exchangeOrder',
      },
      component: () => import('@/pages/marketing/storeIntegralOrder/index'),
    },
    {
      path: 'user_point/index',
      name: `${pre}userPoint`,
      meta: {
        auth: ['marketing-user_point'],
        title: 'router.marketing.integralLog',
      },
      component: () => import('@/pages/marketing/userPoint/index'),
    },
    {
      path: 'live/live_room',
      name: `${pre}live_room`,
      meta: {
        auth: true,
        title: 'router.marketing.liveRoomManage',
      },
      component: () => import('@/pages/marketing/live/index'),
    },
    {
      path: 'live/add_live_room',
      name: `${pre}add_live_room`,
      meta: {
        auth: true,
        title: 'router.marketing.liveRoomManage',
        activeMenu: routePre + '/marketing/live/live_room',
      },
      component: () => import('@/pages/marketing/live/creat_live'),
    },
    {
      path: 'live/live_goods',
      name: `${pre}live_goods`,
      meta: {
        auth: true,
        title: 'router.marketing.liveGoodsManage',
      },
      component: () => import('@/pages/marketing/live/live_goods'),
    },
    {
      path: 'live/add_live_goods',
      name: `${pre}add_live_goods`,
      meta: {
        auth: true,
        title: 'router.marketing.liveGoodsManage',
        activeMenu: routePre + '/marketing/live/live_goods',
      },
      component: () => import('@/pages/marketing/live/add_goods'),
    },
    {
      path: 'live/anchor',
      name: `${pre}anchor`,
      meta: {
        auth: true,
        title: 'router.marketing.anchorManage',
      },
      component: () => import('@/pages/marketing/live/anchor'),
    },
    {
      path: 'presell/index',
      name: `${pre}storePresell`,
      meta: {
        auth: ['marketing-presell'],
        title: 'router.marketing.presellProduct',
      },
      component: () => import('@/pages/marketing/storePresell/index'),
    },
    {
      path: 'presell/presell_list',
      name: `${pre}presellList`,
      meta: {
        auth: ['marketing-presell-presell_list'],
        title: 'router.marketing.presellList',
      },
      component: () => import('@/pages/marketing/storePresell/presellList'),
    },
    {
      path: 'presell/create/:id?/:copy?',
      name: `${pre}storePresellCreate`,
      meta: {
        auth: ['marketing-presell-create'],
        title: 'router.marketing.presellCreate',
      },
      component: () => import('@/pages/marketing/storePresell/create'),
    },
    {
      path: 'lottery/index',
      name: `${pre}lottery`,
      meta: {
        auth: true,
        title: 'router.marketing.lotteryList',
      },
      component: () => import('@/pages/marketing/lottery/index'),
    },
    {
      path: 'lottery/create',
      name: `${pre}create`,
      meta: {
        auth: true,
        title: 'router.marketing.lotteryCreate',
        activeMenu: routePre + '/marketing/lottery/list',
      },
      component: () => import('@/pages/marketing/lottery/create'),
    },
    {
      path: 'lottery/recording_list',
      name: `${pre}recording_list`,
      meta: {
        auth: true,
        title: 'router.marketing.lotteryRecord',
        activeMenu: routePre + '/marketing/lottery/list',
      },
      component: () => import('@/pages/marketing/lottery/recordingList'),
    },
    {
      path: 'lottery/config',
      name: `${pre}lottery_config`,
      meta: {
        auth: ['admin-marketing-lottery-config'],
        title: 'router.marketing.lotteryConfig',
      },
      component: () => import('@/pages/marketing/lottery/config'),
    },
    {
      path: 'lottery/list',
      name: `${pre}list`,
      meta: {
        auth: true,
        title: 'router.marketing.lotteryList',
      },
      component: () => import('@/pages/marketing/lottery/lotteryList'),
    },
    {
      path: 'channel_code/channelCodeIndex',
      name: `${pre}channel_code`,
      meta: {
        auth: true,
        title: 'router.marketing.officialAccountChannelCode',
        keepAlive: true,
      },
      component: () => import('@/pages/marketing/channelCode/channelCodeIndex'),
    },
    {
      path: 'channel_code/create',
      name: `${pre}create_code`,
      meta: {
        auth: ['marketing-channel_code-create'],
        title: 'router.marketing.channelCode',
        activeMenu: routePre + '/marketing/channel_code/channelCodeIndex',
      },
      component: () => import('@/pages/marketing/channelCode/createCode'),
    },
    {
      path: 'channel_code/code_statistic',
      name: `${pre}code_statistic`,
      meta: {
        auth: ['marketing-channel_code-statistic'],
        title: 'router.marketing.qrCodeStatistic',
        activeMenu: routePre + '/marketing/channel_code/channelCodeIndex',
      },
      component: () => import('@/pages/marketing/channelCode/codeStatistic'),
    },
    {
      path: 'point_record',
      name: `${pre}point_record`,
      meta: {
        auth: ['marketing-point_record-index'],
        title: 'router.marketing.integralRecord',
      },
      component: () => import('@/pages/marketing/point_record/index'),
    },
    {
      path: 'point_statistic',
      name: `${pre}point_statistic`,
      meta: {
        auth: ['marketing-point_statistic-index'],
        title: 'router.marketing.integralStatistic',
      },
      component: () => import('@/pages/marketing/point_statistic/index'),
    },
    {
      path: 'recharge',
      name: `${pre}recharge`,
      meta: {
        title: 'router.marketing.rechargeConfig',
      },
      component: () => import('@/pages/marketing/recharge/index'),
    },
    {
      path: 'sign',
      name: `${pre}sign`,
      meta: {
        title: 'router.marketing.signConfig',
      },
      component: () => import('@/pages/marketing/sign/index'),
    },
    {
      path: 'sign_rewards',
      name: `${pre}sign_rewards`,
      meta: {
        title: 'router.marketing.signRewards',
      },
      component: () => import('@/pages/marketing/sign/rewards'),
    },
    {
      path: `member_config/:type?/:tab_id?`,
      name: `${pre}member_config`,
      meta: {
        title: 'router.marketing.memberConfig',
      },
      component: () => import('@/pages/setting/setSystem/index'),
    },
    {
      path: `newuser/gift`,
      name: `${pre}gift`,
      meta: {
        title: 'router.marketing.newUserGift',
        auth: ['admin-marketing-new-user-gift'],
      },
      component: () => import('@/pages/marketing/newuser/gift'),
    },
  ],
};
