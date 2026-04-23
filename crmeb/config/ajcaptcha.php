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
declare(strict_types=1);

return [
    'font_file' => '', //Tùy chỉnh đường dẫn gói phông chữ, để trống và sử dụng giá trị mặc định
    //Mã xác minh văn bản
    'click_world' => [
        'backgrounds' => []
    ],
    //Mã xác minh trượt
    'block_puzzle' => [
        /*Đường dẫn ảnh nền, để trống và sử dụng giá trị mặc định. Nó hỗ trợ hai cấu trúc dữ liệu: chuỗi và mảng. chuỗi là thư mục của ảnh mặc định và mảng chỉ mục mảng là địa chỉ của ảnh cụ thể.*/
        'backgrounds' => [
            public_path().'statics/images/check1.jpg',
            public_path().'statics/images/check2.jpg',
            public_path().'statics/images/check3.jpg',
            public_path().'statics/images/check4.jpg',
        ],

        /*Sơ đồ mẫu,Định dạng giống như trên và hỗ trợ chuỗi vàarray*/
        'templates' => [],

        'offset' => 10, //Dung sai bù đắp

        'is_cache_pixel' => true, //Có bật giá trị pixel hình ảnh được lưu trong bộ nhớ đệm hay không. Việc kích hoạt nó có thể cải thiện hiệu suất phản hồi của máy chủ (nhưng xin lưu ý rằng khi thay đổi hình ảnh, bạn cần xóa bộ nhớ đệm.）

        'is_interfere' => true, //Kích hoạt bản đồ nhiễu
    ],
    //hình mờ
    'watermark' => [
        'fontsize' => 12,
        'color' => '#FFFFFF',
        'text' => 'CRMEB'
    ],
    'cache' => [
        //Nếu bạn dùng framework và muốn sử dụng cache driver như redis thì nên đổi sang driver cache trong framework.
        'constructor' => app()->make(\think\Cache::class),
        'method' => [
            //Việc tuân thủ thông số kỹ thuật PSR-16 không yêu cầu cài đặt thông số này（tp6, laravel,hyperf）。Ví dụ: tp5 không hỗ trợ nó (phương thức bộ đệm tp5 làrm,Vì vậy, hãy cấu hình nó như"delete" => "rm"）
            /**
             * 'get' => 'get', //lấy
             * 'set' => 'set', //cài đặt
             * 'delete' => 'delete',//xóa bỏ
             * 'has' => 'has' //keytồn tại
             */
        ],
        'options' => [
            //Nếu bạn vẫn sử dụng\Fastknife\Utils\CacheUtilsLà trình điều khiển bộ đệm, bạn có thể tùy chỉnh cấu hình bộ đệm。
            'expire' => 300,//Khoảng thời gian hiệu lực của bộ đệm (mặc định là 0, có nghĩa là bộ đệm vĩnh viễn)）
            'prefix' => '', //tiền tố bộ đệm
            'path' => '', //thư mục bộ đệm
            'serialize' => [], //Phương pháp tuần tự hóa và giải tuần tự hóa bộ đệm
        ]
    ]
];
