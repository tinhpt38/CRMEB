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
// | Áp dụng Cài đặt
// +----------------------------------------------------------------------

return [
    // Có buộc phải sử dụng định tuyến hay không
    'url_route_must'        => true,
    // Hợp nhất các quy tắc định tuyến
    'route_rule_merge'      => true,
    // Liệu tuyến đường có khớp chính xác không?
    'route_complete_match'  => true,
    // Có tự động chuyển đổi tên bộ điều khiển và hành động trong URL hay không
    'url_convert'           => false,
];
