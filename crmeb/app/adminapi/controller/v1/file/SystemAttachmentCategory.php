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
use app\services\system\attachment\SystemAttachmentCategoryServices;
use think\facade\App;

/**
 * Lớp quản lý phân loại ảnh
 * Class SystemAttachmentCategory
 * @package app\adminapi\controller\v1\file
 */class SystemAttachmentCategory extends AuthController
{
    /**
     * @var SystemAttachmentCategoryServices
     */    protected $service;

    /**
     * @param App $app
     * @param SystemAttachmentCategoryServices $service
     */    public function __construct(App $app, SystemAttachmentCategoryServices $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    /**
     * Hiển thị danh sách tài nguyên
     * @return \think\Response
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function index()
    {
        $where = $this->request->getMore([
            ['name', ''],
            ['pid', 0],
            ['all', 0],
            ['type', 0],
        ]);
        if ($where['name'] != '' || $where['all'] == 1) $where['pid'] = '';
        return app('json')->success($this->service->getAll($where));
    }

    /**
     * Thêm biểu mẫu
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     */    public function create()
    {
        [$id, $type] = $this->request->getMore([
            ['id', 0],
            ['type', 0],
        ], true);
        return app('json')->success($this->service->createForm($id, $type));
    }

    /**
     * Lưu mới
     * @return mixed
     */    public function save()
    {
        $data = $this->request->postMore([
            ['pid', 0],
            ['name', ''],
            ['type', 0],
        ]);
        if (is_array($data['pid'])) $data['pid'] = end($data['pid']);
        if (!$data['name']) {
            return app('json')->fail('Vui lòng điền tên danh mục');
        }
        $this->service->save($data);
        return app('json')->success('Đã thêm thành công');
    }

    /**
     * chỉnh sửa biểu mẫu
     * @param $id
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     */    public function edit($id)
    {
        return app('json')->success($this->service->editForm($id));
    }

    /**
     * Lưu tài nguyên cập nhật
     * @param $id
     * @return mixed
     */    public function update($id)
    {
        $data = $this->request->postMore([
            ['pid', 0],
            ['name', '']
        ]);
        if (is_array($data['pid'])) $data['pid'] = end($data['pid']);
        if (!$data['name']) {
            return app('json')->fail('Vui lòng điền tên danh mục');
        }
        if ($data['pid'] == $id) {
            return app('json')->fail('Đẳng cấp vượt trội không thể là chính bạn');
        }
        $info = $this->service->get($id);
        $count = $this->service->count(['pid' => $id]);
        if ($count && $info['pid'] != $data['pid']) return app('json')->fail('Danh mục này có các danh mục phụ và cấp trên không thể sửa đổi.');
        $this->service->update($id, $data);
        return app('json')->success('Sửa đổi thành công');
    }

    /**
     * Xóa tài nguyên được chỉ định
     * @param int $id
     * @return \think\Response
     */    public function delete($id)
    {
        $this->service->del($id);
        return app('json')->success('Xóa thành công');
    }
}
