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

namespace app\api\controller\v1\wechat;


use app\Request;
use app\services\activity\live\LiveRoomServices;
use app\services\wechat\RoutineServices;

/**
 * Chương trình nhỏ liên quan
 * Class AuthController
 * @package app\api\controller\wechat
 */
class AuthController
{
    protected $services = NUll;

    /**
     * AuthController constructor.
     * @param RoutineServices $services
     */
    public function __construct(RoutineServices $services)
    {
        $this->services = $services;
    }

    /**
     * Đăng nhập được ủy quyền chương trình nhỏ
     * @param Request $request
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2023/02/24
     */
    public function mp_auth(Request $request)
    {
        [$code, $cache_key, $login_type, $spread_spid, $spread_code, $iv, $encryptedData] = $request->postMore([
            ['code', ''],
            ['cache_key', ''],
            ['login_type', ''],
            ['spread_spid', 0],
            ['spread_code', ''],
            ['iv', ''],
            ['encryptedData', ''],
        ], true);
        $token = $this->services->mp_auth($code, $cache_key, $login_type, $spread_spid, $spread_code, $iv, $encryptedData);
        if ($token) {
            if (isset($token['key']) && $token['key']) {
                return app('json')->success('Ủy quyền thành công, vui lòng liên kết số điện thoại di động của bạn', $token);
            } else {
                return app('json')->success('Đăng nhập thành công', [
                    'userInfo' => $token['userInfo']
                ]);
            }
        } else
            return app('json')->fail('Đăng nhập không thành công');
    }

    /**
     * Nhận ủy quyềnlogo
     * @return mixed
     */
    public function get_logo()
    {
        $logo = sys_config('wap_login_logo');
        if (strstr($logo, 'http') === false && $logo) $logo = sys_config('site_url') . $logo;
        return app('json')->success(['logo_url' => str_replace('\\', '/', $logo)]);
    }

    /**
     * Gọi lại thanh toán chương trình nhỏ
     */
    public function notify()
    {
        return $this->services->notify();
    }

    /**
     * Nhận tin nhắn đăng ký chương trình nhỏid
     * @return mixed
     */
    public function temp_ids()
    {
        return app('json')->success($this->services->tempIds());
    }

    /**
     * Nhận danh sách phát sóng trực tiếp chương trình mini
     * @param Request $request
     * @param LiveRoomServices $liveRoom
     * @return mixed
     */
    public function live(Request $request, LiveRoomServices $liveRoom)
    {
        return app('json')->success($liveRoom->userList([]));
    }

    /**
     * Nhận phát lại trực tiếp
     * @param $id
     * @param LiveRoomServices $lvieRoom
     * @return mixed
     */
    public function livePlaybacks($id, LiveRoomServices $lvieRoom)
    {
        return app('json')->success($lvieRoom->getPlaybacks((int)$id));
    }
}
