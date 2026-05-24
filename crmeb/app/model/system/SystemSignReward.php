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
 * @author: thủy triều
 * @email: 442384644@qq.com
 * @date: 2023/7/28
 */class SystemSignReward extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'system_sign_reward';

    /**
     * Nhập trình tìm kiếm
     * @param $query
     * @param $value
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/7/28
     */    public function searchTypeAttr($query, $value)
    {
        if ($value !== '') $query->where('type', $value);
    }
}