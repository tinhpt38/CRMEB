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
 * Kiểu biểu mẫu enum
 * Lớp FormTypeEnum
 * @author Chờ gió về
 * @email 136327134@qq.com
 * @date 2023/8/14
 * @package crmeb\services\crud\enum
 */
class FormTypeEnum
{
    //hộp thả xuống
    const SELECT = 'select';
    //Hộp nhập liệu
    const INPUT = 'input';
    //Hộp nhập số
    const NUMBER = 'number';
    //hộp văn bản nhiều dòng
    const TEXTAREA = 'textarea';
    //Ngày và giờ duy nhất
    const DATE_TIME = 'dateTime';
    //Lựa chọn phạm vi ngày và giờ
    const DATE_TIME_RANGE = 'dateTimeRange';
    //hộp kiểm
    const  CHECKBOX = 'checkbox';
    //công tắc
    const SWITCH = 'switches';
    //nút radio
    const  RADIO = 'radio';
    //Lựa chọn hình ảnh duy nhất
    const FRAME_IMAGE_ONE = 'frameImageOne';
    //Lựa chọn nhiều hình ảnh
    const  FRAME_IMAGES = 'frameImages';

    const FORM_TYPE_ALL = [
        self::INPUT,
        self::NUMBER,
        self::RADIO,
        self::SELECT,
        self::TEXTAREA,
        self::FRAME_IMAGE_ONE,
        self::FRAME_IMAGES,
        self::CHECKBOX,
        self::DATE_TIME,
        self::DATE_TIME_RANGE,
    ];


}
