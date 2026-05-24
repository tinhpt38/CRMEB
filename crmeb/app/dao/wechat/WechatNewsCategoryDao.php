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

use app\dao\BaseDao;
use app\model\wechat\WechatNewsCategory;

/**
 *
 * Class UserWechatUserDao
 * @package app\dao\user
 */class WechatNewsCategoryDao extends BaseDao
{
    /**
     * @return string
     */    protected function setModel(): string
    {
        return WechatNewsCategory::class;
    }

    /**Phân loại tin tức $model
     * @param array $where
     * @return \crmeb\basic\BaseModel
     */    public function getNewCtae(array $where)
    {
        return parent::getModel()->when(isset($where['cate_name']), function ($query) use ($where) {
            $query->where('cate_name', 'LIKE', "%$where[cate_name]%");
        })->where('status', 1)->order('add_time desc');
    }


}
