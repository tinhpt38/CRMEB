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

use app\api\validate\user\StoreServiceFeedbackValidate;
use app\Request;
use app\services\kefu\service\StoreServiceFeedbackServices;
use app\services\kefu\service\StoreServiceLogServices;
use app\services\kefu\service\StoreServiceRecordServices;
use app\services\kefu\service\StoreServiceServices;
use app\services\other\CacheServices;
use crmeb\services\CacheService;

/**
 * Dịch vụ khách hàng
 * Class StoreService
 * @package app\api\controller\user
 */
class StoreService
{
    /**
     * @var StoreServiceLogServices
     */
    protected $services;

    /**
     * StoreService constructor.
     * @param StoreServiceLogServices $services
     */
    public function __construct(StoreServiceLogServices $services)
    {
        $this->services = $services;
    }

    /**
     * Danh sách dịch vụ khách hàng
     * @param StoreServiceServices $services
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function lst(StoreServiceServices $services)
    {
        $serviceInfoList = $services->getServiceList(['status' => 1]);
        if (!count($serviceInfoList)) return app('json')->success([]);
        return app('json')->success($serviceInfoList['list']);
    }

    /**
     * Lịch sử trò chuyện dịch vụ khách hàng
     * @param Request $request
     * @param StoreServiceServices $services
     * @param StoreServiceRecordServices $recordServices
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function record(Request $request, StoreServiceServices $services, StoreServiceRecordServices $recordServices)
    {
        list($uidTo) = $request->getMore([
            ['uidTo', 0]
        ], true);
        $serviceInfoList = $services->getServiceList(['status' => 1]);
        if (!count($serviceInfoList)) return app('json')->fail('Hiện tại chưa có nhân viên chăm sóc khách hàng trực tuyến, vui lòng liên hệ sau.');
        $uid = $request->uid();
        $uids = array_column($serviceInfoList['list'], 'uid');
        if (!$uidTo) {
            //Tôi là người phục vụ khách hàng
            if (in_array($uid, $uids)) {
                $uids = array_merge(array_diff($uids, [$uid]));
                if (!$uids) return app('json')->fail('Không thể trò chuyện với chính mình');
            }
        } else {
            if (in_array($uid, $uids)) {
                $uid = $uidTo;
            }
        }
        if (!$uids) {
            return app('json')->fail('Hiện tại chưa có nhân viên chăm sóc khách hàng trực tuyến, vui lòng liên hệ sau.');
        }
        //Cuộc trò chuyện ưu tiên dịch vụ khách hàng cuối cùng
        $toUid = $recordServices->value(['user_id' => $uid], 'to_uid');
        if (!in_array($toUid, $uids)) {
            $toUid = 0;
        }
        if (!$toUid) {
            $toUid = $uids[array_rand($uids)] ?? 0;
        }

        if (!$toUid) return app('json')->fail('Hiện tại chưa có nhân viên chăm sóc khách hàng trực tuyến, vui lòng liên hệ sau.');
        $result = ['serviceList' => [], 'uid' => $toUid];
        $serviceLogList = $this->services->getChatList(['uid' => $uid], $uid);
        if (!$serviceLogList) return app('json')->success($result);
        $idArr = array_column($serviceLogList, 'id');
        array_multisort($idArr, SORT_ASC, $serviceLogList);
        $result['serviceList'] = $serviceLogList;
        return app('json')->success($result);
    }

    /**
     * Lấy nội dung quảng cáo trên trang chăm sóc khách hàng
     * @return mixed
     */
    public function getKfAdv()
    {
        /** @var CacheServices $cache */
        $cache = app()->make(CacheServices::class);
        $content = $cache->getDbCache('kf_adv', '');
        return app('json')->success(compact('content'));
    }

    /**
     * Lưu phản hồi
     * @param Request $request
     * @param StoreServiceFeedbackServices $services
     * @return mixed
     */
    public function saveFeedback(Request $request, StoreServiceFeedbackServices $services)
    {
        $data = $request->postMore([
            ['rela_name', ''],
            ['phone', ''],
            ['content', ''],
        ]);

        validate(StoreServiceFeedbackValidate::class)->check($data);

        $data['content'] = htmlspecialchars($data['content']);
        $data['add_time'] = time();
        $data['uid'] = $request->uid();
        $services->save($data);
        return app('json')->success('Đã lưu thành công');
    }

    /**
     * Văn bản tiêu đề của trang phản hồi dịch vụ khách hàng
     * @return mixed
     */
    public function getFeedbackInfo()
    {
        return app('json')->success(['feedback' => sys_config('service_feedback')]);
    }

    /**
     * Xác nhận đăng nhập
     * @param Request $request
     * @param StoreServiceServices $services
     * @param string $code
     * @return mixed
     */
    public function setLoginCode(Request $request, StoreServiceServices $services, string $code)
    {
        if (!$code) {
            return app('json')->fail('Quét không thành công, vui lòng quét lại');
        }
        $cacheCode = CacheService::get($code);
        if ($cacheCode === false || $cacheCode === null) {
            return app('json')->fail('Mã QR đã hết hạn. Vui lòng quét lại.');
        }
        $userInfo = $services->get(['uid' => $request->uid()]);
        if (!$userInfo) {
            return app('json')->fail('Bạn không phải là nhân viên dịch vụ khách hàng và không thể đăng nhập.');
        }
        $userInfo->uniqid = $code;
        $userInfo->save();
        CacheService::set($code, '0', 600);
        return app('json')->success('Đăng nhập thành công');
    }

    /**
     * Nhận lịch sử trò chuyện của dịch vụ khách hàng và người dùng hiện tại
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function recordList(StoreServiceRecordServices $services, Request $request, string $nickname = '', $is_tourist = 0)
    {
        return app('json')->success($services->getServiceList((int)$request->uid(), $nickname, (int)$is_tourist));
    }
}
