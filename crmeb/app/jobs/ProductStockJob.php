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

use app\services\product\product\OutStoreProductServices;
use crmeb\basic\BaseJobs;
use crmeb\traits\QueueTrait;
use think\facade\Log;

class ProductStockJob extends BaseJobs
{
    use QueueTrait;

    /**
     * tính toán chia
     * @param array $data
     * @return bool
     */    public function distribute(array $data): bool
    {
        try {
            foreach ($data as $key => $item) {
                ProductStockJob::dispatch('calcValueStock', [$key]);
            }
        } catch (\Exception $e) {
            Log::error(['msg' => 'Tính toán phân chia không thành công,Lý do lỗi:' . $e->getMessage(), 'data' => $data]);
        }
        return true;
    }

    /**
     * Tính toán hàng tồn kho
     * @param int $id
     * @return bool
     */    public function calcValueStock(int $id): bool
    {
        try {
            /** @var OutStoreProductServices $services */            $services = app()->make(OutStoreProductServices::class);
            $services->calcStockByAttrValue($id);
        } catch (\Exception $e) {
            Log::error(['msg' => 'Không thể tính toán tồn kho sản phẩm,Lý do lỗi:' . $e->getMessage(), 'data' => $id]);
        }
        return true;
    }
}