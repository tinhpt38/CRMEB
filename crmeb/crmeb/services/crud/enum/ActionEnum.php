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
 * Liệt kê phương thức truy cập
 * Lớp ActionEnum
 * @author Chờ gió về
 * @email 136327134@qq.com
 * @date 2023/8/12
 * @package crmeb\services\crud\enum
 */
class ActionEnum
{
    //danh sách
    const INDEX = 'index';
    //Nhận dữ liệu sáng tạo
    const CREATE = 'create';
    //cứu
    const SAVE = 'save';
    //Nhận dữ liệu chỉnh sửa
    const EDIT = 'edit';
    //Ôn lại
    const UPDATE = 'update';
    //tình trạng
    const STATUS = 'status';
    //xóa bỏ
    const DELETE = 'delete';
    //Kiểm tra
    const READ = 'read';
    //Tất cả các tên phương thức
    const ACTION_ALL = [
        self::INDEX,
        self::CREATE,
        self::SAVE,
        self::EDIT,
        self::UPDATE,
        self::STATUS,
        self::DELETE,
        self::READ
    ];
}
