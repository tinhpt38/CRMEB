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
use app\services\system\SystemEventServices;
use think\facade\App;
use think\facade\Env;

class SystemEvent extends AuthController
{
    public function __construct(App $app, SystemEventServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Loại sự kiện tùy chỉnh
     * @return \think\Response
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/6/7
     */
    public function getMarkList()
    {
        return app('json')->success($this->services->getMarkList());
    }

    /**
     * Danh sách sự kiện tùy chỉnh
     * @return \think\Response
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/6/7
     */
    public function getEventList()
    {
        return app('json')->success($this->services->getEventList());
    }

    /**
     * Chi tiết sự kiện tùy chỉnh
     * @param $id
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/6/7
     */
    public function getEventInfo($id)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');
        return app('json')->success($this->services->getEventInfo($id));
    }

    /**
     * Thêm và chỉnh sửa sự kiện tùy chỉnh
     * @return \think\Response
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/6/7
     */
    public function saveEvent()
    {
        $data = $this->request->postMore([
            ['id', 0],
            ['name', ''],
            ['mark', ''],
            ['content', ''],
            ['is_open', 0],
            ['customCode', ''],
            ['password', ''],
        ]);
        if ($data['name'] == '') return app('json')->fail('Vui lòng điền tên sự kiện');
        if ($data['mark'] == '') return app('json')->fail('Vui lòng chọn loại sự kiện');
        if (!Env::get('app_debug', false)) return app('json')->fail('Nội dung tùy chỉnh không thể được thêm hoặc sửa đổi trong môi trường sản xuất. Nếu bạn cần sửa đổi nó, vui lòng sửa đổi mục app_debug trong tệp .env thànhtrue');
        if ($data['password'] === '') return app('json')->fail('Mật khẩu không thể trống');
        if (config('filesystem.password') !== $data['password']) return app('json')->fail('Mật khẩu sai');
        $adminInfo = $this->request->adminInfo();
        if (!$adminInfo) return app('json')->fail('Hoạt động trái phép');
        if ($adminInfo['level'] != 0) return app('json')->fail('Chỉ quản trị viên cấp cao mới có thể Thao tác các tác vụ theo lịch trình');
        if (!$this->isSafePhpCode($data['customCode'])) return app('json')->fail('Có mã nguy hiểm trong nội dung tùy chỉnh, vui lòng kiểm tra mã');
        $this->services->saveEvent($data);
        return app('json')->success('Đã lưu thành công');
    }

    /**
     * Kiểm tra xem nó có chứa từ khóa cho các thao tác như xóa bảng, xóa dữ liệu bảng, xóa tệp, sửa đổi nội dung và hậu tố tệp, thực thi lệnh, v.v.
     * @param $code
     * @return bool
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/6/7
     */
    function isSafePhpCode($code)
    {
        // Kiểm tra xem nó có chứa từ khóa cho các thao tác như xóa bảng, xóa dữ liệu bảng, xóa tệp, sửa đổi nội dung và hậu tố tệp, thực thi lệnh, v.v.
        $dangerous_keywords = [
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
        ];
        foreach ($dangerous_keywords as $keyword) {
            if (strpos($code, $keyword) !== false) {
                return false;
            }
        }
        return true; // Nếu tất cả các bước kiểm tra bảo mật đều vượt qua, hãy quay lại true
    }

    /**
     * Liệu sự kiện tùy chỉnh có bật nút chuyển hay không
     * @param $id
     * @param $is_open
     * @return \think\Response
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/6/7
     */
    public function setEventStatus($id, $is_open)
    {
        $this->services->setEventStatus($id, $is_open);
        return app('json')->success('Thiết lập thành công');
    }

    /**
     * Xóa sự kiện tùy chỉnh
     * @param $id
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/6/7
     */
    public function delEvent($id)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');
        $this->services->eventDel($id);
        return app('json')->success('Xóa thành công');
    }
}