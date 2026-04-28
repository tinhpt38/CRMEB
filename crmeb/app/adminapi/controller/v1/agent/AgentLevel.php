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
use app\services\agent\AgentLevelServices;
use app\services\agent\AgentLevelTaskServices;
use think\facade\App;

/**
 * bộ điều khiển mức phân phối
 * Class AgentLevel
 * @package app\controller\admin\v1\agent
 */
class AgentLevel extends AuthController
{
    /**
     * @var AgentLevelServices
     */
    protected $services;

    /**
     * AgentLevel constructor.
     * @param App $app
     * @param AgentLevelServices $services
     */
    public function __construct(App $app, AgentLevelServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Danh sách cấp độ phân phối phụ trợ
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index()
    {
        // Nhận thông số yêu cầu, bao gồm trạng thái và từ khóa
        $where = $this->request->getMore([
            ['status', ''],
            ['keyword', '']
        ]);
        // Gọi lớp dịch vụ để lấy danh sách cấp độ
        return app('json')->success($this->services->getLevelList($where));
    }

    /**
     * Thêm biểu mẫu cấp độ phân phối
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function create()
    {
        // Gọi lớp dịch vụ để tạo biểu mẫu thêm
        return app('json')->success($this->services->createForm());
    }

    /**
     * Lưu cấp độ phân phối
     * @return mixed
     */
    public function save()
    {
        // Nhận và xác minh dữ liệu yêu cầu
        $data = $this->request->postMore([
            ['name', ''],
            ['grade', 0],
            ['image', ''],
            ['one_brokerage_percent', 0],
            ['two_brokerage_percent', 0],
            ['status', 0]]);
        if (!$data['name']) return app('json')->fail('Vui lòng nhập tên cấp độ');
        if (!$data['grade']) return app('json')->fail('Vui lòng nhập cấp độ');
        if (!$data['image']) return app('json')->fail('Vui lòng chọn một biểu tượng cấp độ');
        // Xác minh xem tỷ lệ giảm giá cấp hai có lớn hơn tỷ lệ giảm giá cấp một hay không
        if ($data['two_brokerage_percent'] > $data['one_brokerage_percent']) {
            return app('json')->fail('Tỷ lệ giảm giá của cấp độ thứ hai không được lớn hơn tỷ lệ giảm giá của cấp độ thứ nhất.');
        }
        // Kiểm tra xem cấp độ đã tồn tại chưa
        $grade = $this->services->get(['grade' => $data['grade'], 'is_del' => 0]);
        if ($grade) {
            return app('json')->fail('Cấp độ hiện tại đã tồn tại');
        }
        $data['add_time'] = time();
        // lưu dữ liệu
        $this->services->save($data);
        return app('json')->success('Đã thêm cấp độ thành công');
    }

    /**
     * Hiển thị tài nguyên được chỉ định
     * @param $id
     */
    public function read($id)
    {

    }

    /**
     * Chỉnh sửa biểu mẫu cấp độ phân phối
     * @param $id
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function edit($id)
    {
        // Gọi lớp dịch vụ để tạo biểu mẫu chỉnh sửa
        return app('json')->success($this->services->editForm((int)$id));
    }

    /**
     * Sửa đổi mức phân phối
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function update($id)
    {
        // Nhận và xác minh dữ liệu yêu cầu
        $data = $this->request->postMore([
            ['name', ''],
            ['grade', 0],
            ['image', ''],
            ['one_brokerage_percent', 0],
            ['two_brokerage_percent', 0],
            ['status', 0]]);
        if (!$data['name']) return app('json')->fail('Vui lòng nhập tên cấp độ');
        if (!$data['grade']) return app('json')->fail('Vui lòng nhập cấp độ');
        if (!$data['image']) return app('json')->fail('Vui lòng chọn một biểu tượng cấp độ');
        // Xác minh xem tỷ lệ giảm giá cấp hai có lớn hơn tỷ lệ giảm giá cấp một hay không
        if ($data['two_brokerage_percent'] > $data['one_brokerage_percent']) {
            return app('json')->fail('Tỷ lệ giảm giá của cấp độ thứ hai không được lớn hơn tỷ lệ giảm giá của cấp độ thứ nhất.');
        }
        // Kiểm tra xem cấp độ của biên tập viên có tồn tại không
        if (!$levelInfo = $this->services->getLevelInfo((int)$id)) return app('json')->fail('Cấp độ Sửa viên không tồn tại');
        // Kiểm tra xem các cấp độ có bị trùng lặp không
        $grade = $this->services->get(['grade' => $data['grade'], 'is_del' => 0]);
        if ($grade && $grade['id'] != $id) {
            return app('json')->fail('Cấp độ hiện tại đã tồn tại');
        }

        // Cập nhật thông tin cấp độ
        $levelInfo->name = $data['name'];
        $levelInfo->grade = $data['grade'];
        $levelInfo->image = $data['image'];
        $levelInfo->one_brokerage_percent = $data['one_brokerage_percent'];
        $levelInfo->two_brokerage_percent = $data['two_brokerage_percent'];
        $levelInfo->status = $data['status'];
        $levelInfo->save();
        return app('json')->success('Sửa đổi thành công');
    }

    /**
     * Xóa cấp độ phân phối
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function delete($id)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');
        //Kiểm tra xem dữ liệu cấp độ phân phối có tồn tại không
        $levelInfo = $this->services->getLevelInfo((int)$id);
        if ($levelInfo) {
            //Cập nhật dữ liệu đã xóa
            $res = $this->services->update($id, ['is_del' => 1]);
            if (!$res)
                return app('json')->fail('Xóa không thành công');
            //Xóa nhiệm vụ ở cấp độ này sẽ bị xóa
            /** @var AgentLevelTaskServices $agentLevelTaskServices */
            $agentLevelTaskServices = app()->make(AgentLevelTaskServices::class);
            $agentLevelTaskServices->update(['level_id' => $id], ['is_del' => 1]);
        }
        return app('json')->success('Xóa thành công');
    }

    /**
     * Sửa đổi trạng thái
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function set_status($id = 0, $status = '')
    {
        if ($status == '' || $id == 0) return app('json')->fail('Lỗi tham số');
        // cập nhật trạng thái
        $this->services->update($id, ['status' => $status]);
        return app('json')->success('Thiết lập thành công');
    }

    /**
     * Lấy số lượng biểu mẫu nhiệm vụ
     * @param int $id Nhiệm vụID
     * @return \think\response\Json
     */
    public function getTaskNumForm($id)
    {
        // Xác định xem ID tác vụ có phải là 0 hay không, nếu là 0 thì trả về thông báo lỗi
        if ($id == 0) return app('json')->fail('Lỗi tham số');
        // Gọi lớp dịch vụ để lấy số lượng biểu mẫu nhiệm vụ
        $result = $this->services->getTaskNumForm($id);
        // Trả về thông tin thành công và số lượng biểu mẫu nhiệm vụ
        return app('json')->success($result);
    }

    /**
     * Đặt số lượng nhiệm vụ
     * @param int $id Nhiệm vụID
     * @return \think\response\Json
     */
    public function setTaskNum($id)
    {
        // Lấy số lượng nhiệm vụ từ yêu cầu
        $data = $this->request->postMore([
            ['task_num', 0]
        ]);
        // Gọi lớp dịch vụ để đặt số lượng tác vụ
        $res = $this->services->setTaskNum($id, $data);
        return app('json')->success('Thiết lập thành công');
    }
}
