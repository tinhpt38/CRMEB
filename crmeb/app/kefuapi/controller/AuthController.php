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

namespace app\kefuapi\controller;


use crmeb\basic\BaseController;

/**
 * Class AuthController
 * @package app\kefuapi\controller
 */abstract class AuthController extends BaseController
{

    /**
     * @var int
     */    protected $kefuId;

    /**
     * @var array
     */    protected $kefuInfo;

    /**
     * khởi tạo
     */    protected function initialize()
    {
        $this->kefuId = $this->request->kefuId();
        $this->kefuInfo = $this->request->kefuInfo();
    }
}
