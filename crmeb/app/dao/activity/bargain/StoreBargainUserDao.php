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

namespace app\dao\activity\bargain;

use app\dao\BaseDao;
use app\model\activity\bargain\StoreBargainUser;

/**
 *
 * Class StoreBargainUserDao
 * @package app\dao\activity
 */class StoreBargainUserDao extends BaseDao
{

    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return StoreBargainUser::class;
    }

    /**
     * Nhận số lượng người giúp đỡ
     * @param array $where
     * @return array
     */    public function getAllCount(array $where = [])
    {
        return $this->getModel()->where($where)->group('bargain_id')->column('count(*)', 'bargain_id');
    }

    /**
     * Nhận bảng giáID
     * @param int $bargainId $bargainId Sản phẩm trả giá
     * @param int $bargainUserUid $bargainUserUid  Kích hoạt ID Khách hàng thương lượng
     * @param int $status $status  Trạng thái thương lượng 1 Đang tham gia 2 Việc tham gia không thành công khi kết thúc sự kiện 3 Tham gia thành công khi kết thúc sự kiện
     * @return mixed
     */    public function getBargainUserTableId(int $bargainId = 0, int $bargainUserUid = 0)
    {
        return $this->value(['bargain_id' => $bargainId, 'uid' => $bargainUserUid, 'is_del' => 0, 'status' => 1], 'id') ?? 0;
    }

    /**
     * Nhận danh sách thương lượng của Khách hàng
     * @param int $bargainUserUid
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function userAll(int $bargainUserUid, int $page, int $limit)
    {
        return $this->search(['uid' => $bargainUserUid, 'is_del' => 0])->with('getBargain')->order('add_time DESC,id DESC')->page($page, $limit)->select()->toArray();
    }

    /**
     * Nhận trạng thái thương lượng
     * @param $bargainId
     * @param $uid
     * @return mixed
     */    public function getBargainUserStatus($bargainId, $uid)
    {
        return $this->search(['bargain_id' => $bargainId, 'uid' => $uid])->order('add_time DESC')->value('status');
    }


    /**
     * Sửa đổi trạng thái thương lượng
     * @param int $id
     * @param int $status
     * @return \crmeb\basic\BaseModel
     */    public function updateBargainStatus(int $id, int $status = 3)
    {
        return $this->getModel()->where('id', $id)->where('status', 1)->update(['status' => $status]);
    }

    /**
     * Lịch sử trả giá
     * @param $where
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function bargainUserList($where, $page = 0, $limit = 0)
    {
        return $this->search($where)->with(['getBargain', 'getUser'])
            ->when(isset($where['real_name']) && $where['real_name'] != '', function ($query) use ($where) {
                $query->where('uid', $where['real_name']);
            })->when($page != 0, function ($query) use ($page, $limit) {
                $query->page($page, $limit);
            })->order('add_time desc')->select()->toArray();
    }
}
