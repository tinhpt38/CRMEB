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

namespace app\jobs;


use app\services\product\product\CopyTaobaoServices;
use app\services\product\product\StoreDescriptionServices;
use app\services\product\product\StoreProductServices;
use app\services\product\sku\StoreProductAttrValueServices;
use crmeb\basic\BaseJobs;
use crmeb\services\CacheService;
use crmeb\traits\QueueTrait;
use think\facade\Log;

/**
 * Sao chép sản phẩm
 * Class ProductCopyJob
 * @package app\jobs
 */class ProductCopyJob extends BaseJobs
{
    use QueueTrait;

    /**
     * Tải hình ảnh chi tiết sản phẩm
     * @param $id
     * @return bool
     */    public function copyDescriptionImage($id, $description, $image, $count)
    {
        try {
            /** @var CopyTaobaoServices $copyTaobao */            $copyTaobao = app()->make(CopyTaobaoServices::class);
            /** @var StoreDescriptionServices $storeDescriptionServices */            $storeDescriptionServices = app()->make(StoreDescriptionServices::class);
            if (is_int(strpos($image, 'http'))) {
                $d_image = $image;
            } else {
                $d_image = 'http://' . ltrim($image, '\//');
            }
            $description_cache = CacheService::get('desc_images_' . $id);
            if ($description_cache === null || $description_cache === '') {
                $description_cache = $description;
                CacheService::set('desc_images_count' . $id, 0);
            }
            $res = $copyTaobao->downloadCopyImage($d_image);
            $description_cache = str_replace($image, $res, $description_cache);
            $desc_count = CacheService::get('desc_images_count' . $id) + 1;
            if ($desc_count == $count) {
                CacheService::delete('desc_images_' . $id);
                CacheService::delete('desc_images_count' . $id);
                $storeDescriptionServices->saveDescription((int)$id, $description_cache);
            } else {
                CacheService::set('desc_images_' . $id, $description_cache);
                CacheService::set('desc_images_count' . $id, $desc_count);
            }
        } catch (\Throwable $e) {
            Log::error('Không tải được hình ảnh chi tiết sản phẩm, lý do không tải được:' . $e->getMessage() . '_' . $e->getFile() . '_' . $e->getLine());
        }
        return true;
    }

    /**
     * Tải xuống hình ảnh băng chuyền sản phẩm
     * @param $id
     * @return bool
     */    public function copySliderImage($id, $image, $count)
    {
        try {
            /** @var CopyTaobaoServices $copyTaobao */            $copyTaobao = app()->make(CopyTaobaoServices::class);
            /** @var StoreProductServices $StoreProductServices */            $StoreProductServices = app()->make(StoreProductServices::class);
            //Tải hình ảnh
            $res = $copyTaobao->downloadCopyImage($image);
            //Lấy hình ảnh băng chuyền trong bộ đệm
            $slider_images = CacheService::get('slider_images_' . $id) ?? [];
            //Nếu bộ nhớ đệm rỗng, hãy gán giá trị[]
            if ($slider_images === null || $slider_images === '') $slider_images = [];
            //Chèn hình ảnh đã tải xuống vào mảng
            array_push($slider_images, $res);
            //nếu như$slider_imagesSố lượng ảnh vào và ảnh đến$countNếu bằng nhau thì có nghĩa là quá trình tải xuống đã hoàn tất và được ghi vào bảng sản phẩm. Nếu không, tiếp tục chèn vào bộ đệm.
            if (count($slider_images) == $count) {
                CacheService::delete('slider_images_' . $id);
                $image = $slider_images[0];
                $slider_images = $slider_images ? json_encode($slider_images) : '';
                $StoreProductServices->update($id, ['slider_image' => $slider_images, 'image' => $image]);
            } else {
                CacheService::set('slider_images_' . $id, $slider_images);
            }
        } catch (\Throwable $e) {
            Log::error('Không tải được hình ảnh băng chuyền sản phẩm, lý do không tải được:' . $e->getMessage() . '_' . $e->getFile() . '_' . $e->getLine());
        }
        return true;
    }

    /**
     * Tải hình ảnh thông số kỹ thuật sản phẩm
     * @param $value_id
     * @param $value_image
     * @return bool
     */    public function copyAttrImage($value_id, $value_image)
    {
        try {
            /** @var CopyTaobaoServices $copyTaobao */            $copyTaobao = app()->make(CopyTaobaoServices::class);
            /** @var StoreProductAttrValueServices $StoreProductAttrValueServices */            $StoreProductAttrValueServices = app()->make(StoreProductAttrValueServices::class);
            //Tải hình ảnh
            $res = $copyTaobao->downloadCopyImage($value_image);
            $StoreProductAttrValueServices->update($value_id, ['image' => $res]);
        } catch (\Throwable $e) {
            Log::error('Không tải được hình ảnh thông số sản phẩm, lý do không tải được:' . $e->getMessage() . '_' . $e->getFile() . '_' . $e->getLine());
        }
        return true;
    }
}
