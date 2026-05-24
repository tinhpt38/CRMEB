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
namespace app\api\controller\v1\user;

use app\Request;
use app\services\message\MessageSystemServices;


/**
 * Lớp địa chỉ Khách hàng
 * Class UserController
 * @package app\api\controller\store
 */class MessageSystemController
{
    protected $services = NUll;

    /**
     * MessageSystemController constructor.
     * @param MessageSystemServices $services
     */    public function __construct(MessageSystemServices $services)
    {
        $this->services = $services;
    }

    /**
     * Danh sách tin nhắn trang web
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function message_list(Request $request)
    {
        $uid = (int)$request->uid();
        return app('json')->success($this->services->getMessageSystemList($uid));
    }

    /**
     * Chi tiết tin nhắn trang web
     * @param Request $request
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function detail(Request $request, $id)
    {
        if (!$id) {
            app('json')->fail('Lỗi tham số');
        }
        $uid = (int)$request->uid();
        $where['uid'] = $uid;
        $where['id'] = $id;
        return app('json')->success($this->services->getInfo($where));
    }

    /**
     * Chỉnh sửa/sửa đổi trường danh sách tin nhắn
     * @param Request $request
     * @return mixed
     */    public function edit_message(Request $request)
    {
        $data = $request->getMore([
            ['id', 0],
            ['key', ''],
            ['value', ''],
            ['all', 0]
        ]);
        $all = (int)$data['all'];
        if ($all === 1) {
            $this->services->update(['uid' => $request->uid()], [$data['key'] => $data['value']]);
        } else {
            $this->services->update($data['id'], [$data['key'] => $data['value']]);
        }
        return app('json')->success('Thiết lập thành công');
    }
}
