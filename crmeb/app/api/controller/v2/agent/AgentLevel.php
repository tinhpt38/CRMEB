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
namespace app\api\controller\v2\agent;


use app\Request;
use app\services\agent\AgentLevelServices;
use app\services\agent\AgentLevelTaskServices;

/**
 * Class AgentLevel
 * @package app\controller\api\v2\agent
 */
class AgentLevel
{
    protected $services;

    public function __construct(AgentLevelServices $services)
    {
        $this->services = $services;
    }

    /**
     * Kiểm tra xem người dùng có thể trở thành thành viên hay không
     * @param Request $request
     * @return mixed
     */
    public function detection(Request $request)
    {
        return app('json')->success($this->services->detection((int)$request->uid()));
    }

    /**
     * Danh sách cấp độ nhà phân phối
     * @param Request $request
     * @return mixed
     */
    public function levelList(Request $request)
    {
        return app('json')->success($this->services->getUserlevelList((int)$request->uid()));
    }

    /**
     * Nhận nhiệm vụ cấp độ
     * @param Request $request
     * @param AgentLevelTaskServices $services
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function levelTaskList(Request $request, AgentLevelTaskServices $services, $id)
    {
        return app('json')->success($services->getUserLevelTaskList((int)$request->uid(), (int)$id));
    }

    /**
     * Chi tiết thành viên
     * @param Request $request
     * @return mixed
     */
    public function userLevelInfo(Request $request)
    {
        return app('json')->success($this->services->getUserLevelInfo((int)$request->uid()));
    }

}
