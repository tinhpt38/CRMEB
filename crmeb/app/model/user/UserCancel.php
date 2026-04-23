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

/**
 * Class UserCancel
 * @package app\model\user
 */
class UserCancel extends BaseModel
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
    protected $name = 'user_cancel';

    /**
     * Bảng người dùng liên quan
     * @return \think\model\relation\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'uid', 'uid')->bind([
            'money' => 'now_money',
            'brokerage' => 'brokerage_price'
        ]);
    }

    /**
     * công cụ tìm trạng thái
     * @param $query
     * @param $value
     */
    public function searchStatusAttr($query, $value)
    {
        if ($value !== '') {
            $query->where('status', $value);
        }
    }

    /**
     * người tìm kiếm từ khóa
     * @param $query
     * @param $value
     */
    public function searchKeywordsAttr($query, $value)
    {
        if ($value !== '') {
            $query->where('uid|name|phone', 'like', '%' . $value . '%');
        }

    }


}
