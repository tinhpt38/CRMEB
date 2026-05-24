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
namespace app\adminapi\controller\v1\system;

use think\facade\App;
use app\adminapi\controller\AuthController;
use app\services\system\log\SystemFileServices;

/**
 * Bộ điều khiển xác minh tập tin
 * Class SystemFile
 * @package app\admin\controller\system
 *
 */class SystemFile extends AuthController
{
    /**
     * @var SystemFileServices
     */    protected $services;

    /**
     * Người xây dựng
     * SystemFile constructor.
     * @param App $app
     * @param SystemFileServices $services
     */    public function __construct(App $app, SystemFileServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Hồ sơ xác minh tập tin
     * @return mixed
     */    public function index()
    {
        return app('json')->success(['list' => $this->services->getFileList()]);
    }

    /**
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     *
     * @date 2022/09/07
     * @author yyw
     */    public function login()
    {
        [$password] = $this->request->postMore([
            'password',
        ], true);

        $adminInfo = $this->request->adminInfo();
        if (!$adminInfo) return app('json')->fail('Hoạt động trái phép');
        if ($adminInfo['level'] != 0) return app('json')->fail('Hoạt động trái phép');
        if ($password === '') return app('json')->fail('Vui lòng nhập mật khẩu');

        return app('json')->success($this->services->login($password, 'file_edit'));
    }

    //Mở thư mục
    public function opendir()
    {
        [$dir, $fileDir, $superior] = $this->request->getMore([
            ['dir', ''],
            ['filedir', ''],
            ['superior', ''],
        ], true);
        return app('json')->success($this->services->opendir($dir, $fileDir, $superior));
    }

    //Tập tin nhận xét
    public function fileMark()
    {
        [$path, $fileToken] = $this->request->postMore([
            ['path', ''],
            ['fileToken', ''],
        ], true);
        if ($path == '') return app('json')->fail('Lỗi tham số');
        return app('json')->success($this->services->markForm($path, $fileToken));
    }

    //Lưu tập tin ghi chú
    public function fileMarkSave()
    {
        [$full_path, $mark] = $this->request->postMore([
            ['full_path', ''],
            ['mark', ''],
        ], true);
        $full_path = $this->request->param('full_path');
        if ($full_path == '') return app('json')->fail('Lỗi tham số');
        $this->services->fileMarkSave($full_path, $mark);
        return app('json')->success('Đã lưu thành công');
    }

    //đọc tập tin
    public function openfile()
    {
        $file = $this->request->param('filepath');
        if (empty($file)) return app('json')->fail('Lỗi nền tảng: Đã xảy ra ngoại lệ, vui lòng thử lại sau');
        return app('json')->success($this->services->openfile($file));
    }

    //lưu tập tin
    public function savefile()
    {
        $comment = $this->request->param('comment');
        $filepath = $this->request->param('filepath');
        if (empty($filepath)) {
            return app('json')->fail('Đường dẫn tệp không tồn tại');
        }
        $res = $this->services->savefile($filepath, $comment);
        if ($res) {
            return app('json')->success('Đã lưu thành công');
        } else {
            return app('json')->fail('Lưu không thành công');
        }
    }

    /**
     * Tạo thư mục
     * @return mixed
     *
     * @date 2022/09/17
     * @author yyw
     */    public function createFolder()
    {
        [$path, $name] = $this->request->postMore([
            ['path', ''],
            ['name', '']
        ], true);
        if (empty($path) || empty($name)) {
            return app('json')->fail('Lỗi nền tảng: Đã xảy ra ngoại lệ, vui lòng thử lại sau');
        }
        $data = [];
        try {
            $res = $this->services->createFolder($path, $name);
            if ($res) {
                $data = [
                    'children' => [],
                    'contextmenu' => true,
                    'isDir' => true,
                    'loading' => false,
                    'path' => $path,
                    'pathname' => $path . DS . $name,
                    'title' => $name,
                ];
            } else {
                return app('json')->fail('Thao tác không thành công');
            }
        } catch (\Exception $e) {
            return app('json')->fail($e->getMessage());
        }
        return app('json')->success($data);
    }

    /**
     * Tạo tập tin
     * @return mixed
     *
     * @date 2022/09/17
     * @author yyw
     */    public function createFile()
    {
        [$path, $name] = $this->request->postMore([
            ['path', ''],
            ['name', '']
        ], true);
        if (empty($path) || empty($name)) {
            return app('json')->fail('Lỗi nền tảng: Đã xảy ra ngoại lệ, vui lòng thử lại sau');
        }
        $data = [];
        try {
            $res = $this->services->createFile($path, $name);
            if ($res) {
                $data = [
                    'children' => [],
                    'contextmenu' => true,
                    'isDir' => false,
                    'loading' => false,
                    'path' => $path,
                    'pathname' => $path . DS . $name,
                    'title' => $name,
                ];
            } else {
                return app('json')->fail('Thao tác không thành công');
            }
        } catch (\Exception $e) {
            return app('json')->fail($e->getMessage());
        }
        return app('json')->success($data);
    }

    /**
     * Xóa một tập tin hoặc thư mục
     * @return mixed
     *
     * @date 2022/09/17
     * @author yyw
     */    public function delFolder()
    {
        [$path] = $this->request->postMore([
            ['path', '']
        ], true);
        if (empty($path)) {
            return app('json')->fail('Lỗi nền tảng: Đã xảy ra ngoại lệ, vui lòng thử lại sau');
        }
        try {
            $this->services->delFolder($path);
        } catch (\Exception $e) {
            return app('json')->fail($e->getMessage());
        }
        return app('json')->success('Hoạt động thành công');
    }

    /**
     * Đổi tên tập tin
     * @return mixed
     *
     * @date 2022/09/28
     * @author yyw
     */    public function rename()
    {
        [$newname, $oldname] = $this->request->postMore([
            ['newname', ''],
            ['oldname', '']
        ], true);
        if (empty($newname) || empty($oldname)) {
            return app('json')->fail('Lỗi nền tảng: Đã xảy ra ngoại lệ, vui lòng thử lại sau');
        }
        try {
            $this->services->rename($newname, $oldname);
        } catch (\Exception $e) {
            return app('json')->fail($e->getMessage());
        }
        return app('json')->success('Hoạt động thành công');

    }


    public function copyFolder()
    {
        [$surDir, $toDir] = $this->request->postMore([
            ['surDir', ''],
            ['toDir', '']
        ], true);
        if (empty($surDir) || empty($toDir)) {
            return app('json')->fail('Lỗi nền tảng: Đã xảy ra ngoại lệ, vui lòng thử lại sau');
        }
        try {
            return app('json')->success($this->services->copyFolder($surDir, $toDir));
        } catch (\Exception $e) {
            return app('json')->fail($e->getMessage());
        }
    }

    /**
     * ghi tập tinmd5
     * @return \think\Response
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2026/2/25
     */    public function writeMd5()
    {
        try {
            $this->services->writeMd5();
        } catch (\Exception $e) {
            return app('json')->fail($e->getMessage());
        }
        return app('json')->success('Hoạt động thành công');
    }
}
