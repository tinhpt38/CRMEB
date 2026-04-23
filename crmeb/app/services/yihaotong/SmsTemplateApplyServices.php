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


use app\services\BaseServices;
use crmeb\services\FormBuilder;

/**
 * mẫu tin nhắn
 * Class SmsTemplateApplyServices
 * @package app\services\message\sms
 */
class SmsTemplateApplyServices extends BaseServices
{
    /**
     * @var FormBuilder
     */
    protected $builder;

    /**
     * SmsTemplateApplyServices constructor.
     * @param FormBuilder $builder
     */
    public function __construct(FormBuilder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * Tạo mẫu tin nhắn SMS
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function createSmsTemplateForm()
    {
        $field = [
            $this->builder->input('title', 'Tên mẫu')->placeholder('Tên mẫu,Ví dụ: thanh toán đơn hàng thành công'),
            $this->builder->input('content', 'Nội dung mẫu')->type('textarea')->placeholder('Nội dung mẫu như: hàng bạn mua đã được thanh toán thành công, số tiền thanh toán{$pay_price}nhân dân tệ, số đơn hàng{$order_id},Cảm ơn bạn đã ghé thăm! (Lưu ý: Không thêm chữ ký SMS vào nội dung mẫu.）'),
            $this->builder->radio('type', 'loại mẫu', 1)->options([['label' => 'Mã xác minh', 'value' => 1], ['label' => 'thông báo', 'value' => 2], ['label' => 'khuyến mãi', 'value' => 3]])
        ];
        return $field;
    }

    /**
     * Nhận mẫu ứng dụng SMS
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function getSmsTemplateForm()
    {
        return create_form('Đăng ký mẫu SMS', $this->createSmsTemplateForm(), $this->url('/notify/sms/temp'), 'POST');
    }

}
