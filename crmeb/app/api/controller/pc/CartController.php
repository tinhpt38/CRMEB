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

namespace app\api\controller\pc;


use app\Request;
use app\services\pc\CartServices;

class CartController
{
    protected $services;

    public function __construct(CartServices $services)
    {
        $this->services = $services;
    }

    /**
     * Lấy danh sách giỏ hàng của Khách hàng
     * @param Request $request
     * @return mixed
     */    public function getCartList(Request $request)
    {
        $uid = $request->uid();
        $data = $this->services->getCartList((int)$uid);
        return app('json')->success($data);
    }
}
