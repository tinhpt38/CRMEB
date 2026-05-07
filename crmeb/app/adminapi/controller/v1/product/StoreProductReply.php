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
use app\services\product\product\StoreProductReplyServices;
use think\facade\App;

/**
 * Bộ điều khiển quản lý bình luận
 * Class StoreProductReply
 * @package app\admin\controller\store
 */
class StoreProductReply extends AuthController
{
    /**
     * @var StoreProductReplyServices
     */
    protected $services;
    
    /**
     * Người xây dựng
     * @param App $app
     * @param StoreProductReplyServices $service
     * @var StoreProductReplyServices $services
     */
    public function __construct(App $app, StoreProductReplyServices $service)
    {
        parent::__construct($app);
        $this->services = $service;
    }

    /**
     * Hiển thị danh sách tài nguyên
     *
     * @return \think\Response
     */
    public function index()
    {
        $where = $this->request->getMore([
            ['is_reply', ''],
            ['store_name', ''],
            ['account', ''],
            ['data', ''],
            ['product_id', 0],
            ['key', ''],
            ['order', ''],
            ['status', ''],
        ]);
        $list = $this->services->sysPage($where);
        return app('json')->success($list);
    }

    /**
     * Xóa bình luận
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $this->services->del($id);
        return app('json')->success('Xóa thành công');
    }

    /**
     * Trả lời bình luận
     * @param $id
     * @return mixed
     */
    public function set_reply($id)
    {
        [$content] = $this->request->postMore([
            ['content', '']
        ], true);
        $this->services->setReply($id, $content);
        return app('json')->success('Trả lời thành công');
    }

    /**
     * Tạo một mẫu bình luận ảo
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function fictitious_reply($product_id = 0)
    {
        if (!$product_id) {
            $product_id = (int)$this->request->param('product_id', 0);
        }
        return app('json')->success($this->services->createForm($product_id));
    }

    /**
     * Lưu đánh giá ảo
     * @return mixed
     */
    public function save_fictitious_reply()
    {
        $data = $this->request->postMore([
            ['image', ''],
            ['nickname', ''],
            ['avatar', ''],
            ['comment', ''],
            ['pics', []],
            ['product_score', 0],
            ['service_score', 0],
            ['product_id', 0],
            ['add_time', 0],
            ['suk', ''],
        ]);
        if (!$data['product_id']) {
            $data['product_id'] = $data['image']['product_id'] ?? '';
        }
        $this->validate(['product_id' => $data['product_id'], 'nickname' => $data['nickname'], 'avatar' => $data['avatar'], 'comment' => $data['comment'], 'product_score' => $data['product_score'], 'service_score' => $data['service_score']], \app\adminapi\validate\product\StoreProductReplyValidate::class, 'save');
        $this->services->saveReply($data);
        return app('json')->success('Đã lưu thành công');
    }

    /**
     * Đánh giá đánh giá sản phẩm
     * @param $id
     * @param $status
     * @return \think\Response
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/4/22
     */
    public function set_status($id, $status)
    {
        $this->services->update($id, ['status' => $status]);
        return app('json')->success($status == 1 ? 'Duyệt thành công' : 'Từ chối thành công');
    }

    /**
     * Đánh giá đánh giá sản phẩm hàng loạt
     * @return \think\Response
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2025/6/18
     */
    public function batch_set_status()
    {
        list($ids, $status) = $this->request->postMore([
            ['ids', []],
            ['status', 0]
        ], true);
        $this->services->batchUpdate($ids, ['status' => $status]);
        return app('json')->success($status == 1 ? 'Duyệt thành công' : 'Từ chối thành công');
    }
}
