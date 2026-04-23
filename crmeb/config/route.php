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
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | Áp dụng cài đặt
// +----------------------------------------------------------------------

return [
    // pathinfodấu phân cách
    'pathinfo_depr'         => '/',
    // URLhậu tố giả tĩnh
    'url_html_suffix'       => 'html',
    // URLCác tham số chế độ thông thường để tạo tự động
    'url_common_param'      => true,
    // Có bật tính năng giải quyết độ trễ tuyến đường hay không
    'url_lazy_route'        => false,
    // Có buộc phải sử dụng định tuyến hay không
    'url_route_must'        => true,
    // Hợp nhất các quy tắc định tuyến
    'route_rule_merge'      => false,
    // Liệu tuyến đường có khớp chính xác không?
    'route_complete_match'  => true,
    // Sử dụng định tuyến chú thích
    'route_annotation'      => false,
    // Có bật bộ nhớ đệm tuyến đường hay không
    'route_check_cache'     => false,
    // Định tuyến tham số kết nối bộ đệm
    'route_cache_option'    => [],
    // bộ đệm tuyến đườngKey
    'route_check_cache_key' => '',
    // Tên lớp bộ điều khiển truy cập
    'controller_layer'      => 'controller',
    // Tên bộ điều khiển trống
    'empty_controller'      => 'Error',
    // Có nên sử dụng hậu tố điều khiển hay không
    'controller_suffix'     => false,
    // Quy tắc biến định tuyến mặc định
    'default_route_pattern' => '[\w\.]+',
    // Có tự động chuyển đổi tên bộ điều khiển và hành động trong URL hay không
    'url_convert'           => true,
    // Có bật bộ nhớ đệm yêu cầu hay không, bộ nhớ đệm tự động thực sự và hỗ trợ cài đặt quy tắc bộ nhớ đệm yêu cầu.
    'request_cache'         => false,
    // Yêu cầu thời hạn hiệu lực của bộ đệm
    'request_cache_expire'  => null,
    // Quy tắc loại trừ bộ đệm yêu cầu chung
    'request_cache_except'  => [],
    // Tên bộ điều khiển mặc định
    'default_controller'    => 'Index',
    // Tên hoạt động mặc định
    'default_action'        => 'index',
    // Hậu tố phương thức hoạt động
    'action_suffix'         => '',
    // Phương thức xử lý được trả về theo định dạng JSONP mặc định
    'default_jsonp_handler' => 'jsonpReturn',
    // Phương thức xử lý JSONP mặc định
    'var_jsonp_handler'     => 'callback',
];
