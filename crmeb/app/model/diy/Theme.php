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
namespace app\model\diy;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

/**
 * Chủ đề tùy chỉnh
 * @author wuhaotian
 * @email 442384644@qq.com
 * @date 2025/12/18
 */
class Theme extends BaseModel
{
    use ModelTrait;

    protected $pk = 'id';

    protected $name = 'theme';
}
