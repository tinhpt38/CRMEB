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

namespace app\services\shipping;


use app\dao\shipping\ShippingTemplatesRegionDao;
use app\services\BaseServices;
use crmeb\exceptions\AdminException;

/**
 * Chỉ định bưu phí
 * Class ShippingTemplatesRegionServices
 * @package app\services\shipping
 * @method  delete($id, ?string $key = null) Xóa dữ liệu
 * @method getTempRegionList(array $tempIds, array $cityId) Nhận danh sách dữ liệu vận chuyển miễn phí dựa trên id mẫu vận chuyển sản phẩm và id thành phố
 */class ShippingTemplatesRegionServices extends BaseServices
{
    /**
     * Người xây dựng
     * ShippingTemplatesRegionServices constructor.
     * @param ShippingTemplatesRegionDao $dao
     */    public function __construct(ShippingTemplatesRegionDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Thêm thông tin vận chuyển
     * @param array $regionInfo
     * @param int $type
     * @param int $tempId
     * @return bool
     * @throws \Exception
     */    public function saveRegion(array $regionInfo, int $type = 0, $tempId = 0)
    {
        $res = true;
        if ($tempId) {
            if ($this->dao->count(['temp_id' => $tempId])) {
                $res = $this->dao->delete($tempId, 'temp_id');
            }
        }
        $regionList = [];
        foreach ($regionInfo as $item) {
            if (isset($item['region']) && is_array($item['region'])) {
                $uniqid = uniqid('adminapi') . rand(1000, 9999);
                foreach ($item['region'] as $value) {
                    if (isset($value['children']) && is_array($value['children'])) {
                        foreach ($value['children'] as $vv) {
                            if (!isset($vv['city_id'])) {
                                throw new AdminException('Không thể lưu nếu không có id thành phố');
                            }
                            $regionList[] = [
                                'temp_id' => $tempId,
                                'province_id' => $value['city_id'] ?? 0,
                                'city_id' => $vv['city_id'] ?? 0,
                                'first' => $item['first'] ?? 0,
                                'first_price' => $item['price'] ?? 0,
                                'continue' => $item['continue'] ?? 0,
                                'continue_price' => $item['continue_price'] ?? 0,
                                'type' => $type,
                                'uniqid' => $uniqid,
                            ];
                        }
                    } else {
                        $regionList[0] = [
                            'temp_id' => $tempId,
                            'province_id' => 0,
                            'city_id' => 0,
                            'first' => $item['first'] ?? 0,
                            'first_price' => $item['price'] ?? 0,
                            'continue' => $item['continue'] ?? 0,
                            'continue_price' => $item['continue_price'] ?? 0,
                            'type' => $type,
                            'uniqid' => $uniqid,
                        ];
                    }
                }
            }
        }
        return $res && $this->dao->saveAll($regionList);
    }

    /**
     * Nhận dữ liệu thành phố theo mẫu vận chuyển sản phẩm nhất định
     * @param int $tempId
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getRegionList(int $tempId)
    {
        $regionList = $this->dao->getShippingGroupArray(['temp_id' => $tempId], 'uniqid', 'uniqid', '');
        $regionData = [];
        $infos = $this->dao->getShippingArray(['uniqid' => $regionList, 'temp_id' => $tempId], '*', 'uniqid');
        foreach ($regionList as $uniqid) {
            $info = $infos[$uniqid];
            if ($info['province_id'] == 0) {
                $regionData[] = [
                    'region' => [
                        'city_id' => 0,
                        'name' => 'Mặc định trên toàn quốc',
                    ],
                    'regionName' => 'Mặc định trên toàn quốc',
                    'first' => $info['first'] ? floatval($info['first']) : 0,
                    'price' => $info['first_price'] ? floatval($info['first_price']) : 0,
                    'continue' => $info['continue'] ? floatval($info['continue']) : 0,
                    'continue_price' => $info['continue_price'] ? floatval($info['continue_price']) : 0,
                    'uniqid' => $info['uniqid'],
                ];
            } else {
                $regionData[] = [
                    'region' => $this->getRegionTemp($uniqid, $info['province_id']),
                    'regionName' => '',
                    'first' => $info['first'] ? floatval($info['first']) : 0,
                    'price' => $info['first_price'] ? floatval($info['first_price']) : 0,
                    'continue' => $info['continue'] ? floatval($info['continue']) : 0,
                    'continue_price' => $info['continue_price'] ? floatval($info['continue_price']) : 0,
                    'uniqid' => $info['uniqid'],
                ];
            }
        }

        foreach ($regionData as &$item) {
            if (!$item['regionName']) {
                $item['regionName'] = implode(';', array_map(function ($val) {
                    return $val['name'];
                }, $item['region']));
            }
        }

        return $regionData;
    }

    /**
     * Lấy mẫu cước vận chuyển theo tỉnh thành
     * @param string $uniqid
     * @param int $provinceId
     * @return array
     */    public function getRegionTemp(string $uniqid, int $provinceId)
    {
        /** @var ShippingTemplatesRegionCityServices $services */        $services = app()->make(ShippingTemplatesRegionCityServices::class);
        $infoList = $services->getUniqidList(['uniqid' => $uniqid]);
        $childrenData = [];
        foreach ($infoList as $item) {
            $childrenData[] = [
                'city_id' => $item['province_id'],
                'name' => $item['name'] ?? 'Toàn quốc',
                'children' => $this->getCityTemp($uniqid, $item['province_id'])
            ];
        }
        return $childrenData;
    }

    /**
     * Lấy dữ liệu theo khu vực thành thị
     * @param string $uniqid
     * @param int $provinceId
     * @return array
     */    public function getCityTemp(string $uniqid, int $provinceId)
    {
        /** @var ShippingTemplatesRegionCityServices $services */        $services = app()->make(ShippingTemplatesRegionCityServices::class);
        $infoList = $services->getUniqidList(['uniqid' => $uniqid, 'province_id' => $provinceId], false);
        $childrenData = [];
        foreach ($infoList as $item) {
            $childrenData[] = [
                'city_id' => $item['city_id'],
                'name' => $item['name'] ?? 'Toàn quốc',
            ];
        }
        return $childrenData;
    }
}
