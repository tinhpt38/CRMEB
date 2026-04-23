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
namespace app\adminapi\controller\v1\setting;

use app\adminapi\controller\AuthController;
use app\services\other\AgreementServices;
use think\facade\App;

class SystemAgreement extends AuthController
{
    /**
     * Người xây dựng
     * SystemCity constructor.
     * @param App $app
     * @param AgreementServices $services
     */
    public function __construct(App $app, AgreementServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Nhận nội dung thỏa thuận
     * @param $type
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAgreement($type)
    {
        if (!$type) return app('json')->fail('Loại giao thức không tồn tại');
        $info = $this->services->getAgreementBytype($type);
        return app('json')->success($info);
    }

    /**
     * Lưu nội dung thỏa thuận
     * @return mixed
     */
    public function saveAgreement()
    {
        $data = $this->request->postMore([
            ['id', 0],
            ['type', 0],
            ['title', ''],
            ['content', ''],
        ]);
        $data['status'] = 1;
        $this->services->saveAgreement($data, $data['id']);
        return app('json')->success('Đã lưu thành công');
    }
}
