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

/**
 * Bàn phụ
 * Class Auxiliary
 * @package app\model\other
 */
class Auxiliary extends BaseModel
{

    use ModelTrait;

    /**
     * trình diễn
     * @var string
     */
    protected $name = 'auxiliary';
    protected $insert = ['add_time'];
    protected $autoWriteTimestamp = false;
    /**
     * khóa chính
     * @var string
     */
    protected $pk = 'id';

    /**Nhập trình tìm kiếm
     * @param $query
     * @param $value
     */
    public function searchTypeAttr($query, $value)
    {
        $query->where('type', $value);
    }

    /**loại tìm kiếm id ràng buộc
     * @param $query
     * @param $value
     */
    public function searchBindingIdAttr($query, $value)
    {
        $query->where('binding_id', $value);
    }

    /**Nhập Trình tìm trạng thái
     * @param $query
     * @param $value
     */
    public function searchStatusAttr($query, $value)
    {
        $query->whereIn('status', $value);
    }
    /**gõ công cụ tìm kiếm id liên kết
     * @param $query
     * @param $value
     */
    public function searchRelationIdAttr($query, $value)
    {
        $query->where('relation_id', $value);
    }
}
