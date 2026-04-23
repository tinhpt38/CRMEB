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

namespace app\services\kefu;


use app\services\BaseServices;
use app\dao\service\StoreServiceDao;
use app\services\kefu\service\StoreServiceAuxiliaryServices;
use app\services\kefu\service\StoreServiceServices;
use app\services\user\UserServices;
use app\services\wechat\WechatUserServices;
use crmeb\exceptions\ApiException;
use crmeb\services\workerman\ChannelService;
use app\services\kefu\service\StoreServiceLogServices;
use app\services\kefu\service\StoreServiceRecordServices;
use think\facade\Log;

/**
 * Class KefuServices
 * @package app\services\kefu
 */
class KefuServices extends BaseServices
{

    /**
     * KefuServices constructor.
     * @param StoreServiceDao $dao
     */
    public function __construct(StoreServiceDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Nhận danh sách dịch vụ khách hàng
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getServiceList(array $where, array $noId)
    {
        $where['status'] = 1;
        $where['noId'] = $noId;
        $where['online'] = 1;
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getServiceList($where, $page, $limit);
        $count = $this->dao->count($where);
        return compact('list', 'count');
    }

    /**
     * Nhận lịch sử trò chuyện
     * @param int $uid
     * @param int $toUid
     * @param int $isUp
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getChatList(int $uid, int $toUid, int $upperId, int $is_tourist = 0)
    {
        /** @var StoreServiceLogServices $service */
        $service = app()->make(StoreServiceLogServices::class);
        [$page, $limit] = $this->getPageValue();
        return array_reverse($service->tidyChat($service->getServiceChatList(['chat' => [$uid, $toUid], 'is_tourist' => $is_tourist], $limit, $upperId)));
    }

    /**
     * Chuyển dịch vụ khách hàng
     * @param int $kfuUid
     * @param int $uid
     * @param int $toUid
     * @return mixed
     */
    public function setTransfer(int $kfuUid, int $uid, int $kfuToUid)
    {
        if ($uid === $kfuToUid) {
            throw new ApiException('Bạn không thể chuyển nó cho chính mình');
        }
        /** @var StoreServiceAuxiliaryServices $auxiliaryServices */
        $auxiliaryServices = app()->make(StoreServiceAuxiliaryServices::class);
        /** @var StoreServiceLogServices $service */
        $service = app()->make(StoreServiceLogServices::class);
        $addTime = $auxiliaryServices->value(['binding_id' => $kfuUid, 'relation_id' => $uid], 'update_time');
        $list = $service->getMessageList(['chat' => [$kfuUid, $uid], 'add_time' => $addTime]);
        $data = [];
        foreach ($list as $item) {
            if ($item['to_uid'] == $kfuUid) {
                $item['to_uid'] = $kfuToUid;
            }
            if ($item['uid'] == $kfuUid) {
                $item['uid'] = $kfuToUid;
            }
            $item['add_time'] = time();
            unset($item['id']);
            $data[] = $item;
        }
        $record = $this->transaction(function () use ($data, $service, $kfuUid, $uid, $kfuToUid, $auxiliaryServices) {
            if ($data) {
                $num = count($data) - 1;
                $messageData = $data[$num] ?? [];
                $res = $service->saveAll($data);
            } else {
                $num = 0;
                $res = true;
                $messageData = [];
            }
            /** @var StoreServiceRecordServices $serviceRecord */
            $serviceRecord = app()->make(StoreServiceRecordServices::class);
            $info = $serviceRecord->get(['user_id' => $kfuUid, 'to_uid' => $uid], ['type', 'message_type', 'is_tourist', 'avatar', 'nickname']);
            $record = $serviceRecord->saveRecord($uid, $kfuToUid, $messageData['msn'] ?? '', $info['type'] ?? 1, $messageData['message_type'] ?? 1, $num, $info['is_tourist'] ?? 0, $info['nickname'] ?? "", $info['avatar'] ?? '');
            $res = $res && $auxiliaryServices->saveAuxliary(['binding_id' => $kfuUid, 'relation_id' => $uid]);
            if (!$res && !$record) {
                throw new ApiException('Chuyển sang dịch vụ khách hàng không thành công');
            }
            return $record;
        });
        try {
            if (!$record['is_tourist']) {
                /** @var UserServices $userService */
                $userService = app()->make(UserServices::class);
                $_userInfo = $userService->getUserInfo($uid, 'nickname,avatar');
                $record['nickname'] = $_userInfo['nickname'];
                $record['avatar'] = $_userInfo['avatar'];
            }
            $keufInfo = $this->dao->get(['uid' => $kfuUid], ['avatar', 'nickname']);
            if ($keufInfo) {
                $keufInfo = $keufInfo->toArray();
            } else {
                $keufInfo = (object)[];
            }
            //Gửi tin nhắn thông báo tới bộ phận chăm sóc khách hàng được chuyển
            ChannelService::instance()
                ->setTrigger('crmeb_chat')
                ->send('transfer', ['recored' => $record, 'kefuInfo' => $keufInfo, 'fun' => true], [$kfuToUid]);
            //Thông báo cho người dùng trò chuyện với người dùng này
            $keufToInfo = $this->dao->get(['uid' => $kfuToUid], ['avatar', 'nickname']);
            ChannelService::instance()
                ->setTrigger('crmeb_chat')
                ->send('to_transfer', ['toUid' => $kfuToUid, 'avatar' => $keufToInfo['avatar'] ?? '', 'nickname' => $keufToInfo['nickname'] ?? ''], [$uid]);
        } catch (\Exception $e) {
        }
        return true;
    }

    /**
     * Trả lời từ khóa, nếu không có từ khóa mặc định sẽ tự động gửi đến bộ phận chăm sóc khách hàng
     * @param string $reply
     * @param string $openId
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function replyTransferService(string $reply, string $openId)
    {
        /** @var WechatUserServices $userServices */
        $userServices = app()->make(WechatUserServices::class);
        $userInfo = $userServices->get(['openid' => $openId], ['uid', 'nickname', 'headimgurl as avatar']);
        if (!$userInfo) {
            return true;
        }
        /** @var StoreServiceServices $kfServices */
        $kfServices = app()->make(StoreServiceServices::class);
        $serviceInfoList = $kfServices->getServiceList(['status' => 1, 'online' => 1]);
        if (!count($serviceInfoList)) {
            return true;
        }
        $uids = array_column($serviceInfoList['list'], 'uid');
        if (!$uids) {
            return true;
        }
        /** @var StoreServiceRecordServices $recordServices */
        $recordServices = app()->make(StoreServiceRecordServices::class);
        //Cuộc trò chuyện ưu tiên dịch vụ khách hàng cuối cùng
        $toUid = $recordServices->getLatelyMsgUid(['to_uid' => $userInfo['uid']], 'user_id');
        //Nếu khách hàng mà bạn trò chuyện lần trước không thuộc bộ phận dịch vụ khách hàng hiện tại, hãy tuyển nhân viên dịch vụ khách hàng mới
        if (!in_array($toUid, $uids)) {
            $toUid = 0;
        }
        if (!$toUid) {
            $toUid = $uids[array_rand($uids)] ?? 0;
        }
        if (!$toUid) {
            return true;
        }
        /** @var StoreServiceLogServices $logServices */
        $logServices = app()->make(StoreServiceLogServices::class);
        $num = $logServices->getMessageNum(['uid' => $userInfo['uid'], 'to_uid' => $toUid, 'type' => 0, 'is_tourist' => 0]);
        $record = $recordServices->saveRecord($userInfo['uid'], $toUid, $reply, 1, 1, $num, 0, $userInfo['nickname'] ?? "", $userInfo['avatar'] ?? '');

        $data = [
            'add_time' => time(),
            'is_tourist' => 0,
            'to_uid' => $toUid,
            'msn' => $reply,
            'uid' => $userInfo['uid'],
            'type' => 0
        ];
        $data = $logServices->save($data);
        $data = $data->toArray();
        $data['_add_time'] = $data['add_time'];
        $data['add_time'] = strtotime($data['add_time']);
        $data['record'] = $record;

        try {
            ChannelService::instance()
                ->setTrigger('crmeb_chat')->send('mssage_num', [
                    'uid' => $userInfo['uid'],
                    'num' => $num,
                    'recored' => $data['record']
                ], [$toUid]);
            ChannelService::instance()
                ->setTrigger('crmeb_chat')->send('reply', $data, [$toUid]);
        } catch (\Throwable $e) {
            Log::error('Không thể đẩy tin nhắn nếu không mở kết nối dài. Nội dung tin nhắn là：' . $reply);
        }
    }
}
