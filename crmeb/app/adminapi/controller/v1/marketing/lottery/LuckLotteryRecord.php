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
declare (strict_types=1);

namespace app\adminapi\controller\v1\marketing\lottery;

use app\adminapi\controller\AuthController;
use app\services\activity\lottery\LuckLotteryRecordServices;
use think\facade\App;

/**
 * Kỷ lục trúng xổ số
 * Class LuckLotteryRecord
 * @package app\controller\admin\v1\marketing\lottery
 */
class LuckLotteryRecord extends AuthController
{

    /**
     * LuckLotteryRecord constructor.
     * @param App $app
     * @param LuckLotteryRecordServices $services
     */
    public function __construct(App $app, LuckLotteryRecordServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Danh sách kỷ lục xổ số
     * @return mixed
     */
    public function index()
    {
        $where = $this->request->postMore([
            ['type', ''],
            ['keyword', ''],
            ['time', ''],
            ['lottery_id', ''],
        ]);
        return app('json')->success($this->services->getList($where));
    }

    /**
     * Giao hàng thắng lợi
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function deliver($id)
    {
        $data = $this->request->postMore([
            ['deliver_name', ''],
            ['deliver_number', ''],
            ['mark', ''],
        ]);
        if (!$id) {
            return app('json')->fail('Lỗi tham số');
        }
        $this->services->setDeliver((int)$id, $data);
        return app('json')->success($this->services->setDeliver((int)$id, $data) ? 'Thiết lập thành công' : 'Thiết lập không thành công');
    }
}
