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
namespace app\adminapi\controller\v1\product;

use app\adminapi\controller\AuthController;
use app\services\product\product\StoreProductProtectionServices;
use think\facade\App;

class StoreProductProtection extends AuthController
{
    public function __construct(App $app, StoreProductProtectionServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    public function protectionList()
    {
        $where = $this->request->getMore([
            ['title', ''],
            ['status', '']
        ]);
        $where['is_del'] = 0;
        return app('json')->success($this->services->protectionList($where));
    }

    public function protectionInfo($id)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');
        $info = $this->services->protectionInfo($id);
        return app('json')->success($info);
    }

    public function protectionForm($id)
    {
        return app('json')->success($this->services->protectionForm($id));
    }

    public function protectionSave($id)
    {
        $data = $this->request->postMore([
            ['title', ''],
            ['content', ''],
            ['image', ''],
            ['sort', 0],
            ['status', 0]
        ]);
        $this->validate($data, \app\adminapi\validate\product\StoreProductProtectionValidate::class, 'save');
        $this->services->protectionSave($id, $data);
        return app('json')->success('Đã lưu thành công');
    }

    public function protectionStatus($id, $status)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');
        $this->services->protectionStatus($id, $status);
        return app('json')->success('Sửa đổi thành công');
    }

    public function protectionDel($id)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');
        $this->services->protectionDel($id);
        return app('json')->success('Xóa thành công');
    }
}
