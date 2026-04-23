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

namespace app\model\system;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

/**
 * Mô hình cài đặt cấp hệ thống
 * Class SystemUserLevel
 * @package app\model\system
 */
class SystemNotification extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */
    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */
    protected $name = 'system_notification';

    public function searchTypeAttr($query, $value)
    {
        if ($value != '') {
            $query->where('type', $value);
        }
    }

    public function searchNameAttr($query, $value)
    {
        if ($value != '') {
            $query->whereLike('name|title', "%$value%");
        }
    }

}
