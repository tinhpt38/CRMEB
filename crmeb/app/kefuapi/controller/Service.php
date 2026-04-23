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

namespace app\kefuapi\controller;

use app\Request;
use think\facade\App;
use app\services\kefu\KefuServices;
use app\services\other\CategoryServices;
use app\kefuapi\validate\SpeechcraftValidate;
use app\services\kefu\service\StoreServiceSpeechcraftServices;

/**
 * Class Service
 * @package app\kefuapi\controller
 */
class Service extends AuthController
{
    /**
     * Service constructor.
     * @param App $app
     * @param KefuServices $services
     */
    public function __construct(App $app, KefuServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Chuyển danh sách dịch vụ khách hàng
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getServiceList(Request $request, $uid = 0)
    {
        $where = $request->getMore([
            ['nickname', ''],
        ]);
        return app('json')->success($this->services->getServiceList($where, [$this->kefuInfo['uid'], $uid]));
    }

    /**
     * Danh sách các câu nói
     * @param Request $request
     * @param StoreServiceSpeechcraftServices $services
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getSpeechcraftList(Request $request, StoreServiceSpeechcraftServices $services)
    {
        $where = $request->getMore([
            ['title', ''],
            ['cate_id', ''],
            ['type', 0]
        ]);
        if ($where['type']) {
            $where['kefu_id'] = $this->kefuId;
        } else {
            $where['kefu_id'] = 0;
        }
        $data = $services->getSpeechcraftList($where);
        return app('json')->success($data['list']);
    }

    /**
     * Thêm danh mục
     * @param Request $request
     * @param CategoryServices $services
     * @return mixed
     */
    public function saveCate(Request $request, CategoryServices $services)
    {
        $data = $request->postMore([
            ['name', ''],
            [['sort', 'd'], 0],
        ]);

        if (!$data['name']) {
            return app('json')->fail('Tên danh mục không được để trống');
        }
        $data['add_time'] = time();
        $data['owner_id'] = $this->kefuId;
        $data['type'] = 1;

        $services->save($data);
        return app('json')->success('Đã thêm thành công');
    }

    /**
     * Sửa đổi phân loại
     * @param Request $request
     * @param CategoryServices $services
     * @param $id
     * @return mixed
     */
    public function editCate(Request $request, CategoryServices $services, $id)
    {
        $data = $request->postMore([
            ['name', ''],
            [['sort', 'd'], 0],
        ]);

        if (!$data['name']) {
            return app('json')->fail('Tên danh mục không được để trống');
        }

        $cateInfo = $services->get($id);
        if (!$cateInfo) {
            return app('json')->fail('Dữ liệu không tồn tại');
        }
        $cateInfo->name = $data['name'];
        $cateInfo->sort = $data['sort'];

        if ($cateInfo->save()) {
            return app('json')->success('Sửa đổi thành công');
        } else {
            return app('json')->fail('Sửa đổi không thành công');
        }
    }

    /**
     * Xóa danh mục
     * @param CategoryServices $services
     * @param $id
     * @return mixed
     */
    public function deleteCate(CategoryServices $services, $id)
    {
        $cateInfo = $services->get($id);
        if (!$cateInfo) {
            return app('json')->fail('Danh mục không tồn tại');
        }

        if ($cateInfo->delete()) {
            return app('json')->success('Xóa thành công');
        } else {
            return app('json')->fail('Xóa không thành công');
        }
    }

    /**
     * Nhận danh mục dịch vụ khách hàng hiện tại
     * @param CategoryServices $services
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getCateList(CategoryServices $services, $type)
    {
        return app('json')->success($services->getCateList(['owner_id' => $type ? $this->kefuId : 0, 'type' => 1], ['id', 'name', 'sort']));
    }

    /**
     * Thêm từ
     * @param Request $request
     * @param StoreServiceSpeechcraftServices $services
     * @return mixed
     */
    public function saveSpeechcraft(Request $request, StoreServiceSpeechcraftServices $services, CategoryServices $categoryServices)
    {
        $data = $request->postMore([
            ['title', ''],
            ['cate_id', 0],
            ['message', ''],
            ['sort', 0]
        ]);

        validate(SpeechcraftValidate::class)->check($data);

        if (!$categoryServices->count(['owner_id' => $this->kefuId, 'type' => 1, 'id' => $data['cate_id']])) {
            return app('json')->fail('Danh mục không tồn tại');
        }
        if ($services->count(['message' => $data['message']])) {
            return app('json')->fail('Đã thêm nội dung trùng lặp');
        }
        $data['add_time'] = time();
        $data['kefu_id'] = $this->kefuId;

        $res = $services->save($data);
        if ($res) {
            return app('json')->success('Đã thêm thành công', null, $res->toArray());
        } else {
            return app('json')->fail('Thêm không thành công');
        }
    }

    /**
     * Sửa đổi lời nói của bạn
     * @param Request $request
     * @param StoreServiceSpeechcraftServices $services
     * @param $id
     * @return mixed
     */
    public function editSpeechcraft(Request $request, StoreServiceSpeechcraftServices $services, CategoryServices $categoryServices, $id)
    {
        $data = $request->postMore([
            ['title', ''],
            ['cate_id', 0],
            ['message', ''],
        ]);

        if (!$data['message']) {
            return app('json')->fail('Nội dung tiêu đề Hoa Thục không được để trống.');
        }
        if (!$categoryServices->count(['owner_id' => $this->kefuId, 'type' => 1, 'id' => $data['cate_id']])) {
            return app('json')->fail('Dữ liệu không tồn tại');
        }
        $speechcraft = $services->get($id);
        if (!$speechcraft) {
            return app('json')->fail('Dữ liệu không tồn tại');
        }
        if (!$speechcraft->kefu_id) {
            return app('json')->fail('Diễn ngôn công khai không thể sửa đổi');
        }
        $speechcraft->title = $data['title'];
        if ($data['cate_id']) {
            $speechcraft->cate_id = $data['cate_id'];
        }
        $speechcraft->message = $data['message'];

        if ($speechcraft->save()) {
            return app('json')->success('Sửa đổi thành công');
        } else {
            return app('json')->fail('Sửa đổi không thành công');
        }
    }

    /**
     * Xóa từ
     * @param StoreServiceSpeechcraftServices $services
     * @param $id
     * @return mixed
     */
    public function deleteSpeechcraft(StoreServiceSpeechcraftServices $services, $id)
    {
        $speechcraft = $services->get($id);
        if (!$speechcraft) {
            return app('json')->fail('Kỹ năng từ không được phát hiện');
        }
        if ($speechcraft->delete()) {
            return app('json')->success('Xóa thành công');
        } else {
            return app('json')->fail('Xóa không thành công');
        }
    }

    /**
     * Lịch sử trò chuyện
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getChatList(Request $request)
    {
        [$uid, $upperId, $is_tourist] = $request->postMore([
            ['uid', 0],
            ['upperId', 0],
            ['is_tourist', 0],
        ], true);
        if (!$uid) {
            return app('json')->fail('Lỗi tham số');
        }
        return app('json')->success($this->services->getChatList($this->kefuInfo['uid'], $uid, (int)$upperId, $is_tourist));
    }

    /**
     * Chi tiết dịch vụ khách hàng hiện tại
     * @return mixed
     */
    public function getServiceInfo()
    {
        $this->kefuInfo['site_name'] = sys_config('site_name');
        return app('json')->success($this->kefuInfo->toArray());
    }

    /**
     * Chuyển dịch vụ khách hàng
     * @return mixed
     */
    public function transfer()
    {
        [$kefuToUid, $uid] = $this->request->postMore([
            ['kefuToUid', 0],
            ['uid', 0]
        ], true);
        if (!$kefuToUid || !$uid) {
            return app('json')->fail('Người chuyển nhượng mất tíchid');
        }
        $this->services->setTransfer($this->kefuInfo['uid'], (int)$uid, (int)$kefuToUid);
        return app('json')->success('Chuyển thành công');
    }
}
