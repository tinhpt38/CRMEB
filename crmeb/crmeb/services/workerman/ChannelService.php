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

namespace crmeb\services\workerman;


use Channel\Client;

class ChannelService
{
    /**
     * @var Client
     */
    protected $channel;

    /**
     * @var
     */
    protected $trigger = 'crmeb';

    /**
     * @var ChannelService
     */
    protected static $instance;

    public function __construct()
    {
        self::connet();
    }

    public static function instance()
    {
        if (is_null(self::$instance))
            self::$instance = new self();

        return self::$instance;
    }

    public static function connet()
    {
        $config = config('workerman.channel');
        Client::connect($config['ip'], $config['port']);
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setTrigger(string $name)
    {
        $this->trigger = $name;
        return $this;
    }

    /**
     * Gửi tin nhắn
     * @param string $type kiểu
     * @param array|null $data dữ liệu
     * @param array|null $ids người dùng id,Không chuyển cho tất cả người dùng
     */
    public function send(string $type, ?array $data = null, ?array $ids = null)
    {
        $res = compact('type');
        if (!is_null($data))
            $res['data'] = $data;

        if (!is_null($ids) && count($ids))
            $res['ids'] = $ids;

        $this->trigger($this->trigger, $res);
        $this->trigger = 'crmeb';
    }

    public function trigger(string $type, ?array $data = null)
    {
        Client::publish($type, $data);
    }
}
