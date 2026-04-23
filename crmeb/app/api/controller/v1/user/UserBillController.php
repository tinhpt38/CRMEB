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
namespace app\api\controller\v1\user;


use app\Request;
use app\services\other\PosterServices;
use app\services\other\QrcodeServices;
use app\services\system\attachment\SystemAttachmentServices;
use app\services\system\config\SystemConfigServices;
use app\services\user\UserBillServices;
use app\services\user\UserBrokerageServices;
use app\services\user\UserMoneyServices;
use crmeb\services\app\MiniProgramService;
use app\services\other\UploadService;

/**
 * Loại hóa đơn
 * Class UserBillController
 * @package app\api\controller\user
 */
class UserBillController
{
    protected $services;

    /**
     * UserBillController constructor.
     * @param UserBillServices $services
     */
    public function __construct(UserBillServices $services)
    {
        $this->services = $services;
    }

    /**
     * Dữ liệu khuyến mãi Hoa hồng của ngày hôm qua Số tiền rút tích lũy Hoa hồng hiện tại
     * @param Request $request
     * @return mixed
     */
    public function commission(Request $request)
    {
        $uid = (int)$request->uid();
        return app('json')->success($this->services->commission($uid));
    }

    /**
     * Đơn hàng khuyến mãi
     * @param Request $request
     * @return mixed
     */
    public function spread_order(Request $request)
    {
        $orderInfo = $request->postMore([
            ['page', 1],
            ['limit', 20],
            ['category', 'now_money'],
            ['type', 'brokerage'],
        ]);
        $uid = (int)$request->uid();
        return app('json')->success($this->services->spread_order($uid, $orderInfo));
    }

    /**
     * Chi tiết hoa hồng khuyến mãi
     * @param Request $request
     * @param $type 0 Tất cả 1 Chi tiêu 2 Nạp tiền 3 Hoàn tiền 4 Rút tiền
     * @return mixed
     */
    public function spread_commission(Request $request, $type)
    {
        $uid = (int)$request->uid();
        $data = [];
        switch ($type) {
            case 0:
            case 1:
            case 2:
                /** @var UserMoneyServices $moneyService */
                $moneyService = app()->make(UserMoneyServices::class);
                $data = $moneyService->getMoneyList($uid, $type);
                break;
            case 3:
            case 4:
                /** @var UserBrokerageServices $brokerageService */
                $brokerageService = app()->make(UserBrokerageServices::class);
                $data = $brokerageService->getBrokerageList($uid, $type);
                break;
        }
        return app('json')->success($data);
    }

    /**
     * Tổng số tiền hoa hồng/rút tiền khuyến mãi
     * @param Request $request
     * @param $type 3 Hoa hồng 4 Rút tiền
     * @return mixed
     */
    public function spread_count(Request $request, $type)
    {
        $uid = (int)$request->uid();
        return app('json')->success(['count' => $this->services->spread_count($uid, $type)]);
    }


    /**
     * Phân phối tạo áp phích mã QR
     * @param Request $request
     * @return mixed
     */
    public function spread_banner(Request $request)
    {
        list($type) = $request->getMore([
            ['type', 2],
        ], true);
        $user = $request->user();
        $rootPath = app()->getRootPath();
        /** @var SystemConfigServices $systemConfigServices */
        $systemConfigServices = app()->make(SystemConfigServices::class);
        $spreadBanner = $systemConfigServices->getSpreadBanner() ?? [];
        $bannerCount = count($spreadBanner);
        if (!$bannerCount) return app('json')->fail('Chưa có áp phích');
        $routineSpreadBanner = [];
        foreach ($spreadBanner as $item) {
            $routineSpreadBanner[] = ['pic' => $item];
        }
        if ($type == 1) {
            $poster = $user['uid'] . '_' . $user['is_promoter'] . '_user_routine_poster_';
        } else {
            $poster = $user['uid'] . '_' . $user['is_promoter'] . '_user_wap_poster_';
        }
        /** @var SystemAttachmentServices $systemAttachment */
        $systemAttachment = app()->make(SystemAttachmentServices::class);
        /** @var QrcodeServices $qrCode */
        $qrCode = app()->make(QrcodeServices::class);
        $count = $systemAttachment->getCount([['name', 'LIKE', "$poster%"]]);
        if ($count) {
            $SpreadBanner = $systemAttachment->getLikeNameList($poster);
            //thay đổi tái tạo
            if ($bannerCount != count($SpreadBanner)) {
                $systemAttachment->delete([['name', 'like', "$poster%"]]);
            } else {
                $siteUrl = sys_config('site_url');
                $siteUrlHttps = set_http_type($siteUrl, $request->isSsl() ? 0 : 1);
                foreach ($SpreadBanner as &$item) {
                    if ($type == 1) {
                        if ($item['image_type'] == 1)
                            $item['poster'] = $siteUrlHttps . $item['att_dir'];
                        else
                            $item['poster'] = set_http_type($item['att_dir'], $request->isSsl() ? 0 : 1);
                        $item['poster'] = str_replace('\\', '/', $item['poster']);
                    } else {
                        if ($item['image_type'] == 1)
                            $item['wap_poster'] = $siteUrl . $item['att_dir'];
                        else
                            $item['wap_poster'] = set_http_type($item['att_dir'], 1);
                    }
                }
                return app('json')->success($SpreadBanner);
            }
        }
        try {
            $resRoutine = true;//Chương trình nhỏ
            $resWap = true;//Tài khoản chính thức
            $siteUrl = sys_config('site_url');
            if ($type == 1) {
                //Chương trình nhỏ
                $name = $user['uid'] . '_' . $user['is_promoter'] . '_user_routine.jpg';
                $imageInfo = $systemAttachment->getInfo(['name' => $name]);
                //Kiểm tra xem tập tin từ xa có tồn tại không
                if (isset($imageInfo['att_dir']) && strstr($imageInfo['att_dir'], 'http') !== false && curl_file_exist($imageInfo['att_dir']) === false) {
                    $imageInfo = null;
                    $systemAttachment->delete(['name' => $name]);
                }
                if (!$imageInfo) {
                    $resForever = $qrCode->qrCodeForever($user['uid'], 'spread', '', '');
                    $resCode = MiniProgramService::appCodeUnlimitService($resForever->id, '', 280);
                    if ($resCode) {
                        $res = ['res' => $resCode, 'id' => $resForever->id];
                    } else {
                        $res = false;
                    }
                    if (!$res) return app('json')->fail('Tạo mã QR không thành công');
                    $uploadType = (int)sys_config('upload_type', 1);
                    $upload = UploadService::init();
                    $uploadRes = $upload->to('routine/spread/code')->validate()->setAuthThumb(false)->stream($res['res'], $name);
                    if ($uploadRes === false) {
                        return app('json')->fail($upload->getError());
                    }
                    $imageInfo = $upload->getUploadInfo();
                    $imageInfo['image_type'] = $uploadType;
                    $systemAttachment->attachmentAdd($imageInfo['name'], $imageInfo['size'], $imageInfo['type'], $imageInfo['dir'], $imageInfo['thumb_path'], 1, $imageInfo['image_type'], $imageInfo['time'], 2);
                    $qrCode->setQrcodeFind($res['id'], ['status' => 1, 'url_time' => time(), 'qrcode_url' => $imageInfo['dir']]);
                    $urlCode = $imageInfo['dir'];
                } else $urlCode = $imageInfo['att_dir'];
                if ($imageInfo['image_type'] == 1) $urlCode = $siteUrl . $urlCode;
                $siteUrlHttps = set_http_type($siteUrl, $request->isSsl() ? 0 : 1);
                $filelink = [
                    'Bold' => 'statics' . DS . 'font' . DS . 'Alibaba-PuHuiTi-Regular.otf',
                    'Normal' => 'statics' . DS . 'font' . DS . 'Alibaba-PuHuiTi-Regular.otf',
                ];
                if (!file_exists($filelink['Bold'])) return app('json')->fail('Thiếu tập tin phông chữBold');
                if (!file_exists($filelink['Normal'])) return app('json')->fail('Thiếu tập tin phông chữNormal');
                foreach ($routineSpreadBanner as $key => &$item) {
                    $posterInfo = 'Tạo áp phích không thành công:(';
                    $config = array(
                        'image' => array(
                            array(
                                'url' => $urlCode,     //Tài nguyên mã QR
                                'stream' => 0,
                                'left' => 114,
                                'top' => 790,
                                'right' => 0,
                                'bottom' => 0,
                                'width' => 120,
                                'height' => 120,
                                'opacity' => 100
                            )
                        ),
                        'text' => array(
                            array(
                                'text' => $user['nickname'],
                                'left' => 250,
                                'top' => 840,
                                'fontPath' => $rootPath . 'public' . DS . $filelink['Bold'],     //tập tin phông chữ
                                'fontSize' => 16,             //Cỡ chữ
                                'fontColor' => '40,40,40',       //Màu chữ
                                'angle' => 0,
                            ),
                            array(
                                'text' => 'mời bạn tham gia' . sys_config('site_name'),
                                'left' => 250,
                                'top' => 880,
                                'fontPath' => $rootPath . 'public' . DS . $filelink['Normal'],     //tập tin phông chữ
                                'fontSize' => 16,             //Cỡ chữ
                                'fontColor' => '40,40,40',       //Màu chữ
                                'angle' => 0,
                            )
                        ),
                        'background' => $item['pic']
                    );
                    $resRoutine = $resRoutine && $posterInfo = PosterServices::setSharePoster($config, 'routine/spread/poster', $user['uid'] . '_' . $user['is_promoter'] . '_user_routine_poster_' . $key . '.jpg');
                    if (!is_array($posterInfo)) return app('json')->fail($posterInfo);
                    $systemAttachment->attachmentAdd($posterInfo['name'], $posterInfo['size'], $posterInfo['type'], $posterInfo['dir'], $posterInfo['thumb_path'], 1, $posterInfo['image_type'], $posterInfo['time'], 2);
                    if ($resRoutine) {
                        if ($posterInfo['image_type'] == 1)
                            $item['poster'] = $siteUrlHttps . $posterInfo['dir'];
                        else
                            $item['poster'] = set_http_type($posterInfo['dir'], $request->isSsl() ? 0 : 1);
                        $item['poster'] = str_replace('\\', '/', $item['poster']);
                    }
                }
            } else if ($type == 2) {
                //Tài khoản chính thức
                $name = $user['uid'] . '_' . $user['is_promoter'] . '_user_wap.jpg';
                $imageInfo = $systemAttachment->getInfo(['name' => $name]);
                //Kiểm tra xem tập tin từ xa có tồn tại không
                if (isset($imageInfo['att_dir']) && strstr($imageInfo['att_dir'], 'http') !== false && curl_file_exist($imageInfo['att_dir']) === false) {
                    $imageInfo = null;
                    $systemAttachment->delete(['name' => $name]);
                }
                if (!$imageInfo) {
                    $codeUrl = set_http_type($siteUrl . '?spread=' . $user['uid'], $request->isSsl() ? 0 : 1);//Liên kết mã QR
                    $imageInfo = PosterServices::getQRCodePath($codeUrl, $name);
                    if (is_string($imageInfo)) return app('json')->fail('Tạo mã QR không thành công', ['error' => $imageInfo]);
                    $systemAttachment->attachmentAdd($imageInfo['name'], $imageInfo['size'], $imageInfo['type'], $imageInfo['dir'], $imageInfo['thumb_path'], 1, $imageInfo['image_type'], $imageInfo['time'], 2);
                    $urlCode = $imageInfo['dir'];
                } else $urlCode = $imageInfo['att_dir'];
                if ($imageInfo['image_type'] == 1) $urlCode = $siteUrl . $urlCode;
                $siteUrl = set_http_type($siteUrl, $request->isSsl() ? 0 : 1);
                $filelink = [
                    'Bold' => 'statics' . DS . 'font' . DS . 'Alibaba-PuHuiTi-Regular.otf',
                    'Normal' => 'statics' . DS . 'font' . DS . 'Alibaba-PuHuiTi-Regular.otf',
                ];
                if (!file_exists($filelink['Bold'])) return app('json')->fail('Thiếu tập tin phông chữBold');
                if (!file_exists($filelink['Normal'])) return app('json')->fail('Thiếu tập tin phông chữNormal');
                foreach ($routineSpreadBanner as $key => &$item) {
                    $posterInfo = 'Tạo áp phích không thành công:(';
                    $config = array(
                        'image' => array(
                            array(
                                'url' => $urlCode,     //Tài nguyên mã QR
                                'stream' => 0,
                                'left' => 114,
                                'top' => 790,
                                'right' => 0,
                                'bottom' => 0,
                                'width' => 120,
                                'height' => 120,
                                'opacity' => 100
                            )
                        ),
                        'text' => array(
                            array(
                                'text' => $user['nickname'],
                                'left' => 250,
                                'top' => 840,
                                'fontPath' => $rootPath . 'public' . DS . $filelink['Bold'],     //tập tin phông chữ
                                'fontSize' => 16,             //Cỡ chữ
                                'fontColor' => '40,40,40',       //Màu chữ
                                'angle' => 0,
                            ),
                            array(
                                'text' => 'mời bạn tham gia' . sys_config('site_name'),
                                'left' => 250,
                                'top' => 880,
                                'fontPath' => $rootPath . 'public' . DS . $filelink['Normal'],     //tập tin phông chữ
                                'fontSize' => 16,             //Cỡ chữ
                                'fontColor' => '40,40,40',       //Màu chữ
                                'angle' => 0,
                            )
                        ),
                        'background' => $item['pic']
                    );
                    $resWap = $resWap && $posterInfo = PosterServices::setSharePoster($config, 'wap/spread/poster', $user['uid'] . '_' . $user['is_promoter'] . '_user_wap_poster_' . $key . '.jpg');
                    if (!is_array($posterInfo)) return app('json')->fail($posterInfo);
                    $systemAttachment->attachmentAdd($posterInfo['name'], $posterInfo['size'], $posterInfo['type'], $posterInfo['dir'], $posterInfo['thumb_path'], 1, $posterInfo['image_type'], $posterInfo['time'], 2);
                    if ($resWap) {
                        if ($posterInfo['image_type'] == 1)
                            $item['wap_poster'] = $siteUrl . $posterInfo['thumb_path'];
                        else
                            $item['wap_poster'] = set_http_type($posterInfo['thumb_path'], 1);
                    }
                }
            }
            if ($resRoutine && $resWap) return app('json')->success($routineSpreadBanner);
            else return app('json')->fail('Không tạo được hình ảnh');
        } catch (\Exception $e) {
            return app('json')->fail('Lỗi hệ thống khi tạo hình ảnh', ['line' => $e->getLine(), 'message' => $e->getMessage(), 'file' => $e->getFile()]);
        }
    }

    /**
     * Lấy mã QR của chương trình mini
     * @param Request $request
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getRoutineCode(Request $request)
    {
        $user = $request->user();
        /** @var SystemAttachmentServices $systemAttachment */
        $systemAttachment = app()->make(SystemAttachmentServices::class);
        //Chương trình nhỏ
        $name = $user['uid'] . '_' . $user['is_promoter'] . '_user_routine.jpg';
        $imageInfo = $systemAttachment->getInfo(['name' => $name]);
        //Kiểm tra xem tập tin từ xa có tồn tại không
        if (isset($imageInfo['att_dir']) && strstr($imageInfo['att_dir'], 'http') !== false && curl_file_exist($imageInfo['att_dir']) === false) {
            $imageInfo = null;
            $systemAttachment->delete(['name' => $name]);
        }
        $siteUrl = sys_config('site_url');
        /** @var QrcodeServices $qrCode */
        $qrCode = app()->make(QrcodeServices::class);
        if (!$imageInfo) {
            $resForever = $qrCode->qrCodeForever($user['uid'], 'spread', '', '');
            $resCode = MiniProgramService::appCodeUnlimitService($resForever->id, '', 280);
            if ($resCode) {
                $res = ['res' => $resCode, 'id' => $resForever->id];
            } else {
                $res = false;
            }
            if (!$res) return app('json')->fail('Tạo mã QR không thành công');
            $uploadType = (int)sys_config('upload_type', 1);
            $upload = UploadService::init();
            $uploadRes = $upload->to('routine/spread/code')->validate()->setAuthThumb(false)->stream($res['res'], $name);
            if ($uploadRes === false) {
                return app('json')->fail($upload->getError());
            }
            $imageInfo = $upload->getUploadInfo();
            $imageInfo['image_type'] = $uploadType;
            $systemAttachment->attachmentAdd($imageInfo['name'], $imageInfo['size'], $imageInfo['type'], $imageInfo['dir'], $imageInfo['thumb_path'], 1, $imageInfo['image_type'], $imageInfo['time'], 2);
            $qrCode->setQrcodeFind($res['id'], ['status' => 1, 'url_time' => time(), 'qrcode_url' => $imageInfo['dir']]);
            $urlCode = $imageInfo['dir'];
        } else $urlCode = $imageInfo['att_dir'];
        if ($imageInfo['image_type'] == 1) $urlCode = $siteUrl . $urlCode;
        return app('json')->success(['url' => $urlCode]);
    }

    /**
     * Nhận chi tiết áp phích
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getSpreadInfo(Request $request)
    {
        /** @var SystemConfigServices $systemConfigServices */
        $systemConfigServices = app()->make(SystemConfigServices::class);
        $spreadBanner = $systemConfigServices->getSpreadBanner() ?? [];
        $bannerCount = count($spreadBanner);
        $routineSpreadBanner = [];
        if ($bannerCount) {
            foreach ($spreadBanner as $item) {
                $routineSpreadBanner[] = ['pic' => $item];
            }
        }

        if (sys_config('share_qrcode', 0) && request()->isWechat()) {
            /** @var QrcodeServices $qrcodeService */
            $qrcodeService = app()->make(QrcodeServices::class);
            $qrcode = $qrcodeService->getTemporaryQrcode('spread', $request->uid())->url;
        } else {
            $qrcode = '';
        }

        return app('json')->success([
            'spread' => $routineSpreadBanner,
            'qrcode' => $qrcode,
            'nickname' => $request->user('nickname'),
            'site_name' => sys_config('site_name')
        ]);
    }

    /**
     * Kỷ lục điểm
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function integral_list(Request $request)
    {
        $uid = (int)$request->uid();
        $data = $this->services->getIntegralList($uid);
        return app('json')->success($data['list'] ?? []);
    }

    /**
     * Xếp hạng hoa hồng
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function brokerage_rank(Request $request)
    {
        $data = $request->getMore([
            ['page', ''],
            ['limit'],
            ['type']
        ]);
        $uid = (int)$request->uid();
        return app('json')->success($this->services->brokerage_rank($uid, $data['type']));
    }

    /**
     * Lệnh khuyến mãi của Phòng Kinh doanh/Đại lý
     * @param Request $request
     * @return mixed
     */
    public function divisionOrder(Request $request)
    {
        $uid = (int)$request->uid();
        return app('json')->success($this->services->divisionOrder($uid));
    }
}
