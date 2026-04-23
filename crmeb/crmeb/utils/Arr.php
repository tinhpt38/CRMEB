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
 * Lớp trợ giúp mảng hoạt động
 * Class Arr
 * @package crmeb\utils
 */
class Arr
{
    /**
     * Thêm giá trị mặc định vào mảng
     * @param array $keys
     * @param array $configList
     * @return array
     */
    public static function getDefaultValue(array $keys, array $configList = [])
    {
        $value = [];
        foreach ($keys as $val) {
            if (is_array($val)) {
                $k = $val[0] ?? '';
                $v = $val[1] ?? '';
            } else {
                $k = $val;
                $v = '';
            }
            $value[$k] = $configList[$k] ?? $v;
        }
        return $value;
    }

    /**
     * Nhận danh sách menu ivew
     * @param array $data
     * @return array
     */
    public static function getMenuIviewList(array $data)
    {
        return Arr::toIviewUi(Arr::getTree($data));
    }

    /**
     * Chuyển đổi giá trị khóa theo yêu cầu của iviewUi
     * @param $data
     * @return array
     */
    public static function toIviewUi($data)
    {
        $newData = [];
        foreach ($data as $k => $v) {
            $temp = [];
            $temp['id'] = $v['id'];
            $temp['pid'] = $v['pid'];
            $temp['path'] = $v['menu_path'];
            $temp['title'] = $v['menu_name'];
            $temp['icon'] = $v['icon'];
            $temp['header'] = $v['header'];
            $temp['is_header'] = $v['is_header'];
            $temp['is_show'] = $v['is_show_path'];
            if ($v['is_show_path']) {
                $temp['auth'] = ['hidden'];
            }
            if (!empty($v['children'])) {
                $temp['children'] = self::toIviewUi($v['children']);
            }
            $newData[] = $temp;
        }
        return $newData;
    }

    /**
     * Nhận menu cây
     * @param $data
     * @param int $pid
     * @param int $level
     * @return array
     */
    public static function getTree($data, $pid = 0, $level = 1)
    {
        $childs = self::getChild($data, $pid, $level);
        $dataSort = array_column($childs, 'sort');
        array_multisort($dataSort, SORT_DESC, $childs);
        foreach ($childs as $key => $navItem) {
            $resChild = self::getTree($data, $navItem['id']);
            if (null != $resChild) {
                $childs[$key]['children'] = $resChild;
            }
        }
        return $childs;
    }

    /**
     * Nhận menu con
     * @param $arr
     * @param $id
     * @param $lev
     * @return array
     */
    private static function getChild(&$arr, $id, $lev)
    {
        $child = [];
        foreach ($arr as  $value) {
            if ($value['pid'] == $id) {
                $value['level'] = $lev;
                $child[] = $value;
            }
        }
        return $child;
    }

    /**
     * Định dạng dữ liệu
     * @param array $array
     * @param $value
     * @param int $default
     * @return mixed
     */
    public static function setValeTime(array $array, $value, $default = 0)
    {
        foreach ($array as $item) {
            if (!isset($value[$item]))
                $value[$item] = $default;
            else if (is_string($value[$item]))
                $value[$item] = (float)$value[$item];
        }
        return $value;
    }

    /**
     * Lấy một tập hợp các giá trị trong mảng hai chiều và sắp xếp lại thành một mảng,và xác định xem mỗi mục trong mảng có đúng không
     * @param array $data
     * @param string $filed
     * @return array
     */
    public static function getArrayFilterValeu(array $data, string $filed)
    {
        return array_filter(array_unique(array_column($data, $filed)), function ($item) {
            if ($item) {
                return $item;
            }
        });
    }

    /**
     * Chuyển đổi mảng thành chuỗi để loại bỏ trùng lặp
     * @param array $data
     * @return false|string[]
     */
    public static function unique(array $data)
    {
        return array_unique(explode(',', implode(',', $data)));
    }

    /**
     * Nhận giá trị khóa được chỉ định sau khi loại bỏ trùng lặp trong mảng
     * @param array $list
     * @param string $key
     * @return array
     */
    public static function getUniqueKey(array $list, string $key)
    {
        return array_unique(array_column($list, $key));
    }

    /**
     * Nhận một giá trị ngẫu nhiên từ một mảng
     * @param array $data
     * @return bool|mixed
     */
    public static function getArrayRandKey(array $data)
    {
        if (!$data) {
            return false;
        }
        $mun = rand(0, count($data));
        if (!isset($data[$mun])) {
            return self::getArrayRandKey($data);
        }
        return $data[$mun];
    }
}
