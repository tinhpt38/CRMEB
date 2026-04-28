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

namespace app\services\system\store;


use app\dao\system\store\SystemStoreStaffDao;
use app\services\BaseServices;
use crmeb\exceptions\AdminException;
use crmeb\services\FormBuilder;

/**
 * nhân viên cửa hàng
 * Class SystemStoreStaffServices
 * @package app\services\system\store
 * @mixin SystemStoreStaffDao
 */
class SystemStoreStaffServices extends BaseServices
{
    /**
     * @var FormBuilder
     */
    protected $builder;

    /**
     * Người xây dựng
     * SystemStoreStaffServices constructor.
     * @param SystemStoreStaffDao $dao
     * @param FormBuilder $builder
     */
    public function __construct(SystemStoreStaffDao $dao, FormBuilder $builder)
    {
        $this->dao = $dao;
        $this->builder = $builder;
    }

    /**
     * Xác định xem nhân viên bán hàng có được phép xóa sổ hay không
     * @param $uid
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function verifyStatus($uid)
    {
        return (bool)$this->dao->getOne(['uid' => $uid, 'status' => 1, 'verify_status' => 1]);
    }

    /**
     * Lấy danh sách nhân viên
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getStoreStaffList(array $where)
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getStoreStaffList($where, $page, $limit);
        $count = $this->dao->count($where);
        return compact('list', 'count');
    }

    /**
     * Lấy danh sách cửa hàng trong ô chọn
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getStoreSelectFormData()
    {
        /** @var SystemStoreServices $service */
        $service = app()->make(SystemStoreServices::class);
        $menus = [];
        foreach ($service->getStore() as $menu) {
            $menus[] = ['value' => $menu['id'], 'label' => $menu['name']];
        }
        return $menus;
    }

    /**
     * Nhận mẫu đơn bảo lãnh
     * @param array $formData
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function createStoreStaffForm(array $formData = [])
    {
        if ($formData) {
            $field[] = $this->builder->frameImage('image', 'Thay đổi hình đại diện', $this->url(config('app.admin_prefix', 'admin') . '/widget.images/index', array('fodder' => 'image'),true), $formData['avatar'] ?? '')->icon('el-icon-user')->width('950px')->height('560px')->props(['footer' => false]);
        } else {
            $field[] = $this->builder->frameImage('image', 'Người dùng trung tâm mua sắm', $this->url(config('app.admin_prefix', 'admin') . '/system.User/list', ['fodder' => 'image'], true))->icon('el-icon-user')->width('950px')->height('560px')->Props(['srcKey' => 'image', 'footer' => false]);
        }
        $field[] = $this->builder->hidden('uid', $formData['uid'] ?? 0);
        $field[] = $this->builder->hidden('avatar', $formData['avatar'] ?? '');
        $field[] = $this->builder->select('store_id', 'Điểm đón', ($formData['store_id'] ?? ''))->setOptions($this->getStoreSelectFormData())->filterable(true);
        $field[] = $this->builder->input('staff_name', 'Tên người bảo lãnh', $formData['staff_name'] ?? '')->col(24)->required();
        $field[] = $this->builder->input('phone', 'số điện thoại', $formData['phone'] ?? '')->col(24)->required();
        $field[] = $this->builder->radio('verify_status', 'công tắc xóa', $formData['verify_status'] ?? 1)->options([['value' => 1, 'label' => 'Hoạt động'], ['value' => 0, 'label' => 'đóng cửa']]);
        $field[] = $this->builder->radio('status', 'Trạng thái', $formData['status'] ?? 1)->options([['value' => 1, 'label' => 'Hoạt động'], ['value' => 0, 'label' => 'đóng cửa']]);
        return $field;
    }

    /**
     * Thêm hình thức bảo lãnh
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function createForm()
    {
        return create_form('Thêm người bảo lãnh', $this->createStoreStaffForm(), $this->url('/merchant/store_staff/save/0'));
    }

    /**
     * Chỉnh sửa mẫu đơn bảo lãnh
     * @param int $id
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function updateForm(int $id)
    {
        $storeStaff = $this->dao->get($id);
        if (!$storeStaff) {
            throw new AdminException('Dữ liệu không tồn tại');
        }
        return create_form('Sửa đổi người bảo lãnh', $this->createStoreStaffForm($storeStaff->toArray()), $this->url('/merchant/store_staff/save/' . $id));
    }

}
