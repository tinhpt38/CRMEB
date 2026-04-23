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


use app\services\product\product\StoreProductLogServices;
use crmeb\basic\BaseJobs;
use crmeb\traits\QueueTrait;
use think\facade\Log;

class ProductLogJob extends BaseJobs
{
    use QueueTrait;
    /**
     * @param $type  'visit','cart','order','pay','collect','refund'
     * @param $data
     * @return bool
     */
    public function doJob($type,$data)
    {
        try {
            /** @var StoreProductLogServices $productLogServices */
            $productLogServices = app()->make(StoreProductLogServices::class);
            $productLogServices->createLog($type, $data);
        }catch (\Throwable $e){
            Log::error('Đã xảy ra lỗi khi ghi hồ sơ sản phẩm,Lý do lỗi:' . $e->getMessage());
        }
        return true;
    }
}
