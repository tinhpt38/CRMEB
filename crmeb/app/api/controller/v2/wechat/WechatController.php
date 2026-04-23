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
namespace app\api\controller\v2\wechat;

use app\Request;
use app\services\wechat\WechatServices;
use crmeb\services\CacheService;

/**
 * Class WechatController
 * @package app\api\controller\v2\wechat
 */
class WechatController
{
    protected $services = NUll;

    /**
     * WechatController constructor.
     * @param WechatServices $services
     */
    public function __construct(WechatServices $services)
    {
        $this->services = $services;
    }

    /**
     * Tài khoản chính thức được ủy quyền đăng nhập, quay lạitoken
     * @param $spread
     * @return \think\Response
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/12
     */
    public function authLogin($spread = '', $agent_id = '')
    {
        $data = $this->services->authLogin($spread, $agent_id);
        return app('json')->success($data);
    }

    /**
     * Tài khoản chính thức được ủy quyền để ràng buộc số điện thoại di động
     * @param string $key
     * @param string $phone
     * @param string $captcha
     * @return \think\Response
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/12
     */
    public function authBindingPhone($key = '', $phone = '', $captcha = '')
    {
        //Xác minh mã xác minh
        $verifyCode = CacheService::get('code_' . $phone);
        if (!$verifyCode)
            return app('json')->fail('Vui lòng lấy mã xác minh trước');
        $verifyCode = substr($verifyCode, 0, 6);
        if ($verifyCode != $captcha) {
            CacheService::delete('code_' . $phone);
            return app('json')->fail('Lỗi mã xác minh');
        }
        CacheService::delete('code_' . $phone);
        $data = $this->services->authBindingPhone($key, $phone);
        return app('json')->success($data);
    }
}
