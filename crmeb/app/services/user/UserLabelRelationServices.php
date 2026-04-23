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

namespace app\services\user;

use app\services\BaseServices;
use app\dao\user\UserLabelRelationDao;
use crmeb\exceptions\AdminException;

/**
 *
 * Class UserLabelRelationServices
 * @package app\services\user
 * @method getColumn(array $where, string $field, string $key = '') Nhận một mảng trường
 * @method saveAll(array $data) Lưu dữ liệu theo lô
 */
class UserLabelRelationServices extends BaseServices
{

    /**
     * UserLabelRelationServices constructor.
     * @param UserLabelRelationDao $dao
     */
    public function __construct(UserLabelRelationDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Nhận thẻ người dùngids
     * @param int $uid
     * @return array
     */
    public function getUserLabels(int $uid)
    {
        return $this->dao->getColumn(['uid' => $uid], 'label_id', '');
    }

    /**
     * Nhãn cài đặt người dùng
     * @param $uids
     * @param array $labels
     * @return bool
     * @throws \Exception
     */
    public function setUserLabel($uids, array $labels, $label_type = 0)
    {
        if (!is_array($uids)) $uids = [$uids];
        if (!count($labels)) {
            $this->dao->delete([['uid', 'in', $uids]]);
            return true;
        }
        if ($label_type == 0 || $label_type == 1) {
            if ($label_type == 0) $this->dao->delete([['uid', 'in', $uids]]);
            $data = [];
            foreach ($uids as $uid) {
                foreach ($labels as $label) {
                    $data[] = ['uid' => $uid, 'label_id' => $label];
                }
            }
            if ($data) {
                if (!$this->dao->saveAll($data)) throw new AdminException('Không đặt được nhãn');
            }
        } else {
            $this->dao->delete([['uid', 'in', $uids], ['label_id', 'in', $labels]]);
        }
        return true;
    }

    /**
     * Hủy nhãn người dùng
     * @param int $uid
     * @param array $labels
     * @return mixed
     */
    public function unUserLabel(int $uid, array $labels)
    {
        if (!count($labels)) {
            return true;
        }
        $this->dao->delete([
            ['uid', '=', $uid],
            ['label_id', 'in', $labels],
        ]);
        return true;
    }

    /**
     * Nhận thẻ người dùng
     * @param array $uids
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getUserLabelList(array $uids)
    {
        return $this->dao->getLabelList($uids);
    }
}
