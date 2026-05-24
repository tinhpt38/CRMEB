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

namespace app\dao\sms;

use app\dao\BaseDao;
use app\model\sms\SmsRecord;

/**
 * Bản ghi gửi SMS
 * Class SmsRecordDao
 * @package app\dao\sms
 */class SmsRecordDao extends BaseDao
{
    /**
     * Thiết lập mô hình
     * @return string
     */    public function setModel(): string
    {
        return SmsRecord::class;
    }

    /**
     * Bản ghi gửi SMS
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getRecordList(array $where, int $page, int $limit)
    {
        return $this->search($where)->page($page, $limit)->order('add_time DESC')->select()->toArray();
    }

    /**
     * Nhận 20 bản ghi tin nhắn văn bản không trạng thái trong 10 phút qua
     * @return array
     */    public function getCodeNull()
    {
        return $this->getModel()->where([
            ['resultcode', '=', null],
            ['add_time', '<=', time() - 600],
            ['record_id', '>', 0]
        ])->limit(20)->column('record_id');
    }

}
