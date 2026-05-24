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

namespace app\model\sms;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;
use think\Model;

/**
 *  bản ghi tin nhắn SMSModel
 * Class SmsRecord
 * @package app\model\sms
 */class SmsRecord extends BaseModel
{
    use ModelTrait;

    /**
     * Khóa chính của bảng dữ liệu
     * @var string
     */    protected $pk = 'id';

    /**
     * Tên mẫu
     * @var string
     */    protected $name = 'sms_record';

    /**
     * trạng thái tin nhắn
     * @var array
     */    protected $resultcode = ['100' => 'thành công', '130' => 'thất bại', '131' => 'Số trống', '132' => 'tắt máy', '133' => 'Tắt máy', '134' => 'không quốc tịch'];

    /**
     * Công cụ lấy thời gian
     * @param $value
     * @return false|string
     */    protected function getAddTimeAttr($value)
    {
        return $value ? date('Y-m-d H:i:s', $value) : '';
    }

    /**
     * bộ lấy mã trạng thái
     * @param $value
     * @return mixed|string
     */    protected function getResultcodeAttr($value)
    {
        return $this->resultcode[$value] ?? 'không quốc tịch';
    }

    /**
     * công cụ tìm số điện thoại
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchPhoneAttr($query, $value)
    {
        $query->where('phone', $value);
    }

    /**
     * Trình tìm trạng thái SMS
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchResultcodeAttr($query, $value)
    {
        $query->where('resultcode', $value);
    }

    /**
     * uidNgười tìm kiếm
     * @param Model $query
     * @param $value
     */    public function searchUidAttr($query, $value)
    {
        if ($value) {
            $query->where('uid', $value);
        }
    }

    /**
     * ip
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchAddIpAttr($query, $value)
    {
        $query->where('add_ip', $value);
    }

    /**
     * resultcode
     * @param Model $query
     * @param $value
     * @param $data
     */    public function searchTypeAttr($query, $value)
    {
        if ($value !== '') {
            if (is_array($value)) {
                $query->whereIn('resultcode', $value)->when(in_array('134', $value), function ($query) {
                    $query->whereOr('resultcode', NULL);
                });
            } else {
                $query->where('resultcode', $value)->when($value == 134, function ($query) {
                    $query->whereOr('resultcode', NULL);
                });
            }
        }
    }
}
