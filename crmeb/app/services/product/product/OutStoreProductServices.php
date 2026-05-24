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

namespace app\services\product\product;

use app\dao\product\product\StoreProductDao;
use app\jobs\ProductStockJob;
use app\services\BaseServices;
use app\services\order\StoreCartServices;
use app\services\product\sku\StoreProductAttrResultServices;
use app\services\product\sku\StoreProductAttrServices;
use app\services\product\sku\StoreProductAttrValueServices;
use crmeb\exceptions\AdminException;

/**
 * Class OutStoreProductServices
 * @package app\services\product\product
 * @method getOne(array $where) Lấy một phần dữ liệu
 * @method update(int $id, array $data) Lấy một phần dữ liệu
 * @method delete($id, ?string $key = null) Xóa dữ liệu
 * @method value($where, ?string $field = null) Nhận các trường
 * @method incStockDecSales(array $where, int $num, string $stock = 'stock', string $sales = 'sales') Tăng hàng tồn kho và giảm doanh số bán hàng
 * @method count(array $where) Lấy số lượng theo điều kiện quy định
 * @method getColumn(array $where, string $field, string $key = '') Nhận một mảng trường
 * @method getSearchList(array $where, int $page = 0, int $limit = 0, ?array $field = ['*']) Nhận danh sách
 * @method get(int $id, array $field) Lấy một phần dữ liệu
 * @method getCid(int $page, int $limit) Nhận phân loại cấp độ đầu tiênID
 * @method downAdvance() Các mặt hàng bán trước sẽ tự động hết hạn và bị loại khỏi kệ
 */class OutStoreProductServices extends BaseServices
{
    protected $productType = ['Hàng thông thường', 'Sản phẩm thẻ', 'Mã giảm giá', 'hàng ảo'];

    public function __construct(StoreProductDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Thêm sản phẩm chỉnh sửa mới
     * @param int $id
     * @param array $data
     * @param int $validate
     */    public function save(int $id, array $data)
    {
        if (count($data['cate_id']) < 1) throw new AdminException('Vui lòng chọn danh mục sản phẩm');
        if (!$data['store_name']) throw new AdminException('Vui lòng nhập tên sản phẩm');

        if (count($data['slider_image']) < 1) {
            $data['is_show'] = 0;
        }

        $data['brand_id'] = 0;
        $data['logistics'] = 1; // Phương pháp hậu cần

        $detail = $data['attrs'];
        $attr = $data['items'];
        $cate_id = $data['cate_id'];
        $description = $data['description'];

        $data['is_limit'] = intval($data['is_limit']);
        if (!$data['is_limit']) {
            $data['limit_type'] = 0;
            $data['limit_num'] = 0;
        } else {
            if (!in_array($data['limit_type'], [1, 2])) throw new AdminException('Vui lòng chọn loại hạn chế mua hàng');
            if ($data['limit_num'] <= 0) throw new AdminException('Số lượng giới hạn mua hàng phải lớn hơn 0');
        }

        // Bưu phí cố định 0 có nghĩa là miễn phí vận chuyển
        $data['freight'] = 2;
        $data['postage'] = (float)$data['postage'];
        if (bccomp($data['postage'], '0.00', 2) < 0) {
            throw new AdminException('Lỗi định dạng phí vận chuyển');
        }

        if (count($data['activity']) == 4) {
            foreach ($data['activity'] as $k => $v) {
                if ($v == 'bán chớp nhoáng') {
                    $data['activity'][$k] = 1;
                } elseif ($v == 'Mặc cả') {
                    $data['activity'][$k] = 2;
                } elseif ($v == 'Chia sẻ nhóm') {
                    $data['activity'][$k] = 3;
                } else {
                    $data['activity'][$k] = 0;
                }
            }
            $data['activity'] = implode(',', $data['activity']);
        } else {
            $data['activity'] = '0,1,2,3';
        }

        $data['is_hot'] = $data['is_benefit'] = $data['is_new'] = $data['is_good'] = $data['is_best'] = 0;
        foreach ($data['recommend'] as $item) {
            if (isset($data[$item])) {
                $data[$item] = 1;
            }
        }

        $data['is_vip'] = intval(in_array($data['is_vip'], [0, 1]) ? $data['is_vip'] : 0);
        $data['is_sub'] = intval(in_array($data['is_sub'], [0, 1]) ? $data['is_sub'] : 0);
        $data['vip_product'] = intval($data['vip_product']);

        $data['presale'] = intval($data['presale']);
        $data['presale_start_time'] = $data['presale'] ? strtotime($data['presale_time'][0]) : 0;
        $data['presale_end_time'] = $data['presale'] ? strtotime($data['presale_time'][1]) : 0;

        foreach ($detail as &$item) {
            if (isset($item['stock'])) {
                unset($item['stock']);
            }

            if (empty($item['pic'])) {
                $data['is_show'] = 0;
            }

            $item['cost'] = sprintf("%.2f", $item['cost'] ?? '0.00');
            $item['price'] = sprintf("%.2f", $item['price'] ?? '0.00');
            $item['ot_price'] = sprintf("%.2f", $item['ot_price'] ?? '0.00');
            $item['weight'] = (string)$item['weight'];
            $item['volume'] = (string)$item['volume'];
            $item['pic'] = $item['pic'] ?? '';

            if ($data['is_vip'] == 0) {
                $item['vip_price'] = '0.00';
            } else {
                $item['vip_price'] = sprintf("%.2f", $item['vip_price'] ?? '0.00');
            }

            if ($data['is_sub'] == 0) {
                $item['brokerage'] = '0.00';
                $item['brokerage_two'] = '0.00';
            } else {
                $item['brokerage'] = sprintf("%.2f", $item['brokerage'] ?? '0.00');
                $item['brokerage_two'] = sprintf("%.2f", $item['brokerage'] ?? '0.00');
                if (bccomp(bcadd($item['brokerage'], $item['brokerage_two']), $item['price']) == 1) {
                    throw new AdminException('Tổng số tiền giảm giá cấp một và cấp hai không được lớn hơn giá bán của sản phẩm.');
                }
            }
        }

        $data['price'] = min(array_column($detail, 'price'));
        $data['ot_price'] = min(array_column($detail, 'ot_price'));
        $data['cost'] = min(array_column($detail, 'cost'));
        $data['cate_id'] = implode(',', $data['cate_id']);
        $data['image'] = $data['slider_image'][0] ?? '';
        $data['slider_image'] = json_encode($data['slider_image']);
        $data['give_integral'] = (int)$data['give_integral'];
        unset($data['description'], $data['coupon_ids'], $data['items'], $data['attrs']);
        /** @var StoreDescriptionServices $storeDescriptionServices */        $storeDescriptionServices = app()->make(StoreDescriptionServices::class);
        /** @var StoreProductCateServices $storeProductCateServices */        $storeProductCateServices = app()->make(StoreProductCateServices::class);
        /** @var StoreProductAttrServices $storeProductAttrServices */        $storeProductAttrServices = app()->make(StoreProductAttrServices::class);
        /** @var StoreCategoryServices $storeCategoryServices */        $storeCategoryServices = app()->make(StoreCategoryServices::class);
        /** @var StoreProductServices $productServices */        $productServices = app()->make(StoreProductServices::class);
        return $this->transaction(function () use ($id, $data, $description, $cate_id, $storeDescriptionServices, $storeProductCateServices, $storeProductAttrServices, $storeCategoryServices, $detail, $attr, $productServices) {
            if ($data['spec_type'] == 0) {
                $attr = [
                    [
                        'value' => 'Đặc điểm kỹ thuật',
                        'detailValue' => '',
                        'attrHidden' => '',
                        'detail' => ['mặc định']
                    ]
                ];
                $detail[0]['value1'] = 'Đặc điểm kỹ thuật';
                $detail[0]['detail'] = ['Đặc điểm kỹ thuật' => 'mặc định'];
            }
            if ($id) {
                $this->dao->update($id, $data);
                $storeDescriptionServices->saveDescription($id, $description);
                $cateData = [];
                $time = time();
                $cateGory = $storeCategoryServices->getColumn([['id', 'IN', $cate_id]], 'id,pid', 'id');
                foreach ($cate_id as $cid) {
                    if ($cid && isset($cateGory[$cid]['pid'])) {
                        $cateData[] = ['product_id' => $id, 'cate_id' => $cid, 'cate_pid' => $cateGory[$cid]['pid'], 'status' => $data['is_show'], 'add_time' => $time];
                    }
                }
                $storeProductCateServices->change($id, $cateData);
                $skuList = $productServices->validateProductAttr($attr, $detail, $id, 0, 0);
                $attrRes = $storeProductAttrServices->saveProductAttr($skuList, $id, 0, 0, 0);
                if (!$attrRes) throw new AdminException('Thêm không thành công');
            } else {
                $data['add_time'] = time();
                $data['code_path'] = '';
                $data['spu'] = $productServices->createSpu();
                $res = $this->dao->save($data);
                $storeDescriptionServices->saveDescription($res->id, $description);
                $cateData = [];
                $time = time();
                $cateGory = $storeCategoryServices->getColumn([['id', 'IN', $cate_id]], 'id,pid', 'id');
                foreach ($cate_id as $cid) {
                    if ($cid && isset($cateGory[$cid]['pid'])) {
                        $cateData[] = ['product_id' => $res->id, 'cate_id' => $cid, 'cate_pid' => $cateGory[$cid]['pid'], 'status' => $data['is_show'], 'add_time' => $time];
                    }
                }
                $storeProductCateServices->change($res->id, $cateData);
                $skuList = $productServices->validateProductAttr($attr, $detail, $res->id, 0, 0, true);
                $attrRes = $storeProductAttrServices->saveProductAttr($skuList, $res->id, 0, 0, 0);
                if (!$attrRes) throw new AdminException('Thêm không thành công');
                $id = (int)$res->id;
            }
            return $id;
        });
    }

    /**
     * Thiết lập và xóa sản phẩm
     * @param int $id
     * @param int $is_show
     */    public function setShow(int $id, int $is_show)
    {
        if (empty($id)) throw new AdminException('Lỗi tham số');

        if ($is_show) {
            // Kiểm tra xem sản phẩm có thể được đưa lên kệ hay không
            $this->checkShelves($id);
        }

        /** @var StoreCartServices $cartService */        $cartService = app()->make(StoreCartServices::class);
        $cartService->changeStatus($id, $is_show);
        $this->dao->update($id, ['is_show' => $is_show]);

        /** @var StoreProductCateServices $storeProductCateServices */        $storeProductCateServices = app()->make(StoreProductCateServices::class);
        $storeProductCateServices->update($id, ['status' => $is_show], 'product_id');
        return true;
    }

    /**
     * Nhận chi tiết sản phẩm
     * @param int $id
     * @return array|\think\Model|null
     */    public function getInfo(int $id)
    {
        /** @var StoreDescriptionServices $storeDescriptionServices */        $storeDescriptionServices = app()->make(StoreDescriptionServices::class);
        /** @var StoreProductAttrResultServices $storeProductAttrResultServices */        $storeProductAttrResultServices = app()->make(StoreProductAttrResultServices::class);
        /** @var StoreProductAttrValueServices $storeProductAttrValueServices */        $storeProductAttrValueServices = app()->make(StoreProductAttrValueServices::class);
        /** @var StoreProductServices $productServices */        $productServices = app()->make(StoreProductServices::class);
        $productInfo = $this->dao->get($id, ['id', 'image', 'slider_image', 'store_name', 'store_info', 'keyword', 'bar_code',
            'cate_id', 'price', 'ot_price', 'postage', 'unit_name', 'sort', 'is_show', 'cost', 'spec_type', 'spu', 'freight',
            'is_vip', 'vip_price', 'is_limit', 'limit_type', 'limit_num', 'give_integral']);
        if ($productInfo) {
            $productInfo = $productInfo->toArray();
        } else {
            throw new AdminException('Sản phẩm không tồn tại');
        }

        $productInfo['cate_id'] = $productInfo['cate_id'] ? array_map('intval', explode(',', $productInfo['cate_id'])) : [];
        $productInfo['cate_name'] = '';
        if ($productInfo['cate_id']) {
            /** @var StoreCategoryServices $storeCategoryServices */            $storeCategoryServices = app()->make(StoreCategoryServices::class);
            $cateList = $storeCategoryServices->getCateArray(implode(',', $productInfo['cate_id']));
            $productInfo['cate_name'] = implode(',', array_column($cateList, 'cate_name'));
        }

        $productInfo['description'] = $storeDescriptionServices->getDescription(['product_id' => $id, 'type' => 0]);
        /** @var StoreProductAttrServices $storeProductAttrServices */        $storeProductAttrServices = app()->make(StoreProductAttrServices::class);
        //Không có thuộc tính Thêm thuộc tính mặc định
        if (!$storeProductAttrResultServices->getResult(['product_id' => $id, 'type' => 0])) {
            $attr = [
                [
                    'value' => 'Đặc điểm kỹ thuật',
                    'detailValue' => '',
                    'attrHidden' => '',
                    'detail' => ['mặc định']
                ]
            ];
            $detail[0] = [
                'value1' => 'mặc định',
                'detail' => ['Đặc điểm kỹ thuật' => 'mặc định'],
                'pic' => $productInfo['image'],
                'price' => $productInfo['price'],
                'cost' => $productInfo['cost'],
                'ot_price' => $productInfo['ot_price'],
                'stock' => $productInfo['stock'] ?? 0,
                'bar_code' => '',
                'bar_code_number' => '',
                'weight' => '0.00',
                'volume' => '0.00'
            ];
            $skuList = $productServices->validateProductAttr($attr, $detail, $id, 0, 1, true);
            $storeProductAttrServices->saveProductAttr($skuList, $id, 0);
            $this->dao->update($id, ['spec_type' => 0]);
        }
        if ($productInfo['spec_type'] == 1) {
            $result = $storeProductAttrResultServices->getResult(['product_id' => $id, 'type' => 0]);
            foreach ($result['value'] as $k => $v) {
                $num = 1;
                foreach ($v['detail'] as $dv) {
                    $result['value'][$k]['value' . $num] = $dv;
                    $num++;
                }
                $result['value'][$k]['price'] = sprintf("%.2f", $v['price'] ?? '0.00');
                $result['value'][$k]['cost'] = sprintf("%.2f", $v['cost'] ?? '0.00');
                $result['value'][$k]['ot_price'] = sprintf("%.2f", $v['ot_price'] ?? '0.00');
                $result['value'][$k]['vip_price'] = sprintf("%.2f", $v['vip_price'] ?? '0.00');
                $result['value'][$k]['weight'] = sprintf("%.2f", $v['weight'] ?? '0.00');
                $result['value'][$k]['volume'] = sprintf("%.2f", $v['volume'] ?? '0.00');
                $result['value'][$k]['brokerage'] = sprintf("%.2f", $v['brokerage'] ?? '0.00');
                $result['value'][$k]['brokerage_two'] = sprintf("%.2f", $v['brokerage_two'] ?? '0.00');
                $result['value'][$k]['vip_price'] = sprintf("%.2f", $v['vip_price'] ?? '0.00');

                unset($result['value'][$k]['is_virtual'], $result['value'][$k]['stock']);
            }
            $productInfo['items'] = $result['attr'];
            $productInfo['attrs'] = $result['value'];
        } else {
            $result = $storeProductAttrValueServices->getOne(['product_id' => $id, 'type' => 0]);
            $productInfo['items'] = [];
            $productInfo['attrs'] = [];
            $productInfo['attrs'][] = [
                'pic' => $result['image'] ?? '',
                'price' => $result['price'] ?? '0.00',
                'cost' => $result['cost'] ?? '0.00',
                'ot_price' => $result['ot_price'] ?? '0.00',
                'bar_code' => $result['bar_code'] ?? '',
                'bar_code_number' => $result['bar_code_number'] ?? '',
                'weight' => $result['weight'] ?? '0.00',
                'volume' => $result['volume'] ?? '0.00',
                'brokerage' => $result['brokerage'] ?? '0.00',
                'brokerage_two' => $result['brokerage_two'] ?? '0.00',
                'vip_price' => $result['vip_price'] ?? '0.00',
            ];
        }
        return $productInfo;
    }

    /**
     * Lấy danh sách sản phẩm đã chọn
     * @param array $where
     * @return array
     */    public function searchList(array $where)
    {
        [$page, $limit] = $this->getPageValue();
        $field = ['id', 'image', 'slider_image', 'store_name', 'store_info', 'keyword', 'bar_code', 'cate_id', 'price',
            'is_vip', 'vip_price', 'ot_price', 'postage', 'unit_name', 'sort', 'is_show', 'cost', 'spec_type', 'spu', 'freight'];
        $list = $this->dao->getSearchList($where, $page, $limit, $field, ['description']);

        $cateIds = implode(',', array_column($list, 'cate_id'));
        /** @var StoreCategoryServices $storeCategoryServices */        $storeCategoryServices = app()->make(StoreCategoryServices::class);
        $cateList = $storeCategoryServices->getCateArray($cateIds);

        foreach ($list as &$item) {
            $item['cate_id'] = $item['cate_id'] ? array_map('intval', explode(',', $item['cate_id'])) : [];
            $cateName = array_filter($cateList, function ($val) use ($item) {
                if (in_array($val['id'], $item['cate_id'])) {
                    return $val;
                }
            });

            $item['cate_name'] = implode(',', array_column($cateName, 'cate_name'));
        }
        $count = $this->dao->getCount($where);
        return compact('count', 'list');
    }

    /**
     * Kiểm tra kệ
     * @param int $id
     * @return void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    private function checkShelves(int $id)
    {
        $productInfo = $this->dao->get($id, ['id', 'image', 'slider_image', 'spec_type', 'unit_name', 'cate_id'], []);
        if ($productInfo) {
            $productInfo = $productInfo->toArray();
        } else {
            throw new AdminException('Sản phẩm không tồn tại');
        }

        if (!$productInfo['image'] || !$productInfo['slider_image']) {
            throw new AdminException('Vui lòng chọn hình ảnh băng chuyền sản phẩm');
        }

        if (!$productInfo['unit_name']) {
            throw new AdminException('Vui lòng điền vào Đơn vị');
        }

        if (!$productInfo['cate_id']) {
            throw new AdminException('Vui lòng chọn danh mục sản phẩm');
        }

        if ($productInfo['spec_type'] == 1) {
            /** @var StoreProductAttrResultServices $storeProductAttrResultServices */            $storeProductAttrResultServices = app()->make(StoreProductAttrResultServices::class);
            $result = $storeProductAttrResultServices->getResult(['product_id' => $id, 'type' => 0]);
            foreach ($result['value'] as $v) {
                foreach ($v['detail'] as $dv) {
                    if (!$dv['pic']) {
                        throw new AdminException('Vui lòng tải lên hình ảnh sản phẩm');
                    }
                }
            }
        } else {
            /** @var StoreProductAttrValueServices $storeProductAttrValueServices */            $storeProductAttrValueServices = app()->make(StoreProductAttrValueServices::class);
            $result = $storeProductAttrValueServices->getOne(['product_id' => $id, 'type' => 0]);
            if (!$result['image']) {
                throw new AdminException('Vui lòng tải lên hình ảnh sản phẩm');
            }
        }
    }

    /**
     * Đồng bộ hóa hàng tồn kho
     * @param array $items
     * @return void
     */    public function syncStock(array $items)
    {
        return $this->transaction(function () use ($items) {
            $goods = $saveData = [];
            // Đồng bộ hóa kho giá trị đặc tả
            /** @var StoreProductAttrValueServices $storeProductAttrValueServices */            $storeProductAttrValueServices = app()->make(StoreProductAttrValueServices::class);
            $list = $storeProductAttrValueServices->getColumn(['bar_code' => array_column($items, 'bar_code')], 'id, product_id, bar_code, stock', 'bar_code');

            foreach ($items as $item) {
                $value = $list[$item['bar_code']] ?? [];
                if (!$value) continue;
                if (!isset($goods[$value['product_id']])) $goods[$value['product_id']] = 1;
                $saveData[] = ['id' => $value['id'], 'stock' => $item['qty']];
            }

            if ($saveData) {
                $storeProductAttrValueServices->saveAll($saveData);
            }

            if ($goods) {
                ProductStockJob::dispatch('distribute', [$goods]);
            }
            return true;
        });
    }

    /**
     * Tính toán tồn kho sản phẩm
     * @param int $id
     * @return void
     */    public function calcStockByAttrValue(int $id)
    {
        return $this->transaction(function () use ($id) {
            /** @var StoreProductAttrValueServices $storeProductAttrValueServices */            $storeProductAttrValueServices = app()->make(StoreProductAttrValueServices::class);

            /** @var StoreProductAttrResultServices $storeProductAttrResultServices */            $storeProductAttrResultServices = app()->make(StoreProductAttrResultServices::class);

            $stock = $storeProductAttrValueServices->sum(['product_id' => $id], 'stock');
            $res = $this->dao->update($id, ['stock' => $stock]);
            if (!$res) throw new AdminException('Sửa đổi không thành công');

            $attrValue = $storeProductAttrValueServices->getColumn(['product_id' => $id], 'stock', 'bar_code');
            $result = $storeProductAttrResultServices->getResult(['product_id' => $id, 'type' => 0]);
            if (!$attrValue || !$result) return;

            foreach ($result['value'] as $k => $value) {
                if (isset($attrValue[$value['bar_code']])) {
                    $result['value'][$k]['stock'] = $attrValue[$value['bar_code']];
                }
            }
            $storeProductAttrResultServices->del($id, 0);
            $storeProductAttrResultServices->setResult($result, $id, 0);
            return true;
        });
    }
}
