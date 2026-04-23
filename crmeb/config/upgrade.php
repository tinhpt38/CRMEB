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

/**
 * Nâng cấp cấu hình
 * Danh sách phiên bản được sắp xếp từ nhỏ đến lớn và việc nâng cấp sẽ được thực hiện theo thứ tự.
 */
return [
    // Yêu cầu phiên bản tối thiểu (Chỉ khi đạt đến phiên bản này, bạn mới có thể sử dụng chức năng nâng cấp trực tuyến giữa các phiên bản)
    // Vì chức năng nâng cấp trực tuyến đa phiên bản đã được phát triển ở phiên bản v6.0.0 nên người dùng có phiên bản thấp hơn phiên bản này không thể sử dụng được.
    'min_version' => [
        'version' => 'CRMEB-BZ v6.0.0',
        'code' => 600,
        'message' => 'Chức năng nâng cấp trực tuyến đa phiên bản yêu cầu sử dụng v6.0.0 trở lên. Vui lòng nâng cấp thủ công lên v6.0.0 trước.'
    ],

    // Danh sách phiên bản (Sắp xếp theo phiên bản từ nhỏ nhất đến lớn nhất)
    // version: tên phiên bản
    // code: mã phiên bản (Số, để so sánh)
    // file: Nâng cấp tên tập lệnh (Liên quan đến thư mục nâng cấp/phiên bản/)
    // description: Mô tả phiên bản
    'versions' => [
        [
            'version' => 'CRMEB-BZ v6.0.0',
            'code' => 600,
            'file' => 'v6.0.0.php',
            'description' => 'Phiên bản tối ưu hóa hiệu suất'
        ],
    ],

    // Nâng cấp thư mục tập lệnh
    'upgrade_path' => app()->getRootPath() . 'upgrade' . DIRECTORY_SEPARATOR . 'versions' . DIRECTORY_SEPARATOR,

    // Thông tin nền tảng
    'platform' => 'CRMEB',

    // APPThông tin chứng nhận (Phần ghi đè có thể được đọc từ tệp .version)
    'app_id' => 'ze7x9rxsv09l6pvsyo',
    'app_key' => 'fuF7U9zaybLa5gageVQzxtxQMFnvU2OI',

    // Nâng cấp cấu hình máy chủ từ xa
    'remote' => [
        'login_url' => 'https://upgrade.crmeb.net/api/login',
        'upgrade_url' => 'https://upgrade.crmeb.net/api/upgrade/list',
        'upgrade_current_url' => 'https://upgrade.crmeb.net/api/upgrade/current_list',
        'agreement_url' => 'https://upgrade.crmeb.net/api/upgrade/agreement',
        'package_download_url' => 'https://upgrade.crmeb.net/api/upgrade/download',
        'upgrade_status_url' => 'https://upgrade.crmeb.net/api/upgrade/status',
        'upgrade_log_url' => 'https://upgrade.crmeb.net/api/upgrade/log',
    ],

    // Cấu hình dự phòng
    'backup' => [
        'database' => true,  // Có nên sao lưu cơ sở dữ liệu không
        'project' => true,   // Có sao lưu các tập tin dự án hay không
        'path' => app()->getRootPath() . 'backup' . DIRECTORY_SEPARATOR,
    ],

    // Thư mục bị bỏ qua (Khi sao lưu)
    'ignore_dirs' => ['.', '..', '.git', '.idea', 'runtime', 'backup', 'upgrade'],

    // Phần mở rộng tập tin bị bỏ qua
    'ignore_extensions' => ['zip', 'gz', 'log'],
];
