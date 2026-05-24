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

namespace app\api\controller\v2\user;


use app\services\user\UserInvoiceServices;
use think\Request;

/**
 * Class UserInvoiceController
 * @package app\api\controller\v2\user
 */class UserInvoiceController
{
    /**
     * @var UserInvoiceServices
     */    protected $services;

    /**
     * UserInvoiceController constructor.
     * @param UserInvoiceServices $services
     */    public function __construct(UserInvoiceServices $services)
    {
        $this->services = $services;
    }

    /**
     * Nhận thông tin hóa đơn riêng lẻ
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function invoice($id)
    {
        if (!$id) {
            return app('json')->fail('Lỗi tham số');
        }
        return app('json')->success($this->services->getInvoice((int)$id));
    }

    /**
     * Danh sách hóa đơn
     * @param Request $request
     * @return mixed
     */    public function invoiceList(Request $request)
    {
        $data = $request->postMore([
            ['header_type', ''],
            ['type', '']
        ]);
        $uid = (int)$request->uid();
        return app('json')->success($this->services->getUserList($uid, $data));
    }

    /**
     * Đặt hóa đơn mặc định
     * @param Request $request
     * @return mixed
     */    public function setDefaultInvoice(Request $request)
    {
        list($id) = $request->getMore([['id', 0]], true);
        if (!$id || !is_numeric($id)) return app('json')->fail('Lỗi tham số');
        $uid = (int)$request->uid();
        $this->services->setDefaultInvoice($uid, (int)$id);
        return app('json')->success('Thiết lập thành công');
    }

    /**
     * Nhận hóa đơn mặc định
     * @param Request $request
     * @return mixed
     */    public function getDefaultInvoice(Request $request)
    {
        [$type] = $request->postMore(['type', 1], true);
        $uid = (int)$request->uid();
        $defaultInvoice = $this->services->getUserDefaultInvoice($uid, (int)$type);
        if ($defaultInvoice) {
            $defaultInvoice = $defaultInvoice->toArray();
            return app('json')->success($defaultInvoice);
        }
        return app('json')->success([]);
    }

    /**
     * Sửa đổi Thêm hóa đơn
     * @param Request $request
     * @return mixed
     */    public function saveInvoice(Request $request)
    {
        $data = $request->postMore([
            [['id', 'd'], 0],
            [['header_type', 'd'], 1],
            [['type', 'd'], 1],
            ['drawer_phone', ''],
            ['email', ''],
            ['name', ''],
            ['duty_number', ''],
            ['tell', ''],
            ['address', ''],
            ['bank', ''],
            ['card_number', ''],
            ['is_default', 0]
        ]);
        if (!$data['drawer_phone']) return app('json')->fail('Vui lòng điền số điện thoại di động lập hóa đơn');
        if (!check_phone($data['drawer_phone'])) return app('json')->fail('Định dạng số điện thoại di động không chính xác');
        if (!$data['name']) return app('json')->fail('Vui lòng điền tiêu đề hóa đơn (tên công ty phát hành hóa đơn)）');
        if (!in_array($data['header_type'], [1, 2])) {
            $data['header_type'] = empty($data['duty_number']) ? 1 : 2;
        }
        if ($data['header_type'] == 1 && !preg_match('/^[\x80-\xff]{2,60}$/', $data['name'])) {
            return app('json')->fail('Vui lòng điền đúng tiêu đề hóa đơn (tên công ty phát hành hóa đơn)）');
        }
        if ($data['header_type'] == 2 && !preg_match('/^[0-9a-zA-Z&\(\)\（\）\x80-\xff]{2,150}$/', $data['name'])) {
            return app('json')->fail('Vui lòng điền đúng tiêu đề hóa đơn (tên công ty phát hành hóa đơn)）');
        }
        if ($data['header_type'] == 2 && !$data['duty_number']) {
            return app('json')->fail('Vui lòng điền mã số thuế trên hóa đơn');
        }
        if ($data['header_type'] == 2 && !preg_match('/^[A-Z0-9]{15}$|^[A-Z0-9]{17}$|^[A-Z0-9]{18}$|^[A-Z0-9]{20}$/', $data['duty_number'])) {
            return app('json')->fail('Vui lòng điền đúng mã số thuế trên hóa đơn');
        }
        if ($data['card_number'] && !preg_match('/^[1-9]\d{11,19}$/', $data['card_number'])) {
            return app('json')->fail('Vui lòng điền đúng số thẻ ngân hàng');
        }
        $uid = (int)$request->uid();
        $re = $this->services->saveInvoice($uid, $data);
        if ($re) {
            if ($re['type'] == 'edit') {
                return app('json')->success('Sửa đổi thành công');
            } else {
                return app('json')->success('Đã thêm thành công', $re['data']);
            }
        } else {
            return app('json')->fail('Thao tác không thành công');
        }

    }

    /**
     * Xóa hóa đơn
     * @param Request $request
     * @return mixed
     */    public function delInvoice(Request $request)
    {
        [$id] = $request->postMore([['id', 0]], true);
        if (!$id || !is_numeric($id)) return app('json')->fail('Lỗi tham số');
        $uid = (int)$request->uid();
        $re = $this->services->delInvoice($uid, (int)$id);
        if ($re)
            return app('json')->success('Xóa thành công');
        else
            return app('json')->fail('Xóa không thành công');
    }
}
