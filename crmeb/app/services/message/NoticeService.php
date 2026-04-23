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

namespace app\services\message;

use app\services\BaseServices;
use crmeb\services\CacheService;

/**
 * Danh mục dịch vụ tin nhắn trang web
 * Class MessageSystemServices
 */
class NoticeService extends BaseServices
{
    protected $noticeInfo;
    protected $event;

    /**
     * cài đặt
     * @param string $event
     * @return $this
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function setEvent(string $event)
    {
        if ($this->event != $event) {
            /** @var SystemNotificationServices $services */
            $services = app()->make(SystemNotificationServices::class);
            $noticeInfo = $services->getOneNotce(['mark' => $event]);
            $this->noticeInfo = $noticeInfo ? $noticeInfo->toArray() : [];
            $this->event = $event;
        }
        return $this;
    }
}
