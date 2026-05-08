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

namespace app\services\activity\advance;

use app\dao\activity\advance\StoreAdvanceDao;
use app\jobs\ProductLogJob;
use app\Request;
use app\services\BaseServices;
use app\services\order\StoreOrderServices;
use app\services\other\QrcodeServices;
use app\services\product\product\StoreDescriptionServices;
use app\services\product\product\StoreProductRelationServices;
use app\services\product\product\StoreProductReplyServices;
use app\services\product\product\StoreProductServices;
use app\services\product\sku\StoreProductAttrResultServices;
use app\services\product\sku\StoreProductAttrServices;
use app\services\product\sku\StoreProductAttrValueServices;
use crmeb\exceptions\AdminException;
use crmeb\exceptions\ApiException;
use crmeb\services\CacheService;

/**
 * Bán trước sản phẩm
 * Class StoreAdvanceServices
 * @package app\services\activity
 * @method get(int $id, array $field) Lấy một phần dữ liệu
 * @method getAdvanceStatus(array $ids) Biết liệu sản phẩm bán trước có được bật hay không
 */
class StoreAdvanceServices extends BaseServices
{
    /**
     * StoreAdvanceServices constructor.
     * @param StoreAdvanceDao $dao
     */
    public function __construct(StoreAdvanceDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Nhận danh sách bán trước ở chế độ nền
     * @param $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList($where)
    {
        [$page, $limit] = $this->getPageValue();
        $where['is_del'] = 0;
        $list = $this->dao->getList($where, $page, $limit);
        $count = $this->dao->getCount($where);
        return compact('list', 'count');
    }

    /**
     * Lưu dữ liệu trước khi bán
     * @param $id
     * @param $data
     */
    public function saveData($id, $data)
    {
        $description = $data['description'];
        $detail = $data['attrs'];
        $items = $data['items'];
        $data['start_time'] = strtotime($data['section_time'][0]);
        $data['stop_time'] = strtotime($data['section_time'][1]);
        $data['images'] = json_encode($data['images']);
        $data['price'] = min(array_column($detail, 'price'));
        $data['ot_price'] = min(array_column($detail, 'ot_price'));
        $data['cost'] = min(array_column($detail, 'cost'));
        $data['quota'] = $data['quota_show'] = array_sum(array_column($detail, 'quota'));
        $data['stock'] = array_sum(array_column($detail, 'stock'));
        if ($data['type']) {
            $data['pay_start_time'] = strtotime($data['pay_time'][0]);
            $data['pay_stop_time'] = strtotime($data['pay_time'][1]);
        }
        unset($data['section_time'], $data['description'], $data['attrs'], $data['items']);
        /** @var StoreDescriptionServices $storeDescriptionServices */
        $storeDescriptionServices = app()->make(StoreDescriptionServices::class);
        /** @var StoreProductAttrServices $storeProductAttrServices */
        $storeProductAttrServices = app()->make(StoreProductAttrServices::class);
        /** @var StoreProductServices $storeProductServices */
        $storeProductServices = app()->make(StoreProductServices::class);
        if ($data['quota'] > $storeProductServices->value(['id' => $data['product_id']], 'stock')) {
            throw new AdminException('Giới hạn không thể vượt quá số lượng sản phẩm tồn kho');
        }
        $this->transaction(function () use ($id, $data, $description, $detail, $items, $storeDescriptionServices, $storeProductAttrServices, $storeProductServices) {
            if ($id) {
                $res = $this->dao->update($id, $data);
                $storeDescriptionServices->saveDescription((int)$id, $description, 6);
                $skuList = $storeProductServices->validateProductAttr($items, $detail, (int)$id, 6);
                $valueGroup = $storeProductAttrServices->saveProductAttr($skuList, (int)$id, 6);
                if (!$res) throw new AdminException('Sửa đổi không thành công');
            } else {
                if (!$storeProductServices->getOne(['is_show' => 1, 'is_del' => 0, 'id' => $data['product_id']])) {
                    throw new AdminException('Sản phẩm đã được lấy ra khỏi kệ hoặc chuyển vào thùng tái chế');
                }
                $data['add_time'] = time();
                $res = $this->dao->save($data);
                $storeProductServices->update($data['product_id'], ['is_show' => 0]);
                $storeDescriptionServices->saveDescription((int)$res->id, $description, 6);
                $skuList = $storeProductServices->validateProductAttr($items, $detail, (int)$res->id, 6, 1, true);
                $valueGroup = $storeProductAttrServices->saveProductAttr($skuList, (int)$res->id, 6);
                if (!$res) throw new AdminException('Thêm không thành công');
            }
        });
    }

    /**
     * Nhận thông tin chi tiết trước khi bán
     * @param int $id
     * @return array|\think\Model|null
     */
    public function getInfo(int $id)
    {
        $info = $this->dao->get($id);
        if ($info) {
            if ($info['start_time'] && $info['stop_time']) {
                $start_time = date('Y-m-d H:i', (int)$info['start_time']);
                $stop_time = date('Y-m-d H:i', (int)$info['stop_time']);
            }
            if (isset($start_time) && isset($stop_time)) {
                $info['section_time'] = [$start_time, $stop_time];
            } else {
                $info['section_time'] = [];
            }
            if ($info['pay_start_time'] && $info['pay_stop_time']) {
                $start_time = date('Y-m-d H:i', (int)$info['pay_start_time']);
                $stop_time = date('Y-m-d H:i', (int)$info['pay_stop_time']);
            }
            if (isset($start_time) && isset($stop_time)) {
                $info['pay_time'] = [$start_time, $stop_time];
            } else {
                $info['pay_time'] = [];
            }
            $info['price'] = floatval($info['price']);
            $info['ot_price'] = floatval($info['ot_price']);
            /** @var StoreDescriptionServices $storeDescriptionServices */
            $storeDescriptionServices = app()->make(StoreDescriptionServices::class);
            $info['description'] = $storeDescriptionServices->getDescription(['product_id' => $id, 'type' => 6]);
            $info['attrs'] = $this->attrList($id, $info['product_id']);
        }
        return $info;
    }

    /**
     * Nhận thông số kỹ thuật
     * @param int $id
     * @param int $pid
     * @return mixed
     */
    public function attrList(int $id, int $pid)
    {
        /** @var StoreProductAttrResultServices $storeProductAttrResultServices */
        $storeProductAttrResultServices = app()->make(StoreProductAttrResultServices::class);
        $advanceResult = $storeProductAttrResultServices->value(['product_id' => $id, 'type' => 6], 'result');
        $items = json_decode($advanceResult, true)['attr'];
        $productAttr = $this->getAttr($items, $pid, 0);
        $advanceAttr = $this->getAttr($items, $id, 6);
        foreach ($productAttr as $pk => $pv) {
            foreach ($advanceAttr as &$sv) {
                if ($pv['detail'] == $sv['detail']) {
                    $productAttr[$pk] = $sv;
                }
            }
            $productAttr[$pk]['detail'] = json_decode($productAttr[$pk]['detail']);
        }
        $attrs['items'] = $items;
        $attrs['value'] = $productAttr;
        foreach ($items as $key => $item) {
            $header[] = ['title' => $item['value'], 'key' => 'value' . ($key + 1), 'align' => 'center', 'minWidth' => 80];
        }
        $header[] = ['title' => 'hình ảnh', 'slot' => 'pic', 'align' => 'center', 'minWidth' => 120];
        $header[] = ['title' => 'giá bán trước', 'key' => 'price', 'type' => 1, 'align' => 'center', 'minWidth' => 80];
        $header[] = ['title' => 'giá thành', 'key' => 'cost', 'align' => 'center', 'minWidth' => 80];
        $header[] = ['title' => 'giá chéo', 'key' => 'ot_price', 'align' => 'center', 'minWidth' => 80];
        $header[] = ['title' => 'Trong kho', 'key' => 'stock', 'align' => 'center', 'minWidth' => 80];
        $header[] = ['title' => 'phiên bản giới hạn', 'key' => 'quota', 'type' => 1, 'align' => 'center', 'minWidth' => 80];
        $header[] = ['title' => 'cân nặng(KG)', 'key' => 'weight', 'align' => 'center', 'minWidth' => 80];
        $header[] = ['title' => 'Dung tích(m³)', 'key' => 'volume', 'align' => 'center', 'minWidth' => 80];
        $header[] = ['title' => 'Mã sản phẩm', 'key' => 'bar_code', 'align' => 'center', 'minWidth' => 80];
        $header[] = ['title' => 'mã vạch', 'key' => 'bar_code_number', 'align' => 'center', 'minWidth' => 80];
        $attrs['header'] = $header;
        return $attrs;
    }

    /**
     * Nhận thông số kỹ thuật
     * @param $attr
     * @param $id
     * @param $type
     * @return array
     */
    public function getAttr($attr, $id, $type)
    {
        /** @var StoreProductAttrValueServices $storeProductAttrValueServices */
        $storeProductAttrValueServices = app()->make(StoreProductAttrValueServices::class);
        list($value, $head) = attr_format($attr);
        $valueNew = [];
        $count = 0;
        foreach ($value as $suk) {
            $detail = explode(',', $suk);
            $sukValue = $storeProductAttrValueServices->getColumn(['product_id' => $id, 'type' => $type, 'suk' => $suk], 'bar_code,bar_code_number,cost,price,ot_price,stock,image as pic,weight,volume,brokerage,brokerage_two,quota', 'suk');
            if (count($sukValue)) {
                foreach ($detail as $k => $v) {
                    $valueNew[$count]['value' . ($k + 1)] = $v;
                }
                $valueNew[$count]['detail'] = json_encode(array_combine($head, $detail));
                $valueNew[$count]['pic'] = $sukValue[$suk]['pic'] ?? '';
                $valueNew[$count]['price'] = $sukValue[$suk]['price'] ? floatval($sukValue[$suk]['price']) : 0;
                $valueNew[$count]['cost'] = $sukValue[$suk]['cost'] ? floatval($sukValue[$suk]['cost']) : 0;
                $valueNew[$count]['ot_price'] = isset($sukValue[$suk]['ot_price']) ? floatval($sukValue[$suk]['ot_price']) : 0;
                $valueNew[$count]['stock'] = $sukValue[$suk]['stock'] ? intval($sukValue[$suk]['stock']) : 0;
                $valueNew[$count]['quota'] = $sukValue[$suk]['quota'] ? intval($sukValue[$suk]['quota']) : 0;
                $valueNew[$count]['bar_code'] = $sukValue[$suk]['bar_code'] ?? '';
                $valueNew[$count]['bar_code_number'] = $sukValue[$suk]['bar_code_number'] ?? '';
                $valueNew[$count]['weight'] = $sukValue[$suk]['weight'] ? floatval($sukValue[$suk]['weight']) : 0;
                $valueNew[$count]['volume'] = $sukValue[$suk]['volume'] ? floatval($sukValue[$suk]['volume']) : 0;
                $valueNew[$count]['brokerage'] = $sukValue[$suk]['brokerage'] ? floatval($sukValue[$suk]['brokerage']) : 0;
                $valueNew[$count]['brokerage_two'] = $sukValue[$suk]['brokerage_two'] ? floatval($sukValue[$suk]['brokerage_two']) : 0;
                $valueNew[$count]['_checked'] = $type != 0;
                $count++;
            }
        }
        return $valueNew;
    }

    /**
     * Chi tiết sản phẩm
     * @param Request $request
     * @param int $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAdvanceinfo(Request $request, int $id)
    {
        $uid = (int)$request->uid();
        $storeInfo = $this->dao->getOne(['id' => $id], '*', ['description']);
        if (!$storeInfo) {
            throw new ApiException('Sản phẩm không tồn tại');
        } else {
            $storeInfo = $storeInfo->toArray();
        }
        $siteUrl = sys_config('site_url');
        $storeInfo['image'] = set_file_url($storeInfo['image'], $siteUrl);
        $storeInfo['image_base'] = set_file_url($storeInfo['image'], $siteUrl);
        if (time() < $storeInfo['start_time']) {
            $data['pay_status'] = 1;
        } elseif (time() > $storeInfo['stop_time']) {
            $data['pay_status'] = 3;
        } else {
            $data['pay_status'] = 2;
        }
        $storeInfo['start_time'] = date('Y-m-d H:i:s', (int)$storeInfo['start_time']);
        $storeInfo['stop_time'] = date('Y-m-d H:i:s', (int)$storeInfo['stop_time']);

        /** @var StoreProductServices $storeProductService */
        $storeProductService = app()->make(StoreProductServices::class);
        $productInfo = $storeProductService->get($storeInfo['product_id']);
        $storeInfo['total'] = $productInfo['sales'] + $productInfo['ficti'];
        $storeInfo['store_name'] = $storeInfo['title'];
        $storeInfo['store_info'] = $storeInfo['info'];

        /** @var QrcodeServices $qrcodeService */
        $qrcodeService = app()->make(QrcodeServices::class);
        $storeInfo['code_base'] = $qrcodeService->getWechatQrcodePath($id . '_product_advance_detail_wap.jpg', 'pages/activity/presell_details/index?id=' . $id);

        /** @var StoreOrderServices $storeOrderServices */
        $storeOrderServices = app()->make(StoreOrderServices::class);
        $data['buy_num'] = $storeOrderServices->getBuyCount($uid, 'advance_id', $id);

        /** @var StoreProductRelationServices $storeProductRelationServices */
        $storeProductRelationServices = app()->make(StoreProductRelationServices::class);
        $storeInfo['userCollect'] = $storeProductRelationServices->isProductRelation(['uid' => $uid, 'product_id' => $storeInfo['product_id'], 'type' => 'collect', 'category' => 'product']);
        $storeInfo['userLike'] = false;
        $storeInfo['uid'] = $uid;

        if ($storeInfo['quota'] > 0) {
            $percent = (int)(($storeInfo['quota_show'] - $storeInfo['quota']) / $storeInfo['quota_show'] * 100);
            $storeInfo['percent'] = $percent;
            $storeInfo['stock'] = $storeInfo['quota'];
        } else {
            $storeInfo['percent'] = 100;
            $storeInfo['stock'] = 0;
        }
        //Chi tiết sản phẩm
        $data['storeInfo'] = get_thumb_water($storeInfo, 'big', ['image', 'images']);

        /** @var StoreProductReplyServices $storeProductReplyService */
        $storeProductReplyService = app()->make(StoreProductReplyServices::class);
        $data['reply'] = get_thumb_water($storeProductReplyService->getRecProductReply($storeInfo['product_id']), 'small', ['pics']);
        [$replyCount, $goodReply, $replyChance] = $storeProductReplyService->getProductReplyData((int)$storeInfo['product_id']);
        $data['replyChance'] = $replyChance;
        $data['replyCount'] = $replyCount;

        /** @var StoreProductAttrServices $storeProductAttrServices */
        $storeProductAttrServices = app()->make(StoreProductAttrServices::class);
        list($productAttr, $productValue) = $storeProductAttrServices->getProductAttrDetail($id, $uid, 0, 6, $storeInfo['product_id']);
        $data['productAttr'] = $productAttr;
        $data['productValue'] = $productValue;
        $data['routine_contact_type'] = sys_config('routine_contact_type', 0);
        //Sự kiện truy cập của người dùng
        event('UserVisitListener', [$uid, $id, 'advance', $storeInfo['product_id'], 'view']);
        //Lịch sử duyệt web
        ProductLogJob::dispatch(['visit', ['uid' => $uid, 'product_id' => $storeInfo['product_id']]]);
        return $data;
    }

    /**
     * Sửa đổi hàng tồn kho trước khi bán
     * @param int $num
     * @param int $advanceId
     * @return bool
     */
    public function decAdvanceStock(int $num, int $advanceId, string $unique = '')
    {
        $product_id = $this->dao->value(['id' => $advanceId], 'product_id');
        if ($unique) {
            /** @var StoreProductAttrValueServices $skuValueServices */
            $skuValueServices = app()->make(StoreProductAttrValueServices::class);
            //Tăng doanh số bán hàng bằng cách trừ đi hàng tồn kho của các mặt hàng trước khi bán
            $res = false !== $skuValueServices->decProductAttrStock($advanceId, $unique, $num, 6);
            //trừ đi hàng tồn kho trước khi bán
            $res = $res && $this->dao->decStockIncSales(['id' => $advanceId, 'type' => 6], $num);
            //Nhận bán trướcsku
            $sku = $skuValueServices->value(['product_id' => $advanceId, 'unique' => $unique, 'type' => 6], 'suk');
            //Trừ đi lượng hàng tồn kho hiện tại của mã sản phẩm phổ biến để tăng doanh số bán hàng
            $res = $res && $skuValueServices->decStockIncSales(['product_id' => $product_id, 'suk' => $sku, 'type' => 0], $num);
        } else {
            $res = false !== $this->dao->decStockIncSales(['id' => $advanceId, 'type' => 6], $num);
        }
        /** @var StoreProductServices $services */
        $services = app()->make(StoreProductServices::class);
        //trừ đi hàng tồn kho chung
        $res = $res && $services->decProductStock($num, $product_id);
        return $res;
    }

    /**
     * Giảm doanh số bán hàng và tăng hàng tồn kho
     * @param int $num
     * @param int $advanceId
     * @param string $unique
     * @return bool
     */
    public function incAdvanceStock(int $num, int $advanceId, string $unique = '')
    {
        $product_id = $this->dao->value(['id' => $advanceId], 'product_id');
        if ($unique) {
            /** @var StoreProductAttrValueServices $skuValueServices */
            $skuValueServices = app()->make(StoreProductAttrValueServices::class);
            //Trừ đi doanh số bán hàng của sản phẩm giá hời,Tăng hàng tồn kho và số lượng mua hạn chế
            $res = false !== $skuValueServices->incProductAttrStock($advanceId, $unique, $num, 6);
            //Trừ đi doanh số bán hàng giá hời,tăng hàng tồn kho
            $res = $res && $this->dao->incStockDecSales(['id' => $advanceId, 'type' => 6], $num);
            //Giảm khối lượng bán hàng của mã sản phẩm thông thường,tăng hàng tồn kho
            $suk = $skuValueServices->value(['unique' => $unique, 'product_id' => $advanceId, 'type' => 6], 'suk');
            $productUnique = $skuValueServices->value(['suk' => $suk, 'product_id' => $product_id, 'type' => 0], 'unique');
            if ($productUnique) {
                $res = $res && $skuValueServices->incProductAttrStock($product_id, $productUnique, $num);
            }
        } else {
            //Trừ đi doanh số bán hàng giá hời,tăng hàng tồn kho
            $res = false !== $this->dao->incStockDecSales(['id' => $advanceId, 'type' => 6], $num);
        }
        /** @var StoreProductServices $services */
        $services = app()->make(StoreProductServices::class);
        //Trừ đi hàng tồn kho thông thường cộng với doanh thu
        $res = $res && $services->incProductStock($num, $product_id);
        return $res;
    }

    /**
     * Xác minh giới hạn tồn kho của đơn đặt hàng trước khi bán
     * @param int $uid
     * @param int $combinationId
     * @param int $cartNum
     * @param string $unique
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function checkAdvanceStock(int $uid, int $advanceId, int $cartNum = 1, string $unique = '')
    {
        $productInfo = $this->dao->getOne(['id' => $advanceId, 'status' => 1, 'is_del' => 0], '*,title as store_name');
        if (!$productInfo) throw new ApiException('Sản phẩm đã được đưa ra khỏi kệ hoặc bị xóa');
        /** @var StoreProductAttrValueServices $attrValueServices */
        $attrValueServices = app()->make(StoreProductAttrValueServices::class);
        if ($unique == '') {
            $unique = $attrValueServices->value(['product_id' => $advanceId, 'type' => 6], 'unique');
        }
        $attrInfo = $attrValueServices->getOne(['product_id' => $advanceId, 'unique' => $unique, 'type' => 6]);
        if (!$attrInfo || $attrInfo['product_id'] != $advanceId) {
            throw new ApiException('Vui lòng chọn thuộc tính sản phẩm hợp lệ');
        }
        /** @var StoreOrderServices $orderServices */
        $orderServices = app()->make(StoreOrderServices::class);
        $userBuyCount = $orderServices->getBuyCount($uid, 'advance_id', $advanceId);
        if ($productInfo['num'] < ($userBuyCount + $cartNum)) {
            throw new ApiException('Tổng giới hạn mua hàng cho mỗi người{:num}miếng', ['num' => $productInfo['num']]);
        }
        if ($productInfo['start_time'] > time()) throw new ApiException('Sự kiện chưa bắt đầu');
        if ($productInfo['stop_time'] < time()) throw new ApiException('Sự kiện đã kết thúc');
        if ($cartNum > $attrInfo['quota']) {
            throw new ApiException('Sản phẩm này đã hết hàng{:num}', ['num' => $cartNum]);
        }
        return [$attrInfo, $unique, $productInfo];
    }
}
