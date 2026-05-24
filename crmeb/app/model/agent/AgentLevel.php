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

namespace app\model\agent;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * Cấp độ nhà phân phối
 * Class AgentLevel
 * @package app\model\agent
 */class AgentLevel extends BaseModel
{

    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'agent_level';

    /**
     * Nhiệm vụ cấp liên quan
     * @return \think\model\relation\HasMany
     */    public function task()
    {
        return $this->hasMany(AgentLevelTask::class, 'level_id', 'id')->where('is_del', 0);
    }

    /**
     * tìm kiếm từ khóa
     * @param $query
     * @param $value
     */    public function searchKeywordAttr($query, $value)
    {
        if ($value !== '') $query->whereLike('id|name', "%" . trim($value) . "%");
    }

    /**
     * công cụ tìm mức
     * @param $query Model
     * @param $value
     */    public function searchGradeAttr($query, $value)
    {
        if ($value !== '') $query->where('grade', $value);
    }

    /**
     * công cụ tìm trạng thái
     * @param $query Model
     * @param $value
     */    public function searchStatusAttr($query, $value)
    {
        if ($value !== '') $query->where('status', $value);
    }

    /**
     * Có nên xóa người tìm kiếm hay không
     * @param $query Model
     * @param $value
     */    public function searchIsDelAttr($query, $value)
    {
        if ($value !== '') $query->where('is_del', $value);
    }
}
