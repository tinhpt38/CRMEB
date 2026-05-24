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

if (!function_exists('get_this_class_methods')) {
    /**Nhận phương thức lớp hiện tại
     * @param $class
     * @return array
     */    function get_this_class_methods($class, $unarray = [])
    {
        $arrayall = get_class_methods($class);
        if ($parent_class = get_parent_class($class)) {
            $arrayparent = get_class_methods($parent_class);
            $arraynow = array_diff($arrayall, $arrayparent);//xóa cha mẹ
        } else {
            $arraynow = $arrayall;
        }
        return array_diff($arraynow, $unarray);//loại bỏ vô dụng
    }
}


if (!function_exists('setconfig')) {
    /**
     * Chức năng sửa đổi cấu hình
     * @param $arr1 or $string Tiền tố cấu hình
     * @param $arr2 or $string biến dữ liệu
     * @return bool trạng thái trả về
     */    function setconfig($name, $pat, $rep)
    {
        /**
         * Nguyên tắc là mở tệp cấu hình cấu hình, sử dụng tìm kiếm và thay thế thông thường, sau đó lưu tệp. Không thể sửa đổi cấu hình có giá trị là mảng.
         * Tham số được truyền vào là 2 mảng, mảng trước là cấu hình và mảng sau là giá trị số. Sự kết hợp thông thường là một trích dẫn duy nhất. Nếu của bạn là dấu chấm phẩy, vui lòng đổi nó thành dấu chấm phẩy.
         * $pat[0] = Tiền tố tham số;  ví dụ:   default_return_type
         * $rep[0] = Thay thế cái gì;    ví dụ:  json
         */        $pats = $reps = [];
        if (is_array($pat) && is_array($rep)) {
            for ($i = 0; $i < count($pat); $i++) {
                $pats[$i] = '/\'' . $pat[$i] . '\'(.*?),/';
                $reps[$i] = "'" . $pat[$i] . "'" . "=>" . "'" . $rep[$i] . "',";
            }
            $fileurl = app()->getConfigPath() . $name . ".php";
            $string = file_get_contents($fileurl); //Tải tập tin cấu hình
            $string = preg_replace($pats, $reps, $string); // Tìm kiếm và thay thế thường xuyên
            @file_put_contents($fileurl, $string); // Viết tập tin cấu hình
            return true;
        } else if (is_string($pat) && is_string($rep)) {
            $pats = '/\'' . $pat . '\'(.*?),/';
            if (substr_count($rep, '[')) {
                $reps = "'" . $pat . "'" . "=>" . $rep . ",";
            } else {
                $rep = str_replace('\'', "", $rep);
                $reps = "'" . $pat . "'" . "=>" . "'" . $rep . "',";
            }
            $fileurl = app()->getConfigPath() . $name . ".php";
            $string = file_get_contents($fileurl); //Tải tập tin cấu hình
            $string = preg_replace($pats, $reps, $string); // Tìm kiếm và thay thế thường xuyên
            @file_put_contents($fileurl, $string); // Viết tập tin cấu hình
            return true;
        } else {
            return false;

        }
    }
}
if (!function_exists('arrayToText')) {
    /**
     * Chức năng sửa đổi cấu hình
     * @param $array
     * @return string
     */    function arrayToText($array)
    {
        $config = print_r($array, true);
        $config = str_replace('[', "\"", $config);
        $config = str_replace(']', "\"", $config);
        $input = explode("\n", $config);
        foreach ($input as $k => $v) {
            if (empty($v) || strpos($v, 'Array') !== false || strpos($v, '(') !== false || strpos($v, ')') !== false) {
                continue;
            }
            $tmpValArr = explode('=>', $v);
            if (count($tmpValArr) == 2) {
                $input[$k] = $tmpValArr[0] . '=> \'' . trim($tmpValArr[1]) . '\',';
            }
        }
        $config = implode("\n", $input);
        $config = str_replace('Array', "", $config);
        $config = str_replace('(', "[", $config);
        $config = str_replace(')', "],", $config);
        $config = rtrim($config, "\n");
        $config = rtrim($config, ",");
        $config = "<?php \n return " . $config . ';';
//        $fileurl = app()->getConfigPath() ."templates.php";
//        @file_put_contents($fileurl, $config); // Viết tập tin cấu hình
        return $config;
    }
}
if (!function_exists('attr_format')) {
    /**
     * Thuộc tính định dạng
     * @param $arr
     * @return array
     */    function attr_format($arr): array
    {
        $len = count($arr);
        $title = array_column($arr, 'value');
        $result = [];

        // Khi mảng thuộc tính không trống, định dạng tổ hợp
        if ($len > 0) {
            // Khi loại thuộc tính lớn hơn 1, cần phải có tổ hợp tích Descartes
            if ($len > 1) {
                // Đầu tiên lấy tập chi tiết thuộc tính đầu tiên làm kết quả ban đầu
                $result = $arr[0]['detail'];
                // Lần lượt thực hiện các kết hợp theo cặp với từng bộ chi tiết thuộc tính tiếp theo
                for ($i = 0; $i < $len - 1; $i++) {
                    // Lưu tập kết quả hiện tại cho chu kỳ tiếp theo
                    $temp = $result;
                    // Xóa kết quả và chuẩn bị thu thập lại các kết hợp mới
                    $result = [];
                    // Duyệt qua Tất cả các kết hợp thu được ở vòng trước
                    foreach ($temp as $item) {
                        // Nối lần lượt sự kết hợp hiện tại với tập hợp chi tiết thuộc tính tiếp theo
                        foreach ($arr[$i + 1]['detail'] as $datum) {
                            // Nếu phần tử là một mảng, hãy sử dụng giá trị giá trị để ghép nó; nếu không, hãy ghép nó trực tiếp.
                            if (is_array($item)) {
                                $result[] = trim($item['value']) . ',' . trim($datum['value']);
                            } else {
                                $result[] = trim($item) . ',' . trim($datum);
                            }
                        }
                    }
                }
            } else {
                // Khi chỉ có một thuộc tính, truy xuất trực tiếp Tất cả các giá trị thuộc tính của nhóm.
                foreach ($arr[0]['detail'] as $item) {
                    // Cũng phân biệt giữa mảng và không mảng và lấy giá trị thống nhất hoặc trực tiếp.
                    if (is_array($item)) {
                        $result[] = trim($item['value']);
                    } else {
                        $result[] = trim($item);
                    }
                }
            }
        }
        // Trả về danh sách giá trị thuộc tính kết hợp và danh sách tên thuộc tính
        return [$result, $title];
    }
}

if (!function_exists('verify_domain')) {

    /**
     * Xác minh xem tên miền có hợp pháp không
     * @param string $domain
     * @return bool
     */    function verify_domain(string $domain): bool
    {
        $res = "/^(?=^.{3,255}$)(http(s)?:\/\/)(www\.)?[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+(:\d+)*(\/\w+\.\w+)*$/";
        if (preg_match($res, $domain))
            return true;
        else
            return false;
    }
}
