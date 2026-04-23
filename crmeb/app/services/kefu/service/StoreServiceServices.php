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
namespace app\services\kefu\service;


use app\dao\service\StoreServiceDao;
use app\services\BaseServices;
use app\services\user\UserServices;
use crmeb\exceptions\AdminException;
use crmeb\exceptions\ApiException;
use crmeb\services\FormBuilder;

/**
 * dịch vụ khách hàng
 * Class StoreServiceServices
 * @package app\services\kefu\service
 * @method getStoreServiceOrderNotice() Nhận dịch vụ khách hàng chấp nhận thông báo
 */
class StoreServiceServices extends BaseServices
{

    /**
     * Tạo biểu mẫu
     * @var Form
     */
    protected $builder;

    /**
     * Người xây dựng
     * StoreServiceServices constructor.
     * @param StoreServiceDao $dao
     */
    public function __construct(StoreServiceDao $dao, FormBuilder $builder)
    {
        $this->dao = $dao;
        $this->builder = $builder;
    }

    /**
     * Nhận danh sách dịch vụ khách hàng
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getServiceList(array $where)
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getServiceList($where, $page, $limit);
        foreach ($list as &$item) {
            if (strpos($item['avatar'], '/statics/system_images/') !== false) {
                $item['avatar'] = set_file_url($item['avatar']);
            }
        }
        $this->updateNonExistentService(array_column($list, 'uid'));
        $count = $this->dao->count($where);
        return compact('list', 'count');
    }

    /**
     * @param array $uids
     * @return bool
     */
    public function updateNonExistentService(array $uids = [])
    {
        if (!$uids) {
            return true;
        }
        /** @var UserServices $services */
        $services = app()->make(UserServices::class);
        $userUids = $services->getColumn([['uid', 'in', $uids]], 'uid');
        $unUids = array_diff($uids, $userUids);
        return $this->dao->deleteNonExistentService($unUids);
    }

    /**
     * Tạo biểu mẫu dịch vụ khách hàng
     * @param array $formData
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function createServiceForm(array $formData = [])
    {
        if ($formData) {
            $field[] = $this->builder->frameImage('avatar', 'Hình đại diện dịch vụ khách hàng', $this->url(config('app.admin_prefix', 'admin') . '/widget.images/index', ['fodder' => 'avatar'], true), $formData['avatar'] ?? '')->icon('el-icon-user')->width('950px')->height('560px')->props(['footer' => false]);
        } else {
            $field[] = $this->builder->frameImage('image', 'Chọn người dùng', $this->url(config('app.admin_prefix', 'admin') . '/system.user/list', ['fodder' => 'image'], true))->icon('el-icon-user')->width('950px')->height('560px')->Props(['srcKey' => 'image', 'footer' => false]);
            $field[] = $this->builder->hidden('uid', 0);
            $field[] = $this->builder->hidden('avatar', '');
        }
        $field[] = $this->builder->input('nickname', 'Tên dịch vụ khách hàng', $formData['nickname'] ?? '')->col(24)->required();
        $field[] = $this->builder->input('phone', 'số điện thoại', $formData['phone'] ?? '')->col(24)->required();
        if ($formData) {
            $field[] = $this->builder->input('account', 'Đăng nhập tài khoản', $formData['account'] ?? '')->col(24)->required();
            $field[] = $this->builder->input('password', 'Mật khẩu đăng nhập')->type('password')->col(24)->placeholder('Vui lòng để trống nếu bạn không muốn thay đổi mật khẩu.');
            $field[] = $this->builder->input('true_password', 'Xác nhận mật khẩu')->type('password')->col(24)->placeholder('Vui lòng để trống nếu bạn không muốn thay đổi mật khẩu.');
        } else {
            $field[] = $this->builder->input('account', 'Đăng nhập tài khoản')->col(24)->required();
            $field[] = $this->builder->input('password', 'Mật khẩu đăng nhập')->type('password')->col(24)->required();
            $field[] = $this->builder->input('true_password', 'Xác nhận mật khẩu')->type('password')->col(24)->required();
        }
        $field[] = $this->builder->switches('status', 'Tình trạng dịch vụ khách hàng', (string)($formData['status'] ?? 1))->appendControl('1', [
            $this->builder->switches('customer', 'Quản lý đơn hàng di động：', (string)($formData['customer'] ?? 0)),
            $this->builder->switches('notify', 'Thông báo đặt hàng：', (string)($formData['notify'] ?? 0)),
        ])->activeValue('1')->inactiveValue('0');
        return $field;
    }

    /**
     * Tạo biểu mẫu mua lại dịch vụ khách hàng
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function create()
    {
        return create_form('Thêm dịch vụ khách hàng', $this->createServiceForm(), $this->url('/app/wechat/kefu'), 'POST');
    }

    /**
     * Chỉnh sửa Nhận biểu mẫu
     * @param int $id
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function edit(int $id)
    {
        $serviceInfo = $this->dao->get($id);
        if (!$serviceInfo) {
            throw new AdminException('Dữ liệu không tồn tại');
        }
        return create_form('Chỉnh sửa dịch vụ khách hàng', $this->createServiceForm($serviceInfo->toArray()), $this->url('/app/wechat/kefu/' . $id), 'PUT');
    }

    /**
     * Lấy danh sách người dùng lịch sử trò chuyện của ai đó
     * @param int $uid
     * @return array|array[]
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getChatUser(int $uid)
    {
        /** @var StoreServiceLogServices $serviceLog */
        $serviceLog = app()->make(StoreServiceLogServices::class);
        /** @var UserServices $serviceUser */
        $serviceUser = app()->make(UserServices::class);
        $uids = $serviceLog->getChatUserIds($uid);
        if (!$uids) {
            return [];
        }
        return $serviceUser->getUserList(['uid' => $uids], 'nickname,uid,avatar as headimgurl');
    }

    /**
     * Kiểm tra xem người dùng có phải là nhân viên dịch vụ khách hàng không
     * @param array $where
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function checkoutIsService(array $where)
    {
        return (bool)$this->dao->count($where);
    }

    /**
     * Kiểm tra lịch sử trò chuyện và nhận dịch vụ khách hànguid
     * @param int $uid người dùng hiện tạiuid
     * @param int $uidTo Trang lênid
     * @param int $limit Số hiển thị
     * @param int $toUid dịch vụ khách hànguid
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getRecord(int $uid, int $uidTo, int $limit = 10, int $toUid = 0)
    {
        if (!$toUid) {
            $serviceInfoList = $this->getServiceList(['status' => 1, 'online' => 1]);
            if (!count($serviceInfoList)) {
                throw new ApiException('Hiện tại chưa có nhân viên chăm sóc khách hàng trực tuyến, vui lòng liên hệ sau.');
            }
            $uids = array_column($serviceInfoList['list'], 'uid');
            if (!$uids) {
                throw new ApiException('Hiện tại chưa có nhân viên chăm sóc khách hàng trực tuyến, vui lòng liên hệ sau.');
            }
            /** @var StoreServiceRecordServices $recordServices */
            $recordServices = app()->make(StoreServiceRecordServices::class);
            //Cuộc trò chuyện ưu tiên dịch vụ khách hàng cuối cùng
            $toUid = $recordServices->getLatelyMsgUid(['to_uid' => $uid], 'user_id');
            //Nếu khách hàng mà bạn trò chuyện lần trước không thuộc dịch vụ khách hàng hiện tại, hãy bắt đầu một khách hàng mới
            if (!in_array($toUid, $uids)) {
                $toUid = 0;
            }
            if (!$toUid) {
                $toUid = $uids[array_rand($uids)] ?? 0;
            }
            if (!$toUid) {
                throw new ApiException('Hiện tại chưa có nhân viên chăm sóc khách hàng trực tuyến, vui lòng liên hệ sau.');
            }
        }
        $userInfo = $this->dao->get(['uid' => $toUid], ['nickname', 'avatar']);
        if (!$userInfo) {
            /** @var UserServices $userServices */
            $userServices = app()->make(UserServices::class);
            $userInfo = $userServices->get(['uid' => $toUid], ['nickname', 'avatar']);
            if (!$userInfo) {
                $userInfo['nickname'] = '';
                $userInfo['avatar'] = '';
            }
        }
        if ($userInfo['avatar']) $userInfo['avatar'] = set_file_url($userInfo['avatar']);
        /** @var StoreServiceLogServices $logServices */
        $logServices = app()->make(StoreServiceLogServices::class);
        $result = ['serviceList' => [], 'uid' => $toUid, 'nickname' => $userInfo['nickname'], 'avatar' => $userInfo['avatar']];
        $serviceLogList = $logServices->getServiceChatList(['chat' => [$uid, $toUid], 'is_tourist' => 0], $limit, $uidTo);
        $result['serviceList'] = array_reverse($logServices->tidyChat($serviceLogList));
        return $result;
    }
}
