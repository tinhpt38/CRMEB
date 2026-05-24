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

use app\dao\user\UserExtractDao;
use app\services\BaseServices;
use app\services\order\StoreOrderCreateServices;
use app\services\statistic\CapitalFlowServices;
use app\services\system\admin\SystemAdminServices;
use app\services\wechat\WechatUserServices;
use crmeb\exceptions\AdminException;
use crmeb\exceptions\ApiException;
use crmeb\services\AliPayService;
use crmeb\services\FormBuilder as Form;
use crmeb\services\app\WechatService;
use crmeb\services\pay\Pay;
use crmeb\services\wechat\Payment;
use crmeb\services\workerman\ChannelService;
use EasyWeChat\Payment\Order;
use think\exception\ValidateException;
use think\facade\Route as Url;

/**
 *
 * Class UserExtractServices
 * @package app\services\user
 */class UserExtractServices extends BaseServices
{

    /**
     * UserExtractServices constructor.
     * @param UserExtractDao $dao
     */    public function __construct(UserExtractDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Nhận hồ sơ rút tiền
     * @param int $id
     * @param array $field
     * @return array|\think\Model|null
     */    public function getExtract(int $id, array $field = [])
    {
        return $this->dao->get($id, $field);
    }

    /**
     * Nhận tổng số tiền rút của Khách hàng
     * @param int $uid
     * @return float
     */    public function getUserExtract(int $uid)
    {
        return $this->dao->getWhereSum(['uid' => $uid, 'status' => 1]);
    }

    /**
     * Nhận danh sách tổng số tiền rút cho một số Khách hàng nhất định
     * @param array $uids
     */    public function getUsersSumList(array $uids)
    {
        return $this->dao->getWhereSumList(['uid' => $uids, 'status' => 1]);
    }

    public function getCount(array $where = [])
    {
        return $this->dao->getCount($where);
    }

    /**
     * Nhận danh sách rút tiền
     * @param array $where
     * @param string $field
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getUserExtractList(array $where, string $field = '*')
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getExtractList($where, $field, $page, $limit);
        foreach ($list as &$item) {
            $item['nickname'] = $item['user']['nickname'] ?? '';
            $item['receive_price'] = bcsub((string)$item['extract_price'], (string)$item['extract_fee'], 2);
        }
        $count = $this->dao->count($where);
        return compact('list', 'count');
    }

    /**
     * Nhận tổng số tiền rút
     * @param array $where
     */    public function getExtractSum(array $where)
    {
        return $this->dao->getExtractMoneyByWhere($where, 'extract_price');
    }

    /**
     * Từ chối yêu cầu rút tiền
     * @param $id
     * @param $fail_msg
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */    public function changeFail(int $id, $userExtract, $message)
    {
        $fail_time = time();
        $extract_number = $userExtract['extract_price'];
        $mark = 'Rút tiền không thành công,Hoa hồng trả lại' . $extract_number . 'Nhân dân tệ';
        $uid = $userExtract['uid'];
        $status = -1;
        /** @var UserServices $userServices */        $userServices = app()->make(UserServices::class);
        $user = $userServices->getUserInfo($uid);
        $this->transaction(function () use ($user, $uid, $id, $extract_number, $message, $userServices, $status, $fail_time) {
            //Tăng kỷ lục hoa hồng
            /** @var UserBrokerageServices $userBrokerageServices */            $userBrokerageServices = app()->make(UserBrokerageServices::class);
            $now_brokerage = bcadd((string)$user['brokerage_price'], (string)$extract_number, 2);
            $userBrokerageServices->income('extract_fail', $uid, $extract_number, $now_brokerage, $id);
            if (!$userServices->update($uid, ['brokerage_price' => bcadd((string)$user['brokerage_price'], (string)$extract_number, 2)], 'uid'))
                throw new AdminException('Không thể tăng hoa hồng cho Khách hàng');
            if (!$this->dao->update($id, ['fail_time' => $fail_time, 'fail_msg' => $message, 'status' => $status])) {
                throw new AdminException('Sửa đổi không thành công');
            }
        });

        event('NoticeListener', [['uid' => $uid, 'userType' => strtolower($user['user_type']), 'extract_number' => $extract_number, 'nickname' => $user['nickname'], 'message' => $message], 'user_balance_change']);

        //Thông báo tùy chỉnh - việc rút tiền của Khách hàng không thành công
        $userExtract['nickname'] = $user['nickname'];
        $userExtract['message'] = $message;
        $userExtract['time'] = date('Y-m-d H:i:s');
        $userExtract['price'] = $extract_number;
        $userExtract['phone'] = app()->make(UserServices::class)->value($userExtract['uid'], 'phone');
        event('CustomNoticeListener', [$userExtract['uid'], $userExtract, 'extract_fail']);

        //Sự kiện tùy chỉnh - lỗi rút tiền của Khách hàng
        event('CustomEventListener', ['admin_extract_fail', [
            'uid' => $userExtract['uid'],
            'price' => $userExtract['price'],
            'pay_type' => $userExtract['extract_type'],
            'nickname' => $userExtract['price'],
            'phone' => $userExtract['phone'],
            'fail_time' => date('Y-m-d H:i:s')
        ]]);

        return true;
    }

    /**
     * Áp dụng thông qua rút tiền
     * @param int $id
     * @param $userExtract
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function changeSuccess(int $id, $userExtract)
    {
        $extractNumber = bcsub($userExtract['extract_price'], $userExtract['extract_fee'], 2);
        /** @var WechatUserServices $wechatServices */        $wechatServices = app()->make(WechatUserServices::class);
        /** @var UserServices $userServices */        $userServices = app()->make(UserServices::class);
        $userType = $userServices->value(['uid' => $userExtract['uid']], 'user_type');
        $nickname = $userServices->value(['uid' => $userExtract['uid']], 'nickname');
        $phone = $userServices->value(['uid' => $userExtract['uid']], 'phone');
        $order_id = $userExtract['wechat_order_id'] != '' ? $userExtract['wechat_order_id'] : app()->make(StoreOrderCreateServices::class)->getNewOrderId('tx');
        $insertData = ['wechat_order_id' => $order_id, 'nickname' => $nickname, 'phone' => $phone];

        //WeChat tự động rút tiền mặt để đổi
        if (sys_config('weixin_extract_type', 0) && $userExtract['extract_type'] == 'weixin') {
            $type = '';
            $openid = $wechatServices->uidToOpenid($userExtract['uid'], $userExtract['channel_type']);
            if ($userExtract['channel_type'] == 'wechat') {
                $type = Order::JSAPI;
            } elseif ($userExtract['channel_type'] == 'routine') {
                $type = 'mini';
            } elseif ($userExtract['channel_type'] == 'app') {
                $type = Order::APP;
            }
            if (!$openid) {
                $openid = $wechatServices->uidToOpenid($userExtract['uid'], 'wechat');
                $type = Order::JSAPI;
            }
            if (!$openid) {
                $openid = $wechatServices->uidToOpenid($userExtract['uid'], 'routine');
                $type = 'mini';
            }
            if (!$openid) {
                $openid = $wechatServices->uidToOpenid((int)$userExtract['uid'], 'app');
                $type = Order::APP;
            }
            if (!$openid) {
                throw new ValidateException('Người dùng này hiện không hỗ trợ chuyển tự động để thay đổi, vui lòng chuyển thủ công');
            }
            //v3Chuyển nhượng thương gia
            if (sys_config('pay_wechat_type')) {
                $pay = new Pay('v3_wechat_pay');
                if (sys_config('v3_pay_public_key') != '') {
                    $res = $pay->merchantPayNew(
                        $type,
                        $order_id,
                        sys_config('v3_transfer_scene_id', '1000'),
                        $openid,
                        $userExtract['real_name'],
                        bcmul($extractNumber, '100', 0),
                        'Rút tiền hoa hồng để thay đổi',
                        sys_config('site_url') . '/api/transfer/notify/' . $type,
                        'tiền công lao động',
                        [
                            [
                                'info_type' => 'Loại vị trí',
                                'info_content' => 'Phần thưởng của người quảng bá'
                            ],
                            [
                                'info_type' => 'Mô tả thù lao',
                                'info_content' => 'Rút thưởng lệnh khuyến mại'
                            ],
                        ]
                    );
                    $this->dao->update($id, [
                        'out_bill_no' => $res['out_bill_no'] ?? '',
                        'package_info' => $res['package_info'] ?? '',
                        'state' => $res['state'] ?? '',
                        'transfer_bill_no' => $res['transfer_bill_no'] ?? '',
                        'fail_reason' => $res['fail_reason'] ?? '',
                        'status' => 1
                    ]);
                    event('NoticeListener', [['uid' => $userExtract['uid'], 'order_id' => $order_id, 'extractNumber' => $extractNumber, 'type' => 1], 'revenue_received']);
                    return 'v3_extract';
                } else {
                    $res = $pay->merchantPay($openid, $order_id, $extractNumber, [
                        'type' => $type,
                        'batch_name' => 'Rút tiền hoa hồng để thay đổi',
                        'batch_remark' => 'Bạn đang ở' . date('Y-m-d H:i:s') . 'Rút tiền mặt.' . $extractNumber . 'Nhân dân tệ'
                    ]);
                    $this->dao->update($id, ['wechat_order_id' => $order_id]);
                }

            } else {
                // Rút tiền mặt WeChat
                $res = WechatService::merchantPay($openid, $order_id, (string)$extractNumber, 'Rút tiền hoa hồng để thay đổi');
            }

            if (!$res) {
                throw new ApiException('Thanh toán doanh nghiệp không nhận được thay đổi, vui lòng thử lại sau.');
            }
        }
        if (sys_config('alipay_extract_type', 0) && $userExtract['extract_type'] == 'alipay') {
            // Xây dựng thông số rút tiền Alipay
            $alipaySignType = sys_config('alipay_sign_type');
            if ($alipaySignType == 0) {
                $bizParams = [
                    'payee_type' => 'ALIPAY_LOGONID', // Loại tài khoản người nhận thanh toán, tài khoản đăng nhập ALIPAY_LOGONID-Alipay
                    'payee_account' => $userExtract['real_name'], // Tài khoản người nhận thanh toán, tài khoản Alipay được xác thực bằng tên thật
                    'amount' => $extractNumber, // Số tiền rút
                    'payer_show_name' => sys_config('site_name'), // Tên người trả tiền/tên cá nhân
                    'payee_real_name' => $userExtract['user_name'], // Tên thật/tên cá nhân của người nhận thanh toán
                    'remark' => 'Rút tiền mặt ' . format_vnd($extractNumber) . ' đến Alipay', // Ghi chú kinh doanh
                ];
            } else {
                $bizParams = [
                    'out_biz_no' => $order_id, // Số đơn đặt hàng của người bán
                    'trans_amount' => $extractNumber,
                    'biz_scene' => 'DIRECT_TRANSFER',
                    'product_code' => 'TRANS_ACCOUNT_NO_PWD',
                    'order_title' => sys_config('site_name') . 'Rút tiền mặt',
                    'payee_info' => [
                        'identity' => $userExtract['alipay_code'],
                        'identity_type' => 'ALIPAY_LOGON_ID',
                        'name' => $userExtract['user_name'],
                    ],
                    'remark' => 'Rút tiền mặt ' . format_vnd($extractNumber) . ' đến Alipay', // Ghi chú kinh doanh
                ];
            }
            // Gọi dịch vụ Alipay để bắt đầu yêu cầu rút tiền Alipay
            $res = AliPayService::instance()->merchantPay($bizParams, $alipaySignType);
            // Nếu yêu cầu rút tiền Alipay không thành công, một ngoại lệ sẽ được đưa ra.
            if (!$res) {
                throw new ApiException('Rút tiền không thành công, vui lòng kiểm tra nhật ký.！');
            }
        }

        /** @var UserServices $userService */        $userService = app()->make(UserServices::class);
        $user = $userService->getUserInfo($userExtract['uid']);
        $insertData['nickname'] = $user['nickname'];
        $insertData['phone'] = $user['phone'];

        /** @var CapitalFlowServices $capitalFlowServices */        $capitalFlowServices = app()->make(CapitalFlowServices::class);
        $capitalFlowServices->setFlow([
            'order_id' => $order_id,
            'uid' => $userExtract['uid'],
            'price' => bcmul('-1', $extractNumber, 2),
            'pay_type' => $userExtract['extract_type'],
            'nickname' => $insertData['nickname'],
            'phone' => $insertData['phone']
        ], 'extract');

        if (!$this->dao->update($id, ['status' => 1])) {
            throw new AdminException('Sửa đổi không thành công');
        }
        event('NoticeListener', [['uid' => $userExtract['uid'], 'userType' => strtolower($userType), 'extractNumber' => $extractNumber, 'nickname' => $nickname], 'user_extract']);

        //Thông báo tùy chỉnh-Khách hàng rút tiền thành công
        $userExtract['nickname'] = $nickname;
        $userExtract['phone'] = $phone;
        $userExtract['time'] = date('Y-m-d H:i:s');
        $userExtract['price'] = $extractNumber;
        event('CustomNoticeListener', [$userExtract['uid'], $userExtract, 'extract_success']);

        //Người dùng sự kiện tùy chỉnh rút tiền thành công
        event('CustomEventListener', ['admin_extract_success', [
            'uid' => $userExtract['uid'],
            'price' => $extractNumber,
            'pay_type' => $userExtract['extract_type'],
            'nickname' => $insertData['nickname'],
            'phone' => $phone,
            'success_time' => date('Y-m-d H:i:s')
        ]]);

        return true;
    }

    /**
     * Hiển thị danh sách tài nguyên
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function index(array $where)
    {
        $list = $this->getUserExtractList($where);
        /** @var UserServices $userServices */        $userServices = app()->make(UserServices::class);
        //Số tiền mặt cần rút
        $where['status'] = 0;
        $extract_statistics['price'] = $this->getExtractSum($where);
        //Số tiền đã rút
        $where['status'] = 1;
        $extract_statistics['priced'] = $this->getExtractSum($where);
        /** @var UserBrokerageServices $userBrokerageServices */        $userBrokerageServices = app()->make(UserBrokerageServices::class);
        $where['pm'] = 1;
        $brokerage_count = $userBrokerageServices->getUsersBokerageSum($where);
        $refund_brokerage = $userBrokerageServices->sum(['type' => 'refund'], 'number');
        $extract_statistics['brokerage_count'] = bcsub((string)$brokerage_count, (string)$refund_brokerage, 2);
        //Số tiền mặt chưa rút
        $extract_statistics['brokerage_not'] = $extract_statistics['brokerage_count'] > $extract_statistics['priced'] ? bcsub((string)$extract_statistics['brokerage_count'], (string)$extract_statistics['priced'], 2) : 0.00;
        return compact('extract_statistics', 'list');
    }

    /**
     * Hiển thị trang biểu mẫu tài nguyên chỉnh sửa.
     *
     * @param int $id
     * @return \think\Response
     */    public function edit(int $id)
    {
        $UserExtract = $this->getExtract($id);
        if (!$UserExtract) {
            throw new AdminException('Dữ liệu không tồn tại');
        }
        $f = array();
        $f[] = Form::input('real_name', 'Tên', $UserExtract['real_name']);
        $f[] = Form::number('extract_price', 'Số tiền rút', (float)$UserExtract['extract_price'])->precision(2)->disabled(true);
        if ($UserExtract['extract_type'] == 'alipay') {
            $f[] = Form::input('alipay_code', 'tài khoản Alipay', $UserExtract['alipay_code']);
        } else if ($UserExtract['extract_type'] == 'weixin') {
            $f[] = Form::input('wechat', 'ID WeChat', $UserExtract['wechat']);
        } else if ($UserExtract['extract_type'] == 'balance') {
        } else {
            $f[] = Form::input('bank_code', 'Số thẻ ngân hàng', $UserExtract['bank_code']);
            $f[] = Form::input('bank_address', 'Ngân hàng mở tài khoản', $UserExtract['bank_address']);
        }
        $f[] = Form::input('mark', 'Nhận xét', $UserExtract['mark'])->type('textarea');
        return create_form('Sửa', $f, Url::buildUrl('/finance/extract/' . $id), 'PUT');
    }

    public function update(int $id, array $data)
    {
        if (!$this->dao->update($id, $data))
            throw new AdminException('Sửa đổi không thành công');
        else
            return true;
    }

    /**
     * từ chối
     * @param $id
     * @return mixed
     */    public function refuse(int $id, string $message)
    {
        $extract = $this->getExtract($id);
        if (!$extract) {
            throw new AdminException('Dữ liệu không tồn tại');
        }
        if ($extract->status == 1) {
            throw new AdminException('Đã rút');
        }
        if ($extract->status == -1) {
            throw new AdminException('Yêu cầu rút tiền của bạn đã bị từ chối');
        }
        $res = $this->changeFail($id, $extract, $message);
        if ($res) {
            return true;
        } else {
            throw new AdminException('Thao tác không thành công');
        }
    }

    /**
     * vượt qua
     * @param int $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function adopt(int $id)
    {
        $extract = $this->getExtract($id);
        if (!$extract) {
            throw new AdminException('Dữ liệu không tồn tại');
        }
        if ($extract->status == 1) {
            throw new AdminException('Đã rút');
        }
        if ($extract->status == -1) {
            throw new AdminException('Yêu cầu rút tiền của bạn đã bị từ chối');
        }
        $res = $this->changeSuccess($id, $extract);
        if ($res) {
            return $res;
        } else {
            throw new AdminException('Thao tác không thành công');
        }
    }

    /**Số tiền cần rút
     * @return int
     */    public function userExtractCount()
    {
        return $this->dao->count(['status' => 0]);
    }

    /**
     * Rút tiền mặt thẻ ngân hàng
     * @param int $uid
     * @return mixed
     */    public function bank(int $uid)
    {
        /** @var UserServices $userService */        $userService = app()->make(UserServices::class);
        $user = $userService->getUserInfo($uid, 'brokerage_price,uid');
        if (!$user) {
            throw new ApiException('Dữ liệu không tồn tại');
        }
        /** @var UserBrokerageServices $services */        $services = app()->make(UserBrokerageServices::class);
        $data['broken_commission'] = $services->getUserFrozenPrice($uid);
        if ($data['broken_commission'] < 0)
            $data['broken_commission'] = '0';
        $data['brokerage_price'] = $user['brokerage_price'];
        //Hoa hồng có thể được rút
        $data['commissionCount'] = bcsub((string)$data['brokerage_price'], (string)$data['broken_commission'], 2);
        $extractBank = sys_config('user_extract_bank') ?? []; //Ngân hàng rút tiền
        $extractBank = str_replace("\r\n", "\n", $extractBank);//Ngăn chặn sự không tương thích
        $data['extractBank'] = explode("\n", is_array($extractBank) ? ($extractBank[0] ?? $extractBank) : $extractBank);
        $data['minPrice'] = sys_config('user_extract_min_price');//Số tiền tối thiểu để rút
        $data['weixinExtractType'] = (int)sys_config('weixin_extract_type', 0);//Phương thức thanh toán WeChat
        $data['alipayExtractType'] = (int)sys_config('alipay_extract_type', 0);//Phương thức thanh toán Alipay
        $data['withdrawal_fee'] = sys_config('withdrawal_fee', 0);//Phí rút tiền
        return $data;
    }

    /**
     * Yêu cầu rút tiền
     * @param int $uid
     * @param array $data
     */    public function cash(int $uid, array $data)
    {
        /** @var UserServices $userService */        $userService = app()->make(UserServices::class);
        $user = $userService->getUserInfo($uid);
        if (!$user) {
            throw new ApiException('Dữ liệu không tồn tại');
        }

        if ($data['extract_type'] == 'weixin' && !sys_config('weixin_extract_type', 0) && !$data['weixin']) {
            throw new ApiException('Vui lòng nhập tài khoản WeChat của bạn');
        }

        if ($data['extract_type'] == 'weixin' && bccomp($data['money'], '0.1', 2) < 0) {
            throw new ApiException('Số tiền rút tối thiểu trên WeChat không được nhỏ hơn 0,1 nhân dân tệ');
        }

        /** @var WechatUserServices $wechatServices */        $wechatServices = app()->make(WechatUserServices::class);
        $openid = $wechatServices->uidToOpenid($uid, 'wechat');
        if (!$openid) $openid = $wechatServices->uidToOpenid($uid, 'routine');

        if ($data['extract_type'] == 'weixin' && sys_config('weixin_extract_type', 0) && !$openid) {
            throw new ApiException('Vui lòng theo dõi tài khoản công khai trước');
        }

        /** @var UserBrokerageServices $services */        $services = app()->make(UserBrokerageServices::class);
        $data['broken_commission'] = $services->getUserFrozenPrice($uid);
        if ($data['broken_commission'] < 0)
            $data['broken_commission'] = 0;
        $data['brokerage_price'] = $user['brokerage_price'];
        //Hoa hồng có thể được rút
        $commissionCount = bcsub((string)$data['brokerage_price'], (string)$data['broken_commission'], 2);
        if ($data['money'] > $commissionCount) {
            throw new ApiException('Không đủ hoa hồng để rút tiền');
        }

        $extractPrice = $user['brokerage_price'];
        $userExtractMinPrice = sys_config('user_extract_min_price');
        if ($data['money'] < $userExtractMinPrice) {
            throw new ApiException('Số tiền rút không thể ít hơn{:money}Nhân dân tệ', ['money' => $userExtractMinPrice]);
        }
        if ($extractPrice < 0) {
            throw new ApiException('Hoa hồng rút tiền không đủ{:money}Nhân dân tệ', ['money' => $data['money']]);
        }
        if ($data['money'] > $extractPrice) {
            throw new ApiException('Hoa hồng rút tiền không đủ{:money}Nhân dân tệ', ['money' => $data['money']]);
        }
        if ($data['money'] <= 0) {
            throw new ApiException('Hoa hồng rút tiền lớn hơn0');
        }
        $data['extract_price'] = bcmul($data['money'], '1', 2);
        $insertData = [
            'wechat_order_id' => app()->make(StoreOrderCreateServices::class)->getNewOrderId('tx'),
            'uid' => $user['uid'],
            'extract_type' => $data['extract_type'],
            'extract_price' => $data['extract_price'],
            'extract_fee' => bcmul((string)$data['extract_price'], bcdiv((string)sys_config('withdrawal_fee', '0'), '100', 4), 2),
            'add_time' => time(),
            'balance' => $user['brokerage_price'],
            'status' => 0,
            'channel_type' => $data['channel_type']
        ];
        if (isset($data['name']) && strlen(trim($data['name']))) $insertData['real_name'] = $data['name'];
        else $insertData['real_name'] = $user['nickname'];
        if (isset($data['cardnum'])) $insertData['bank_code'] = $data['cardnum'];
        else $insertData['bank_code'] = '';
        if (isset($data['bankname'])) $insertData['bank_address'] = $data['bankname'];
        else $insertData['bank_address'] = '';
        if (isset($data['weixin'])) $insertData['wechat'] = $data['weixin'];
        else $insertData['wechat'] = $user['nickname'];
        $mark = '';
        $feeMark = sys_config('withdrawal_fee', 0) == 0 ? '' : '，phí xử lý' . $insertData['extract_fee'] . 'Nhân dân tệ';
        if ($data['extract_type'] == 'alipay') {
            $insertData['alipay_code'] = $data['alipay_code'];
            $insertData['qrcode_url'] = $data['qrcode_url'];
            $insertData['user_name'] = $data['user_name'];
            $insertData['real_name'] = $data['user_name'];
            $mark = 'Rút tiền bằng Alipay' . $insertData['extract_price'] . 'Nhân dân tệ' . $feeMark;
        } else if ($data['extract_type'] == 'bank') {
            $mark = 'Sử dụng thẻ UnionPay' . $insertData['bank_code'] . 'Rút tiền mặt' . $insertData['extract_price'] . 'Nhân dân tệ' . $feeMark;
        } else if ($data['extract_type'] == 'weixin') {
            $insertData['user_name'] = $data['user_name'];
            $insertData['real_name'] = $data['user_name'];
            $insertData['qrcode_url'] = $data['qrcode_url'];
            $mark = 'Rút tiền bằng WeChat' . $insertData['extract_price'] . 'Nhân dân tệ' . $feeMark;
            if (sys_config('weixin_extract_type', 0) && $openid) {
                if ($data['extract_price'] < 0.1) {
                    throw new ApiException('Số tiền tối thiểu để thanh toán WeChat dành cho doanh nghiệp thay đổi là 1 nhân dân tệ');
                }
            }
        }
        $res1 = $this->transaction(function () use ($insertData, $data, $uid, $userService, $user, $mark) {
            if (!$res1 = $this->dao->save($insertData)) {
                throw new ApiException('Không thể đăng ký rút tiền');
            }
            $balance = bcsub((string)$user['brokerage_price'], $data['extract_price'], 2) ?? 0;
            if (!$userService->update($uid, ['brokerage_price' => $balance], 'uid')) {
                throw new ApiException('Không thể đăng ký rút tiền');
            }

            //Lưu giữ hồ sơ hoa hồng
            /** @var UserBrokerageServices $userBrokerageServices */            $userBrokerageServices = app()->make(UserBrokerageServices::class);
            $userBrokerageServices->income('extract', $uid, ['mark' => $mark, 'number' => $data['extract_price']], $balance, $res1['id']);
            return $res1;
        });

        try {
            ChannelService::instance()->send('WITHDRAW', ['id' => $res1->id]);
        } catch (\Exception $e) {
        }
        /** @var SystemAdminServices $systemAdmin */        $systemAdmin = app()->make(SystemAdminServices::class);
        $systemAdmin->adminNewPush();
        //thông tin
        event('NoticeListener', [['nickname' => $user['nickname'], 'money' => $data['extract_price']], 'kefu_send_extract_application']);

        //Sự kiện tùy chỉnh - rút tiền của Khách hàng
        event('CustomEventListener', ['user_extract', [
            'uid' => $insertData['uid'],
            'phone' => $user['phone'],
            'extract_type' => $insertData['extract_type'],
            'extract_price' => $insertData['extract_price'],
            'extract_fee' => $insertData['extract_fee'],
            'extract_time' => date('Y-m-d H:i:s'),
        ]]);

        return true;
    }

    /**
     * @param array $where
     * @param string $SumField
     * @param string $selectType
     * @param string $group
     * @return float|mixed
     */    public function getOutMoneyByWhere(array $where, string $SumField, string $selectType, string $group = "")
    {
        switch ($selectType) {
            case "sum" :
                return $this->dao->getWhereSumField($where, $SumField);
            case "group" :
                return $this->dao->getGroupField($where, $SumField, $group);
        }
    }
}
