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
namespace app\adminapi\controller\v1\agent;

use app\adminapi\controller\AuthController;
use app\services\agent\SpreadApplyServices;
use think\facade\App;

class SpreadApply extends AuthController
{
    /**
     * @var SpreadApplyServices
     */
    protected $services;

    /**
     * SpreadApply constructor.
     * @param App $app
     * @param SpreadApplyServices $services
     */
    public function __construct(App $app, SpreadApplyServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Danh sách ứng dụng
     * @return mixed
     */
    public function applyList()
    {
        $where = $this->request->getMore([
            ['status', ''],
            ['keyword', ''],
        ]);
        return app('json')->success($this->services->applyList($where));
    }

    /**
     * xem xét ứng dụng
     * @param $id
     * @param $uid
     * @param $status
     * @return mixed
     */
    public function applyExamine($id, $uid, $status)
    {
        [$refusal_reason] = $this->request->postMore([
            ['refusal_reason', ''],
        ], true);
        $this->services->applyExamine($id, $uid, $status, $refusal_reason);
        return app('json')->success($status == 1 ? 'Tán thành' : 'từ chối thành công');
    }

    /**
     * Xóa ứng dụng
     * @param $id
     * @return mixed
     */
    public function applyDelete($id)
    {
        $this->services->applyDelete($id);
        return app('json')->success('Xóa thành công');
    }
}
