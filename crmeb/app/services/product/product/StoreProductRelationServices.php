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


use app\dao\product\product\StoreProductRelationDao;
use app\services\BaseServices;
use app\jobs\ProductLogJob;
use crmeb\exceptions\ApiException;

/**
 * Class StoreProductRelationService
 * @package app\services\product\product
 */class StoreProductRelationServices extends BaseServices
{
    /**
     * StoreProductRelationServices constructor.
     * @param StoreProductRelationDao $dao
     */    public function __construct(StoreProductRelationDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Người dùng thích hay sưu tầm sản phẩm
     * @param array $where
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function isProductRelation(array $where)
    {
        $res = $this->dao->getOne($where);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Lấy số lượng bộ sưu tập của Khách hàng
     * @param int $uid
     * @return int
     */    public function getUserCollectCount(int $uid)
    {
        return $this->dao->count(['uid' => $uid, 'type' => 'collect']);
    }

    /**
     * sưu tầm
     * @param int $uid
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2023/03/01
     */    public function getUserCollectProduct(int $uid)
    {
        $where['uid'] = $uid;
        $where['type'] = 'collect';
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getList($where, 'product_id,category', $page, $limit);
        foreach ($list as $k => $product) {
            if ($product['product'] && isset($product['product']['id'])) {
                $list[$k]['pid'] = $product['product']['id'] ?? 0;
                $list[$k]['store_name'] = $product['product']['store_name'] ?? 0;
                $list[$k]['price'] = $product['product']['price'] ?? 0;
                $list[$k]['ot_price'] = $product['product']['ot_price'] ?? 0;
                $list[$k]['sales'] = $product['product']['sales'] ?? 0;
                $list[$k]['image'] = get_thumb_water($product['product']['image'] ?? 0);
                $list[$k]['is_del'] = $product['product']['is_del'] ?? 0;
                $list[$k]['is_show'] = $product['product']['is_show'] ?? 0;
                $list[$k]['is_fail'] = $product['product']['is_del'] && $product['product']['is_show'];
            } else {
                unset($list[$k]);
            }
        }
        $count = $this->dao->count($where);
        return compact('list', 'count');
    }

    /**
     * Thêm bộ sưu tập thích
     * @param int $productId
     * @param int $uid
     * @param string $relationType
     * @param string $category
     * @return bool
     */    public function productRelation(int $productId, int $uid, string $relationType, string $category = 'product')
    {
        $relationType = strtolower($relationType);
        $category = strtolower($category);
        $data = ['uid' => $uid, 'product_id' => $productId, 'type' => $relationType, 'category' => $category];
        if ($this->dao->getOne($data)) {
            return true;
        }
        $data['add_time'] = time();
        if (!$this->dao->save($data)) {
            throw new ApiException('Lưu không thành công');
        }
        //Hồ sơ thu thập
        ProductLogJob::dispatch(['collect', ['uid' => $uid, 'product_id' => $productId]]);

        //Sản phẩm yêu thích của Khách hàng sự kiện tùy chỉnh
        event('CustomEventListener', ['user_product_collect', [
            'product_id' => $productId,
            'uid' => $uid,
            'collect_time' => date('Y-m-d H:i:s'),
        ]]);

        return true;
    }

    /**
     * Hủy Thích Thu thập
     * @param array $productId
     * @param int $uid
     * @param string $relationType
     * @param string $category
     * @return bool
     * @throws \Exception
     */    public function unProductRelation(array $productId, int $uid, string $relationType, string $category = 'product')
    {
        $relationType = strtolower($relationType);
        $category = strtolower($category);
        $storeProductRelation = $this->dao->delete([
            ['uid', '=', $uid],
            ['product_id', 'in', $productId],
            ['type', '=', $relationType],
            ['category', '=', $category]
        ]);
        if (!$storeProductRelation) throw new ApiException('Hủy không thành công');
        return true;
    }

    /**
     * Bộ sưu tập thêm lượt thích hàng loạt
     * @param array $productIdS
     * @param int $uid
     * @param string $relationType
     * @param string $category
     * @return bool
     */    public function productRelationAll(array $productIdS, int $uid, string $relationType, string $category = 'product')
    {
        $relationType = strtolower($relationType);
        $category = strtolower($category);
        $relationData = [];
        $productIdS = array_unique($productIdS);
        $relationProductIdS = $this->dao->getColumn(['uid' => $uid, 'type' => $relationType, 'category' => $category, 'product_id' => $productIdS], 'product_id');
        foreach ($productIdS as $productId) {
            if (!in_array($productId, $relationProductIdS)) {
                $relationData[] = ['uid' => $uid, 'product_id' => $productId, 'type' => $relationType, 'category' => $category];
            }
        }
        if ($relationData) {
            if (!$this->dao->saveAll($relationData)) {
                throw new ApiException('Thêm không thành công');
            }
        }
        return true;
    }
}
