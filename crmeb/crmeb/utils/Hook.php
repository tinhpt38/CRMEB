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
namespace crmeb\utils;

use think\helper\Str;

/**
 *
 * Class Hook
 * @package crmeb\utils
 */
class Hook
{
    /**
     * Tên lớp
     * @var string
     */
    protected $namespace;

    /**
     * tiền tố phương thức
     * @var string
     */
    protected $prefix;

    /**
     * Người xây dựng
     * Hook constructor.
     * @param string $namespace
     * @param string|null $prefix
     */
    public function __construct(string $namespace, string $prefix = null)
    {
        $this->namespace = $namespace;
        if ($prefix) {
            $this->prefix = $prefix;
        }
    }

    /**
     * Thực hiện phương thức gắn kết
     * @param string $hookName
     * @param mixed ...$arguments
     * @return bool
     */
    public function listen(string $hookName, ...$arguments)
    {
        if (class_exists($this->namespace)) {
            $handle = app()->make($this->namespace);
            $hookName = Str::studly(($this->prefix ?: '') . ucfirst($hookName));
            if (method_exists($handle, $hookName)) {
                try {
                    return call_user_func_array([$handle, $hookName], $arguments);
                } catch (\Throwable $e) {
                }
            }
        }
        return false;
    }
}
