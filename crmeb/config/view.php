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
// | Cài đặt mẫu
// +----------------------------------------------------------------------

return [
    // Loại công cụ tạo mẫu được sử dụngThink
    'type'          => 'Think',
    // Quy tắc hiển thị mẫu mặc định 1 Phân tích thành chữ thường + gạch chân 2 Chuyển đổi tất cả thành chữ thường 3 Giữ nguyên phương thức hoạt động
    'auto_rule'     => 1,
    // Tên thư mục mẫu
    'view_dir_name' => 'view',
    // Hậu tố mẫu
    'view_suffix'   => 'html',
    // Dấu phân cách tên tệp mẫu
    'view_depr'     => DIRECTORY_SEPARATOR,
    // Thẻ bắt đầu thẻ thông thường của công cụ mẫu
    'tpl_begin'     => '{',
    // Thẻ đóng thẻ thông thường của công cụ tạo mẫu
    'tpl_end'       => '}',
    // thẻ thư viện thẻ thẻ bắt đầu
    'taglib_begin'  => '{',
    // thẻ thẻ thư viện thẻ thẻ cuối
    'taglib_end'    => '}',
    //Đường dẫn tệp mẫu
    'view_path'     => public_path(),
];
