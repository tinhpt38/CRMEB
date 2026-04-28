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
use app\services\user\UserSignServices;

/**
 * Đăng nhập người dùng
 * Class UserController
 * @package app\api\controller\v1\user
 */
class UserSignController
{
    protected $services = NUll;

    /**
     * UserController constructor.
     * @param UserSignServices $services
     */
    public function __construct(UserSignServices $services)
    {
        $this->services = $services;
    }

    /**
     * Đăng nhập cấu hình
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function sign_config(Request $request)
    {
        $uid = (int)$request->uid();
        return app('json')->success($this->services->signConfig($uid));
    }

    /**
     * Danh sách đăng ký
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function sign_list(Request $request)
    {
        list($page, $limit) = $request->getMore([
            ['page', 0],
            ['limit', 0]
        ], true);
        if (!$limit) return app('json')->success([]);
        $uid = (int)$request->uid();
        return app('json')->success($this->services->getUserSignList($uid));
    }

    /**
     * Đăng nhập
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function sign_integral(Request $request)
    {
        if (sys_config('sign_status') == 0) {
            return app('json')->fail('Chức năng đăng nhập chưa được kích hoạt');
        }
        $uid = (int)$request->uid();
        $integral = $this->services->sign($uid);
        return app('json')->success('Đăng nhập để nhận{:integral}điểm thưởng', ['integral' => $integral], ['integral' => $integral]);
    }

    /**
     * Đăng nhập thông tin người dùng
     * @param Request $request
     * @return mixed
     */
    public function sign_user(Request $request)
    {
        list($sign, $integral, $all) = $request->postMore([
            ['sign', 0],
            ['integral', 0],
            ['all', 0],
        ], true);
        $uid = (int)$request->uid();
        return app('json')->success($this->services->signUser($uid, $sign, $integral, $all));
    }

    /**
     * Danh sách đăng nhập (năm, tháng)）
     * @param Request $request
     * @return mixed
     */
    public function sign_month(Request $request)
    {
        $uid = (int)$request->uid();
        return app('json')->success($this->services->getSignMonthList($uid));
    }

    /**
     * Người dùng đặt lời nhắc đăng ký
     * @param Request $request
     * @param $status
     * @return \think\Response
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/9
     */
    public function sign_remind(Request $request, $status)
    {
        $uid = (int)$request->uid();
        $this->services->setSignRemind($uid, $status);
        return app('json')->success('Thiết lập thành công');
    }

}
