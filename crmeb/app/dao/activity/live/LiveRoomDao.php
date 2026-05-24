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

namespace app\dao\activity\live;


use app\dao\BaseDao;
use app\model\activity\live\LiveRoom;

/**
 * Class LiveRoomDao
 * @package app\dao\live
 */class LiveRoomDao extends BaseDao
{

    /**
     * @return string
     * @author xaboy
     * @day 2020/7/29
     */    protected function setModel(): string
    {
        return LiveRoom::class;
    }


    /**
     * @param array $where
     * @param string $field
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getList(array $where, string $field = '*', array $with = [], int $page, int $limit)
    {
        return $this->search($where)->field($field)->with($with)->page($page, $limit)->order('sort desc,id desc')->select()->toArray();
    }

    /**
     * @param $roomId
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function validRoom($roomId)
    {
        return $this->getModel()->where('id', $roomId)->where('status', 'IN', [0, 2])->where('is_del', 0)->find();
    }

    public function getRooms(array $roomIds)
    {
        return $this->search(['room_id' => $roomIds])->column('live_status,id', 'room_id');
    }
}
