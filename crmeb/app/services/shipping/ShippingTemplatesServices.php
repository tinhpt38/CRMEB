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

namespace app\services\shipping;

use app\services\BaseServices;
use app\dao\shipping\ShippingTemplatesDao;
use crmeb\exceptions\AdminException;

/**
 * Mẫu vận chuyển hàng hóa
 * Class ShippingTemplatesServices
 * @package app\services\shipping
 * @method getSelectList() Nhận danh sách lựa chọn thả xuống
 * @method get($id) Lấy một phần dữ liệu
 * @method getShippingColumn(array $where, string $field, string $key) Nhận dữ liệu theo các điều kiện quy định của mẫu vận chuyển hàng hóa
 */
class ShippingTemplatesServices extends BaseServices
{

    /**
     * Người xây dựng
     * ShippingTemplatesServices constructor.
     * @param ShippingTemplatesDao $dao
     */
    public function __construct(ShippingTemplatesDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Nhận danh sách các mẫu vận chuyển hàng hóa
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getShippingList(array $where)
    {
        [$page, $limit] = $this->getPageValue();
        $data = $this->dao->getShippingList($where, $page, $limit);
        $count = $this->dao->count($where);
        return compact('data', 'count');
    }

    /**
     * Lấy mẫu vận chuyển hàng hóa cần sửa đổi
     * @param int $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getShipping(int $id)
    {
        $templates = $this->dao->get($id);
        if (!$templates) {
            throw new AdminException('Mẫu đã sửa đổi không tồn tại');
        }
        /** @var ShippingTemplatesFreeServices $freeServices */
        $freeServices = app()->make(ShippingTemplatesFreeServices::class);
        /** @var ShippingTemplatesRegionServices $regionServices */
        $regionServices = app()->make(ShippingTemplatesRegionServices::class);
        /** @var ShippingTemplatesNoDeliveryServices $noDeliveryServices */
        $noDeliveryServices = app()->make(ShippingTemplatesNoDeliveryServices::class);
        $data['appointList'] = $freeServices->getFreeList($id);
        $data['templateList'] = $regionServices->getRegionList($id);
        $data['noDeliveryList'] = $noDeliveryServices->getNoDeliveryList($id);
        if (!isset($data['templateList'][0]['region'])) {
            $data['templateList'][0]['region'] = ['city_id' => 0, 'name' => 'Mặc định trên toàn quốc'];
        }
        $data['formData'] = [
            'name' => $templates->name,
            'type' => $templates->getData('type'),
            'appoint_check' => intval($templates->getData('appoint')),
            'no_delivery_check' => intval($templates->getData('no_delivery')),
            'sort' => intval($templates->getData('sort')),
        ];
        return $data;
    }

    /**
     * Lưu hoặc sửa đổi mẫu vận chuyển hàng hóa
     * @param int $id
     * @param array $temp
     * @param array $data
     * @return mixed
     */
    public function save(int $id, array $temp, array $data)
    {
        if ($id) {
            $res = $this->dao->update($id, $temp);
        } else {
            $id = $this->dao->insertGetId($temp);
            $res = true;
        }

        /** @var ShippingTemplatesRegionServices $regionServices */
        $regionServices = app()->make(ShippingTemplatesRegionServices::class);


        return $this->transaction(function () use ($regionServices, $data, $id, $res) {
            //Thiết lập giao hàng khu vực
            $res = $res && $regionServices->saveRegion($data['region_info'], (int)$data['type'], (int)$id);
            if (!$res) {
                throw new AdminException('Không thể thêm bưu phí cho khu vực được chỉ định');
            }
            //Đặt giao hàng miễn phí được chỉ định
            if ($data['appoint']) {
                /** @var ShippingTemplatesFreeServices $freeServices */
                $freeServices = app()->make(ShippingTemplatesFreeServices::class);
                $res = $res && $freeServices->saveFree($data['appoint_info'], (int)$data['type'], (int)$id);
            }

            //Thiết lập không giao hàng
            if ($data['no_delivery']) {
                /** @var ShippingTemplatesNoDeliveryServices $noDeliveryServices */
                $noDeliveryServices = app()->make(ShippingTemplatesNoDeliveryServices::class);
                $res = $res && $noDeliveryServices->saveNoDelivery($data['no_delivery_info'], (int)$id);
            }

            if ($res) {
                return true;
            } else {
                throw new AdminException('Lưu không thành công');
            }
        });
    }

    /**
     * Xóa mẫu vận chuyển
     * @param int $id
     */
    public function detete(int $id)
    {
        $this->dao->delete($id);
        /** @var ShippingTemplatesFreeServices $freeServices */
        $freeServices = app()->make(ShippingTemplatesFreeServices::class);
        /** @var ShippingTemplatesRegionServices $regionServices */
        $regionServices = app()->make(ShippingTemplatesRegionServices::class);
        /** @var ShippingTemplatesNoDeliveryServices $noDeliveryServices */
        $noDeliveryServices = app()->make(ShippingTemplatesNoDeliveryServices::class);
        $freeServices->delete($id, 'temp_id');
        $regionServices->delete($id, 'temp_id');
        $noDeliveryServices->delete($id, 'temp_id');
    }
}
