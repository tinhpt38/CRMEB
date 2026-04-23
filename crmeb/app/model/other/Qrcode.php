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


use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * Class Qrcode
 * @package app\model\other
 */
class Qrcode extends BaseModel
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
    protected $name = 'qrcode';

    /**
     * type Người tìm kiếm
     * @param Model $query
     * @param $value
     */
    public function searchTypeAttr($query, $value)
    {
        if ($value != '') {
            $query->whereLike('type', $value);
        }
    }

    /**
     * status Người tìm kiếm
     * @param Model $query
     * @param $value
     */
    public function searchStatusAttr($query, $value)
    {
        if ($value != '') {
            $query->whereLike('status', $value);
        }
    }

    /**
     * third_type Người tìm kiếm
     * @param Model $query
     * @param $value
     */
    public function searchThirdTypeAttr($query, $value)
    {
        if ($value != '') {
            $query->whereLike('third_type', $value);
        }
    }

    /**
     * third_id Người tìm kiếm
     * @param Model $query
     * @param $value
     */
    public function searchThirdIdAttr($query, $value)
    {
        if ($value != '') {
            $query->whereLike('third_id', $value);
        }
    }

}
