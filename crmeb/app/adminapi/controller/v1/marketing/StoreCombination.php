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
use app\services\activity\combination\StoreCombinationServices;
use app\services\activity\combination\StorePinkServices;
use think\facade\App;

/**
 * Quản lý nhóm
 * Class StoreCombination
 * @package app\admin\controller\store
 */
class StoreCombination extends AuthController
{
    /**
     * StoreCombination constructor.
     * @param App $app
     * @param StoreCombinationServices $services
     */
    public function __construct(App $app, StoreCombinationServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Danh sách nhóm nhóm
     * @return mixed
     */
    public function index()
    {
        $where = $this->request->getMore([
            ['start_status', ''],
            ['is_show', ''],
            ['store_name', ''],
            ['product_id', 0]
        ]);
        $where['is_del'] = 0;
        $list = $this->services->systemPage($where);
        return app('json')->success($list);
    }

    /**
     * Thống kê nhóm nhóm
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function statistics()
    {
        /** @var StorePinkServices $storePinkServices */
        $storePinkServices = app()->make(StorePinkServices::class);
        $info = $storePinkServices->getStatistics();
        return app('json')->success($info);
    }

    /**
     * Chi tiết
     * @param $id
     * @return mixed
     */
    public function read($id)
    {
        $info = $info = $this->services->getInfo((int)$id);
        return app('json')->success(compact('info'));
    }

    /**
     * Lưu tài nguyên mới
     * @param int $id
     */
    public function save($id = 0)
    {
        $data = $this->request->postMore([
            [['product_id', 'd'], 0],
            [['title', 's'], ''],
            [['info', 's'], ''],
            [['unit_name', 's'], ''],
            ['image', ''],
            ['images', []],
            ['section_time', []],
            [['is_host', 'd'], 0],
            [['is_show', 'd'], 0],
            [['num', 'd'], 0],
            [['temp_id', 'd'], 0],
            [['effective_time', 'd'], 0],
            [['people', 'd'], 0],
            [['description', 's'], ''],
            ['attrs', []],
            ['items', []],
            ['num', 1],
            ['once_num', 1],
            ['sort', 0],
            ['copy', 0],
            ['virtual', 100],
            ['logistics', []],//Phương pháp hậu cần
            ['freight', 1],//Cài đặt phí vận chuyển
            ['postage', 0],//Bưu phí
            ['custom_form', ''],
            ['virtual_type', 0],
            ['is_commission', 0],
            ['head_commission', 0],
        ]);
        $this->validate($data, \app\adminapi\validate\marketing\StoreCombinationValidate::class, 'save');
        if ($data['section_time']) {
            [$start_time, $end_time] = $data['section_time'];
            if (strtotime($end_time) < time()) {
                return app('json')->fail('Thời gian kết thúc hoạt động không được nhỏ hơn thời gian hiện tại');
            }
        }
        $combination = [];
        if ($id) {
            $combination = $this->services->get((int)$id);
            if (!$combination) {
                return app('json')->fail('Dữ liệu không tồn tại');
            }
        }
        //Hạn chế chỉnh sửa
        if ($data['copy'] == 0 && $combination) {
            if ($combination['stop_time'] < time()) {
                return app('json')->fail('Sự kiện đã kết thúc,Vui lòng thêm lại hoặc sao chép');
            }
        }
        if ($data['num'] < $data['once_num']) {
            return app('json')->fail('Giới hạn số lượng mua một lần không thể lớn hơn tổng số lượng mua');
        }
        if ($data['copy'] == 1) {
            $id = 0;
            unset($data['copy']);
        }
        $this->services->saveData($id, $data);
        return app('json')->success('Đã lưu thành công');
    }

    /**
     * Xóa chuyến tham quan theo nhóm
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $this->services->update($id, ['is_del' => 1]);
        return app('json')->success('Xóa thành công');
    }

    /**
     * Sửa đổi trạng thái
     * @param $id
     * @param $status
     * @return mixed
     */
    public function set_status($id, $status)
    {
        if ($status == 1) {
            $info = $this->services->get($id);
            if ($info['stop_time'] < time()) {
                return app('json')->fail('Sự kiện đã kết thúc và không thể thêm vào kệ');
            }
        }
        $this->services->update($id, ['is_show' => $status]);
        return app('json')->success('Thiết lập thành công');
    }

    /**
     * Danh sách nhóm nhóm
     * @return mixed
     */
    public function combine_list()
    {
        $where = $this->request->getMore([
            ['status', ''],
            ['data', '', '', 'time'],
        ]);
        /** @var StorePinkServices $storePinkServices */
        $storePinkServices = app()->make(StorePinkServices::class);
        $list = $storePinkServices->systemPage($where);
        return app('json')->success($list);
    }

    /**
     * Danh sách những người tham gia nhóm
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function order_pink($id)
    {
        /** @var StorePinkServices $storePinkServices */
        $storePinkServices = app()->make(StorePinkServices::class);
        $list = $storePinkServices->getPinkMember($id);
        return app('json')->success(compact('list'));
    }

    /**
     * Thống kê nhóm nhóm
     * @param $id
     * @return mixed
     */
    public function combinationStatistics($id)
    {
        $data = $this->services->combinationStatistics($id);
        return app('json')->success($data);
    }

    /**
     * Người tham gia sự kiện
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function combinationStatisticsList($id)
    {
        $where = $this->request->getMore([
            ['real_name', '', '', 'keyword'],
            ['status', '']
        ]);
        $where['cid'] = $id;
        /** @var StorePinkServices $storePinkServices */
        $storePinkServices = app()->make(StorePinkServices::class);
        $list = $storePinkServices->systemPage($where);
        return app('json')->success($list);
    }

    /**
     * Thứ tự nhóm
     * @param $id
     * @return mixed
     */
    public function combinationStatisticsOrder($id)
    {
        $where = $this->request->getMore([
            ['real_name', ''],
            ['status', '']
        ]);
        return app('json')->success($this->services->combinationStatisticsOrder($id, $where));
    }

    /**
     * Lập nhóm ngay
     * @param $id
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2025/6/18
     */
    public function immediatelyCombination($id)
    {
        /** @var StorePinkServices $storePinkServices */
        $storePinkServices = app()->make(StorePinkServices::class);
        $storePinkServices->virtualCombination($id, 'admin');
        return app('json')->success('Thành lập nhóm thành công');
    }
}
