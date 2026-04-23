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
namespace app\adminapi\controller;


use crmeb\basic\BaseController;
use think\facade\Validate;

/**
 * Lớp cơ sở Lớp mà tất cả các bộ điều khiển kế thừa từ đó
 * Class AuthController
 * @package app\adminapi\controller
 */
class AuthController extends BaseController
{
    /**
     * Thông tin quản trị viên đã đăng nhập hiện tại
     * @var
     */
    protected $adminInfo;

    /**
     * Quản trị viên hiện đang đăng nhậpID
     * @var
     */
    protected $adminId;

    /**
     * Quyền quản trị viên hiện tại
     * @var array
     */
    protected $auth = [];


    /**
     * khởi tạo
     */
    protected function initialize()
    {
        $this->adminId = $this->request->adminId();
        $this->adminInfo = $this->request->adminInfo();
        $this->auth = $this->request->adminInfo['rule'] ?? [];
    }

    /**
     * Xác thực dữ liệu
     * @param array $data
     * @param $validate
     * @param null $message
     * @param bool $batch
     * @return bool
     */
    final protected function validate(array $data, $validate, $message = null, bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // Các tình huống hỗ trợ
                list($validate, $scene) = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }

            if (is_string($message) && empty($scene)) {
                $v->scene($message);
            }
        }

        if (is_array($message))
            $v->message($message);


        // Có xác minh hàng loạt hay không
        if ($batch) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }
}
