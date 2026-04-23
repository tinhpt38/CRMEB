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
use app\services\user\UserExtractServices;
use think\facade\Config;

/**
 * Rút tiền
 * Class UserExtractController
 * @package app\api\controller\user
 */
class UserExtractController
{
    protected $services = NUll;

    /**
     * UserExtractController constructor.
     * @param UserExtractServices $services
     */
    public function __construct(UserExtractServices $services)
    {
        $this->services = $services;
    }

    /**
     * Ngân hàng rút tiền
     * @param Request $request
     * @return mixed
     */
    public function bank(Request $request)
    {
        $uid = (int)$request->uid();
        return app('json')->success($this->services->bank($uid));
    }

    /**
     * Đơn xin rút tiền
     * @param Request $request
     * @return mixed
     */
    public function cash(Request $request)
    {
        $extractInfo = $request->postMore([
            ['alipay_code', ''],
            ['extract_type', ''],
            ['money', 0],
            ['user_name', ''],
            ['name', ''],
            ['bankname', ''],
            ['cardnum', ''],
            ['weixin', ''],
            ['qrcode_url', ''],
        ]);
        $extractInfo['channel_type'] = $request->getFromType();
        $extractType = Config::get('pay.extractType', []);
        if (!in_array($extractInfo['extract_type'], $extractType))
            return app('json')->fail('Không có phương thức rút tiền');
        if (!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', (float)$extractInfo['money'])) return app('json')->fail('Số tiền rút được nhập không chính xác');
        if (!$extractInfo['cardnum'] == '')
            if (!preg_match('/^([1-9]{1})(\d{15}|\d{16}|\d{18})$/', $extractInfo['cardnum']))
                return app('json')->fail('Số thẻ ngân hàng nhập sai');
        if ($extractInfo['extract_type'] == 'weixin') {
            if (trim($extractInfo['user_name']) == '') return app('json')->fail('Vui lòng điền tên thật của bạn');
        } elseif ($extractInfo['extract_type'] == 'alipay') {
            if (trim($extractInfo['alipay_code']) == '') return app('json')->fail('Vui lòng nhập số tài khoản Alipay của bạn');
            if (trim($extractInfo['user_name']) == '') return app('json')->fail('Vui lòng điền tên thật của bạn');
        } elseif ($extractInfo['extract_type'] == 'bank') {
            if (!$extractInfo['cardnum']) return app('json')->fail('Vui lòng nhập số tài khoản thẻ ngân hàng của bạn');
            if (!$extractInfo['bankname']) return app('json')->fail('Vui lòng nhập thông tin ngân hàng mở tài khoản');
        }
        $uid = (int)$request->uid();
        if ($this->services->cash($uid, $extractInfo))
            return app('json')->success('Ứng dụng rút tiền đã thành công');
        else
            return app('json')->fail('Không thể đăng ký rút tiền');
    }
}
