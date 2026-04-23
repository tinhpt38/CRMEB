<?php
//chữ ký tập tin
$fileValue = '';
//Yêu cầu phiên bản php tối thiểu
define('PHP_EDITION', '7.1.0');
//Kiểm tra môi trường dịch vụ
if (function_exists('saeAutoLoader') || isset($_SERVER['HTTP_BAE_ENV_APPID'])) {
    showHtml('Rất tiếc, môi trường hiện tại không hỗ trợ hệ thống này. Vui lòng sử dụng các dịch vụ độc lập hoặc máy chủ đám mây.！');
}

define('APP_DIR', _dir_path(substr(dirname(__FILE__), 0, -15)));//Thư mục dự án
define('SITE_DIR', _dir_path(substr(dirname(__FILE__), 0, -8)));//Thư mục tập tin đầu vào

if (file_exists('../install.lock')) {
    showHtml('Bạn đã cài đặt hệ thống. Nếu bạn muốn cài đặt lại nó, trước tiên hãy xóa tệp install.lock trong thư mục chung, sau đó cài đặt lại.。');
}

@set_time_limit(1000);

if ('7.1.0' > phpversion()) {
    exit('Phiên bản php của bạn quá thấp và phần mềm này không thể cài đặt được. Nó tương thích với phiên bản php.7.1~7.4，Cảm ơn！');
}
if (phpversion() >= '8.0.0') {
    exit('Phiên bản php của bạn quá cao và phần mềm này không thể cài đặt được. Nó tương thích với phiên bản php.7.1~7.4，Cảm ơn！');
}

date_default_timezone_set('PRC');
error_reporting(E_ALL & ~E_NOTICE);
header('Content-Type: text/html; charset=UTF-8');

//mysqlLấy từ vùng chứa cấu hình cơ sở dữ liệu
$MYSQL_HOST_IP = getenv('MYSQL_HOST_IP') ?: '127.0.0.1';
$MYSQL_PORT = getenv('MYSQL_PORT') ?: '3306';
$MYSQL_USER = getenv('MYSQL_USER') ?: 'root';
$MYSQL_PASSWORD = getenv('MYSQL_PASSWORD') ?: '123456';
$MYSQL_DATABASE = getenv('MYSQL_DATABASE') ?: 'crmeb';
//redisLấy từ thùng chứa cấu hình
$REDIS_HOST_IP = getenv('REDIS_HOST_IP') ?: '127.0.0.1';
$REDIS_PORT = getenv('REDIS_PORT') ?: '6379';
$REDIS_DATABASE = getenv('REDIS_DATABASE') ?: 0;
$REDIS_PASSWORD = getenv('REDIS_PASSWORD') ?: '';

//cơ sở dữ liệu
$sqlFile = 'crmeb.sql';
$configFile = '.env';
if (!file_exists(SITE_DIR . 'install/' . $sqlFile) || !file_exists(SITE_DIR . 'install/' . $configFile)) {
    echo 'Thiếu các tập tin cài đặt cần thiết!';
    exit;
}
$Title = "CRMEBTrình hướng dẫn cài đặt";
$Powered = "Powered by CRMEB";
$steps = array(
    '1' => 'Thỏa thuận cấp phép cài đặt',
    '2' => 'Phát hiện môi trường đang chạy',
    '3' => 'Cài đặt thông số cài đặt',
    '4' => 'Chi tiết cài đặt',
    '5' => 'Cài đặt hoàn tất',
);
$step = $_GET['step'] ?? 1;

//Địa chỉ
$scriptName = !empty($_SERVER["REQUEST_URI"]) ? $scriptName = $_SERVER["REQUEST_URI"] : $scriptName = $_SERVER["PHP_SELF"];
$rootPath = @preg_replace("/\/(I|i)nstall\/index\.php(.*)$/", "", $scriptName);
[$request_scheme, $request_host] = getSchemeAndHost();

switch ($step) {
    case '1':
        include_once("./templates/step1.php");
        exit();

    case '2':
        if (phpversion() < '7.1.0' || phpversion() >= '8.0.0') {
            die('Hệ thống này yêu cầu PHP như 7.1~7.4 phiên bản PHP hiện tại là：' . phpversion());
        }

        $passOne = $passTwo = 'yes';
        $os = PHP_OS;
        $server = $_SERVER["SERVER_SOFTWARE"];
        $phpv = phpversion();
        if (ini_get('file_uploads')) {
            $uploadSize = '<img class="yes" src="images/install/yes.png" alt="Phải">' . ini_get('upload_max_filesize');
        } else {
            $passOne = 'no';
            $uploadSize = '<img class="no" src="images/install/warring.png" alt="sai">Cấm tải lên';
        }
        if (function_exists('session_start')) {
            $session = '<img class="yes" src="images/install/yes.png" alt="Phải">cho phép';
        } else {
            $passOne = 'no';
            $session = '<img class="no" src="images/install/warring.png" alt="sai">đóng cửa';
        }
        if (!ini_get('safe_mode')) {
            $safe_mode = '<img class="yes" src="images/install/yes.png" alt="Phải">cho phép';
        } else {
            $passOne = 'no';
            $safe_mode = '<img class="no" src="images/install/warring.png" alt="sai">đóng cửa';
        }
        $tmp = function_exists('gd_info') ? gd_info() : array();
        if (!empty($tmp['GD Version'])) {
            $gd = '<img class="yes" src="images/install/yes.png" alt="Phải">' . $tmp['GD Version'];
        } else {
            $passOne = 'no';
            $gd = '<img class="no" src="images/install/warring.png" alt="sai">Chưa được cài đặt';
        }
        if (function_exists('mysqli_connect')) {
            $mysql = '<img class="yes" src="images/install/yes.png" alt="Phải">Đã cài đặt';
        } else {
            $passOne = 'no';
            $mysql = '<img class="no" src="images/install/warring.png" alt="sai">Vui lòng cài đặt tiện ích mở rộng mysqli';
        }
        if (function_exists('curl_init')) {
            $curl = '<img class="yes" src="images/install/yes.png" alt="Phải">cho phép';
        } else {
            $passOne = 'no';
            $curl = '<img class="no" src="images/install/warring.png" alt="sai">đóng cửa';
        }
        if (function_exists('bcadd')) {
            $bcmath = '<img class="yes" src="images/install/yes.png" alt="Phải">cho phép';
        } else {
            $passOne = 'no';
            $bcmath = '<img class="no" src="images/install/warring.png" alt="sai">đóng cửa';
        }
        if (function_exists('openssl_encrypt')) {
            $openssl = '<img class="yes" src="images/install/yes.png" alt="Phải">cho phép';
        } else {
            $passOne = 'no';
            $openssl = '<img class="no" src="images/install/warring.png" alt="sai">đóng cửa';
        }

        $folder = array(
            'backup',
            'public',
            'runtime',
        );
        foreach ($folder as $dir) {
            if (!is_file(APP_DIR . $dir)) {
                if (!is_dir(APP_DIR . $dir)) {
                    dir_create(APP_DIR . $dir);
                }
            }
            if (!testwrite(APP_DIR . $dir) || !is_readable(APP_DIR . $dir)) {
                $passTwo = 'no';
            }
        }
        $file = array(
            '.env',
            '.version',
            '.constant',
        );
        foreach ($file as $filename) {
            if (!is_writeable(APP_DIR . $filename) || !is_readable(APP_DIR . $filename)) {
                $passTwo = 'no';
            }
        }

        include_once("./templates/step2.php");
        exit();

    case '3':
        $dbName = strtolower(trim($_POST['dbName']));
        $_POST['dbport'] = $_POST['dbport'] ?: '3306';
        if ($_GET['mysqldbpwd']) {
            $dbHost = $_POST['dbHost'];
            $conn = mysqli_init();
            mysqli_options($conn, MYSQLI_OPT_CONNECT_TIMEOUT, 2);
            @mysqli_real_connect($conn, $dbHost, $_POST['dbUser'], $_POST['dbPwd'], NULL, $_POST['dbport']);
            if ($error = mysqli_connect_errno($conn)) {
                if ($error == 2002) {
                    die(json_encode(2002));//Địa chỉ hoặc cổng sai
                } else if ($error == 1045) {
                    die(json_encode(1045));//Tên người dùng hoặc mật khẩu sai
                } else {
                    die(json_encode(-1));//Liên kết không thành công
                }
            } else {
                if (mysqli_get_server_info($conn) < 5.1) {
                    die(json_encode(-5));//Phiên bản quá thấp
                }
                $result = mysqli_query($conn, "SELECT @@global.sql_mode");
                $result = $result->fetch_array();
                $version = mysqli_get_server_info($conn);
                if ($version >= 5.7) {
                    if (strstr($result[0], 'STRICT_TRANS_TABLES') || strstr($result[0], 'STRICT_ALL_TABLES') || strstr($result[0], 'TRADITIONAL') || strstr($result[0], 'ANSI'))
                        exit(json_encode(-2));//Cấu hình cơ sở dữ liệu cần được sửa đổi
                }
                $result = mysqli_query($conn, "select count(table_name) as c from information_schema.`TABLES` where table_schema='$dbName'");
                $result = $result->fetch_array();
                if ($result['c'] > 0) {
                    mysqli_close($conn);
                    exit(json_encode(-3));//Cơ sở dữ liệu tồn tại
                } else {
                    if (!mysqli_select_db($conn, $dbName)) {
                        //Đặt mã hóa khi tạo dữ liệu
                        if (!mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS `" . $dbName . "` DEFAULT CHARACTER SET utf8;")) {
                            exit(json_encode(-4));//Không có quyền tạo cơ sở dữ liệu
                        } else {
                            mysqli_query($conn, "DROP DATABASE `" . $dbName . "` ;");
                            mysqli_close($conn);
                            exit(json_encode(1));//Cấu hình cơ sở dữ liệu thành công
                        }
                    } else {
                        mysqli_close($conn);
                        exit(json_encode(1));//Cấu hình cơ sở dữ liệu thành công
                    }
                }
            }
        }
        if ($_GET['redisdbpwd']) {

            //redisThông tin cơ sở dữ liệu
            $rbhost = $_POST['rbhost'] ?? '127.0.0.1';
            $rbport = $_POST['rbport'] ?? 6379;
            $rbpw = $_POST['rbpw'] ?? '';
            $rbselect = $_POST['rbselect'] ?? 0;

            try {
                if (!class_exists('redis')) {
                    exit(json_encode(-1));
                }
                $redis = new Redis();
                if (!$redis) {
                    exit(json_encode(-1));
                }
                $redis->connect($rbhost, $rbport);
                if ($rbpw) {
                    $redis->auth($rbpw);
                }
                if ($rbselect) {
                    $redis->select($rbselect);
                }
                $res = $redis->set('install', 1, 10);
                if ($res) {
                    exit(json_encode(1));
                } else {
                    exit(json_encode(-3));
                }
            } catch (Throwable $e) {
                exit(json_encode(-3));
            }
        }
        include_once("./templates/step3.php");
        exit();

    case '4':
        if (intval($_GET['install'])) {
            $n = intval($_GET['n']);
            if ($n == 999999)
                exit;
            $arr = array();

            $dbHost = trim($_POST['dbhost']);
            $_POST['dbport'] = $_POST['dbport'] ?: '3306';
            $dbName = strtolower(trim($_POST['dbname']));
            $dbUser = trim($_POST['dbuser']);
            $dbPwd = trim($_POST['dbpw']);
            $dbPrefix = empty($_POST['dbprefix']) ? 'eb_' : trim($_POST['dbprefix']);

            $username = trim($_POST['manager']);
            $password = trim($_POST['manager_pwd']) ?: 'crmeb.com';

            if (!function_exists('mysqli_connect')) {
                $arr['msg'] = "Vui lòng cài đặt tiện ích mở rộng mysqli!";
                exit(json_encode($arr));
            }
            $conn = @mysqli_connect($dbHost, $dbUser, $dbPwd, NULL, $_POST['dbport']);
            if (mysqli_connect_errno($conn)) {
                $arr['msg'] = "Không thể kết nối với cơ sở dữ liệu!" . mysqli_connect_error($conn);
                exit(json_encode($arr));
            }
            mysqli_set_charset($conn, "utf8"); //,character_set_client=binary,sql_mode='';
            $version = mysqli_get_server_info($conn);
            if ($version < 5.1) {
                $arr['msg'] = 'Phiên bản cơ sở dữ liệu quá thấp! Phải là 5.1 trở lên';
                exit(json_encode($arr));
            }

            if (!mysqli_select_db($conn, $dbName)) {
                //Đặt mã hóa khi tạo dữ liệu
                if (!mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS `" . $dbName . "` DEFAULT CHARACTER SET utf8;")) {
                    $arr['msg'] = 'cơ sở dữ liệu ' . $dbName . ' Không tồn tại và không có quyền tạo cơ sở dữ liệu mới！';
                    exit(json_encode($arr));
                }
                if ($n == -1) {
                    $arr['n'] = 0;
                    $arr['msg'] = "Cơ sở dữ liệu được tạo thành công:{$dbName}";
                    exit(json_encode($arr));
                }
                mysqli_select_db($conn, $dbName);
            }

            //Đọc tập tin dữ liệu
            $sqldata = file_get_contents(SITE_DIR . 'install/' . $sqlFile);
            $sqlFormat = sql_split($sqldata, $dbPrefix);
            //Tạo và ghi tệp cơ sở dữ liệu sql vào thư viện. Kết thúc

            /**
             * Thực thi câu lệnh SQL
             */
            $counts = count($sqlFormat);
            for ($i = $n; $i < $counts; $i++) {
                $sql = trim($sqlFormat[$i]);
                if (strstr($sql, 'CREATE TABLE')) {
                    preg_match('/CREATE TABLE (IF NOT EXISTS)? `eb_([^ ]*)`/is', $sql, $matches);
                    mysqli_query($conn, "DROP TABLE IF EXISTS `$matches[2]`");
                    $sql = str_replace('`eb_', '`' . $dbPrefix, $sql);//Thay thế tiền tố bảng
                    $ret = mysqli_query($conn, $sql);
                    if ($ret) {
                        $message = 'Tạo bảng dữ liệu[' . $dbPrefix . $matches[2] . ']Hoàn thành!';
                    } else {
                        $err = mysqli_error($conn);
                        $message = 'Tạo bảng dữ liệu[' . $dbPrefix . $matches[2] . ']thất bại!Lý do thất bại：' . $err;
                    }
                    $i++;
                    $arr = array('n' => $i, 'count' => $counts, 'msg' => $message, 'time' => date('Y-m-d H:i:s'));
                    exit(json_encode($arr));
                } else {
                    if (trim($sql) == '')
                        continue;
                    $sql = str_replace('`eb_', '`' . $dbPrefix, $sql);//Thay thế tiền tố bảng
                    $sql = str_replace('http://demo.crmeb.com', $request_scheme . '://' . $request_host, $sql);//Thay thế tên miền hình ảnh
                    $sql = str_replace('http:\\\\/\\\\/demo.crmeb.com', $request_scheme . ':\\\\/\\\\/' . $request_host, $sql);//Thay thế tên miền hình ảnh
                    $ret = mysqli_query($conn, $sql);
                    $message = '';
                    $arr = array('n' => $i, 'count' => $counts, 'msg' => $message, 'time' => date('Y-m-d H:i:s'));
                }
            }


            // Xóa dữ liệu thử nghiệm
            if (!$_POST['demo']) {
                $result = mysqli_query($conn, "show tables");
                $tables = mysqli_fetch_all($result);//Các tham số MYSQL_ASSOC, MYSQLI_NUM và MYSQLI_BOTH chỉ định loại mảng sẽ được tạo.
                $bl_table = array(
                    'eb_agent_level',
                    'eb_agreement',
                    'eb_cache',
                    'eb_diy',
                    'eb_express',
                    'eb_lang_code',
                    'eb_lang_country',
                    'eb_lang_type',
                    'eb_member_right',
                    'eb_member_ship',
                    'eb_out_interface',
                    'eb_page_categroy',
                    'eb_page_link',
                    'eb_shipping_templates',
                    'eb_shipping_templates_region',
                    'eb_system_admin',
                    'eb_system_city',
                    'eb_system_config',
                    'eb_system_config_tab',
                    'eb_system_event_data',
                    'eb_system_file_info',
                    'eb_system_group',
                    'eb_system_group_data',
                    'eb_system_menus',
                    'eb_system_notification',
                    'eb_system_route',
                    'eb_system_route_cate',
                    'eb_system_timer',
                    'eb_system_user_level'
                );
                foreach ($bl_table as $k => $v) {
                    $bl_table[$k] = str_replace('eb_', $dbPrefix, $v);
                }

                foreach ($tables as $key => $val) {
                    if (!in_array($val[0], $bl_table)) {
                        mysqli_query($conn, "truncate table " . $val[0]);
                    }
                }
            }

            $unique = uniqid();

            //Đọc tệp cấu hình và thay thế dữ liệu cấu hình thực1
            $strConfig = file_get_contents(SITE_DIR . 'install/' . $configFile);
            $strConfig = str_replace('#DB_HOST#', $dbHost, $strConfig);
            $strConfig = str_replace('#DB_NAME#', $dbName, $strConfig);
            $strConfig = str_replace('#DB_USER#', $dbUser, $strConfig);
            $strConfig = str_replace('#DB_PWD#', $dbPwd, $strConfig);
            $strConfig = str_replace('#DB_PORT#', $_POST['dbport'], $strConfig);
            $strConfig = str_replace('#DB_PREFIX#', $dbPrefix, $strConfig);
            $strConfig = str_replace('#DB_CHARSET#', 'utf8', $strConfig);

            //Cấu hình bộ đệm
            $cachetype = $_POST['cache_type'] == 0 ? 'file' : 'redis';
            $strConfig = str_replace('#CACHE_TYPE#', $cachetype, $strConfig);
            $strConfig = str_replace('#CACHE_PREFIX#', 'cache_' . $unique . ':', $strConfig);
            $strConfig = str_replace('#CACHE_TAG_PREFIX#', 'cache_tag_' . $unique . ':', $strConfig);

            //redisThông tin cơ sở dữ liệu
            $rbhost = $_POST['rbhost'] ?? '127.0.0.1';
            $rbport = $_POST['rbport'] ?? '6379';
            $rbpw = $_POST['rbpw'] ?? '';
            $rbselect = $_POST['rbselect'] ?? 0;
            $strConfig = str_replace('#RB_HOST#', $rbhost, $strConfig);
            $strConfig = str_replace('#RB_PORT#', $rbport, $strConfig);
            $strConfig = str_replace('#RB_PWD#', $rbpw, $strConfig);
            $strConfig = str_replace('#RB_SELECT#', $rbselect, $strConfig);

            //Tên hàng đợi cần được thay đổi
            $strConfig = str_replace('#QUEUE_NAME#', $unique, $strConfig);

            @chmod(APP_DIR . '/.env', 0777); //Địa chỉ của tệp cấu hình cơ sở dữ liệu
            @file_put_contents(APP_DIR . '/.env', $strConfig); //Địa chỉ của tệp cấu hình cơ sở dữ liệu

            //Chèn trường bảng quản trị viên bảng tp_admin
            $time = time();
            $ip = get_client_ip();
            $ip = empty($ip) ? "0.0.0.0" : $ip;
            $password = password_hash($_POST['manager_pwd'], PASSWORD_BCRYPT);
            mysqli_query($conn, "truncate table {$dbPrefix}system_admin");
            $addadminsql = "INSERT INTO `{$dbPrefix}system_admin` (`id`, `account`, `head_pic`, `pwd`, `real_name`, `roles`, `last_ip`, `last_time`, `add_time`, `login_count`, `level`, `status`, `is_del`) VALUES
(1, '" . $username . "', '/statics/system_images/admin_head_pic.png', '" . $password . "', 'admin', '1', '" . $ip . "',$time , $time, 0, 0, 1, 0)";
            $res = mysqli_query($conn, $addadminsql);
            $res2 = true;
            if ($request_host) {
                $site_url = '\'"' . $request_scheme . '://' . $request_host . '"\'';
                $res2 = mysqli_query($conn, 'UPDATE `' . $dbPrefix . 'system_config` SET `value`=' . $site_url . ' WHERE `menu_name`="site_url"');
            }
            $arr = array('n' => 999999, 'count' => $counts, 'msg' => 'Cài đặt hoàn tất', 'time' => date('Y-m-d H:i:s'));
            exit(json_encode($arr));

        }
        include_once("./templates/step4.php");
        exit();

    case '5':
        $ip = get_client_ip();
        $host = $_SERVER['HTTP_HOST'];
        $curent_version = getversion();
        $uid = getUid();
        $version = trim($curent_version['version']);
        $platform = trim($curent_version['platform']);
        installlog();
        include_once("./templates/step5.php");
        @touch('../install.lock');
        exit();
}
//Đọc số phiên bản
function getversion()
{
    $version_arr = [];
    $curent_version = @file(APP_DIR . '.version');
    foreach ($curent_version as $val) {
        list($k, $v) = explode('=', $val);
        $version_arr[$k] = $v;
    }
    return $version_arr;
}

function getUid()
{
    $config = include_once APP_DIR.'/config/plat.php';

    return $config['stores']['sms']['template_id']['ADMIN_ORDER_UID'] ?? 0;
}

//Ghi thông tin cài đặt
function installlog()
{
    $mt_rand_str = sp_random_string(6);
    $str_constant = "<?php" . PHP_EOL . "define('INSTALL_DATE'," . time() . ");" . PHP_EOL . "define('SERIALNUMBER','" . $mt_rand_str . "');";
    @file_put_contents(APP_DIR . '.constant', $str_constant);
}

//Thẩm quyền xét xử
function testwrite($d)
{
    if (is_file($d)) {
        if (is_writeable($d)) {
            return true;
        }
        return false;

    } else {
        $tfile = "_test.txt";
        $fp = @fopen($d . "/" . $tfile, "w");
        if (!$fp) {
            return false;
        }
        fclose($fp);
        $rs = @unlink($d . "/" . $tfile);
        if ($rs) {
            return true;
        }
        return false;
    }

}


function sql_split($sql, $tablepre)
{

    if ($tablepre != "tp_")
        $sql = str_replace("tp_", $tablepre, $sql);

    $sql = preg_replace("/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "ENGINE=\\1 DEFAULT CHARSET=utf8", $sql);

    $sql = str_replace("\r", "\n", $sql);
    $ret = array();
    $num = 0;
    $queriesarray = explode(";\n", trim($sql));
    unset($sql);
    foreach ($queriesarray as $query) {
        $ret[$num] = '';
        $queries = explode("\n", trim($query));
        $queries = array_filter($queries);
        foreach ($queries as $query) {
            $str1 = substr($query, 0, 1);
            if ($str1 != '#' && $str1 != '-')
                $ret[$num] .= $query;
        }
        $num++;
    }
    return $ret;
}

function _dir_path($path)
{
    $path = str_replace('\\', '/', $path);
    if (substr($path, -1) != '/')
        $path = $path . '/';
    return $path;
}

// Nhận địa chỉ IP của khách hàng
function get_client_ip()
{
    static $ip = NULL;
    if ($ip !== NULL)
        return $ip;
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos = array_search('unknown', $arr);
        if (false !== $pos)
            unset($arr[$pos]);
        $ip = trim($arr[0]);
    } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IPĐịa chỉ xác minh pháp lý
    $ip = (false !== ip2long($ip)) ? $ip : '0.0.0.0';
    return $ip;
}

function dir_create($path, $mode = 0777)
{
    if (is_dir($path))
        return TRUE;
    $ftp_enable = 0;
    $path = dir_path($path);
    $temp = explode('/', $path);
    $cur_dir = '';
    $max = count($temp) - 1;
    for ($i = 0; $i < $max; $i++) {
        $cur_dir .= $temp[$i] . '/';
        if (@is_dir($cur_dir))
            continue;
        @mkdir($cur_dir, 0777, true);
        @chmod($cur_dir, 0777);
    }
    return is_dir($path);
}

function dir_path($path)
{
    $path = str_replace('\\', '/', $path);
    if (substr($path, -1) != '/')
        $path = $path . '/';
    return $path;
}

function sp_password($pw, $pre)
{
    $decor = md5($pre);
    $mi = md5($pw);
    return substr($decor, 0, 12) . $mi . substr($decor, -4, 4);
}

function sp_random_string($len = 8)
{
    $chars = array(
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
        "3", "4", "5", "6", "7", "8", "9"
    );
    $charsLen = count($chars) - 1;
    shuffle($chars);    // xáo trộn mảng
    $output = "";
    for ($i = 0; $i < $len; $i++) {
        $output .= $chars[mt_rand(0, $charsLen)];
    }
    return $output;
}

// Xóa các thư mục đệ quy
function delFile($dir, $file_type = '')
{
    if (is_dir($dir)) {
        $files = scandir($dir);
        //Mở thư mục //Liệt kê tất cả các file trong thư mục và xóa . Và ..
        foreach ($files as $filename) {
            if ($filename != '.' && $filename != '..') {
                if (!is_dir($dir . '/' . $filename)) {
                    if (empty($file_type)) {
                        unlink($dir . '/' . $filename);
                    } else {
                        if (is_array($file_type)) {
                            //Tệp được chỉ định khớp thông thường
                            if (preg_match($file_type[0], $filename)) {
                                unlink($dir . '/' . $filename);
                            }
                        } else {
                            //Chỉ định một tệp chứa các chuỗi nhất định
                            if (false != stristr($filename, $file_type)) {
                                unlink($dir . '/' . $filename);
                            }
                        }
                    }
                } else {
                    delFile($dir . '/' . $filename);
                    rmdir($dir . '/' . $filename);
                }
            }
        }
    } else {
        if (file_exists($dir)) unlink($dir);
    }
}

//Phương pháp nhắc lỗi
function showHtml($str)
{
    echo '
		<html>
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        </head>
        <body>
        ' . $str . '
        </body>
        </html>';
    exit;
}

/**
 * Tính chữ ký
 * @param string $path
 * @throws Exception
 */
function getFileSignature(string $path)
{
    global $fileValue;
    if (!is_dir($path)) {
        $fileValue .= @md5_file($path);
    } else {
        if (!$dh = opendir($path)) throw new Exception($path . " File open failed!");
        while (($file = readdir($dh)) != false) {
            if ($file == "." || $file == "..") {
                continue;
            } else {
                getFileSignature($path . DIRECTORY_SEPARATOR . $file);
            }
        }
        closedir($dh);
    }
}

function getSchemeAndHost()
{
    // Kiểm tra thông tin tiêu đề cài đặt proxy ngược
    $request_scheme = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? 'http';
    $request_host = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? '';

    // Nếu không có tiêu đề proxy ngược thì tiêu đề HTTP tiêu chuẩn sẽ được sử dụng
    if (empty($request_host)) {
        $request_scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $request_host = $_SERVER['HTTP_HOST'] ?? '';
    }

    // Nếu vẫn không lấy được tên miền, hãy sử dụng biến máy chủ thay thế
    if (empty($request_host)) {
        $request_host = $_SERVER['SERVER_NAME'] ?? 'localhost';

        // Nếu số cổng được sử dụng (cổng không chuẩn), hãy thêm số cổng
        $port = $_SERVER['SERVER_PORT'] ?? '';
        if (($request_scheme === 'https' && $port !== '443') || ($request_scheme === 'http' && $port !== '80')) {
            $request_host .= ':' . $port;
        }
    }

    // Xây dựng và trả lại sơ đồ vàhost
    return [$request_scheme, $request_host];
}

?>
