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
namespace app\adminapi\controller\v1\marketing\live;

use app\adminapi\controller\AuthController;
use app\services\activity\live\LiveGoodsServices;
use think\facade\App;

/**
 * Sản phẩm phòng phát sóng trực tiếp
 * Class LiveGoods
 * @package app\controller\admin\store
 */
class LiveGoods extends AuthController
{
    /**
     * LiveGoods constructor.
     * @param App $app
     * @param LiveGoodsServices $services
     */
    public function __construct(App $app, LiveGoodsServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Danh sách sản phẩm phòng phát sóng trực tiếp
     * @return mixed
     */
    public function list()
    {
        $where = $this->request->postMore([
            ['kerword', ''],
            ['status', ''],
            ['is_show', ''],
            ['live_id', 0]
        ]);
        return app('json')->success($this->services->getList($where));
    }

    /**
     * Tạo sản phẩm phát sóng trực tiếp
     * @return mixed
     */
    public function create()
    {
        [$product_ids] = $this->request->postMore([
            ['product_id', []]
        ], true);
        return app('json')->success($this->services->create($product_ids));
    }

    /**
     * Tải lên sản phẩm trực tiếp
     * @return mixed
     * @throws \EasyWeChat\Core\Exceptions\InvalidArgumentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function add()
    {
        [$goods_info] = $this->request->postMore([
            ['goods_info', []]
        ], true);
        $error = false;
        foreach ($goods_info as $goods) {
            $this->validate($goods, \app\adminapi\validate\marketing\LiveGoodsValidate::class, 'save');
            if (!preg_match('/.*(\.png|\.jpg|\.jpeg|\.gif)$/', $goods['image']) && strpos(strtolower($goods['image']), "phar://") !== false) {
                $error = true;
            }
        }
        if ($error) return app('json')->fail(40137);
        $this->services->add($goods_info);
        return app('json')->success('Đã lưu thành công');
    }

    /**
     * Chi tiết sản phẩm
     * @param $id
     * @return mixed
     */
    public function detail($id)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');
        $goods = $this->services->get($id, ['*'], ['product']);
        return app('json')->success($goods ? $goods->toArray() : []);
    }

    /**
     * Đồng bộ hóa các sản phẩm phát sóng trực tiếp
     * @return mixed
     */
    public function syncGoods()
    {
        $this->services->syncGoodStatus();
        return app('json')->success('Đồng bộ hóa thành công');
    }

    /**
     * Gửi lại để xem xét
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function audit($id)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');
        $this->services->audit((int)$id);
        return app('json')->success('Thiết lập thành công');
    }

    /**
     * Rút lại đánh giá
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function resetAudit($id)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');
        $this->services->resetAudit((int)$id);
        return app('json')->success('Thiết lập thành công');
    }

    /**
     * Đặt trạng thái
     * @param int $id
     * @param $is_show
     * @return mixed
     */
    public function setShow(int $id, $is_show)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');
        return app('json')->success($this->services->isShow($id, $is_show));
    }

    /**
     * Xóa sản phẩm
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function delete($id)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');
        $this->services->delete($id);
        return app('json')->success('Xóa thành công');
    }

}
