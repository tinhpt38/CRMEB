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
namespace app\adminapi\controller\v1\system;

use app\adminapi\controller\AuthController;
use app\services\system\crontab\SystemCrontabServices;
use think\facade\App;
use think\facade\Env;

class SystemCrontab extends AuthController
{
    public function __construct(App $app, SystemCrontabServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Nhận danh sách nhiệm vụ theo lịch trình
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getTimerList()
    {
        $where = $this->request->getMore([
            ['custom', 0],
        ]);
        $where['is_del'] = 0;
        return app('json')->success($this->services->getTimerList($where));
    }

    /**
     * Nhận chi tiết nhiệm vụ theo lịch trình
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getTimerInfo($id)
    {
        return app('json')->success($this->services->getTimerInfo($id));
    }

    /**
     * Nhận loại nhiệm vụ theo lịch trình
     * @return mixed
     */    public function getMarkList()
    {
        return app('json')->success($this->services->getMarkList());
    }

    /**
     * Lưu các nhiệm vụ theo lịch trình
     * @return mixed
     */    public function saveTimer()
    {
        $data = $this->request->postMore([
            ['id', 0],
            ['name', ''],
            ['mark', ''],
            ['content', ''],
            ['type', 0],
            ['is_open', 0],
            ['month', 0],
            ['week', 0],
            ['day', 0],
            ['hour', 0],
            ['minute', 0],
            ['second', 0],
            ['customCode', ''],
            ['password', ''],
        ]);
        if ($data['mark'] == 'customTimer') {
            if (!Env::get('app_debug', false)) return app('json')->fail('Nội dung tùy chỉnh không thể được thêm hoặc sửa đổi trong môi trường sản xuất. Nếu bạn cần sửa đổi nó, vui lòng sửa đổi mục app_debug trong tệp .env thànhtrue');
            if ($data['password'] === '') return app('json')->fail('Mật khẩu không thể trống');
            if (config('filesystem.password') !== $data['password']) return app('json')->fail('Mật khẩu sai');
            $adminInfo = $this->request->adminInfo();
            if (!$adminInfo) return app('json')->fail('Hoạt động trái phép');
            if ($adminInfo['level'] != 0) return app('json')->fail('Chỉ quản trị viên cấp cao mới có thể Thao tác các tác vụ theo lịch trình');
            if (!$this->isSafePhpCode($data['customCode'])) return app('json')->fail('Có mã nguy hiểm trong Nội dung tùy chỉnh, vui lòng kiểm tra mã');
        }
        $this->services->saveTimer($data);
        return app('json')->success('Đã lưu thành công');
    }

    /**
     * Xóa nhiệm vụ đã lên lịch
     * @param $id
     * @return mixed
     */    public function delTimer($id)
    {
        $this->services->delTimer($id);
        return app('json')->success('Xóa thành công');
    }

    /**
     * Đặt trạng thái tác vụ theo lịch trình
     * @param $id
     * @param $is_open
     * @return mixed
     */    public function setTimerStatus($id, $is_open)
    {
        $this->services->setTimerStatus($id, $is_open);
        return app('json')->success('Thiết lập thành công');
    }

    /**
     * Kiểm tra xem nó có chứa từ khóa cho các thao tác như xóa bảng, xóa dữ liệu bảng, xóa tệp, sửa đổi Nội dung và hậu tố tệp, thực thi lệnh, v.v.
     * @param $code
     * @return bool
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/6/6
     */    function isSafePhpCode($code)
    {
        // Kiểm tra xem nó có chứa từ khóa cho các thao tác như xóa bảng, xóa dữ liệu bảng, xóa tệp, sửa đổi Nội dung và hậu tố tệp, thực thi lệnh, v.v.
        $dangerous_keywords = array(
            'delete',
            'destroy',
            'DROP TABLE',
            'DELETE FROM',
            'unlink(',
            'fwrite(',
            'shell_exec(',
            'exec(',
            'system(',
            'passthru('
        );
        foreach ($dangerous_keywords as $keyword) {
            if (strpos($code, $keyword) !== false) {
                return false;
            }
        }
        return true; // Nếu Tất cả các bước kiểm tra bảo mật đều vượt qua, hãy quay lại true
    }

}