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
use app\services\system\lang\LangCountryServices;
use think\facade\App;

class LangCountry extends AuthController
{
    /**
     * @param App $app
     * @param LangCountryServices $services
     */    public function __construct(App $app, LangCountryServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Danh sách ngôn ngữ quốc gia
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function langCountryList()
    {
        $where = $this->request->getMore([
            ['keyword', ''],
        ]);
        return app('json')->success($this->services->langCountryList($where));
    }

    /**
     * Đặt biểu mẫu loại ngôn ngữ quốc gia
     * @param $id
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function langCountryForm($id)
    {
        return app('json')->success($this->services->langCountryForm($id));
    }

    /**
     * Sửa đổi ngôn ngữ khu vực
     * @param $id
     * @return mixed
     */    public function langCountrySave($id)
    {
        $data = $this->request->postMore([
            ['name', ''],
            ['code', ''],
            ['type_id', 0],
        ]);
        $this->services->langCountrySave($id, $data);
        return app('json')->success('Đã lưu thành công');
    }

    public function langCountryDel($id)
    {
        $this->services->langCountryDel($id);
        return app('json')->success('Xóa thành công');
    }
}