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

namespace app\model\system\store;

use app\model\user\User;
use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * người mẫu thư ký
 * Class SystemStoreStaff
 * @package app\model\system\store
 */
class SystemStoreStaff extends BaseModel
{
    use ModelTrait;

    /**
     * Tên mẫu
     * @var string
     */
    protected $name = 'system_store_staff';

    /**
     * userLiên kết một-một trong bảng người dùng
     * @return \think\model\relation\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'uid', 'uid')->field(['uid', 'nickname'])->bind([
            'nickname' => 'nickname'
        ]);
    }

    /**
     * Lưu trữ liên kết một-một trong bảng
     * @return \think\model\relation\HasOne
     */
    public function store()
    {
        return $this->hasOne(SystemStore::class, 'id', 'store_id')->field(['id', 'name'])->bind([
            'name' => 'name'
        ]);
    }

    /**
     * Trình lấy dấu thời gian cho đến nay
     * @param $value
     * @return false|string
     */
    public static function getAddTimeAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    /**
     * Có người tìm kiếm thẩm quyền xóa sổ không?
     * @param Model $query
     * @param $value người dùnguid
     */
    public function searchIsStatusAttr($query, $value)
    {
        $query->where(['uid' => $value, 'status' => 1, 'verify_status' => 1]);
    }

    /**
     * uidNgười tìm kiếm
     * @param Model $query
     * @param $value
     */
    public function searchUidAttr($query, $value)
    {
        $query->where('uid', $value);
    }

    /**
     * Trình tìm kiếm ID cửa hàng
     * @param Model $query
     * @param $value
     */
    public function searchStoreIdAttr($query, $value)
    {
        if ($value && $value > 0) {
            $query->where('store_id', $value);
        }
    }
}
