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

namespace app\model\system;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * Mô hình cài đặt cấp hệ thống
 * Class SystemUserLevel
 * @package app\model\system
 */
class MessageSystem extends BaseModel
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
    protected $name = 'message_system';


    protected $insert = ['add_time'];

    /**
     * IDNgười tìm kiếm
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchIdAttr($query, $value, $data)
    {
        $query->where('id', $value);
    }
    /**
     * UIDNgười tìm kiếm
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchUidAttr($query, $value, $data)
    {
        $query->where('uid', $value);
    }
    /**
     * LookNgười tìm kiếm
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchLookAttr($query, $value, $data)
    {
        $query->where('look', $value);
    }
    /**
     * delNgười tìm kiếm
     * @param Model $query
     * @param $value
     * @param $data
     */
    public function searchIsDelAttr($query, $value, $data)
    {
        $query->where('is_del', $value);
    }

}
