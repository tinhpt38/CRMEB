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

namespace app\adminapi\controller\v1\kefu;

use app\Request;
use think\facade\App;
use app\adminapi\controller\AuthController;
use app\services\kefu\service\StoreServiceSpeechcraftServices;
use app\adminapi\validate\service\StoreServiceSpeechcraftValidata;

/**
 * Bộ điều khiển trống từ vựng
 * Class StoreServiceSpeechcraft
 * @package app\adminapi\controller\v1\application\wechat
 */class StoreServiceSpeechcraft extends AuthController
{
    /**
     * StoreServiceSpeechcraft constructor.
     * @param App $app
     * @param StoreServiceSpeechcraftServices $services
     */    public function __construct(App $app, StoreServiceSpeechcraftServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Hiển thị danh sách tài nguyên
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function index(Request $request)
    {
        $where = $request->getMore([
            ['title', ''],
            ['message', ''],
            [['cate_id', 'd'], ''],
        ]);
        $where['kefu_id'] = 0;
        return app('json')->success($this->services->getSpeechcraftList($where));
    }

    /**
     * Hiển thị trang biểu mẫu tạo tài nguyên
     * @return mixed
     */    public function create()
    {
        return app('json')->success($this->services->createForm());
    }

    /**
     * Lưu tài nguyên mới
     * @param Request $request
     * @return \think\Response
     */    public function save(Request $request)
    {
        $data = $request->postMore([
            ['title', ''],
            ['message', ''],
            [['cate_id', 'd'], 0],
            ['sort', 0],
        ]);

        $this->validate($data, StoreServiceSpeechcraftValidata::class);
        $data['add_time'] = time();
        $data['kefu_id'] = 0;
        if ($this->services->count(['message' => $data['message']])) {
            return app('json')->fail('Từ không thể được thêm nhiều lần');
        }
        if ($this->services->save($data)) {
            return app('json')->success('Tạo từ thành công');
        } else {
            return app('json')->fail('Không tạo được cụm từ');
        }
    }

    /**
     * Hiển thị tài nguyên được chỉ định
     * @param int $id
     * @return \think\Response
     */    public function read($id)
    {
        $info = $this->services->get($id);
        if (!$info) {
            return app('json')->fail('Dữ liệu không tồn tại');
        }
        return app('json')->success($info);
    }

    /**
     * Hiển thị trang biểu mẫu tài nguyên chỉnh sửa
     * @param $id
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function edit($id)
    {
        return app('json')->success($this->services->updateForm((int)$id));
    }

    /**
     * Lưu tài nguyên cập nhật
     * @param Request $request
     * @param int $id
     * @return \think\Response
     */    public function update(Request $request, $id)
    {
        $data = $request->postMore([
            ['title', ''],
            ['message', ''],
            ['sort', 0],
            [['cate_id', 'd'], 0],
        ]);

        $this->validate($data, StoreServiceSpeechcraftValidata::class);
        $message = $this->services->get(['message' => $data['message']]);
        if ($message && $message['id'] != $id) {
            return app('json')->fail('Từ không thể được thêm nhiều lần');
        }
        if ($this->services->update($id, $data)) {
            return app('json')->success('Sửa đổi thành công');
        } else {
            return app('json')->fail('Sửa đổi không thành công');
        }

    }

    /**
     * Xóa tài nguyên được chỉ định
     * @param int $id
     * @return \think\Response
     */    public function delete($id)
    {
        if (!$id || !($info = $this->services->get($id))) {
            return app('json')->fail('Các từ đã xóa không tồn tại');
        }
        if ($info->delete()) {
            return app('json')->success('Xóa thành công');
        } else {
            return app('json')->fail('Xóa không thành công');
        }
    }
}
