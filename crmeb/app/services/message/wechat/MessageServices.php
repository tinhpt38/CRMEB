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

namespace app\services\message\wechat;


use app\services\activity\bargain\StoreBargainServices;
use app\services\activity\combination\StoreCombinationServices;
use app\services\activity\combination\StorePinkServices;
use app\services\activity\seckill\StoreSeckillServices;
use app\services\BaseServices;
use app\services\other\QrcodeServices;
use app\services\product\product\StoreProductServices;
use app\services\user\LoginServices;
use app\services\user\UserServices;
use app\services\wechat\WechatQrcodeServices;
use app\services\wechat\WechatReplyServices;
use app\services\wechat\WechatUserServices;
use crmeb\services\app\WechatService;
use think\facade\Log;

class MessageServices extends BaseServices
{

    /**
     * Quét mã
     * @param $message
     * @return array|\EasyWeChat\Message\Image|\EasyWeChat\Message\News|\EasyWeChat\Message\Text|\EasyWeChat\Message\Transfer|\EasyWeChat\Message\Voice|mixed|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2023/02/24
     */
    public function wechatEventScan($message)
    {
        /** @var QrcodeServices $qrcodeService */
        $qrcodeService = app()->make(QrcodeServices::class);
        /** @var WechatReplyServices $wechatReplyService */
        $wechatReplyService = app()->make(WechatReplyServices::class);
        /** @var WechatUserServices $wechatUser */
        $wechatUser = app()->make(WechatUserServices::class);
        /** @var LoginServices $loginService */
        $loginService = app()->make(LoginServices::class);
        /** @var UserServices $userService */
        $userService = app()->make(UserServices::class);

        $response = $wechatReplyService->reply('subscribe');
        if ($message->EventKey && ($qrInfo = $qrcodeService->getQrcode($message->Ticket, 'ticket'))) {
            $qrcodeService->scanQrcode($message->Ticket, 'ticket');
            $thirdType = explode('-', $qrInfo['third_type']);
            $baseUrl = sys_config('site_url');
            if (in_array(strtolower($thirdType[0]), ['spread', 'agent', 'wechatqrcode', 'product', 'combination', 'seckill', 'bargain', 'pink'])) {
                //Quét mã QR yêu cầu tạo luồng người dùng
                $spreadUid = $qrInfo['third_id'];
                $spreadInfo = $userService->get($spreadUid);
                $is_new = $wechatUser->saveUser($message->FromUserName);
                $uid = $wechatUser->getFieldValue($message->FromUserName, 'openid', 'uid', ['user_type', '<>', 'h5']);
                $userInfo = $userService->get($uid);
                try {
                    switch (strtolower($thirdType[0])) {
                        case 'spread':
                            if ($spreadUid == $uid) {
                                $response = 'Tôi không thể giới thiệu bản thân mình';
                            } else if (!$userInfo) {
                                $response = 'Người dùng không tồn tại';
                            } else if (!$spreadInfo) {
                                $response = 'Người dùng cao cấp không tồn tại';
                            } else if ($userInfo['spread_uid']) {
                                $response = 'Đã có người giới thiệu!';
                            } else if (!$loginService->updateUserInfo(['code' => $spreadUid], $userInfo, $is_new)) {
                                $response = 'Không thể liên kết người giới thiệu!';
                            }
                            $wechatNews['title'] = sys_config('site_name');
                            $wechatNews['image'] = sys_config('wap_login_logo');
                            $wechatNews['url'] = $baseUrl . '/pages/index/index';
                            $loginService->updateUserInfo(['code' => $spreadUid], $userInfo, $is_new);
                            $messages = WechatService::newsMessage($wechatNews);
                            WechatService::staffService()->message($messages)->to($message->FromUserName)->send();
                            break;
                        case 'agent':
                            if ($spreadUid == $uid) {
                                $response = 'Tôi không thể giới thiệu bản thân mình';
                            } else if (!$userInfo) {
                                $response = 'Người dùng không tồn tại';
                            } else if (!$spreadInfo) {
                                $response = 'Người dùng cao cấp không tồn tại';
                            } else if ($userInfo->is_division) {
                                $response = 'Bạn là bộ phận kinh doanh,Không thể bị ràng buộc trở thành nhân viên của người khác';
                            } else if ($userInfo->is_agent) {
                                $response = 'Bạn là một đại lý,Không thể bị ràng buộc trở thành nhân viên của người khác';
                            } else if ($loginService->updateUserInfo(['code' => $spreadUid, 'is_staff' => 1], $userInfo, $is_new)) {
                                $response = 'Liên kết nhân viên cửa hàng thành công!';
                            }
                            break;
                        case 'wechatqrcode':
                            /** @var WechatQrcodeServices $wechatQrcodeService */
                            $wechatQrcodeService = app()->make(WechatQrcodeServices::class);
                            //wechatqrcodeloại dữ liệu mã QR,third_idĐối với mã kênhid
                            $qrcodeInfo = $wechatQrcodeService->qrcodeInfo($qrInfo['third_id']);
                            $spreadUid = $qrcodeInfo['uid'];
                            $spreadInfo = $userService->get($spreadUid);
                            $is_new = $wechatUser->saveUser($message->FromUserName);
                            $uid = $wechatUser->getFieldValue($message->FromUserName, 'openid', 'uid', ['user_type', '<>', 'h5']);
                            $userInfo = $userService->get($uid);
                            if ($qrcodeInfo['status'] == 0 || $qrcodeInfo['is_del'] == 1 || ($qrcodeInfo['end_time'] < time() && $qrcodeInfo['end_time'] > 0)) {
                                $response = 'Mã QR đã hết hạn';
                            } else if ($spreadUid == $uid) {
                                $response = 'Tôi không thể giới thiệu bản thân mình';
                            } else if (!$userInfo) {
                                $response = 'Người dùng không tồn tại';
                            } else if (!$spreadInfo) {
                                $response = 'Người dùng cao cấp không tồn tại';
                            } else if ($loginService->updateUserInfo(['code' => $spreadUid], $userInfo, $is_new)) {
                                //Viết bản ghi mã quét,Trả lại nội dung
                                $response = $wechatQrcodeService->wechatQrcodeRecord($qrcodeInfo, $userInfo, $spreadInfo);
                            }
                            break;
                        case 'product':
                            /** @var StoreProductServices $productService */
                            $productService = app()->make(StoreProductServices::class);
                            $productInfo = $productService->get($thirdType[1] ?? 0);
                            $wechatNews['title'] = $productInfo->store_name;
                            $wechatNews['image'] = $productInfo->image;
                            $wechatNews['description'] = $productInfo->store_info;
                            $wechatNews['url'] = $baseUrl . '/pages/goods_details/index?id=' . $thirdType[1];
                            $loginService->updateUserInfo(['code' => $spreadUid], $userInfo, $is_new);
                            $messages = WechatService::newsMessage($wechatNews);
                            WechatService::staffService()->message($messages)->to($message->FromUserName)->send();
                            break;
                        case 'combination':
                            /** @var StoreCombinationServices $combinationService */
                            $combinationService = app()->make(StoreCombinationServices::class);
                            $productInfo = $combinationService->get($thirdType[1] ?? 0);
                            $wechatNews['title'] = $productInfo->title;
                            $wechatNews['image'] = $productInfo->image;
                            $wechatNews['description'] = $productInfo->info;
                            $wechatNews['url'] = $baseUrl . '/pages/activity/goods_combination_details/index?id=' . $thirdType[1];
                            $loginService->updateUserInfo(['code' => $spreadUid], $userInfo, $is_new);
                            $messages = WechatService::newsMessage($wechatNews);
                            WechatService::staffService()->message($messages)->to($message->FromUserName)->send();
                            break;
                        case 'seckill':
                            /** @var StoreSeckillServices $seckillService */
                            $seckillService = app()->make(StoreSeckillServices::class);
                            $productInfo = $seckillService->get($thirdType[1] ?? 0);
                            $wechatNews['title'] = $productInfo->title;
                            $wechatNews['image'] = $productInfo->image;
                            $wechatNews['description'] = $productInfo->info;
                            $wechatNews['url'] = $baseUrl . '/pages/activity/goods_seckill_details/index?id=' . $thirdType[1];
                            $loginService->updateUserInfo(['code' => $spreadUid], $userInfo, $is_new);
                            $messages = WechatService::newsMessage($wechatNews);
                            WechatService::staffService()->message($messages)->to($message->FromUserName)->send();
                            break;
                        case 'bargain':
                            /** @var StoreBargainServices $bargainService */
                            $bargainService = app()->make(StoreBargainServices::class);
                            $productInfo = $bargainService->get($thirdType[1] ?? 0);
                            $wechatNews['title'] = $productInfo->title;
                            $wechatNews['image'] = $productInfo->image;
                            $wechatNews['description'] = $productInfo->info;
                            $wechatNews['url'] = $baseUrl . '/pages/activity/goods_bargain_details/index?id=' . $thirdType[1] . '&bargain=' . $thirdType[2];
                            $loginService->updateUserInfo(['code' => $spreadUid], $userInfo, $is_new);
                            $messages = WechatService::newsMessage($wechatNews);
                            WechatService::staffService()->message($messages)->to($message->FromUserName)->send();
                            break;
                        case 'pink':
                            /** @var StorePinkServices $pinkService */
                            $pinkService = app()->make(StorePinkServices::class);
                            /** @var StoreCombinationServices $combinationService */
                            $combinationService = app()->make(StoreCombinationServices::class);
                            $pinktInfo = $pinkService->get($thirdType[1]);
                            $productInfo = $combinationService->get($pinktInfo->cid);
                            $wechatNews['title'] = $productInfo->title;
                            $wechatNews['image'] = $productInfo->image;
                            $wechatNews['description'] = $productInfo->info;
                            $wechatNews['url'] = $baseUrl . '/pages/activity/goods_combination_status/index?id=' . $thirdType[1];
                            $loginService->updateUserInfo(['code' => $spreadUid], $userInfo, $is_new);
                            $messages = WechatService::newsMessage($wechatNews);
                            WechatService::staffService()->message($messages)->to($message->FromUserName)->send();
                            break;
                    }
                } catch (\Throwable $e) {
                    $response = $e->getMessage();
                }
            } else {
                //Quét mã QR không tạo ra luồng người dùng
            }
        }
        return $response;
    }

    /**
     * Hủy theo dõi
     * @param $message
     */
    public function wechatEventUnsubscribe($message)
    {
        /** @var WechatUserServices $wechatUser */
        $wechatUser = app()->make(WechatUserServices::class);
        $wechatUser->unSubscribe($message->FromUserName);
    }

    /**
     * Theo dõi tài khoản công khai
     * @param $message
     * @return array|\EasyWeChat\Message\Image|\EasyWeChat\Message\News|\EasyWeChat\Message\Text|\EasyWeChat\Message\Transfer|\EasyWeChat\Message\Voice|mixed|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2023/02/24
     */
    public function wechatEventSubscribe($message)
    {
        /** @var QrcodeServices $qrcodeService */
        $qrcodeService = app()->make(QrcodeServices::class);
        /** @var WechatReplyServices $wechatReplyService */
        $wechatReplyService = app()->make(WechatReplyServices::class);
        /** @var WechatUserServices $wechatUser */
        $wechatUser = app()->make(WechatUserServices::class);
        /** @var LoginServices $loginService */
        $loginService = app()->make(LoginServices::class);
        /** @var UserServices $userService */
        $userService = app()->make(UserServices::class);

        $response = $wechatReplyService->reply('subscribe');
        if ($message->EventKey && ($qrInfo = $qrcodeService->getQrcode($message->Ticket, 'ticket'))) {
            $qrcodeService->scanQrcode($message->Ticket, 'ticket');
            $thirdType = explode('-', $qrInfo['third_type']);
            $baseUrl = sys_config('site_url');
            if (in_array(strtolower($thirdType[0]), ['spread', 'agent', 'wechatqrcode', 'product', 'combination', 'seckill', 'bargain', 'pink'])) {
                //Quét mã QR yêu cầu tạo luồng người dùng
                $spreadUid = $qrInfo['third_id'];
                $spreadInfo = $userService->get($spreadUid);
                $is_new = $wechatUser->saveUser($message->FromUserName);
                $uid = $wechatUser->getFieldValue($message->FromUserName, 'openid', 'uid', ['user_type', '<>', 'h5']);
                $userInfo = $userService->get($uid);
                try {
                    switch (strtolower($thirdType[0])) {
                        case 'spread':
                            if ($spreadUid == $uid) {
                                $response = 'Tôi không thể giới thiệu bản thân mình';
                            } else if (!$userInfo) {
                                $response = 'Người dùng không tồn tại';
                            } else if (!$spreadInfo) {
                                $response = 'Người dùng cao cấp không tồn tại';
                            } else if ($userInfo['spread_uid']) {
                                $response = 'Đã có người giới thiệu!';
                            } else if (!$loginService->updateUserInfo(['code' => $spreadUid], $userInfo, $is_new)) {
                                $response = 'Không thể liên kết người giới thiệu!';
                            }
                            $wechatNews['title'] = sys_config('site_name');
                            $wechatNews['image'] = sys_config('wap_login_logo');
                            $wechatNews['url'] = $baseUrl . '/pages/index/index';
                            $loginService->updateUserInfo(['code' => $spreadUid], $userInfo, $is_new);
                            $messages = WechatService::newsMessage($wechatNews);
                            WechatService::staffService()->message($messages)->to($message->FromUserName)->send();
                            break;
                        case 'agent':
                            if ($spreadUid == $uid) {
                                $response = 'Tôi không thể giới thiệu bản thân mình';
                            } else if (!$userInfo) {
                                $response = 'Người dùng không tồn tại';
                            } else if (!$spreadInfo) {
                                $response = 'Người dùng cao cấp không tồn tại';
                            } else if ($userInfo->is_division) {
                                $response = 'Bạn là bộ phận kinh doanh,Không thể bị ràng buộc trở thành nhân viên của người khác';
                            } else if ($userInfo->is_agent) {
                                $response = 'Bạn là một đại lý,Không thể bị ràng buộc trở thành nhân viên của người khác';
                            } else if ($loginService->updateUserInfo(['code' => $spreadUid, 'is_staff' => 1], $userInfo, $is_new)) {
                                $response = 'Liên kết nhân viên cửa hàng thành công!';
                            }
                            break;
                        case 'wechatqrcode':
                            /** @var WechatQrcodeServices $wechatQrcodeService */
                            $wechatQrcodeService = app()->make(WechatQrcodeServices::class);
                            //wechatqrcodeloại dữ liệu mã QR,third_idĐối với mã kênhid
                            $qrcodeInfo = $wechatQrcodeService->qrcodeInfo($qrInfo['third_id']);
                            $spreadUid = $qrcodeInfo['uid'];
                            $spreadInfo = $userService->get($spreadUid);
                            $is_new = $wechatUser->saveUser($message->FromUserName);
                            $uid = $wechatUser->getFieldValue($message->FromUserName, 'openid', 'uid', ['user_type', '<>', 'h5']);
                            $userInfo = $userService->get($uid);
                            if ($qrcodeInfo['status'] == 0 || $qrcodeInfo['is_del'] == 1 || ($qrcodeInfo['end_time'] < time() && $qrcodeInfo['end_time'] > 0)) {
                                $response = 'Mã QR đã hết hạn';
                            } else if ($spreadUid == $uid) {
                                $response = 'Tôi không thể giới thiệu bản thân mình';
                            } else if (!$userInfo) {
                                $response = 'Người dùng không tồn tại';
                            } else if (!$spreadInfo) {
                                $response = 'Người dùng cao cấp không tồn tại';
                            } else if ($loginService->updateUserInfo(['code' => $spreadUid], $userInfo, $is_new)) {
                                //Viết bản ghi mã quét,Trả lại nội dung
                                $response = $wechatQrcodeService->wechatQrcodeRecord($qrcodeInfo, $userInfo, $spreadInfo);
                            }
                            break;
                        case 'product':
                            /** @var StoreProductServices $productService */
                            $productService = app()->make(StoreProductServices::class);
                            $productInfo = $productService->get($thirdType[1] ?? 0);
                            $wechatNews['title'] = $productInfo->store_name;
                            $wechatNews['image'] = $productInfo->image;
                            $wechatNews['description'] = $productInfo->store_info;
                            $wechatNews['url'] = $baseUrl . '/pages/goods_details/index?id=' . $thirdType[1];
                            $loginService->updateUserInfo(['code' => $spreadUid], $userInfo, $is_new);
                            $messages = WechatService::newsMessage($wechatNews);
                            WechatService::staffService()->message($messages)->to($message->FromUserName)->send();
                            break;
                        case 'combination':
                            /** @var StoreCombinationServices $combinationService */
                            $combinationService = app()->make(StoreCombinationServices::class);
                            $productInfo = $combinationService->get($thirdType[1] ?? 0);
                            $wechatNews['title'] = $productInfo->title;
                            $wechatNews['image'] = $productInfo->image;
                            $wechatNews['description'] = $productInfo->info;
                            $wechatNews['url'] = $baseUrl . '/pages/activity/goods_combination_details/index?id=' . $thirdType[1];
                            $loginService->updateUserInfo(['code' => $spreadUid], $userInfo, $is_new);
                            $messages = WechatService::newsMessage($wechatNews);
                            WechatService::staffService()->message($messages)->to($message->FromUserName)->send();
                            break;
                        case 'seckill':
                            /** @var StoreSeckillServices $seckillService */
                            $seckillService = app()->make(StoreSeckillServices::class);
                            $productInfo = $seckillService->get($thirdType[1] ?? 0);
                            $wechatNews['title'] = $productInfo->title;
                            $wechatNews['image'] = $productInfo->image;
                            $wechatNews['description'] = $productInfo->info;
                            $wechatNews['url'] = $baseUrl . '/pages/activity/goods_seckill_details/index?id=' . $thirdType[1];
                            $loginService->updateUserInfo(['code' => $spreadUid], $userInfo, $is_new);
                            $messages = WechatService::newsMessage($wechatNews);
                            WechatService::staffService()->message($messages)->to($message->FromUserName)->send();
                            break;
                        case 'bargain':
                            /** @var StoreBargainServices $bargainService */
                            $bargainService = app()->make(StoreBargainServices::class);
                            $productInfo = $bargainService->get($thirdType[1] ?? 0);
                            $wechatNews['title'] = $productInfo->title;
                            $wechatNews['image'] = $productInfo->image;
                            $wechatNews['description'] = $productInfo->info;
                            $wechatNews['url'] = $baseUrl . '/pages/activity/goods_bargain_details/index?id=' . $thirdType[1] . '&bargain=' . $thirdType[2];
                            $loginService->updateUserInfo(['code' => $spreadUid], $userInfo, $is_new);
                            $messages = WechatService::newsMessage($wechatNews);
                            WechatService::staffService()->message($messages)->to($message->FromUserName)->send();
                            break;
                        case 'pink':
                            /** @var StorePinkServices $pinkService */
                            $pinkService = app()->make(StorePinkServices::class);
                            /** @var StoreCombinationServices $combinationService */
                            $combinationService = app()->make(StoreCombinationServices::class);
                            $pinktInfo = $pinkService->get($thirdType[1]);
                            $productInfo = $combinationService->get($pinktInfo->cid);
                            $wechatNews['title'] = $productInfo->title;
                            $wechatNews['image'] = $productInfo->image;
                            $wechatNews['description'] = $productInfo->info;
                            $wechatNews['url'] = $baseUrl . '/pages/activity/goods_combination_status/index?id=' . $thirdType[1];
                            $loginService->updateUserInfo(['code' => $spreadUid], $userInfo, $is_new);
                            $messages = WechatService::newsMessage($wechatNews);
                            WechatService::staffService()->message($messages)->to($message->FromUserName)->send();
                            break;
                    }
                } catch (\Throwable $e) {
                    $response = $e->getMessage();
                }
            } else {
                //Quét mã QR không tạo ra luồng người dùng
            }
        }

        // Cập nhật cờ theo dõi
        if (!is_string($response)) {
            $wechatUser->subscribe($message->FromUserName);
        }
        return $response;
    }

    /**
     * địa điểm sự kiện
     * @param $message
     * @return string
     */
    public function wechatEventLocation($message)
    {
        //return 'location';
    }

    /**
     * Sự kiện nhảy URL
     * @param $message
     * @return string
     */
    public function wechatEventView($message)
    {
        //return 'view';
    }

    /**
     * Tin nhắn hình ảnh
     * @param $message
     * @return string
     */
    public function wechatMessageImage($message)
    {
        //return 'image';
    }

    /**
     * tin nhắn thoại
     * @param $message
     * @return string
     */
    public function wechatMessageVoice($message)
    {
        //return 'voice';
    }

    /**
     * tin nhắn video
     * @param $message
     * @return string
     */
    public function wechatMessageVideo($message)
    {
        //return 'video';
    }

    /**
     * tin nhắn vị trí
     */
    public function wechatMessageLocation($message)
    {
        //return 'location';
    }

    /**
     * tin nhắn liên kết
     * @param $message
     * @return string
     */
    public function wechatMessageLink($message)
    {
        //return 'link';
    }

    /**
     * Tin tức khác
     */
    public function wechatMessageOther($message)
    {
        //return 'other';
    }
}
