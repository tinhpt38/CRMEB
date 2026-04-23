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

namespace app\model\diy;


use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

class PageCategory extends BaseModel
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
    protected $name = 'page_categroy';

    /**
     * Người tìm kiếm cha mẹ
     * @param Model $query
     * @param $value
     */
    public function searchPidAttr($query, $value)
    {
        if ($value !== '') {
            if (is_array($value)) {
                $query->whereIn('pid', $value);
            } else {
                $query->where('pid', $value);
            }
        }
    }


    /**
     * Có nên sử dụng công cụ tìm kiếm hay không
     * @param Model $query
     * @param $value
     */
    public function searchStatusAttr($query, $value)
    {
        if ($value != '') $query->where('status', $value);
    }

    /**
     * Phát hiện mô-đun
     * @param Model $query
     * @param $value
     */
    public function searchNoModelAttr($query, $value)
    {
        $query->when(!in_array('seckill', $value), function ($q1) {
            $q1->whereNotLike('name', '%bán chớp nhoáng%');
        })->when(!in_array('bargain', $value), function ($q2) {
            $q2->whereNotLike('name', '%Mặc cả%');
        })->when(!in_array('combination', $value), function ($q3) {
            $q3->whereNotLike('name', '%Chia sẻ nhóm%');
        });
    }
}
