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
use app\adminapi\controller\v1\system\SystemClearData;
use app\services\order\StoreCartServices;
use app\services\other\CacheServices;
use app\services\product\product\StoreCategoryServices;
use app\services\product\product\StoreProductServices;
use crmeb\services\FileService;
use app\services\other\UploadService;
use think\facade\App;
use think\Request;

/**
 * Class StoreProduct
 * @package app\adminapi\controller\v1\product
 */class StoreProduct extends AuthController
{
    protected $service;

    public function __construct(App $app, StoreProductServices $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    /**
     * Hiển thị tiêu đề danh sách tài nguyên
     * @return mixed
     */    public function type_header()
    {
        $where = $this->request->getMore([
            ['store_name', ''],
            ['cate_id', ''],
            ['spec_type', ''],
            ['is_gift', ''],
            ['vip_product', ''],
            ['price_s', []],
            ['stock_s', []],
            ['sales_s', []],
        ]);
        $list = $this->service->getHeader($where);
        return app('json')->success(compact('list'));
    }

    /**
     * Thoát dữ liệu chưa được lưu
     * @param CacheServices $services
     * @return mixed
     */    public function getCacheData(CacheServices $services)
    {
        return app('json')->success(['info' => $services->getDbCache($this->adminId . '_product_data', [])]);
    }

    /**
     * 1Lưu dữ liệu sản phẩm mỗi phút
     * @param CacheServices $services
     * @return mixed
     */    public function saveCacheData(CacheServices $services)
    {
        $data = $this->request->postMore([
            ['cate_id', []],
            ['store_name', ''],
            ['store_info', ''],
            ['keyword', ''],
            ['unit_name', 'miếng'],
            ['image', []],
            ['recommend_image', ''],
            ['slider_image', []],
            ['postage', 0],
            ['is_sub', []],//Hoa hồng là riêng biệt hay mặc định?
            ['sort', 0],
            ['sales', 0],
            ['ficti', 100],
            ['give_integral', 0],
            ['is_show', 0],
            ['temp_id', 0],
            ['is_hot', 0],
            ['is_benefit', 0],
            ['is_best', 0],
            ['is_new', 0],
            ['mer_use', 0],
            ['is_postage', 0],
            ['is_good', 0],
            ['description', ''],
            ['spec_type', 0],
            ['video_link', ''],
            ['items', []],
            ['attrs', []],
            ['activity', []],
            ['coupon_ids', []],
            ['label_id', []],
            ['command_word', ''],
            ['tao_words', ''],
            ['type', 0]
        ]);
        $services->setDbCache($this->adminId . '_product_data', $data, 68400);
        return app('json')->success('Đã lưu thành công');
    }

    /**
     * Xóa bộ đệm dữ liệu
     * @param CacheServices $services
     * @return mixed
     */    public function deleteCacheData(CacheServices $services)
    {
        $services->delectDbCache($this->adminId . '_product_data');
        return app('json')->success('Xóa thành công');
    }

    /**
     * Hiển thị danh sách tài nguyên
     * @return mixed
     */    public function index()
    {
        $where = $this->request->getMore([
            ['store_name', ''],
            ['cate_id', ''],
            ['type', 1],
            ['sales', 'normal'],
            ['spec_type', ''],
            ['is_gift', ''],
            ['vip_product', ''],
            ['price_s', []],
            ['stock_s', []],
            ['sales_s', []],
            ['store_label_id', []],
            ['logistics', ''],
            ['time', ''],
            ['virtual_type', ''],
            ['store_id', ''],
        ]);
        $data = $this->service->getList($where);
        return app('json')->success($data);
    }

    /**
     * Sửa đổi trạng thái
     * @param string $is_show
     * @param string $id
     * @return mixed
     */    public function set_show($is_show = '', $id = '')
    {
        $del = $this->service->value(['id' => $id], 'is_del');
        if ($del == 1) return app('json')->fail('Sản phẩm đã bị xóa, vui lòng khôi phục sản phẩm trước');
        $this->service->setShow([$id], $is_show);
        return app('json')->success('Thiết lập thành công');
    }

    /**
     * Thiết lập kệ sản phẩm hàng loạt
     * @return mixed
     */    public function product_show()
    {
        [$ids] = $this->request->postMore([
            ['ids', []]
        ], true);
        $this->service->setShow($ids, 1);
        return app('json')->success('Thiết lập thành công');
    }

    /**
     * Thiết lập loại bỏ sản phẩm hàng loạt
     * @return mixed
     */    public function product_unshow()
    {
        [$ids] = $this->request->postMore([
            ['ids', []]
        ], true);
        $this->service->setShow($ids, 0);
        return app('json')->success('Thiết lập thành công');
    }

    /**
     * Nhận mẫu thông số kỹ thuật
     * @return mixed
     */    public function get_rule()
    {
        $list = $this->service->getRule();
        return app('json')->success($list);
    }

    /**
     * Nhận chi tiết sản phẩm
     * @param int $id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function get_product_info($id = 0)
    {
        return app('json')->success($this->service->getInfo((int)$id));
    }

    /**
     * Lưu mới hoặc chỉnh sửa
     * @param $id
     * @return mixed
     * @throws \Exception
     */    public function save($id)
    {
        $data = $this->request->postMore([
            ['virtual_type', 0],// Loại sản phẩm
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
            ['temp_id', 0],//Mẫu vận chuyển sản phẩmid
            ['give_integral', 0],//Tặng điểm
            ['presale', 0],//Chuyển đổi sản phẩm trước khi bán
            ['presale_time', 0],//Thời gian bán trước
            ['presale_day', 0],//Ngày vận chuyển trước khi bán
            ['vip_product', 0],//Có nên trả tiền cho các sản phẩm thành viên hay không
            ['vip_product_type', 0],//0Chỉ hiển thị với thành viên trả phí,1Chỉ dành cho thành viên trả phí
            ['is_sub', []],//Hoa hồng là riêng biệt hay mặc định?
            ['recommend', []],//Khuyến nghị sản phẩm
            ['activity', []],//Ưu tiên hoạt động
            ['recommend_list', []],//Sản phẩm được khuyên dùng chất lượng cao
            ['coupon_ids', []],//Mã giảm giá
            ['label_id', []],//Thẻ khách hàng
            ['command_word', ''],//Mật khẩu sản phẩm
            ['is_show', 0],//Nó có ở trên kệ không?
            ['ficti', 0],//bán hàng ảo
            ['sort', 0],//loại
            ['recommend_image', ''],//Hình ảnh gợi ý sản phẩm
            ['sales', 0],//Doanh số bán hàng
            ['custom_form', []],//Biểu mẫu tùy chỉnh
            ['type', 0],
            ['is_copy', 0],//Nó có phải là một sản phẩm sao chép?
            ['is_limit', 0],//Có giới hạn mua hàng không?
            ['limit_type', 0],//Loại hạn chế mua hàng
            ['limit_num', 0],//Giới hạn mua hàng
            ['min_qty', 1],//Số lượng mua tối thiểu
            ['params_list', []],//Thuộc tính sản phẩm
            ['label_list', []],//Nhãn sản phẩm
            ['protection_list', []],//Bảo hành sản phẩm
            ['is_gift', 0],//Nó có phải là một món quà?
            ['gift_price', 0],//phụ phí quà tặng
            ['store_id', 0],//Cửa hàng
        ]);
        $this->service->save((int)$id, $data);
        return app('json')->success('Đã lưu thành công');
    }

    /**
     * Xóa tài nguyên được chỉ định
     *
     * @param int $id
     * @return \think\Response
     */    public function delete($id)
    {
        //Xóa một sản phẩm để kiểm tra xem nó đã tham gia hoạt động chưa
        $this->service->checkActivity($id);
        $res = $this->service->del($id);
        /** @var StoreCartServices $cartService */        $cartService = app()->make(StoreCartServices::class);
        $cartService->changeStatus($id, 0);
        return app('json')->success($res);
    }

    /**
     * Di chuyển các lô vào thùng rác
     * @return \think\Response
     */    public function batchDelete()
    {
        [$ids] = $this->request->postMore([
            ['ids', []],
        ], true);
        return app('json')->success($this->service->batchDelete($ids));
    }

    /**
     * Khôi phục hàng loạt từ Thùng rác
     * @return \think\Response
     */    public function batchRecover()
    {
        [$ids] = $this->request->postMore([
            ['ids', []],
        ], true);
        $this->service->batchRecover($ids);
        return app('json')->success('Khôi phục thành công');
    }

    /**
     * Tạo danh sách thông số kỹ thuật
     * @param int $id
     * @param int $type
     * @return mixed
     */    public function is_format_attr($id = 0, $type = 0)
    {
        $data = $this->request->postMore([
            ['attrs', []],
            ['items', []],
            ['is_virtual', 0],
            ['virtual_type', 0]
        ]);
        $info = $this->service->getAttr($data, $id, $type);
        return app('json')->success(compact('info'));
    }


    /**
     * Lấy danh sách sản phẩm đã chọn
     * @return mixed
     */    public function search_list()
    {
        $where = $this->request->getMore([
            ['cate_id', ''],
            ['store_name', ''],
            ['type', 1],
            ['is_live', 0],
            ['is_new', ''],
            ['is_virtual', -1],
            ['is_presale', -1],
            ['is_show', 1],
        ]);
        $where['is_del'] = 0;
        $where['cate_id'] = toIntArray($where['cate_id']);
        /** @var StoreCategoryServices $storeCategoryServices */        $storeCategoryServices = app()->make(StoreCategoryServices::class);
        if ($where['cate_id'] !== '') {
            if ($storeCategoryServices->value(['id' => $where['cate_id']], 'pid')) {
                $where['sid'] = $where['cate_id'];
            } else {
                $where['cid'] = $where['cate_id'];
            }
        }
        unset($where['cate_id']);
        $list = $this->service->searchList($where);
        return app('json')->success($list);
    }

    /**
     * Lấy thông số kỹ thuật của sản phẩm
     * @return mixed
     */    public function get_attrs()
    {
        [$id, $type] = $this->request->getMore([
            [['id', 'd'], 0],
            [['type', 'd'], 0],
        ], true);
        $info = $this->service->getProductRules($id, $type);
        return app('json')->success(compact('info'));
    }

    /**
     * Nhận danh sách các mẫu vận chuyển sản phẩm
     * @return mixed
     */    public function get_template()
    {
        return app('json')->success($this->service->getTemp());
    }

    /**
     * Nhận video tải lêntoken
     * @return mixed
     * @throws \Exception
     */    public function getTempKeys(Request $request)
    {
        $upload = UploadService::init();
        $type = (int)sys_config('upload_type', 1);
        $key = $request->get('key', '');
        $path = $request->get('path', '');
        $contentType = $request->get('contentType', '');
        if ($type === 5) {
            if (!$key || !$contentType) {
                return app('json')->fail('Thiếu tham số');
            }
            $re = $upload->getTempKeys($key, $path, $contentType);
        } else {
            $re = $upload->getTempKeys();
        }
        return $re ? app('json')->success($re) : app('json')->fail('Không thể lấy được');
    }

    /**
     * Kiểm tra xem sản phẩm có hoạt động không
     * @param $id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function check_activity($id)
    {
        $this->service->checkActivity($id);
        return app('json')->success('Xóa thành công');
    }

    /**
     * Nhập khẩu bí mật thẻ
     * @return mixed
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */    public function import_card()
    {
        $data = $this->request->getMore([
            ['file', ""]
        ]);
        if (!$data['file']) return app('json')->fail('Vui lòng tải tập tin lên');
        $file = public_path() . substr($data['file'], 1);
        // Nhận hậu tố tập tin
        $suffix = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (!in_array($suffix, ['xls', 'xlsx'])) {
            return app('json')->fail('Định dạng tệp không chính xác, vui lòng tải lên tệp ở định dạng xls hoặc xlsx！');
        }
        /** @var FileService $readExcelService */        $readExcelService = app()->make(FileService::class);
        $cardData = $readExcelService->readExcel($file, 'card', 1, ucfirst($suffix));
        return app('json')->success($cardData);
    }

    /**
     * Cài đặt lô sản phẩm
     * @return mixed
     */    public function batchSetting()
    {
        $data = $this->request->postMore([
            ['ids', []],
            ['cate_id', []],
            ['logistics', []],
            ['freight', 2],
            ['postage', 0],
            ['temp_id', 1],
            ['give_integral', 0],
            ['coupon_ids', []],
            ['label_id', []],
            ['label_list', []],
            ['recommend', []],
            ['type', 0],
            ['is_gift', 0],
            ['gift_price', 0],
        ]);
        $this->service->batchSetting($data);
        return app('json')->success('Thiết lập thành công');
    }

    /**
     * Giao diện loại sản phẩm
     * @return \think\Response
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/9/29
     */    public function productTypeConfig()
    {
        $productTypeConfig = sys_config('product_type_config');
        foreach ($productTypeConfig as $key => $value) {
            $productTypeConfig[$key] = intval($value);
        }
        return app('json')->success(sys_config('product_type_config'));
    }

    /**
     * Xuất file sản phẩm
     * @return \think\Response
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/10/9
     */    public function productExport()
    {
        $where = $this->request->getMore([
            ['store_name', ''],
            ['cate_id', ''],
            ['type', 1],
            ['sales', 'normal']
        ]);
        $where['virtual_type'] = 0;
        return app('json')->success($this->service->productExportList($where));
    }

    /**
     * Di chuyển và nhập khẩu sản phẩm
     * @return \think\Response
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/10/9
     */    public function productImport()
    {
        [$file] = $this->request->getMore([
            ['file', ""]
        ], true);
        if (!$file) return app('json')->fail('Vui lòng tải tập tin lên');
        $res = $this->service->productImport($file);
        return app('json')->success('Nhập thành công', $res);
    }

    /**
     * Xóa hoàn toàn các mục khỏi thùng rác
     * @param $id
     * @return \think\Response
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/10/9
     */    public function fullDel($id)
    {
        app()->make(SystemClearData::class)->recycleProduct($id);
        return app('json')->success('Xóa thành công');
    }

    public function otherInfo($id, $type)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');
        return app('json')->success($this->service->otherInfo($id, $type));
    }

    public function otherSave($id, $type)
    {
        $data = $this->request->postMore([
            ['is_sub', 0],
            ['is_vip', 0],
            ['vip_product', 0],
            ['vip_product_type', 0],
            ['attr_value', []],
        ]);
        $this->service->otherSave($id, $type, $data);
        return app('json')->success('Đã lưu thành công');
    }
}
