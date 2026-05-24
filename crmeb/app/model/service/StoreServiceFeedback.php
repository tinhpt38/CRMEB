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

namespace app\model\service;


use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * Phản hồi tin nhắn CSKH
 * Class StoreServiceFeedback
 * @package app\model\service
 */class StoreServiceFeedback extends BaseModel
{

    use ModelTrait;

    /**
     * @var string
     */    protected $name = 'store_service_feedback';

    /**
     * @var string
     */    protected $pk = 'id';

    /**
     * @param $value
     * @return false|string
     */    public function getAddTimeAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    /**
     * Tìm kiếm tiêu đề
     * @param Model $query
     * @param $value
     */    public function searchTitleAttr($query, $value)
    {
        $value && $query->whereLike('rela_name|phone|content|uid', "%" . $value . "%");
    }

}
