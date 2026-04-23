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
namespace app\adminapi\controller\v1\application\wechat;

use app\adminapi\controller\AuthController;
use app\services\wechat\WechatMenuServices;
use think\facade\App;

/**
 * Bộ điều khiển menu WeChat
 * Class Menus
 * @package app\admin\controller\wechat
 */
class Menus extends AuthController
{
    /**
     * Người xây dựng
     * Menus constructor.
     * @param App $app
     * @param WechatMenuServices $services
     */
    public function __construct(App $app, WechatMenuServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Nhận thực đơn
     * @return mixed
     */
    public function index()
    {
        $menus = $this->services->getWechatMenu();
        return app('json')->success(compact('menus'));
    }

    /**
     * lưu thực đơn
     * @return mixed
     */
    public function save()
    {
        $buttons = request()->post('button/a', []);
        if(strlen($buttons[0]['name']) > 15) return app('json')->fail('Tên menu không được dài hơn 5 ký tự');
        if (!count($buttons)) return app('json')->fail('Vui lòng thêm ít nhất một nút');
        $this->services->saveMenu($buttons);
        return app('json')->success('Sửa đổi thành công');
    }
}
