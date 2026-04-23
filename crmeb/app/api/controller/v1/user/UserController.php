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
use app\services\product\product\StoreProductLogServices;
use app\services\user\UserCancelServices;
use app\services\user\UserServices;
use app\services\wechat\WechatUserServices;


/**
 * Lớp người dùng
 * Class UserController
 * @package app\api\controller\store
 */
class UserController
{
    protected $services = NUll;

    /**
     * UserController constructor.
     * @param UserServices $services
     */
    public function __construct(UserServices $services)
    {
        $this->services = $services;
    }

    /**
     * Lấy thông tin người dùng
     * @param Request $request
     * @return mixed
     */
    public function userInfo(Request $request)
    {
        $info = $request->user()->toArray();
        return app('json')->success($this->services->userInfo($info));
    }

    /**
     * Thống kê quỹ người dùng
     * @param Request $request
     * @return mixed
     */
    public function balance(Request $request)
    {
        $uid = (int)$request->uid();
        return app('json')->success($this->services->balance($uid));
    }

    /**
     * Trung tâm cá nhân
     * @param Request $request
     * @return mixed
     */
    public function user(Request $request)
    {
        $user = $request->user()->toArray();
        return app('json')->success($this->services->personalHome($user, $request->tokenData()));
    }

    /**
     * Nhận trạng thái hoạt động
     * @return mixed
     */
    public function activity()
    {
        return app('json')->success($this->services->activity());
    }

    /**
     * Thông tin người dùng sửa đổi
     * @param Request $request
     * @return mixed
     */
    public function edit(Request $request)
    {
        list($avatar, $nickname) = $request->postMore([
            ['avatar', ''],
            ['nickname', ''],
        ], true);
        if (!$avatar && $nickname == '') {
            return app('json')->fail('Vui lòng nhập biệt hiệu hoặc chọn hình đại diện');
        }
        $uid = (int)$request->uid();
        if ($this->services->eidtNickname($uid, ['avatar' => $avatar, 'nickname' => $nickname])) {
            return app('json')->success('Thiết lập thành công');
        }
        return app('json')->fail('Thiết lập không thành công');
    }

    /**
     * Xếp hạng nhà quảng cáo
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function rank(Request $request)
    {
        $data = $request->getMore([
            ['page', ''],
            ['limit', ''],
            ['type', '']
        ]);
        return app('json')->success($this->services->getRankList($data));
    }

    /**
     * Thêm bản ghi truy cập
     * @param Request $request
     * @return mixed
     */
    public function set_visit(Request $request)
    {
        $data = $request->postMore([
            ['url', ''],
            ['stay_time', 0]
        ]);
        if ($data['url'] == '') return app('json')->fail('Lỗi tham số');
        $data['uid'] = (int)$request->uid();
        $data['ip'] = $request->ip();
        if ($this->services->setVisit($data)) {
            return app('json')->success('Đã thêm thành công');
        } else {
            return app('json')->fail('Thêm không thành công');
        }
    }

    /**
     * Trình quảng bá liên kết âm thầm
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function spread(Request $request)
    {
        [$spreadUid, $code, $agent_id] = $request->postMore([
            ['puid', 0],
            ['code', 0],
            ['agent_id', 0]
        ], true);
        $uid = (int)$request->uid();
        $res = $this->services->spread($uid, (int)$spreadUid, $code, $agent_id);
        return app('json')->success($res);
    }

    /**
     * Người dùng được đề xuất
     * @param Request $request
     * @return mixed
     */
    public function spread_people(Request $request)
    {
        $spreadInfo = $request->postMore([
            ['grade', 0],
            ['keyword', ''],
            ['sort', ''],
        ]);
        if (!in_array($spreadInfo['grade'], [0, 1])) {
            return app('json')->fail('Lỗi tham số');
        }
        $uid = $request->uid();
        return app('json')->success($this->services->getUserSpreadGrade($uid, $spreadInfo['grade'], $spreadInfo['sort'], $spreadInfo['keyword']));
    }

    /**
     * Bạn có chú ý không?
     * @param Request $request
     * @return mixed
     */
    public function subscribe(Request $request)
    {
        if ($request->uid()) {
            /** @var WechatUserServices $wechatUserService */
            $wechatUserService = app()->make(WechatUserServices::class);
            $subscribe = (bool)$wechatUserService->value(['uid' => $request->uid()], 'subscribe');
            return app('json')->success(['subscribe' => $subscribe]);
        } else {
            return app('json')->success(['subscribe' => true]);
        }
    }

    /**
     * Đăng xuất người dùng
     * @param Request $request
     * @return mixed
     */
    public function SetUserCancel(Request $request)
    {
        /** @var UserCancelServices $userCancelServices */
        $userCancelServices = app()->make(UserCancelServices::class);
        $userCancelServices->SetUserCancel($request->uid());
        return app('json')->success('Đăng xuất thành công');
    }

    /**
     * Lịch sử duyệt sản phẩm
     * @param Request $request
     * @param StoreProductLogServices $services
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function visitList(Request $request, StoreProductLogServices $services)
    {
        $where['uid'] = (int)$request->uid();
        $where['type'] = 'visit';
        $result = $services->getList($where, 'product_id', 'id,product_id,max(add_time) as add_time');
        $time_data = [];
        if ($result['list']) {
            foreach ($result['list'] as $key => &$item) {
                $add_time = strtotime($item['add_time']);
                if (date('Y') == date('Y', $add_time)) {//Năm nay
                    $item['time_key'] = date('m-d', $add_time);
                } else {
                    $item['time_key'] = date('Y-m-d', $add_time);
                }
            }
            $time_data = array_merge(array_unique(array_column($result['list'], 'time_key')));
        }
        $result['time'] = $time_data;
        return app('json')->success($result);
    }

    /**
     * Xóa lịch sử duyệt sản phẩm
     * @param Request $request
     * @param StoreProductLogServices $services
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function visitDelete(Request $request, StoreProductLogServices $services)
    {
        $uid = (int)$request->uid();
        [$ids] = $request->postMore([
            ['ids', []],
        ], true);
        if ($ids) {
            $where = ['uid' => $uid, 'product_id' => $ids];
            $services->delete($where);
        }
        return app('json')->success('Xóa thành công');
    }
}
