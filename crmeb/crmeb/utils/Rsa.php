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

namespace crmeb\utils;

use think\exception\ValidateException;

/**
 * Class Rsa
 * @author Chờ gió tới
 * @email 136327134@qq.com
 * @date 2023/5/16
 * @package crmeb\utils
 */
class Rsa
{
    /**
     * @var string
     */
    protected $publicKey;

    /**
     * @var string
     */
    protected $privateKey;

    /**
     * @var string
     */
    protected $basePath;

    /**
     * Nhận hồ sơ chứng chỉ
     * @param $publicKey
     * @param $privateKey
     */
    public function __construct(string $publicKey = 'cert_public_password.key', string $privateKey = 'cert_private_password.key')
    {
        $this->basePath = app()->getRootPath();
        if ($publicKey) {
            $this->publicKey = $this->basePath . $publicKey;
        }
        if ($privateKey) {
            $this->privateKey = $this->basePath . $publicKey;
        }
        if (!is_file($this->publicKey) || !is_file($this->privateKey)) {
            $this->exportOpenSSLFile();
        }
    }

    /**
     * @return false|string
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/5/16
     */
    public function getPublicKey()
    {
        if (!is_file($this->publicKey)) {
            $this->exportOpenSSLFile();
        }

        return file_get_contents($this->publicKey);
    }

    /**
     * Tạo chứng chỉ
     * @return bool
     */
    public function exportOpenSSLFile($passwork = null)
    {

        $publicKey = $privateKey = '';
        $dir = app()->getRootPath() . 'runtime/conf';
        $conf = 'openssl.cnf';
        if (!is_dir($dir)) {
            mkdir($dir, 0700);
        }
        if (!file_exists($conf)) {
            touch($dir . '/' . $conf);
        }

        //Cài đặt thông số
        $config = [
            "digest_alg" => "sha256",
            //Số byte 512 1024 2048 4096, v.v.
            "private_key_bits" => 1024,
            "config" => $dir . '/' . $conf,
            //Kiểu mã hóa
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ];

        //Tạo khóa riêng và khóa chung
        $res = openssl_pkey_new($config);
        if ($res == false) {
            //Tạo không thành công,Vui lòng kiểm tra xem tệp openssl.cnf có tồn tại không
            return false;
        }

        //Xuất khóa dưới dạng chuỗi được mã hóa PEM và đầu ra (được truyền bằng tham chiếu）。
        openssl_pkey_export($res, $privateKey, $passwork, $config);
        $publicKey = openssl_pkey_get_details($res);
        $publicKey = $publicKey["key"];

        //Tạo chứng chỉ
        $createPublicFileRet = file_put_contents($this->publicKey, $publicKey);
        $createPrivateFileRet = file_put_contents($this->privateKey, $privateKey);
        if (!($createPublicFileRet || $createPrivateFileRet)) {
            return false;
        }

        openssl_free_key($res);
        return true;
    }

    /**
     * Mã hóa dữ liệu
     * @param string $data
     * @param string|null $passwork
     * @return false|string
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/5/16
     */
    function privateEncrypt(string $data, string $passwork = null)
    {
        $encrypted = '';
        $pi_key = openssl_pkey_get_private(file_get_contents($this->privateKey), $passwork);//Hàm này có thể được sử dụng để xác định xem khóa riêng có khả dụng hay không và có thể trả về id tài nguyên Id tài nguyên
        // Độ dài mã hóa tối đa được phép là 117, được chia thành mã hóa phân đoạn
        $plainData = str_split($data, 100);//Số chữ số để tạo khóa 1024 bit key
        foreach ($plainData as $chunk) {
            $partialEncrypted = '';
            $encryptionOk = openssl_private_encrypt($chunk, $partialEncrypted, $pi_key);//Mã hóa khóa riêng
            if ($encryptionOk === false) {
                return false;
            }
            $encrypted .= $partialEncrypted;
        }

        $encrypted = base64_encode($encrypted);//Nội dung được mã hóa thường chứa các ký tự đặc biệt và yêu cầu chuyển đổi mã hóa. Khi truyền qua các URL giữa các mạng, hãy chú ý xem liệu mã hóa base64 có an toàn cho URL hay không.
        return $encrypted;
    }

    /**
     * RSAGiải mã khóa công khai(Nội dung được mã hóa bằng khóa riêng có thể được giải mã bằng khóa chung)
     * @param string $public_key khóa công khai
     * @param string $data Chuỗi mã hóa khóa riêng
     * @return string $decrypted Trả về chuỗi được giải mã
     * @author mosishu
     */
    function publicDecrypt(string $data)
    {
        $decrypted = '';
        $pu_key = openssl_pkey_get_public(file_get_contents($this->publicKey));//Chức năng này có thể được sử dụng để xác định xem khóa chung có sẵn hay không
        $plainData = str_split(base64_decode($data), 128);//Số chữ số để tạo khóa 1024 bit key
        foreach ($plainData as $chunk) {
            $str = '';
            $decryptionOk = openssl_public_decrypt($chunk, $str, $pu_key);//Giải mã khóa công khai
            if ($decryptionOk === false) {
                return false;
            }
            $decrypted .= $str;
        }
        return $decrypted;
    }

    /**
     * Giải mã khóa riêng
     * @param string $data
     * @return mixed
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/5/16
     */
    public function privateDecrypt(string $data)
    {
        if (!is_file($this->privateKey)) {
            $this->exportOpenSSLFile();
        }

        $res = openssl_private_decrypt(base64_decode($data), $decryptedData, file_get_contents($this->privateKey));

        if (false === $res) {
            throw new ValidateException('RSA:Giải mã không thành công');
        }

        return $decryptedData;
    }

}
