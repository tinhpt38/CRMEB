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
use app\services\wechat\RoutineServices;
use crmeb\services\CacheService;


/**
 * Class AuthController
 * @package app\api\controller\v2\wechat
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
     * Trả về khóa bộ đệm của thông tin người dùng và trả về việc có buộc buộc liên kết số điện thoại di động hay không.
     * @param $code
     * @param string $spread_code
     * @param string $spread_spid
     * @return \think\Response
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/12
     */
    public function authType($code, $spread_code = '', $spread_spid = '')
    {
        $data = $this->services->authType($code, $spread_code, $spread_spid);
        return app('json')->success($data);
    }

    /**
     * Nhận từ bộ đệmtoken
     * @param $key
     * @return \think\Response
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/12
     */
    public function authLogin($key)
    {
        $data = $this->services->authLogin($key);
        return app('json')->success($data);
    }

    /**
     * Ủy quyền lấy số điện thoại di động của người dùng chương trình mini và liên kết trực tiếp
     * @param string $code
     * @param string $iv
     * @param string $encryptedData
     * @param string $spread_code
     * @param string $spread_spid
     * @param string $key
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function authBindingPhone($code = '', $iv = '', $encryptedData = '', $spread_code = '', $spread_spid = '', $key = '')
    {
        if (!$code || !$iv || !$encryptedData)
            return app('json')->fail('Lỗi tham số');
        $data = $this->services->authBindingPhone($code, $iv, $encryptedData, $spread_code, $spread_spid, $key);
        if ($data) {
            return app('json')->success('Đăng nhập thành công', $data);
        } else
            return app('json')->fail('Đăng nhập không thành công');
    }

    /**
     * Chương trình nhỏ đăng nhập số điện thoại di động
     * @param string $key
     * @param string $phone
     * @param string $captcha
     * @param string $spread_code
     * @param string $spread_spid
     * @param string $code
     * @return \think\Response
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/12
     */
    public function phoneLogin($key = '', $phone = '', $captcha = '', $spread_code = '', $spread_spid = '', $code = '')
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
        $data = $this->services->phoneLogin($key, $phone, $spread_code, 0, $spread_spid, $code);
        return app('json')->success($data);
    }

    /**
     * Chương trình mini liên kết số điện thoại di động
     * @param string $code
     * @param string $iv
     * @param string $encryptedData
     * @return \think\Response
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2023/02/24
     */
    public function bindingPhone($code = '', $iv = '', $encryptedData = '')
    {
        if (!$code || !$iv || !$encryptedData) return app('json')->fail('Lỗi tham số');
        $this->services->bindingPhone($code, $iv, $encryptedData);
        return app('json')->success('Ràng buộc thành công');
    }
}
