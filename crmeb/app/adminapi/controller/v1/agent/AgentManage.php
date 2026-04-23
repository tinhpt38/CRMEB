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
use app\services\agent\AgentManageServices;
use app\services\user\UserServices;
use think\facade\App;

/**
 * Kiểm soát viên quản lý nhà phân phối
 * Class AgentManage
 * @package app\adminapi\controller\v1\agent
 */
class AgentManage extends AuthController
{
    /**
     * @var AgentManageServices
     */
    protected $services;

    /**
     * AgentManage constructor.
     * @param App $app
     * @param AgentManageServices $services
     */
    public function __construct(App $app, AgentManageServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Danh sách quản lý phân phối
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index()
    {
        // Nhận thông số yêu cầu: biệt hiệu, phạm vi ngày
        $where = $this->request->getMore([
            ['nickname', ''],
            ['data', ''],
        ]);
        // Gọi lớp dịch vụ để lấy danh sách nhà phân phối
        return app('json')->success($this->services->agentSystemPage($where));
    }

    /**
     * Thống kê trưởng phòng phân phối
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function get_badge()
    {
        // Nhận thông số yêu cầu
        $where = $this->request->getMore([
            ['data', '', '', 'time'],
            ['nickname', ''],
        ]);
        // Quay lại thống kê phân phối
        return app('json')->success(['res' => $this->services->getSpreadBadge($where)]);
    }

    /**
     * Danh sách nhà quảng bá
     * @return mixed
     */
    public function get_stair_list()
    {
        // Nhận thông số yêu cầu: ID người dùng, phạm vi ngày, biệt hiệu, loại (cấp một/cấp phụ）
        $where = $this->request->getMore([
            ['uid', 0],
            ['data', ''],
            ['nickname', ''],
            ['type', '']
        ]);
        // Gọi lớp dịch vụ để lấy danh sách các nhà quảng bá
        return app('json')->success($this->services->getStairList($where));
    }

    /**
     * Thống kê người đứng đầu danh sách nhà quảng cáo
     * @return mixed
     */
    public function get_stair_badge()
    {
        // Nhận thông số yêu cầu
        $where = $this->request->getMore([
            ['uid', ''],
            ['data', ''],
            ['nickname', ''],
            ['type', ''],
        ]);
        // Quay lại số liệu thống kê của người quảng bá
        return app('json')->success(['res' => $this->services->getSairBadge($where)]);
    }

    /**
     * Thống kê danh sách đơn hàng khuyến mãi
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function get_stair_order_list()
    {
        // Nhận thông số yêu cầu
        $where = $this->request->getMore([
            ['uid', 0],
            ['data', ''],
            ['order_id', ''],
            ['type', ''],
        ]);
        // Gọi lớp dịch vụ để lấy danh sách đơn hàng khuyến mãi
        return app('json')->success($this->services->getStairOrderList((int)$where['uid'], $where));
    }

    /**
     * Xem mã QR để khuyến mãi tài khoản công cộng
     * @param string $uid
     * @param string $action
     * @return mixed
     */
    public function look_code($uid = '', $action = '')
    {
        if (!$uid || !$action) return app('json')->fail('Lỗi tham số');
        try {
            // Phương thức gọi động
            if (method_exists($this, $action)) {
                $res = $this->$action($uid);
                if ($res)
                    return app('json')->success($res);
                else
                    return app('json')->fail('Không thể lấy được');
            } else
                return app('json')->fail('Hiện chưa có phương pháp nào như vậy');
        } catch (\Exception $e) {
            return app('json')->fail('Không lấy được mã QR khuyến mãi, vui lòng kiểm tra cấu hình WeChat của bạn', ['line' => $e->getLine(), 'messag' => $e->getMessage()]);
        }
    }

    /**
     * Lấy mã QR của tài khoản chính thức
     * @param $uid
     * @return array
     */
    public function wechant_code($uid)
    {
        // Gọi lớp dịch vụ để tạo mã QR tài khoản công khai
        $qr_code = $this->services->wechatCode((int)$uid);
        if (isset($qr_code['url']))
            return ['code_src' => $qr_code['url']];
        else
            return app('json')->fail('Không thể lấy được');
    }

    /**
     * Xem mã QR khuyến mãi chương trình mini
     * @param string $uid
     */
    public function look_xcx_code($uid = '')
    {
        if (!strlen(trim($uid))) {
            return app('json')->fail('Lỗi tham số');
        }
        // Gọi lớp dịch vụ để tạo mã QR chương trình mini
        return app('json')->success($this->services->lookXcxCode((int)$uid));
    }

    /**
     * Xem mã QR khuyến mãi H5
     * @param string $uid
     * @return mixed|string
     */
    public function look_h5_code($uid = '')
    {
        if (!strlen(trim($uid))) return app('json')->fail('Lỗi tham số');
        // Gọi lớp dịch vụ để tạo mã QR H5
        return app('json')->success($this->services->lookH5Code((int)$uid));
    }

    /**
     * Xóa quyền khuyến mãi khỏi một người dùng
     * @param $uid
     * @return mixed
     */
    public function delete_spread($uid)
    {
        if (!$uid) app('json')->fail('Lỗi tham số');
        // Gọi lớp dịch vụ để xóa quyền khuyến mãi
        return app('json')->success($this->services->delSpread((int)$uid) ? 'Thiết lập thành công' : 'Thiết lập không thành công');
    }

    /**
     * Sửa đổi trình quảng bá ưu việt
     * @param UserServices $services
     * @return mixed
     */
    public function editSpread(UserServices $services)
    {
        // Nhận thông số yêu cầu: ID người dùng, nhà quảng cáo cấp trênID
        [$uid, $spreadUid] = $this->request->postMore([
            [['uid', 'd'], 0],
            [['spread_uid', 'd'], 0],
        ], true);
        if (!$uid || !$spreadUid) {
            return app('json')->fail('Lỗi tham số');
        }
        if ($uid == $spreadUid) {
            return app('json')->fail('Những người quảng bá cấp cao không thể tự mình làm điều đó');
        }
        // Lấy thông tin người dùng
        $userInfo = $services->get($uid);
        if (!$userInfo) {
            return app('json')->fail('Người dùng không tồn tại');
        }
        // Xác minh xem người dùng cao cấp có tồn tại không
        if (!$services->count(['uid' => $spreadUid])) {
            return app('json')->fail('Người dùng cao cấp không tồn tại');
        }
        if ($userInfo->spread_uid == $spreadUid) {
            return app('json')->fail('Người quảng bá hiện tại đã là người được chọn');
        }
        $spreadInfo = $services->get($spreadUid);
        if ($spreadInfo->spread_uid == $uid) {
            return app('json')->fail('Người đề xướng cấp trên không thể phục vụ cấp dưới của mình');
        }
        //Cấp trên trước giảm số người được thăng chức
        if ($userInfo->spread_uid) {
            $oldSpread = $services->get($userInfo->spread_uid);
            $oldSpread->spread_count = $oldSpread->spread_count - 1;
            $oldSpread->save();
        }
        // Cấp trên mới tăng số lượng người quảng bá
        $spreadInfo->spread_count = $spreadInfo->spread_count + 1;
        $spreadInfo->save();
        // Cập nhật mối quan hệ khuyến mãi của người dùng
        $userInfo->spread_uid = $spreadUid;
        $userInfo->spread_time = time();
        $userInfo->division_id = $spreadInfo->division_id;
        $userInfo->agent_id = $spreadInfo->agent_id;
        $userInfo->staff_id = $spreadInfo->staff_id;
        $userInfo->save();
        return app('json')->success('Sửa đổi thành công');
    }

    /**
     * Hủy bỏ tư cách thăng hạng của người quảng bá
     * @param $uid
     * @return mixed
     */
    public function delete_system_spread($uid)
    {
        if (!$uid) app('json')->fail('Lỗi tham số');
        // Gọi lớp dịch vụ để hủy tư cách khuyến mãi
        return app('json')->success($this->services->delSystemSpread((int)$uid) ? 'Hủy thành công' : 'Hủy không thành công');
    }

    /**
     * Nhận biểu mẫu cấp độ phân phối miễn phí
     * @param AgentLevelServices $services
     * @param $uid
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getLevelForm(AgentLevelServices $services, $uid)
    {
        if (!$uid) app('json')->fail('Lỗi tham số');
        // Gọi AgentLevelServices để lấy biểu mẫu
        return app('json')->success($services->levelForm((int)$uid));
    }

    /**
     * Mức độ phân phối miễn phí
     * @param AgentLevelServices $services
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function giveAgentLevel(AgentLevelServices $services)
    {
        // Lấy thông số: user ID, cấp độID
        [$uid, $id] = $this->request->postMore([
            [['uid', 'd'], 0],
            [['id', 'd'], 0],
        ], true);
        if (!$uid || !$id) {
            return app('json')->fail('Lỗi tham số');
        }
        // Gọi AgentLevelServices để tặng cấp độ
        return app('json')->success($services->givelevel((int)$uid, (int)$id) ? 'Quà tặng thành công' : 'Quà tặng không thành công');
    }
}
