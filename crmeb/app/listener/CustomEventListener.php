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
namespace app\listener;

use app\services\system\SystemEventServices;
use think\facade\Log;

class CustomEventListener
{
    public function handle($event)
    {
        [$mark, $data] = $event;
        try {
            $list = app()->make(SystemEventServices::class)->selectList(['mark' => $mark, 'is_del' => 0, 'is_open' => 1])->toArray();
            foreach ($list as $item) {
                eval(json_decode($item['customCode']));
            }
        } catch (\Throwable $e) {
            $listener_log_open = config("log.listener_log", false);
            if ($listener_log_open) {
                $date = date('Y-m-d H:i:s', time());
                Log::write($date . 'Lỗi sự kiện tùy chỉnh:' . $e->getMessage(), 'listener');
            }
        }
    }
}