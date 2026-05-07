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
namespace app\adminapi\controller\v1\product;

use app\adminapi\controller\AuthController;
use app\services\product\product\StoreCategoryServices;
use think\facade\App;

/**
 * Bộ điều khiển danh mục sản phẩm
 * Class StoreCategory
 * @package app\admin\controller\system
 */
class StoreCategory extends AuthController
{
    /**
     * @var StoreCategoryServices
     */
    protected $service;

    /**
     * StoreCategory constructor.
     * @param App $app
     * @param StoreCategoryServices $service
     */
    public function __construct(App $app, StoreCategoryServices $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    /**
     * Danh sách danh mục
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index()
    {
        $where = $this->request->getMore([
            ['is_show', ''],
            ['pid', ''],
            ['cate_name', ''],
        ]);
        $data = $this->service->getList($where);
        return app('json')->success($data);
    }

    /**
     * Tìm kiếm danh mục sản phẩm
     * @param $type
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function tree_list($type)
    {
        $list = $this->service->getTierList(1, $type);
        return app('json')->success($list);
    }

    /**
     * Nhận dữ liệu định dạng tầng phân loại
     * @param $type
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function cascader_list($type = 1)
    {
        return app('json')->success($this->service->cascaderList(1, $type));
    }

    /**
     * Sửa đổi trạng thái
     * @param string $is_show
     * @param string $id
     */
    public function set_show($is_show = '', $id = '')
    {
        if ($is_show == '' || $id == '') return app('json')->fail('Lỗi tham số');
        $this->service->setShow($id, $is_show);
        return app('json')->success($is_show == 1 ? 'Hiển thị thành công' : 'Ẩn thành công');
    }

    /**
     * Tạo biểu mẫu mới
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function create()
    {
        return app('json')->success($this->service->createForm());
    }

    /**
     * Lưu danh mục mới
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function save()
    {
        $data = $this->request->postMore([
            ['pid', 0],
            ['cate_name', ''],
            ['pic', ''],
            ['big_pic', ''],
            ['sort', 0],
            ['is_show', 0]
        ]);
        $this->validate($data, \app\adminapi\validate\product\StoreCategoryValidate::class, 'save');
        $this->service->createData($data);
        return app('json')->success('Đã lưu thành công');
    }

    /**
     * Tạo biểu mẫu cập nhật
     * @param $id
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function edit($id)
    {
        return app('json')->success($this->service->editForm((int)$id));
    }

    /**
     * Cập nhật danh mục
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function update($id)
    {
        $data = $this->request->postMore([
            ['pid', 0],
            ['cate_name', ''],
            ['pic', ''],
            ['big_pic', ''],
            ['sort', 0],
            ['is_show', 0]
        ]);
        $this->validate($data, \app\adminapi\validate\product\StoreCategoryValidate::class, 'update');

        $this->service->editData($id, $data);
        return app('json')->success('Sửa đổi thành công');
    }

    /**
     * Xóa danh mục
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $this->service->del((int)$id);
        return app('json')->success('Xóa thành công');
    }
}
