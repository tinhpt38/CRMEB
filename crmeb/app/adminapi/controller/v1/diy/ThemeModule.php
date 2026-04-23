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
namespace app\adminapi\controller\v1\diy;

use app\adminapi\controller\AuthController;
use app\services\diy\ThemeModuleServices;
use crmeb\exceptions\AdminException;
use think\facade\App;

/**
 * Quản lý thành phần chủ đề
 */
class ThemeModule extends AuthController
{
    /**
     * @var ThemeModuleServices
     */
    protected $services;

    /**
     * Người xây dựng
     * @param App $app
     * @param ThemeModuleServices $services
     */
    public function __construct(App $app, ThemeModuleServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Danh sách thành phần
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index()
    {
        $where = $this->request->getMore([
            ['type', ''],
        ]);
        $data = $this->services->getModuleList($where);
        return app('json')->success($data);
    }

    /**
     * Chi tiết thành phần
     * @param int $id
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function read(int $id)
    {
        if (!$id) {
            throw new AdminException('Lỗi tham số');
        }
        $info = $this->services->getModuleInfo($id);
        return app('json')->success($info);
    }

    /**
     * Thêm thành phần mới
     * @return \think\Response
     */
    public function save()
    {
        $data = $this->request->postMore([
            ['type', ''],
            ['data', ''],
        ]);
        $id = $this->services->saveModule(0, $data);
        return app('json')->success('Đã lưu thành công', ['id' => $id]);
    }

    /**
     * Chỉnh sửa thành phần
     * @param int $id
     * @return \think\Response
     */
    public function update(int $id)
    {
        if (!$id) {
            throw new AdminException('Lỗi tham số');
        }
        $data = $this->request->postMore([
            ['type', ''],
            ['data', ''],
        ]);
        $this->services->saveModule($id, $data);
        return app('json')->success('Đã lưu thành công', ['id' => $id]);
    }

    /**
     * Xóa thành phần
     * @param int $id
     * @return \think\Response
     */
    public function delete(int $id)
    {
        if (!$id) {
            throw new AdminException('Lỗi tham số');
        }
        $this->services->deleteModule($id);
        return app('json')->success('Xóa thành công');
    }
}

