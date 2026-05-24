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
 * Hồ sơ nhà phân phối hoàn thành nhiệm vụ cấp độ
 * Class AgentLevelTaskRecord
 * @package app\model\agent
 */class AgentLevelTaskRecord extends BaseModel
{

    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'agent_level_task_record';

    /**
     * Cấp độ nhà phân phối liên kết
     * @return \think\model\relation\HasOne
     */    public function level()
    {
        return $this->hasOne(AgentLevel::class, 'id', 'level_id');
    }

    /**
     * Nhiệm vụ cấp nhà phân phối liên kết
     * @return \think\model\relation\HasOne
     */    public function task()
    {
        return $this->hasOne(AgentLevelTask::class, 'id', 'task_id');
    }

    /**
     * Trình tìm kiếm cấp độ nhà phân phối
     * @param $query Model
     * @param $value
     */    public function searchLevelIdAttr($query, $value)
    {
        if ($value !== '') $query->where('level_id', $value);
    }

    /**
     * Công cụ tìm kiếm cấp độ
     * @param $query Model
     * @param $value
     */    public function searchTaskIdAttr($query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('task_id', $value);
        } else {
            if ($value !== '') $query->where('task_id', $value);
        }
    }

    /**
     * Người tìm kiếm Khách hàng
     * @param $query Model
     * @param $value
     */    public function searchUidAttr($query, $value)
    {
        if ($value !== '') $query->where('uid', $value);
    }

    /**
     * công cụ tìm trạng thái
     * @param $query Model
     * @param $value
     */    public function searchStatusAttr($query, $value)
    {
        if ($value !== '') $query->where('status', $value);
    }


}
