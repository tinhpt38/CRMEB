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
use app\services\system\lang\LangTypeServices;
use crmeb\services\CacheService;
use think\facade\App;

class LangType extends AuthController
{
    /**
     * @param App $app
     * @param LangTypeServices $services
     */
    public function __construct(App $app, LangTypeServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Nhận danh sách các loại ngôn ngữ
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function langTypeList()
    {
        $where['is_del'] = 0;
        return app('json')->success($this->services->langTypeList($where));
    }

    /**
     * Thêm biểu mẫu loại ngôn ngữ
     * @param int $id
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function langTypeForm(int $id = 0)
    {
        return app('json')->success($this->services->langTypeForm($id));
    }

    /**
     * Lưu loại ngôn ngữ
     * @return mixed
     */
    public function langTypeSave()
    {
        $data = $this->request->postMore([
            ['id', 0],
            ['language_name', ''],
            ['file_name', ''],
            ['is_default', 0],
            ['status', 0]
        ]);
        $this->services->langTypeSave($data);
        CacheService::delete('lang_type_data');
        return app('json')->success('Đã lưu thành công');
    }

    /**
     * Sửa đổi trạng thái loại ngôn ngữ
     * @param $id
     * @param $status
     * @return mixed
     */
    public function langTypeStatus($id, $status)
    {
        $this->services->langTypeStatus($id, $status);
        return app('json')->success('Thiết lập thành công');
    }

    /**
     * Xóa loại ngôn ngữ
     * @param int $id
     * @return mixed
     */
    public function langTypeDel(int $id = 0)
    {
        $this->services->langTypeDel($id);
        CacheService::delete('lang_type_data');
        return app('json')->success('Xóa thành công');
    }
}
