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

use app\services\user\UserBillServices;
use think\facade\App;
use app\adminapi\controller\AuthController;

/**
 * Class Finance
 * @package app\adminapi\controller\v1\finance
 */
class Finance extends AuthController
{
    /**
     * Finance constructor.
     * @param App $app
     * @param UserBillServices $services
     */
    public function __construct(App $app, UserBillServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Loại bộ lọc
     */
    public function bill_type()
    {
        return app('json')->success($this->services->bill_type());
    }

    /**
     * Hồ sơ tài trợ
     */
    public function list()
    {
        $where = $this->request->getMore([
            ['start_time', ''],
            ['end_time', ''],
            ['nickname', ''],
            ['limit', 20],
            ['page', 1],
            ['type', ''],
        ]);
        return app('json')->success($this->services->getBillList($where));
    }

    /**
     * hồ sơ ủy ban
     * @return mixed
     */
    public function get_commission_list()
    {
        $where = $this->request->getMore([
            ['nickname', ''],
            ['price_max', ''],
            ['price_min', ''],
            ['sum_number', 'normal'],
            ['brokerage_price', 'normal'],
            ['time', '']
        ]);
        return app('json')->success($this->services->getCommissionList($where));
    }

    /**
     * Hoa hồng chi tiết thông tin người dùng
     * @param $id
     * @return mixed
     */
    public function user_info($id)
    {
        return app('json')->success($this->services->user_info((int)$id));
    }

    /**
     * Danh sách cá nhân hồ sơ rút tiền hoa hồng
     */
    public function get_extract_list($id = '')
    {
        if ($id == '') return app('json')->fail('Lỗi tham số');
        $where = $this->request->getMore([
            ['start_time', ''],
            ['end_time', ''],
            ['nickname', '']
        ]);
        $where['category'] = 'now_money';
        $where['type'] = ['brokerage', 'brokerage_user'];
        return app('json')->success($this->services->getBillOneList((int)$id, $where));
    }

}
