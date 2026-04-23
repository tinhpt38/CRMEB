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

namespace crmeb\basic;


use crmeb\interfaces\JobInterface;
use think\facade\Log;
use think\queue\Job;

/**
 * Lớp cơ sở hàng đợi tin nhắn
 * Class BaseJobs
 * @package crmeb\basic
 */
abstract class BaseJobs implements JobInterface
{

    /**
     * @param $name
     * @param $arguments
     */
    public function __call($name, $arguments)
    {
        $this->fire(...$arguments);
    }

    /**
     * Chạy hàng đợi tin nhắn
     * @param Job $job
     * @param $data
     */
    public function fire(Job $job, $data): void
    {
        try {
            $action = $data['do'] ?? 'doJob';//Tên nhiệm vụ
            $infoData = $data['data'] ?? [];//dữ liệu thực thi
            $errorCount = $data['errorCount'] ?? 0;//Số lỗi tối đa
            $this->runJob($action, $job, $infoData, $errorCount);
        } catch (\Throwable $e) {
            Log::error('lỗi xếp hàng：' . $e->getMessage());
            $job->delete();
        }
    }

    /**
     * hàng đợi thực thi
     * @param string $action
     * @param Job $job
     * @param array $infoData
     * @param int $errorCount
     */
    protected function runJob(string $action, Job $job, array $infoData, int $errorCount = 3)
    {

        $action = method_exists($this, $action) ? $action : 'handle';
        if (!method_exists($this, $action)) {
            $job->delete();
        }

        if ($this->{$action}(...$infoData)) {
            //Xóa tác vụ
            $job->delete();
        } else {
            if ($job->attempts() >= $errorCount && $errorCount) {
                //Xóa tác vụ
                $job->delete();
            } else {
                //Xếp hàng lại
                $job->release();
            }
        }

    }
}
