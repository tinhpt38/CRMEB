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

// Áp dụng các tập tin công khai
use app\services\pay\PayServices;
use crmeb\services\CacheService;
use crmeb\services\HttpService;
use Fastknife\Service\ClickWordCaptchaService;
use think\exception\ValidateException;
use crmeb\services\FormBuilder as Form;
use app\services\other\UploadService;
use Fastknife\Service\BlockPuzzleCaptchaService;
use app\services\system\lang\LangTypeServices;
use app\services\system\lang\LangCodeServices;
use app\services\system\lang\LangCountryServices;
use think\facade\Config;
use think\facade\Log;
use think\facade\Db;

if (!function_exists('crmebLog')) {
    /**
     * CRMEB Log nhật ký
     * @param $msg
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2023/03/03
     */
    function crmebLog($msg)
    {
        Log::write($msg, 'crmeb');
    }
}

if (!function_exists('success')) {
    /**
     * chức năng trợ giúp phản hồi
     * @param mixed $msg tin nhắn phản hồi
     * @param array|null $data dữ liệu phản hồi
     * @param array|null $replace mảng thay thế tin nhắn
     * @return \think\Response
     * @see \crmeb\utils\Json::success()
     */
    function success($msg = 'success', ?array $data = null, ?array $replace = [])
    {
        return app('json')->success($msg, $data, $replace);
    }
}

if (!function_exists('fail')) {
    /**
     * Chức năng trợ giúp phản hồi lỗi
     * @param mixed $msg tin nhắn phản hồi
     * @param array|null $data dữ liệu phản hồi
     * @param array|null $replace mảng thay thế tin nhắn
     * @return \think\Response
     * @see \crmeb\utils\Json::fail()
     */
    function fail($msg = 'fail', ?array $data = null, ?array $replace = [])
    {
        return app('json')->fail($msg, $data, $replace);
    }
}

if (!function_exists('getWorkerManUrl')) {

    /**
     * Nhận dữ liệu dịch vụ khách hàng
     * @return mixed
     */
    function getWorkerManUrl()
    {
        $ws = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'wss://' : 'ws://';
        $host = $_SERVER['HTTP_HOST'];
        $data['admin'] = $ws . $host . '/notice';
        $data['chat'] = $ws . $host . '/msg';
        return $data;
    }
}
if (!function_exists('object2array')) {

    /**
     * Đối tượng vào mảng
     * @param $object
     * @return array|mixed
     */
    function object2array($object)
    {
        $array = [];
        if (is_object($object)) {
            foreach ($object as $key => $value) {
                $array[$key] = $value;
            }
        } else {
            $array = $object;
        }
        return $array;
    }
}

if (!function_exists('exception')) {
    /**
     * Xử lý ngoại lệ ném
     * @param $msg
     * @param int $code
     * @param string $exception
     * @throws \think\Exception
     */
    function exception($msg, $code = 0, $exception = '')
    {
        $e = $exception ?: '\think\Exception';
        throw new $e($msg, $code);
    }
}

if (!function_exists('sys_config')) {
    /**
     * Nhận một cấu hình duy nhất của hệ thống
     * @param string $name
     * @param string $default
     * @return string
     */
    function sys_config(string $name, $default = '')
    {
        if (empty($name))
            return $default;
        $sysConfig = app('sysConfig')->get($name);
        if (is_array($sysConfig)) {
            foreach ($sysConfig as &$item) {
                if (!is_array($item)) {
                    if (strpos($item, '/uploads/system/') !== false || strpos($item, '/statics/system_images/') !== false) $item = set_file_url($item);
                }
            }
        } else {
            if (strpos($sysConfig, '/uploads/system/') !== false || strpos($sysConfig, '/statics/system_images/') !== false) $sysConfig = set_file_url($sysConfig);
        }
        $config = is_array($sysConfig) ? $sysConfig : trim($sysConfig);
        if ($config === '' || $config === false) {
            return $default;
        } else {
            return $config;
        }
    }
}

if (!function_exists('sys_data')) {
    /**
     * Nhận dữ liệu hệ thống riêng lẻ
     * @param string $name
     * @return string
     */
    function sys_data(string $name, int $limit = 0)
    {
        return app('sysGroupData')->getData($name, $limit);
    }
}

if (!function_exists('filter_emoji')) {

    // Lọc biểu thức biểu tượng cảm xúc
    function filter_emoji($str)
    {
        $str = preg_replace_callback(    //Thực hiện tìm kiếm biểu thức chính quy và thay thế bằng cách sử dụng lệnh gọi lại
            '/./u',
            function (array $match) {
                return strlen($match[0]) >= 4 ? '' : $match[0];
            },
            $str);
        return $str;
    }
}


if (!function_exists('str_middle_replace')) {
    /** TODO Hệ thống không được sử dụng
     * @param string $string Chuỗi cần được thay thế
     * @param int $start Giữ lại vài cái đầu tiên
     * @param int $end Cuối cùng còn lại bao nhiêu?
     * @return string
     */
    function str_middle_replace($string, $start, $end)
    {
        $strlen = mb_strlen($string, 'UTF-8');//Nhận độ dài chuỗi
        $firstStr = mb_substr($string, 0, $start, 'UTF-8');//Nhận vị trí đầu tiên
        $lastStr = mb_substr($string, -1, $end, 'UTF-8');//Lấy chữ số cuối cùng
        return $strlen == 2 ? $firstStr . str_repeat('*', mb_strlen($string, 'utf-8') - 1) : $firstStr . str_repeat("*", $strlen - 2) . $lastStr;

    }
}


if (!function_exists('sensitive_words_filter')) {

    /**
     * Lọc từ nhạy cảm
     *
     * @param string
     * @return string
     */
    function sensitive_words_filter($str)
    {
        if (!$str) return '';
        $file = app()->getAppPath() . 'public/statics/plug/censorwords/CensorWords';
        $words = file($file);
        foreach ($words as $word) {
            $word = str_replace(array("\r\n", "\r", "\n", "/", "<", ">", "=", " "), '', $word);
            if (!$word) continue;

            $ret = preg_match("/$word/", $str, $match);
            if ($ret) {
                return $match[0];
            }
        }
        return '';
    }
}

if (!function_exists('make_path')) {

    /**
     * Chuyển đổi đường dẫn tải lên,đường dẫn mặc định
     * @param $path
     * @param int $type
     * @param bool $force
     * @return string
     */
    function make_path($path, int $type = 2, bool $force = false)
    {
        $path = DS . ltrim(rtrim($path));
        switch ($type) {
            case 1:
                $path .= DS . date('Y');
                break;
            case 2:
                $path .= DS . date('Y') . DS . date('m');
                break;
            case 3:
                $path .= DS . date('Y') . DS . date('m') . DS . date('d');
                break;
        }
        try {
            if (is_dir(app()->getRootPath() . 'public' . DS . 'uploads' . $path) == true || mkdir(app()->getRootPath() . 'public' . DS . 'uploads' . $path, 0777, true) == true) {
                return trim(str_replace(DS, '/', $path), '.');
            } else return '';
        } catch (\Exception $e) {
            if ($force)
                throw new \Exception($e->getMessage());
//            return 'Không thể tạo thư mục, vui lòng kiểm tra quyền thư mục tải lên của bạn：' . app()->getRootPath() . 'public' . DS . 'uploads' . DS . 'attach' . DS;
            return '';
        }

    }
}


if (!function_exists('curl_file_exist')) {
    /**
     * CURL Kiểm tra xem tập tin từ xa có hiện diện không
     * @param $url
     * @return bool
     */
    function curl_file_exist($url)
    {
        $ch = curl_init();
        try {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            $contents = curl_exec($ch);
            if (preg_match("/404/", $contents)) return false;
            if (preg_match("/403/", $contents)) return false;
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
if (!function_exists('set_file_url')) {
    /**
     * Đặt đường dẫn bổ sung
     * @param $url
     * @return bool
     */
    function set_file_url($image, $siteUrl = '')
    {
        if (!strlen(trim($siteUrl))) $siteUrl = sys_config('site_url');
        if (!$image) return $image;
        if (is_array($image)) {
            foreach ($image as &$item) {
                $domainTop1 = substr($item, 0, 4);
                $domainTop2 = substr($item, 0, 2);
                if ($domainTop1 != 'http' && $domainTop2 != '//')
                    $item = $siteUrl . str_replace('\\', '/', $item);
            }
        } else {
            $domainTop1 = substr($image, 0, 4);
            $domainTop2 = substr($image, 0, 2);
            if ($domainTop1 != 'http' && $domainTop2 != '//')
                $image = $siteUrl . str_replace('\\', '/', $image);
        }
        return $image;
    }
}

if (!function_exists('set_http_type')) {
    /**
     * Sửa đổi https và http
     * @param string $url tên miền
     * @param int $type 0 Quay lại https 1 Quay lại http
     * @return string
     */
    function set_http_type($url, $type = 0)
    {

        // Xác minh cơ bản
        if (empty($url)) {
            return $url;
        }
        
        // Kiểm tra xem nó đã hoàn thành chưa URL
        $is_full_url = (strpos($url, '://') !== false);
        
        if ($is_full_url) {
            // xử lý hoàn tất URL
            if ($type) {
                // Chuyển đổi thành HTTP
                $url = preg_replace('/^https:/i', 'http:', $url);
            } else {
                // Chuyển đổi thành HTTPS
                $url = preg_replace('/^http:/i', 'https:', $url);
            }
        }
        
        return $url;
    }

}

if (!function_exists('check_card')) {
    /**
     * Xác minh ID
     * @param $card
     * @return bool
     */
    function check_card($card)
    {
        $city = [11 => "Bắc Kinh", 12 => "Thiên Tân", 13 => "Hà Bắc", 14 => "Sơn Tây", 15 => "Nội Mông", 21 => "Liêu Ninh", 22 => "Cát Lâm", 23 => "Hắc Long Giang ", 31 => "Thượng Hải", 32 => "Giang Tô", 33 => "Chiết Giang", 34 => "An Huy", 35 => "Phúc Kiến", 36 => "Giang Tây", 37 => "Sơn Đông", 41 => "Hà Nam", 42 => "hồ bắc ", 43 => "Hồ Nam", 44 => "Quảng Đông", 45 => "Quảng Tây", 46 => "Hải Nam", 50 => "Trùng Khánh", 51 => "Tứ Xuyên", 52 => "Quý Châu", 53 => "Vân Nam", 54 => "Tây Tạng ", 61 => "Thiểm Tây", 62 => "Cam Túc", 63 => "Thanh Hải", 64 => "Ninh Hạ", 65 => "Tân Cương", 71 => "Đài Loan", 81 => "Hồng Kông", 82 => "Macao", 91 => "nước ngoài "];
        $tip = "";
        $match = "/^\d{6}(18|19|20)?\d{2}(0[1-9]|1[012])(0[1-9]|[12]\d|3[01])\d{3}(\d|X)$/";
        $pass = true;
        if (!$card || !preg_match($match, $card)) {
            //Lỗi định dạng chứng minh nhân dân
            $pass = false;
        } else if (!$city[substr($card, 0, 2)]) {
            //Địa chỉ sai
            $pass = false;
        } else {
            //18Số kiểm tra cuối cùng của CMND cần được xác minh
            if (strlen($card) == 18) {
                $card = str_split($card);
                //∑(ai×Wi)(mod 11)
                //hệ số trọng số
                $factor = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
                //Kiểm tra chữ số
                $parity = [1, 0, 'X', 9, 8, 7, 6, 5, 4, 3, 2];
                $sum = 0;
                $ai = 0;
                $wi = 0;
                for ($i = 0; $i < 17; $i++) {
                    $ai = $card[$i];
                    $wi = $factor[$i];
                    $sum += $ai * $wi;
                }
                $last = $parity[$sum % 11];
                if ($parity[$sum % 11] != $card[17]) {
                    //                        $tip = "Kiểm tra lỗi bit";
                    $pass = false;
                }
            } else {
                $pass = false;
            }
        }
        if (!$pass) return false;/* Lỗi định dạng chứng minh nhân dân*/
        return true;/* Định dạng thẻ ID là chính xác*/
    }
}
if (!function_exists('check_link')) {
    /**
     * Xác minh địa chỉ
     * @param string $link
     * @return false|int
     */
    function check_link(string $link)
    {
        return preg_match("/^(http|https|ftp):\/\/[A-Za-z0-9-_]+\.[A-Za-z0-9-_]+[\/=\?%\-&_~`@[\]\’:+!]*([^<>\”])*$/", $link);
    }
}
if (!function_exists('check_phone')) {
    /**
     * Xác minh số điện thoại di động
     * @param $phone
     * @return false|int
     */
    function check_phone($phone)
    {
        return \crmeb\utils\PhoneValidate::isVnMobile((string)$phone);
    }
}
if (!function_exists('format_vnd')) {
    /**
     * Định dạng số tiền VND.
     */
    function format_vnd($amount): string
    {
        return number_format((float)$amount, 0, ',', '.') . ' đ';
    }
}
if (!function_exists('anonymity')) {
    /**
     * Xử lý ẩn danh biệt danh của người dùng
     * @param $name
     * @return string
     */
    function anonymity($name, $type = 1)
    {
        if ($type == 1) {
            return mb_substr($name, 0, 1, 'UTF-8') . '**' . mb_substr($name, -1, 1, 'UTF-8');
        } else {
            $strLen = mb_strlen($name, 'UTF-8');
            $min = 3;
            if ($strLen <= 1)
                return '*';
            if ($strLen <= $min)
                return mb_substr($name, 0, 1, 'UTF-8') . str_repeat('*', $min - 1);
            else
                return mb_substr($name, 0, 1, 'UTF-8') . str_repeat('*', $strLen - 1) . mb_substr($name, -1, 1, 'UTF-8');
        }
    }
}
if (!function_exists('sort_list_tier')) {
    /**
     * sắp xếp thứ bậc
     * @param $data
     * @param int $pid
     * @param string $field
     * @param string $pk
     * @param string $html
     * @param int $level
     * @param bool $clear
     * @return array
     */
    function sort_list_tier($data, $pid = 0, $field = 'pid', $pk = 'id', $html = '|-----', $level = 1, $clear = true)
    {
        static $list = [];
        if ($clear) $list = [];
        foreach ($data as $k => $res) {
            if ($res[$field] == $pid) {
                $res['html'] = str_repeat($html, $level);
                $list[] = $res;
                unset($data[$k]);
                sort_list_tier($data, $res[$pk], $field, $pk, $html, $level + 1, false);
            }
        }
        return $list;
    }
}

if (!function_exists('sort_city_tier')) {
    /**
     * Tổng hợp dữ liệu thành phố
     * @param $data
     * @param int $pid
     * @param string $field
     * @param string $pk
     * @param string $html
     * @param int $level
     * @param bool $clear
     * @return array
     */
    function sort_city_tier($data, $pid = 0, $navList = [])
    {
        foreach ($data as $k => $menu) {
            if ($menu['parent_id'] == $pid) {
                unset($menu['parent_id']);
                unset($data[$k]);
                $menu['c'] = sort_city_tier($data, $menu['v']);
                $navList[] = $menu;
            }
        }
        return $navList;
    }
}

if (!function_exists('time_tran')) {
    /**
     * Chuyển đổi nhân hóa dấu thời gian
     * @param $time
     * @return string
     */
    function time_tran($time)
    {
        $t = time() - $time;
        $f = array(
            '31536000' => 'Năm',
            '2592000' => 'tháng',
            '604800' => 'Tuần',
            '86400' => 'ngày',
            '3600' => 'Giờ',
            '60' => 'phút',
            '1' => 'Thứ hai'
        );
        foreach ($f as $k => $v) {
            if (0 != $c = floor($t / (int)$k)) {
                return $c . $v . 'phía trước';
            }
        }
    }
}

if (!function_exists('url_to_path')) {
    /**
     * urlđường dẫn chuyển đổi
     * @param $url
     * @return string
     */
    function url_to_path($url)
    {
        $path = trim(str_replace('/', DS, $url), DS);
        if (0 !== strripos($path, 'public'))
            $path = 'public' . DS . $path;
        return app()->getRootPath() . $path;
    }
}

if (!function_exists('path_to_url')) {
    /**
     * đường dẫn đến đường dẫn url
     * @param $path
     * @return string
     */
    function path_to_url($path)
    {
        return trim(str_replace(DS, '/', $path), '.');
    }
}

if (!function_exists('image_to_base64')) {
    /**
     * Lấy hình ảnh và chuyển đổi nó thànhbase64
     * @param string $avatar
     * @return bool|string
     */
    function image_to_base64($avatar = '', $timeout = 9)
    {
        $avatar = str_replace('https', 'http', $avatar);
        try {
            $url = parse_url($avatar);
            if ($url['scheme'] . '://' . $url['host'] == sys_config('site_url')) {
                $pattern = '/<\?php(.*?)\?>/s';
                $imgData = preg_replace($pattern, '', file_get_contents(public_path() . substr($url['path'], 1)));
                return "data:image/jpeg;base64," . base64_encode($imgData);
            }
            $url = $url['host'];
            $header = [
                'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:45.0) Gecko/20100101 Firefox/45.0',
                'Accept-Language: zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
                'Accept-Encoding: gzip, deflate, br',
                'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
                'Host:' . $url
            ];
            $dir = pathinfo($url);
            $host = $dir['dirname'];
            $refer = $host . '/';
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_REFERER, $refer);
            curl_setopt($curl, CURLOPT_URL, $avatar);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_ENCODING, 'gzip');
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            $data = curl_exec($curl);
            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            if ($code == 200) {
                return "data:image/jpeg;base64," . base64_encode($data);
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (!function_exists('put_image')) {
    /**
     * Lấy hình ảnh và chuyển đổi nó thànhbase64
     * @param string $avatar
     * @return bool|string
     */
    function put_image($url, $filename = '')
    {

        if ($url == '') {
            return false;
        }
        try {
            if ($filename == '') {
                $ext = pathinfo($url, PATHINFO_EXTENSION);
                if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
                    return false;
                }
                $filename = time() . "." . $ext;
            }

            // Lưu tập tin vào thư mục được chỉ định
            $imgData = file_get_contents($url);
            $pattern = '/<\?php(.*?)\?>/s';
            $imgData = preg_replace($pattern, '', $imgData);
            if ($imgData !== false) {
                $path = 'uploads' . DS . 'qrcode' . DS . $filename;
                if (file_put_contents($path, $imgData) !== false) {
                    return $path;
                }
            }
        } catch (\Exception $e) {
        }

        return false;
    }
}


if (!function_exists('debug_file')) {
    /**
     * Gỡ lỗi tập tin
     * @param $content
     */
    function debug_file($content, string $fileName = 'error', string $ext = 'txt')
    {
        $msg = '[' . date('Y-m-d H:i:s', time()) . '] [ DEBUG ] ';
        $pach = app()->getRuntimePath();
        file_put_contents($pach . $fileName . '.' . $ext, $msg . print_r($content, true) . "\r\n", FILE_APPEND);
    }
}


if (!function_exists('sql_filter')) {
    /**
     * sql Lọc tham số
     * @param string $str
     * @return mixed
     */
    function sql_filter(string $str)
    {
        $filter = ['select ', 'insert ', 'update ', 'delete ', 'drop', 'truncate ', 'declare', 'xp_cmdshell', '/add', ' or ', 'exec', 'create', 'chr', 'mid', ' and ', 'execute'];
        $toupper = array_map(function ($str) {
            return strtoupper($str);
        }, $filter);
        return str_replace(array_merge($filter, $toupper, ['%20']), '', $str);
    }
}

if (!function_exists('filter_str')) {
    /**
     * Lọc các ký tự nhạy cảm với chuỗi
     * @param $str
     * @return array|mixed|string|string[]|null
     */
    function filter_str($str)
    {
        $param_filter_type = sys_config('param_filter_type');
        if ($param_filter_type != 0) {
            $rules = preg_split('/\r\n|\r|\n/', base64_decode(sys_config('param_filter_data')));
            if ($param_filter_type == 1) {
                foreach ($rules as $item) {
                    if (preg_match($item, $str)) {
                        throw new \Exception('Yêu cầu giao diện không thành công: hoạt động bất hợp pháp！');
                    }
                }
            }
            if (filter_var($str, FILTER_VALIDATE_URL)) {
                $url = parse_url($str);
                if (!isset($url['scheme'])) return $str;
                $host = $url['scheme'] . '://' . $url['host'];
                $str = $host . preg_replace($rules, '', str_replace($host, '', $str));
            } else {
                $str = preg_replace($rules, '', $str);
            }
        }
        return $str;
    }
}

if (!function_exists('is_brokerage_statu')) {

    /**
     * Tôi có thể trở thành người quảng bá không?
     * @param float $price
     * @return bool
     */
    function is_brokerage_statu(float $price)
    {
        if (!sys_config('brokerage_func_status')) {
            return false;
        }
        $storeBrokerageStatus = sys_config('store_brokerage_statu', 1);
        if ($storeBrokerageStatus == 1) {
            return false;
        } else if ($storeBrokerageStatus == 2) {
            return true;
        } else {
            $storeBrokeragePrice = sys_config('store_brokerage_price', 0);
            return $price >= $storeBrokeragePrice;
        }
    }
}

if (!function_exists('array_unique_fb')) {
    /**
     * Loại bỏ các giá trị trùng lặp khỏi mảng hai chiều
     * @param $array
     * @return array
     */
    function array_unique_fb($array)
    {
        $out = array();
        foreach ($array as $key => $value) {
            if (!in_array($value, $out)) {
                $out[$key] = $value;
            }
        }
        $out = array_values($out);
        return $out;
    }
}


if (!function_exists('get_crmeb_version')) {
    /**
     * Lấy số phiên bản hệ thống CRMEB
     * @param string $default
     * @return string
     */
    function get_crmeb_version($default = 'v1.0.0')
    {
        try {
            $version = parse_ini_file(app()->getRootPath() . '.version');
            return $version['version'] ?? $default;
        } catch (\Throwable $e) {
            return $default;
        }
    }
}

if (!function_exists('get_crmeb_version_vode')) {
    /**
     * Lấy số phiên bản hệ thống CRMEB
     * @param string $default
     * @return string
     */
    function get_crmeb_version_vode($default = '0')
    {
        try {
            $version = parse_ini_file(app()->getRootPath() . '.version');
            return $version['version_code'] ?? $default;
        } catch (\Throwable $e) {
            return $default;
        }
    }
}

if (!function_exists('get_file_link')) {
    /**
     * Lấy đường dẫn đầy đủ của file có tên miền
     * @param string $link
     * @return string
     */
    function get_file_link(string $link)
    {
        if (!$link) {
            return '';
        }
        if (substr($link, 0, 4) === "http" || substr($link, 0, 2) === "//") {
            return $link;
        } else {
            return app()->request->domain() . $link;
        }
    }
}

if (!function_exists('tidy_tree')) {
    /**
     * Định dạng danh mục
     * @param $menusList
     * @param int $pid
     * @param array $navList
     * @return array
     */
    function tidy_tree($menusList, $pid = 0, $navList = [])
    {
        foreach ($menusList as $k => $menu) {
            if ($menu['parent_id'] == $pid) {
                unset($menusList[$k]);
                $menu['children'] = tidy_tree($menusList, $menu['id']);
                if ($menu['children']) $menu['expand'] = true;
                $navList[] = $menu;
            }
        }
        return $navList;
    }
}

if (!function_exists('create_form')) {
    /**
     * Phương pháp tạo biểu mẫu
     * @param string $title
     * @param array $field
     * @param $url
     * @param string $method
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    function create_form(string $title, array $field, $url, string $method = 'POST')
    {
        $form = Form::createForm((string)$url);//Gửi địa chỉ
        $form->setMethod($method);//Phương thức gửi
        $form->setRule($field);//trường biểu mẫu
        $form->setTitle($title);//tiêu đề biểu mẫu
        $rules = $form->formRule();
        $title = $form->getTitle();
        $action = $form->getAction();
        $method = $form->getMethod();
        $info = '';
        $status = true;
        $methodData = ['POST', 'PUT', 'GET', 'DELETE'];
        if (!in_array(strtoupper($method), $methodData)) {
            throw new ValidateException('Phương thức yêu cầu sai');
        }
        return compact('rules', 'title', 'action', 'method', 'info', 'status');
    }
}

if (!function_exists('msectime')) {
    /**
     * Nhận mili giây
     * @return float
     */
    function msectime()
    {
        list($msec, $sec) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
    }
}


if (!function_exists('array_bc_sum')) {
    /**
     * Lấy tổng của mảng một chiều với độ chính xác cao
     * @param array $data
     * @return string
     */
    function array_bc_sum(array $data)
    {
        $sum = '0';
        foreach ($data as $item) {
            $sum = bcadd($sum, (string)$item, 2);
        }
        return $sum;
    }
}

if (!function_exists('get_tree_children')) {
    /**
     * tree menu con
     * @param array $data dữ liệu
     * @param string $childrenname Tên dữ liệu con
     * @param string $keyName Tên khóa dữ liệu
     * @param string $pidName Tên khóa dữ liệu cấp trên
     * @return array
     */
    function get_tree_children(array $data, string $childrenname = 'children', string $keyName = 'id', string $pidName = 'pid')
    {
        $list = array();
        foreach ($data as $value) {
            $list[$value[$keyName]] = $value;
        }
        $tree = array(); //Cây được định dạng
        foreach ($list as $item) {
            if (isset($list[$item[$pidName]])) {
                $list[$item[$pidName]][$childrenname][] = &$list[$item[$keyName]];
            } else {
                $tree[] = &$list[$item[$keyName]];
            }
        }
        return $tree;
    }
}

if (!function_exists('get_tree_children_value')) {

    function get_tree_children_value(array $data, $value, string $childrenname = 'children', string $keyName = 'id')
    {
        static $childrenValue = [];
        foreach ($data as $item) {
            $childrenData = $item[$childrenname] ?? [];
            if (count($childrenData)) {
                return get_tree_children_value($childrenData, $childrenname, $keyName);
            } else {
                if ($item[$keyName] == $value) {
                    $childrenValue[] = $item['value'];
                }
            }
        }
        return $childrenValue;
    }
}


if (!function_exists('get_tree_value')) {
    /**
     * lấy
     * @param array $data
     * @param int|string $value
     * @return array
     */
    function get_tree_value(array $data, $value)
    {
//        static $childrenValue = [];
//        foreach ($data as &$item) {
//            if ($item['value'] == $value) {
//                $childrenValue[] = $item['value'];
//                if ($item['pid']) {
//                    $value = $item['pid'];
//                    unset($item);
//                    return get_tree_value($data, $value);
//                }
//            }
//        }
//        return $childrenValue;
        $childrenValue = []; // Mảng để lưu trữ các giá trị phụ được tìm thấy
        foreach ($data as $item) {
            if ($item['value'] == $value) { // Nếu mục hiện tại'value'Khóa khớp với giá trị đã cho
                $childrenValue[] = $item['value']; // Thêm giá trị hiện tại vào mảng các giá trị phụ
                if ($item['pid']) { // Nếu mục hiện tại có'pid'giá trị, chỉ ra rằng có cha mẹ
                    // Gọi đệ quy hàm get_tree_value và thêm giá trị của mục cha'pid'giá trị như mới$valuetham số
                    $childrenValue = array_merge($childrenValue, get_tree_value($data, $item['pid']));
                }
            }
        }
        return $childrenValue; // Trả về một mảng chứa tất cả các giá trị phụ
    }
}

if (!function_exists('get_image_thumb')) {
    /**
     * Nhận hình thu nhỏ
     * @param $filePath
     * @param string $type all|big|mid|small
     * @param bool $is_remote_down
     * @return mixed|string|string[]
     */
    function get_image_thumb($filePath, string $type = 'all', bool $is_remote_down = false)
    {
        if (!$filePath || !is_string($filePath) || strpos($filePath, '?') !== false) return $filePath;
        try {
            $upload = UploadService::getOssInit($filePath, $is_remote_down);
            //TODO
            $fileArr = explode('/', $filePath);
            $data = $upload->thumb($filePath, end($fileArr), $type);
            $image = $type == 'all' ? $data : $data[$type] ?? $filePath;
        } catch (\Throwable $e) {
            $image = $filePath;
        }
        $data = parse_url($image);
        if (!isset($data['host']) && (substr($image, 0, 2) == './' || substr($image, 0, 1) == '/')) {//Không phải là một địa chỉ đầy đủ
            $image = sys_config('site_url') . $image;
        }
        //Yêu cầu là https và hình ảnh là http. Địa chỉ hình ảnh cần phải được thay đổi.
        //TODO có đọc cấu hình nền hay khôngurl
        if (strpos(request()->domain(), 'https:') !== false && strpos($image, 'https:') === false) {
            $image = str_replace('http:', 'https:', $image);
        }
        return $image;
    }
}

if (!function_exists('get_thumb_water')) {
    /**
     * Xử lý mảng để thu được hình thu nhỏ và hình mờ
     * @param $list
     * @param string $type
     * @param array|string[] $field 1、['image','images'] type Tham số giá trị:type 2、['small'=>'image','mid'=>'images'] type Lấy mảng trườngkey
     * @param bool $is_remote_down
     * @return array|mixed|string|string[]
     */
    function get_thumb_water($list, string $type = 'small', array $field = ['image'], bool $is_remote_down = false)
    {
        // Chức năng hình thu nhỏ không được bật và dữ liệu gốc được trả về trực tiếp.
        if (!sys_config('image_thumb_status', 0)) {
            return $list;
        }
        if (!$list || !$field) return $list;
        $baseType = $type;
        $data = $list;
        if (is_string($list)) {
            $field = [$type => 'image'];
            $data = ['image' => $list];
        }
        if (is_array($data)) {
            foreach ($field as $type => $key) {
                if (is_integer($type)) {//Mảng chỉ mục, mặc địnhtype
                    $type = $baseType;
                }
                //mảng một chiều
                if (isset($data[$key])) {
                    if (is_array($data[$key])) {
                        $path_data = [];
                        foreach ($data[$key] as $k => $path) {
                            $path_data[] = get_image_thumb($path, $type, $is_remote_down);
                        }
                        $data[$key] = $path_data;
                    } else {
                        $data[$key] = get_image_thumb($data[$key], $type, $is_remote_down);
                    }
                } else {
                    foreach ($data as &$item) {
                        if (!isset($item[$key]))
                            continue;
                        if (is_array($item[$key])) {
                            $path_data = [];
                            foreach ($item[$key] as $k => $path) {
                                $path_data[] = get_image_thumb($path, $type, $is_remote_down);
                            }
                            $item[$key] = $path_data;
                        } else {
                            $item[$key] = get_image_thumb($item[$key], $type, $is_remote_down);
                        }
                    }
                }
            }
        }
        return is_string($list) ? ($data['image'] ?? '') : $data;
    }
}

if (!function_exists('getLang')) {
    /**
     * Chức năng dịch đa ngôn ngữ: chuyển đổi văn bản đến“Nhận dạng ngôn ngữ Trung Quốc”Dịch sang văn bản ngôn ngữ tương ứng và hỗ trợ thay thế biến
     *
     *Quy trình thực hiện:
     * 1. Chụp ngoại lệ: Toàn bộ logic được gói gọn trong try-catch. Nếu xảy ra lỗi ở bất kỳ liên kết nào, mã định danh ban đầu sẽ được trả về trực tiếp để tránh gián đoạn hệ thống do ngoại lệ của mô-đun ngôn ngữ.
     * 2. Nội dung phụ thuộc: Nhận ba phiên bản dịch vụ cốt lõi cùng một lúc
     * - LangCountryServices: Chịu trách nhiệm lập bản đồ các quốc gia/khu vực và loại ngôn ngữ
     * - LangTypeServices: chịu trách nhiệm về siêu dữ liệu của các loại ngôn ngữ (như zh-CN, en-US)
     * - LangCodeServices: chịu trách nhiệm về bảng mã ngôn ngữ（code => Đọc văn bản đã dịch)
     * 3. Phạm vi ngôn ngữ (range) ưu tiên phán đoán:
     * ① Đọc tiêu đề yêu cầu cb-lang trước (front-end/interface chủ động chỉ định)
     * ② Nếu không có, hãy đọc ngôn ngữ mặc định của hệ thống（LangTypeServices.is_default = 1）
     *    ③ Nếu hệ thống không được định cấu hình bằng ngôn ngữ mặc định, thẻ ngôn ngữ đầu tiên của Trình duyệt Ngôn ngữ chấp nhận sẽ được đọc.
     * ④ Nếu nó vẫn trống, buộc chuyển sang zh-CN để đảm bảo rằng logic tiếp theo có sẵn giá trị
     * 4. Tăng tốc bộ đệm: tất cả“Một khi đã viết, hiếm khi thay đổi”thống nhất sử dụng dữ liệu CacheService::remember() Bộ nhớ đệm trong 3600 giây để giảm áp lực cơ sở dữ liệu
     * - sys_lang_source_map: Tiếng Trung remarks => code ánh xạ, được sử dụng để chuyển đổi dữ liệu đến“biểu tượng Trung Quốc”Chuyển đổi sang nội bộ code
     *    - type_id_{range}：Kiểm tra type_id tương ứng dựa trên mã ngắn ngôn ngữ (chẳng hạn như zh-CN)
     * - lang_type_data: tất cả các loại ngôn ngữ được kích hoạt id => file_name Bảng ánh xạ, được sử dụng để xác minh xem ngôn ngữ có hợp pháp không
     *    - lang_{file_name}：gói ngôn ngữ cụ thể code => Toàn bộ mảng văn bản dịch
     * 5. Quá trình dịch thuật:
     * - Nếu loại ngôn ngữ không tồn tại, hãy trả về trực tiếp mã định danh ban đầu.
     * - nếu như“biểu tượng Trung Quốc”Nếu nó tồn tại trong bảng ánh xạ và mã tương ứng tồn tại trong gói ngôn ngữ thì văn bản dịch sẽ được lấy; nếu không, giấy tờ tùy thân ban đầu sẽ được trả lại.
     * 6. Thay thế biến: được hỗ trợ {:tên biến} Ngữ pháp, thay thế hàng loạt phần giữ chỗ trong văn bản đã dịch bằng $replace giá trị trong mảng
     * 7. Khôi phục ngoại lệ: Nhật ký lỗi chi tiết (tên tệp/số dòng/thông tin ngoại lệ) được ghi lại trong quá trình bắt và nhận dạng ban đầu vẫn được trả về để đảm bảo hoạt động tiếp tục.
     *
     * @param string $msg   Mã định danh ngôn ngữ Trung Quốc (nhận xét), chẳng hạn như "Tên người dùng không được để trống"
     * @param array  $replace Ánh xạ biến tùy chọn, chẳng hạn như ['name' => 'Số điện thoại']，sẽ thay đổi văn bản {:name} Thay thế bằng“Số điện thoại”
     * @return string       Văn bản dịch cuối cùng; mã định danh ban đầu được trả về trong trường hợp không tìm thấy bất kỳ ngoại lệ hoặc bản dịch nào
     */
    function getLang($msg, array $replace = [])
    {
        /* Khi xảy ra lỗi trong toàn bộ quá trình dịch thuật, logo gốc sẽ được trả lại trực tiếp để tránh làm gián đoạn hoạt động kinh doanh. */
        try {
            /* --------------- 1. Tiêm phụ thuộc: nhận các dịch vụ liên quan đến ngôn ngữ --------------- */
            /** @var LangCountryServices $langCountryServices */
            $langCountryServices = app()->make(LangCountryServices::class);
            /** @var LangTypeServices $langTypeServices */
            $langTypeServices = app()->make(LangTypeServices::class);
            /** @var LangCodeServices $langCodeServices */
            $langCodeServices = app()->make(LangCodeServices::class);

            /* --------------- 2. Xác định phạm vi ngôn ngữ hiện tại（range） --------------- */
            $request = app()->request;
            // Ưu tiên ngôn ngữ được chỉ định bởi giao diện người dùng/giao diện
            $range = $request->header('cb-lang');
            if (!$range) {
                // Khi không được chỉ định, ngôn ngữ mặc định của hệ thống sẽ được đọc.
                $range = CacheService::remember('range_name', function () use ($langTypeServices) {
                    return $langTypeServices->value(['is_default' => 1], 'file_name');
                });
                if (!$range) {
                    // Hệ thống không được cấu hình với ngôn ngữ mặc định, vì vậy hãy thử sử dụng trình duyệt Accept-Language
                    if ($request->header('accept-language') !== null) {
                        $range = explode(',', $request->header('accept-language'))[0];
                    } else {
                        // Mặc định: tiếng Việt
                        $range = 'vi-VN';
                    }
                }
            }

            $defaultTypeId = CacheService::remember('default_lang_type_id', function () use ($langTypeServices) {
                return (int)($langTypeServices->value(['is_default' => 1], 'id') ?: 1);
            }, 3600);

            /* --------------- 3. Đọc dữ liệu bản đồ khác nhau (có bộ đệm) --------------- */
            // remarks => code (ngôn ngữ nguồn, mặc định type_id default)
            $langSourceMap = CacheService::remember('sys_lang_source_map', function () use ($langCodeServices, $defaultTypeId) {
                return $langCodeServices->getColumn(['type_id' => $defaultTypeId], 'code', 'remarks');
            }, 3600);

            // Kiểm tra ngôn ngữ tương ứng theo mã viết tắt của ngôn ngữ (chẳng hạn như vi-VN) type_id
            $typeId = CacheService::remember('type_id_' . $range, function () use ($langCountryServices, $range, $defaultTypeId) {
                return (int)($langCountryServices->value(['code' => $range], 'type_id') ?: $defaultTypeId);
            }, 3600);

            // Tất cả các loại ngôn ngữ được kích hoạt id => file_name bảng ánh xạ
            $langData = CacheService::remember('lang_type_data', function () use ($langTypeServices) {
                return $langTypeServices->getColumn(['status' => 1, 'is_del' => 0], 'file_name', 'id');
            }, 3600);

            /* --------------- 4. Xác minh xem loại ngôn ngữ có hợp pháp không --------------- */
            if (!isset($langData[$typeId])) {
                return $msg;
            }

            /* --------------- 5. Đọc gói ngôn ngữ hiện tại（code => Dịch văn bản） --------------- */
            $langStr = 'lang_' . str_replace('-', '_', $langData[$typeId]); // Xây dựng bộ đệm key
            $lang = CacheService::remember($langStr, function () use ($typeId, $langCodeServices) {
                return $langCodeServices->getColumn(['type_id' => $typeId], 'lang_explain', 'code');
            }, 3600);

            /* --------------- 6. Nhận văn bản dịch --------------- */
            if (isset($langSourceMap[$msg]) && isset($lang[$langSourceMap[$msg]])) {
                // Nếu bảng ánh xạ tồn tại và mã tương ứng tồn tại trong gói ngôn ngữ, hãy sử dụng văn bản đã dịch.
                $message = (string)$lang[$langSourceMap[$msg]];
            } else {
                // Không tìm thấy bản dịch, quay lại logo gốc
                $message = $msg;
            }

            /* --------------- 7. Thay thế biến (hỗ trợ {:tên biến} ngữ pháp） --------------- */
            if (!empty($replace) && is_array($replace)) {
                // Xây dựng một mảng giữ chỗ, chẳng hạn như ['name'] -> ['{:name}']
                $key = array_map(function ($v) { return "{:{$v}}"; }, array_keys($replace));
                // Thay thế hàng loạt
                $message = str_replace($key, array_values($replace), $message);
            }

            return $message;
        } catch (\Throwable $e) {
            /* Ghi lại nhật ký lỗi chi tiết và vẫn trả lại nhận dạng ban đầu để đảm bảo hoạt động kinh doanh được tiếp tục. */
            Log::error('Nhận ngôn ngữmsg：' . $msg . 'Đã xảy ra lỗi, nguyên nhân là：' . json_encode([
                    'file'  => $e->getFile(),
                    'message' => $e->getMessage(),
                    'line'  => $e->getLine()
                ]));
            return $msg;
        }
    }
}

if (!function_exists('aj_captcha_check_one')) {
    /**
     * Xác minh Thanh trượt 1 Xác minh
     * @param string $token
     * @param string $pointJson
     * @return bool
     */
    function aj_captcha_check_one(string $captchaType, string $token, string $pointJson)
    {
        aj_get_serevice($captchaType)->check($token, $pointJson);
        return true;
    }
}

if (!function_exists('aj_captcha_check_two')) {
    /**
     * Xác minh xác minh thanh trượt 2x
     * @param string $token
     * @param string $pointJson
     * @return bool
     */
    function aj_captcha_check_two(string $captchaType, string $captchaVerification)
    {
        aj_get_serevice($captchaType)->verificationByEncryptCode($captchaVerification);
        return true;
    }
}


if (!function_exists('aj_captcha_create')) {
    /**
     * Tạo mã xác minh
     * @return array
     */
    function aj_captcha_create(string $captchaType)
    {
        return aj_get_serevice($captchaType)->get();
    }
}

if (!function_exists('aj_get_serevice')) {

    /**
     * @param string $captchaType
     * @return ClickWordCaptchaService|BlockPuzzleCaptchaService
     */
    function aj_get_serevice(string $captchaType)
    {
        $config = Config::get('ajcaptcha');
        switch ($captchaType) {
            case "clickWord":
                $service = new ClickWordCaptchaService($config);
                break;
            case "blockPuzzle":
                $service = new BlockPuzzleCaptchaService($config);
                break;
            default:
                throw new ValidateException('captchaTypeTham số không chính xác！');
        }
        return $service;
    }
}

if (!function_exists('out_push')) {
    /**
     * Đẩy dữ liệu mặc định
     * @param string $pushUrl
     * @param array $data
     * @param string $tip
     * @return bool
     */
    function out_push(string $pushUrl, array $data, string $tip = ''): bool
    {
        $param = json_encode($data, JSON_UNESCAPED_UNICODE);
        $res = HttpService::postRequest($pushUrl, $param, ['Content-Type:application/json', 'Content-Length:' . strlen($param)]);
        $res = $res ? json_decode($res, true) : [];
        if (!$res || !isset($res['code']) || $res['code'] != 0) {
            \think\facade\Log::error(['msg' => $tip . 'Đẩy không thành công', 'data' => $res]);
            return false;
        }
        return true;
    }
}

if (!function_exists('dump_sql')) {
    /**
     * Insql
     * @param string $pushUrl
     * @param array $data
     * @param string $tip
     * @return bool
     */
    function dump_sql()
    {
        Db::listen(function ($sql) {
            var_dump($sql);
        });
    }
}

if (!function_exists('toIntArray')) {

    /**
     * Xử lý id, v.v. và lọc tham số
     * @param $data
     * @param string $separator
     * @return array
     */
    function toIntArray($data, string $separator = ',')
    {
        if (!is_string($data) && !is_int($data)) {
            return array_unique(array_diff(array_map('intval', $data), [0]));
        } else {
            return !empty($data) ? array_unique(array_diff(array_map('intval', explode($separator, $data)), [0])) : [];
        }
    }
}
