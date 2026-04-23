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

/**
 * Lớp trợ giúp thao tác chuỗi
 * Class Str
 * @package crmeb\utils
 */
class Str
{
    /**
     * @param $action
     * @param $controller
     * @param $module
     * @param $route
     * @return string
     */
    public static function getAuthName(string $action, string $controller, string $module, $route)
    {
        return strtolower($module . '/' . $controller . '/' . $action . '/' . self::paramStr($route));
    }

    /**
     * @param $params
     * @return string
     */
    public static function paramStr($params)
    {
        if (!is_array($params)) $params = json_decode($params, true) ?: [];
        $p = [];
        foreach ($params as $key => $param) {
            $p[] = $key;
            $p[] = $param;
        }
        return implode('/', $p);
    }

    /**
     * Chặn các byte tiếng Trung được chỉ định
     * @param string $str
     * @param int $utf8len
     * @param string $chaet
     * @param string $file
     * @return string
     */
    public static function substrUTf8($str, $utf8len = 100, $chaet = 'UTF-8', $file = '....')
    {
        if (mb_strlen($str, $chaet) > $utf8len) {
            $str = mb_substr($str, 0, $utf8len, $chaet) . $file;
        }
        return $str;
    }
}
