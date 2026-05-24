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
namespace app\adminapi\controller\v1\agent;

use app\adminapi\controller\AuthController;
use app\services\agent\DivisionAgentApplyServices;
use app\services\agent\DivisionServices;
use app\services\other\AgreementServices;
use app\services\user\UserServices;
use crmeb\exceptions\AdminException;
use think\facade\App;

/**
 * Người điều khiển bộ phận
 * Class Division
 * @package app\adminapi\controller\v1\agent
 */class Division extends AuthController
{
    /**
     * @var DivisionServices
     */    protected $services;

    /**
     * Division constructor.
     * @param App $app
     * @param DivisionServices $services
     */    public function __construct(App $app, DivisionServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Danh sách Đơn vị kinh doanh
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function divisionList()
    {
        // Nhận thông số yêu cầu
        $where = $this->request->getMore([
            ['division_type', 0],
            ['keyword', '']
        ]);
        if ($where['division_type'] == 2) {
            $where['division_id'] = $this->adminInfo['division_id'];
        }
        // Gọi lớp dịch vụ để lấy danh sách các Đơn vị kinh doanh
        $data = $this->services->getDivisionList($where);
        return app('json')->success($data);
    }

    /**
     * Danh sách cấp dưới
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function divisionDownList()
    {
        // Lấy thông số: loại Đơn vị kinh doanh, ID khách hàng
        [$type, $uid] = $this->request->getMore([
            ['division_type', 0],
            ['uid', 0],
        ], true);
        // Gọi lớp dịch vụ để lấy danh sách cấp dưới
        $data = $this->services->divisionDownList($type, $uid);
        return app('json')->success($data);
    }

    /**
     * Thêm bộ phận Sửa
     * @param $uid
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     */    public function divisionCreate($uid)
    {
        // Gọi lớp dịch vụ để lấy biểu mẫu Đơn vị kinh doanh
        return app('json')->success($this->services->getDivisionForm((int)$uid));
    }

    /**
     * Phòng bảo quản
     * @return mixed
     */    public function divisionSave()
    {
        // Nhận và xác minh dữ liệu yêu cầu
        $data = $this->request->postMore([
            ['uid', 0],
            ['aid', 0],
            ['division_percent', 0],
            ['division_end_time', ''],
            ['division_status', 1],
            ['account', ''],
            ['pwd', ''],
            ['conf_pwd', ''],
            ['division_name', ''],
            ['roles', []],
            ['image', []]
        ]);
        // Lưu dữ liệu Đơn vị kinh doanh
        $this->services->divisionSave($data);
        return app('json')->success('Đã lưu thành công');
    }

    /**
     * Thêm cơ quan Sửa
     * @param $uid
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     */    public function divisionAgentCreate($uid)
    {
        // Gọi lớp dịch vụ để lấy biểu mẫu tác nhân
        return app('json')->success($this->services->getDivisionAgentForm((int)$uid));
    }

    /**
     * lưu đại lý
     * @param UserServices $userServices
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function divisionAgentSave(UserServices $userServices)
    {
        // Nhận và xác minh dữ liệu yêu cầu
        $data = $this->request->postMore([
            ['division_id', 0],
            ['uid', 0],
            ['division_percent', 0],
            ['division_end_time', ''],
            ['division_status', 1],
            ['division_name', ''],
            ['edit', 0],
            ['image', []],
        ]);
        if ((int)$data['uid'] == 0) $data['uid'] = $data['image']['uid'];
        // Xác minh thông tin Khách hàng
        $userInfo = $userServices->getUserInfo($data['uid'], 'is_division,is_agent,is_staff');
        if (!$userInfo) throw new AdminException('Lỗi tham số');
        if ($data['edit'] == 0) {
            if ($userInfo['is_division']) throw new AdminException('Người dùng này là bộ phận kinh doanh, vui lòng không thêm nó làm đại lý');
            if ($userInfo['is_agent']) throw new AdminException('Người dùng này là đại lý và không thể được thêm nhiều lần');
            if ($userInfo['is_staff']) throw new AdminException('Người dùng này là nhân viên cấp dưới và không thể được thêm làm đại lý');
            // Xác minh thông tin Đơn vị kinh doanh
            $divisionUserInfo = $userServices->count(['uid' => (int)$data['division_id'], 'is_division' => 1, 'division_id' => $data['division_id']]);
            if (!$divisionUserInfo) throw new AdminException('Lỗi tham số');
        }
        // Lưu dữ liệu đại lý
        $this->services->divisionAgentSave($data);
        return app('json')->success('Đã lưu thành công');
    }

    /**
     * Đặt trạng thái
     * @param $status
     * @param $uid
     * @return mixed
     */    public function setDivisionStatus($status, $uid)
    {
        // Gọi lớp dịch vụ để đặt trạng thái
        $this->services->setDivisionStatus($status, $uid);
        return app('json')->success('Thiết lập thành công');
    }

    /**
     * Xóa thành công
     * @param $type
     * @param $uid
     * @return mixed
     */    public function delDivision($type, $uid)
    {
        // Gọi lớp dịch vụ để xóa bộ phận kinh doanh/đại lý
        $this->services->delDivision($type, $uid);
        return app('json')->success('Xóa thành công');
    }

    /**
     * Danh sách Ứng dụng phụ trợ
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function AdminApplyList()
    {
        // Nhận thông số yêu cầu
        $where = $this->request->getMore([
            ['uid', 0],
            ['division_id', 0],
            ['division_invite', ''],
            ['status', ''],
            ['keyword', ''],
            ['time', ''],
        ]);
        $where['division_id'] = $this->adminInfo['division_id'];
        /** @var DivisionAgentApplyServices $applyServices */        $applyServices = app()->make(DivisionAgentApplyServices::class);
        // Nhận danh sách Ứng dụng
        $data = $applyServices->AdminApplyList($where);
        return app('json')->success($data);
    }

    /**
     * Biểu mẫu đánh giá
     * @param $id
     * @param $type
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     */    public function examineApply($id, $type)
    {
        /** @var DivisionAgentApplyServices $applyServices */        $applyServices = app()->make(DivisionAgentApplyServices::class);
        // Nhận mẫu đánh giá
        $data = $applyServices->examineApply($id, $type);
        return app('json')->success($data);
    }

    /**
     * Đánh giá đại lý
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function applyAgentSave()
    {
        // Nhận thông số kiểm tra
        $data = $this->request->getMore([
            ['type', 0],
            ['id', 0],
            ['division_percent', ''],
            ['division_end_time', ''],
            ['division_status', ''],
            ['refusal_reason', 0]
        ]);
        /** @var DivisionAgentApplyServices $applyServices */        $applyServices = app()->make(DivisionAgentApplyServices::class);
        // Lưu kết quả đánh giá
        $data = $applyServices->applyAgentSave($data);
        return app('json')->success('Thiết lập thành công');
    }

    /**
     * Xóa đánh giá đại lý
     * @param $id
     * @return mixed
     */    public function delApply($id)
    {
        /** @var DivisionAgentApplyServices $applyServices */        $applyServices = app()->make(DivisionAgentApplyServices::class);
        // Xóa bản ghi Ứng dụng
        $applyServices->delApply($id);
        return app('json')->success('Xóa thành công');
    }

    /**
     * Thêm biểu mẫu nhân viên
     * @param $uid
     * @return \think\Response
     * @throws \FormBuilder\Exception\FormBuilderException
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2024/1/22
     */    public function divisionStaffCreate($uid)
    {
        // Gọi lớp dịch vụ để lấy biểu mẫu nhân viên
        return app('json')->success($this->services->getDivisionStaffForm((int)$uid));
    }

    /**
     * Lưu nhân viên
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2024/1/22
     */    public function divisionStaffSave()
    {
        // Nhận và xác minh dữ liệu yêu cầu
        $data = $this->request->getMore([
            ['uid', 0],
            ['division_percent', 0],
            ['agent_id', 0],
            ['image', []],
        ]);
        // Lưu dữ liệu nhân viên
        $this->services->divisionStaffSave($data);
        return app('json')->success('Đã lưu thành công');
    }

    /**
     * Thống kê phân phối
     * @return \think\Response
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2025/4/8
     */    public function divisionStatistics()
    {
        // Nhận thông số yêu cầu: loại, thời gian, phân trang, sắp xếp
        [$type, $time, $page, $limit, $sort, $order] = $this->request->getMore([
            ['type', 0],
            ['time', ''],
            ['page', 1],
            ['limit', 15],
            ['sort', 'order_sum'],
            ['order', 'desc'],
        ], true);
        $time = $time != '' ? explode('-', $time) : [];
        // Nhận số liệu thống kê
        $data = $this->services->divisionStatistics($type, $time, $page, $limit, $sort, $order);
        return app('json')->success($data);

    }
}
