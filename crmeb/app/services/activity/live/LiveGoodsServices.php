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

namespace app\services\activity\live;


use app\dao\activity\live\LiveGoodsDao;
use app\services\BaseServices;
use app\services\product\product\StoreProductServices;
use crmeb\exceptions\AdminException;
use crmeb\services\app\MiniProgramService;
use crmeb\utils\DownloadImage;
use crmeb\utils\Str;
use think\facade\Log;

/**
 * Class LiveGoodsServices
 * @package app\services\activity\live
 */class LiveGoodsServices extends BaseServices
{
    /**
     * LiveGoodsServices constructor.
     * @param LiveGoodsDao $dao
     */    public function __construct(LiveGoodsDao $dao)
    {
        $this->dao = $dao;
    }

    public function getList(array $where)
    {
        [$page, $limit] = $this->getPageValue();
        $where['is_del'] = 0;
        $list = $this->dao->getList($where, '*', ['product'], $page, $limit);
        $count = $this->dao->count($where);
        return compact('count', 'list');
    }

    public function create(array $product_ids)
    {
        if (!$product_ids) throw new AdminException('Lỗi tham số');
        /** @var StoreProductServices $product */        $productServices = app()->make(StoreProductServices::class);
        $products = $productServices->getColumn([['id', 'IN', $product_ids], ['is_del', '=', 0], ['is_show', '=', 1]], 'id,image,store_name,price,price as cost_price,stock', 'id');
        if (count($product_ids) != count($products)) {
            throw new AdminException('Sản phẩm đã được lấy ra khỏi kệ hoặc chuyển vào thùng tái chế');
        }
        $checkGoods = $this->dao->getCount([['product_id', 'IN', $product_ids], ['is_del', '=', 0], ['audit_status', '<>', 3]]);
        if ($checkGoods > 0) {
            throw new AdminException('Thêm không thành công');
        }
        return array_merge($products);
    }

    /**
     * Thêm sản phẩm phát trực tiếp
     * @param array $goods_info
     * @return bool
     * @throws \Exception
     */    public function add(array $goods_info)
    {
        if (!$goods_info) throw new AdminException('Lỗi tham số');
        $product_ids = array_column($goods_info, 'id');
        $this->create($product_ids);
        $miniUpload = MiniProgramService::materialTemporaryService();
        /** @var DownloadImage $download */        $download = app()->make(DownloadImage::class);
        $dataAll = $data = [];
        $time = time();
        foreach ($goods_info as $product) {
            $data = [
                'product_id' => $product['id'],
                'name' => Str::substrUTf8($product['store_name'], 12, 'UTF-8', ''),
                'cover_img' => $product['image'] ?? '',
                'price_type' => 1,
                'cost_price' => $product['cost_price'] ?? 0.00,
                'price' => $product['price'] ?? 0.00,
                'url' => 'pages/goods_details/index?id=' . $product['id'],
                'sort' => $product['sort'] ?? 0,
                'add_time' => $time
            ];
            try {
                $path = root_path() . 'public' . $download->thumb(true)->downloadImage($data['cover_img'])['path'];
                $coverImgUrl = $miniUpload->uploadImage($path)->media_id;
                @unlink($path);
            } catch (\Throwable $e) {
                Log::error('Lỗi thêm hình ảnh sản phẩm phát sóng trực tiếp, nguyên nhân：' . $e->getMessage());
                @unlink($path);
                $coverImgUrl = $data['cover_img'];
            }
            $res = MiniProgramService::addGoods($coverImgUrl, $data['name'], $data['price_type'], $data['url'], floatval($data['price']));
            $data['goods_id'] = $res['goodsId'];
            $data['audit_id'] = $res['auditId'];
            $data['audit_status'] = 1;
            $dataAll[] = $data;
        }
        if (!$goods = $this->dao->saveAll($dataAll)) {
            throw new AdminException('Thêm không thành công');
        }
        return true;
    }

    /**
     * Đồng bộ hóa sản phẩm
     * @return bool
     * @throws \EasyWeChat\Core\Exceptions\InvalidArgumentException
     */    public function syncGoods()
    {
        $liveGoods = $this->dao->getColumn([['goods_id', '>', 0]], '*', 'id');
        if ($liveGoods) {
            /** @var DownloadImage $downloadImage */            $downloadImage = app()->make(DownloadImage::class);
            foreach ($liveGoods as $good) {
                $path = root_path() . 'public' . $downloadImage->thumb(true)->downloadImage($good['cover_img'])['path'];
                $coverImgUrl = MiniProgramService::materialTemporaryService()->uploadImage($path)->media_id;
                @unlink($path);
                $res = MiniProgramService::addGoods($coverImgUrl, $good['name'], $good['price_type'], $good['url'], floatval($good['price']));
                $data['goods_id'] = $res['goodsId'];
                $data['audit_id'] = $res['auditId'];
                $data['audit_status'] = 1;
                if (!$this->dao->update($good['id'], $data, 'id')) {
                    throw new AdminException('Đồng bộ hóa không thành công');
                }
            }
        }
        return true;
    }

    public function wxCreate($goods)
    {
        if ($goods['goods_id'])
            throw new AdminException('Sản phẩm đã được tạo');

        $goods = $goods->toArray();
        /** @var DownloadImage $downloadImage */        $downloadImage = app()->make(DownloadImage::class);
        $path = root_path() . 'public' . $downloadImage->thumb(true)->downloadImage($goods['cover_img'])['path'];

        $url = 'pages/goods_details/index?id=' . $goods['product_id'];
        $coverImgUrl = MiniProgramService::materialTemporaryService()->uploadImage($path)->media_id;
        @unlink($path);
        return MiniProgramService::addGoods($coverImgUrl, $goods['name'], 1, $url, floatval($goods['price']));
    }

    public function isShow(int $id, $is_show)
    {
        $goods = $this->dao->get(['id' => $id, 'audit_status' => 2]);
        if (!$goods) {
            throw new AdminException('Hoạt động này không được phép xem xét hoặc xem xét không thành công');
        }
        $this->dao->update($id, ['is_show' => $is_show]);
        return true;
    }

    /**
     * Gửi lại để xem xét
     * @param int $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function audit(int $id)
    {
        $goods = $this->dao->get($id);
        if (!$goods) {
            throw new AdminException('Dữ liệu không tồn tại');
        }
        if ($goods['audit_status'] != 0) {
            throw new AdminException('Đang xem xét hoặc phê duyệt');
        }
        if (!$this->dao->update($id, ['audit_status' => 1])) {
            throw new AdminException('Sửa đổi không thành công');
        }
        return MiniProgramService::auditGoods((int)$goods['good_id']);
    }

    /**
     * Rút lại đánh giá
     * @param int $id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function resetAudit(int $id)
    {
        $goods = $this->dao->get($id);
        if (!$goods) {
            throw new AdminException('Dữ liệu không tồn tại');
        }
        if ($goods['audit_status'] == 0) {
            return true;
        }
        if ($goods['audit_status'] != 1) {
            throw new AdminException('Đánh giá được thông qua hoặc không thành công');
        }
        if (!$this->dao->update($id, ['audit_status' => 0])) {
            throw new AdminException('Sửa đổi không thành công');
        }
        return MiniProgramService::resetauditGoods((int)$goods['good_id'], $goods['audit_id']);
    }

    /**
     * Xóa sản phẩm
     * @param int $id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function delete(int $id)
    {
        $goods = $this->dao->get(['id' => $id, 'is_del' => 0]);
        if ($goods) {
            if (in_array($goods['audit_status'], [0, 1])) {
                throw new AdminException('Sản phẩm đang được xem xét và không thể xóa được.');
            }
            if (!$this->dao->update($id, ['is_del' => 1])) {
                throw new AdminException('Xóa không thành công');
            }
            if (MiniProgramService::deleteGoods((int)$goods->goods_id)) {
                /** @var LiveRoomGoodsServices $liveRoomGoods */                $liveRoomGoods = app()->make(LiveRoomGoodsServices::class);
                $liveRoomGoods->delete(['live_goods_id' => $id]);
            }
        }
        return true;
    }

    /**
     * Đồng bộ trạng thái Đánh giá sản phẩm trực tiếp
     * @return bool
     */    public function syncGoodStatus()
    {
        $goodsIds = $this->dao->goodsStatusAll();
        if (!count($goodsIds)) return true;
        $res = MiniProgramService::getGooodsInfo(array_keys($goodsIds));
        foreach ($res as $item) {
            if (isset($goodsIds[$item['goods_id']]) && $item['audit_status'] != $goodsIds[$item['goods_id']]) {
                $data = ['audit_status' => $item['audit_status']];
                //TODO Đồng bộ trạng thái review sản phẩm
                $this->dao->update((int)$goodsIds[$item['goods_id']]['id'], $data);
            }
        }
        return true;
    }

}
