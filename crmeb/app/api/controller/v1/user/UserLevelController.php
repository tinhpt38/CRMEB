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

use app\model\system\SystemUserTask;
use app\Request;
use app\services\user\UserLevelServices;

/**
 * Hạng mục cấp thành viên
 * Class UserLevelController
 * @package app\api\controller\user
 */class UserLevelController
{
    protected $services = NUll;

    /**
     * UserLevelController constructor.
     * @param UserLevelServices $services
     */    public function __construct(UserLevelServices $services)
    {
        $this->services = $services;
    }

    /**
     * Kiểm tra xem Khách hàng có thể trở thành thành viên hay không
     * @param Request $request
     * @return mixed
     */    public function detection(Request $request)
    {
        return app('json')->success($this->services->detection((int)$request->uid()));
    }

    /**
     * Danh sách cấp thành viên
     * @param Request $request
     * @return mixed
     */    public function grade(Request $request)
    {
        return app('json')->success(['list'=>$this->services->grade((int)$request->uid()),'task'=>['list'=>[],'task'=>[]]]);
    }

    /**
     * Nhận nhiệm vụ cấp độ
     * @param Request $request
     * @param $id
     * @return mixed
     */    public function task(Request $request, $id)
    {
        return app('json')->success((new SystemUserTask())->getTashList($id, $request->uid()));
    }

    /**
     * Chi tiết thành viên
     * @param Request $request
     * @return mixed
     */    public function userLevelInfo(Request $request)
    {
        return app('json')->success($this->services->getUserLevelInfo((int)$request->uid()));
    }

    /**
     * Danh sách kinh nghiệm
     * @param Request $request
     * @return mixed
     */    public function expList(Request $request)
    {
        return app('json')->success($this->services->expList((int)$request->uid()));
    }

}
