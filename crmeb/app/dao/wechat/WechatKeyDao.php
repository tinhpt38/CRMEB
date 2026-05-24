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
use app\model\wechat\WechatKey;

/**
 *
 * Class UserWechatUserDao
 * @package app\dao\user
 */class WechatKeyDao extends BaseDao
{
    protected function setModel(): string
    {
        return WechatKey::class;
    }

    /**
     * Người tìm kiếm
     * @param array $where
     * @param bool $search
     * @return \crmeb\basic\BaseModel|mixed|Model
     * @throws \ReflectionException
     */    public function search(array $where = [], bool $search = false)
    {
        return parent::search($where, $search)->when(isset($where['id']), function ($query) use ($where) {
            $query->where('id', $where['id']);
        });
    }

}
