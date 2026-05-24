<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2026 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

namespace app\jobs;

use app\services\activity\bargain\StoreBargainServices;
use app\services\activity\combination\StoreCombinationServices;
use app\services\activity\seckill\StoreSeckillServices;
use app\services\activity\coupon\StoreCouponUserServices;
use app\services\kefu\service\StoreServiceServices;
use app\services\message\notice\SmsService;
use app\services\order\OutStoreOrderServices;
use app\services\order\StoreOrderCartInfoServices;
use app\services\order\StoreOrderEconomizeServices;
use app\services\order\StoreOrderServices;
use app\services\product\product\StoreProductServices;
use app\services\user\member\MemberCardServices;
use app\services\user\UserLabelRelationServices;
use app\services\user\UserLevelServices;
use app\services\user\UserServices;
use app\services\wechat\WechatUserServices;
use crmeb\basic\BaseJobs;
use crmeb\services\app\WechatService;
use crmeb\services\workerman\ChannelService;
use crmeb\traits\QueueTrait;
use think\exception\ValidateException;
use think\facade\Log;

/**
 * Hàng đợi tin nhắn đặt hàng
 * Class OrderJob
 * @package crmeb\jobs
 */class OrderJob extends BaseJobs
{
    use QueueTrait;

    /**
     * Gửi tin nhắn khi thanh toán đơn hàng được thực hiện thành công
     * @param $order
     * @return bool
     */    public function doJob($order)
    {
        //Tính toán tiết kiệm sản phẩm
        try {
            $this->setEconomizeMoney($order);
        } catch (\Throwable $e) {
            Log::error('Tính toán tiết kiệm,Lý do thất bại:' . $e->getMessage());
        }
        //Cập nhật số lượng đơn hàng thanh toán của Khách hàng
        try {
            $this->setUserPayCountAndPromoter($order);
        } catch (\Throwable $e) {
            Log::error('Không thể cập nhật số đơn đặt hàng của Khách hàng,Lý do thất bại:' . $e->getMessage());
        }
        //Thêm thẻ Khách hàng
        try {
            $this->setUserLabel($order);
        } catch (\Throwable $e) {
            Log::error('Thêm thẻ Khách hàng không thành công,Lý do thất bại:' . $e->getMessage());
        }
        try {
            if (in_array($order['is_channel'], [0, 2])) {//Tài khoản chính thức gửi tin nhắn mẫu
                $this->sendOrderPaySuccessCustomerService($order, 1);
            } else if (in_array($order['is_channel'], [1, 2])) {//Chương trình nhỏ gửi tin nhắn mẫu
                $this->sendOrderPaySuccessCustomerService($order, 0);
            }
        } catch (\Exception $e) {
            throw new ValidateException('Gửi tin nhắn chăm sóc khách hàng,Tin nhắn SMS không thành công,Lý do thất bại:' . $e->getMessage());
        }


        //In phiếu giao hàng
//        $switch = sys_config('pay_success_printing_switch') ? true : false;
//        if ($switch) {
//            try {
//                /** @var StoreOrderServices $orderServices */
//                $orderServices = app()->make(StoreOrderServices::class);
//                $orderServices->orderPrint($order, $order['cart_id']);
//            } catch (\Throwable $e) {
//                Log::error('Đã xảy ra lỗi khi in biên lai,Lý do lỗi:' . $e->getMessage());
//            }
//        }

        //Kiểm tra cấp độ thành viên
        try {
            /** @var UserLevelServices $levelServices */            $levelServices = app()->make(UserLevelServices::class);
            $levelServices->detection((int)$order['uid']);
        } catch (\Throwable $e) {
            Log::error('Nâng cấp cấp thành viên không thành công,Lý do thất bại:' . $e->getMessage());
        }
        //Gửi tin nhắn đặt hàng mới tới nền
        try {
            ChannelService::instance()->send('NEW_ORDER', ['order_id' => $order['order_id']]);
        } catch (\Throwable $e) {
            Log::error('Không thể gửi tin nhắn đặt hàng mới tới nền,Lý do thất bại:' . $e->getMessage());
        }
        return true;
    }

    /**
     * Đặt số lượng Khách hàng mua hàng và thời gian phát hiện để trở thành người quảng bá
     * @param $order
     */    public function setUserPayCountAndPromoter($order)
    {
        /** @var UserServices $userServices */        $userServices = app()->make(UserServices::class);
        $userInfo = $userServices->get($order['uid']);
        if ($userInfo) {
            $userInfo->pay_count = $userInfo->pay_count + 1;
            if (!$userInfo->is_promoter) {
                /** @var StoreOrderServices $orderServices */                $orderServices = app()->make(StoreOrderServices::class);
                $price = $orderServices->sum(['paid' => 1, 'refund_status' => 0, 'uid' => $userInfo['uid']], 'pay_price');
                $status = is_brokerage_statu($price);
                if ($status) {
                    $userInfo->is_promoter = 1;
                }
            }
            $userInfo->save();
        }
    }

    /**
     * Đặt thẻ cho giao dịch mua của Khách hàng
     * @param $order
     */    public function setUserLabel($order)
    {
        /** @var StoreOrderCartInfoServices $cartInfoServices */        $cartInfoServices = app()->make(StoreOrderCartInfoServices::class);
        $productIds = $cartInfoServices->getCartColunm(['oid' => $order['id']], 'product_id', '');
        /** @var StoreProductServices $productServices */        $productServices = app()->make(StoreProductServices::class);
        $label = $productServices->getColumn([['id', 'in', $productIds]], 'label_id');
        $labelIds = array_unique(explode(',', implode(',', $label)));
        /** @var UserLabelRelationServices $labelServices */        $labelServices = app()->make(UserLabelRelationServices::class);
        $where = [
            ['label_id', 'in', $labelIds],
            ['uid', '=', $order['uid']]
        ];
        $data = [];
        $userLabel = $labelServices->getColumn($where, 'label_id');
        foreach ($labelIds as $item) {
            if (!in_array($item, $userLabel)) {
                $data[] = ['uid' => $order['uid'], 'label_id' => $item];
            }
        }
        $re = true;
        if ($data) {
            $re = $labelServices->saveAll($data);
        }
        return $re;
    }


    /**
     * Sau khi thanh toán đơn hàng thành công, gửi tin nhắn chăm sóc khách hàng đến bộ phận chăm sóc khách hàng
     * @param $order
     * @param int $type 1 Tài khoản chính thức 0 Chương trình nhỏ
     * @return string
     */    public function sendOrderPaySuccessCustomerService($order, $type = 0)
    {
        /** @var StoreServiceServices $services */        $services = app()->make(StoreServiceServices::class);
        /** @var WechatUserServices $wechatUserServices */        $wechatUserServices = app()->make(WechatUserServices::class);
        $serviceOrderNotice = $services->getStoreServiceOrderNotice();
        if (count($serviceOrderNotice)) {
            /** @var StoreProductServices $services */            $services = app()->make(StoreProductServices::class);
            /** @var StoreSeckillServices $seckillServices */            $seckillServices = app()->make(StoreSeckillServices::class);
            /** @var StoreCombinationServices $pinkServices */            $pinkServices = app()->make(StoreCombinationServices::class);
            /** @var StoreBargainServices $bargainServices */            $bargainServices = app()->make(StoreBargainServices::class);
            /** @var StoreOrderCartInfoServices $cartInfoServices */            $cartInfoServices = app()->make(StoreOrderCartInfoServices::class);
            foreach ($serviceOrderNotice as $item) {
                $userInfo = $wechatUserServices->getOne(['uid' => $item['uid'], 'user_type' => 'wechat']);
                if ($userInfo) {
                    $userInfo = $userInfo->toArray();
                    if ($userInfo['subscribe'] && $userInfo['openid']) {
                        if ($item['customer']) {
                            // Bật quản lý thống kê và đẩy tin nhắn đồ họa
                            $head = 'Số đơn hàng nhắc nhở đặt hàng：' . $order['order_id'];
                            $url = sys_config('site_url') . '/pages/admin/orderDetail/index?id=' . $order['order_id'];
                            $description = '';
                            $image = sys_config('site_logo');
                            if (isset($order['seckill_id']) && $order['seckill_id'] > 0) {
                                $description .= 'mặt hàng flash sale：' . $seckillServices->value(['id' => $order['seckill_id']], 'title');
                                $image = $seckillServices->value(['id' => $order['seckill_id']], 'image');
                            } else if (isset($order['combination_id']) && $order['combination_id'] > 0) {
                                $description .= 'Sản phẩm mua chung：' . $pinkServices->value(['id' => $order['combination_id']], 'title');
                                $image = $pinkServices->value(['id' => $order['combination_id']], 'image');
                            } else if (isset($order['bargain_id']) && $order['bargain_id'] > 0) {
                                $title = $bargainServices->value(['id' => $order['bargain_id']], 'title');
                                $description .= 'Sản phẩm trả giá：' . $title;
                                $image = $bargainServices->value(['id' => $order['bargain_id']], 'image');
                            } else {
                                $productIds = $cartInfoServices->getCartIdsProduct($order['id']);
                                $storeProduct = $services->getProductArray([['id', 'in', $productIds]], 'image,store_name', 'id');
                                if (count($storeProduct)) {
                                    foreach ($storeProduct as $value) {
                                        $description .= $value['store_name'] . '  ';
                                        $image = $value['image'];
                                    }
                                }
                            }
                            $message = WechatService::newsMessage($head, $description, $url, $image);
                            try {
                                WechatService::staffService()->message($message)->to($userInfo['openid'])->send();
                            } catch (\Exception $e) {
                                Log::error($userInfo['nickname'] . 'Gửi không thành công' . $e->getMessage());
                            }
                        } else {
                            // Tin nhắn văn bản đẩy
                            $head = "Nhắc nhở CSKH: thân mến,Bạn có một đơn đặt hàng mới \r\nSố đơn hàng:{$order['order_id']}\r\nSố tiền thanh toán：" . format_vnd($order['pay_price']) . "\r\nBình luận：{$order['mark']}\r\nNguồn đặt hàng: Chương trình nhỏ";
                            if ($type) $head = "Nhắc nhở CSKH: thân mến,Bạn có một đơn đặt hàng mới \r\nSố đơn hàng:{$order['order_id']}\r\nSố tiền thanh toán：" . format_vnd($order['pay_price']) . "\r\nBình luận：{$order['mark']}\r\nNguồn đặt hàng: Tài khoản chính thức";
                            try {
                                WechatService::staffService()->message($head)->to($userInfo['openid'])->send();
                            } catch (\Exception $e) {
                                Log::error($userInfo['nickname'] . 'Gửi không thành công' . $e->getMessage());
                            }
                        }
                    }
                }

            }
        }
    }

    /**
     * Tính toán tiết kiệm
     * @param $order
     * @return false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function setEconomizeMoney($order)
    {
        /** @var UserServices $userService */        $userService = app()->make(UserServices::class);
        /** @var StoreOrderCartInfoServices $cartInfoService */        $cartInfoService = app()->make(StoreOrderCartInfoServices::class);
        /** @var StoreCouponUserServices $couponService */        $couponService = app()->make(StoreCouponUserServices::class);
        /** @var StoreOrderEconomizeServices $economizeService */        $economizeService = app()->make(StoreOrderEconomizeServices::class);
        /** @var MemberCardServices $memberCardService */        $memberCardService = app()->make(MemberCardServices::class);
        $getOne = $economizeService->getOne(['order_id' => $order['order_id']]);
        if ($getOne) return false;
        //Kiểm tra xem bạn có phải là thành viên không
        $userInfo = $userService->getUserInfo($order['uid']);
        if ($userInfo && $userInfo['is_money_level'] > 0) {
            $save = [];
            $save['order_type'] = 1;
            $save['add_time'] = time();
            $save['pay_price'] = $order['pay_price'];
            $save['order_id'] = $order['order_id'];
            $save['uid'] = $order['uid'];
            //Tính toán tiết kiệm sản phẩm
            $isOpenVipPrice = $memberCardService->isOpenMemberCard('vip_price');
            if ($isOpenVipPrice) {
                $cartInfo = $cartInfoService->getOrderCartInfo($order['id']);
                $memberPrice = 0.00;
                if ($cartInfo) {
                    foreach ($cartInfo as $k => $item) {
                        foreach ($item as $value) {
                            if (isset($value['price_type']) && $value['price_type'] == 'member') $memberPrice += bcmul($value['vip_truePrice'], $value['cart_num'] ?: 1, 2);
                        }
                    }
                }
                $save['member_price'] = $memberPrice;
            }
            //Tính toán tiết kiệm bưu phí
            $isOpenExpress = $memberCardService->isOpenMemberCard('express');
            if ($isOpenExpress) {
                $expressTotalMoney = bcdiv($order['total_postage'], bcdiv($isOpenExpress, 100, 2), 2);
                $save['postage_price'] = bcsub($expressTotalMoney, $order['total_postage'], 2);
            }

            //Tính toán tiết kiệm phiếu thành viên
            if ($order['coupon_id']) {
                $couponMoney = $couponService->get($order['coupon_id'], ['*'], ['issue']);
                if ($couponMoney && $couponMoney['receive_type']) {
                    $save['coupon_price'] = $couponMoney['coupon_price'];
                }
            }
            return $economizeService->addEconomize($save);
        }
        return false;

    }
}
