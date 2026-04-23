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

namespace app\dao\service;

use app\dao\BaseDao;
use app\model\service\StoreServiceLog;

/**
 * Lịch sử trò chuyện dịch vụ khách hàngdao
 * Class StoreServiceLogDao
 * @package app\dao\service
 */
class StoreServiceLogDao extends BaseDao
{

    /**
     * StoreServiceLogDao constructor.
     */
    public function __construct()
    {
        //Xóa lịch sử trò chuyện từ năm ngoái
//        $this->removeChat();
//        $this->removeYesterDayChat();
    }

    /**
     * Thiết lập mô hình
     * @return string
     */
    protected function setModel(): string
    {
        return StoreServiceLog::class;
    }

    /**
     * Lấy uid và bản ghi trò chuyệnto_uid
     * @param int $uid
     * @return mixed
     */
    public function getServiceUserUids(int $uid)
    {
        return $this->search(['uid' => $uid])->group('uid,to_uid')->field(['uid', 'to_uid'])->select()->toArray();
    }

    /**
     * Nhận lịch sử trò chuyện và phân trang
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getServiceList(array $where, int $page, int $limit, array $field = ['*'])
    {
        return $this->search($where)->with('user')->field($field)->order('add_time DESC')->page($page, $limit)->select()->toArray();
    }

    /**
     * Lấy lịch sử chat để lật trang
     * @param array $where
     * @param int $page
     * @param int $limit
     * @param bool $isUp
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getChatList(array $where, int $limit = 20, int $upperId = 0)
    {
        return $this->search($where)->when($upperId, function ($query) use ($upperId, $limit) {
            $query->where('id', '<', $upperId)->limit($limit)->order('id DESC');
        })->when(!$upperId, function ($query) use ($limit) {
            $query->limit($limit)->order('id DESC');
        })->with(['user'])->select()->toArray();
    }

    /**
     * Xóa lịch sử trò chuyện từ năm ngoái
     * @return bool
     */
    public function removeChat()
    {
        return $this->search(['time' => 'last year'])->delete();
    }

    /**
     * Xóa lịch sử trò chuyện của người dùng khách vào tuần trước
     * @return bool
     */
    public function removeYesterDayChat()
    {
        return $this->search(['time' => 'last week', 'is_tourist' => 1])->delete();
    }


    /**
     * Lấy số lượng vật phẩm dựa trên điều kiện
     * @param array $where
     * @return int
     */
    public function whereByCount(array $where)
    {
        return $this->search(['uid' => $where['uid']])->order('id DESC')->where('add_time', '<', time() - 300)->count();
    }

    /**
     * Lấy số lượng tin nhắn chưa đọc
     * @param array $where
     * @return int
     */
    public function getMessageNum(array $where)
    {
        return $this->getModel()->where($where)->count();
    }

    /**
     * Tìm kiếm lịch sử trò chuyện
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getMessageList(array $where)
    {
        return $this->search(['chat' => $where['chat']])->when(isset($where['add_time']) && $where['add_time'], function ($query) use ($where) {
            $query->where('add_time', '>', $where['add_time']);
        })->select()->toArray();
    }
}
