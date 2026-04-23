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

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 * Danh sách cửa hàng
 * Class SystemStore
 * @package app\model\system\store
 */
class SystemStore extends BaseModel
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
    protected $name = 'system_store';

    /**
     * Máy thu vĩ độ và kinh độ
     * @param $value
     * @param $data
     * @return string
     */
    public static function getLatlngAttr($value, $data)
    {
        return $data['latitude'] . ',' . $data['longitude'];
    }

    /**
     * Công cụ tìm loại cửa hàng
     * @param Model $query
     * @param $value
     */
    public function searchTypeAttr($query, $value)
    {
        switch ((int)$value) {
            case 1:
                $query->where(['is_del' => 0, 'is_show' => 0]);
                break;
            case 0:
                $query->where(['is_del' => 0, 'is_show' => 1]);
                break;
            default:
                $query->where('is_del', 1);
                break;
        }
    }

    /**
     * Số điện thoại,id,Người tìm kiếm biệt hiệu
     * @param Model $query
     * @param $value
     */
    public function searchKeywordsAttr($query, $value)
    {
        if ($value) {
            $query->where('id|name|introduction|phone', 'LIKE', "%$value%");
        }
    }
}
