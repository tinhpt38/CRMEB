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
namespace app\model\agent;


use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

class DivisionAgentApply extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'division_agent_apply';

    /**
     * uid
     * @param $query
     * @param $value
     */    public function searchUidAttr($query, $value)
    {
        if ($value != '') $query->where('uid', $value);
    }

    /**
     * division_id
     * @param $query
     * @param $value
     */    public function searchDivisionIdAttr($query, $value)
    {
        if ((int)$value !== 0) $query->where('division_id', $value);
    }

    /**
     * division_invite
     * @param $query
     * @param $value
     */    public function searchDivisionInviteAttr($query, $value)
    {
        if ($value != '') $query->where('division_invite', $value);
    }

    /**
     * status
     * @param $query
     * @param $value
     */    public function searchStatusAttr($query, $value)
    {
        if ($value !== '' && $value !== 'all') $query->where('status', $value);
    }

    /**
     * @param $query
     * @param $value
     */    public function searchKeywordAttr($query, $value)
    {
        if ($value !== '') $query->where('uid|agent_name', 'like', '%' . $value . '%');
    }

    /**
     * is_del
     * @param $query
     * @param $value
     */    public function searchIsDelAttr($query, $value)
    {
        if ($value !== '') $query->where('is_del', $value);
    }
}
