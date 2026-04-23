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
namespace app\adminapi\controller\v1\user;

use app\adminapi\controller\AuthController;
use app\services\user\UserCancelServices;
use think\facade\App;

class UserCancel extends AuthController
{
    /**
     * UserCancel constructor.
     * @param App $app
     * @param UserCancelServices $services
     */
    public function __construct(App $app, UserCancelServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Nhận danh sách đăng xuất
     * @return mixed
     */
    public function getCancelList()
    {
        $where = $this->request->postMore([
            ['status', 0],
            ['keywords', ''],
        ]);
        $data = $this->services->getCancelList($where);
        return app('json')->success($data);
    }

    /**
     * Nhận xét
     * @return mixed
     */
    public function setMark()
    {
        [$id, $mark] = $this->request->postMore([
            ['id', 0],
            ['mark', ''],
        ], true);
        $this->services->serMark($id, $mark);
        return app('json')->success('Bình luận thành công');
    }

    public function agreeCancel($id)
    {
        return app('json')->success('Đăng xuất thành công');
    }

    public function refuseCancel($id)
    {
        return app('json')->success('Từ chối đăng xuất');
    }
}
