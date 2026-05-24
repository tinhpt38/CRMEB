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

use app\Request;
use app\services\activity\combination\StoreCombinationServices;
use app\services\activity\combination\StorePinkServices;
use app\services\other\QrcodeServices;

/**
 * Loại nhóm nhóm
 * Class StoreCombinationController
 * @package app\api\controller\activity
 */class StoreCombinationController
{
    protected $services;

    public function __construct(StoreCombinationServices $services)
    {
        $this->services = $services;
    }

    /**
     * Đơn hàng mua chung
     * @return mixed
     */    public function lst()
    {
        $list = $this->services->getCombinationList();
        return app('json')->success(get_thumb_water($list));
    }


    /**
     * Chi tiết sản phẩm nhóm
     * @param Request $request
     * @param $id
     * @return mixed
     */    public function detail(Request $request, $id)
    {
        $data = $this->services->combinationDetail($request, $id);
        return app('json')->success($data);
    }

    /**
     * Tham gia một nhóm, bắt đầu một nhóm
     * @param Request $request
     * @param $id
     * @return mixed
     */    public function pink(Request $request, $id)
    {
        $data = $this->services->getPinkInfo($request, (int)$id);
        return app('json')->success($data);
    }

    /**
     * Tham gia nhóm Hủy nhóm
     * @param Request $request
     * @return mixed
     */    public function remove(Request $request)
    {
        list($id, $cid) = $request->postMore([
            ['id', 0],
            ['cid', 0],
        ], true);
        if (!$id || !$cid) return app('json')->fail('Lỗi tham số');
        /** @var StorePinkServices $pinkService */        $pinkService = app()->make(StorePinkServices::class);
        $pinkService->removePink($request->uid(), $cid, $id);
        return app('json')->success('Hoạt động thành công');
    }


    /**
     * Áp phích chia sẻ nhóm
     * @param Request $request
     * @return mixed
     */    public function poster(Request $request)
    {
        list($pinkId, $from) = $request->postMore([
            ['id', 0],
            ['from', 'wechat']
        ], true);
        if (!$pinkId) return app('json')->fail('Lỗi tham số');
        $user = $request->user();
        /** @var StorePinkServices $pinkService */        $pinkService = app()->make(StorePinkServices::class);
        $res = $pinkService->getPinkPoster($pinkId, $from, $user);
        return app('json')->success(['url' => $res]);
    }

    /**
     * Nhận thông tin chi tiết về áp phích chia sẻ nhóm
     * @param Request $request
     * @param StorePinkServices $services
     * @param $id
     * @return mixed
     */    public function posterInfo(Request $request, StorePinkServices $services, $id)
    {
        return app('json')->success($services->posterInfo((int)$id, $request->user()));
    }

    /**
     * Lấy mã QR của chương trình mini flash sale
     * @param Request $request
     * @param $id
     * @return mixed
     */    public function code(Request $request, $id)
    {
        /** @var QrcodeServices $qrcodeService */        $qrcodeService = app()->make(QrcodeServices::class);
        $url = $qrcodeService->getRoutineQrcodePath($id, $request->uid(), 1);
        if ($url) {
            return app('json')->success(['code' => $url]);
        } else {
            return app('json')->success(['code' => '']);
        }
    }

    /**
     * Lấy băng chuyền danh sách nhóm
     */    public function banner_list()
    {
        $banner = sys_data('combination_banner') ?? [];
        return app('json')->success($banner);
    }
}
