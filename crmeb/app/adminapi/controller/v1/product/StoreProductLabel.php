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
use app\services\product\product\StoreProductLabelCateServices;
use app\services\product\product\StoreProductLabelServices;
use think\facade\App;

class StoreProductLabel extends AuthController
{
    protected $labelCateServices;
    protected $labelServices;

    public function __construct(App $app, StoreProductLabelCateServices $labelCateServices, StoreProductLabelServices $labelServices)
    {
        parent::__construct($app);
        $this->labelCateServices = $labelCateServices;
        $this->labelServices = $labelServices;
    }

    public function labelCateList()
    {
        $where = $this->request->getMore([
            ['name', ''],
        ]);
        $where['is_del'] = 0;
        return app('json')->success($this->labelCateServices->getLabelCateList($where));
    }

    public function labelCateForm($id)
    {
        return app('json')->success($this->labelCateServices->labelCateForm($id));
    }

    public function labelCateSave($id)
    {
        $data = $this->request->postMore([
            ['name', ''],
            ['sort', 0],
        ]);
        $this->validate($data, \app\adminapi\validate\product\StoreProductLabelValidate::class, 'save_cate');
        $this->labelCateServices->labelCateSave($id, $data);
        return app('json')->success('Đã lưu thành công');
    }

    public function labelCateDel($id)
    {
        $this->labelCateServices->labelCateDel($id);
        return app('json')->success('Xóa thành công');
    }


    public function labelList()
    {
        $where = $this->request->getMore([
            ['name', ''],
            ['cate_id', ''],
            ['status', ''],
            ['is_show', ''],
        ]);
        $where['is_del'] = 0;
        return app('json')->success($this->labelServices->LabelList($where));
    }

    public function labelInfo($id)
    {
        return app('json')->success($this->labelServices->labelInfo($id));

    }

    public function labelSave()
    {
        $data = $this->request->postMore([
            ['id', 0],
            ['name', ''],
            ['cate_id', 0],
            ['type', 0],
            ['font_color', ''],
            ['bg_color', ''],
            ['border_color', ''],
            ['image', ''],
            ['sort', 0],
            ['status', 1],
            ['is_show', 1],
        ]);
        $this->validate($data, \app\adminapi\validate\product\StoreProductLabelValidate::class, 'save_label');
        $this->labelServices->labelSave($data);
        return app('json')->success('Đã lưu thành công');
    }

    public function labelDel($id)
    {
        $this->labelServices->labelDel($id);
        return app('json')->success('Xóa thành công');
    }

    public function labelIsShow($id, $is_show)
    {
        $this->labelServices->labelIsShow($id, $is_show);
        return app('json')->success('Sửa đổi thành công');
    }

    public function labelStatus($id, $status)
    {
        $this->labelServices->labelStatus($id, $status);
        return app('json')->success('Sửa đổi thành công');
    }

    public function labelUseList()
    {
        return app('json')->success($this->labelServices->labelUseList());
    }
}
