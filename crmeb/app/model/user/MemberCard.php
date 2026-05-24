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

namespace app\model\user;


use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

class MemberCard extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'member_card';

    protected $insert = ['add_time', 'update_time'];

    protected $hidden = ['update_time', 'add_time'];

    protected $updateTime = false;

    /**
     * Trình tìm kiếm số thẻ
     * @param Model $query
     * @param $value
     */    public function searchCardNumberAttr($query, $value)
    {
        if ($value) {
            $query->whereLike('card_number', '%' . $value . '%');
        }

    }

    /**
     * Trình tìm kiếm uid Khách hàng
     * @param Model $query
     * @param $value
     */    public function searchUseUidAttr($query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('use_uid', $value);
        } else {
            $query->where('use_uid', $value);
        }
    }

    /**
     * Công cụ tìm số điện thoại di động
     * @param Model $query
     * @param $value
     */    public function searchPhoneAttr($query, $value)
    {
        if ($value) {
            $query->whereIn('use_uid', function ($query) use ($value) {
                $query->name('user')->whereLike('phone', $value . '%')->field('uid')->select();
            });
        }
    }

    /**
     * công cụ tìm kiếm id hàng loạt
     * @param Model $query
     * @param $value
     */    public function searchBatchCardIdAttr($query, $value)
    {
        $query->where('card_batch_id', $value);
    }

    /**
     * Người dùng use_time người tìm kiếm
     * @param Model $query
     * @param $value
     */    public function searchUseTimeAttr($query, $value)
    {
        if ($value > 0) {
            $query->where('use_time', '>', 0);
        }
        if ($value == 0) {
            $query->where('use_time', 0);
        }

    }

    public function searchIsStatusAttr($query, $value)
    {
        if ($value) {
            $query->where('status', $value);
        }

    }
}
