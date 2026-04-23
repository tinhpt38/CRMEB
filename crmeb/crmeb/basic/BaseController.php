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

namespace crmeb\basic;

use think\facade\App;

/**
 * Lớp cơ sở điều khiển
 */
abstract class BaseController
{
    /**
     * RequestVí dụ
     * @var \app\Request
     */
    protected $request;

    /**
     * Ví dụ ứng dụng
     * @var \think\App
     */
    protected $app;

    /**
     * Phần mềm trung gian điều khiển
     * @var array
     */
    protected $middleware = [];

    /**
     * @var
     */
    protected $services;

    /**
     * Địa chỉ giao diện yêu cầu ủy quyền
     * @var string[]
     */
    private $authRule = [];
    /**
     * Người xây dựng
     * @access public
     * @param App $app Đối tượng ứng dụng
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->request = app('request');
        $this->initialize();
    }

    /**
     * @return mixed
     */
    abstract protected function initialize();


}
