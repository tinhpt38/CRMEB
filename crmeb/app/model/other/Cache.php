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
namespace app\model\other;

use crmeb\traits\ModelTrait;
use crmeb\basic\BaseModel;
use think\Model;

/**
 *  bộ nhớ đệmModel
 * Class Cache
 * @package app\model\other
 */
class Cache extends BaseModel
{
    use ModelTrait;

    const EXPIRE = 0;
    /**
     * Tên mẫu
     * @var string
     */
    protected $name = 'cache';

    /**
     * Trình tìm kiếm KEY được lưu trong bộ nhớ đệm
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchKeyAttr($query, $value, $data)
    {
        $query->where('key', $value);
    }
}
