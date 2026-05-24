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
namespace app\adminapi\controller\v1\file;

use app\adminapi\controller\AuthController;
use app\services\system\attachment\SystemAttachmentServices;
use crmeb\services\CacheService;
use think\facade\App;

/**
 * Lớp quản lý tệp đính kèm
 * Class SystemAttachment
 * @package app\adminapi\controller\v1\file
 */class SystemAttachment extends AuthController
{
    /**
     * @var SystemAttachmentServices
     */    protected $service;

    /**
     * @param App $app
     * @param SystemAttachmentServices $service
     */    public function __construct(App $app, SystemAttachmentServices $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    /**
     * hiển thị danh sách
     * @return mixed
     */    public function index()
    {
        $where = $this->request->getMore([
            ['pid', 0],
            ['real_name', ''],
            ['type', 0],
        ]);
        return app('json')->success($this->service->getImageList($where));
    }

    /**
     * Xóa tài nguyên được chỉ định
     * @return mixed
     */    public function delete()
    {
        [$ids] = $this->request->postMore([
            ['ids', '']
        ], true);
        $this->service->del($ids);
        return app('json')->success('Xóa thành công');
    }

    /**
     * Tải lên hình ảnh
     * @param int $upload_type
     * @param int $type
     * @return mixed
     */    public function upload($upload_type = 0, $type = 0)
    {
        [$pid, $file, $menuName] = $this->request->postMore([
            ['pid', 0],
            ['file', 'file'],
            ['menu_name', '']
        ], true);
        $res = $this->service->upload((int)$pid, $file, $upload_type, $type, $menuName);
        return app('json')->success('Tải lên thành công', ['src' => $res]);
    }

    /**
     * hình ảnh chuyển động
     * @return mixed
     */    public function moveImageCate()
    {
        $data = $this->request->postMore([
            ['pid', 0],
            ['images', '']
        ]);
        $this->service->move($data);
        return app('json')->success('Đã di chuyển thành công');
    }

    /**
     * Sửa đổi tên tập tin
     * @param $id
     * @return mixed
     */    public function update($id)
    {
        $realName = $this->request->post('real_name', '');
        if (!$realName) {
            return app('json')->fail('Tên tệp không được để trống');
        }
        $this->service->update($id, ['real_name' => $realName]);
        return app('json')->success('Sửa đổi thành công');
    }

    /**
     * Nhận loại tải lên
     * @return mixed
     */    public function uploadType()
    {
        $data['upload_type'] = (string)sys_config('upload_type', 1);
        return app('json')->success($data);
    }

    /**
     * Tải lên nhiều phần video
     * @return mixed
     */    public function videoUpload()
    {
        $data = $this->request->postMore([
            ['chunkNumber', 0],//mảnh nào
            ['currentChunkSize', 0],//Kích thước mảnh
            ['chunkSize', 0],//tổng kích thước
            ['totalChunks', 0],//Tổng số mảnh vỡ
            ['file', 'file'],//tài liệu
            ['md5', ''],//MD5
            ['filename', ''],//Tên tập tin
        ]);
        $res = $this->service->videoUpload($data, $_FILES['file']);
        return app('json')->success($res);
    }

    /**
     * Lấy link trang upload code scan và thông số
     * @return \think\Response
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2023/06/13
     */    public function scanUploadQrcode()
    {
        [$pid] = $this->request->getMore([
            ['pid', 0]
        ], true);
        $uploadToken = md5(time());
        CacheService::set('scan_upload', $uploadToken, 600);
        $url = sys_config('site_url') . '/app/upload?pid=' . $pid . '&token=' . $uploadToken;
        return app('json')->success(['url' => $url]);
    }

    /**
     * Xóa mã QR
     * @return \think\Response
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/6/26
     */    public function removeUploadQrcode()
    {
        CacheService::delete('scan_upload');
        return app('json')->success();
    }

    /**
     * Lấy dữ liệu hình ảnh được tải lên bằng cách quét mã QR
     * @param $scan_token
     * @return \think\Response
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2023/06/13
     */    public function scanUploadImage($scan_token)
    {
        return app('json')->success($this->service->scanUploadImage($scan_token));
    }

    /**
     * Tải hình ảnh lên Internet
     * @return \think\Response
     * @throws \Exception
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2023/06/13
     */    public function onlineUpload()
    {
        $data = $this->request->postMore([
            ['pid', 0],
            ['images', []]
        ]);
        $this->service->onlineUpload($data);
        return app('json')->success('Tải lên thành công');
    }

    public function videoDataSave()
    {
        $data = $this->request->postMore([
            ['pid', 0],
            ['video_name', ''],
            ['video_path', '']
        ]);
        $this->service->attachmentAdd(
            $data['video_name'],
            0,
            'video/mp4',
            $data['video_path'],
            $data['video_path'],
            $data['pid'],
            (int)sys_config('upload_type', 1),
            time(),
            1,
            1,
            $data['video_name']
        );;
        return app('json')->success('Tải lên thành công');
    }
}
