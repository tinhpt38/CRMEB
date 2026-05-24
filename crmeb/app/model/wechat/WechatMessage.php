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

namespace app\model\wechat;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

/**
 * Hồ sơ hành vi Khách hàng WeChat  model
 * Class WechatMessage
 * @package app\model\wechat
 */class WechatMessage extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'wechat_message';

    protected $insert = ['add_time'];

    public static function setAddTimeAttr($value)
    {
        return time();
    }

}
