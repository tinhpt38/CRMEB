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


use app\dao\product\product\StoreProductLogDao;
use app\services\order\StoreOrderCartInfoServices;
use app\services\BaseServices;
use crmeb\exceptions\AdminException;

/**
 * Nhật ký truy cập sản phẩm
 * Class StoreProductLogServices
 * @package app\services\product\product
 * @method getProductTrend($time, $timeType, $str) Xu hướng hàng hóa
 */
class StoreProductLogServices extends BaseServices
{
    /**
     * StoreProductLogServices constructor.
     * @param StoreProductLogDao $dao
     */
    public function __construct(StoreProductLogDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Tạo nhật ký truy cập khác nhau
     * @param string $type
     * @param array $data
     * @return bool
     */
    public function createLog(string $type, array $data)
    {
        if (!in_array($type, ['order', 'pay', 'refund']) && (!isset($data['product_id']) || !$data['product_id'])) {
            throw new AdminException('Thiếu vật phẩmID');
        }
        if ($type != 'visit' && (!isset($data['uid']) || !$data['uid'])) {
            throw new AdminException('Thiếu người dùngUID');
        }
        $log_data = $log_data_all = [];
        $log_data['type'] = $type;
        $log_data['product_id'] = $data['product_id'] ?? 0;
        $log_data['uid'] = $data['uid'] ?? 0;
        $log_data['add_time'] = time();
        switch ($type) {
            case 'visit'://truy cập
                $log_data['visit_num'] = isset($data['visit_num']) && $data['visit_num'] ? $data['visit_num'] : 1;
                break;
            case 'cart'://thêm vào giỏ hàng
                $log_data['cart_num'] = isset($data['cart_num']) && $data['cart_num'] ? $data['cart_num'] : 1;
                break;
            case 'collect'://sưu tầm
                $log_data['collect_num'] = isset($data['collect_num']) && $data['collect_num'] ? $data['collect_num'] : 1;
                break;
            case 'order'://Đặt hàng
                if (!isset($data['order_id']) || !$data['order_id']) {
                    throw new AdminException('Thiếu đơn hàngID');
                }
                /** @var StoreOrderCartInfoServices $cartInfoServices */
                $cartInfoServices = app()->make(StoreOrderCartInfoServices::class);
                $cartInfo = $cartInfoServices->getOrderCartInfo($data['order_id']);
                foreach ($cartInfo as $value) {
                    $product = $value['cart_info'];
                    $log_data['product_id'] = $product['product_id'] ?? 0;
                    $log_data['order_num'] = $product['cart_num'] ?? 1;
                    $log_data_all[] = $log_data;
                }
                break;
            case 'pay'://chi trả
                if (!isset($data['order_id']) || !$data['order_id']) {
                    throw new AdminException('Thiếu đơn hàngID');
                }
                /** @var StoreOrderCartInfoServices $cartInfoServices */
                $cartInfoServices = app()->make(StoreOrderCartInfoServices::class);
                $cartInfo = $cartInfoServices->getOrderCartInfo($data['order_id']);
                foreach ($cartInfo as $value) {
                    $product = $value['cart_info'];
                    $log_data['product_id'] = $product['product_id'] ?? 0;
                    $log_data['pay_num'] = $product['cart_num'] ?? 0;
                    $log_data['cost_price'] = $product['costPrice'] ?? 0;
                    $log_data['pay_price'] = $product['truePrice'] ?? 0;
                    $log_data['pay_uid'] = $data['uid'] ?? 0;
                    $log_data_all[] = $log_data;
                }
                break;
            case 'refund'://Đền bù
                if (!isset($data['order_id']) || !$data['order_id']) {
                    throw new AdminException('Thiếu đơn hàngID');
                }
                /** @var StoreOrderCartInfoServices $cartInfoServices */
                $cartInfoServices = app()->make(StoreOrderCartInfoServices::class);
                $cartInfo = $cartInfoServices->getOrderCartInfo($data['order_id']);
                foreach ($cartInfo as $value) {
                    $product = $value['cart_info'];
                    $log_data['product_id'] = $product['product_id'] ?? 0;
                    $log_data['uid'] = $data['uid'] ?? 0;
                    $log_data['pay_uid'] = $data['uid'] ?? 0;
                    $log_data['refund_num'] = $product['cart_num'] ?? 0;
                    $log_data['refund_price'] = $product['truePrice'] ?? 0;
                    $log_data_all[] = $log_data;
                }
                break;
            default:
                throw new AdminException('Loại bản ghi này chưa được hỗ trợ');
        }
        if ($log_data_all) {
            $res = $this->dao->saveAll($log_data_all);
        } else {
            $res = $this->dao->save($log_data);
        }
        if (!$res) {
            throw new AdminException('Không thể thêm bản ghi sản phẩm');
        }
        return true;
    }

    /**
     * Tìm thứ hạng của sản phẩm đã mua
     * @param $where
     * @return mixed
     */
    public function getRanking(array $where)
    {
        $list = $this->dao->getRanking($where);
        foreach ($list as &$item) {
            if ($item['profit'] == null) $item['profit'] = 0;
            if ($item['changes'] == null) $item['changes'] = 0;
            if ($item['repeats'] == null) {
                $item['repeats'] = 0;
            } else {
                $item['repeats'] = bcdiv($this->dao->getRepeats($where, $item['product_id']), $item['repeats'], 2);
            }
        }
        return $list;
    }

    /**
     * Duyệt danh sách sản phẩm
     * @param array $where
     * @param string $group
     * @param string $field
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList(array $where, string $group = '', string $field = '*')
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getList($where, $field, $page, $limit, $group);
        if ($group) {
            $count = $this->dao->getDistinctCount($where, $group, true);
        } else {
            $count = $this->dao->count($where);
        }
        return compact('list', 'count');
    }
}
