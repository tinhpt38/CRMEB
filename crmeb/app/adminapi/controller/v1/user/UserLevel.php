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
namespace app\adminapi\controller\v1\user;

use app\adminapi\controller\AuthController;
use app\services\user\UserLevelServices;
use think\facade\App;

/**
 * Cài đặt thành viên
 * Class UserLevel
 * @package app\adminapi\controller\v1\user
 */
class UserLevel extends AuthController
{

    /**
     * user constructor.
     * @param App $app
     * @param UserLevelServices $services
     */
    public function __construct(App $app, UserLevelServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /*
     * Nhận biểu mẫu thêm tài nguyên
     * */
    public function create()
    {
        $where = $this->request->getMore(
            ['id', 0]
        );
        return app('json')->success($this->services->edit((int)$where['id']));
    }

    /*
     * Thêm hoặc sửa đổi cấp độ thành viên
     * @param $id mức độ sửa đổiid
     * @return json
     * */
    public function save()
    {
        $data = $this->request->postMore([
            ['id', 0],
            ['name', ''],
            ['is_forever', 0],
            ['money', 0],
            ['is_pay', 0],
            ['valid_date', 0],
            ['grade', 0],
            ['discount', 0],
            ['icon', ''],
            ['image', ''],
            ['is_show', ''],
            ['exp_num', 0]
        ]);
        if ($data['valid_date'] == 0) $data['is_forever'] = 1;//Khi thời gian hiệu lực là 0, nó là vĩnh viễn.
        if (!$data['name']) return app('json')->fail('Vui lòng nhập tên cấp độ');
        if (!$data['grade']) return app('json')->fail('Vui lòng nhập cấp độ');
        if (!$data['icon']) return app('json')->fail('Vui lòng tải lên biểu tượng cấp độ');
        if (!$data['image']) return app('json')->fail('Vui lòng tải lên biểu tượng nền cấp độ');
        if (!$data['exp_num']) return app('json')->fail('Vui lòng nhập giá trị trải nghiệm nâng cấp');
        $this->services->save((int)$data['id'], $data);
        return app('json')->success('Đã lưu thành công');
    }

    /*
     * Lấy danh sách VIP do hệ thống thiết lập
     * @param int page
     * @param int limit
     * */
    public function get_system_vip_list()
    {
        $where = $this->request->getMore([
            ['page', 0],
            ['limit', 10],
            ['title', ''],
            ['is_show', ''],
        ]);
        return app('json')->success($this->services->getSytemList($where));
    }

    /*
     * Xóa cấp độ thành viên
     * @param int $id
     * */
    public function delete($id)
    {
        return app('json')->success($this->services->delLevel((int)$id));
    }

    /**
     * Đặt hiển thị cấp độ thành viên|trốn
     *
     * @return json
     */
    public function set_show($is_show = '', $id = '')
    {
        if ($is_show == '' || $id == '') return app('json')->fail('Lỗi tham số');
        return app('json')->success($this->services->setShow((int)$id, (int)$is_show));
    }

    /**
     * Chỉnh sửa nhanh danh sách cấp độ
     * field:value name:Thành viên kim cương/grade:8/discount:92.00
     * @return json
     */
    public function set_value($id)
    {
        $data = $this->request->postMore([
            ['field', ''],
            ['value', '']
        ]);
        if ($data['field'] == '' || $data['value'] == '') return app('json')->fail('Lỗi tham số');
        $this->services->setValue((int)$id, $data);
        return app('json')->success('Đã lưu thành công');
    }


}
