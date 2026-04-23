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

namespace app\services\order;

use app\services\activity\advance\StoreAdvanceServices;
use app\services\BaseServices;
use app\dao\order\StoreCartDao;
use app\services\activity\coupon\StoreCouponIssueServices;
use app\services\product\shipping\ShippingTemplatesServices;
use app\services\shipping\ShippingTemplatesNoDeliveryServices;
use app\services\system\SystemUserLevelServices;
use app\services\user\member\MemberCardServices;
use app\services\user\UserServices;
use app\jobs\ProductLogJob;
use crmeb\exceptions\ApiException;
use crmeb\services\CacheService;
use app\services\activity\seckill\StoreSeckillServices;
use app\services\activity\bargain\StoreBargainServices;
use app\services\activity\combination\StoreCombinationServices;
use app\services\product\product\StoreProductServices;
use app\services\product\sku\StoreProductAttrValueServices;

/**
 *
 * Class StoreCartServices
 * @package app\services\order
 * @method updateCartStatus($cartIds) Sửa đổi trạng thái giỏ hàng
 * @method getUserCartNum(int $uid, string $type, int $numType) Số lượng giỏ hàng
 * @method deleteCartStatus(array $cartIds) Sửa đổi trạng thái giỏ hàng
 * @method array productIdByCartNum(array $ids, int $uid)  Lấy số lượng giỏ hàng dựa trên id sản phẩm
 * @method getCartList(array $where, ?int $page = 0, ?int $limit = 0, ?array $with = []) Nhận giỏ hàng của người dùng
 * @method getSum($where, $field) Tổng
 * @method getProductTrend($time, $timeType, $str) xu hướng giỏ hàng
 */
class StoreCartServices extends BaseServices
{

    /**
     * StoreCartServices constructor.
     * @param StoreCartDao $dao
     */
    public function __construct(StoreCartDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Lấy số lượng giỏ hàng được người dùng đặt
     * @param array $unique
     * @param int $productId
     * @param int $uid
     * @return array
     */
    public function getUserCartNums(array $unique, int $productId, int $uid)
    {
        $where['is_pay'] = 0;
        $where['is_del'] = 0;
        $where['is_new'] = 0;
        $where['product_id'] = $productId;
        $where['uid'] = $uid;
        return $this->dao->getUserCartNums($where, $unique);
    }

    /**
     * Lấy danh sách giỏ hàng của người dùng
     * @param $uid
     * @param string $cartIds
     * @param bool $new
     * @param array $addr
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getUserProductCartListV1($uid, $cartIds = '', bool $new, $addr = [], int $shipping_type = 1, $is_gift = 0)
    {
        if ($new) {
            $cartIds = explode(',', $cartIds);
            $cartInfo = [];
            foreach ($cartIds as $key) {
                $info = CacheService::get($key);
                if ($info) {
                    $cartInfo[] = $info;
                }
            }
        } else {
            $cartInfo = $this->dao->getCartList(['uid' => $uid, 'status' => 1, 'id' => $cartIds], 0, 0, ['productInfo', 'attrInfo']);
        }
        if (!$cartInfo) {
            throw new ApiException('Không lấy được thông tin giỏ hàng');
        }
        if ($is_gift == 1) {
            $addr = [];
            $shipping_type = 0;
        }
        [$cartInfo, $valid, $invalid] = $this->handleCartList($uid, $cartInfo, $addr, $shipping_type);
        $seckillIds = array_unique(array_column($cartInfo, 'seckill_id'));
        $bargainIds = array_unique(array_column($cartInfo, 'bargain_id'));
        $combinationId = array_unique(array_column($cartInfo, 'combination_id'));
        $advanceId = array_unique(array_column($cartInfo, 'advance_id'));
        $deduction = ['seckill_id' => $seckillIds[0] ?? 0, 'bargain_id' => $bargainIds[0] ?? 0, 'combination_id' => $combinationId[0] ?? 0, 'advance_id' => $advanceId[0] ?? 0];
        return ['cartInfo' => $cartInfo, 'valid' => $valid, 'invalid' => $invalid, 'deduction' => $deduction];
    }

    /**
     * Tạo đơn hàng bằng thuật toán bông tuyếtID
     * @return string
     * @throws \Exception
     */
    public function getCartId($prefix)
    {
        $snowflake = new \Godruoyi\Snowflake\Snowflake();
        //32Chút
        if (PHP_INT_SIZE == 4) {
            $id = abs((int)$snowflake->id());
        } else {
            $id = $snowflake->setStartTimeStamp(strtotime('2020-06-05') * 1000)->id();
        }
        return $prefix . $id;
    }

    /**
     * Xác minh hàng tồn kho
     * @param int $uid
     * @param int $cartNum
     * @param string $unique
     * @param int $type
     * @param $productId
     * @param int $seckillId
     * @param int $bargainId
     * @param int $combinationId
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function checkProductStock(int $uid, int $cartNum, string $unique, int $type = 0, $productId, int $seckillId, int $bargainId, int $combinationId, int $advanceId)
    {
        /** @var StoreProductAttrValueServices $attrValueServices */
        $attrValueServices = app()->make(StoreProductAttrValueServices::class);
        switch ($type) {
            case 0://bình thường
                if ($unique == '') {
                    $unique = $attrValueServices->value(['product_id' => $productId, 'type' => 0], 'unique');
                }
                /** @var StoreProductServices $productServices */
                $productServices = app()->make(StoreProductServices::class);
                $productInfo = $productServices->isValidProduct($productId);
                if (!$productInfo) {
                    throw new ApiException('Sản phẩm này đã bị gỡ bỏ khỏi kệ hoặc bị xóa');
                }
                $attrInfo = $attrValueServices->getOne(['unique' => $unique, 'type' => 0]);
                if (!$unique || !$attrInfo || $attrInfo['product_id'] != $productId) {
                    throw new ApiException('Vui lòng chọn thuộc tính sản phẩm hợp lệ');
                }
                $nowStock = $attrInfo['stock'];//Hàng tồn kho trong tay
                if ($cartNum > $nowStock) {
                    throw new ApiException('Sản phẩm này đã hết hàng{:num}', ['num' => $cartNum]);
                }
                if ($productInfo['is_virtual'] == 1 && $productInfo['virtual_type'] == 2 && $attrInfo['coupon_id']) {
                    /** @var StoreCouponIssueServices $issueCoupon */
                    $issueCoupon = app()->make(StoreCouponIssueServices::class);
                    if (!$issueCoupon->getCount(['id' => $attrInfo['coupon_id'], 'status' => 1, 'is_del' => 0])) {
                        throw new ApiException('Phiếu giảm giá bạn muốn mua đã hết hạn và không thể mua được');
                    }
                }
                $stockNum = $this->dao->value(['product_id' => $productId, 'product_attr_unique' => $unique, 'uid' => $uid, 'status' => 1], 'cart_num') ?: 0;
                if ($nowStock < ($cartNum + $stockNum)) {
                    $surplusStock = $nowStock - $cartNum;//hàng còn lại
                    if ($surplusStock < $stockNum) {
                        $this->dao->update(['product_id' => $productId, 'product_attr_unique' => $unique, 'uid' => $uid, 'status' => 1], ['cart_num' => $surplusStock]);
                    }
                }
                break;
            case 1://bán chớp nhoáng
                /** @var StoreSeckillServices $seckillService */
                $seckillService = app()->make(StoreSeckillServices::class);
                [$attrInfo, $unique, $productInfo] = $seckillService->checkSeckillStock($uid, $seckillId, $cartNum, $unique);
                break;
            case 2://Mặc cả
                /** @var StoreBargainServices $bargainService */
                $bargainService = app()->make(StoreBargainServices::class);
                [$attrInfo, $unique, $productInfo, $bargainUserInfo] = $bargainService->checkBargainStock($uid, $bargainId, $cartNum, $unique);
                break;
            case 3://Chia sẻ nhóm
                /** @var StoreCombinationServices $combinationService */
                $combinationService = app()->make(StoreCombinationServices::class);
                [$attrInfo, $unique, $productInfo] = $combinationService->checkCombinationStock($uid, $combinationId, $cartNum, $unique);
                break;
            case 6://Bán trước
                /** @var StoreAdvanceServices $advanceService */
                $advanceService = app()->make(StoreAdvanceServices::class);
                [$attrInfo, $unique, $productInfo] = $advanceService->checkAdvanceStock($uid, $advanceId, $cartNum, $unique);
                break;
            default:
                throw new ApiException('Vui lòng làm mới và thử lại');
        }
        if ($type && $type != 6) {
            //Kiểm kê thông số kỹ thuật sản phẩm gốc
            $product_stock = $attrValueServices->value(['product_id' => $productInfo['product_id'], 'suk' => $attrInfo['suk'], 'type' => 0], 'stock');
            if ($product_stock < $cartNum) {
                throw new ApiException('Sản phẩm này đã hết hàng{:num}', ['num' => $cartNum]);
            }
        }
        return [$attrInfo, $unique, $bargainUserInfo['bargain_price_min'] ?? 0, $cartNum, $productInfo];
    }

    /**
     * Thêm vào giỏ hàng
     * @param int $uid người dùngUID
     * @param int $product_id hàng hóaID
     * @param int $cart_num số lượng sản phẩm
     * @param string $product_attr_unique hàng hóaSKU
     * @param string $type Thêm loại giỏ hàng
     * @param bool $new true = Mua nó ngay bây giờ，false = thêm vào giỏ hàng
     * @param int $combination_id Nhóm sản phẩmID
     * @param int $seckill_id mặt hàng flash saleID
     * @param int $bargain_id mặt hàng giá hờiID
     * @return mixed|string
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function setCart(int $uid, int $product_id, int $cart_num = 1, string $product_attr_unique = '', int $type = 0, bool $new = true, int $combination_id = 0, int $seckill_id = 0, int $bargain_id = 0, int $advance_id = 0)
    {
        if ($cart_num < 1) $cart_num = 1;
        if ($type == 0) {
            //Kiểm tra giới hạn mua hàng
            $this->checkLimit($uid, $product_id, $cart_num, $new);
        }
        //Kiểm tra giới hạn hàng tồn kho
        [$attrInfo, $product_attr_unique, $bargainPriceMin, $cart_num, $productInfo] = $this->checkProductStock($uid, $cart_num, $product_attr_unique, $type, $product_id, $seckill_id, $bargain_id, $combination_id, $advance_id);
        if ($new) {
            /** @var StoreOrderCreateServices $storeOrderCreateService */
            $storeOrderCreateService = app()->make(StoreOrderCreateServices::class);
            $key = $storeOrderCreateService->getNewOrderId((string)$uid);
            $info['id'] = $key;
            $info['type'] = $type;
            $info['seckill_id'] = $seckill_id;
            $info['bargain_id'] = $bargain_id;
            $info['combination_id'] = $combination_id;
            $info['advance_id'] = $advance_id;
            $info['product_id'] = $product_id;
            $info['product_attr_unique'] = $product_attr_unique;
            $info['cart_num'] = $cart_num;
            $info['productInfo'] = $productInfo ? $productInfo->toArray() : [];
            $info['productInfo']['attrInfo'] = $attrInfo->toArray();
            $info['attrInfo'] = $attrInfo->toArray();
            $info['sum_price'] = $info['productInfo']['attrInfo']['price'];
            //Mặc cả
            if ($bargain_id) {
                $info['truePrice'] = $bargainPriceMin;
                $info['productInfo']['attrInfo']['price'] = $bargainPriceMin;
            } else {
                $info['truePrice'] = $info['productInfo']['attrInfo']['price'] ?? $info['productInfo']['price'] ?? 0;
            }
            //Thương lượng nhóm, flash sale, không có giá thành viên
            if ($bargain_id || $combination_id || $seckill_id || $advance_id) {
                $info['truePrice'] = $info['productInfo']['attrInfo']['price'] ?? 0;
                $info['vip_truePrice'] = 0;
            }
            $info['trueStock'] = $info['productInfo']['attrInfo']['stock'];
            $info['costPrice'] = $info['productInfo']['attrInfo']['cost'];
            try {
                CacheService::set($key, $info, 3600);
            } catch (\Throwable $e) {
                throw new ApiException($e->getMessage());
            }
            return $key;
        } else {//Thêm vào giỏ hàng
            ProductLogJob::dispatch(['cart', ['uid' => $uid, 'product_id' => $product_id, 'cart_num' => $cart_num]]);
            $cart = $this->dao->getOne(['type' => $type, 'uid' => $uid, 'product_id' => $product_id, 'product_attr_unique' => $product_attr_unique, 'is_del' => 0, 'is_new' => 0, 'is_pay' => 0, 'status' => 1]);

            //Sự kiện tùy chỉnh-Thêm vào giỏ hàng
            event('CustomEventListener', ['user_add_cart', [
                'product_id' => $product_id,
                'uid' => $uid,
                'cart_num' => $cart_num,
                'add_time' => date('Y-m-d H:i:s'),
            ]]);

            if ($cart) {
                $cart->cart_num = $cart_num + $cart->cart_num;
                $cart->add_time = time();
                $cart->save();
                return $cart->id;
            } else {
                $add_time = time();
                return $this->dao->save(compact('uid', 'product_id', 'cart_num', 'product_attr_unique', 'type', 'add_time'))->id;
            }
        }
    }

    /**Xóa các mặt hàng khỏi giỏ hàng
     * @param int $uid
     * @param array $ids
     * @return StoreCartDao|bool
     */
    public function removeUserCart(int $uid, array $ids)
    {
        if (!$uid || !$ids) return false;
        return $this->dao->removeUserCart($uid, $ids);
    }

    /**Giỏ hàng Sửa đổi số lượng sản phẩm
     * @param $id
     * @param $number
     * @param $uid
     * @return bool|\crmeb\basic\BaseModel
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function changeUserCartNum($id, $number, $uid)
    {
        if (!$id || !$number || !$uid) return false;
        $where = ['uid' => $uid, 'id' => $id];
        $carInfo = $this->dao->getOne($where, 'product_id,combination_id,seckill_id,bargain_id,product_attr_unique,cart_num');

        //Kiểm tra số lượng sửa đổi giỏ hàng mua hàng giới hạn mua hàng
        /** @var StoreProductServices $productServices */
        $productServices = app()->make(StoreProductServices::class);
        $limitInfo = $productServices->get($carInfo->product_id, ['is_limit', 'limit_type', 'limit_num', 'min_qty']);
        if ($number < $limitInfo['min_qty']) {
            throw new ApiException('Không thể ít hơn số lượng mua tối thiểu');
        }
        if ($limitInfo['is_limit']) {
            $num = $this->dao->sum([['uid', '=', $uid], ['product_id', '=', $carInfo->product_id], ['id', '<>', $id]], 'cart_num') + $number;
            if ($limitInfo['limit_type'] == 1 && $num > $limitInfo['limit_num']) {
                throw new ApiException('Số lượng mua một lần không được lớn hơn {:limit} miếng', ['limit' => $limitInfo['limit_num']]);
            } else if ($limitInfo['limit_type'] == 2) {
                /** @var StoreOrderCartInfoServices $orderCartServices */
                $orderCartServices = app()->make(StoreOrderCartInfoServices::class);
                $orderPayNum = $orderCartServices->sum(['uid' => $uid, 'product_id' => $carInfo->product_id], 'cart_num');
                $orderRefundNum = $orderCartServices->sum(['uid' => $uid, 'product_id' => $carInfo->product_id], 'refund_num');
                $orderNum = $orderPayNum - $orderRefundNum;
                if (($num + $orderNum) > $limitInfo['limit_num']) {
                    throw new ApiException('Sản phẩm này được giới hạn mua {:limit} mặt hàng bạn đã mua {:pay_num} miếng', ['limit' => $limitInfo['limit_num'], 'pay_num' => $orderNum]);
                }
            }
        }

        $stock = $productServices->getProductStock($carInfo->product_id, $carInfo->product_attr_unique);
        if (!$stock) throw new ApiException('Hết hàng');
        if ($stock < $number) throw new ApiException('Sản phẩm này đã hết hàng{:num}', ['num' => $number]);
        if ($carInfo->cart_num == $number) return true;
        return $this->dao->changeUserCartNum(['uid' => $uid, 'id' => $id], (int)$number);
    }

    /**
     * Sửa đổi trạng thái giỏ hàng
     * @param int $productId
     * @param int $status 0 Sản phẩm bị loại khỏi kệ
     */
    public function changeStatus(int $productId, $status = 0)
    {
        $this->dao->update($productId, ['status' => $status], 'product_id');
    }

    /**
     * Nhận danh sách giỏ hàng
     * @param int $uid
     * @param int $status
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getUserCartList(int $uid, int $status, string $cartIds = '')
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getCartList(['uid' => $uid, 'status' => $status, 'id' => $cartIds], $page, $limit, ['productInfo', 'attrInfo' => function ($query) {
            $query->where('type', 0);
        }]);
        [$list, $valid, $invalid] = $this->handleCartList($uid, $list);
        $seckillIds = array_unique(array_column($list, 'seckill_id'));
        $bargainIds = array_unique(array_column($list, 'bargain_id'));
        $combinationId = array_unique(array_column($list, 'combination_id'));
        $discountId = array_unique(array_column($list, 'discount_id'));
        $deduction = ['seckill_id' => $seckillIds[0] ?? 0, 'bargain_id' => $bargainIds[0] ?? 0, 'combination_id' => $combinationId[0] ?? 0, 'discount_id' => $discountId[0] ?? 0];
        if ($status == 1) {
            return ['valid' => $list, 'invalid' => [], 'deduction' => $deduction];
        } else {
            return ['valid' => [], 'invalid' => $list, 'deduction' => $deduction];
        }
    }

    /**
     * Lựa chọn lại giỏ hàng
     * @param int $cart_id
     * @param int $product_id
     * @param string $unique
     */
    public function modifyCart(int $cart_id, int $product_id, string $unique)
    {
        /** @var StoreProductAttrValueServices $attrService */
        $attrService = app()->make(StoreProductAttrValueServices::class);
        $stock = $attrService->value(['product_id' => $product_id, 'unique' => $unique, 'type' => 0], 'stock');
        if ($stock > 0) {
            $this->dao->update($cart_id, ['product_attr_unique' => $unique, 'cart_num' => 1]);
        } else {
            throw new ApiException('Thông số kỹ thuật đã chọn đã hết hàng.');
        }
    }

    /**
     * Chọn lại giỏ hàng
     * @param $id
     * @param $uid
     * @param $productId
     * @param $unique
     * @param $num
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function resetCart($id, $uid, $productId, $unique, $num)
    {
        $res = $this->dao->getOne(['uid' => $uid, 'product_id' => $productId, 'product_attr_unique' => $unique]);
        if ($res) {
            $res->cart_num = $res->cart_num + $num;
            $res->save();
            $this->dao->delete($id);
        } else {
            $this->dao->update($id, ['product_attr_unique' => $unique, 'cart_num' => $num]);
        }
    }

    /**
     * Trang chủThêm vào giỏ hàng
     * @param $uid
     * @param $productId
     * @param $num
     * @param $unique
     * @param $type
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function setCartNum($uid, $productId, $num, $unique, $type)
    {
        if ($type == 1) {
            //Kiểm tra giới hạn mua hàng
            $this->checkLimit($uid, $productId, $num, 0);
        }

        /** @var StoreProductAttrValueServices $attrValueServices */
        $attrValueServices = app()->make(StoreProductAttrValueServices::class);

        if ($unique == '') {
            $unique = $attrValueServices->value(['product_id' => $productId, 'type' => 0], 'unique');
        }
        /** @var StoreProductServices $productServices */
        $productServices = app()->make(StoreProductServices::class);

        if (!$productServices->isValidProduct((int)$productId, 'id')) {
            throw new ApiException('Sản phẩm này đã bị gỡ bỏ khỏi kệ hoặc bị xóa');
        }
        if (!($unique && $attrValueServices->getAttrvalueCount($productId, $unique, 0))) {
            throw new ApiException('Vui lòng chọn thuộc tính sản phẩm hợp lệ');
        }
        if ($productServices->getProductStock((int)$productId, $unique) < $num) {
            throw new ApiException('Sản phẩm này đã hết hàng{:num}', ['num' => $num]);
        }

        $cart = $this->dao->getOne(['uid' => $uid, 'product_id' => $productId, 'product_attr_unique' => $unique]);
        $min_qty = $productServices->value(['id' => $productId], 'min_qty');
        if ($cart) {
            if ($type == -1) {
                $cart->cart_num = $num;
            } elseif ($type == 0) {
                $cart->cart_num = $cart->cart_num - $num;
                if ($cart->cart_num < $min_qty) {
                    return $this->dao->delete($cart->id);
                }
            } elseif ($type == 1) {
                $cart->cart_num = $cart->cart_num + $num;
            }
            if ($cart->cart_num === 0) {
                return $this->dao->delete($cart->id);
            } else {
                $cart->add_time = time();
                $cart->save();
                return $cart->id;
            }
        } else {

            $data = [
                'uid' => $uid,
                'product_id' => $productId,
                'cart_num' => $num > $min_qty ? $num : $min_qty,
                'product_attr_unique' => $unique,
                'type' => 0,
                'add_time' => time()
            ];
            return $this->dao->save($data)->id;
        }
    }

    /**
     * Nhận id số giỏ hàng của người dùng và số lượng thống kê
     * @param int $uid
     * @param string $numType
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getUserCartCount(int $uid, string $numType)
    {
        $count = 0;
        $ids = [];
        $sum_price = 0;
        $cartList = $this->dao->getUserCartList($uid, '*', ['productInfo', 'attrInfo']);
        if ($cartList) {
            /** @var StoreProductServices $productServices */
            $productServices = app()->make(StoreProductServices::class);
            /** @var MemberCardServices $memberCardService */
            $memberCardService = app()->make(MemberCardServices::class);
            $vipStatus = $memberCardService->isOpenMemberCard('vip_price', false);
            /** @var UserServices $user */
            $user = app()->make(UserServices::class);
            $userInfo = $user->getUserInfo($uid);
            $discount = 100;
            if (sys_config('member_func_status', 1)) {
                /** @var SystemUserLevelServices $systemLevel */
                $systemLevel = app()->make(SystemUserLevelServices::class);
                $discount = $systemLevel->value(['id' => $userInfo['level'], 'is_del' => 0, 'is_show' => 1], 'discount') ?: 100;
            }
            foreach ($cartList as &$item) {
                $productInfo = $item['productInfo'];
                if (isset($productInfo['attrInfo']['product_id']) && $item['product_attr_unique']) {
                    [$truePrice, $vip_truePrice, $type] = $productServices->setLevelPrice($productInfo['attrInfo']['price'] ?? 0, $uid, $userInfo, $vipStatus, $discount, $productInfo['attrInfo']['vip_price'] ?? 0, $productInfo['is_vip'] ?? 0, true);
                    $item['truePrice'] = $truePrice;
                    $item['price_type'] = $type;
                } else {
                    [$truePrice, $vip_truePrice, $type] = $productServices->setLevelPrice($item['productInfo']['price'] ?? 0, $uid, $userInfo, $vipStatus, $discount, $item['productInfo']['vip_price'] ?? 0, $item['productInfo']['is_vip'] ?? 0, true);
                    $item['truePrice'] = $truePrice;
                    $item['price_type'] = $type;
                }
                $sum_price = bcadd((string)$sum_price, (string)bcmul((string)$item['cart_num'], (string)$item['truePrice'], 4), 2);
            }
            $ids = array_column($cartList, 'id');
            if ($numType) {
                $count = count($cartList);
            } else {
                $count = array_sum(array_column($cartList, 'cart_num'));
            }
        }
        return compact('count', 'ids', 'sum_price');
    }

    /**
     * Xử lý dữ liệu giỏ hàng
     * @param int $uid
     * @param array $cartList
     * @param array $addr
     * @param int $shipping_type
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2023/02/16
     */
    public function handleCartList(int $uid, array $cartList, array $addr = [], int $shipping_type = 1)
    {
        if (!$cartList) return [$cartList, [], []];
        $tempIds = [];
        $userInfo = [];
        $discount = 100;
        if ($uid) {
            /** @var UserServices $user */
            $user = app()->make(UserServices::class);
            $userInfo = $user->getUserInfo($uid);
            //Cấp độ người dùng có được bật không?
            if (sys_config('member_func_status', 1)) {
                /** @var SystemUserLevelServices $systemLevel */
                $systemLevel = app()->make(SystemUserLevelServices::class);
                $discount = $systemLevel->value(['id' => $userInfo['level'], 'is_del' => 0, 'is_show' => 1], 'discount') ?: 100;
            }
        }

        //Cho dù tư cách thành viên trả phí có được bật hay không và người dùng có phải là thành viên trả phí hay không, nếu cả hai đều đáp ứng, số tiền tính đơn hàng sẽ được tính theo tư cách thành viên trả phí.。
        /** @var MemberCardServices $memberCardService */
        $memberCardService = app()->make(MemberCardServices::class);
        $vipStatus = $memberCardService->isOpenMemberCard('vip_price', false) && $userInfo['is_money_level'] > 0;

        //Mẫu vận chuyển hàng hóa không giao hàng
        if ($shipping_type == 1 && $addr) {
            $cityId = (int)($addr['city_id'] ?? 0);
            if ($cityId) {
                foreach ($cartList as $item) {
                    $tempIds[] = $item['productInfo']['temp_id'];
                }
                $tempIds = array_unique($tempIds);
                $shippingService = app()->make(\app\services\shipping\ShippingTemplatesServices::class);
                $tempIds = $shippingService->getColumn([['id', 'in', $tempIds], ['no_delivery', '=', 1]], 'id');
                if ($tempIds) {
                    /** @var ShippingTemplatesNoDeliveryServices $noDeliveryServices */
                    $noDeliveryServices = app()->make(ShippingTemplatesNoDeliveryServices::class);
                    $tempIds = $noDeliveryServices->isNoDelivery(array_unique($tempIds), $cityId);
                }
            }
        }

        /** @var StoreProductServices $productServices */
        $productServices = app()->make(StoreProductServices::class);
        $valid = $invalid = [];
        foreach ($cartList as &$item) {
            if ($item['type'] == 0) $item['min_qty'] = $item['productInfo']['min_qty'];
            $item['productInfo']['express_delivery'] = false;
            $item['productInfo']['store_mention'] = false;
            if (isset($item['productInfo']['logistics'])) {
                if (in_array(1, explode(',', $item['productInfo']['logistics']))) {
                    $item['productInfo']['express_delivery'] = true;
                }
                if (in_array(2, explode(',', $item['productInfo']['logistics']))) {
                    $item['productInfo']['store_mention'] = true;
                }
            }
            if (isset($item['attrInfo']) && $item['attrInfo'] && (!isset($item['productInfo']['attrInfo']) || !$item['productInfo']['attrInfo'])) {
                $item['productInfo']['attrInfo'] = $item['attrInfo'] ?? [];
            }
            $item['attrStatus'] = isset($item['productInfo']['attrInfo']['stock']) && $item['productInfo']['attrInfo']['stock'];
            $item['productInfo']['attrInfo']['image'] = $item['productInfo']['attrInfo']['image'] ?? $item['productInfo']['image'] ?? '';
            $item['productInfo']['attrInfo']['suk'] = $item['productInfo']['attrInfo']['suk'] ?? 'Hết hạn';
            if (isset($item['productInfo']['attrInfo'])) {
                $item['productInfo']['attrInfo'] = get_thumb_water($item['productInfo']['attrInfo']);
            }
            $item['productInfo'] = get_thumb_water($item['productInfo']);
            $productInfo = $item['productInfo'];
            $item['vip_truePrice'] = 0;
            $is_activity = $item['seckill_id'] || $item['bargain_id'] || $item['combination_id'] || $item['advance_id'];
            if (isset($productInfo['attrInfo']['product_id']) && $item['product_attr_unique']) {
                $item['costPrice'] = $productInfo['attrInfo']['cost'] ?? 0;
                $item['trueStock'] = $productInfo['attrInfo']['stock'] ?? 0;
                $item['truePrice'] = $productInfo['attrInfo']['price'] ?? 0;
                $item['sum_price'] = $productInfo['attrInfo']['price'] ?? 0;
                if (!$is_activity) {
                    [$truePrice, $vip_truePrice, $type] = $productServices->setLevelPrice($productInfo['attrInfo']['price'] ?? 0, $uid, $userInfo, $vipStatus, $discount, $productInfo['attrInfo']['vip_price'] ?? 0, $productInfo['is_vip'] ?? 0, true);
                    $item['truePrice'] = $truePrice;
                    $item['vip_truePrice'] = $vip_truePrice;
                    $item['price_type'] = $type;
                } else {
                    $item['price_type'] = 'activity';
                }
            } else {
                $item['costPrice'] = $item['productInfo']['cost'] ?? 0;
                $item['trueStock'] = $item['productInfo']['stock'] ?? 0;
                $item['truePrice'] = $item['productInfo']['price'] ?? 0;
                $item['sum_price'] = $item['productInfo']['price'] ?? 0;
                if (!$is_activity) {
                    [$truePrice, $vip_truePrice, $type] = $productServices->setLevelPrice($item['productInfo']['price'] ?? 0, $uid, $userInfo, $vipStatus, $discount, $item['productInfo']['vip_price'] ?? 0, $item['productInfo']['is_vip'] ?? 0, true);
                    $item['truePrice'] = $truePrice;
                    $item['vip_truePrice'] = $vip_truePrice;
                    $item['price_type'] = $type;
                } else {
                    $item['price_type'] = 'activity';
                }
            }
            if (isset($item['status']) && $item['status'] == 0) {
                $item['is_valid'] = 0;
                $invalid[] = $item;
            } else {
                switch ($shipping_type) {
                    case 1:
                        //Chưa giao
                        if (in_array($item['productInfo']['temp_id'], $tempIds) || (isset($item['productInfo']['logistics']) && !in_array(1, explode(',', $item['productInfo']['logistics'])) && $item['productInfo']['logistics'] != 0)) {
                            $item['is_valid'] = 0;
                            $invalid[] = $item;
                        } else {
                            $item['is_valid'] = 1;
                            $valid[] = $item;
                        }
                        break;
                    case 2:
                        //Nhận hàng tại cửa hàng không được hỗ trợ
                        if (isset($item['productInfo']['logistics']) && $item['productInfo']['logistics'] && !in_array(2, explode(',', $item['productInfo']['logistics'])) && $item['productInfo']['logistics'] != 0) {
                            $item['is_valid'] = 0;
                            $invalid[] = $item;
                        } else {
                            $item['is_valid'] = 1;
                            $valid[] = $item;
                        }
                        break;
                    default:
                        $item['is_valid'] = 1;
                        $valid[] = $item;
                        break;
                }
            }
            unset($item['attrInfo']);
        }
        return [$cartList, $valid, $invalid];
    }

    /**
     * Kiểm tra giới hạn mua hàng
     * @param $uid
     * @param $product_id
     * @param $num
     * @param $new
     * @return bool
     * @throws \ReflectionException
     */
    public function checkLimit($uid, $product_id, $num, $new)
    {
        /** @var StoreProductServices $productServices */
        $productServices = app()->make(StoreProductServices::class);
        /** @var StoreOrderCartInfoServices $orderCartServices */
        $orderCartServices = app()->make(StoreOrderCartInfoServices::class);

        $limitInfo = $productServices->get($product_id, ['is_limit', 'limit_type', 'limit_num']);
        if (!$limitInfo) throw new ApiException('Sản phẩm không tồn tại');
        $limitInfo = $limitInfo->toArray();
        if (!$limitInfo['is_limit']) return true;
        if ($limitInfo['limit_type'] == 1) {
            $cartNum = 0;
            if (!$new) {
                $cartNum = $this->dao->sum(['uid' => $uid, 'product_id' => $product_id], 'cart_num');
            }
            if (($num + $cartNum) > $limitInfo['limit_num']) {
                throw new ApiException('Số lượng mua một lần không được lớn hơn {:limit} miếng', ['limit' => $limitInfo['limit_num']]);
            }
        } else if ($limitInfo['limit_type'] == 2) {
            $cartNum = $this->dao->sum(['uid' => $uid, 'product_id' => $product_id], 'cart_num');
            $orderPayNum = $orderCartServices->sum(['uid' => $uid, 'product_id' => $product_id, 'split_status' => 0], 'cart_num');
            $orderRefundNum = $orderCartServices->sum(['uid' => $uid, 'product_id' => $product_id, 'split_status' => 0], 'refund_num');
            $orderNum = $orderPayNum - $orderRefundNum;
            if (($num + $orderNum + $cartNum) > $limitInfo['limit_num']) {
                throw new ApiException('Sản phẩm này được giới hạn mua {:limit} mặt hàng bạn đã mua {:pay_num} miếng', ['limit' => $limitInfo['limit_num'], 'pay_num' => $orderNum]);
            }
        }
        return true;
    }

    /**
     * Xác định xem thành viên không trả tiền có mua sản phẩm dành riêng cho thành viên hay không
     * @param $user
     * @param $pid
     * @return bool
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/10/30
     */
    public function checkVipGoodsBuy($user, $pid)
    {
        $is_vip_product = app()->make(StoreProductServices::class)->value(['id' => $pid], 'vip_product');
        if ($is_vip_product == 1 && $user['is_money_level'] == 0) throw new ApiException('Sản phẩm này chỉ dành riêng cho thành viên trả phí và bạn không có quyền mua nó.');
        return true;
    }
}
