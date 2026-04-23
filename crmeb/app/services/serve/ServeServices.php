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

namespace app\services\serve;


use app\services\BaseServices;
use crmeb\services\copyproduct\CopyProduct;
use crmeb\services\express\Express;
use crmeb\services\FormBuilder;
use crmeb\services\invoice\Invoice;
use crmeb\services\printer\Printer;
use crmeb\services\serve\Serve;
use crmeb\services\sms\Sms;
use think\facade\Config;

/**
 * Lối vào dịch vụ nền tảng
 * Class ServeServices
 * @package crmeb\services
 */
class ServeServices extends BaseServices
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
     * Nhận cấu hình
     * @param array $config
     * @return array
     */
    public function getConfig(array $config = [])
    {
        return array_merge([
            'account' => sys_config('sms_account'),
            'secret' => sys_config('sms_token')
        ], $config);
    }


    /**
     * Nhận cấu hình gửi SMS dựa trên loại
     * @param $type
     * @param array $configDefault
     * @return array
     */
    protected function getTypeConfig($type, array $configDefault = [])
    {
        if (!$type) {
            $type = Config::get('sms.default', '');
        }
        $config = Config::get('sms.stores.' . $type);
        foreach ($config as $key => &$item) {
            if (empty($item)) {
                $item = sys_config($key);
            }
        }
        if ($configDefault) {
            $config = array_merge($config, $configDefault);
        }
        return $config;
    }

    /**
     * Tin nhắn ngắn
     * @param string|null $type
     * @param array $config
     * @return Sms
     */
    public function sms(string $type = null, array $config = [])
    {
        return app()->make(Sms::class, [$type, $this->getTypeConfig($type, $config)]);
    }

    /**
     * Sao chép sản phẩm
     * @param string|null $type
     * @param array $config
     * @return CopyProduct
     */
    public function copy(string $type = null, array $config = [])
    {
        return app()->make(CopyProduct::class, [$type, $this->getConfig($config)]);
    }

    /**
     * Mẫu điện tử
     * @param array $config
     * @return Express
     */
    public function express(array $config = [])
    {
        return app()->make(Express::class, [$this->getConfig($config)]);
    }

    /**
     * In biên lai
     * @param array $config
     * @return Express
     */
    public function orderPrint(array $config = [])
    {
        return app()->make(Printer::class, [$this->getConfig($config)]);
    }

    /**
     * người dùng
     * @param array $config
     * @return Serve
     */
    public function user(array $config = [])
    {
        return app()->make(Serve::class, [$this->getConfig($config)]);
    }

    /**
     * Hóa đơn điện tử
     * @param array $config
     * @return Serve
     */
    public function invoice(array $config = [])
    {
        return app()->make(Invoice::class, [$this->getConfig($config)]);
    }

    /**
     * Nhận mẫu SMS
     * @param int $page
     * @param int $limit
     * @param int $type
     * @return array
     */
    public function getSmsTempsList(int $page, int $limit, int $type)
    {
        $list = $this->sms()->temps($page, $limit, $type);
        foreach ($list['data'] as &$item) {
            $item['templateid'] = $item['temp_id'];
            switch ((int)$item['temp_type']) {
                case 1:
                    $item['type'] = 'Mã xác minh';
                    break;
                case 2:
                    $item['type'] = 'thông báo';
                    break;
                case 30:
                    $item['type'] = 'SMS tiếp thị';
                    break;
            }
        }
        return $list;
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
            $this->builder->radio('type', 'loại mẫu', 1)->options([['label' => 'Mã xác minh', 'value' => 1], ['label' => 'thông báo', 'value' => 2], ['label' => 'tiếp thị', 'value' => 3]])
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
