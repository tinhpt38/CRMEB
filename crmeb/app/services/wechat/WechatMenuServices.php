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


use app\dao\wechat\WechatMenuDao;
use app\services\BaseServices;
use crmeb\exceptions\AdminException;
use crmeb\services\app\WechatService;

/**
 * Trình đơn WeChat
 * Class WechatMenuServices
 * @package app\services\wechat
 */class WechatMenuServices extends BaseServices
{
    /**
     * Người xây dựng
     * WechatMenuServices constructor.
     * @param WechatMenuDao $dao
     */    public function __construct(WechatMenuDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Nhận menu WeChat
     * @return array|mixed
     */    public function getWechatMenu()
    {
        $menus = $this->dao->value(['key' => 'wechat_menus'], 'result');
        return $menus ? json_decode($menus, true) : [];
    }

    /**
     * Lưu menu WeChat
     * @param array $buttons
     * @return bool
     */    public function saveMenu(array $buttons)
    {
        try {
            WechatService::menuService()->add($buttons);
            if ($this->dao->count(['key' => 'wechat_menus'])) {
                $this->dao->update('wechat_menus', ['result' => json_encode($buttons), 'add_time' => time()], 'key');
            } else {
                $this->dao->save(['key' => 'wechat_menus', 'result' => json_encode($buttons), 'add_time' => time()]);
            }
            return true;
        } catch (\Exception $e) {
            if (strstr($e->getMessage(), 'Request AccessToken fail. response')) {
                $msgData = str_replace('Request AccessToken fail. response: ', '', $e->getMessage());
                $msgData = json_decode($msgData, true);
                $errcode = $msgData['errcode'] ?? 0;
                if ($errcode == 40164) {
                    throw new AdminException('IP của bạn không còn trong danh sách trắng,Vui lòng truy cập nền tảng công cộng Tencent WeChat để thêm danh sách IP trắng');
                }
            }
            if (strstr($e->getMessage(), 'invalid weapp appid')) {
                throw new AdminException('Ứng dụng bạn điền không hợp lệ,Vui lòng kiểm tra');
            }
            throw new AdminException(WechatService::getMessage($e->getMessage()));
        }
    }
}
