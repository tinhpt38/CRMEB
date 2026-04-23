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
namespace app\adminapi\controller\v1\application\routine;

use app\jobs\notice\SyncMessageJob;
use app\services\message\SystemNotificationServices;
use app\services\other\QrcodeServices;
use app\services\system\attachment\SystemAttachmentServices;
use crmeb\exceptions\AdminException;
use think\exception\ValidateException;
use think\facade\App;
use app\adminapi\controller\AuthController;
use crmeb\services\FileService;
use app\services\other\UploadService;
use crmeb\services\app\MiniProgramService;

/**
 * Class RoutineTemplate
 * @package app\adminapi\controller\v1\application\routine
 */
class RoutineTemplate extends AuthController
{
    protected $cacheTag = '_system_wechat';

    /**
     * Người xây dựng
     * WechatTemplate constructor.
     * @param App $app
     * @param SystemNotificationServices $services
     */
    public function __construct(App $app, SystemNotificationServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Đồng bộ hóa tin nhắn đăng ký
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function syncSubscribe()
    {
        if (!sys_config('routine_appId') || !sys_config('routine_appsecret')) {
            throw new AdminException('Trước tiên hãy định cấu hình chương trình mini appid, appSecret và các tham số khác');
        }

        $list = MiniProgramService::getSubscribeTemplateList();
        foreach ($list->data as $v) {
            MiniProgramService::delSubscribeTemplate($v['priTmplId']);
        }

        $tempKeys = $this->services->getTempKey('routine');
        foreach ($tempKeys as $key => $content) {
            SyncMessageJob::dispatch('SyncSubscribe', [$key, $content]);
        }

        return app('json')->success('Đồng bộ hóa thành công');
    }

    /**
     * Tải xuống ứng dụng
     * @return mixed
     */
    public function downloadTemp()
    {
        [$name, $is_live] = $this->request->postMore([
            ['name', ''],
            ['is_live', 0]
        ], true);
        if (sys_config('routine_appId', '') == '') throw new AdminException('Trước tiên hãy định cấu hình chương trình mini appid, appSecret và các tham số khác');
        try {
            @unlink(public_path() . 'statics/download/routine.zip');
            //Sao chép tập tin nguồn
            /** @var FileService $fileService */
            $fileService = app(FileService::class);
            $fileService->copyDir(public_path() . 'statics/mp_view', public_path() . 'statics/download');
            //Thay thế appid và tên
            $this->updateConfigJson(sys_config('routine_appId'), $name != '' ? $name : sys_config('routine_name'));
            //Có bật phát sóng trực tiếp hay không
            if ($is_live == 0) $this->updateAppJson();
            //thay thếurl
            $this->updateUrl('https://' . $_SERVER['HTTP_HOST']);
            //tập tin nén
            $fileService->addZip(public_path() . 'statics/download', public_path() . 'statics/download/routine.zip', public_path() . 'statics/download');
            $data['url'] = sys_config('site_url') . '/statics/download/routine.zip';
            return app('json')->success($data);
        } catch (\Throwable $e) {
            throw new AdminException($e->getMessage());
        }
    }

    /**
     * thay thếurl
     * @param $url
     */
    public function updateUrl($url)
    {
        $fileUrl = app()->getRootPath() . "public/statics/download/common/vendor.js";
        $string = file_get_contents($fileUrl); //Tải tập tin cấu hình
        $string = str_replace('https://demo.crmeb.com', $url, $string); // Tìm kiếm và thay thế thường xuyên
        $newFileUrl = app()->getRootPath() . "public/statics/download/common/vendor.js";
        @file_put_contents($newFileUrl, $string); // Viết tập tin cấu hình

    }

    /**
     * Xác định xem có nên bắt đầu phát sóng trực tiếp hay không(Không được dùng nữa)
     * @param int $iszhibo
     */
    public function updateAppJson()
    {
        $fileUrl = app()->getRootPath() . "public/statics/download/app.json";
        $string = file_get_contents($fileUrl); //Tải tập tin cấu hình
        $pats = '/,
      "plugins": \{
        "live-player-plugin": \{
          "version": "(.*?)",
          "provider": "(.*?)"
        }
      }/';
        $string = preg_replace($pats, '', $string); // Tìm kiếm và thay thế thường xuyên
        $newFileUrl = app()->getRootPath() . "public/statics/download/app.json";
        @file_put_contents($newFileUrl, $string); // Viết tập tin cấu hình
    }

    /**
     * thay thếappid
     * @param string $appid
     * @param string $projectanme
     */
    public function updateConfigJson($appId = '', $projectName = '')
    {
        $fileUrl = app()->getRootPath() . "public/statics/download/project.config.json";
        $string = file_get_contents($fileUrl); //Tải tập tin cấu hình
        // Thay thếappid
        $appIdOld = '/"appid"(.*?),/';
        $appIdNew = '"appid"' . ': ' . '"' . $appId . '",';
        $string = preg_replace($appIdOld, $appIdNew, $string); // Tìm kiếm và thay thế thường xuyên
        // Thay thế tên applet
        $projectNameOld = '/"projectname"(.*?),/';
        $projectNameNew = '"projectname"' . ': ' . '"' . $projectName . '",';
        $string = preg_replace($projectNameOld, $projectNameNew, $string); // Tìm kiếm và thay thế thường xuyên
        $newFileUrl = app()->getRootPath() . "public/statics/download/project.config.json";
        @file_put_contents($newFileUrl, $string); // Viết tập tin cấu hình
    }

    /**
     * Lấy mã applet
     * @return string
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getDownloadInfo()
    {
        $data['routine_name'] = sys_config('routine_name', '');
        if (sys_config('routine_appId') == '') {
            $data['code'] = '';
        } else {
            $name = $data['routine_name'] . '.jpg';
            /** @var SystemAttachmentServices $systemAttachmentModel */
            $systemAttachmentModel = app()->make(SystemAttachmentServices::class);
            $imageInfo = $systemAttachmentModel->getInfo(['name' => $name]);
            if (!$imageInfo) {
                /** @var QrcodeServices $qrcode */
                $qrcode = app()->make(QrcodeServices::class);
                $resForever = $qrcode->qrCodeForever(0, 'code');
                if ($resForever) {
                    $resCode = MiniProgramService::appCodeUnlimitService($resForever->id, '', 280);
                    $res = ['res' => $resCode, 'id' => $resForever->id];
                } else {
                    $res = false;
                }
                if (!$res) throw new ValidateException('Tạo mã QR không thành công');
                $upload = UploadService::init(1);
                if ($upload->to('routine/code')->setAuthThumb(false)->stream((string)$res['res'], $name) === false) {
                    return $upload->getError();
                }
                $imageInfo = $upload->getUploadInfo();
                $imageInfo['image_type'] = 1;
                $systemAttachmentModel->attachmentAdd($imageInfo['name'], $imageInfo['size'], $imageInfo['type'], $imageInfo['dir'], $imageInfo['thumb_path'], 1, $imageInfo['image_type'], $imageInfo['time'], 2);
                $qrcode->update($res['id'], ['status' => 1, 'time' => time(), 'qrcode_url' => $imageInfo['dir']]);
                $data['code'] = sys_config('site_url') . $imageInfo['dir'];
            } else $data['code'] = sys_config('site_url') . $imageInfo['att_dir'];
        }
        $data['appId'] = sys_config('routine_appId');
        $data['help'] = 'https://doc.crmeb.com/web/single/crmeb_v4/978';
        return app('json')->success($data);
    }
}
