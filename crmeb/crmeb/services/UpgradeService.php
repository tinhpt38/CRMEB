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
namespace crmeb\services;

class UpgradeService extends FileService
{
    //Yêu cầu tên miền
    public static $domain = 'http://shop.crmeb.net/';
    //Cập nhật thông tin website kịp thời
    public static $updatewebinfourl = 'index.php/admin/server.upgrade_api/updatewebinfo.html';
    //Địa chỉ giao diện công cộng Nhận số phiên bản
    public static $isNowVersionUrl = 'index.php/admin/server.upgrade_api/now_version.html';
    //Địa chỉ giao diện công cộng Nhận chi tiết phiên bản
    public static $isVersionInfo = 'index.php/admin/server.upgrade_api/version_info.html';
    //Địa chỉ giao diện công cộng Nhận danh sách phiên bản lịch sử
    public static $isList = 'index.php/admin/server.upgrade_api/get_version_list.html';
    //Địa chỉ giao diện công cộng Viết thông tin phiên bản cập nhật
    public static $isInsertLog = 'index.php/admin/server.upgrade_api/set_upgrade_info.html';
    //Địa chỉ giao diện công cộng Nhận tất cả các phiên bản lớn hơn phiên bản hiện tại
    public static $isNowVersion = 'index.php/admin/server.upgrade_api/get_now_version.html';
    //Địa chỉ giao diện công cộng Cập nhật thông tin URL
    protected static $UpdateWeBinfo = 'index.php/admin/server.upgrade_api/updatewebinfo.html';
    //Địa chỉ giao diện công cộng Nhận bao nhiêu phiên bản chưa được cập nhật
    public static $NewVersionCount = 'index.php/admin/server.upgrade_api/new_version_count.html';
    //Địa chỉ giao diện công cộng xác định xem có sự cho phép hay không. Trả về 1 khi được phép, 0 khi không được phép.
    protected static $Isauth = 'index.php/admin/server.upgrade_api/isauth.html';
    //Thanh toán riêng
    private static $seperater = "{&&}";

    //Cập nhật thông tin URL
    public function snyweninfo($serverweb)
    {
        return self::request_post(self::$UpdateWeBinfo, $serverweb);
    }

    //Xác định xem có sự cho phép hay không. Trả về 1 khi được phép, 0 nếu không được phép.
    public function isauth()
    {
        return self::request_post(self::$Isauth);
    }

    /*
     *Nhận mã thông báo ip và tạo thời gian hiện tại và thời gian hết hạn
     * @param string ip
     * @param int $valid_peroid Thời hạn sử dụng 15 ngày
     */
    public static function get_token($ip = '', $valid_peroid = 1296000)
    {
        $request = app('request');
        if (empty($ip)) $ip = $request->ip();
        $to_ken = $request->domain() . self::$seperater . $ip . self::$seperater . time() . self::$seperater . (time() + $valid_peroid) . self::$seperater;
        $token = self::enCode($to_ken);
        return $token;
    }

    private static function getRet($msg, $code = 400)
    {
        return ['msg' => $msg, 'code' => $code];
    }

    /**
     *
     * @param string $url
     * @param array $post_data
     */
    public static function start()
    {
        $pach = app()->getRootPath() . 'version';
        $request = app('request');
        if (!file_exists($pach)) return self::getRet($pach . 'Thiếu file nâng cấp, vui lòng liên hệ với quản trị viên');
        $version = @file($pach);
        if (!isset($version[0])) return self::getRet('Không thể lấy được');
        $lv = self::request_post(self::$isNowVersionUrl, ['token' => self::get_token($request->ip())]);
        if (isset($lv['code']) && $lv['code'] == 200)
            $version_lv = isset($lv['data']['version']) && $lv['data']['version'] ? $lv['data']['version'] : false;
        else
            return isset($lv['msg']) ? self::getRet($lv['msg']) : self::getRet('Không thể lấy được');
        if ($version_lv === false) return self::getRet('Không thể lấy được');
        if (strstr($version[0], '=') !== false) {
            $version = explode('=', $version[0]);
            if ($version[1] != $version_lv) {
                return self::getRet($version_lv, 200);
            }
        }
        return self::getRet('Không thể lấy được');
    }

    public static function getVersion()
    {
        $pach = app()->getRootPath() . '.version';
        if (!file_exists($pach)) return self::getRet($pach . 'Thiếu file nâng cấp, vui lòng liên hệ với quản trị viên');
        $version = @file($pach);
        if (!isset($version[0]) && !isset($version[1])) return self::getRet('Không thể lấy được');
        $arr = [];
        foreach ($version as $val) {
            list($k, $v) = explode('=', $val);
            $arr[$k] = $v;
        }
        return self::getRet($arr, 200);
    }

    /**
     * Mô phỏng bài đăng để thực hiện yêu cầu url
     * @param string $url
     * @param array $post_data
     */
    public static function request_post($url = '', $post_data = array())
    {
        if (strstr($url, 'http') === false) $url = self::$domain . $url;
        if (empty($url)) {
            return false;
        }
        if (!isset($post_data['token'])) $post_data['token'] = self::get_token();
        $o = "";
        foreach ($post_data as $k => $v) {
            $o .= "$k=" . urlencode($v) . "&";
        }
        $post_data = substr($o, 0, -1);
        $postUrl = $url;
        $curlPost = $post_data;
        $ch = curl_init();//khởi tạocurl
        curl_setopt($ch, CURLOPT_URL, $postUrl);//Tìm nạp trang web được chỉ định
        curl_setopt($ch, CURLOPT_HEADER, 0);//cài đặtheader
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//Kết quả bắt buộc phải là một chuỗi và xuất ra màn hình
        curl_setopt($ch, CURLOPT_POST, 1);//postPhương thức gửi
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $data = curl_exec($ch);//chạycurl
        curl_close($ch);
        if ($data) {
            $data = json_decode($data, true);
        }
        return $data;
    }

    /**
     * Xác minh tập tin từ xa tồn tại và tải xuống
     * @param string $url đường dẫn tập tin
     * @param string $savefile lưu địa chỉ
     */
    public static function check_remote_file_exists($url, $savefile)
    {
        $url = self::$domain . 'public' . DS . 'uploads' . DS . 'upgrade' . DS . $url;
        $url = str_replace('\\', '/', $url);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        // Gửi yêu cầu
        $result = curl_exec($curl);
        $found = false;
        // Nếu yêu cầu không được gửi thì không thành công
        if ($result !== false) {
            // Sau đó kiểm tra xem mã phản hồi http có200
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($statusCode == 200) {
                curl_close($curl);

                $fileservice = new self;
                //Tải tập tin xuống
                $zip = $fileservice->downRemoteFile($url, $savefile);
                if ($zip['error'] > 0) return false;
                if (!isset($zip['save_path']) && empty($zip['save_path'])) return false;
                if (!file_exists($zip['save_path'])) return false;
                return $zip['save_path'];
            }
        }
        curl_close($curl);
        return $found;
    }

    /**
     * mã hóa phổ quát
     * @param String $string Chuỗi cần được mã hóa
     * @param String $skey mã hóaEKY
     * @return String
     */
    private static function enCode($string = '', $skey = 'fb')
    {
        $skey = array_reverse(str_split($skey));
        $strArr = str_split(base64_encode($string));
        $strCount = count($strArr);
        foreach ($skey as $key => $value) {
            $key < $strCount && $strArr[$key] .= $value;
        }
        return str_replace('=', 'O0O0O', join('', $strArr));
    }

    /**
     * Loại bỏ các dòng xuống dòng, loại bỏ các khoảng trắng, loại bỏ các nguồn cấp dòng, loại bỏtab
     * @param String $str Các chuỗi cần loại bỏ
     * @return String
     */
    public static function replace($str)
    {
        return trim(str_replace(array("\r", "\n", "\t"), '', $str));
    }
}
