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
use app\model\user\UserSearch;

/**
 * Tìm kiếm Khách hàng
 * Class UserSearchDao
 * @package app\dao\user
 */class UserSearchDao extends BaseDao
{

    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return UserSearch::class;
    }

    /**
     * Nhận danh sách
     * @param array $where
     * @param string $order
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getList(array $where = [], string $order = 'id desc', int $page = 0, int $limit = 0): array
    {
        return $this->search($where)->order($order)->when($page && $limit, function ($query) use ($page, $limit) {
            $query->page($page, $limit);
        })->select()->toArray();
    }

    /**
     * * Có được bức tranh toàn cầu|Kết quả tìm kiếm của Khách hàng cho một từ khóa nhất định
     * @param int $uid
     * @param string $keyword từ khóa
     * @param int $preTime Mất bao lâu để một tập hợp kết quả được coi là hợp lệ?
     * @return array|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getKeywordResult(int $uid, string $keyword, int $preTime = 7200)
    {
        if (!$keyword) return [];
        $where = ['keyword' => $keyword];
        if ($uid) $where['uid'] = $uid;
        return $this->search($where)->when($uid && $preTime == 0, function ($query) {
                $query->where('is_del', 0);
            })->when($preTime > 0, function ($query) use ($preTime) {
                $query->where('add_time', '>', time() - $preTime);
            })->order('add_time desc,id desc')->find() ?? [];
    }
}
