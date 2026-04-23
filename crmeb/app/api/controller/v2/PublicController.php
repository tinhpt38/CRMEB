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

namespace app\api\controller\v2;


use app\Request;
use app\services\diy\DiyServices;
use app\services\product\product\StoreCategoryServices;
use app\services\product\product\StoreProductServices;
use app\services\user\UserServices;
use app\services\user\UserSignServices;
use app\services\wechat\WechatUserServices;

class PublicController
{
    /**
     * Mua lại trang chủ
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $fastNumber = (int)sys_config('fast_number', 0);//TODO Chọn nhanh số lượng danh mục
        /** @var StoreCategoryServices $categoryService */
        $categoryService = app()->make(StoreCategoryServices::class);
        $info['fastList'] = $fastNumber ? $categoryService->byIndexList($fastNumber, 'id,cate_name,pid,pic') : [];//TODO Chọn nhanh số lượng danh mục
        /** @var StoreProductServices $storeProductServices */
        $storeProductServices = app()->make(StoreProductServices::class);
        //Nhận sản phẩm được đề xuất
        [$baseList, $firstList, $benefit, $likeInfo, $vipList] = $storeProductServices->getRecommendProductArr((int)$request->uid(), ['is_best', 'is_new', 'is_benefit', 'is_hot']);
        $info['bastList'] = $baseList;//TODO Số lượng sản phẩm được đề xuất
        $info['firstList'] = $firstList;//TODO Số lượng sản phẩm mới ra mắt lần đầu
        if ($request->uid()) {
            /** @var UserServices $userService */
            $userService = app()->make(UserServices::class);
            //Kiểm tra xem tư cách thành viên đã hết hạn chưa
            $userService->offMemberLevel($request->uid());
            /** @var WechatUserServices $wechatUserService */
            $wechatUserService = app()->make(WechatUserServices::class);
            $subscribe = (bool)$wechatUserService->value(['uid' => $request->uid(), 'user_type' => 'wechat'], 'subscribe');
        } else {
            $subscribe = true;
        }
        $tengxun_map_key = sys_config('tengxun_map_key');
        $site_name = sys_config('site_name');
        return app('json')->success(compact('info', 'benefit', 'likeInfo', 'subscribe', 'tengxun_map_key', 'site_name'));
    }

    /**
     * Lấy dữ liệu trang
     * @param Request $request
     * @param string $name
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getDiy(Request $request, $name = '')
    {
        list($id) = $request->getMore([
            ['id', 0]
        ], true);
        /** @var DiyServices $diyService */
        $diyService = app()->make(DiyServices::class);
        $data = $diyService->getDiy($id);
        return app('json')->success($data);
    }

    /**
     * @param int $id
     * @return mixed
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2023/2/8
     */
    public function getVersion($id = 0)
    {
        /** @var DiyServices $diyService */
        $diyService = app()->make(DiyServices::class);
        $data = $diyService->getDiyVersion((int)$id);
        return app('json')->success(['version' => $data['version'] ?: '', 'is_diy' => $data['is_diy']]);
    }

    /**
     * Có nên buộc buộc một số điện thoại di động hay không
     * @return mixed
     */
    public function bindPhoneStatus()
    {
        $status = (bool)sys_config('store_user_mobile');
        return app('json')->success(compact('status'));
    }

    /**
     * Bạn có chú ý không?
     * @param Request $request
     * @param WechatServices $services
     * @return mixed
     */
    public function subscribe(Request $request, WechatUserServices $services)
    {
        return app('json')->success(['subscribe' => (bool)$services->value(['uid' => $request->uid(), 'user_type' => 'wechat'], 'subscribe')]);
    }

    /**
     * Nhận trạng thái kích hoạt tự nhận hàng tại điểm đón
     * @return mixed
     */
    public function getStoreStatus()
    {
        $data['store_status'] = sys_config('store_self_mention', 0);
        return app('json')->success($data);
    }

    /**
     * Nhận lựa chọn màu sắc và lựa chọn mẫu danh mục
     * @param DiyServices $services
     * @param $name
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function colorChange(DiyServices $services, $name)
    {
        $status = (int)$services->getColorChange((string)$name);
        $is_diy = $services->value(['status' => 1, 'is_del' => 0], 'is_diy');
        return app('json')->success(compact('status', 'is_diy'));
    }

    public function getDiySign(Request $request)
    {
        $uid = (int)$request->uid();
        return app('json')->success(app()->make(UserSignServices::class)->signConfig($uid, 1));
    }
}
