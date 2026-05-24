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

class Diy extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'diy';

    protected $updateTime = false;

    /**
     * Thêm công cụ lấy thời gian
     * @param $value
     * @return false|string
     */    public function getAddTimeAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    /**
     * Bộ thu thời gian sửa đổi
     * @param $value
     * @return false|string
     */    public function getUpdateTimeAttr($value)
    {
        return $value ? date('Y-m-d H:i:s', $value) : 'Chưa có';
    }

    /**
     * Nhập trình tìm kiếm
     * @param Model $query
     * @param $value
     */    public function searchTypeAttr($query, $value)
    {
        if ($value !== '') {
            if ($value == -1) {
                $query->whereIn('type', [0, 2]);
            } else {
                $query->where('type', $value);
            }
        }
    }

    /**
     * Trình tìm kiếm số phiên bản
     * @param Model $query
     * @param $value
     */    public function searchVersionAttr($query, $value)
    {
        if ($value != '') $query->where('version', $value);
    }

    /**
     * Có nên sử dụng công cụ tìm kiếm hay không
     * @param Model $query
     * @param $value
     */    public function searchStatusAttr($query, $value)
    {
        if ($value != '') $query->where('status', $value);
    }

    /**
     * người tìm kiếm tên
     * @param Model $query
     * @param $value
     */    public function searchNameAttr($query, $value)
    {
        if ($value != '') $query->where('name', $value);
    }

    /**
     * @param $query
     * @param $value
     */    public function searchIsDelAttr($query, $value)
    {
        if ($value !== '') $query->where('is_del', $value);
    }

    public function searchIsProAttr($query, $value)
    {
        if ($value !== '') $query->where('is_pro', $value);
    }

}
