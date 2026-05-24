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
namespace app\api\controller\v1\activity;

use app\services\activity\bargain\StoreBargainServices;
use app\services\activity\bargain\StoreBargainUserHelpServices;
use app\services\activity\bargain\StoreBargainUserServices;
use app\Request;
use app\services\user\UserServices;

/**
 * Mặt hàng giảm giá
 * Class StoreBargainController
 * @package app\api\controller\activity
 */class StoreBargainController
{
    protected $services;

    public function __construct(StoreBargainServices $services)
    {
        $this->services = $services;
    }

    /**
     * Hình ảnh trên cùng của danh sách giảm giá
     * @return mixed
     */    public function config()
    {
        $lovely = sys_data('routine_lovely') ?? [];//bannerhình ảnh
        $info = $lovely[2] ?? [];
        return app('json')->success($info);
    }

    /**
     * Danh sách sản phẩm mặc cả
     * @param Request $request
     * @return mixed
     */    public function lst(Request $request)
    {
        $bargainList = $this->services->getBargainList();
        return app('json')->success(get_thumb_water($bargainList));
    }

    /**
     * Chi tiết thương lượng và thông tin đăng nhập hiện tại
     * @param Request $request
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function detail(Request $request, $id)
    {
        list($bargainUid) = $request->getMore([
            ['bargainUid', 0]
        ], true);
        $data = $this->services->getBargain($request, $id, (int)$bargainUid);
        return app('json')->success($data);
    }

    /**
     * Mặc cả số lượt xem/chia sẻ/tham gia
     * @param Request $request
     * @return mixed
     */    public function share(Request $request)
    {
        /** @var StoreBargainUserHelpServices $bargainUserHelpService */        $bargainUserHelpService = app()->make(StoreBargainUserHelpServices::class);
        list($bargainId) = $request->postMore([['bargainId', 0]], true);
        $data['lookCount'] = $this->services->sum([], 'look');//TODO Số lượng người xem
        $data['userCount'] = $bargainUserHelpService->count([]);//TODO Số lượng người tham gia
        if (!$bargainId) return app('json')->success($data);
        $this->services->addBargain($bargainId, 'share');
        $data['shareCount'] = $this->services->sum([], 'share');//TODO Số người chia sẻ
        return app('json')->success($data);
    }

    /**
     * Đang đàm phán
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function start(Request $request)
    {
        list($bargainId) = $request->postMore([
            ['bargainId', 0]
        ], true);
        return app('json')->success($this->services->setBargain($request->uid(), $bargainId));
    }

    /**
     * Mặc cả Giúp bạn bè mặc cả
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function help(Request $request)
    {
        list($bargainId, $bargainUserUid) = $request->postMore([
            ['bargainId', 0],
            ['bargainUserUid', 0]
        ], true);
        return app('json')->success($this->services->setHelpBargain($request->uid(), (int)$bargainId, (int)$bargainUserUid));
    }

    /**
     * Thương lượng Trợ giúp thương lượng
     * @param Request $request
     * @return mixed
     */    public function help_list(Request $request)
    {
        list($bargainId, $bargainUserUid) = $request->postMore([
            ['bargainId', 0],
            ['bargainUserUid', 0],
        ], true);
        /** @var StoreBargainUserServices $bargainUser */        $bargainUser = app()->make(StoreBargainUserServices::class);
        $bargainUserTableId = $bargainUser->getBargainUserTableId((int)$bargainId, (int)$bargainUserUid);

        /** @var StoreBargainUserHelpServices $bargainUserHelp */        $bargainUserHelp = app()->make(StoreBargainUserHelpServices::class);
        [$page, $limit] = $this->services->getPageValue();
        $storeBargainUserHelp = $bargainUserHelp->getHelpList((int)$bargainUserTableId, $page, $limit);
        return app('json')->success($storeBargainUserHelp);
    }

    /**
     * Mặc cả Cho phép mặc cả thông tin Khách hàng
     * @param Request $request
     * @return mixed
     */    public function start_user(Request $request)
    {
        list($bargainId, $bargainUserUid) = $request->postMore([
            ['bargainId', 0],
            ['bargainUserUid', 0],
        ], true);
        if (!$bargainId || !$bargainUserUid) return app('json')->fail('Lỗi tham số');
        /** @var UserServices $userServices */        $userServices = app()->make(UserServices::class);
        $userInfo = $userServices->getUserInfo((int)$bargainUserUid);
        if (!$userInfo) {
            return app('json')->fail('Không thể lấy được thông tin Khách hàng');
        }
        return app('json')->success(['nickname' => $userInfo['nickname'], 'avatar' => $userInfo['avatar']]);
    }

    /**
     * Lịch sử trả giá(Đã tham gia)
     * @param Request $request
     * @return mixed
     */    public function user_list(Request $request)
    {
        $uid = $request->uid();
        /** @var StoreBargainUserServices $bargainUser */        $bargainUser = app()->make(StoreBargainUserServices::class);
        $bargainUser->editBargainUserStatus($uid);// TODO Xác định hoạt động thương lượng đã hết hạn
        $list = $bargainUser->getBargainUserAll($uid);
        if (count($list)) return app('json')->success(get_thumb_water($list));
        else return app('json')->success([]);
    }

    /**
     * Giảm giá Hủy bỏ
     * @param Request $request
     * @return mixed
     */    public function user_cancel(Request $request)
    {
        list($bargainId) = $request->postMore([['bargainId', 0]], true);
        if (!$bargainId) return app('json')->fail('Lỗi tham số');
        /** @var StoreBargainUserServices $bargainUser */        $bargainUser = app()->make(StoreBargainUserServices::class);
        $res = $bargainUser->cancelBargain($bargainId, $request->uid());
        if ($res) return app('json')->success('Hủy thành công');
        else return app('json')->success('Hủy không thành công');
    }

    /**
     * áp phích mặc cả
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function poster(Request $request)
    {
        [$bargainId, $from] = $request->postMore([
            ['bargainId', ''],
            ['from', 'wechat']
        ], true);
        $posterUrl = $this->services->poster($bargainId, $request->user(), $from);
        if ($posterUrl) {
            return app('json')->success(['url' => $posterUrl]);
        } else {
            return app('json')->fail('Không tạo được áp phích');
        }
    }

    /**
     * Nhận chia sẻ thông tin áp phích
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function posterInfo(Request $request, $bargainId)
    {
        return app('json')->success($this->services->posterInfo((int)$bargainId, $request->user()));
    }
}
