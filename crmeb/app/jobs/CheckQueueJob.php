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


use crmeb\basic\BaseJobs;
use crmeb\traits\QueueTrait;

/**
 * Kiểm tra xem hàng đợi tin nhắn có được thực thi hay không
 * Class CheckQueueJob
 * @package app\jobs
 */
class CheckQueueJob extends BaseJobs
{
    use QueueTrait;

    public function doJob($key)
    {
        $path = root_path('runtime') . '.queue';
        file_put_contents($path, $key);
        return true;
    }
}
