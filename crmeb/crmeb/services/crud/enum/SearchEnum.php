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

namespace crmeb\services\crud\enum;

/**
 * Bảng liệt kê phương pháp tìm kiếm
 * Lớp SearchEnum
 * @author Chờ gió về
 * @email 136327134@qq.com
 * @date 2023/8/14
 * @package crmeb\services\crud\enum
 */
class SearchEnum
{
    //bình đẳng
    const SEARCH_TYPE_EQ = '=';
    //nhỏ hơn hoặc bằng
    const SEARCH_TYPE_LTEQ = '<=';
    //Lớn hơn hoặc bằng
    const SEARCH_TYPE_GTEQ = '>=';
    //không bằng
    const SEARCH_TYPE_NEQ = '<>';
    //tìm kiếm mờ
    const SEARCH_TYPE_LIKE = 'LIKE';
    //Khoảng thời gian - được sử dụng để tìm kiếm khoảng thời gian
    const SEARCH_TYPE_BETWEEN = 'BETWEEN';

    //Kiểu tìm kiếm
    const SEARCH_TYPE = [
        self::SEARCH_TYPE_EQ,
        self::SEARCH_TYPE_LTEQ,
        self::SEARCH_TYPE_GTEQ,
        self::SEARCH_TYPE_NEQ,
        self::SEARCH_TYPE_LIKE,
        self::SEARCH_TYPE_BETWEEN,
    ];
}
