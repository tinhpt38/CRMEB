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

namespace app\services\user\member;


use app\dao\user\MemberCardDao;
use app\services\BaseServices;
use app\services\order\OtherOrderServices;
use app\services\order\StoreOrderCreateServices;
use app\services\user\UserServices;
use crmeb\exceptions\AdminException;
use crmeb\exceptions\ApiException;
use crmeb\services\SystemConfigService;

class MemberCardServices extends BaseServices
{
    /**
     * @var MemberCardDao
     */    protected $dao;

    /** Khởi tạo và lấy phần xử lý lớp dao
     * MemberCardServices constructor.
     * @param MemberCardDao $memberCardDao
     */    public static $_memberTypePrefix = ['month', 'quarter', 'year', 'ever', 'free', 'owner'];

    public function __construct(MemberCardDao $memberCardDao)
    {
        $this->dao = $memberCardDao;
    }

    public function getSearchList(array $where = [])
    {
        /** @var  UserServices $userService */        $userService = app()->make(UserServices::class);
        [$page, $limit] = $this->getPageValue();
        $where['batch_card_id'] = $where['card_batch_id'];
        if ($where['is_use'] != "") {
            if ($where['is_use'] == 0) {
                $where['use_time'] = 0;
            } else {
                $where['use_time'] = 1;
            }
        }
        unset($where['is_use']);
        $list = $this->dao->getSearchList($where, $page, $limit);
        $userIds = array_column($list->toArray(), 'use_uid');
        $userList = $userService->getColumn([['uid', 'in', $userIds]], 'nickname,phone,real_name', 'uid');
        foreach ($list as $k => $v) {
            if ($v['use_uid']) {
                $list[$k]['username'] = $userList[$v['use_uid']]['real_name'] ?: $userList[$v['use_uid']]['nickname'];
                $list[$k]['phone'] = $userList[$v['use_uid']] ? $userList[$v['use_uid']]['phone'] : "";
            }
            $list[$k]['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
            $list[$k]['use_time'] = $v['use_time'] != 0 ? date('Y-m-d H:i:s', $v['use_time']) : "Không được sử dụng";
        }
        $count = $this->dao->count($where);
        return compact('list', 'count');

    }

    /** Tạo thẻ thành viên miễn phí
     * @param array $data
     */    public function addCard(array $data)
    {
        if (!isset($data['card_batch_id']) || !$data['card_batch_id'] || $data['card_batch_id'] == 0 || !isset($data['total_num']) || !$data['total_num'] || $data['total_num'] == 0) {
            throw new AdminException('Lỗi tham số');
        }
        try {
            if (!isset($data['total_num'])) throw new AdminException('Lỗi tham số');
            $num = $data['total_num'];
            unset($data['total_num']);
            $res = [];
            for ($i = 0; $i < $num; $i++) {
                $data['card_number'] = $this->makeRandomNumber("CR", $data['card_batch_id']);
                $data['card_password'] = $this->makeRandomNumber();
                $data['status'] = 1;
                $data['add_time'] = time();
                $res[] = $data;
            }
            //Các lát dữ liệu được chèn theo đợt để cải thiện hiệu suất。
            $chunk_inster_card = array_chunk($res, 100, true);
            foreach ($chunk_inster_card as $v) {
                $this->dao->saveAll($v);
            }
            return true;
        } catch (\Exception $exception) {
            throw new AdminException('Không tạo được thẻ');
        }
    }

    /**Lấy số ngẫu nhiên của số thẻ
     * @param bool $prefix
     * @param bool $random
     * @return string
     */    public function makeRandomNumber($prefix = false, $random = false)
    {
        if (!$prefix) {
            $prefix = "";
        }
        if (!$random || !is_numeric($random)) {
            $one_random = mt_rand(11111, 99999);
        } else {
            $one_random = sprintf("%05d", $random);
        }
        $date_random = date('ymd', time());
        $random_tmp = strlen($one_random);
        $two_randow = str_pad(mt_rand(1, 99999), $random_tmp, '0', STR_PAD_LEFT);
        if (!$random) {
            return $two_randow;
        } else {
            return $prefix . $one_random . $date_random . $two_randow;
        }
    }

    /** Nhận thẻ thành viên
     * @param array $data
     * @param int $uid
     */    public function drawMemberCard(array $data, int $uid)
    {
        if (!$uid || !$data) throw new ApiException('Lỗi tham số');
        $isOpenMember = $this->isOpenMemberCard();
        if (!$isOpenMember) throw new ApiException('Chức năng thành viên chưa được kích hoạt');
        if (!isset($data['member_card_code']) || !$data['member_card_code']) throw new ApiException('Vui lòng nhập số thẻ thành viên của bạn');
        if (!isset($data['member_card_code']) || !$data['member_card_pwd']) throw new ApiException('Vui lòng nhập mật khẩu để nhận thẻ');
        $card_info = $this->dao->getOneByWhere(['card_number' => trim($data['member_card_code'])]);
        if (!$card_info) throw new ApiException('Thẻ thành viên không tồn tại');
        /** @var MemberCardBatchServices $memberBatchServices */        $memberBatchServices = app()->make(MemberCardBatchServices::class);
        $batch_info = $memberBatchServices->getOne($card_info['card_batch_id']);
        if (!$batch_info) throw new ApiException('Thẻ thành viên chưa được kích hoạt và tạm thời không thể sử dụng được.');
        if ($batch_info->status != 1) throw new ApiException('Thẻ thành viên chưa được kích hoạt và tạm thời không thể sử dụng được.');
        if ($card_info['status'] == 0) throw new ApiException('Thẻ thành viên chưa được kích hoạt và tạm thời không thể sử dụng được.');
        if ($card_info['card_password'] != trim($data['member_card_pwd'])) throw new ApiException('Mật khẩu thẻ thành viên không chính xác');
        if ($card_info['use_uid'] && $card_info['use_time']) throw new ApiException('Thẻ thành viên đã được sử dụng');
        /** @var UserServices $userServices */        $userServices = app()->make(UserServices::class);
        $user_info = $userServices->getUserInfo($uid);
        if (!$user_info) throw new ApiException('Người dùng không tồn tại');
        if ($user_info->is_money_level > 0 && $user_info->is_ever_level == 1) throw new ApiException('Bạn đã là thành viên thường trực và không cần phải thu thập lại. Bạn có thể chuyển thẻ này cho người thân, bạn bè để cùng nhau hưởng ưu đãi.');


        /**
         * Thời hạn sử dụng cụ thể của thẻ batch có thể được mở nếu doanh nghiệp có nhu cầu. Đừng xóa nó.。
         */        if ($card_info->status != 1) throw new ApiException('Thẻ thành viên chưa được kích hoạt và tạm thời không thể sử dụng được.');
        $this->transaction(function () use ($card_info, $user_info, $batch_info, $memberBatchServices, $userServices, $data) {
            $res1 = $this->dao->update($card_info->id, ['use_uid' => $user_info->uid, 'use_time' => time(), 'update_time' => time()], 'id');
            if ($res1) {
                $res2 = $memberBatchServices->useCardSetInc($batch_info->id, 'use_num', 1);
                $overdue_time = 0;
                switch ($user_info->is_money_level) {
                    case 1:
                    case 2:
                    case 3:
                        $overdue_time = bcadd(bcmul($batch_info->use_day, 86400, 0), $user_info->overdue_time, 0);
                        $overdue_time = mktime(date('H'), date('i'), date('s'), date('m', $overdue_time), date('d', $overdue_time), date('Y', $overdue_time));
                        break;
                    case 0:
                        $overdue_time = bcadd(bcmul($batch_info->use_day, 86400, 0), time(), 0);
                        break;
                }
                $channel_type = $data['from'];
                /** @var OtherOrderServices $OtherOrderServices */                $OtherOrderServices = app()->make(OtherOrderServices::class);
                $storeOrderCreateService = app()->make(StoreOrderCreateServices::class);
                $record_data['uid'] = $user_info->uid;
                $record_data['member_code'] = $card_info->card_number;
                $record_data['use_day'] = $batch_info->use_day;
                $record_data['overdue_time'] = $overdue_time;
                $record_data['order_id'] = $storeOrderCreateService->getNewOrderId();
                $record_data['channel_type'] = $channel_type;
                $record_data['member_type'] = "free";
                $record_data['vip_day'] = $batch_info->use_day;
                $record_data['type'] = 2;
                $record_data['paid'] = 1;
                $record_data['pay_time'] = time();
                $res3 = $OtherOrderServices->addOtherOrderData($record_data);
                //if ($res3) $res4 = $userServices->update($user_info->uid, ['level' => 1, 'overdue_time' => $overdue_time, 'is_permanent' => 0], 'uid');
                /** @var UserServices $userServices */                $userServices = app()->make(UserServices::class);
                $res4 = $userServices->setMemberOverdueTime($batch_info->use_day, $user_info->uid, 2, $record_data['member_type']);
                $res5 = $res1 && $res2 && $res3 && $res4;
                return $res5;
            }
        });


    }

    /**  Xác minh xem loại thẻ thành viên này có tồn tại không
     * @param string $member_type
     * @return bool
     */    public function checkmemberType(string $member_type)
    {
        $member_type_arr = $this->getMemberTypeInfo();
        if (!array_key_exists($member_type, $member_type_arr)) throw new ApiException('Hiện chưa có thẻ thành viên loại này');
        return true;
    }

    /** Nhận lợi ích thành viên và hướng dẫn cấu hình
     * @return array
     */    public function getMemberRightsInfo()
    {
        /** @var MemberRightServices $memberRightService */        $memberRightService = app()->make(MemberRightServices::class);
        $memberRight = $memberRightService->getSearchList(['status' => 1]);
        if ($memberRight['list']) {
            foreach ($memberRight['list'] as $k => &$v) {
                $v['title'] = $v['show_title'];
                $v['pic'] = $v['image'];
                $v['right'] = $v['explain'];
                if ($v['right_type'] == 'offline') $v['explain'] = 'Thanh toán ngoại tuyến' . floatval(bcdiv((string)$v['number'], '10', 1)) . 'nếp gấp';
                if ($v['right_type'] == 'sign') $v['explain'] = 'Đăng nhập và nhận thêm phần thưởng' . (int)$v['number'] . 'lần điểm';
                if ($v['right_type'] == 'express') $v['explain'] = 'Cước vận chuyển' . floatval(bcdiv((string)$v['number'], '10', 1)) . 'nếp gấp';
                if ($v['right_type'] == 'integral') $v['explain'] = 'Hoàn lại nhiều tiền hơn' . (int)$v['number'] . 'lần điểm';
            }
        }

        return ['member_right' => $memberRight['list']];
    }

    /**Nhận cấu hình thẻ thành viên
     * @return array
     */    public function getMemberTypeInfo()
    {
        /** @var SystemConfigService $systemConfigService */        $systemConfigService = app()->make(SystemConfigService::class);
        $data = [];
        foreach (self::$_memberTypePrefix as $v) {
            $data[$v] = $systemConfigService::more([$v . '_title', $v . '_vip_day', $v . '_pre_price', $v . '_price']);
        }
        return $data;
    }

    /**Xử lý dữ liệu thẻ thành viên
     * @return array
     */    public function DoMemberType()
    {
        $data = array();
        /** @var MemberShipServices $memberShipService */        $memberShipService = app()->make(MemberShipServices::class);
        $list = $memberShipService->getApiList(['is_del' => 0]);
        foreach ($list as $v) {
            $data[] = [
                'mc_id' => $v['id'],
                'title' => $v['title'],
                'type' => $v['type'],
                'vip_day' => $v['vip_day'],
                'pre_price' => $v['pre_price'],
                'price' => $v['price'],
            ];
        }
        return $data;
    }

    /**Dữ liệu loại thành viên
     * @return bool
     */    public function getMemberTypeValue()
    {
        $member_type = $this->DoMemberType();
        if (!$member_type) return false;
        $new_member_data = [];
        foreach ($member_type as $k => $v) {
            $new_member_data[$v['mc_id']] = $v;
        }
        return $new_member_data;
    }

    /**Xuất thẻ thành viên
     * @param $where
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getExportData($where)
    {
        $data = $this->dao->getSearchList($where);
        /** @var UserServices $userService */        $userService = app()->make(UserServices::class);
        /** @var MemberCardBatchServices $batchService */        $batchService = app()->make(MemberCardBatchServices::class);
        foreach ($data as $k => $v) {
            $data[$k]['use_time'] = $v['use_time'] != 0 ? date('Y-m-d H:i:s', $v['use_time']) : "";
            $data[$k]['user_name'] = '';
            $data[$k]['user_phone'] = '';
            if ($v['use_uid'] != 0) {
                $userInfo = $userService->get($v['use_uid']);
                $data[$k]['user_name'] = $userInfo['nickname'] ?: $userInfo['account'];
                $data[$k]['user_phone'] = $userInfo['phone'];
            }
        }
        $batchInfo = $batchService->getOne($where['batch_card_id']);
        $dataArray['title'] = $batchInfo ? $batchInfo['title'] : "";
        $dataArray['data'] = $data;
        return $dataArray;
    }

    /**Nhận hồ sơ thành viên
     * @param array $where
     * @return array
     */    public function getSearchRecordList(array $where)
    {
        /** @var OtherOrderServices $otherOrderSevice */        $otherOrderSevice = app()->make(OtherOrderServices::class);
        return $otherOrderSevice->getMemberRecord($where);
    }

    /**
     * Kiểm tra xem chức năng thành viên có được bật hay không
     * @param string $rightType
     * @param bool $get_number
     * @return bool|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function isOpenMemberCard(string $rightType = '', bool $get_number = true)
    {
        $isOpen = sys_config('member_card_status', 1);
        //Nếu danh mục vốn chủ sở hữu được thông qua, hãy kiểm tra xem bạn có vốn chủ sở hữu nhất định không
        if (!$rightType) {
            if ($isOpen) return true;
            return false;
        } else {
            /** @var MemberRightServices $memberRightService */            $memberRightService = app()->make(MemberRightServices::class);
            $memberRight = $memberRightService->getOne(['right_type' => $rightType], 'status,number');
            if ($isOpen && $memberRight && $memberRight['status']) {
                if ($get_number) {
                    $number = $memberRight['number'];
                    if (!$number) return false;
                    return $number;
                }
                return true;
            }
            return false;
        }

    }

    /**
     * Sửa đổi trạng thái thẻ thành viên
     * @param $id
     * @param $status
     * @return bool
     */    public function setStatus($id, $status)
    {
        $card_batch_id = $this->dao->value(['id' => $id], 'card_batch_id');
        $card_batch_status = app()->make(MemberCardBatchServices::class)->value(['id' => $card_batch_id], 'status');
        if ($card_batch_status == 0) {
            throw new AdminException('Lô không được kích hoạt và không thể sử dụng tạm thời.');
        }
        $res = $this->dao->update($id, ['status' => $status]);
        if ($res) return true;
        return false;
    }
}
