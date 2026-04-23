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
use app\model\service\StoreServiceRecord;

/**
 * Class StoreServiceRecordDao
 * @package app\dao\service
 */
class StoreServiceRecordDao extends BaseDao
{

    /**
     * StoreServiceRecordDao constructor.
     */
    public function __construct()
    {
        $this->deleteWeekRecord();
    }

    /**
     * Thiết lập mô hình
     * @return string
     */
    protected function setModel(): string
    {
        return StoreServiceRecord::class;
    }

    /**
     * Xóa hồ sơ khách truy cập từ tuần trước
     */
    protected function deleteWeekRecord()
    {
        $this->search(['time' => 'last week', 'timeKey' => 'update_time', 'is_tourist' => 1])->delete();
    }

    /**
     *
     * @param array $where
     * @param array $data
     * @return \crmeb\basic\BaseModel
     */
    public function updateOnline(array $where, array $data)
    {
        return $this->getModel()->whereNotIn('to_uid', $where['notUid'])->update($data);
    }

    /**
     * Nhận danh sách người dùng trò chuyện dịch vụ khách hàng
     * @param array $where
     * @param int $page
     * @param int $limit
     * @param array $with
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getServiceList(array $where, int $page, int $limit, array $with = [])
    {
        return $this->search($where)->page($page, $limit)->when(count($with), function ($query) use ($with) {
            $query->with($with);
        })->order('update_time desc')->select()->toArray();
    }

    /**
     * Truy vấn người dùng uid gần đây đã trò chuyện với người dùng
     * @param array $where
     * @param string $key
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getLatelyMsgUid(array $where, string $key)
    {
        return $this->search($where)->order('update_time DESC')->value($key);
    }
}
