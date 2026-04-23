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

use Exception;

/**
 * Tính toán chữ ký
 * Class fileVerification
 * @package crmeb\utils
 */
class fileVerification
{
    public $path = "";
    public $fileValue = "";

    /**
     * Đường dẫn dự án
     * @param string $path
     * @return string
     * @throws Exception
     */
    public function getSignature(string $path): string
    {
        if (!is_dir($path) && !is_file($path)) {
            throw new Exception($path . " Không phải là một tập tin hoặc thư mục hợp lệ!");
        }

        $appPath = $path . DS . 'app';
        if (!is_dir($appPath)) {
            throw new Exception($appPath . " Không phải là một thư mục hợp lệ!");
        }

        $crmebPath = $path . DS . 'crmeb';
        if (!is_dir($crmebPath)) {
            throw new Exception($crmebPath . " Không phải là một thư mục hợp lệ!");
        }

        $this->path = $appPath;
        $this->getFileSignature($appPath);
        $this->path = $crmebPath;
        $this->getFileSignature($crmebPath);
        return md5($this->fileValue);
    }

    /**
     * Tính chữ ký
     * @param string $path
     * @return void
     * @throws Exception
     */
    public function getFileSignature(string $path)
    {
        if (!is_dir($path)) {
            $this->fileValue .= @md5_file($path);
        } else {
            if (!$dh = opendir($path)) throw new Exception($path . " File open failed!");
            while (($file = readdir($dh)) != false) {
                if ($file == "." || $file == "..") {
                    continue;
                } else {
                    $this->getFileSignature($path . DS . $file);
                }
            }
            closedir($dh);
        }
    }
}