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
namespace app\outapi\controller;

use app\services\order\StoreCartServices;
use app\services\product\product\StoreCategoryServices;
use app\services\product\product\OutStoreProductServices;
use think\facade\App;

/**
 * Class StoreProduct
 * @package aapp\outapi\controller
 */class StoreProduct extends AuthController
{
    protected $services;

    public function __construct(App $app, OutStoreProductServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Hiển thị danh sách tài nguyên
     * @return mixed
     */    public function index()
    {
        $where = $this->request->getMore([
            ['cate_id', ''],
            ['store_name', ''],
            ['type', 1],
            ['is_live', 0],
            ['is_new', ''],
            ['is_virtual', -1],
            ['is_presale', -1]
        ]);
        $where['is_del'] = 0;
        /** @var StoreCategoryServices $storeCategoryServices */        $storeCategoryServices = app()->make(StoreCategoryServices::class);
        if ($where['cate_id'] !== '') {
            if ($storeCategoryServices->value(['id' => $where['cate_id']], 'pid')) {
                $where['sid'] = $where['cate_id'];
            } else {
                $where['cid'] = $where['cate_id'];
            }
        }

        unset($where['cate_id']);
        $list = $this->services->searchList($where);
        return app('json')->success($list);
    }

    /**
     * Sửa đổi trạng thái
     * @param string $id
     * @param string $is_show
     */    public function set_show($id = '', $is_show = '')
    {
        if ($id == '' || $is_show == '') return app('json')->fail('Lỗi tham số');
        $this->services->setShow((int)$id, (int)$is_show);
        return app('json')->success($is_show == 1 ? 'Hiển thị thành công' : 'Ẩn thành công');
    }

    /**
     * Nhận thông tin sản phẩm
     * @param $id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function read($id = 0)
    {
        return app('json')->success($this->services->getInfo((int)$id));
    }

    /**
     * Lưu
     * @return mixed
     * @throws \Exception
     */    public function save()
    {
        $data = $this->request->postMore([
            ['cate_id', []],//Phân loạiid
            ['store_name', ''],//Tên sản phẩm
            ['keyword', ''],//Từ khóa
            ['unit_name', 'miếng'],//Đơn vị
            ['store_info', ''],//Giới thiệu sản phẩm
            ['slider_image', []],//băng chuyền
            ['video_open', 0],//Có bật video hay không
            ['video_link', ''],//Liên kết video
            ['spec_type', 0],//Thông số kỹ thuật đơn và nhiều
            ['items', []],//Đặc điểm kỹ thuật
            ['attrs', []],//Đặc điểm kỹ thuật
            ['description', ''],//Chi tiết sản phẩm
            ['description_images', []],//Chi tiết sản phẩm
            ['logistics', []],//Phương pháp hậu cần
            ['freight', 1],//Cài đặt phí vận chuyển
            ['postage', 0],//Bưu phí
            ['is_sub', 0],//Hoa hồng là riêng biệt hay mặc định?
            ['is_vip', 0],//Giá thành viên trả phí
            ['recommend', []],//Khuyến nghị sản phẩm
            ['temp_id', 0],//Mẫu vận chuyển sản phẩmid
            ['give_integral', 0],//Tặng điểm
            ['presale', 0],//Chuyển đổi sản phẩm trước khi bán
            ['presale_time', 0],//Thời gian bán trước
            ['presale_day', 0],//Ngày vận chuyển trước khi bán
            ['vip_product', 0],//Có nên trả tiền cho các sản phẩm thành viên hay không
            ['activity', []],//Ưu tiên hoạt động
            ['command_word', ''],//Mật khẩu sản phẩm
            ['is_show', 0],//Nó có ở trên kệ không?
            ['ficti', 0],//bán hàng ảo
            ['sort', 0],//loại
            ['recommend_image', ''],//Hình ảnh gợi ý sản phẩm
            ['custom_form', []],//Biểu mẫu tùy chỉnh
            ['is_limit', 0],//Có giới hạn mua hàng không?
            ['limit_type', 0],//Loại hạn chế mua hàng
            ['limit_num', 0]//Giới hạn mua hàng
        ]);
        $id = $this->services->save(0, $data);
        return app('json')->success('Đã lưu thành công', ['id' => $id]);
    }

    /**
     * gia hạn
     * @param $id
     * @return mixed
     */    public function update($id)
    {
        $data = $this->request->postMore([
            ['cate_id', []],//Phân loạiid
            ['store_name', ''],//Tên sản phẩm
            ['keyword', ''],//Từ khóa
            ['unit_name', 'miếng'],//Đơn vị
            ['store_info', ''],//Giới thiệu sản phẩm
            ['slider_image', []],//băng chuyền
            ['video_open', 0],//Có bật video hay không
            ['video_link', ''],//Liên kết video
            ['spec_type', 0],//Thông số kỹ thuật đơn và nhiều
            ['items', []],//Đặc điểm kỹ thuật
            ['attrs', []],//Đặc điểm kỹ thuật
            ['description', ''],//Chi tiết sản phẩm
            ['description_images', []],//Chi tiết sản phẩm
            ['logistics', []],//Phương pháp hậu cần
            ['freight', 1],//Cài đặt phí vận chuyển
            ['postage', 0],//Bưu phí
            ['is_sub', 0],//Hoa hồng là riêng biệt hay mặc định?
            ['is_vip', 0],//Giá thành viên trả phí
            ['recommend', []],//Khuyến nghị sản phẩm
            ['temp_id', 0],//Mẫu vận chuyển sản phẩmid
            ['give_integral', 0],//Tặng điểm
            ['presale', 0],//Chuyển đổi sản phẩm trước khi bán
            ['presale_time', 0],//Thời gian bán trước
            ['presale_day', 0],//Ngày vận chuyển trước khi bán
            ['vip_product', 0],//Có nên trả tiền cho các sản phẩm thành viên hay không
            ['activity', []],//Ưu tiên hoạt động
            ['command_word', ''],//Mật khẩu sản phẩm
            ['is_show', 0],//Nó có ở trên kệ không?
            ['ficti', 0],//bán hàng ảo
            ['sort', 0],//loại
            ['recommend_image', ''],//Hình ảnh gợi ý sản phẩm
            ['custom_form', []],//Biểu mẫu tùy chỉnh
            ['is_limit', 0],//Có giới hạn mua hàng không?
            ['limit_type', 0],//Loại hạn chế mua hàng
            ['limit_num', 0]//Giới hạn mua hàng
        ]);
        $this->services->save((int)$id, $data);
        return app('json')->success('Sửa đổi thành công');
    }

    /**
     * Xóa
     * @param int $id
     * @return \think\Response
     */    public function delete($id)
    {
        //Xóa một sản phẩm để kiểm tra xem nó đã tham gia hoạt động chưa
        $this->services->checkActivity($id);
        $res = $this->services->del($id);
        /** @var StoreCartServices $cartService */        $cartService = app()->make(StoreCartServices::class);
        $cartService->changeStatus($id, 0);
        return app('json')->success($res);
    }

    /**
     * Đồng bộ hóa hàng tồn kho
     * @return void
     */    public function uploadStock()
    {
        [$items] = $this->request->postMore([['items', []]], true);

        foreach ($items as $item) {
            if (!isset($item['bar_code']) || !isset($item['bar_code_number']) || !isset($item['qty'])) {
                return app('json')->fail('Vui lòng kiểm tra mã thuộc tính hoặc số lượng hàng tồn kho');
            }
        }

        if (count($items) > 100) {
            return app('json')->fail('Số lượng mục được đồng bộ hóa không thể vượt quá100');
        }

        $this->services->syncStock($items);
        return app('json')->success('Hoạt động thành công');
    }
}
