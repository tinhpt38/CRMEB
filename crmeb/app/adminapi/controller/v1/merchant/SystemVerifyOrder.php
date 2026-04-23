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
namespace app\adminapi\controller\v1\merchant;

use app\adminapi\controller\AuthController;
use app\services\order\StoreOrderServices;
use app\services\user\UserServices;
use think\facade\App;

/**
 * Viết đơn đặt hàng
 * Class SystemVerifyOrder
 * @package app\adminapi\controller\v1\merchant
 */
class SystemVerifyOrder extends AuthController
{
    /**
     * Người xây dựng
     * SystemVerifyOrder constructor.
     * @param App $app
     * @param StoreOrderServices $services
     */
    public function __construct(App $app, StoreOrderServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Nhận danh sách các lệnh xóa nợ
     * return json
     */
    public function list()
    {
        $where = $this->request->getMore([
            ['data', '', '', 'time'],
            ['real_name', ''],
            ['store_id', ''],
            ['type', ''],
            ['field_key', ''],
        ]);
        $data = $this->services->getOrderList($where + ['status' => 6], ['*'], ['store', 'staff']);
        return app('json')->success(['count' => $data['count'], 'data' => $data['data']]);
    }

    /**
     * Không được sử dụng,Nhận người đứng đầu lệnh xóa nợ
     * @return mixed
     */
    public function getVerifyBadge()
    {
        return app('json')->success([]);
    }

    /**
     * Chi tiết người giới thiệu danh sách đặt hàng
     * @param $uid
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function order_spread_user($uid)
    {
        /** @var UserServices $userServices */
        $userServices = app()->make(UserServices::class);
        $spread = [];
        $spread = $userServices->getUserInfo((int)$uid);
        if ($spread) {
            $spread = $spread->toArray();
            $spread['brokerage_pric'] = $spread['brokerage_price'];
            $spread['birthday'] = $spread['birthday'] ? date('Y-m-d', $spread['birthday']) : '';
            $spread['last_time'] = $spread['last_time'] ? date('Y-m-d H:i:s', $spread['last_time']) : '';
        }
        return app('json')->success(['spread' => $spread]);
    }
}
