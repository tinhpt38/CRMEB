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
 * Bảng liệt kê phương thức lớp logic
 * Lớp ServiceActionEnum
 * @author Chờ gió về
 * @email 136327134@qq.com
 * @date 2023/8/14
 * @package crmeb\services\crud\enum
 */
class ServiceActionEnum
{
    //tìm kiếm
    const INDEX = 'index';
    //Nhận biểu mẫu
    const FORM = 'form';
    //cứu
    const SAVE = 'save';
    //gia hạn
    const UPDATE = 'update';

    const SERVICE_ACTION_ALL = [
        self::INDEX,
        self::FORM,
        self::SAVE,
        self::UPDATE
    ];
}
