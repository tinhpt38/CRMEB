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

namespace app\adminapi\controller\v1\finance;

use app\adminapi\controller\AuthController;
use app\services\user\UserExtractServices;
use think\facade\App;
use think\Request;

/**
 * Class UserExtract
 * @package app\adminapi\controller\v1\finance
 */
class UserExtract extends AuthController
{
    /**
     * UserExtract constructor.
     * @param App $app
     * @param UserExtractServices $services
     */
    public function __construct(App $app, UserExtractServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Hiển thị danh sách tài nguyên
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index()
    {
        $where = $this->request->getMore([
            ['status', ''],
            ['extract_type', ''],
            ['nireid', '', '', 'like'],
            ['data', '', '', 'time'],
        ]);
        if (isset($where['extract_type']) && $where['extract_type'] == 'wx') {
            $where['extract_type'] = 'weixin';
        }
        return app('json')->success($this->services->index($where));
    }

    /**
     * Hiển thị trang biểu mẫu tài nguyên chỉnh sửa
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {
        if (!$id) return app('json')->fail('Dữ liệu không tồn tại');
        return app('json')->success($this->services->edit((int)$id));
    }

    /**
     * Lưu tài nguyên cập nhật
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');
        $id = (int)$id;
        $UserExtract = $this->services->getExtract($id);
        if (!$UserExtract) app('json')->fail('Dữ liệu không tồn tại');
        if ($UserExtract['extract_type'] == 'alipay') {
            $data = $this->request->postMore([
                'real_name',
                'mark',
                'extract_price',
                'alipay_code',
            ]);
            if (!$data['real_name']) return app('json')->fail('Vui lòng nhập tên');
            if ($data['extract_price'] <= -1) return app('json')->fail('Vui lòng nhập số tiền rút');
            if (!$data['alipay_code']) return app('json')->fail('Vui lòng nhập số tài khoản Alipay của bạn');
        } else if ($UserExtract['extract_type'] == 'weixin') {
            $data = $this->request->postMore([
                'real_name',
                'mark',
                'extract_price',
                'wechat',
            ]);
            if ($data['extract_price'] <= -1) return app('json')->fail('Vui lòng nhập số tiền rút');
            if (!$data['wechat']) return app('json')->fail('Vui lòng nhập tài khoản WeChat của bạn');
        } else {
            $data = $this->request->postMore([
                'real_name',
                'extract_price',
                'mark',
                'bank_code',
                'bank_address',
            ]);
            if (!$data['real_name']) return app('json')->fail('Vui lòng nhập tên');
            if ($data['extract_price'] <= -1) return app('json')->fail('Vui lòng nhập số tiền rút');
            if (!$data['bank_code']) return app('json')->fail('Vui lòng nhập số thẻ ngân hàng');
            if (!$data['bank_address']) return app('json')->fail('Vui lòng nhập ngân hàng mở tài khoản');
        }
        return app('json')->success($this->services->update($id, $data) ? 'Sửa đổi thành công' : 'Sửa đổi không thành công');
    }

    /**
     * từ chối
     * @param $id
     * @return mixed
     */
    public function refuse($id)
    {
        if (!$id) app('json')->fail('Lỗi tham số');
        $data = $this->request->postMore([
            ['message', '']
        ]);
        if ($data['message'] == '') return app('json')->fail('Lý do từ chối không được để trống');
        return app('json')->success($this->services->refuse((int)$id, $data['message']) ? 'Thiết lập thành công' : 'Thiết lập không thành công');
    }

    /**
     * vượt qua
     * @param $id
     * @return mixed
     */
    public function adopt($id)
    {
        if (!$id) app('json')->fail('Lỗi tham số');
        $res = $this->services->adopt((int)$id);
        if ($res) {
            if ($res === 'v3_extract') {
                return app('json')->success('Rút tiền thành công, chờ người dùng xác nhận thanh toán');
            } else {
                return app('json')->success('Rút tiền thành công');
            }
        } else {
            return app('json')->success('Thao tác không thành công');
        }
    }
}
