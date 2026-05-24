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
namespace app\adminapi\controller\v1\application\wechat;

use app\services\other\QrcodeServices;
use EasyWeChat\Core\Exceptions\HttpException;
use app\adminapi\controller\AuthController;
use app\services\wechat\WechatReplyServices;
use app\services\wechat\WechatKeyServices;
use think\facade\App;

/**
 * Bộ điều khiển quản lý từ khóa
 * Class Reply
 * @package app\admin\controller\wechat
 */class Reply extends AuthController
{
    /**
     * Người xây dựng
     * Menus constructor.
     * @param App $app
     * @param WechatReplyServices $services
     */    public function __construct(App $app, WechatReplyServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Theo dõi câu trả lời
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function reply()
    {
        $where = $this->request->getMore([
            ['key', ''],
        ]);
        if ($where['key'] == '') return app('json')->fail('Lỗi tham số');
        $info = $this->services->getDataByKey($where['key']);
        return app('json')->success(compact('info'));
    }

    /**
     * Danh sách trả lời từ khóa
     * @return mixed
     */    public function index()
    {
        $where = $this->request->getMore([
            ['key', ''],
            ['type', ''],
        ]);
        $where['key_type'] = 0;
        $list = $this->services->getKeyAll($where);
        return app('json')->success($list);
    }

    /**
     * Chi tiết từ khóa
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function read($id)
    {
        $info = $this->services->getKeyInfo($id);
        return app('json')->success(compact('info'));
    }

    /**
     * Lưu từ khóa
     * @param int $id
     * @return mixed
     */    public function save($id = 0)
    {
        $data = $this->request->postMore([
            'key',
            'type',
            ['status', 0],
            ['data', []],
        ]);
        try {
            if (!isset($data['key']) && empty($data['key']))
                return app('json')->fail('Vui lòng nhập từ khóa');
            if (!isset($data['type']) && empty($data['type']))
                return app('json')->fail('Vui lòng chọn kiểu trả lời');
            if (!in_array($data['type'], $this->services->replyType()))
                return app('json')->fail('Loại trả lời sai');

            if (!isset($data['data']) || !is_array($data['data']))
                return app('json')->fail('Thông số tin nhắn trả lời không chính xác');
            $res = $this->services->redact($data['data'], $id, $data['key'], $data['type'], $data['status']);
            if (!$res)
                return app('json')->fail('Lưu không thành công');
            else
                return app('json')->success('Đã lưu thành công', $data);
        } catch (HttpException $e) {
            return app('json')->fail($e->getMessage());
        }
    }

    /**
     * Xóa từ khóa
     * @param $id
     * @return mixed
     */    public function delete($id)
    {
        if (!$this->services->delete($id)) {
            return app('json')->fail('Xóa không thành công');
        } else {
            /** @var WechatKeyServices $keyServices */            $keyServices = app()->make(WechatKeyServices::class);
            $res = $keyServices->delete($id, 'reply_id');
            if (!$res) {
                return app('json')->fail('Xóa không thành công');
            }
        }
        return app('json')->success('Xóa thành công');
    }

    /**
     * Sửa đổi trạng thái
     * @param $id
     * @param $status
     * @return mixed
     */    public function set_status($id, $status)
    {
        if ($status == '' || $id == 0) return app('json')->fail('Lỗi tham số');
        $this->services->update($id, ['status' => $status], 'id');
        return app('json')->success('Thiết lập thành công');
    }

    /**
     * Tạo mã QR trả lời theo dõi
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function code_reply($id)
    {
        if (!$id) {
            return app('json')->fail('Lỗi tham số');
        }
        /** @var QrcodeServices $qrcode */        $qrcode = app()->make(QrcodeServices::class);
        $code = $qrcode->getForeverQrcode('reply', $id);
        if (!$code['ticket']) {
            return app('json')->fail('Tạo mã QR không thành công');
        }
        return app('json')->success($code->toArray());
    }

}
