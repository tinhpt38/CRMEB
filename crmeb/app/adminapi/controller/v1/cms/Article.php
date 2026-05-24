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
use app\services\article\ArticleServices;
use think\facade\App;

/**
 * Quản lý bài viết
 * Class Article
 * @package app\adminapi\controller\v1\cms
 */class Article extends AuthController
{
    /**
     * @var ArticleServices
     */    protected $service;

    /**
     * Article constructor.
     * @param App $app
     * @param ArticleServices $service
     */    public function __construct(App $app, ArticleServices $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    /**
     * Nhận danh sách
     * @return mixed
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function index()
    {
        $where = $this->request->getMore([
            ['title', ''],
            ['pid', 0, '', 'cid'],
        ]);
        $data = $this->service->getList($where);
        return app('json')->success($data);
    }

    /**
     * Lưu dữ liệu bài viết
     * @return mixed
     */    public function save()
    {
        $data = $this->request->postMore([
            ['id', 0],
            ['cid', ''],
            ['title', ''],
            ['author', ''],
            ['image_input', ''],
            ['content', ''],
            ['synopsis', 0],
            ['share_title', ''],
            ['share_synopsis', ''],
            ['sort', 0],
            ['url', ''],
            ['is_banner', 0],
            ['is_hot', 0],
            ['status', 1]
        ]);
        $this->service->save($data);
        return app('json')->success('Đã thêm thành công');
    }

    /**
     * Nhận dữ liệu bài viết duy nhất
     * @param int $id
     * @return mixed
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function read($id = 0)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');
        $info = $this->service->read($id);
        return app('json')->success($info);
    }

    /**
     * Xóa bài viết
     * @param int $id
     * @return mixed
     */    public function delete($id = 0)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');
        $this->service->del($id);
        return app('json')->success('Xóa thành công');
    }

    /**
     * Sản phẩm liên quan đến bài viết
     * @param int $id
     * @return mixed
     */    public function relation($id)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');
        list($product_id) = $this->request->postMore([
            ['product_id', 0]
        ], true);
        $res = $this->service->bindProduct($id, $product_id);
        if ($res) {
            return app('json')->success('Hiệp hội thành công');
        } else {
            return app('json')->fail('Liên kết thất bại');
        }
    }

    /**
     * Hủy liên kết sản phẩm
     * @param int $id
     * @return mixed
     */    public function unrelation($id)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');
        $res = $this->service->bindProduct($id);
        if ($res) {
            return app('json')->success('Hủy thành công');
        } else {
            return app('json')->fail('Hủy không thành công');
        }
    }
}
