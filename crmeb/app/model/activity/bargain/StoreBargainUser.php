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

namespace app\model\activity\bargain;

use app\model\user\User;
use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * TODO Tham gia thương lượngModel
 * Class StoreBargainUser
 * @package app\model\activity
 */class StoreBargainUser extends BaseModel
{
    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'store_bargain_user';

    use ModelTrait;

    /**
     * hiệp hội một-một
     *Chi tiết sản phẩm của các sản phẩm liên quan đến sản phẩm
     * @return \think\model\relation\HasOne
     */    public function getBargain()
    {
        return $this->hasOne(StoreBargain::class, 'id', 'bargain_id')->bind(['title', 'image', 'datatime' => 'stop_time', 'people_num']);
    }

    /**
     * hiệp hội một-một
     * Có được Khách hàng thương lượng
     * @return \think\model\relation\HasOne
     */    public function getUser()
    {
        return $this->hasOne(User::class, 'uid', 'uid')->bind(['avatar', 'nickname']);
    }

    /**
     * Người tìm kiếm Khách hàng
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchUidAttr($query, $value, $data)
    {
        $query->where('uid', $value);
    }

    /**
     * Trình tìm kiếm ID sản phẩm
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchBargainIdAttr($query, $value, $data)
    {
        $query->where('bargain_id', $value);
    }

    /**
     * công cụ tìm trạng thái
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchStatusAttr($query, $value, $data)
    {
        if ($value != '') $query->where('status', $value);
    }

    /**
     * Có nên xóa người tìm kiếm hay không
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchIsDelAttr($query, $value, $data)
    {
        $query->where('is_del', $value);
    }
}
