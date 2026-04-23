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
namespace app\api\controller\v1\user;


use app\Request;
use app\services\agent\DivisionAgentApplyServices;
use app\services\agent\DivisionServices;
use app\services\other\AgreementServices;
use app\services\user\UserServices;
use crmeb\services\CacheService;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

class DivisionController
{
    protected $services;

    /**
     * DivisionController constructor.
     * @param DivisionAgentApplyServices $services
     */
    public function __construct(DivisionAgentApplyServices $services)
    {
        $this->services = $services;
    }

    /**
     * Đăng ký làm đại lý
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function applyAgent(Request $request, $id)
    {
        $data = $request->postMore([
            ['uid', 0],
            ['agent_name', ''],
            ['name', ''],
            ['phone', 0],
            ['code', 0],
            ['division_invite', 0],
            ['images', []]
        ]);
        $verifyCode = CacheService::get('code_' . $data['phone']);
        if ($verifyCode != $data['code']) return app('json')->fail('Lỗi mã xác minh');
        if ($data['division_invite'] == 0) return app('json')->fail('Vui lòng điền mã mời');
        $this->services->applyAgent($data, $id);
        return app('json')->success('Gửi thành công');
    }

    /**
     * Chi tiết ứng dụng
     * @param Request $request
     * @return mixed
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function applyInfo(Request $request)
    {
        $uid = $request->uid();
        $data = $this->services->applyInfo($uid);
        return app('json')->success($data);
    }

    /**
     * Quy tắc truy cập di động
     * @param AgreementServices $agreementServices
     * @return mixed
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getAgentAgreement(AgreementServices $agreementServices)
    {
        $data = $agreementServices->getAgreementBytype(2);
        return app('json')->success($data);
    }

    /**
     * danh sách nhân viên
     * @param Request $request
     * @return mixed
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getStaffList(Request $request)
    {
        $where = $request->postMore([
            ['keyword', ''],
            ['sort', ''],
        ]);
        $where['agent_id'] = $request->uid();
        return app('json')->success($this->services->getStaffList($request->isRoutine(), $where));
    }

    /**
     * Đặt tỷ lệ nhân viên
     * @param Request $request
     * @return mixed
     */
    public function setStaffPercent(Request $request)
    {
        [$agentPercent, $uid] = $request->postMore([
            ['agent_percent', ''],
            ['uid', 0],
        ], true);
        $agentId = $request->uid();
        if (!$uid) return app('json')->fail('Lỗi tham số');
        /** @var UserServices $userService */
        $userService = app()->make(UserServices::class);
        $upPercent = $userService->value(['uid' => $agentId], 'division_percent');
        if ($agentPercent >= $upPercent) return app('json')->fail('Tỷ lệ không thể lớn hơn tỷ lệ của bạn');
        $userService->update(['uid' => $uid, 'agent_id' => $agentId], ['division_percent' => $agentPercent]);
        return app('json')->success('Thiết lập thành công');
    }

    /**
     * Xóa nhân viên
     * @param Request $request
     * @param $uid
     * @return mixed
     */
    public function delStaff(Request $request, $uid)
    {
        if (!$uid) return app('json')->fail('Lỗi tham số');
        $agentId = $request->uid();
        /** @var UserServices $userService */
        $userService = app()->make(UserServices::class);
        $userService->update(['uid' => $uid, 'agent_id' => $agentId], ['division_percent' => 0, 'agent_id' => 0, 'division_id' => 0, 'staff_id' => 0, 'division_type' => 0, 'is_staff' => 0]);
        return app('json')->success('Xóa thành công');
    }

    /**
     * Phương thức ràng buộc nhân viên
     * @param Request $request
     * @return \think\Response
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2024/2/2
     */
    public function agentSpread(Request $request)
    {
        [$agentId, $agentCode] = $request->postMore([
            ['agent_id', 0],
            ['agent_code', 0],
        ], true);
        $res = app()->make(DivisionServices::class)->agentSpreadStaff($request->uid(), (int)$agentId, (int)$agentCode);
        if ($res) {
            return app('json')->success($res);
        } else {
            return app('json')->fail('Không có hành động');
        }
    }
}
