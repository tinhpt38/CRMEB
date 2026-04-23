<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ Tệp nhập ứng dụng ]
namespace think;

if ('7.1.0' > phpversion()) {
    exit('Phiên bản php của bạn quá thấp và phần mềm này không thể cài đặt được. Nó tương thích với phiên bản php.7.1~7.4，Cảm ơn！');
}
if (phpversion() >= '8.0.0') {
    exit('Phiên bản php của bạn quá cao và phần mềm này không thể cài đặt được. Nó tương thích với phiên bản php.7.1~7.4，Cảm ơn！');
}

define('DS', DIRECTORY_SEPARATOR);

//Kiểm tra xem hệ thống CRMEB đã được cài đặt chưa
if(file_exists("./install/") && !file_exists("./install.lock")){
    if($_SERVER['PHP_SELF'] != '/index.php'){
        header("Content-type: text/html; charset=utf-8");
        exit("Hãy cài đặt nó vào thư mục gốc của tên miền,giống:<br/> www.xxx.com/index.php Chính xác <br/>  www.xxx.com/www/index.php sai lầm,Tên miền không thể được theo sau bởi một thư mục., Nhưng dự án không có giới hạn lưu trữ thư mục gốc,Có thể được đặt trong bất kỳ thư mục,apacheChỉ cần cấu hình máy chủ ảo");
    }
    header('Location:/install/index.php');
    exit();
}

require __DIR__ . '/../vendor/autoload.php';

// Thực thi ứng dụng HTTP và phản hồi
$http = (new App())->http;

$response = $http->run();

$response->send();

$http->end($response);
