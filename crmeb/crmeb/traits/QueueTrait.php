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

namespace crmeb\traits;


use crmeb\utils\Queue;
use think\facade\Env;

/**
 * Nhanh chóng tham gia vào hàng đợi tin nhắn
 * Trait QueueTrait
 * @package crmeb\traits
 */
trait QueueTrait
{
    /**
     * Danh sách
     * @return null
     */
    protected static function queueName()
    {
        return null;
    }

    /**
     * tham gia hàng đợi
     * @param $action
     * @param array $data
     * @param string|null $queueName
     * @return mixed
     */
    public static function dispatch($action, array $data = [], string $queueName = null)
    {
        if (sys_config('queue_open', 0) == 1 && Env::get('cache.driver', 'file') == 'redis') {
            $queue = Queue::instance()->job(__CLASS__);
            if (is_array($action)) {
                $queue->data(...$action);
            } else if (is_string($action)) {
                $queue->do($action)->data(...$data);
            }
            if ($queueName) {
                $queue->setQueueName($queueName);
            } else if (static::queueName()) {
                $queue->setQueueName(static::queueName());
            }
            return $queue->push();
        } else {
            $className = '\\' . __CLASS__;
            $res = new $className();
            if (is_array($action)) {
                $res->doJob(...$action);
            } else {
                $res->$action(...$data);
            }
        }
    }

    /**
     * Trì hoãn việc tham gia hàng đợi tin nhắn
     * @param int $secs
     * @param $action
     * @param array $data
     * @param string|null $queueName
     * @return mixed
     */
    public static function dispatchSecs(int $secs, $action, array $data = [], string $queueName = null)
    {
        if (sys_config('queue_open', 0) == 1 && Env::get('cache.driver', 'file') == 'redis') {
            $queue = Queue::instance()->job(__CLASS__)->secs($secs);
            if (is_array($action)) {
                $queue->data(...$action);
            } else if (is_string($action)) {
                $queue->do($action)->data(...$data);
            }
            if ($queueName) {
                $queue->setQueueName($queueName);
            } else if (static::queueName()) {
                $queue->setQueueName(static::queueName());
            }
            return $queue->push();
        }
    }
}
