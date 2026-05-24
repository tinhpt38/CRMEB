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

namespace app\model\activity\lottery;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * rút thăm trúng thưởng
 * Class LuckLottery
 * @package app\model\activity\lottery
 */class LuckLottery extends BaseModel
{

    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'luck_lottery';

    /**
     * Công cụ sửa đổi cấp độ Khách hàng xổ số
     * @param $value
     * @return false|string
     */    protected function setUserLevelAttr($value)
    {
        if ($value) {
            return is_array($value) ? json_encode($value) : $value;
        }
        return '';
    }

    /**
     * Trình nhận cấp độ Khách hàng xổ số
     * @param $value
     * @param $data
     * @return mixed
     */    protected function getUserLevelAttr($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    /**
     * Công cụ sửa đổi thẻ Khách hàng xổ số
     * @param $value
     * @return false|string
     */    protected function setUserLabelAttr($value)
    {
        if ($value) {
            return is_array($value) ? json_encode($value) : $value;
        }
        return '';
    }

    /**
     * Trình lấy thẻ Khách hàng xổ số
     * @param $value
     * @param $data
     * @return mixed
     */    protected function getUserLabelAttr($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    /**
     * Giải thưởng liên quan
     * @return \think\model\relation\HasOne
     */    public function prize()
    {
        return $this->hasMany(LuckPrize::class, 'lottery_id', 'id')->where('status', 1)->where('is_del', 0)->order('sort asc,id asc');
    }

    /**
     * người tìm kiếm từ khóa
     * @param $query Model
     * @param $value
     */    public function searchKeywordAttr($query, $value)
    {
        if ($value !== '') $query->where('id|name|desc|content', 'like', '%' . $value . '%');
    }

    /**
     * Công cụ tìm kiếm định dạng xổ số
     * @param $query Model
     * @param $value
     */    public function searchTypeAttr($query, $value)
    {
        if ($value) $query->where('type', $value);
    }

    /**
     * Công cụ tìm loại xổ số
     * @param $query Model
     * @param $value
     */    public function searchFactorAttr($query, $value)
    {
        if ($value !== '') $query->where('factor', $value);
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

    public function records()
    {
        return $this->hasMany(LuckLotteryRecord::class, 'lottery_id', 'id');
    }
}
