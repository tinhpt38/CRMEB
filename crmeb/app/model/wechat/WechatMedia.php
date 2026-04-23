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
 * Class WechatMedia
 * @package app\model\wechat
 */
class WechatMedia extends BaseModel
{
    use ModelTrait;

    /**
     * khóa chính
     * @var string
     */
    protected $pk = 'id';

    /**
     * tên bảng
     * @var string
     */
    protected $name = 'wechat_media';

}
