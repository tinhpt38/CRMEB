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


use crmeb\services\CacheService;
use think\facade\Config;
use think\Response;

/**
 * Class Captcha
 * @package crmeb\utils
 */
class Captcha
{
    // Ví dụ về hình ảnh mã xác minh
    private $im = null;
    // Màu phông chữ của mã xác minh
    private $color = null;
    // Bộ ký tự mã xác minh
    protected $codeSet = '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY';
    // Thời gian hết hạn của mã xác minh（s）
    protected $expire = 1800;
    // Sử dụng mã xác minh của Trung Quốc
    protected $useZh = false;
    // Chuỗi mã xác minh tiếng Trung
    protected $zhSet = 'Chúng tôi tin rằng khi làm được sẽ huy động được nhân dân ở mọi lứa tuổi phát huy lẽ phải của người lao động. Điều này sẽ dẫn tới sự tiến bộ của người dân trong nước. Điều này là do những người ở cấp độ thấp hơn không giống nhau. Hai chính sách, ba tốt, mười cuộc chiến và lộ trình hợp tác phản nông thảo luận về kết quả của hai điều mới, tập trung vào việc bồi dưỡng nhân tài và tập trung vào các yếu tố bên trong và bên ngoài. Người đứng đầu tổ quản lý đề xuất 5 giải pháp cứu rừng chỉ biết có 4 con đường. Tuy nhiên, thông tin chung tương đối yếu và lỗ hổng công cộng dẫn đến dòng người tiếp nhận và vấn đề dầu thô bay ra được đặt ra để chỉ ra rằng khu vực xây dựng đang hoạt động và công chúng rất có học thức. Bằng cách này, Chang Shiqiang và Jitu Shao đã thiết lập một hệ thống chung và thống nhất. Cần đảm bảo rằng ủy ban bảo quản nhiệt chịu trách nhiệm chuyển đổi và quản lý. Nó sẽ được sửa chữa để xác định những con voi bị bệnh. Sáu loại đèn cũ sẽ được sửa chữa. Đai an toàn sẽ được bổ sung vào mỗi mùa đông. Gió sẽ trở về Nanguang. Nhà máy phải thông suốt từ biên giới đến Wanxian. Nhà máy phải bỏ biển bàn giao lại quyền, giấy chứng nhận thanh thiếu niên của trẻ em còn thấp. Tám quy tắc kiểm tra đã gần đến cổng. Sắt cần phải đi qua binh quận để đông đặc lại và loại bỏ. Hệ thống chất lỏng chính xác và bảng giảm kích thước bị hỏng. Công nghệ này nhằm loại bỏ tiềm năng từ trường đáy. Đó là một ý tưởng tốt để đi đến làng Shenhe. Làm thế nào để trồng thủ đô để giúp đỡ đôi mắt của nhà vua, cô nắm lấy cây con phó tướng nói về các nguồn phóng xạ thực phẩm xung quanh gây ra axit cũ nhưng đủ nét ngắn chiếc nhẫn rơi xuống chân đầu tiên làn sóng chân Chengfen Jianfu cá với cuộc kiểm tra phụ thuộc vào sự mất mát của người đàn ông đầy đủ để thúc đẩy vi khuẩn nhánh và giáo viên bảo vệ đá nâng con dấu cát siêu âm của Qu Chunyuan để thay thế quá mô hình giảm nghèo Yang Yangjiang phân tích mu gỗ bóng Chao Trường Y gạo cổ Song Tingwei mất trạm trượt khác Wei Zigu vừa viết Liu Weiluo Fan Gong Một phần của công việc bạn bè vật phẩm giới hạn Yu tua lại Chuang Lv Yu để Gu Yuan giúp Chu Pi Bo Bạn chiếm giữ vòng tròn độc chết Wei Ji huấn luyện và kiểm soát kích thích cuộc gọi đám mây lẫn nhau và crack hạt hạt thực hành cắm thép hàng đầu chính sách đôi ở sai nền tảng hút tắc nghẽn nên inch lá chắn muộn lụa nữ rải rác công việc Zhu pro-bệnh viện lạnh lùng và rõ ràng đạn sai kinh doanh nghệ thuật thị giác phá hủy phiên bản Lie Zero ánh sáng máu thiếu thời gian kiểm tra máy bơm phải Fuchen lao xuống đất, anh hùng từ tính Li Wangpan dường như bị mắc kẹt, Gong Yizhou cất cánh và giao cho phía nô lệ chỗ chạy, vung ngôi sao cảm ứng và đưa ngôi sao giành chiến thắng. Chờ cho đến khi kết thúc cuộc rút lui cát co rút của Nga Chen chiến đấu cho đứa trẻ bị buộc phải xoắn rãnh thuế lộn ngược và giữ gánh nặng vẫn ah thẻ thanh tươi giới thiệu thô khoan đuổi theo chân yếu sợ bột muối Yin Feng sương mù vương miện Bing Phố Laibei tỏa ruột Fu Ji xâm nhập Rui giật mình bóp giây treo hoa mộc lan đường thánh lõm gốm từ muộn tằm tỷ khoảnh khắc Kang Zunmu được đóng khung khoang vườn ra lệnh cho hương thịt anh nhà Minhui quên biên soạn và in ong vội vã mở rộng vết thương lộ ra cạnh lõi du lịch Rung động trung tâm năm khu, rất nhanh, sáng, không đồng nhất, không giấy, thị trấn đêm, Cửu Lê, kẹp trụ, Lan Yinggou, Yi, Nho giáo, hơi nước, phốt pho, tinh thể cứng, cắm ea, đốt niềm vui, sắt, hàn nụ, Yong gạch, đổ ra hiệu suất carbon, gắn răng, nụ, gạch, tưới xiên, châu Âu, Xian Shun, lợn, ngoại thối, hãy thâm nhập công ty, rủi ro, xung, nụ cười, nếu đuôi chùm, doanh nghiệp bạo lực mạnh mẽ, bắp cải, sở, hàn, chữa bệnh, xanh, kéo trâu, nhuộm, mùa thu, rèn, ngọc, trị hạ, chóp nuôi tốt, Phi Châu Thăm thổi lưỡi đồng thay khách lăn, triệu hồi hạn hán, khai sáng trí óc, xuyên Tây Tạng, dám ra lệnh nứt, vỏ lò, than lưu huỳnh, đón đúc và dính, khám phá vận may, thưởng thức nghi lễ, mong nằm xuống sấm sét còn sót lại, kéo dài làn khói, thanh khiết, cày dần, chạy, trồng chậm, Lục Chi, thịnh vượng. Fangge Hanxi anh trai rửa chất thải và hấp thụ bụng Hu ghi lại gương người phụ nữ độc ác làng mỡ chà nguy hiểm ca ngợi chuông rung mã tranh luận thung lũng tre bán Xuqiao Ober lao xuống đường Ebi lưới chặn những tàn tích hoang dã lặng lẽ lên kế hoạch treo cổ thị trấn kiêu ngạo Sheng chịu đựng sự trợ giúp Zha coi chìa khóa trở lại Fu Qing tập hợp xung quanh Mo bận rộn khiêu vũ gặp cáp Gu Jiaoyang Lake hạt nhân âm thanh phá vỡ đèn để tránh lũ lụt cái chết trả lời tần số dũng cảm Huang Liuhajie Gannuo tướng tấn công đảo Xian Nong Ai là người nổi tiếng nhất thế giới? Các cổ phiếu đang bốc hơi và ngưng tụ, và chúng bị mắc kẹt trong hố đen. Bạn đang ở trong hố tối. You Song Arc thật lố bịch. Người ta kể rằng vào thời nhà Đường, tất cả những kẻ cướp thân cây clo cằn cỗi cũng trung thành ca hát để bắt ổ khóa, đặc biệt là Wu Zhidan đã cho phép tù nhân chăn nuôi nổi loạn chạm vào rỉ sét và quét kính.';
    // Sử dụng hình nền
    protected $useImgBg = false;
    // Kích thước phông chữ mã xác minh(px)
    protected $fontSize = 25;
    // Có nên vẽ đường cong nhầm lẫn không
    protected $useCurve = false;
    // Có thêm tiếng ồn hay không
    protected $useNoise = true;
    // Chiều cao hình ảnh mã xác minh
    protected $imageH = 0;
    // Chiều rộng hình ảnh mã xác minh
    protected $imageW = 0;
    // Số chữ số mã xác minh
    protected $length = 4;
    // Phông chữ mã xác minh, không đặt thu thập ngẫu nhiên
    protected $fontttf = '';
    // màu nền
    protected $bg = [243, 251, 254];
    //Mã xác minh số học
    protected $math = false;
    //Mã xác minh
    protected $generator;

    /**
     * Tham số cài đặt phương pháp kiến ​​trúc
     * Captcha constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->length = $config['length'] ?? Config::get('captcha.length', $this->length);
        $this->imageW = $config['imageW'] ?? $this->imageW;
        $this->imageH = $config['imageH'] ?? $this->imageH;
        $this->useCurve = $config['useCurve'] ?? $this->useCurve;
        $this->fontSize = $config['fontSize'] ?? $this->fontSize;
        $this->useImgBg = $config['useImgBg'] ?? $this->useImgBg;
        $this->useZh = $config['useZh'] ?? $this->useZh;
        $this->expire = $config['expire'] ?? $this->expire;
        $this->math = $config['math'] ?? $this->math;
        $this->zhSet = $config['zhSet'] ?? $this->zhSet;
        $this->codeSet = $config['codeSet'] ?? $this->codeSet;
    }

    /**
     * Tạo mã xác minh
     * @return array
     * @throws Exception
     */
    public function generate(): array
    {
        $bag = '';

        if ($this->math) {
            $this->useZh = false;

            $x = random_int(10, 30);
            $y = random_int(1, 9);
            $bag = "{$x} + {$y} = ";
            $key = $x + $y;
            $key .= '';
        } else {
            if ($this->useZh) {
                $characters = preg_split('/(?<!^)(?!$)/u', $this->zhSet);
            } else {
                $characters = str_split($this->codeSet);
            }

            for ($i = 0; $i < $this->length; $i++) {
                $bag .= $characters[rand(0, count($characters) - 1)];
            }

            $key = mb_strtolower($bag, 'UTF-8');
        }

        $hash = password_hash($key, PASSWORD_BCRYPT, ['cost' => 10]);

        $generator = [
            'value' => $bag,
            'key' => $hash,
        ];
        CacheService::set('captcha_' . $key, $generator, $this->expire);
        return $generator;
    }

    /**
     * Xác minh rằng mã xác minh là chính xác
     * @access public
     * @param string $code Mã xác minh người dùng
     * @return bool Mã xác minh người dùng có đúng không?
     */
    public function check(string $code): bool
    {
        $code = mb_strtolower(trim($code), 'UTF-8');
        $name = 'captcha_' . $code;
        if (!CacheService::has($name) || !($generator = CacheService::get($name))) {
            return false;
        }
        $key = $generator['key'] ?? '';
        $res = password_verify($code, $key);

        if ($res) {
            CacheService::delete($name);
        }

        return $res;
    }

    /**
     * Mã xác minh đầu ra
     * @param array|null $generator
     * @return $this
     */
    public function create(array $generator = null): Response
    {
        if (!$generator) {
            $generator = $this->generate();
        }

        // Chiều rộng hình ảnh(px)
        $this->imageW || $this->imageW = $this->length * $this->fontSize * 1.5 + $this->length * $this->fontSize / 2;
        // Hình ảnh cao(px)
        $this->imageH || $this->imageH = $this->fontSize * 2.5;
        // Tạo một bức tranh $this->imageW x $this->imageH hình ảnh
        $this->im = imagecreate($this->imageW, $this->imageH);
        // đặt nền
        imagecolorallocate($this->im, $this->bg[0], $this->bg[1], $this->bg[2]);

        // Phông chữ mã xác minh màu ngẫu nhiên
        $this->color = imagecolorallocate($this->im, mt_rand(1, 150), mt_rand(1, 150), mt_rand(1, 150));

        //TODO Để được thử nghiệm
        // Sử dụng phông chữ ngẫu nhiên cho mã xác minh
        $ttfPath = dirname(app()->getThinkPath(), 2) . DS . 'think-captcha' . DS . 'assets/' . ($this->useZh ? 'zhttfs' : 'ttfs') . '/';
        if (empty($this->fontttf)) {
            $dir = dir($ttfPath);
            $ttfs = [];
            while (false !== ($file = $dir->read())) {
                if ('.' != $file[0] && substr($file, -4) == '.ttf') {
                    $ttfs[] = $file;
                }
            }
            $dir->close();
            $this->fontttf = $ttfs[array_rand($ttfs)];
        }


        $fontttf = $ttfPath . $this->fontttf;

        if ($this->useImgBg) {
            $this->background();
        }

        if ($this->useNoise) {
            // Tiếng ồn sơn
            $this->writeNoise();
        }
        if ($this->useCurve) {
            // vẽ đường giao thoa
            $this->writeCurve();
        }
        // rút mã xác minh
        $text = $this->useZh ? preg_split('/(?<!^)(?!$)/u', $generator['value']) : str_split($generator['value']); // Mã xác minh

        foreach ($text as $index => $char) {

            $x = $this->fontSize * ($index + 1) * mt_rand(1.2, 1.6) * ($this->math ? 1 : 1.5);
            $y = $this->fontSize + mt_rand(10, 20);
            $angle = $this->math ? 0 : mt_rand(-40, 40);

            imagettftext($this->im, $this->fontSize, $angle, $x, $y, $this->color, $fontttf, $char);
        }

        ob_start();
        // hình ảnh đầu ra
        imagepng($this->im);
        $content = ob_get_clean();
        imagedestroy($this->im);
        return response($content, 200, ['Content-Length' => strlen($content)])->contentType('image/png');
    }

    /**
     * Vẽ đường cong hàm sin ngẫu nhiên gồm hai đường nối với nhau dưới dạng đường giao thoa(Bạn có thể thay đổi nó thành một hàm đường cong đẹp hơn)
     *
     *      Tại sao bạn quên viết các công thức toán học ở trường trung học?
     * Phân tích biểu thức hàm sin：y=Asin(ωx+φ)+b
     *      Ảnh hưởng của từng giá trị hằng số trên đồ thị hàm số:
     *A: Xác định giá trị cực đại (tức là bội số của độ giãn và nén theo chiều dọc)
     *b: Biểu thị mối quan hệ vị trí hoặc khoảng cách di chuyển theo chiều dọc của dạng sóng trên trục Y (cộng hoặc trừ)
     * φ: Xác định mối quan hệ giữa dạng sóng và vị trí trục X hoặc khoảng cách di chuyển ngang (trừ trái cộng phải)
     * ω: Khoảng thời gian xác định (khoảng thời gian dương tối thiểuT=2π/∣ω∣）
     *
     */
    protected function writeCurve(): void
    {
        $px = $py = 0;

        // phần trước của đường cong
        $A = mt_rand(1, $this->imageH / 2); // biên độ
        $b = mt_rand(-$this->imageH / 4, $this->imageH / 4); // YĐộ lệch hướng trục
        $f = mt_rand(-$this->imageH / 4, $this->imageH / 4); // XĐộ lệch hướng trục
        $T = mt_rand($this->imageH, $this->imageW * 2); // xe đạp
        $w = (2 * M_PI) / $T;

        $px1 = 0; // Vị trí bắt đầu đường cong abscissa
        $px2 = mt_rand($this->imageW / 2, $this->imageW * 0.8); // Vị trí cuối đường cong abscissa

        for ($px = $px1; $px <= $px2; $px = $px + 1) {
            if (0 != $w) {
                $py = $A * sin($w * $px + $f) + $b + $this->imageH / 2; // y = Asin(ωx+φ) + b
                $i = (int)($this->fontSize / 5);
                while ($i > 0) {
                    imagesetpixel($this->im, $px + $i, $py + $i, $this->color); // đây(while)Hiệu suất vẽ pixel trong vòng lặp tốt hơn nhiều so với vẽ pixel cùng lúc bằng cách sử dụng cỡ chữ của imagettftext và chuỗi hình ảnh (không sử dụng vòng lặp while này).
                    $i--;
                }
            }
        }

        // phần sau của đường cong
        $A = mt_rand(1, $this->imageH / 2); // biên độ
        $f = mt_rand(-$this->imageH / 4, $this->imageH / 4); // XĐộ lệch hướng trục
        $T = mt_rand($this->imageH, $this->imageW * 2); // xe đạp
        $w = (2 * M_PI) / $T;
        $b = $py - $A * sin($w * $px + $f) - $this->imageH / 2;
        $px1 = $px2;
        $px2 = $this->imageW;

        for ($px = $px1; $px <= $px2; $px = $px + 1) {
            if (0 != $w) {
                $py = $A * sin($w * $px + $f) + $b + $this->imageH / 2; // y = Asin(ωx+φ) + b
                $i = (int)($this->fontSize / 5);
                while ($i > 0) {
                    imagesetpixel($this->im, $px + $i, $py + $i, $this->color);
                    $i--;
                }
            }
        }
    }

    /**
     * Vẽ các điểm linh tinh
     * Viết chữ hoặc số bằng các màu khác nhau lên hình
     */
    protected function writeNoise(): void
    {
        $codeSet = '2345678abcdefhijkmnpqrstuvwxyz';
        for ($i = 0; $i < 10; $i++) {
            //Màu nhiễu
            $noiseColor = imagecolorallocate($this->im, mt_rand(150, 225), mt_rand(150, 225), mt_rand(150, 225));
            for ($j = 0; $j < 5; $j++) {
                // Tiếng ồn sơn
                imagestring($this->im, 5, mt_rand(-10, $this->imageW), mt_rand(-10, $this->imageH), $codeSet[mt_rand(0, 29)], $noiseColor);
            }
        }
    }

    /**
     * Vẽ hình nền
     * Lưu ý: Nếu hình ảnh đầu ra mã xác minh tương đối lớn sẽ chiếm nhiều tài nguyên hệ thống hơn.
     */
    protected function background(): void
    {
        $path = dirname(app()->getThinkPath(), 2) . DS . 'think-captcha' . DS . '/assets/bgs/';
        $dir = dir($path);

        $bgs = [];
        while (false !== ($file = $dir->read())) {
            if ('.' != $file[0] && substr($file, -4) == '.jpg') {
                $bgs[] = $path . $file;
            }
        }
        $dir->close();

        $gb = $bgs[array_rand($bgs)];

        list($width, $height) = @getimagesize($gb);
        // Resample
        $bgImage = @imagecreatefromjpeg($gb);
        @imagecopyresampled($this->im, $bgImage, 0, 0, 0, 0, $this->imageW, $this->imageH, $width, $height);
        @imagedestroy($bgImage);
    }
}
