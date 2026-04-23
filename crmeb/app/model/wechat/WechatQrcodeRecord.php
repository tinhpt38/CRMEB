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


use app\model\user\User;
use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

class WechatQrcodeRecord extends BaseModel
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
    protected $name = 'wechat_qrcode_record';

    /**
     * sự kết hợpuser
     * @return \think\model\relation\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'uid', 'uid');
    }

    public function searchQidAttr($query, $value)
    {
        if ($value) $query->where('qid', $value);
    }

    public function searchIsFollowAttr($query, $value)
    {
        if ($value !== '') $query->where('is_follow', $value);
    }
}