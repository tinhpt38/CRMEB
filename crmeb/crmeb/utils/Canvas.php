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


/**
 * Class Canvas
 * @package crmeb\utils
 * @method $this setFileName(string $fileName) Đặt tên tập tin
 * @method $this setPath(string $path) Đặt đường dẫn lưu trữ
 * @method $this setImageType(string $imageType) Đặt loại hình ảnh
 * @method $this setBackgroundHeight(int $backgroundHeight) Đặt chiều cao nền
 * @method $this setBackgroundWidth(int $backgroundWidth) Đặt chiều rộng nền
 * @method $this setFontSize(int $fontSize) Đặt kích thước phông chữ
 * @method $this setFontColor($fontColor) Đặt màu phông chữ
 * @method $this setFontLeft(int $fontLeft) Đặt khoảng cách phông chữ sang trái
 * @method $this setFontTop(int $fontTop) Đặt khoảng cách phông chữ từ trên xuống
 * @method $this setFontText(string $fontText) Đặt văn bản
 * @method $this setFontPath(string $fontPath) Đặt đường dẫn tệp phông chữ
 * @method $this setFontAngle(int $fontAngle) Đặt góc phông chữ
 * @method $this setImageUrl(string $imageUrl) Đặt đường dẫn hình ảnh
 * @method $this setImageLeft(int $imageLeft) Đặt khoảng cách hình ảnh sang trái
 * @method $this setImageTop(int $imageTop) Đặt khoảng cách từ đầu hình ảnh
 * @method $this setImageRight(int $imageRight) Đặt khoảng cách hình ảnh sang trái
 * @method $this setImageStream(bool $imageStream) Đặt xem hình ảnh có phải là tập tin phát trực tuyến hay không
 * @method $this setImageBottom(int $imageBottom) Đặt khoảng cách giữa hình ảnh và đáy
 * @method $this setImageWidth(int $imageWidth) Đặt chiều rộng hình ảnh
 * @method $this setImageHeight(int $imageHeight) Đặt chiều cao hình ảnh
 * @method $this setImageOpacity(int $imageOpacity) Đặt độ trong suốt của hình ảnh
 */
class Canvas
{

    const FONT = 'statics/font/Alibaba-PuHuiTi-Regular.otf';

    /**
     * nền rộng
     * @var int
     */
    protected $backgroundWidth = 600;

    /**
     * nền cao
     * @var int
     */
    protected $backgroundHeight = 1000;

    /**
     * Loại hình ảnh
     * @var string
     */
    protected $imageType = 'jpeg';

    /**
     * lưu địa chỉ
     * @var string
     */
    protected $path = 'uploads/routine/';

    /**
     * tên tập tin
     * @var string
     */
    protected $fileName;

    /**
     * luật lệ
     * @var array
     */
    protected $propsRule = ['fileName', 'path', 'imageType', 'backgroundHeight', 'backgroundWidth'];

    /**
     * Tập dữ liệu phông chữ
     * @var array
     */
    protected $fontValue = [];

    /**
     * Phông chữ có thể được đặt theo mặc địnhvlaue
     * @var array
     */
    protected $defaultFontValue = [
        'fontSize' => 0,
        'fontColor' => '231,180,52',
        'fontLeft' => 0,
        'fontTop' => 0,
        'fontText' => '',
        'fontPath' => self::FONT,
        'fontAngle' => 0,
    ];

    protected $defaultFont;
    /**
     * Tập dữ liệu hình ảnh
     * @var array
     */
    protected $imageValue = [];

    /**
     * Hình ảnh có thể thiết lập thuộc tính
     * @var array
     */
    protected $defaultImageValue = [
        'imageUrl' => '',
        'imageLeft' => 0,
        'imageTop' => 0,
        'imageRight' => 0,
        'imageBottom' => 0,
        'imageWidth' => 0,
        'imageHeight' => 0,
        'imageOpacity' => 0,
        'imageStream' => false,
    ];

    /**
     * chính sự khởi tạo
     * @var self
     */
    protected static $instance;

    protected $defaultImage;

    protected function __construct()
    {
        $this->defaultImage = $this->defaultImageValue;
        $this->defaultFont = $this->defaultFontValue;
    }

    /**
     * Khởi tạo lớp này
     * @return Canvas
     */
    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Tạo một hình ảnh mới
     * @param string $file
     * @return array
     */
    public function createFrom(string $file): array
    {
        $file = str_replace('https', 'http', $file);
        $imagesize = getimagesize($file);
        $type = image_type_to_extension($imagesize[2], true);
        $canvas = NULL;
        switch ($type) {
            case '.png':
                $canvas = imagecreatefrompng($file);
                break;
            case '.jpg':
            case '.jpeg':
                $canvas = imagecreatefromjpeg($file);
                break;
            case '.gif':
                $canvas = imagecreatefromgif($file);
                break;
        }
        return [$canvas, $imagesize];

    }

    /**
     * Đặt phông chữ
     * @return $this
     */
    public function pushFontValue()
    {
        array_push($this->fontValue, $this->defaultFontValue);
        $this->defaultFontValue = $this->defaultFont;
        return $this;
    }

    /**
     * Đặt hình ảnh
     * @return $this
     */
    public function pushImageValue()
    {
        array_push($this->imageValue, $this->defaultImageValue);
        $this->defaultImageValue = $this->defaultImage;
        return $this;
    }

    /**
     * Tạo nền
     * @param int $w
     * @param int $h
     * @return false|resource
     */
    public function createTrueColor(int $w = 0, int $h = 0)
    {
        return imagecreatetruecolor($w ?: $this->backgroundWidth, $h ?: $this->backgroundHeight);
    }


    /**
     * Bắt đầu vẽ
     * @param bool $force Có ném ngoại lệ khi tạo lỗi hay không
     * @return string
     * @throws \Exception
     */
    public function starDrawChart(bool $force = false): string
    {
        try {
            $image = $this->createTrueColor();

            foreach ($this->imageValue as $item) {
                if ($item['imageUrl']) {
                    if ($item['imageStream']) {
                        $res = getimagesizefromstring($item['imageUrl']);
                        $mer = imagecreatefromstring($item['imageUrl']);
                    } else {
                        [$mer, $res] = $this->createFrom($item['imageUrl']);
                    }
                    if ($mer && $res) {
                        $scrW = $res[0] ?? 0;
                        $scrH = $res[1] ?? 0;
                        $imageWidth = $item['imageWidth'] ?: $scrW;
                        $imageHeight = $item['imageHeight'] ?: $scrH;
                        imagecopyresampled($image, $mer, $item['imageLeft'], $item['imageTop'], $item['imageRight'], $item['imageBottom'], $imageWidth, $imageHeight, $scrW, $scrH);
                        unset($scrW, $scrH, $imageWidth, $imageHeight, $res, $mer);
                    }

                }
            }

            foreach ($this->fontValue as $val) {
                if (!is_array($val['fontColor']))
                    $fontColor = explode(',', $val['fontColor']);
                else
                    $fontColor = $val['fontColor'];
                if (count($fontColor) < 3)
                    throw new \RuntimeException('fontColor Separation of thousand bits');
                [$r, $g, $b] = $fontColor;
                $fontColor = imagecolorallocate($image, $r, $g, $b);
                $val['fontLeft'] = $val['fontLeft'] < 0 ? $this->backgroundWidth - abs($val['fontLeft']) : $val['fontLeft'];
                $val['fontTop'] = $val['fontTop'] < 0 ? $this->backgroundHeight - abs($val['fontTop']) : $val['fontTop'];
                imagettftext($image, $val['fontSize'], $val['fontAngle'], $val['fontLeft'], $val['fontTop'], $fontColor, $val['fontPath'], $val['fontText']);
                unset($r, $g, $b, $fontColor);
            }
            if (is_null($this->fileName)) {
                $this->fileName = md5(time());
            }

            $strlen = stripos($this->path, 'uploads');
            $path = $this->path;
            if ($strlen !== false) {
                $path = substr($this->path, 8);
            }

            if (make_path($path, 4, true) === '') {
                throw new \RuntimeException('Không thể tạo thư mục, vui lòng kiểm tra quyền thư mục tải lên của bạn');
            }

            $save_file = $this->path . $this->fileName . '.' . $this->imageType;
            switch ($this->imageType) {
                case 'jpeg':
                case 'jpg':
                    imagejpeg($image, public_path().$save_file, 70);
                    break;
                case 'png':
                    imagepng($image, public_path().$save_file, 70);
                    break;
                case 'gif':
                    imagegif($image, public_path().$save_file, 70);
                    break;
                default:
                    throw new \RuntimeException('Incorrect type set:' . $this->imageType);
            }
            imagedestroy($image);

            return $save_file;
        } catch (\Throwable $e) {
            if ($force || $e instanceof \RuntimeException)
                throw new \Exception($e->getMessage());
            return '';
        }
    }

    /**
     * Magic access..
     *
     * @param $method
     * @param $args
     * @return $this
     */
    public function __call($method, $args): self
    {

        if (0 === stripos($method, 'set') && strlen($method) > 3) {
            $method = lcfirst(substr($method, 3));
        }

        $imageValueKes = array_keys($this->defaultImageValue);
        $fontValueKes = array_keys($this->defaultFontValue);

        if (in_array($method, $imageValueKes)) {
            $this->defaultImageValue[$method] = array_shift($args);
        }

        if (in_array($method, $fontValueKes)) {
            $this->defaultFontValue[$method] = array_shift($args);
        }

        if (in_array($method, $this->propsRule)) {
            $this->{$method} = array_shift($args);
        }

        return $this;
    }


}
