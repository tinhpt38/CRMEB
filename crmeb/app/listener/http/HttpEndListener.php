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

namespace app\listener\http;

use think\facade\Log;
use think\Response;

/**
 * yêu cầu kết thúc sự kiện
 * Class Create
 * @package app\listener\http
 */class HttpEndListener
{
    public function handle(Response $response): void
    {
        if (!is_array($response->getData())) return;
        //Thành công và thất bại trong kinh doanh được lưu trữ riêng biệt
        $status = $response->getData()["status"] ?? 0;
        if ($status == 200) {
            //Chuyển đổi nhật ký thành công kinh doanh
            if (!config("log.success_log")) return;
            $type = "success";
        } else {
            //Chuyển đổi nhật ký thất bại trong kinh doanh
            if (!config("log.fail_log")) return;
            $type = "fail";
        }

        //ID Khách hàng hiện tại
        if (!empty(request()->uid())) {
            $uid = request()->uid();
        } elseif (!empty(request()->adminId())) {
            $uid = request()->adminId();
        } elseif (!empty(request()->kefuId())) {
            $uid = request()->kefuId();
        } else {
            $uid = 0;
        }

        //Nội dung nhật ký
        $log = [
            $uid,                                                                                 //ID khách hàng
            request()->ip(),                                                                      //khách hàngip
            ceil(msectime() - (request()->time(true) * 1000)),                                    //Thời gian thực hiện (mili giây）
            request()->rule()->getMethod(),                                                       //Loại yêu cầu
            str_replace("/", "", request()->rootUrl()),                                           //Ứng dụng
            request()->baseUrl(),                                                                 //lộ trình
            json_encode(request()->param(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),     //Thông số yêu cầu
            json_encode($response->getData(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),   //dữ liệu phản hồi

        ];
        Log::write(implode("|", $log), $type);
    }
}
