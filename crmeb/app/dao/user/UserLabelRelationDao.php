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
declare (strict_types=1);

namespace app\dao\user;

use app\dao\BaseDao;
use app\model\user\UserLabelRelation;

/**
 *
 * Class UserLabelRelationDao
 * @package app\dao\user
 */
class UserLabelRelationDao extends BaseDao
{

    /**
     * Thiết lập mô hình
     * @return string
     */
    protected function setModel(): string
    {
        return UserLabelRelation::class;
    }

    /**
     * Nhận danh sách thẻ người dùng được nhóm theo ID người dùng
     * @param array $uids
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getLabelList(array $uids)
    {
        return $this->search(['uid' => $uids])->with('label')->select()->toArray();
    }

}
