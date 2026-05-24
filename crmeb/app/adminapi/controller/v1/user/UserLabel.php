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
namespace app\adminapi\controller\v1\user;

use app\adminapi\controller\AuthController;
use app\services\user\UserLabelCateServices;
use app\services\user\UserLabelRelationServices;
use app\services\user\UserLabelServices;
use think\facade\App;

/**
 * Thẻ khách hàngBộ điều khiển
 * Class UserLabel
 * @package app\adminapi\controller\v1\user
 */class UserLabel extends AuthController
{

    /**
     * UserLabel constructor.
     * @param App $app
     * @param UserLabelServices $service
     */    public function __construct(App $app, UserLabelServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * danh sách thẻ
     * @param int $label_cate
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function index($label_cate = '')
    {
        return app('json')->success($this->services->getList(['label_cate' => $label_cate]));
    }

    /**
     * Thêm biểu mẫu chỉnh sửa nhãn
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     */    public function add()
    {
        [$id, $cateId] = $this->request->getMore([
            ['id', 0],
            ['cate_id', 0],
        ], true);
        return app('json')->success($this->services->add((int)$id, (int)$cateId));
    }

    /**
     * Lưu dữ liệu biểu mẫu nhãn
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function save()
    {
        $data = $this->request->postMore([
            ['id', 0],
            ['label_cate', 0],
            ['label_name', ''],
        ]);
        if (!$data['label_name'] = trim($data['label_name'])) return app('json')->fail('Thẻ thành viên không được để trống');
        $this->services->save((int)$data['id'], $data);
        return app('json')->success('Đã lưu thành công');
    }

    /**
     * Xóa
     * @param $id
     * @throws \Exception
     */    public function delete()
    {
        list($id) = $this->request->getMore([
            ['id', 0],
        ], true);
        if (!$id) return app('json')->fail('Lỗi tham số');
        $this->services->delLabel((int)$id);
        return app('json')->success('Xóa thành công');
    }

    /**
     * Phân loại thẻ
     * @param UserLabelCateServices $services
     * @return mixed
     */    public function getUserLabel(UserLabelCateServices $services, $uid)
    {
        return app('json')->success($services->getUserLabel((int)$uid));
    }

    /**
     * Đặt nhãn Khách hàng
     * @param UserLabelRelationServices $services
     * @param $uid
     * @return mixed
     */    public function setUserLabel(UserLabelRelationServices $services, $uid)
    {
        [$labels, $unLabelIds] = $this->request->postMore([
            ['label_ids', []],
            ['un_label_ids', []]
        ], true);
        if (!count($labels) && !count($unLabelIds)) {
            return app('json')->fail('Lỗi tham số');
        }
        if ($services->setUserLabel($uid, $labels) && $services->unUserLabel($uid, $unLabelIds)) {
            return app('json')->success('Thiết lập thành công');
        } else {
            return app('json')->fail('Thiết lập không thành công');
        }
    }

    /**
     * Nhận danh sách thẻ Khách hàng được phân loại
     * @param \app\services\user\label\UserLabelCateServices $userLabelCateServices
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function tree_list(UserLabelCateServices $userLabelCateServices)
    {
        $cate = $userLabelCateServices->getLabelCateAll();
        $data = [];
        $label = [];
        if ($cate) {
            foreach ($cate as $value) {
                $data[] = [
                    'id' => $value['id'] ?? 0,
                    'value' => $value['id'] ?? 0,
                    'label_cate' => 0,
                    'label_name' => $value['name'] ?? '',
                    'label' => $value['name'] ?? '',
                    'store_id' => $value['store_id'] ?? 0,
                    'type' => $value['type'] ?? 1,
                ];
            }
            $label = $this->services->getList(['type' => 1]);
            $label = $label['list'] ?? [];
            if ($label) {
                foreach ($label as &$item) {
                    $item['label'] = $item['label_name'];
                    $item['value'] = $item['id'];
                }
            }
        }
        return app('json')->success($this->services->get_tree_children($data, $label));
    }
}
