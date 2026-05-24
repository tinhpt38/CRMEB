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
namespace app\adminapi\controller\v1\marketing;

use app\adminapi\controller\AuthController;
use app\services\activity\bargain\StoreBargainServices;
use app\services\activity\bargain\StoreBargainUserHelpServices;
use app\services\activity\bargain\StoreBargainUserServices;
use think\facade\App;

/**
 * Quản lý trả giá
 * Class StoreBargain
 * @package app\adminapi\controller\v1\marketing
 */class StoreBargain extends AuthController
{
    /**
     * StoreBargain constructor.
     * @param App $app
     * @param StoreBargainServices $services
     */    public function __construct(App $app, StoreBargainServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Lịch sử trả giá
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function index()
    {
        $where = $this->request->getMore([
            ['start_status', ''],
            ['status', ''],
            ['store_name', ''],
            ['product_id', 0],
        ]);
        $where['is_del'] = 0;
        $list = $this->services->getStoreBargainList($where);
        return app('json')->success($list);
    }

    /**
     * Lưu lại những món hàng giá hời
     * @param $id
     * @return mixed
     */    public function save($id)
    {
        $data = $this->request->postMore([
            ['title', ''],
            ['info', ''],
            ['unit_name', ''],
            ['section_time', []],
            ['image', ''],
            ['images', []],
            ['bargain_max_price', 0],
            ['bargain_min_price', 0],
            ['sort', 0],
            ['give_integral', 0],
            ['is_hot', 0],
            ['status', 0],
            ['product_id', 0],
            ['description', ''],
            ['attrs', []],
            ['items', []],
            ['temp_id', 0],
            ['rule', ''],
            ['num', 1],
            ['copy', 0],
            ['bargain_num', 1],
            ['people_num', 1],
            ['logistics', []],//Phương pháp hậu cần
            ['freight', 1],//Cài đặt phí vận chuyển
            ['postage', 0],//Bưu phí
            ['custom_form', ''],
            ['virtual_type', 0],
            ['is_commission', 0],
        ]);
        $this->validate($data, \app\adminapi\validate\marketing\StoreBargainValidate::class, 'save');
        if ($data['section_time']) {
            [$start_time, $end_time] = $data['section_time'];
            if (strtotime($end_time) < time()) {
                return app('json')->fail('Thời gian kết thúc hoạt động không được nhỏ hơn thời gian hiện tại');
            }
        }
        $bragain = [];
        if ($id) {
            $bragain = $this->services->get((int)$id);
            if (!$bragain) {
                return app('json')->fail('Dữ liệu không tồn tại');
            }
        }
        //Hạn chế chỉnh sửa
        if ($data['copy'] == 0 && $bragain) {
            if ($bragain['stop_time'] < time()) {
                return app('json')->fail('Sự kiện đã kết thúc,Vui lòng thêm lại hoặc sao chép');
            }
        }
        if ($data['copy'] == 1) {
            $id = 0;
            unset($data['copy']);
        }
        $this->services->saveData($id, $data);
        return app('json')->success('Đã lưu thành công');
    }

    /**
     * Nhận thông tin chi tiết
     * @param $id
     * @return mixed
     */    public function read($id)
    {
        $info = $this->services->getInfo($id);
        return app('json')->success(compact('info'));
    }

    /**
     * Xóa món hời
     * @param $id
     * @return mixed
     */    public function delete($id)
    {
        $this->services->update($id, ['is_del' => 1]);
        /** @var StoreBargainUserServices $bargainUserService */        $bargainUserService = app()->make(StoreBargainUserServices::class);
        $bargainUserService->userBargainStatusFail($id, true);
        return app('json')->success('Xóa thành công');
    }

    /**
     * Sửa đổi trạng thái
     * @param $id
     * @param $status
     * @return mixed
     */    public function set_status($id, $status)
    {
        /** @var StoreBargainUserServices $bargainUserService */        $bargainUserService = app()->make(StoreBargainUserServices::class);
        if ($status == 0) {
            $bargainUserService->userBargainStatusFail($id, false);
        } else {
            $info = $this->services->get($id);
            if ($info['stop_time'] < time()) {
                return app('json')->fail('Sự kiện đã kết thúc và không thể thêm vào kệ');
            }
        }
        $this->services->update($id, ['status' => $status]);
        return app('json')->success('Sửa đổi thành công');
    }

    /**
     * Lịch sử trả giá
     * @return mixed
     */    public function bargainList()
    {
        $where = $this->request->getMore([
            ['status', ''],
            ['data', '', '', 'time'],
        ]);
        /** @var StoreBargainUserServices $bargainUserService */        $bargainUserService = app()->make(StoreBargainUserServices::class);
        $list = $bargainUserService->bargainUserList($where);
        return app('json')->success($list);
    }

    /**
     * Thông tin mặc cả
     * @param $id
     * @return mixed
     */    public function bargainListInfo($id)
    {
        /** @var StoreBargainUserHelpServices $bargainUserHelpService */        $bargainUserHelpService = app()->make(StoreBargainUserHelpServices::class);
        $list = $bargainUserHelpService->getHelpList((int)$id);
        return app('json')->success(compact('list'));
    }

    /**
     * Thống kê mặc cả
     * @param $id
     * @return mixed
     */    public function bargainStatistics($id)
    {
        $data = $this->services->bargainStatistics($id);
        return app('json')->success($data);
    }

    /**
     * Lịch sử trả giá
     * @param $id
     * @return mixed
     */    public function bargainStatisticsList($id)
    {
        $where = $this->request->getMore([
            ['real_name', ''],
        ]);
        $data = $this->services->bargainStatisticsList($id, $where);
        return app('json')->success($data);
    }

    /**
     * Đơn hàng mặc cả
     * @param $id
     * @return mixed
     */    public function bargainStatisticsOrder($id)
    {
        $where = $this->request->getMore([
            ['real_name', ''],
            ['status', '']
        ]);
        return app('json')->success($this->services->bargainStatisticsOrder($id, $where));
    }
}
