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
namespace app\adminapi\controller\v1\notification\sms;

use app\adminapi\controller\AuthController;
use app\services\serve\ServeServices;
use think\facade\App;

/**
 * Ứng dụng mẫu SMS
 * Class SmsTemplateApply
 * @package app\admin\controller\sms
 */
class SmsTemplateApply extends AuthController
{
    /**
     * @param App $app
     * @param ServeServices $services
     */
    public function __construct(App $app, ServeServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Nhận danh sách mẫu không đồng bộ
     * @return mixed
     */
    public function index()
    {
        $where = $this->request->getMore([
            [['type', 'd'], 0],
            ['status', ''],
            ['title', ''],
            [['page', 'd'], 1],
            [['limit', 'd'], 20],
        ]);
        $where['temp_type'] = $where['type'];
        $templateList = $this->services->sms()->temps($where['page'], $where['limit'], $where['type']);
        $templateList['data'] = $templateList['data'] ?? [];
        foreach ($templateList['data'] as $key => &$item) {
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
        return app('json')->success($templateList);
    }

    /**
     * Hiển thị trang biểu mẫu tạo tài nguyên
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function create()
    {
        return app('json')->success($this->services->getSmsTemplateForm());
    }

    /**
     * Lưu tài nguyên mới
     * @return mixed
     */
    public function save()
    {
        $data = $this->request->postMore([
            ['title', ''],
            ['content', ''],
            ['type', 0]
        ]);
        if (!strlen(trim($data['title']))) {
            return app('json')->fail('Vui lòng nhập tên mẫu');
        }
        if (!strlen(trim($data['content']))) {
            return app('json')->fail('Vui lòng nhập nội dung mẫu');
        }
        $this->services->sms()->apply($data['title'], $data['content'], $data['type']);
        return app('json')->success('Ứng dụng thành công');
    }
}
