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

namespace app\model\user;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * Class UserLabelRelation
 * @package app\model\user
 */class UserLabelRelation extends BaseModel
{
    use ModelTrait;

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'user_label_relation';

    /**
     * @return \think\model\relation\HasOne
     */    public function label()
    {
        return $this->hasOne(UserLabel::class, 'id', 'label_id')->bind([
            'label_name' => 'label_name'
        ]);
    }

    /**
     * uidNgười tìm kiếm
     * @param Model $query
     * @param $value
     */    public function searchUidAttr($query, $value)
    {
        $query->whereIn('uid', $value);
    }
}
