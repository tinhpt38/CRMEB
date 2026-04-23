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

/**
 * Dịch vụ nâng cấp
 * Class UpgradeOpenApiService
 */
class UpgradeOpenApiService
{
    const BASE_URL = 'http://shop.crmeb.net/';

    /**
     * Appid do hệ thống cung cấp, không sửa đổi nó
     * @var string
     */
    protected $appid = 'ze7x9rxsv09l6pvsyo';

    /**
     * @var array
     */
    protected $baseHeader = [];

    /**
     * Người xây dựng
     */
    public function __construct()
    {
        $this->baseHeader = [
            'X-APP-ID'       => $this->appid,
            'System-Version' => get_crmeb_version(),
            'Auth-Host'      => request()->host(),
            'Auth-Label'     => 19
        ];
    }

    /**
     * Giao diện yêu cầu
     * @param string $url
     * @param string $method
     * @param array $data
     * @param array $header
     * @return array|mixed
     * @throws \Exception
     */
    public function client(string $url, string $method = 'POST', array $data = [], array $header = [])
    {
        $baseHeader = array_merge($this->baseHeader, $header);
        if ($baseHeader) {
            $header = [];
            foreach ($baseHeader as $key => $value) {
                $header[] = $key . ':' . $value;
            }
        }

        $res = HttpService::request(self::BASE_URL . $url, $method, $data, $header);

        $res = json_decode($res, true);
        if (json_last_error()) {
            throw new \Exception(json_last_error_msg());
        }

        if (isset($res['status']) && $res['status'] != 200) {
            throw new \Exception($res['msg']);
        }

        return $res['data'] ?? [];
    }

    /**
     * Nhận danh sách nâng cấp
     * @param int $page
     * @param int $limit
     * @return array|mixed
     * @throws \Exception
     */
    public function getUpgradeVersionList(int $page = 1, int $limit = 10)
    {
        return $this->client('api/open/version_list', 'GET', ['page' => $page, 'limit' => $limit]);
    }

    /**
     * Lưu nhật ký nâng cấp
     * @param array $data
     * @return array|mixed
     * @throws \Exception
     */
    public function saveUpgradeLog(array $data)
    {
        return $this->client('api/open/upgrade_log', 'POST', $data);
    }

    /**
     * Nhận chi tiết nâng cấp được chỉ định
     * @param int $id
     * @return array|mixed
     * @throws \Exception
     */
    public function getUpgradeVersionInfo(int $id)
    {
        return $this->client('api/open/version_info', 'GET', ['id' => $id]);
    }

    /**
     * Số lần nâng cấp có thể được thực hiện sau khi có được phiên bản hiện tại
     * @return array|mixed
     * @throws \Exception
     */
    public function getUpgradeNewVersionCount()
    {
        return $this->client('api/open/new_version_count', 'GET');
    }

    /**
     * Nhận quyền nâng cấp cho tên miền hiện tại
     * @return array|mixed
     * @throws \Exception
     */
    public function getUpgradeVersionAuth()
    {
        return $this->client('api/open/get_version_auth', 'GET');
    }

    /**
     * Kiểm tra xem tập tin từ xa có tồn tại không,và tải xuống
     * @param $url
     * @param $savefile
     * @return false|mixed
     */
    public function checkRemoteFileExists(string $url, string $savefile)
    {
        //Địa chỉ oss của Alibaba Cloud không thể được phát hiện và tải xuống trực tiếp
        if (strpos($url, 'aliyuncs.com') !== false || strpos($url, 'oss.') !== false) {
            return FileService::downRemoteFile($url, $savefile);
        }

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
                $zip = FileService::downRemoteFile($url, $savefile);
                if ($zip['error'] > 0)
                    return false;
                if (!isset($zip['save_path']) && empty($zip['save_path']))
                    return false;
                if (!file_exists($zip['save_path']))
                    return false;
                return $zip['save_path'];
            }
        }
        curl_close($curl);
        return $found;
    }
}