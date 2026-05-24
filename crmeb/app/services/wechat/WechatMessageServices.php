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

namespace app\services\wechat;


use app\dao\wechat\WechatMessageDao;
use app\services\BaseServices;
use crmeb\exceptions\ApiException;
use crmeb\services\CacheService;

class WechatMessageServices extends BaseServices
{
    /**
     * Người xây dựng
     * WechatMessageServices constructor.
     * @param WechatMessageDao $dao
     */    public function __construct(WechatMessageDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @param $result
     * @param $openid
     * @param $type
     * @return \think\Model
     */    public function setMessage($result, $openid, $type)
    {
        if (is_object($result) || is_array($result)) $result = json_encode($result);
        $add_time = time();
        $data = compact('result', 'openid', 'type', 'add_time');
        return $this->dao->save($data);
    }

    public function setOnceMessage($result, $openid, $type, $unique, $cacheTime = 172800)
    {
        $cacheName = 'wechat_message_' . $type . '_' . $unique;
        if (CacheService::has($cacheName)) return true;
        $res = $this->setMessage($result, $openid, $type);
        if ($res) CacheService::set($cacheName, 1, $cacheTime);
        return $res;
    }

    /**
     * Hoạt động trước tin nhắn WeChat
     * @param $message
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function wechatMessageBefore($message)
    {
        //Khi nền được bật, Khách hàng sẽ chỉ được tạo khi họ theo dõi trực tiếp tài khoản công khai.
        if (intval(sys_config('create_wechat_user', 0))) {
            /** @var WechatUserServices $wechatUser */            $wechatUser = app()->make(WechatUserServices::class);
            $wechatUser->saveUser($message->FromUserName);
        }
        $event = isset($message->Event) ?
            $message->MsgType . (
            $message->Event == 'subscribe' && isset($message->EventKey) ? '_scan' : ''
            ) . '_' . $message->Event : $message->MsgType;
        $result = json_encode($message);
        $openid = $message->FromUserName;
        $type = strtolower($event);
        $add_time = time();
        if (!$this->dao->save(compact('result', 'openid', 'type', 'add_time'))) {
            throw new ApiException('Cập nhật thông tin không thành công');
        }
        return true;
    }
}
