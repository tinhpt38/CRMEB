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
namespace app\services\pay;

use app\services\activity\lottery\LuckLotteryRecordServices;
use app\services\statistic\CapitalFlowServices;
use app\services\user\UserExtractServices;
use app\services\user\UserServices;
use crmeb\exceptions\AdminException;

class PayTransferNotifyServices
{
    /**
     * Rút tiền mặt
     * @param string|null $order_id Đặt hàngid
     * @return bool
     */
    public function wechatTx(string $order_id = null, string $trade_no = null, string $state = null, string $fail_reason = null)
    {
        try {
            $userExtractServices = app()->make(UserExtractServices::class);
            $userExtractInfo = $userExtractServices->getOne(['transfer_bill_no' => $trade_no]);
            if (!$userExtractInfo) {
                return true;
            }
            $infoData['state'] = $state;
            if ($fail_reason) {
                $infoData['fail_reason'] = $fail_reason;
            }
            $userExtractServices->update($userExtractInfo['id'], $infoData);
            if ($state == 'SUCCESS') {
                /** @var UserServices $userService */
                $userService = app()->make(UserServices::class);
                $user = $userService->getUserInfo($userExtractInfo['uid']);
                $extractNumber = bcsub($userExtractInfo['extract_price'], $userExtractInfo['extract_fee'], 2);
                /** @var CapitalFlowServices $capitalFlowServices */
                $capitalFlowServices = app()->make(CapitalFlowServices::class);
                $capitalFlowServices->setFlow([
                    'order_id' => $order_id,
                    'uid' => $userExtractInfo['uid'],
                    'price' => bcmul('-1', $extractNumber, 2),
                    'pay_type' => $userExtractInfo['extract_type'],
                    'nickname' => $user['nickname'],
                    'phone' => $user['phone']
                ], 'extract');

                event('NoticeListener', [['uid' => $userExtractInfo['uid'], 'userType' => strtolower($user['user_type']), 'extractNumber' => $extractNumber, 'nickname' => $user['nickname']], 'user_extract']);

                //Thông báo tùy chỉnh-người dùng rút tiền thành công
                $userExtract['nickname'] = $user['nickname'];
                $userExtract['phone'] = $user['phone'];
                $userExtract['time'] = date('Y-m-d H:i:s');
                $userExtract['price'] = $extractNumber;
                event('CustomNoticeListener', [$userExtract['uid'], $userExtract, 'extract_success']);

                //Người dùng sự kiện tùy chỉnh rút tiền thành công
                event('CustomEventListener', ['admin_extract_success', [
                    'uid' => $userExtract['uid'],
                    'price' => $extractNumber,
                    'pay_type' => $userExtract['extract_type'],
                    'nickname' => $user['nickname'],
                    'phone' => $user['phone'],
                    'success_time' => date('Y-m-d H:i:s')
                ]]);
            } else {
                $userExtractServices->changeFail($userExtractInfo['id'], $userExtractInfo, 'Rút tiền không thành công, lý do: hết thời gian và không nhận được');
            }
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * phong bì màu đỏ
     * @param string|null $order_id Đặt hàngid
     * @return bool
     */
    public function wechatHb(string $order_id = null, string $trade_no = null, string $state = null, string $fail_reason = null)
    {
        try {
            $info = app()->make(LuckLotteryRecordServices::class)->getOne(['transfer_bill_no' => $trade_no]);
            if (!$info) {
                return true;
            }
            $info->state = $state;
            if ($fail_reason) {
                $info->fail_reason = $fail_reason;
            }
            $info->save();
        } catch (\Exception $e) {
            return false;
        }
    }
}
