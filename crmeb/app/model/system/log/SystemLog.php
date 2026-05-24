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

namespace app\model\system\log;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * mô hình đăng nhập
 * Class SystemLog
 * @package app\model\system\log
 */class SystemLog extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'system_log';

    protected $insert = ['add_time'];

    protected function setAddTimeAttr()
    {
        return time();
    }

    /**
     * Trình tìm kiếm truy cập
     * @param Model $query
     * @param $value
     */    public function searchPagesAttr($query, $value)
    {
        if ($value !== '') {
            $query->whereLike('page', '%' . $value . '%');
        }
    }

    /**
     * Công cụ tìm đường dẫn truy cập
     * @param Model $query
     * @param $value
     */    public function searchPathAttr($query, $value)
    {
        if ($value !== '') {
            $query->whereLike('path', '%' . $value . '%');
        }
    }

    /**
     * ipNgười tìm kiếm
     * @param Model $query
     * @param $value
     */    public function searchIpAttr($query, $value)
    {
        if ($value !== '') {
            $query->where('ip', 'LIKE', "%$value%");
        }
    }

    /**
     * Trình tìm kiếm id quản trị viên
     * @param Model $query
     * @param $value
     */    public function searchAdminIdAttr($query, $value)
    {
        if (!empty($value)) {
            $query->whereIn('admin_id', $value);
        }
    }
}
