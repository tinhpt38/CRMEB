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

namespace app\dao\activity\combination;

use app\dao\BaseDao;
use app\model\activity\combination\StorePink;

/**
 *
 * Class StorePinkDao
 * @package app\dao\activity
 */class StorePinkDao extends BaseDao
{

    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return StorePink::class;
    }

    /**
     * Lấy số lượng nhóm đã đặt
     * @param array $where
     * @return array
     */    public function getPinkCount(array $where = [])
    {
        return $this->getModel()->where($where)->group('cid')->column('count(*)', 'cid');
    }

    /**
     * Nhận danh sách
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getList(array $where, int $page = 0, int $limit = 0)
    {
        return $this->search($where, false)->when($where['k_id'] != 0, function ($query) use ($where) {
            $query->whereOr('id', $where['k_id']);
        })->when(isset($where['keyword']) && $where['keyword'] != '', function ($query) use ($where) {
            $query->where('uid|nickname', 'like', '%' . $where['keyword'] . '%');
        })->with('getProduct')->when($page != 0, function ($query) use ($page, $limit) {
            $query->page($page, $limit);
        })->order('add_time desc')->select()->toArray();
    }

    /**
     * Thu hút những người đang tham gia nhóm,Nhận bài viết sớm nhất
     * @param array $where
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getPinking(array $where)
    {
        return $this->search($where)->order('add_time asc')->find();
    }

    /**
     * Lấy danh sách nhóm
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function pinkList(array $where)
    {
        return $this->search($where)
            ->where('stop_time', '>', time())
            ->order('add_time desc')
            ->select()->toArray();
    }

    /**
     * Lấy số lượng người đang tham gia nhóm
     * @param int $kid
     * @return int
     */    public function getPinkPeople(int $kid)
    {
        return $this->count(['k_id' => $kid, 'is_refund' => 0]) + 1;
    }

    /**
     * Lấy số lượng người đang tham gia nhóm
     * @param array $kids
     * @return int
     */    public function getPinkPeopleCount(array $kids)
    {
        $count = $this->getModel()->whereIn('k_id', $kids)->where('is_refund', 0)->group('k_id')->column('COUNT(id) as count', 'k_id');
        $counts = [];
        foreach ($kids as &$item) {
            if (isset($count[$item])) {
                $counts[$item] = $count[$item] + 1;
            } else {
                $counts[$item] = 1;
            }
        }
        return $counts;
    }

    /**
     * Nhận danh sách các trận đánh nhóm thành công
     * @param int $uid
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function successList(int $uid)
    {
        return $this->search(['status' => 2, 'is_refund' => 0])
//            ->where('uid', '<>', $uid)
                ->limit(10)
            ->select()->toArray();
    }


    /**
     * Lấy số lượng câu đố nhóm đã hoàn thành
     * @return float
     * @throws \ReflectionException
     */    public function getPinkOkSumTotalNum()
    {
        return $this->sum(['status' => 2, 'is_refund' => 0], 'total_num');
    }

    /**
     * Chúng ta có thể tiếp tục tham gia nhóm không?
     * @param int $id
     * @param int $uid
     * @return int
     */    public function isPink(int $id, int $uid)
    {
        return $this->getModel()->where('k_id|id', $id)->where('uid', $uid)->where('is_refund', 0)->count();
    }

    /**
     * Nhận thông tin đặt phòng theo nhóm
     * @param int $id
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getPinkUserOne(int $id)
    {
        return $this->search()->with('getProduct')->find($id);
    }

    /**
     * Nhận thông tin chia sẻ nhóm
     * @param array $where
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getPinkUserList(array $where)
    {
        return $this->getModel()->where($where)->with('getProduct')->select()->toArray();
    }

    /**
     * Lấy danh sách kết thúc nhóm
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function pinkListEnd()
    {
        return $this->getModel()->where('stop_time', '<=', time())
            ->where('status', 1)
            ->where('k_id', 0)
            ->where('is_refund', 0)
            ->field('id,people,k_id,uid,stop_time,order_id_key')->select()->toArray();
    }
}
