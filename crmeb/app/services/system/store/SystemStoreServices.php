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

namespace app\services\system\store;


use app\dao\system\store\SystemStoreDao;
use app\services\BaseServices;
use crmeb\exceptions\AdminException;

/**
 * cửa hàng
 * Class SystemStoreServices
 * @package app\services\system\store
 * @method update($id, array $data, ?string $key = null) Sửa đổi dữ liệu
 * @method get(int $id, ?array $field = []) Nhận dữ liệu
 */class SystemStoreServices extends BaseServices
{
    /**
     * Người xây dựng
     * SystemStoreServices constructor.
     * @param SystemStoreDao $dao
     */    public function __construct(SystemStoreDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Nhận danh sách điểm đón
     * @param array $where
     * @param string $latitude
     * @param string $longitude
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getStoreList(array $where, array $field = ['*'], string $latitude = '', string $longitude = '')
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getStoreList($where, $field, $page, $limit, $latitude, $longitude);
        foreach ($list as &$item) {
            if (isset($item['distance'])) {
                $item['range'] = bcdiv($item['distance'], '1000', 1);
            }
        }
        $count = $this->dao->count($where);
        return compact('list', 'count');
    }

    /**
     * Lấy thống kê đầu của điểm đón
     * @return mixed
     */    public function getStoreData()
    {
        $data['show'] = [
            'name' => 'Hiển thị điểm đón',
            'num' => $this->dao->count(['type' => 0]),
        ];
        $data['hide'] = [
            'name' => 'Điểm đón ẩn',
            'num' => $this->dao->count(['type' => 1]),
        ];
        $data['recycle'] = [
            'name' => 'Điểm thu gom thùng rác tái chế',
            'num' => $this->dao->count(['type' => 2])
        ];
        return $data;
    }

    /**
     * Lưu hoặc sửa đổi cửa hàng
     * @param int $id
     * @param array $data
     * @return mixed
     */    public function saveStore(int $id, array $data)
    {
        return $this->transaction(function () use ($id, $data) {
            if ($id) {
                if ($this->dao->update($id, $data)) {
                    return true;
                } else {
                    throw new AdminException('Sửa đổi không thành công');
                }
            } else {
                $data['add_time'] = time();
                $data['is_show'] = 1;
                if ($this->dao->save($data)) {
                    return true;
                } else {
                    throw new AdminException('Lưu không thành công');
                }
            }
        });
    }

    /**
     * Nhận thông tin chi tiết về điểm đón ở chế độ nền
     * @param int $id
     * @param string $felid
     * @return array|false|mixed|string|string[]|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getStoreDispose(int $id, string $felid = '')
    {
        if ($felid) {
            return $this->dao->value(['id' => $id], $felid);
        } else {
            $storeInfo = $this->dao->get($id);
            if ($storeInfo) {
                $storeInfo['latlng'] = $storeInfo['latitude'] . ',' . $storeInfo['longitude'];
                $storeInfo['dataVal'] = $storeInfo['valid_time'] ? explode(' - ', $storeInfo['valid_time']) : [];
                $storeInfo['timeVal'] = $storeInfo['day_time'] ? explode(' - ', $storeInfo['day_time']) : [];
                $storeInfo['address2'] = $storeInfo['address'] ? explode(',', $storeInfo['address']) : [];
                return $storeInfo;
            }
            return false;
        }
    }

    /**
     * Nhận cửa hàng mà không cần phân trang
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getStore()
    {
        return $this->dao->getStore(['type' => 0]);
    }

    /**
     * Nhận danh sách nhân viên cửa hàng xuất khẩu
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getExportData(array $where)
    {
        return $this->dao->getStoreList($where, ['*']);
    }

}
