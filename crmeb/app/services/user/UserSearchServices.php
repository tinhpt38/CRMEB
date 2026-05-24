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
use app\dao\user\UserSearchDao;

/**
 *
 * Class UserLabelServices
 * @package app\services\user
 *  * @method getColumn(array $where, string $field, string $key = '') Nhận một mảng trường
 *  * @method getKeywordResult(int $uid, string $keyword, int $preTime = 7200) Có được bức tranh toàn cầu|Kết quả tìm kiếm của Khách hàng cho một từ khóa nhất định
 */class UserSearchServices extends BaseServices
{

    /**
     * UserSearchServices constructor.
     * @param UserSearchDao $dao
     */    public function __construct(UserSearchDao $dao)
    {
        $this->dao = $dao;
    }


    /**
     * Lấy danh sách từ khóa tìm kiếm của Khách hàng
     * @param int $uid
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getUserList(int $uid)
    {
        if (!$uid) {
            return [];
        }
        [$page, $limit] = $this->getPageValue();
        return $this->dao->getList(['uid' => $uid, 'is_del' => 0], 'add_time desc,num desc', $page, $limit);
    }

    /**
     * Người dùng thêm lịch sử tìm kiếm
     * @param int $uid
     * @param string $key
     * @param array $result
     */    public function saveUserSearch(int $uid, string $keyword, array $vicword, array $result)
    {
        $result = json_encode($result);
        $vicword = json_encode($vicword, JSON_UNESCAPED_UNICODE);
        $userkeyword = $this->dao->getKeywordResult($uid, $keyword, 0);
        $data = [];
        $data['result'] = $result;
        $data['vicword'] = $vicword;
        $data['add_time'] = time();
        if ($userkeyword) {
            $data['num'] = $userkeyword['num'] + 1;
            $this->dao->update(['id' => $userkeyword['id']], $data);
        } else {
            $data['uid'] = $uid;
            $data['keyword'] = $keyword;
            $this->dao->save($data);
        }
        return true;
    }

}
