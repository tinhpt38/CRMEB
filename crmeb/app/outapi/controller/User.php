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
namespace app\outapi\controller;

use think\facade\App;
use app\services\user\OutUserServices;

/**
 * bộ điều khiển Khách hàng
 * Class User
 * @package app\outapi\controller
 */class User extends AuthController
{
    /**
     * User constructor.
     * @param App $app
     * @param OutUserServices $service
     * @method temp
     */    public function __construct(App $app, OutUserServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Danh sách Khách hàng
     * @return mixed
     */    public function lst()
    {
        $where = $this->request->getMore([
            ['nickname', ''],
            ['status', ''],
            ['field_key', ''],
        ]);
        return app('json')->success($this->services->getUserList($where));
    }

    /**
     * Lưu tài nguyên mới
     *
     * @param \think\Request $request
     * @return \think\Response
     */    public function save()
    {
        $data = $this->request->postMore([
            ['real_name', ''],
            ['phone', 0],
            ['mark', ''],
            ['pwd', ''],
            ['level', 0],
            ['spread_open', 0],
            ['is_promoter', 0],
            ['status', 1]
        ]);
        $uid = $this->services->saveUser(0, $data);
        if (!$uid) {
            return app('json')->fail('Thêm không thành công');
        }
        return app('json')->success('Đã thêm thành công', ['uid' => $uid]);
    }

    /**
     * Cập nhật Khách hàng
     * @param $uid
     * @return mixed
     */    public function update($uid)
    {
        $data = $this->request->postMore([
            ['real_name', ''],
            ['phone', 0],
            ['mark', ''],
            ['pwd', ''],
            ['level', 0],
            ['spread_open', 1],
            ['is_promoter', 0],
            ['status', 1]
        ]);
        if (!$uid) return app('json')->fail('Lỗi tham số');
        $this->services->saveUser((int)$uid, $data);
        return app('json')->success('Sửa đổi thành công');
    }

    /**
     * Liên quan đến quà tặng
     * @param int $uid
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function give($uid)
    {
        $data = $this->request->postMore([
            ['money_status', 0],
            ['money', 0],
            ['integration_status', 0],
            ['integration', 0],
            ['days', 0],
            ['coupon', 0]
        ]);
        if (!$uid) return app('json')->fail('Lỗi tham số');
        if (!$this->services->otherGive((int)$uid, $data)) {
            return app('json')->fail('Thao tác không thành công');
        }
        return app('json')->success('Hoạt động thành công');
    }

    /**
     * Nhận thông tin chi tiết Khách hàng
     * @param $uid
     * @return \think\Response
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2023/06/20
     */    public function info($uid)
    {
        if (!$uid) return app('json')->fail('Lỗi tham số');
        $data = $this->services->userInfo($uid);
        return app('json')->success(compact('data'));
    }

    /**
     * Số dư quà tặng
     * @param int $uid
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function giveBalance($uid)
    {
        $data = $this->request->postMore([
            ['money_status', 0],
            ['money', 0],
            ['integration_status', 0],
            ['integration', 0],
            ['days', 0],
            ['coupon', 0]
        ]);
        if (!$uid) return app('json')->fail('Lỗi tham số');
        if (!$this->services->otherGive((int)$uid, $data)) {
            return app('json')->fail('Thao tác không thành công');
        }
        return app('json')->success('Hoạt động thành công');
    }

    /**
     * Tặng điểm
     * @param int $uid
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function givePoint($uid)
    {
        $data = $this->request->postMore([
            ['money_status', 0],
            ['money', 0],
            ['integration_status', 0],
            ['integration', 0],
            ['days', 0],
            ['coupon', 0]
        ]);
        if (!$uid) return app('json')->fail('Lỗi tham số');
        if (!$this->services->otherGive((int)$uid, $data)) {
            return app('json')->fail('Thao tác không thành công');
        }
        return app('json')->success('Hoạt động thành công');
    }

    /**
     * Sửa đổi số dư
     * @param $uid
     * @return \think\Response
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/5/20
     */    public function changeBalance($uid)
    {
        [$money] = $this->request->postMore([
            ['money', 0],
        ], true);
        if (!$uid) return app('json')->fail('Lỗi tham số');
        $this->services->changeUserData((int)$uid, $money, 'now_money');
        return app('json')->success('Sửa đổi thành công');
    }

    /**
     * Sửa đổi điểm
     * @param $uid
     * @return \think\Response
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/5/20
     */    public function changePoint($uid)
    {
        [$integral] = $this->request->postMore([
            ['integral', 0],
        ], true);
        if (!$uid) return app('json')->fail('Lỗi tham số');
        $this->services->changeUserData((int)$uid, $integral, 'integral');
        return app('json')->success('Sửa đổi thành công');
    }
}