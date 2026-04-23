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
 * từ khóa
 * Class WechatReply
 * @package app\model\wechat
 */
class WechatReply extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */
    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */
    protected $name = 'wechat_reply';

    /**
     * Loại tin nhắn
     * @var string[]
     */
    public static $replyType = ['text', 'image', 'news', 'voice'];

    /**
     * Hiệp hội trả lời tự động tài khoản chính thức
     * @return \think\model\relation\HasMany
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/3
     */
    public function wechatKeys()
    {
        return $this->hasMany(WechatKey::class, 'reply_id', 'id');
    }

    /**
     * Hiệp hội trả lời tự động dịch vụ khách hàng
     * @return \think\model\relation\HasOne
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/3
     */
    public function kefuKey()
    {
        return $this->hasOne(WechatKey::class, 'reply_id', 'id')->bind(['keys']);
    }
}
