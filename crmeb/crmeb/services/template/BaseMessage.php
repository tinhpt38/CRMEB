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

namespace crmeb\services\template;

use crmeb\basic\BaseStorage;
use think\facade\Config;

abstract class BaseMessage extends BaseStorage
{
    /**
     * bản mẫuid
     * @var array
     */
    protected $templateIds = [];

    /**
     * openId
     * @var string
     */
    protected $openId;

    /**
     * Nhảy liên kết
     * @var string
     */
    protected $toUrl;

    /**
     * màu sắc
     * @var string
     */
    protected $color;

    /**
     * khởi tạo
     * @param array $config
     * @return mixed|void
     */
    protected function initialize(array $config)
    {
        $this->templateIds = Config::get($this->configFile . '.stores.' . $this->name . '.template_id', []);
    }

    /**
     * Có ghi nhật ký hay không
     * @return mixed
     */
    public function isLog()
    {
        $isLog = Config::get($this->configFile . 'isLog', false);
        return Config::get($this->configFile . '.stores.' . $this->name . '.isLog', $isLog);
    }

    /**
     * Nhận mẫuid
     * @return array
     */
    public function getTemplateId()
    {
        return $this->templateIds;
    }

    /**
     * openid
     * @param string $openId
     * @return $this
     */
    public function to(string $openId)
    {
        $this->openId = $openId;
        return $this;
    }

    /**
     * Đường nhảy
     * @param string $url
     * @return $this
     */
    public function url(string $url)
    {
        $this->toUrl = $url;
        return $this;
    }

    /**
     * Đặt màu nền
     * @param string $color
     * @return $this
     */
    public function color(?string $color)
    {
        $this->color = $color;
        return $this;
    }

    /**
     * Trích xuất mẫucode
     * @param string $templateId
     * @return null
     */
    protected function getTemplateCode(string $templateId)
    {
        return $this->templateIds[$templateId] ?? null;
    }

    /**
     * Khôi phục mặc định
     */
    protected function clear()
    {
        $this->openId = null;
        $this->toUrl = null;
        $this->color = null;
    }

    /**
     * Gửi tin nhắn
     * @param string $templateId
     * @param array $data
     * @return mixed
     */
    abstract public function send(string $templateId, array $data = []);

    /**
     * Thêm mẫu
     * @param string $shortId
     * @return mixed
     */
    abstract public function add(string $shortId);

    /**
     * Xóa mẫu
     * @param string $templateId
     * @return mixed
     */
    abstract public function delete(string $templateId);

    /**
     * Nhận tất cả các mẫu
     * @return mixed
     */
    abstract public function list();

}
