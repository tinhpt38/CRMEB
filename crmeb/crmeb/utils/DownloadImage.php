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


use app\services\other\UploadService;
use crmeb\exceptions\AdminException;
use think\facade\Config;
use think\Image;

/**
 * Tải hình ảnh về địa phương
 * Class DownloadImage
 * @package crmeb\utils
 * @method $this thumb(bool $thumb) Có tạo hình thu nhỏ hay không
 * @method $this thumbWidth(int $thumbWidth) Chiều rộng hình thu nhỏ
 * @method $this thumHeight(int $thumHeight) Chiều rộng hình thu nhỏ
 * @method $this path(int $path) vị trí lưu trữ
 */
class DownloadImage
{
    //Có tạo hình thu nhỏ hay không
    protected $thumb = false;
    //Chiều rộng hình thu nhỏ
    protected $thumbWidth = 300;
    //Chiều cao hình thu nhỏ
    protected $thumHeight = 300;
    //vị trí lưu trữ
    protected $path = 'attach';

    /**
     * @var string[]
     */
    protected $rules = ['thumb', 'thumbWidth', 'thumHeight', 'path'];

    /**
     * Tải xuống phần mở rộng hình ảnh
     * @param string $url
     * @param string $ex
     * @return array|string[]
     */
    public function getImageExtname($url = '', $ex = 'jpg')
    {
        $_empty = ['file_name' => '', 'ext_name' => $ex];
        if (!$url) return $_empty;
        if (strpos($url, '?')) {
            $_tarr = explode('?', $url);
            $url = trim($_tarr[0]);
        }
        $arr = explode('.', $url);
        if (!is_array($arr) || count($arr) <= 1) return $_empty;
        $ext_name = trim($arr[count($arr) - 1]);
        $ext_name = !$ext_name ? $ex : $ext_name;
        return ['file_name' => md5($url) . '.' . $ext_name, 'ext_name' => $ext_name];
    }

    /**
     * Tải hình ảnh
     * @param string $url
     * @param string $name
     * @param int $upload_type
     * @return mixed
     */
    public function downloadImage(string $url, $name = '')
    {
        if (!$name) {
            //TODO Lấy tên file cần tải
            $downloadImageInfo = $this->getImageExtname($url);
            $ext = $downloadImageInfo['ext_name'];
            $name = $downloadImageInfo['file_name'];
            if (!$name) throw new AdminException('Hình ảnh được tải lên không tồn tại');
        } else {
            $ext = $this->getImageExtname($name)['ext_name'];
        }
        if (!in_array($ext, Config::get('upload.fileExt'))) {
            throw new AdminException('Lỗi định dạng');
        }
        if (strstr($url, 'http://') === false && strstr($url, 'https://') === false) {
            $url = 'http:' . $url;
        }
        $url = str_replace('https://', 'http://', $url);
        if ($this->path == 'attach') {
            $date_dir = date('Y') . DIRECTORY_SEPARATOR . date('m') . DIRECTORY_SEPARATOR . date('d');
            $to_path = $this->path . '/' . $date_dir;
        } else {
            $to_path = $this->path;
        }
        $upload = UploadService::init(1);
        if (!file_exists($upload->uploadDir($to_path) . '/' . $name)) {
            ob_start();
            readfile($url);
            $content = ob_get_contents();
            ob_end_clean();
            $size = strlen(trim($content));
            if (!$content || $size <= 2) throw new AdminException('Việc thu thập luồng hình ảnh không thành công');
            if ($upload->to($to_path)->down($content, $name) === false) {
                throw new AdminException('Tải xuống hình ảnh không thành công');
            }
            $imageInfo = $upload->getDownloadInfo();
            $path = $imageInfo['dir'];
            if ($this->thumb) {
                Image::open(root_path() . 'public' . $path)->thumb($this->thumbWidth, $this->thumHeight)->save(root_path() . 'public' . $path);
                $this->thumb = false;
            }
        } else {
            $path = '/uploads/' . $to_path . '/' . $name;
            $imageInfo['name'] = $name;
        }
        $date['path'] = $path;
        $date['name'] = $imageInfo['name'];
        $date['size'] = $imageInfo['size'] ?? '';
        $date['mime'] = $imageInfo['type'] ?? '';
        $date['image_type'] = 1;
        $date['is_exists'] = false;
        return $date;
    }

    /**
     * @param $name
     * @param $arguments
     * @return $this
     */
    public function __call($name, $arguments)
    {
        if (in_array($name, $this->rules)) {
            if ($name === 'path') {
                $this->{$name} = $arguments[0] ?? 'attach';
            } else {
                $this->{$name} = $arguments[0] ?? null;
            }
            return $this;
        } else {
            throw new \RuntimeException('Method does not exist' . __CLASS__ . '->' . $name . '()');
        }
    }

}
