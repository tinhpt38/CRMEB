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

namespace app\services\yihaotong;


use app\dao\sms\SmsRecordDao;
use app\services\BaseServices;
/**
 * Bản ghi gửi SMS
 * Class SmsRecordServices
 * @package app\services\message\sms
 * @method save(array $data) lưu dữ liệu
 * @method getColumn(array $where, ?string $field, ?string $key = '')
 * @method update(int $id, array $data, ?string $field = '')
 * @method getCodeNull
 */class SmsRecordServices extends BaseServices
{
    /**
     * Người xây dựng
     * SmsRecordServices constructor.
     * @param SmsRecordDao $dao
     */    public function __construct(SmsRecordDao $dao)
    {
        $this->dao = $dao;
    }
}
