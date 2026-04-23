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
namespace app\adminapi\controller\v1\cms;

use app\adminapi\controller\AuthController;
use app\services\article\ArticleCategoryServices;
use crmeb\services\CacheService;
use think\facade\App;

/**
 * Quản lý phân loại bài viết
 * Class ArticleCategory
 * @package app\adminapi\controller\v1\cms
 */
class ArticleCategory extends AuthController
{
    /**
     * @var ArticleCategoryServices
     */
    protected $service;

    /**
     * ArticleCategory constructor.
     * @param App $app
     * @param ArticleCategoryServices $service
     */
    public function __construct(App $app, ArticleCategoryServices $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    /**
     * Nhận danh sách danh mục
     * @return mixed
     */
    public function index()
    {
        $where = $this->request->getMore([
            ['status', ''],
            ['title', ''],
            ['type', 0]
        ]);
        $type = $where['type'];
        unset($where['type']);
        $data = $this->service->getList($where);
        if ($type == 1) $data = $data['list'];
        return app('json')->success($data);
    }

    /**
     * Tạo biểu mẫu mới
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function create()
    {
        return app('json')->success($this->service->createForm(0));
    }

    /**
     * Lưu danh mục mới
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function save()
    {
        $data = $this->request->postMore([
            ['title', ''],
            ['pid', 0],
            ['intr', ''],
            ['image', ''],
            ['sort', 0],
            ['status', 0]
        ]);
        if (!$data['title']) {
            return app('json')->fail('Vui lòng điền tên danh mục');
        }
        $data['add_time'] = time();
        $this->service->save($data);
        CacheService::delete('ARTICLE_CATEGORY');
        CacheService::delete('ARTICLE_CATEGORY_PC');
        return app('json')->success('Đã thêm thành công');
    }

    /**
     * Tạo biểu mẫu sửa đổi
     * @param int $id
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function edit($id = 0)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');
        return app('json')->success($this->service->createForm($id));
    }

    /**
     * Lưu danh mục đã sửa đổi
     * @param $id
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function update($id)
    {
        $data = $this->request->postMore([
            ['id', 0],
            ['title', ''],
            ['pid', 0],
            ['intr', ''],
            ['image', ''],
            ['sort', 0],
            ['status', 0]
        ]);
        $this->service->update($data);
        CacheService::delete('ARTICLE_CATEGORY');
        CacheService::delete('ARTICLE_CATEGORY_PC');
        return app('json')->success('Sửa đổi thành công');
    }

    /**
     * Xóa danh mục bài viết
     * @param $id
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function delete($id)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');
        $this->service->del($id);
        CacheService::delete('ARTICLE_CATEGORY');
        CacheService::delete('ARTICLE_CATEGORY_PC');
        return app('json')->success('Xóa thành công');
    }

    /**
     * Sửa đổi trạng thái phân loại bài viết
     * @param int $id
     * @param int $status
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function set_status($id, $status)
    {
        if ($status == '' || $id == 0) return app('json')->fail('Lỗi tham số');
        $this->service->setStatus($id, $status);
        CacheService::delete('ARTICLE_CATEGORY');
        CacheService::delete('ARTICLE_CATEGORY_PC');
        return app('json')->success('Thiết lập thành công');
    }

    /**
     * Nhận phân loại bài viết
     * @return mixed
     */
    public function categoryList()
    {
        return app('json')->success($this->service->getArticleTwoCategory());
    }

    /**
     * danh sách cây
     * @return mixed
     * @throws \ReflectionException
     */
    public function getTreeList()
    {
        $list = $this->service->getTreeList();
        return app('json')->success($list);
    }
}
