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

namespace app;

use Spatie\Macroable\Macroable;

/**
 * Class Request
 * @package app
 * @method tokenData() Nhận thông tin mã thông báo
 * @method user(string $key = null) Lấy thông tin Khách hàng
 * @method uid() Nhận Khách hànguid
 * @method isAdminLogin() Trạng thái đăng nhập phụ trợ
 * @method adminId() Quản trị viên hậu trườngid
 * @method adminInfo() Thông tin quản lý nền
 * @method kefuId() CSKHid
 * @method kefuInfo() Thông tin CSKH
 */class Request extends \think\Request
{
    use Macroable;

    /**
     * Không lọc tên biến
     * @var array
     */    protected $except = [
        'menu_path',
        'api_url',
        'unique_auth',
        'description',
        'custom_form',
        'params_list',
        'content',
        'tableField',
        'url',
        'customCode',
        'value',
        'refund_reason_wap_img',
        'mini_app_theme',
    ];

    /**
     * Nhận dữ liệu được yêu cầu
     * @param array $params
     * @param bool $suffix
     * @param bool $filter
     * @return array
     */    public function more(array $params, bool $suffix = false, bool $filter = true): array
    {
        $p = [];
        $i = 0;
        $this->filterArrayValues($this->param);
        foreach ($params as $param) {
            if (!is_array($param)) {
                $p[$suffix == true ? $i++ : $param] = $this->param($param);
            } else {
                if (!isset($param[1])) $param[1] = null;
                if (!isset($param[2])) $param[2] = '';
                if (is_array($param[0])) {
                    $name = is_array($param[1]) ? $param[0][0] . '/a' : $param[0][0] . '/' . $param[0][1];
                    $keyName = $param[0][0];
                } else {
                    $name = is_array($param[1]) ? $param[0] . '/a' : $param[0];
                    $keyName = $param[0];
                }

                $p[$suffix == true ? $i++ : ($param[3] ?? $keyName)] = $this->param($name, $param[1], $param[2]);
            }
        }

        if ($filter && $p) {
            $p = $this->filterArrayValues($p);
        }

        return $p;
    }

    /**
     * Lọc chuỗi trong một mảng
     * @param $str
     * @param bool $filter
     * @return array|mixed|string|string[]
     */    public function filterArrayValues($array)
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                // Nếu giá trị là một mảng và không nằm trong tên biến chưa được lọc, hãy gọi đệ quy filterArrayValues, nếu không thì gán giá trị trực tiếp
                $result[$key] = in_array($key, $this->except) ? $value : $this->filterArrayValues($value);
            } else {
                if (in_array($key, $this->except) || is_int($value) || is_null($value)) {
                    $result[$key] = $value;
                } else {
                    // Nếu giá trị là một chuỗi, hãy lọc các ký tự đặc biệt
                    $result[$key] = filter_str($value);
                }
            }
        }
        return $result;
    }

    /**
     * Nhận tham số
     * @param array $params
     * @param bool $suffix
     * @param bool $filter
     * @return array
     */    public function getMore(array $params, bool $suffix = false, bool $filter = true): array
    {
        return $this->more($params, $suffix, $filter);
    }

    /**
     * Nhận thông số bài viết
     * @param array $params
     * @param bool $suffix
     * @param bool $filter
     * @return array
     */    public function postMore(array $params, bool $suffix = false, bool $filter = true): array
    {
        return $this->more($params, $suffix, $filter);
    }

    /**
     * Nhận thiết bị đầu cuối truy cập của Khách hàng
     * @return array|string|null
     */    public function getFromType()
    {
        return $this->header('Form-type', '');
    }

    /**
     * Khách hàng hiện tại
     * @param string $terminal
     * @return bool
     */    public function isTerminal(string $terminal)
    {
        return strtolower($this->getFromType()) === $terminal;
    }

    /**
     * Đây có phải là kết thúc H5?
     * @return bool
     */    public function isH5()
    {
        return $this->isTerminal('h5');
    }

    /**
     * Đây có phải là Ứng dụng khách WeChat không?
     * @return bool
     */    public function isWechat()
    {
        return $this->isTerminal('wechat');
    }

    /**
     * Đây có phải là một chương trình nhỏ không?
     * @return bool
     */    public function isRoutine()
    {
        return $this->isTerminal('routine');
    }

    /**
     * Đây có phải là phía Ứng dụng?
     * @return bool
     */    public function isApp()
    {
        return $this->isTerminal('app');
    }

    /**
     * Đây có phải là phiên bản PC không?
     * @return bool
     */    public function isPc()
    {
        return $this->isTerminal('pc');
    }
}
