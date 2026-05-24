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
namespace app\services\agent;

use app\services\BaseServices;
use app\services\order\StoreOrderServices;
use app\services\other\QrcodeServices;
use app\services\system\admin\SystemAdminServices;
use app\services\system\admin\SystemRoleServices;
use app\services\user\UserServices;
use crmeb\exceptions\AdminException;
use crmeb\exceptions\ApiException;
use crmeb\services\FormBuilder as Form;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\Route;

class DivisionServices extends BaseServices
{
    /**
     * Lấy danh sách bộ phận/đại lý/nhân viên
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getDivisionList(array $where = [])
    {
        /** @var UserServices $userServices */        $userServices = app()->make(UserServices::class);
        $data = $userServices->getDivisionList($where + ['status' => 1], 'uid,nickname,avatar,division_name,division_percent,division_end_time,division_status,division_invite');
        foreach ($data['list'] as &$item) {
            $item['division_end_time'] = date('Y-m-d', $item['division_end_time']);
            $item['agent_count'] = $userServices->count([
                $where['division_type'] == 1 ? 'division_id' : 'agent_id' => $item['uid'],
                'division_type' => $where['division_type'] + 1,
                'status' => 1,
                'is_del' => 0
            ]);
            unset($item['label']);
        }
        return $data;
    }

    /**
     * Danh sách cấp dưới
     * @param $type
     * @param $uid
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function divisionDownList($type, $uid)
    {
        /** @var UserServices $userServices */        $userServices = app()->make(UserServices::class);
        $where = [
            $type == 2 ? 'division_id' : 'agent_id' => $uid,
            'division_type' => $type
        ];
        $where['status'] = 1;
        $where['is_del'] = 0;
        $data = $userServices->getDivisionList($where, 'uid,nickname,avatar,division_name,division_percent,division_end_time,division_status');
        foreach ($data['list'] as &$item) {
            $item['agent_count'] = $userServices->count([
                'agent_id' => $item['uid'],
                'division_type' => $type + 1,
                'status' => 1
            ]);
            unset($item['label']);
        }
        return $data;
    }

    /**
     * Thêm chỉnh sửa biểu mẫu chia
     * @param $uid
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     */    public function getDivisionForm($uid)
    {
        /** @var UserServices $userServices */        $userServices = app()->make(UserServices::class);
        /** @var SystemAdminServices $adminService */        $adminService = app()->make(SystemAdminServices::class);
        $userInfo = $userServices->getUserInfo($uid);
        if ($uid && !$userInfo) throw new AdminException('Lỗi tham số');
        if ($uid) {
            $adminInfo = $adminService->getInfo(['division_id' => $uid])->toArray();
            if (isset($adminInfo['roles'])) {
                foreach ($adminInfo['roles'] as &$item) {
                    $item = intval($item);
                }
            }
        }
        $field = [];
        $title = 'Đơn vị kinh doanh';
        $field[] = Form::input('division_name', 'Tên Đơn vị kinh doanh', $userInfo['division_name'] ?? '')->required('Vui lòng nhập tên phòng kinh doanh');
        if ($uid) {
            $field[] = Form::hidden('uid', $uid);
        } else {
            $field[] = Form::frameImage('image', 'Người dùng được liên kết', $this->url(config('app.admin_prefix', 'admin') . '/system.user/list', ['fodder' => 'image'], true))->icon('el-icon-user')->width('950px')->height('560px')->Props(['srcKey' => 'image', 'footer' => false]);
        }
        $field[] = Form::hidden('aid', $adminInfo['id'] ?? 0);
        $field[] = Form::number('division_percent', 'Tỷ lệ hoa hồng', $userInfo['division_percent'] ?? '')->placeholder('Tỷ lệ hoa hồng đại lý khu vực1-100')->info('Điền từ 1-100, nếu điền 50 tức là giảm giá50%')->style(['width' => '173px'])->min(0)->max(100)->required();
        $field[] = Form::date('division_end_time', 'Thời gian hết hạn', ($userInfo['division_end_time'] ?? '') != 0 ? date('Y-m-d H:i:s', $userInfo['division_end_time']) : '')->placeholder('Thời gian hết hạn đại lý khu vực');
        $field[] = Form::radio('division_status', 'trạng thái đại lý', $userInfo['division_status'] ?? 1)->options([['label' => 'Mở', 'value' => 1], ['label' => 'đóng cửa', 'value' => 0]]);
        $field[] = Form::input('account', 'Quản lý tài khoản', $adminInfo['account'] ?? '')->required('Vui lòng điền vào tài khoản quản trị viên');
        $field[] = Form::input('pwd', 'Mật khẩu quản trị viên')->type('password')->placeholder('Vui lòng điền mật khẩu quản trị viên');
        $field[] = Form::input('conf_pwd', 'Xác nhận mật khẩu')->type('password')->placeholder('Vui lòng nhập mật khẩu xác nhận');
        /** @var SystemRoleServices $service */        $service = app()->make(SystemRoleServices::class);
        $options = $service->getRoleFormSelect(1);
        $field[] = Form::select('roles', 'Trạng thái quản trị viên', $adminInfo['roles'] ?? [])->setOptions(Form::setOptions($options))->multiple(true)->required('Vui lòng chọn danh tính quản trị viên');
        return create_form($title, $field, Route::buildUrl('/agent/division/save'), 'POST');
    }

    /**
     * Lưu dữ liệu Đơn vị kinh doanh
     * @param $data
     * @return mixed
     */    public function divisionSave($data)
    {
        if ((int)$data['uid'] == 0) $data['uid'] = $data['image']['uid'];
        if ((int)$data['uid'] == 0) throw new AdminException('Vui lòng điền thông tin Khách hàngUID');
        /** @var UserServices $userServices */        $userServices = app()->make(UserServices::class);
        if ($data['aid'] == 0) {
            $userInfo = $userServices->getUserInfo($data['uid'], 'is_division,is_agent,is_staff');
            if (!$userInfo) throw new AdminException('Người dùng không tồn tại');
            if ($userInfo['is_division']) throw new AdminException('Người dùng này là bộ phận kinh doanh, vui lòng không thêm nhiều lần');
            if ($userInfo['is_agent']) throw new AdminException('Người dùng này là đại lý và không thể được thêm làm Đơn vị kinh doanh');
            if ($userInfo['is_staff']) throw new AdminException('Người dùng này là nhân viên cấp dưới và không thể được thêm làm bộ phận kinh doanh');
        }
        $uid = $data['uid'];
        $aid = $data['aid'];
        $agentData = [
            'division_percent' => $data['division_percent'],
            'division_end_time' => strtotime($data['division_end_time']),
            'division_change_time' => time(),
            'is_division' => 1,
            'is_agent' => 0,
            'is_staff' => 0,
            'division_id' => $uid,
            'agent_id' => 0,
            'staff_id' => 0,
            'division_type' => 1,
            'division_status' => $data['division_status'],
            'spread_uid' => 0,
            'spread_time' => 0,
            'division_name' => $data['division_name'],
            'is_promoter' => 1
        ];
        $adminData = [
            'account' => $data['account'],
            'pwd' => $data['pwd'],
            'conf_pwd' => $data['conf_pwd'],
            'real_name' => $data['division_name'],
            'roles' => $data['roles'],
            'status' => 1,
            'level' => 1,
            'division_id' => $uid
        ];
        return $this->transaction(function () use ($uid, $agentData, $adminData, $aid, $userServices) {
            $agentData['division_invite'] = $userServices->value(['uid' => $uid], 'division_invite') ?: rand(10000000, 99999999);
            $userServices->update($uid, $agentData);

            /** @var SystemAdminServices $adminService */            $adminService = app()->make(SystemAdminServices::class);
            if (!$aid) {
                if ($adminData['pwd']) {
                    if (!$adminData['conf_pwd']) throw new AdminException('Vui lòng nhập mật khẩu xác nhận');
                    if ($adminData['pwd'] != $adminData['conf_pwd']) throw new AdminException('Mật khẩu nhập hai lần không nhất quán');
                    $adminService->create($adminData);
                } else {
                    throw new AdminException('Vui lòng nhập mật khẩu xác nhận');
                }
            } else {
                $adminInfo = $adminService->get($aid);
                if (!$adminInfo)
                    throw new AdminException('Không tìm thấy thông tin quản trị viên');
                if ($adminInfo->is_del) {
                    throw new AdminException('Quản trị viên đã xóa');
                }
                if (!$adminData['real_name'])
                    throw new AdminException('Tên quản trị viên không được để trống');
                if ($adminData['pwd']) {
                    if (!$adminData['conf_pwd']) throw new AdminException('Vui lòng nhập mật khẩu xác nhận');
                    if ($adminData['pwd'] != $adminData['conf_pwd']) throw new AdminException('Mật khẩu nhập hai lần không nhất quán');
                    $adminInfo->pwd = $this->passwordHash($adminData['pwd']);
                }
                $adminInfo->real_name = $adminData['real_name'];
                $adminInfo->account = $adminData['account'];
                $adminInfo->roles = implode(',', $adminData['roles']);
                if ($adminInfo->save())
                    return true;
                else
                    return false;
            }
            return true;
        });
    }

//    /**
//     * Tạo mã mời
//     * @return false|string
//     */
//    public function getDivisionInvite()
//    {
//        /** @var UserServices $userServices */
//        $userServices = app()->make(UserServices::class);
//        list($msec, $sec) = explode(' ', microtime());
//        $num = time() + mt_rand(10, 999999) . '' . substr($msec, 2, 3);//Tạo số ngẫu nhiên
//        if (strlen($num) < 12)
//            $num = str_pad((string)$num, 8, 0, STR_PAD_RIGHT);
//        else
//            $num = substr($num, 0, 8);
//        if ($userServices->count(['division_invite' => $num])) {
//            return $this->getDivisionInvite();
//        }
//        return $num;
//    }

    /**
     * Thêm cơ quan Sửa
     * @param $uid
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     */    public function getDivisionAgentForm($uid)
    {
        /** @var UserServices $userService */        $userService = app()->make(UserServices::class);
        $userInfo = $userService->get($uid);
        if ($uid && !$userInfo) throw new AdminException('Người dùng không tồn tại');
        $field = [];
        $options = [];
        $divisionList = $userService->getDivisionList(['status' => 1, 'division_type' => 1], 'uid,division_name');
        foreach ($divisionList['list'] as $item) {
            $options[] = ['value' => $item['uid'], 'label' => $item['division_name']];
        }
        $field[] = Form::input('division_name', 'Tên đại lý', $userInfo['division_name'] ?? '')->required('Vui lòng nhập tên đại lý');
        if ($uid) {
            $field[] = Form::hidden('uid', $uid);
            $field[] = Form::hidden('edit', 1);
            $field[] = Form::hidden('division_id', $userInfo['division_id']);
        } else {
            $field[] = Form::select('division_id', 'Phòng kinh doanh cấp cao hơn', '')->setOptions(Form::setOptions($options))->filterable(1);
            $field[] = Form::frameImage('image', 'Người dùng được liên kết', $this->url(config('app.admin_prefix', 'admin') . '/system.user/list', ['fodder' => 'image'], true))->icon('el-icon-user')->width('950px')->height('560px')->Props(['srcKey' => 'image', 'footer' => false]);
            $field[] = Form::hidden('edit', 0);
        }
        $field[] = Form::number('division_percent', 'Tỷ lệ hoa hồng', $userInfo['division_percent'] ?? '')->placeholder('Tỷ lệ hoa hồng đại lý1-100')->info('Điền từ 1-100, nếu điền 50 tức là giảm giá50%,Nhưng không thể cao hơn tỷ lệ của Đơn vị kinh doanh cấp trên')->style(['width' => '173px'])->min(0)->max(100)->required();
        $field[] = Form::date('division_end_time', 'Thời gian hết hạn', ($userInfo['division_end_time'] ?? '') != 0 ? date('Y-m-d H:i:s', $userInfo['division_end_time']) : '')->placeholder('Thời gian hết hạn đại lý');
        $field[] = Form::radio('division_status', 'trạng thái đại lý', $userInfo['division_status'] ?? 1)->options([['label' => 'Mở', 'value' => 1], ['label' => 'đóng cửa', 'value' => 0]]);
        return create_form('đại lý', $field, Route::buildUrl('/agent/division/agent/save'), 'POST');
    }

    /**
     * lưu đại lý
     * @param $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function divisionAgentSave($data)
    {
        /** @var UserServices $userServices */        $userServices = app()->make(UserServices::class);
        $uid = $data['uid'];
        $agentData = [
            'spread_uid' => $data['division_id'],
            'spread_time' => time(),
            'division_id' => $data['division_id'],
            'division_status' => $data['division_status'],
            'division_percent' => $data['division_percent'],
            'division_change_time' => time(),
            'division_end_time' => strtotime($data['division_end_time']),
            'division_type' => 2,
            'is_agent' => 1,
            'agent_id' => $uid,
            'is_staff' => 0,
            'staff_id' => 0,
            'division_name' => $data['division_name'],
            'is_promoter' => 1
        ];
        $division_info = $userServices->getUserInfo($data['division_id'], 'division_end_time,division_percent');
        if ($division_info) {
            if ($agentData['division_percent'] > $division_info['division_percent']) throw new AdminException('Tỷ lệ hoa hồng đại lý không được lớn hơn tỷ lệ hoa hồng Đơn vị kinh doanh');
            if ($agentData['division_end_time'] > $division_info['division_end_time']) throw new AdminException('Thời gian hết hạn của đại lý không được lớn hơn thời gian hết hạn của Đơn vị kinh doanh');
        }
        $res = $userServices->update($uid, $agentData);
        if ($res) return true;
        throw new AdminException('Lưu không thành công');
    }

    /**
     * Sửa đổi trạng thái
     * @param $status
     * @param $uid
     * @return bool
     */    public function setDivisionStatus($status, $uid)
    {
        /** @var UserServices $userServices */        $userServices = app()->make(UserServices::class);
        /** @var SystemAdminServices $adminServices */        $adminServices = app()->make(SystemAdminServices::class);
        $res = $userServices->update($uid, ['division_status' => $status]);
        $res = $res && $adminServices->update(['division_id' => $uid], ['status' => $status]);
        if ($res) {
            return true;
        } else {
            throw new AdminException('Thao tác không thành công');
        }
    }

    /**
     * Xóa bộ phận/đại lý
     * @param $type
     * @param $uid
     * @return mixed
     */    public function delDivision($type, $uid)
    {
        return $this->transaction(function () use ($type, $uid) {
            /** @var UserServices $userServices */            $userServices = app()->make(UserServices::class);
            $userInfo = $userServices->getUserInfo($uid);
            if (!$userInfo) throw new AdminException('Người dùng không tồn tại');
            $userInfo = $userInfo->toArray();
            $data = [
                'division_name' => '',
                'division_type' => 0,
                'division_status' => 0,
                'is_division' => 0,
                'is_agent' => 0,
                'is_staff' => 0,
                'division_id' => 0,
                'agent_id' => 0,
                'staff_id' => 0,
                'division_percent' => 0,
                'division_end_time' => 0,
                'division_change_time' => time(),
                'division_invite' => 0
            ];
            $userServices->update(['uid' => $uid], $data);
            if ($userInfo['division_type'] == 1) {
                app()->make(SystemAdminServices::class)->delete(['division_id' => $uid]);
                $userServices->update(['division_id' => $uid], $data);
            } elseif ($userInfo['division_type'] == 2) {
                app()->make(DivisionAgentApplyServices::class)->delete(['uid' => $uid]);
                $userServices->update(['agent_id' => $uid], $data);
            } elseif ($userInfo['division_type'] == 3) {
                $userServices->update(['staff_id' => $uid], $data);
            }
        });
    }

    /**
     * Thêm nhân viên ở chế độ nền
     * @param $uid
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2024/1/22
     */    public function getDivisionStaffForm($uid)
    {
        $field = [];
        $field[] = Form::frameImage('image', 'nhân viên', $this->url(config('app.admin_prefix', 'admin') . '/system.user/list', ['fodder' => 'image'], true))->icon('el-icon-user')->width('950px')->height('560px')->Props(['srcKey' => 'image', 'footer' => false]);
        $field[] = Form::number('division_percent', 'Tỷ lệ hoa hồng', '')->placeholder('Tỷ lệ hoa hồng nhân viên1-100')->info('Điền từ 1-100, nếu điền 50 tức là giảm giá50%,Nhưng không thể cao hơn tỷ lệ của các đại lý vượt trội')->style(['width' => '173px'])->min(0)->max(100)->required();
        $field[] = Form::hidden('agent_id', $uid);
        return create_form('nhân viên', $field, Route::buildUrl('/agent/division/staff/save'), 'POST');
    }

    /**
     * Lưu nhân viên
     * @param $data
     * @return true
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2024/1/22
     */    public function divisionStaffSave($data)
    {
        $data['uid'] = $data['image']['uid'];
        /** @var UserServices $userServices */        $userServices = app()->make(UserServices::class);
        $userInfo = $userServices->getUserInfo($data['uid'], 'is_division,is_agent,is_staff,division_id,agent_id,staff_id,division_end_time,division_percent');
        if (!$userInfo) throw new AdminException('Người dùng không tồn tại');
        if ($userInfo['is_division']) throw new AdminException('Người dùng này là một bộ phận kinh doanh và không thể bị ràng buộc với tư cách là nhân viên');
        if ($userInfo['is_agent']) throw new AdminException('Người dùng này là đại lý và không thể bị ràng buộc với tư cách là nhân viên');
        if ($userInfo['is_staff'] && $userInfo['agent_id'] == $data['agent_id']) throw new AdminException('Người dùng này là nhân viên của bạn, vui lòng không thêm nó nhiều lần');
        $agentInfo = $userServices->getUserInfo($data['agent_id'], 'division_id,agent_id,division_end_time,division_percent');
        $staffData = [
            'spread_uid' => $data['agent_id'],
            'spread_time' => time(),
            'division_type' => 3,
            'division_status' => 1,
            'is_staff' => 1,
            'division_id' => $agentInfo['division_id'],
            'agent_id' => $agentInfo['agent_id'],
            'staff_id' => $data['uid'],
            'division_percent' => $data['division_percent'],
            'division_change_time' => time(),
            'division_end_time' => $agentInfo['division_end_time'],
            'is_promoter' => 1
        ];
        if ($staffData['division_percent'] > $agentInfo['division_percent']) throw new AdminException('Tỷ lệ hoa hồng đại lý không được lớn hơn tỷ lệ hoa hồng Đơn vị kinh doanh');
        if ($userInfo['agent_id'] != 0 && $userInfo['agent_id'] != $agentInfo['agent_id']) {
            $userServices->update(['staff_id' => $userInfo['uid'], 'spread_uid' => $userInfo['uid']], ['spread_uid' => $agentInfo['agent_id'], 'staff_id' => 0]);
            $userServices->getSearch(['staff_id' => $userInfo['uid'], 'not_spread_uid' => $userInfo['uid']])->update(['staff_id' => 0]);
        }
        $res = $userServices->update($data['uid'], $staffData);
        if ($res) return true;
        throw new AdminException('Lưu không thành công');
    }

    /**
     * Quét mã QR để ràng buộc nhân viên
     * @param $uid
     * @param int $agentId
     * @param int $agentCode
     * @return string
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2024/2/2
     */    public function agentSpreadStaff($uid, int $agentId = 0, int $agentCode = 0)
    {
        if ($agentCode && !$agentId) {
            /** @var QrcodeServices $qrCode */            $qrCode = app()->make(QrcodeServices::class);
            if ($info = $qrCode->getOne(['id' => $agentCode, 'third_type' => 'agent', 'status' => 1])) {
                $agentId = $info['third_id'];
            }
        }
        if (!$agentId) return false;
        if ($uid == $agentId) return 'Tôi không thể giới thiệu bản thân mình';
        /** @var UserServices $userServices */        $userServices = app()->make(UserServices::class);
        $agentInfo = $userServices->getUserInfo($agentId, 'division_id,agent_id,division_end_time,division_percent');
        if (!$agentInfo) return 'Người dùng cao cấp không tồn tại';
        $userInfo = $userServices->getUserInfo($uid, 'is_division,is_agent,is_staff,division_id,agent_id,staff_id,division_end_time,division_percent');
        if (!$userInfo) return 'Người dùng không tồn tại';
        if ($userInfo['is_division']) return 'Bạn là bộ phận kinh doanh,Không thể bị ràng buộc trở thành nhân viên của người khác';
        if ($userInfo['is_agent']) return 'Bạn là một đại lý,Không thể bị ràng buộc trở thành nhân viên của người khác';
        $staffData = [
            'spread_uid' => $agentId,
            'spread_time' => time(),
            'division_type' => 3,
            'division_status' => 1,
            'is_staff' => 1,
            'division_id' => $agentInfo['division_id'],
            'agent_id' => $agentInfo['agent_id'],
            'staff_id' => $uid,
            'division_change_time' => time(),
            'is_promoter' => 1
        ];
        if ($userInfo['agent_id'] != 0 && $userInfo['agent_id'] != $agentInfo['agent_id']) {
            $userServices->update(['staff_id' => $userInfo['uid'], 'spread_uid' => $userInfo['uid']], ['spread_uid' => $agentInfo['agent_id'], 'staff_id' => 0]);
            $userServices->update(['staff_id' => $userInfo['uid'], 'not_spread_uid' => $userInfo['uid']], ['staff_id' => 0]);
        }
        $res = $userServices->update($uid, $staffData);
        if ($res) return 'Liên kết nhân viên thành công';
        return 'Không thể ràng buộc nhân viên';
    }

    /**
     * Nhận tỷ lệ chiết khấu Tỷ lệ hoa hồng
     *Phương pháp hiện tại sẽ giảm dần hoa hồng thu được
     * @param $uid
     * @param $storeBrokerageRatio
     * @param $storeBrokerageRatioTwo
     * @param $isSelfBrokerage
     * @return array
     */    public function getDivisionPercent($uid, $storeBrokerageRatio, $storeBrokerageRatioTwo, $isSelfBrokerage)
    {
        $division_open = (int)sys_config('division_status', 1);
        if (!$division_open) {
            /** Đại lý đã đóng cửa */            $storeBrokerageOne = $storeBrokerageRatio;
            $storeBrokerageTwo = $storeBrokerageRatioTwo;
            $staffPercent = 0;
            $agentPercent = 0;
            $divisionPercent = 0;
        } else {
            /** @var UserServices $userServices */            $userServices = app()->make(UserServices::class);
            $userInfo = $userServices->get($uid);
            if ($userInfo['is_division'] == 1) {
                /** Tôi là bộ phận kinh doanh */                $storeBrokerageOne = 0;
                $storeBrokerageTwo = 0;
                $staffPercent = 0;
                $agentPercent = 0;
                if ($userInfo['division_status'] == 1 && $userInfo['division_end_time'] > time()) {
                    $divisionPercent = $isSelfBrokerage ? $userInfo['division_percent'] : 0;
                } else {
                    $divisionPercent = 0;
                }
            } elseif ($userInfo['is_agent'] == 1) {
                /** Tôi là một đại lý */                $divisionInfo = $userServices->get($userInfo['division_id']);
                $storeBrokerageOne = 0;
                $storeBrokerageTwo = 0;
                $staffPercent = 0;
                if ($userInfo['division_status'] == 1 && $userInfo['division_end_time'] > time()) {
                    $agentPercent = $isSelfBrokerage ? $userInfo['division_percent'] : 0;
                } else {
                    $agentPercent = 0;
                }
                if ($divisionInfo['division_status'] == 1 && $divisionInfo['division_end_time'] > time()) {
                    $divisionPercent = bcsub($divisionInfo['division_percent'], $agentPercent, 2);
                    $divisionPercent = $divisionPercent < 0 ? 0 : $divisionPercent;
                } else {
                    $divisionPercent = 0;
                }
            } elseif ($userInfo['is_staff'] == 1) { // tôi là một nhân viên
                /** Tôi là nhân viên */                $agentInfo = $userServices->get($userInfo['agent_id']);
                $divisionInfo = $userServices->get($userInfo['division_id']);
                $storeBrokerageOne = 0;
                $storeBrokerageTwo = 0;
                if ($userInfo['division_status'] == 1 && $userInfo['division_end_time'] > time()) {
                    $staffPercent = $isSelfBrokerage ? $userInfo['division_percent'] : 0;
                } else {
                    $staffPercent = 0;
                }
                if ($agentInfo['division_status'] == 1 && $agentInfo['division_end_time'] > time()) {
                    $agentPercent = bcsub($agentInfo['division_percent'], $staffPercent, 2);
                    $agentPercent = $agentPercent < 0 ? 0 : $agentPercent;
                } else {
                    $agentPercent = 0;
                }
                if ($divisionInfo['division_status'] == 1 && $divisionInfo['division_end_time'] > time()) {
                    $divisionPercent = bcsub($divisionInfo['division_percent'], bcadd($staffPercent, $agentPercent, 2), 2);
                    $divisionPercent = $divisionPercent < 0 ? 0 : $divisionPercent;
                } else {
                    $divisionPercent = 0;
                }
            } else {
                /** Tôi là Khách hàng bình thường */                $staffInfo = $userServices->get($userInfo['staff_id']);
                $agentInfo = $userServices->get($userInfo['agent_id']);
                $divisionInfo = $userServices->get($userInfo['division_id']);
                if ($userInfo['staff_id']) {
                    /** Người dùng này quảng cáo cho nhân viên */                    if ($userInfo['staff_id'] == $userInfo['spread_uid']) {
                        /** Nhân viên báo cáo trực tiếp cho */                        $storeBrokerageOne = $isSelfBrokerage ? $storeBrokerageRatio : 0;
                        $storeBrokerageTwo = 0;
                        if ($staffInfo['division_status'] == 1 && $staffInfo['division_end_time'] > time()) {
                            $staffPercent = bcsub($staffInfo['division_percent'], $storeBrokerageOne, 2);
                            $staffPercent = $staffPercent < 0 ? 0 : $staffPercent;
                        } else {
                            $staffPercent = 0;
                        }
                        if ($agentInfo['division_status'] == 1 && $agentInfo['division_end_time'] > time()) {
                            $agentPercent = bcsub($agentInfo['division_percent'], bcadd($storeBrokerageOne, $staffPercent, 2), 2);
                            $agentPercent = $agentPercent < 0 ? 0 : $agentPercent;
                        } else {
                            $agentPercent = 0;
                        }
                        if ($divisionInfo['division_status'] == 1 && $divisionInfo['division_end_time'] > time()) {
                            $divisionPercent = bcsub($divisionInfo['division_percent'], bcadd(bcadd($storeBrokerageOne, $staffPercent, 2), $agentPercent, 2), 2);
                            $divisionPercent = $divisionPercent < 0 ? 0 : $divisionPercent;
                        } else {
                            $divisionPercent = 0;
                        }
                    } else {
                        $storeBrokerageOne = $storeBrokerageRatio;
                        $storeBrokerageTwo = $userServices->value(['uid' => $userInfo['spread_uid']], 'spread_uid') == $userInfo['staff_id'] && !$isSelfBrokerage ? 0 : $storeBrokerageRatioTwo;
                        $brokerageOneTwo = bcadd($storeBrokerageOne, $storeBrokerageTwo, 2);
                        if ($staffInfo['division_status'] == 1 && $staffInfo['division_end_time'] > time()) {
                            $staffPercent = bcsub($staffInfo['division_percent'], $brokerageOneTwo, 2);
                            $staffPercent = $staffPercent < 0 ? 0 : $staffPercent;
                        } else {
                            $staffPercent = 0;
                        }
                        if ($agentInfo['division_status'] == 1 && $agentInfo['division_end_time'] > time()) {
                            $agentPercent = bcsub($agentInfo['division_percent'], bcadd($brokerageOneTwo, $staffPercent, 2), 2);
                            $agentPercent = $agentPercent < 0 ? 0 : $agentPercent;
                        } else {
                            $agentPercent = 0;
                        }
                        if ($divisionInfo['division_status'] == 1 && $divisionInfo['division_end_time'] > time()) {
                            $divisionPercent = bcsub($divisionInfo['division_percent'], bcadd(bcadd($brokerageOneTwo, $staffPercent, 2), $agentPercent, 2), 2);
                            $divisionPercent = $divisionPercent < 0 ? 0 : $divisionPercent;
                        } else {
                            $divisionPercent = 0;
                        }
                    }
                } elseif ($userInfo['agent_id']) {
                    /** Người dùng này quảng bá cho đại lý */                    if ($userInfo['agent_id'] == $userInfo['spread_uid']) {
                        $storeBrokerageOne = $isSelfBrokerage ? $storeBrokerageRatio : 0;
                        $storeBrokerageTwo = 0;
                        $staffPercent = 0;
                        if ($agentInfo['division_status'] == 1 && $agentInfo['division_end_time'] > time()) {
                            $agentPercent = bcsub($agentInfo['division_percent'], $storeBrokerageOne, 2);
                            $agentPercent = $agentPercent < 0 ? 0 : $agentPercent;
                        } else {
                            $agentPercent = 0;
                        }
                        if ($divisionInfo['division_status'] == 1 && $divisionInfo['division_end_time'] > time()) {
                            $divisionPercent = bcsub($divisionInfo['division_percent'], bcadd($storeBrokerageOne, $agentPercent, 2), 2);
                            $divisionPercent = $divisionPercent < 0 ? 0 : $divisionPercent;
                        } else {
                            $divisionPercent = 0;
                        }
                    } else {
                        $storeBrokerageOne = $storeBrokerageRatio;
                        $storeBrokerageTwo = $userServices->value(['uid' => $userInfo['spread_uid']], 'spread_uid') == $userInfo['agent_id'] && !$isSelfBrokerage ? 0 : $storeBrokerageRatioTwo;
                        $brokerageOneTwo = bcadd($storeBrokerageOne, $storeBrokerageTwo, 2);
                        $staffPercent = 0;
                        if ($agentInfo['division_status'] == 1 && $agentInfo['division_end_time'] > time()) {
                            $agentPercent = bcsub($agentInfo['division_percent'], $brokerageOneTwo, 2);
                            $agentPercent = $agentPercent < 0 ? 0 : $agentPercent;
                        } else {
                            $agentPercent = 0;
                        }
                        if ($divisionInfo['division_status'] == 1 && $divisionInfo['division_end_time'] > time()) {
                            $divisionPercent = bcsub($divisionInfo['division_percent'], bcadd($brokerageOneTwo, $agentPercent, 2), 2);
                            $divisionPercent = $divisionPercent < 0 ? 0 : $divisionPercent;
                        } else {
                            $divisionPercent = 0;
                        }
                    }
                } elseif ($userInfo['division_id']) {
                    /** Người dùng này quảng cáo bộ phận kinh doanh */                    if ($userInfo['division_id'] == $userInfo['spread_uid']) {
                        /** Phòng kinh doanh báo cáo trực tiếp */                        $storeBrokerageOne = $isSelfBrokerage ? $storeBrokerageRatio : 0;
                        $storeBrokerageTwo = 0;
                        $staffPercent = 0;
                        $agentPercent = 0;
                        if ($divisionInfo['division_status'] == 1 && $divisionInfo['division_end_time'] > time()) {
                            $divisionPercent = bcsub($divisionInfo['division_percent'], $storeBrokerageOne, 2);
                            $divisionPercent = $divisionPercent < 0 ? 0 : $divisionPercent;
                        } else {
                            $divisionPercent = 0;
                        }
                    } else {
                        $storeBrokerageOne = $storeBrokerageRatio;
                        $storeBrokerageTwo = $userServices->value(['uid' => $userInfo['spread_uid']], 'spread_uid') == $userInfo['division_id'] && !$isSelfBrokerage ? 0 : $storeBrokerageRatioTwo;
                        $brokerageOneTwo = bcadd($storeBrokerageOne, $storeBrokerageTwo, 2);
                        $staffPercent = 0;
                        $agentPercent = 0;
                        if ($divisionInfo['division_status'] == 1 && $divisionInfo['division_end_time'] > time()) {
                            $divisionPercent = bcsub($divisionInfo['division_percent'], $brokerageOneTwo, 2);
                            $divisionPercent = $divisionPercent < 0 ? 0 : $divisionPercent;
                        } else {
                            $divisionPercent = 0;
                        }
                    }
                } else {
                    /** Không có mối quan hệ đại lý */                    $storeBrokerageOne = $storeBrokerageRatio;
                    $storeBrokerageTwo = $storeBrokerageRatioTwo;
                    $staffPercent = 0;
                    $agentPercent = 0;
                    $divisionPercent = 0;
                }
            }
        }
        return [max($storeBrokerageOne, 0), max($storeBrokerageTwo, 0), max($staffPercent, 0), max($agentPercent, 0), max($divisionPercent, 0)];
    }

    /**
     * Thống kê Đơn vị kinh doanh
     * @param $type
     * @param $time
     * @param $page
     * @param $limit
     * @param $sort
     * @param $order
     * @return mixed
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2025/4/8
     */    public function divisionStatistics($type, $time, $page, $limit, $sort, $order)
    {
        switch ($type) {
            case 1:
                $field = 'division_id';
                break;
            case 2:
                $field = 'agent_id';
                break;
            case 3:
                $field = 'staff_id';
                break;
            default:
                $field = 'division_id';
        }
        $data = app()->make(StoreOrderServices::class)->divisionStatistics($field, $time, $page, $limit, $sort, $order);
        $uids = array_column($data['list'], $field);
        $userInfos = app()->make(UserServices::class)->getColumn(['uid' => $uids], 'uid,nickname,avatar', 'uid');
        foreach ($data['list'] as $key => &$val) {
            $val['uid'] = $val[$field];
            $val['name'] = $userInfos[$val[$field]]['nickname'];
            $val['avatar'] = set_file_url($userInfos[$val[$field]]['avatar']);
        }
        return $data;
    }
}
