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
namespace app\adminapi\controller\v1\application\wechat;

use app\adminapi\controller\AuthController;
use app\jobs\notice\SyncMessageJob;
use app\services\message\SystemNotificationServices;
use crmeb\exceptions\AdminException;
use crmeb\services\app\WechatService;
use think\facade\App;

/**
 * Tin nhắn mẫu WeChat
 * Class WechatTemplate
 * @package app\adminapi\controller\v1\application\wechat
 */
class WechatTemplate extends AuthController
{
    /**
     * Người xây dựng
     * WechatTemplate constructor.
     * @param App $app
     * @param SystemNotificationServices $services
     */
    public function __construct(App $app, SystemNotificationServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Đồng bộ hóa tin nhắn mẫu WeChat
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function syncSubscribe()
    {
        if (!sys_config('wechat_appid') || !sys_config('wechat_appsecret')) {
            throw new AdminException('Trước tiên hãy định cấu hình ứng dụng tài khoản công khai WeChat, appSecret và các thông số khác');
        }

        $tempIds = $this->services->getTempId('wechat');
        foreach ($tempIds as $v) {
            WechatService::deleleTemplate($v);
        }

        $tempKeys = $this->services->getTempKey('wechat');

        foreach ($tempKeys as $key => $content) {
            SyncMessageJob::dispatch('SyncWechat', [$key, $content['wechat_content']]);
        }
        return app('json')->success('Đồng bộ hóa thành công');
    }
}
