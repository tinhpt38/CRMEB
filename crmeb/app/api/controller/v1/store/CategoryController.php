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
namespace app\api\controller\v1\store;

use app\services\product\product\StoreCategoryServices;
use think\Request;

/**
 * Class CategoryController
 * @package app\api\controller\v1\store
 */
class CategoryController
{
    protected $services;

    public function __construct(StoreCategoryServices $services)
    {
        $this->services = $services;
    }

    /**
     * Nhận danh sách danh mục
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function category(Request $request)
    {
        $where = $request->getMore([
            ['pid', 0],
        ]);
        $category = $this->services->getCategory($where);
        return app('json')->success($category);
    }

    /**
     * @return mixed
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2022/11/11
     */
    public function getCategoryVersion()
    {
        $data = $this->services->getCategoryVersion();
        return app('json')->success(['version' => $data['version'], 'is_diy' => $data['is_diy']]);
    }
}
