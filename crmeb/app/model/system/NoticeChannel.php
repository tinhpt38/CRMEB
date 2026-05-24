<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------

namespace app\model\system;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

/**
 * Kênh thông báo dùng chung (Telegram, Zalo, SMS...).
 */class NoticeChannel extends BaseModel
{
    use ModelTrait;

    protected $pk = 'id';

    protected $name = 'notice_channel';

    // Tắt auto timestamp để tránh sai khác kiểu thời gian giữa các môi trường DB.
    protected $autoWriteTimestamp = false;
}

