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
 * Nhiệm vụ cấp nhà phân phối
 * Class AgentLevelTask
 * @package app\model\agent
 */class AgentLevelTask extends BaseModel
{

    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'agent_level_task';

    /**
     * Cấp độ nhà phân phối liên kết
     * @return \think\model\relation\HasOne
     */    public function level()
    {
        return $this->hasOne(AgentLevel::class, 'id', 'level_id');
    }

    /**
     * Hồ sơ hoàn thành nhiệm vụ liên quan
     * @return \think\model\relation\HasMany
     */    public function record()
    {
        return $this->hasMany(AgentLevelTaskRecord::class, 'task_id', 'id');
    }

    /**
     * người tìm kiếm từ khóa
     * @param $query Model
     * @param $value
     */    public function searchKeywordAttr($query, $value)
    {
        if ($value !== '') $query->where('id|name|desc', 'like', '%' . $value . '%');
    }

    /**
     * Công cụ tìm loại nhiệm vụ
     * @param $query Model
     * @param $value
     */    public function searchTypeAttr($query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('type', $value);
        } else {
            if ($value !== '') $query->where('type', $value);
        }

    }

    /**
     * Trình tìm kiếm cấp độ nhà phân phối
     * @param $query Model
     * @param $value
     */    public function searchLevelIdAttr($query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('Level_id', $value);
        } else {
            if ($value !== '') $query->where('Level_id', $value);
        }

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
