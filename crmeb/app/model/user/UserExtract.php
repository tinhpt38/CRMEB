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
use think\model;

/**
 * Class UserExtract
 * @package app\model\user
 */class UserExtract extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'user_extract';

    //Đang xem xét
    const AUDIT_STATUS = 0;
    //thất bại
    const FAIL_STATUS = -1;
    //Đã rút
    const SUCCESS_STATUS = 1;

    /**
     * Trạng thái
     * @var string[]
     */    protected static $status = [
        -1 => 'thất bại',
        0 => 'Đang xem xét',
        1 => 'Đã rút'
    ];

    /**
     * sự kết hợpuser
     * @return model\relation\HasOne
     */    public function user()
    {
        return $this->hasOne(User::class, 'uid', 'uid');
    }

    /**
     * Khách hànguid
     * @param Model $query
     * @param $value
     */    public function searchUidAttr($query, $value)
    {
        if (is_array($value))
            $query->whereIn('uid', $value);
        else
            $query->where('uid', $value);
    }

    /**
     * Phương thức rút tiền
     * @param Model $query
     * @param $value
     */    public function searchExtractTypeAttr($query, $value)
    {
        if ($value != '') $query->where('extract_type', $value);
    }

    /**
     * Xem lại trạng thái
     * @param Model $query
     * @param $value
     */    public function searchStatusAttr($query, $value)
    {
        if ($value !== '') {
            $query->where('status', $value);
        }
    }

    /**
     * tìm kiếm mờ
     * @param Model $query
     * @param $value
     */    public function searchLikeAttr($query, $value)
    {
        if ($value) {
            $query->where(function ($query) use ($value) {
                $query->where('real_name|id|bank_code|alipay_code', 'LIKE', "%$value%")->whereOr('uid', 'in', function ($query) use ($value) {
                    $query->name('user')->whereLike('nickname', '%' . $value . '%')->field('uid')->select();
                });
            });
        }
    }

}
