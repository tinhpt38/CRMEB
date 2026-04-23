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
namespace app\adminapi\controller\v1\finance;

use app\adminapi\controller\AuthController;
use app\services\user\UserMoneyServices;
use think\facade\App;

class UserBalance extends AuthController
{
    /**
     * UserBalance constructor.
     * @param App $app
     * @param UserMoneyServices $services
     */
    public function __construct(App $app, UserMoneyServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Hồ sơ số dư
     * @return mixed
     */
    public function balanceList()
    {
        $where = $this->request->getMore([
            ['time', ''],
            ['trading_type', 0, '', 'type']
        ]);
        $date = $this->services->balanceList($where);
        return app('json')->success($date);
    }

    /**
     * Ghi chú về số dư
     * @return mixed
     */
    public function balanceRecordRemark($id = 0)
    {
        [$mark] = $this->request->postMore([
            ['mark', '']
        ], true);
        if (!$id) return app('json')->fail('Lỗi tham số');
        if ($mark === '') return app('json')->fail('Chú thích không được để trống');
        $this->services->recordRemark($id, $mark);
        return app('json')->success('Bình luận thành công');
    }
}
