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
namespace app\model\system\crontab;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

class SystemCrontab extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'system_timer';

    /**
     * Không có cập nhật tự độngupdate_time
     * @var bool
     */    protected $updateTime = false;

    /**
     * Có tùy chỉnh trình tìm kiếm tác vụ theo lịch trình hay không
     * @param $query
     * @param $value
     * @param $data
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/6/6
     */    public function searchCustomAttr($query, $value, $data)
    {
        if ($value !== '') {
            if ($value == 0) {
                $query->where('mark', '<>', 'customTimer');
            } else {
                $query->where('mark', 'customTimer');
            }
        }
    }
}