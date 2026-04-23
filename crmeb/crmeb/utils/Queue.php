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

namespace crmeb\utils;

use think\facade\Config;
use think\facade\Queue as QueueThink;
use think\facade\Log;

/**
 * Class Queue
 * @package crmeb\utils
 * @method $this do(string $do) Đặt phương thức thực hiện nhiệm vụ
 * @method $this job(string $job) Đặt tên lớp thực thi nhiệm vụ
 * @method $this errorCount(int $errorCount) Số lần thực thi thất bại
 * @method $this data(...$data) dữ liệu thực thi
 * @method $this secs(int $secs) Trì hoãn giây thực hiện
 * @method $this log($log) khai thác gỗ
 */
class Queue
{

    /**
     * thông báo lỗi
     * @var string
     */
    protected $error;

    /**
     * Đặt thông báo lỗi
     * @param string|null $error
     * @return bool
     */
    protected function setError(?string $error = null)
    {
        $this->error = $error ?: 'lỗi không xác định';
        return false;
    }

    /**
     * Nhận thông báo lỗi
     * @return string
     */
    public function getError()
    {
        $error = $this->error;
        $this->error = null;
        return $error;
    }

    /**
     * Thực hiện nhiệm vụ
     * @var string
     */
    protected $do = 'doJob';

    /**
     * Tên phương thức thực thi tác vụ mặc định
     * @var string
     */
    protected $defaultDo;

    /**
     * Tên lớp nhiệm vụ
     * @var string
     */
    protected $job;

    /**
     * số lỗi
     * @var int
     */
    protected $errorCount = 3;

    /**
     * dữ liệu
     * @var array|string
     */
    protected $data;

    /**
     * Tên hàng đợi
     * @var null
     */
    protected $queueName = null;

    /**
     * Trì hoãn giây thực hiện
     * @var int
     */
    protected $secs = 0;

    /**
     * khai thác gỗ
     * @var string|callable|array
     */
    protected $log;

    /**
     * @var array
     */
    protected $rules = ['do', 'data', 'errorCount', 'job', 'secs', 'log'];

    /**
     * @var static
     */
    protected static $instance;

    /**
     * Queue constructor.
     */
    protected function __construct()
    {
        $this->defaultDo = $this->do;
    }

    /**
     * @return static
     */
    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * Đặt tên cột
     * @param string $queueName
     * @return $this
     */
    public function setQueueName(string $queueName)
    {
        $this->queueName = $queueName;
        return $this;
    }

    /**
     * đưa vào hàng đợi tin nhắn
     * @param array|null $data
     * @return mixed
     */
    public function push(?array $data = null)
    {
        if (!$this->job) {
            return $this->setError('Lớp hàng đợi cần được thực thi phải tồn tại');
        }
        $jodValue = $this->getValues($data);
        $res = QueueThink::{$this->action()}(...$jodValue);
        if (!$res) {
            $res = QueueThink::{$this->action()}(...$jodValue);
            if (!$res) {
                Log::error('Không thể tham gia hàng đợi, thông số：' . json_encode($this->getValues($data)));
            }
        }
        $this->clean();
        return $res;
    }

    /**
     * xóa dữ liệu
     */
    public function clean()
    {
        $this->secs = 0;
        $this->data = [];
        $this->log = null;
        $this->queueName = null;
        $this->errorCount = 3;
        $this->do = $this->defaultDo;
    }

    /**
     * Nhận phương thức nhiệm vụ
     * @return string
     */
    protected function action()
    {
        return $this->secs ? 'later' : 'push';
    }

    /**
     * Nhận thông số
     * @param $data
     * @return array
     */
    protected function getValues($data)
    {
        $jobData['data'] = $data ?: $this->data;
        $jobData['do'] = $this->do;
        $jobData['errorCount'] = $this->errorCount;
        $jobData['log'] = $this->log;
        if ($this->do != $this->defaultDo) {
            $this->job .= '@' . Config::get('queue.prefix', 'eb_') . $this->do;
        }
        if ($this->secs) {
            return [$this->secs, $this->job, $jobData, $this->queueName];
        } else {
            return [$this->job, $jobData, $this->queueName];
        }
    }

    /**
     * @param $name
     * @param $arguments
     * @return $this
     */
    public function __call($name, $arguments)
    {
        if (in_array($name, $this->rules)) {
            if ($name === 'data') {
                $this->{$name} = $arguments;
            } else {
                $this->{$name} = $arguments[0] ?? null;
            }
            return $this;
        } else {
            throw new \RuntimeException('Method does not exist' . __CLASS__ . '->' . $name . '()');
        }
    }
}
