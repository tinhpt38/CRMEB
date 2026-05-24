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
use app\model\user\UserLevel;

/**
 *
 * Class UserLevelDao
 * @package app\dao\user
 */class UserLevelDao extends BaseDao
{

    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return UserLevel::class;
    }

    /**
     * Nhận thông tin chi tiết về cấp độ thành viên của Khách hàng dựa trên uid
     * @param int $uid
     * @param string $field
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getUserLevel(int $uid, string $field = '*')
    {
        return $this->getModel()->where('uid', $uid)->where('is_del', 0)->where('status', 1)->field($field)->with(['levelInfo'])->order('grade desc,add_time desc')->find();
    }

    /**
     * Nhận giảm giá ở cấp độ Khách hàng
     * @param int $uid
     * @return mixed
     */    public function getDiscount(int $uid)
    {
        $level = $this->getModel()->where(['uid' => $uid, 'is_del' => 0, 'status' => 1])->with(['levelInfo' => function ($query) {
            $query->field('id,discount')->bind(['discount_num' => 'discount']);
        }])->order('id desc')->find();
        return $level ? $level->toArray()['discount_num'] : NULL;
    }
}
