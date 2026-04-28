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
declare (strict_types=1);

namespace app\services\user;

use app\services\BaseServices;
use app\dao\user\UserLevelDao;
use app\services\system\SystemUserLevelServices;
use crmeb\exceptions\AdminException;
use crmeb\exceptions\ApiException;
use crmeb\services\FormBuilder as Form;
use think\facade\Route as Url;

/**
 *
 * Class UserLevelServices
 * @package app\services\user
 * @method getDiscount(int $uid, string $field)
 */
class UserLevelServices extends BaseServices
{

    /**
     * UserLevelServices constructor.
     * @param UserLevelDao $dao
     */
    public function __construct(UserLevelDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Một số điều kiện nhất định có được một
     * @param array $where
     * @param string $field
     * @return mixed
     */
    public function getWhereLevel(array $where, string $field = '*')
    {
        return $this->getOne($where, $field);
    }

    /**
     * Nhận một số thông tin cấp độ người dùng
     * @param array $uids
     * @param string $field
     * @param string $key
     * @return array
     */
    public function getUsersLevelInfo(array $uids)
    {
        return $this->dao->getColumn([['uid', 'in', $uids]], 'level_id,is_forever,valid_time', 'uid');
    }

    /**
     * Xóa cấp độ người dùng
     * @param $uids
     * @return \crmeb\basic\BaseModel|mixed
     */
    public function delUserLevel($uids)
    {
        $where = [];
        if (is_array($uids)) {
            $where[] = ['uid', 'IN', $uids];
            $re = $this->dao->batchUpdate($uids, ['is_del' => 1, 'status' => 0], 'uid');
        } else {
            $where[] = ['uid', '=', $uids];
            $re = $this->dao->update($uids, ['is_del' => 1, 'status' => 0], 'uid');
        }
        if (!$re)
            throw new AdminException('Không thể sửa đổi thông tin cấp độ người dùng');
        $where[] = ['category', 'IN', ['exp']];
        /** @var UserBillServices $userbillServices */
        $userbillServices = app()->make(UserBillServices::class);
        $userbillServices->update($where, ['status' => -1]);
        return true;
    }

    /**
     * Nhận thông tin chi tiết cấp độ người dùng dựa trên uid người dùng
     * @param int $uid
     * @param string $field
     */
    public function getUerLevelInfoByUid(int $uid, string $field = '')
    {
        $userLevelInfo = $this->dao->getUserLevel($uid);
        $data = [];
        if ($userLevelInfo) {
            $data = ['id' => $userLevelInfo['id'], 'level_id' => $userLevelInfo['level_id'], 'add_time' => $userLevelInfo['add_time']];
            $data['discount'] = $userLevelInfo['levelInfo']['discount'] ?? 0;
            $data['name'] = $userLevelInfo['levelInfo']['name'] ?? '';
            $data['money'] = $userLevelInfo['levelInfo']['money'] ?? 0;
            $data['icon'] = $userLevelInfo['levelInfo']['icon'] ?? '';
            $data['is_pay'] = $userLevelInfo['levelInfo']['is_pay'] ?? 0;
            $data['grade'] = $userLevelInfo['levelInfo']['grade'] ?? 0;
            $data['exp_num'] = $userLevelInfo['levelInfo']['exp_num'] ?? 0;
        }
        if ($field) return $data[$field] ?? '';
        return $data;
    }

    /**
     * Đặt cấp độ người dùng
     * @param $uid người dùnguid
     * @param $level_id cấpid
     * @return UserLevel|bool|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function setUserLevel(int $uid, int $level_id, $vipinfo = [])
    {
        /** @var SystemUserLevelServices $systemLevelServices */
        $systemLevelServices = app()->make(SystemUserLevelServices::class);
        if (!$vipinfo) {
            $vipinfo = $systemLevelServices->getLevel($level_id);
            if (!$vipinfo) {
                throw new AdminException('Hạng khách hàng không tồn tại');
            }
        }
        /** @var  $user */
        $user = app()->make(UserServices::class);
        $userinfo = $user->getUserInfo($uid);
        //Vô hiệu hóa cấp độ trước đó
        $this->dao->update(['uid' => $uid], ['status' => 0, 'is_del' => 1]);
        //Kiểm tra nếu đã mua
        $uservipinfo = $this->getWhereLevel(['uid' => $uid, 'level_id' => $level_id]);
        $data['mark'] = 'Kính gửi người dùng' . $userinfo['nickname'] . 'hiện hữu' . date('Y-m-d H:i:s', time()) . 'đã trở thành' . $vipinfo['name'];
        $data['add_time'] = time();
        if ($uservipinfo) {
            $data['status'] = 1;
            $data['is_del'] = 0;
            if (!$this->dao->update(['id' => $uservipinfo['id']], $data))
                throw new AdminException('Không thể sửa đổi thông tin cấp độ người dùng');
        } else {
            $data = array_merge($data, [
                'is_forever' => $vipinfo->is_forever,
                'status' => 1,
                'is_del' => 0,
                'grade' => $vipinfo->grade,
                'uid' => $uid,
                'level_id' => $level_id,
                'discount' => $vipinfo->discount,
            ]);
            $data['valid_time'] = 0;
            if (!$this->dao->save($data)) throw new AdminException('Lưu không thành công');
        }
        if ($level_id > $userinfo['level']) {
            $change_exp = $vipinfo['exp_num'] - $userinfo['exp'];
            $pm = 1;
            $type = 'system_exp_add';
            $title = 'Hệ thống tăng kinh nghiệm';
            $mark = 'Hệ thống tăng' . $change_exp . 'kinh nghiệm';
        } else {
            $change_exp = $userinfo['exp'] - $vipinfo['exp_num'];
            $pm = 0;
            $type = 'system_exp_sub';
            $title = 'Hệ thống giảm kinh nghiệm';
            $mark = 'Giảm hệ thống' . $change_exp . 'kinh nghiệm';
        }
        $bill_data['uid'] = $uid;
        $bill_data['pm'] = $pm;
        $bill_data['title'] = $title;
        $bill_data['category'] = 'exp';
        $bill_data['type'] = $type;
        $bill_data['number'] = abs($change_exp);
        $bill_data['balance'] = $userinfo['exp'];
        $bill_data['mark'] = $mark;
        $bill_data['status'] = 1;
        $bill_data['add_time'] = time();
        /** @var UserBillServices $userBillService */
        $userBillService = app()->make(UserBillServices::class);
        if (!$userBillService->save($bill_data)) throw new AdminException('Lưu không thành công');
        if (!$user->update(['uid' => $uid], ['level' => $level_id, 'exp' => $vipinfo['exp_num']])) throw new AdminException('Sửa đổi không thành công');
        return true;
    }

    /**
     * Danh sách thành viên
     * @param $where
     * @return mixed
     */
    public function getSytemList($where)
    {
        /** @var SystemUserLevelServices $systemLevelServices */
        $systemLevelServices = app()->make(SystemUserLevelServices::class);
        return $systemLevelServices->getLevelList($where);
    }

    /**
     * Lấy dữ liệu biểu mẫu cần thiết để thêm và sửa đổi
     * @param int $id
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function edit(int $id)
    {

        if ($id) {
            $vipInfo = app()->make(SystemUserLevelServices::class)->getlevel($id);
            $vipInfo->image = set_file_url($vipInfo->image);
            $vipInfo->icon = set_file_url($vipInfo->icon);
            if (!$vipInfo) {
                throw new AdminException('Dữ liệu không tồn tại');
            }
            $field[] = Form::hidden('id', $id);
            $msg = 'Chỉnh sửa cấp độ người dùng';
        } else {
            $msg = 'Thêm cấp độ người dùng';
        }
        $field[] = Form::input('name', 'Tên cấp độ', isset($vipInfo) ? $vipInfo->name : '')->maxlength(10)->col(24)->required();
        $field[] = Form::number('grade', 'cấp', isset($vipInfo) ? $vipInfo->grade : 0)->min(0)->precision(0)->required();
        $field[] = Form::number('discount', 'tận hưởng giảm giá', isset($vipInfo) ? $vipInfo->discount : 100)->min(0)->max(100)->placeholder('Nhập số giảm giá là 100, đại diện cho giá gốc và 90, đại diện cho mức giảm giá 10%.')->required();
        $field[] = Form::number('exp_num', 'Mở khóa điểm kinh nghiệm', isset($vipInfo) ? $vipInfo->exp_num : 0)->min(0)->precision(0)->required();
        $field[] = Form::frameImage('icon', 'biểu tượng', Url::buildUrl(config('app.admin_prefix', 'admin') . '/widget.images/index', array('fodder' => 'icon')), isset($vipInfo) ? $vipInfo->icon : '')->icon('el-icon-picture-outline')->width('950px')->height('560px')->props(['footer' => false]);
        $field[] = Form::frameImage('image', 'Nền cấp độ người dùng', Url::buildUrl(config('app.admin_prefix', 'admin') . '/widget.images/index', array('fodder' => 'image')), isset($vipInfo) ? $vipInfo->image : '')->icon('el-icon-picture-outline')->width('950px')->height('560px')->props(['footer' => false]);
        $field[] = Form::radio('is_show', 'Có hiển thị hay không', isset($vipInfo) ? $vipInfo->is_show : 0)->options([['label' => 'trình diễn', 'value' => 1], ['label' => 'trốn', 'value' => 0]])->col(24);
        return create_form($msg, $field, Url::buildUrl('/user/user_level'), 'POST');
    }

    /*
     * Thêm hoặc sửa đổi cấp độ thành viên
     * @param $id mức độ sửa đổiid
     * @return json
     * */
    public function save(int $id, array $data)
    {
        /** @var SystemUserLevelServices $systemUserLevel */
        $systemUserLevel = app()->make(SystemUserLevelServices::class);
        $levelOne = $systemUserLevel->getWhereLevel(['is_del' => 0, 'grade' => $data['grade']]);
        $levelTwo = $systemUserLevel->getWhereLevel(['is_del' => 0, 'exp_num' => $data['exp_num']]);
        $levelThree = $systemUserLevel->getWhereLevel(['is_del' => 0, 'name' => $data['name']]);
        $levelPre = $systemUserLevel->getPreLevel($data['grade']);
        $levelNext = $systemUserLevel->getNextLevel($data['grade']);
        if ($levelPre && $data['exp_num'] <= $levelPre['exp_num']) {
            throw new AdminException('Trải nghiệm ở cấp độ người dùng phải lớn hơn trải nghiệm được đặt cho cấp độ trước đó');
        }
        if ($levelNext && $data['exp_num'] >= $levelNext['exp_num']) {
            throw new AdminException('Trải nghiệm ở cấp độ người dùng phải nhỏ hơn trải nghiệm được đặt cho cấp độ tiếp theo');
        }
        //Ôn lại
        if ($id) {
            if (($levelOne && $levelOne['id'] != $id) || ($levelThree && $levelThree['id'] != $id)) {
                throw new AdminException('Hạng khách hàng bạn đặt đã được phát hiện. Mức độ này không thể lặp lại.');
            }
            if ($levelTwo && $levelTwo['id'] != $id) {
                throw new AdminException('Chúng tôi đã phát hiện thấy rằng bạn đã đặt giá trị trải nghiệm cho cấp độ người dùng này. Giá trị kinh nghiệm không thể lặp lại.');
            }
            if (!$systemUserLevel->update($id, $data)) {
                throw new AdminException('Sửa đổi không thành công');
            }
            return true;
        } else {
            if ($levelOne || $levelThree) {
                throw new AdminException('Hạng khách hàng bạn đặt đã được phát hiện. Mức độ này không thể lặp lại.');
            }
            if ($levelTwo) {
                throw new AdminException('Chúng tôi đã phát hiện thấy rằng bạn đã đặt giá trị trải nghiệm cho cấp độ người dùng này. Giá trị kinh nghiệm không thể lặp lại.');
            }
            //Mới
            $data['add_time'] = time();
            if (!$systemUserLevel->save($data)) {
                throw new AdminException('Thêm không thành công');
            }
            return true;
        }
    }

    /**
     * xóa giả
     * @param int $id
     * @return mixed
     */
    public function delLevel(int $id)
    {
        /** @var SystemUserLevelServices $systemUserLevel */
        $systemUserLevel = app()->make(SystemUserLevelServices::class);
        $level = $systemUserLevel->getWhereLevel(['id' => $id]);
        if ($level && $level['is_del'] != 1) {
            if (!$systemUserLevel->update($id, ['is_del' => 1]))
                throw new AdminException('Xóa không thành công');
        }
        return 'Xóa thành công';
    }

    /**
     * Đặt xem có hiển thị hay không
     * @param int $id
     * @param $is_show
     * @return mixed
     */
    public function setShow(int $id, int $is_show)
    {
        /** @var SystemUserLevelServices $systemUserLevel */
        $systemUserLevel = app()->make(SystemUserLevelServices::class);
        if (!$systemUserLevel->getWhereLevel(['id' => $id]))
            throw new AdminException('Dữ liệu không tồn tại');
        if ($systemUserLevel->update($id, ['is_show' => $is_show])) {
            return 'Thiết lập thành công';
        } else {
            throw new AdminException('Thiết lập không thành công');
        }
    }

    /**
     * Sửa đổi nhanh
     * @param int $id
     * @param $is_show
     * @return mixed
     */
    public function setValue(int $id, array $data)
    {
        /** @var SystemUserLevelServices $systemUserLevel */
        $systemUserLevel = app()->make(SystemUserLevelServices::class);
        if (!$systemUserLevel->getWhereLevel(['id' => $id]))
            throw new AdminException('Dữ liệu không tồn tại');
        if ($systemUserLevel->update($id, [$data['field'] => $data['value']])) {
            return true;
        } else {
            throw new AdminException('Lưu không thành công');
        }
    }

    /**
     * Phát hiện nâng cấp thành viên người dùng
     * @param $uid
     * @return bool
     */
    public function detection(int $uid)
    {
        //Thành viên trung tâm mua sắm có được kích hoạt không?
        if (!sys_config('member_func_status')) {
            return true;
        }
        /** @var UserServices $userServices */
        $userServices = app()->make(UserServices::class);
        $user = $userServices->getUserInfo($uid);
        if (!$user) {
            throw new ApiException('Nếu không có người dùng này, không thể phát hiện được cấp độ người dùng nâng cấp.');
        }
        /** @var SystemUserLevelServices $systemUserLevel */
        $systemUserLevel = app()->make(SystemUserLevelServices::class);
        $userAllLevel = $systemUserLevel->getList([['is_del', '=', 0], ['is_show', '=', 1], ['exp_num', '<=', (float)$user['exp']]]);
        if (!$userAllLevel) {
            return true;
        }
        $data = [];
        $data['add_time'] = time();
        $userLevel = $this->dao->getColumn(['uid' => $uid, 'status' => 1, 'is_del' => 0], 'level_id');
        foreach ($userAllLevel as $vipinfo) {
            if (in_array($vipinfo['id'], $userLevel)) {
                continue;
            }
            $data['mark'] = 'Kính gửi người dùng' . $user['nickname'] . 'hiện hữu' . date('Y-m-d H:i:s', time()) . 'đã trở thành' . $vipinfo['name'];
            $uservip = $this->dao->getOne(['uid' => $uid, 'level_id' => $vipinfo['id']]);
            if ($uservip) {
                //Hạ cấp trong trường hợp nâng cấp
                $data['status'] = 1;
                $data['is_del'] = 0;
                if (!$this->dao->update($uservip['id'], $data, 'id')) {
                    throw new ApiException('Phát hiện nâng cấp không thành công');
                }
            } else {
                $data = array_merge($data, [
                    'is_forever' => $vipinfo['is_forever'],
                    'status' => 1,
                    'is_del' => 0,
                    'grade' => $vipinfo['grade'],
                    'uid' => $uid,
                    'level_id' => $vipinfo['id'],
                    'discount' => $vipinfo['discount'],
                ]);
                if (!$this->dao->save($data)) {
                    throw new ApiException('Phát hiện nâng cấp không thành công');
                }
            }
            $data['add_time'] += 1;
        }
        if (!$userServices->update($uid, ['level' => end($userAllLevel)['id']], 'uid')) {
            throw new ApiException('Phát hiện nâng cấp không thành công');
        }
        return true;
    }

    /**
     * Danh sách cấp thành viên
     * @param int $uid
     */
    public function grade(int $uid)
    {
        //Thành viên trung tâm mua sắm có được kích hoạt không?
        if (!sys_config('member_func_status')) {
            return [];
        }
        /** @var UserServices $userServices */
        $userServices = app()->make(UserServices::class);
        $user = $userServices->getUserInfo($uid);
        if (!$user) {
            throw new ApiException('Nếu không có người dùng này, không thể phát hiện được cấp độ người dùng nâng cấp.');
        }
        $userLevelInfo = $this->getUerLevelInfoByUid($uid);
        if (empty($userLevelInfo)) {
            $level_id = 0;
        } else {
            $level_id = $userLevelInfo['level_id'];
        }
        /** @var SystemUserLevelServices $systemUserLevel */
        $systemUserLevel = app()->make(SystemUserLevelServices::class);
        return $systemUserLevel->getLevelListAndGrade($level_id);
    }

    /**
     * Nhận thông tin thành viên
     * @param int $uid
     * @return array[]
     */
    public function getUserLevelInfo(int $uid)
    {
        $data = ['user' => [], 'level_info' => [], 'level_list' => [], 'task' => []];
        //Thành viên trung tâm mua sắm có được kích hoạt không?
        if (!sys_config('member_func_status')) {
            return $data;
        }
        /** @var UserServices $userServices */
        $userServices = app()->make(UserServices::class);
        $user = $userServices->getUserInfo($uid);
        if (!$user) {
            throw new ApiException('Người dùng không tồn tại');
        }
        $data['user'] = $user;
        /** @var SystemUserLevelServices $systemUserLevel */
        $systemUserLevel = app()->make(SystemUserLevelServices::class);
        $levelList = $systemUserLevel->getList(['is_del' => 0, 'is_show' => 1]);
        $i = 0;
        foreach ($levelList as &$level) {
            $level['next_exp_num'] = $levelList[$i + 1]['exp_num'] ?? $level['exp_num'];
            $level['image'] = set_file_url($level['image']);
            $level['icon'] = set_file_url($level['icon']);
            $i++;
        }
        $data['level_list'] = $levelList;
        $data['level_info'] = $this->getUerLevelInfoByUid($uid);
        $data['level_info']['exp'] = $user['exp'] ?? 0;
        /** @var UserBillServices $userBillservices */
        $userBillservices = app()->make(UserBillServices::class);
        $data['level_info']['today_exp'] = $userBillservices->getExpSum($uid, 'today');
        $task = [];
        /** @var UserSignServices $userSignServices */
        $userSignServices = app()->make(UserSignServices::class);
        $task['sign_count'] = $userSignServices->getSignSumDay($uid);
        $task['sign'] = sys_config('sign_give_exp', 0);
        $task['order'] = sys_config('order_give_exp', 0);
        $task['invite'] = sys_config('invite_user_exp', 0);
        $data['task'] = $task;
        return $data;
    }

    /**
     * Danh sách kinh nghiệm
     * @param int $uid
     * @return array
     */
    public function expList(int $uid)
    {
        /** @var UserServices $userServices */
        $userServices = app()->make(UserServices::class);
        $user = $userServices->getUserInfo($uid);
        if (!$user) {
            throw new ApiException('Người dùng không tồn tại');
        }
        /** @var UserBillServices $userBill */
        $userBill = app()->make(UserBillServices::class);
        $data = $userBill->getExpList($uid, [], 'id,title,number,pm,add_time');
        $list = $data['list'] ?? [];
        return $list;
    }
}
