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
declare (strict_types=1);

namespace app\dao\wechat;

use think\model;
use app\dao\BaseDao;
use app\model\wechat\WechatReply;

/**
 *
 * Class UserWechatUserDao
 * @package app\dao\user
 */class WechatReplyDao extends BaseDao
{
    /**
     * @return string
     */    protected function setModel(): string
    {
        return WechatReply::class;
    }

    /**
     * Nhận từ khóa
     * @param $key
     * @return array|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getKey($key)
    {
        $res = $this->getModel()->whereIn('id', function ($query) use ($key) {
            $query->name('wechat_key')->where('keys', $key)->where('key_type', 0)->field(['reply_id'])->select();
        })->where('status', '1')->find();
        if (empty($res)) {
            $res = $this->getModel()->whereIn('id', function ($query) use ($key) {
                $query->name('wechat_key')->where('keys', 'default')->where('key_type', 0)->field(['reply_id'])->select();
            })->where('status', '1')->find();
        }
        return $res;
    }

}
