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
 * Bộ điều khiển tác vụ cấp phân phối
 * Class AgentLevelTask
 * @package app\controller\admin\v1\agent
 */
class AgentLevelTask extends AuthController
{
    /**
     * @var AgentLevelTaskServices
     */
    protected $services;

    /**
     * AgentLevelTask constructor.
     * @param App $app
     * @param AgentLevelTaskServices $services
     */
    public function __construct(App $app, AgentLevelTaskServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Hiển thị danh sách nhiệm vụ cấp độ
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index()
    {
        // Nhận thông số yêu cầu: ID cấp độ, trạng thái, từ khóa
        $where = $this->request->getMore([
            ['id', 0],
            ['status', ''],
            ['keyword', '']
        ]);
        if (!$where['id']) {
            return app('json')->fail('Lỗi tham số');
        }
        $where['level_id'] = $where['id'];
        unset($where['id']);
        // Gọi lớp dịch vụ để lấy danh sách nhiệm vụ cấp độ
        return app('json')->success($this->services->getLevelTaskList($where));
    }

    /**
     * Biểu mẫu bổ sung nhiệm vụ cấp độ
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function create()
    {
        // Nhận cấp độID
        [$level_id] = $this->request->postMore([
            ['level_id', 0]], true);
        if (!$level_id) {
            return app('json')->fail('Lỗi tham số');
        }
        // Gọi lớp dịch vụ để tạo biểu mẫu thêm
        return app('json')->success($this->services->createForm((int)$level_id));
    }

    /**
     * Lưu nhiệm vụ cấp độ
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function save()
    {
        // Nhận và xác minh dữ liệu yêu cầu
        $data = $this->request->postMore([
            ['level_id', 0],
            ['name', ''],
            ['type', ''],
            ['number', 0],
            ['desc', 0],
            ['sort', 0],
            ['status', 0]]);
        if (!$data['level_id']) return app('json')->fail('Lỗi tham số');
        if (!$data['name']) return app('json')->fail('Vui lòng nhập tên nhiệm vụ');
        if (!$data['type']) return app('json')->fail('Vui lòng chọn loại nhiệm vụ');
        if (!$data['number']) return app('json')->fail('Vui lòng nhập số lượng có hạn');
        // Kiểm tra xem loại nhiệm vụ có hợp lệ không
        $this->services->checkTypeTask(0, $data);
        $data['add_time'] = time();
        // Lưu dữ liệu nhiệm vụ
        $this->services->save($data);
        // Số lượng nhiệm vụ cập nhật cấp độ
        $levelInfo = app()->make(AgentLevelServices::class)->get((int)$data['level_id']);
        $levelInfo->task_num = $levelInfo->task_num + 1;
        $levelInfo->task_total_num = $levelInfo->task_total_num + 1;
        $levelInfo->save();
        return app('json')->success('Đã thêm nhiệm vụ thành công');
    }

    /**
     * Hiển thị tài nguyên được chỉ định
     * @param $id
     */
    public function read($id)
    {

    }

    /**
     * Biểu mẫu sửa đổi nhiệm vụ cấp độ
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
     * Sửa đổi nhiệm vụ cấp độ
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
            ['type', ''],
            ['number', 0],
            ['desc', 0],
            ['sort', 0],
            ['status', 0]]);
        if (!$data['name']) return app('json')->fail('Vui lòng nhập tên nhiệm vụ');
        if (!$data['type']) return app('json')->fail('Vui lòng chọn loại nhiệm vụ');
        if (!$data['number']) return app('json')->fail('Vui lòng nhập số lượng có hạn');
        // Kiểm tra xem nhiệm vụ có tồn tại không
        if (!$levelTaskInfo = $this->services->getLevelTaskInfo((int)$id)) return app('json')->fail('Tác vụ đã chỉnh sửa không tồn tại');
        // Kiểm tra xem loại nhiệm vụ có hợp lệ không
        $this->services->checkTypeTask((int)$id, $data);
        // Cập nhật thông tin nhiệm vụ
        $levelTaskInfo->name = $data['name'];
        $levelTaskInfo->type = $data['type'];
        $levelTaskInfo->number = $data['number'];
        $levelTaskInfo->desc = $data['desc'];
        $levelTaskInfo->sort = $data['sort'];
        $levelTaskInfo->status = $data['status'];
        $levelTaskInfo->save();
        return app('json')->success('Sửa đổi thành công');
    }

    /**
     * Xóa nhiệm vụ cấp độ
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function delete($id)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');
        $levelTaskInfo = $this->services->getLevelTaskInfo((int)$id);
        if ($levelTaskInfo) {
            // Đánh dấu để xóa
            $res = $this->services->update($id, ['is_del' => 1]);
            if ($res) {
                // Số lượng nhiệm vụ cập nhật cấp độ
                $levelInfo = app()->make(AgentLevelServices::class)->get((int)$levelTaskInfo['level_id']);
                $levelInfo->task_num = $levelInfo->task_num - 1;
                $levelInfo->task_total_num = $levelInfo->task_total_num - 1;
                if ($levelInfo->task_num <= 0) $levelInfo->task_num = $levelInfo->task_total_num;
                $levelInfo->save();
            } else {
                return app('json')->fail('Xóa không thành công');
            }
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

}
