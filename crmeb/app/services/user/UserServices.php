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

namespace app\services\user;

use app\jobs\UserJob;
use app\services\activity\bargain\StoreBargainServices;
use app\services\activity\combination\StoreCombinationServices;
use app\services\activity\seckill\StoreSeckillServices;
use app\services\agent\AgentLevelServices;
use app\services\agent\SpreadApplyServices;
use app\services\BaseServices;
use app\dao\user\UserDao;
use app\services\activity\coupon\StoreCouponUserServices;
use app\services\diy\DiyServices;
use app\services\kefu\service\StoreServiceRecordServices;
use app\services\kefu\service\StoreServiceServices;
use app\services\order\OtherOrderServices;
use app\services\order\StoreOrderCreateServices;
use app\services\order\StoreOrderServices;
use app\services\order\StoreOrderTakeServices;
use app\services\other\QrcodeServices;
use app\services\product\product\StoreProductLogServices;
use app\services\product\product\StoreProductRelationServices;
use app\services\message\MessageSystemServices;
use app\services\system\SystemUserLevelServices;
use app\services\user\member\MemberCardServices;
use app\services\wechat\WechatUserServices;
use crmeb\exceptions\AdminException;
use crmeb\exceptions\ApiException;
use crmeb\services\CacheService;
use crmeb\services\FormBuilder as Form;
use crmeb\services\FormBuilder;
use crmeb\services\app\WechatService;
use think\Exception;
use think\facade\Route as Url;

/**
 * Class UserServices
 * @package app\services\user
 * @method array getUserInfoArray(array $where, string $field, string $key) Thông tin người dùng tương ứng với truy vấn theo điều kiện được trả về dưới dạng mảng.
 * @method update($id, array $data, ?string $key = null) Sửa đổi dữ liệu
 * @method get($id, ?array $field = [], ?array $with = []) Lấy một phần dữ liệu
 * @method count(array $where) Lấy số lượng theo điều kiện quy định
 * @method value(array $where, string $field) Nhận giá trị khóa được chỉ định
 * @method bcInc($key, string $incField, string $inc, string $keyField = null, int $acc = 2) Phép cộng có độ chính xác cao
 * @method bcDec($key, string $incField, string $inc, string $keyField = null, int $acc = 2) Phép trừ có độ chính xác cao
 * @method getTrendData($time, $type, $timeType)
 * @method incPayCount(int $uid) Số lượng người dùng thanh toán thành công tăng lên
 * @mixin UserDao
 */
class UserServices extends BaseServices
{

    /**
     * UserServices constructor.
     * @param UserDao $dao
     */
    public function __construct(UserDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Lấy thông tin người dùng
     * @param int $uid
     * @param string $field
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2023/03/01
     */
    public function getUserInfo(int $uid, $field = '*')
    {
        if (is_string($field)) $field = explode(',', $field);
        return $this->dao->get($uid, $field);
    }

    /**
     * Lấy danh sách người dùng
     * @param array $where
     * @param string $field
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getUserList(array $where, string $field): array
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getList($where, $field, $page, $limit);
        $count = $this->getCount($where);
        return compact('list', 'count');
    }

    /**
     * Số mục danh sách
     * @param array $where
     * @return int
     */
    public function getCount(array $where, bool $is_list = false)
    {
        return $this->dao->getCount($where, $is_list);
    }

    /**
     * Lưu thông tin người dùng
     * @param $user
     * @param int $spreadUid
     * @param string $userType
     * @return User|\think\Model
     * @throws Exception
     */
    public function setUserInfo($user, int $spreadUid = 0, string $userType = 'wechat')
    {
        $data = [
            'account' => $user['account'] ?? 'wx' . rand(1, 9999) . time(),
            'pwd' => $user['pwd'] ?? md5('123456'),
            'nickname' => $user['nickname'] ?? '',
            'avatar' => $user['headimgurl'] ?? '',
            'phone' => $user['phone'] ?? '',
            'add_time' => time(),
            'add_ip' => app()->request->ip(),
            'last_time' => time(),
            'last_ip' => app()->request->ip(),
            'user_type' => $userType,
            'staff_id' => $user['staff_id'] ?? 0,
            'agent_id' => $user['agent_id'] ?? 0,
            'division_id' => $user['division_id'] ?? 0,
        ];
        if ($spreadUid) {
            $data['spread_uid'] = $spreadUid;
            $data['spread_time'] = time();
        }
        $res = $this->dao->save($data);
        if (!$res)
            throw new AdminException('Không lưu được thông tin người dùng');

        //Phần thưởng đăng ký người dùng mới
        $this->rewardNewUser((int)$res->uid);

        //Sự kiện bài đăng do người dùng tạo
        event('UserRegisterListener', [$spreadUid, $userType, $user['nickname'], $res->uid, 1]);

        //Đăng ký người dùng sự kiện tùy chỉnh
        event('CustomEventListener', ['user_register', [
            'uid' => $res->uid,
            'nickname' => $user['nickname'],
            'phone' => $data['phone'],
            'add_time' => date('Y-m-d H:i:s'),
            'user_type' => $userType,
        ]]);

        if ($spreadUid) {
            //tin nhắn đẩy
            event('NoticeListener', [['spreadUid' => $spreadUid, 'user_type' => $userType, 'nickname' => $user['nickname']], 'bind_spread_uid']);

            //Mối quan hệ ràng buộc sự kiện tùy chỉnh
            event('CustomEventListener', ['user_spread', [
                'uid' => $res->uid,
                'nickname' => $user['nickname'],
                'spread_uid' => $spreadUid,
                'spread_time' => date('Y-m-d H:i:s'),
                'user_type' => $userType,
            ]]);
        }
        return $res;
    }

    /**
     * Tổng hoa hồng của người dùng cho một số điều kiện nhất định
     * @param array $where
     * @return mixed
     */
    public function getSumBrokerage(array $where)
    {
        return $this->dao->getWhereSumField($where, 'brokerage_price');
    }

    /**
     * Nhận danh sách các trường do người dùng chỉ định dựa trên các điều kiện
     * @param array $where
     * @param string $field
     * @param string $key
     * @return array
     */
    public function getColumn(array $where, string $field = '*', string $key = '')
    {
        return $this->dao->getColumn($where, $field, $key);
    }

    /**
     * Nhận khuyến mãi ngoại tuyến của một người dùng nhất định
     */
    public function getSpreadList($uid)
    {
        $one_uids = $this->dao->getColumn(['spread_uid' => $uid], 'uid');
        $two_uids = $this->dao->getColumn([['spread_uid', 'in', $one_uids], ['spread_uid', '<>', 0]], 'uid');
        $uids = array_merge($one_uids, $two_uids);
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getList(['uid' => $uids], 'uid,nickname,real_name,avatar,add_time', $page, $limit);
        foreach ($list as $k => $user) {
            $list[$k]['type'] = in_array($user['uid'], $one_uids) ? 'Cấp 1' : 'Cấp 2';
            $list[$k]['add_time'] = date('Y-m-d', $user['add_time']);
        }
        $count = count($uids);
        return compact('count', 'list');
    }

    /**Tìm nhiều thông tin uid
     * @param $uids
     * @param bool $field
     * @return UserDao|bool|\crmeb\basic\BaseModel|mixed|\think\Collection
     */
    public function getUserListByUids($uids, $field = false)
    {
        if (!$uids || !is_array($uids)) return false;
        return $this->dao->getUserListByUids($uids, $field);
    }

    /**
     * Nhận người dùng phân phối
     * @param array $where
     * @param string $field
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAgentUserList(array $where = [], string $field = '*', $is_page = true)
    {
        $where_data['status'] = 1;
        $where_data['is_promoter'] = 1;
        $where_data['spread_open'] = 1;
        //Loại bỏ các hạn chế về lĩnh vực nhà phân phối khi phân phối bởi Renren
        $store_brokerage_statu = sys_config('store_brokerage_statu');
        if ($store_brokerage_statu == 2) unset($where_data['is_promoter']);
        if (isset($where['nickname']) && $where['nickname'] !== '') {
            $where_data['like'] = $where['nickname'];
        }
        if (isset($where['data']) && $where['data']) {
            $where_data['time'] = $where['data'];
        }
        [$page, $limit] = $this->getPageValue($is_page);
        $list = $this->dao->getAgentUserList($where_data, $field, $page, $limit);
        $count = $this->dao->count($where_data);
        return compact('count', 'list');
    }

    /**
     * Nhận nhà phân phốiids
     * @param array $where
     * @return array
     * @throws \ReflectionException
     */
    public function getAgentUserIds(array $where)
    {
        $where['status'] = 1;
        if (sys_config('store_brokerage_statu') != 2) $where['is_promoter'] = 1;
        $where['spread_open'] = 1;
        if (isset($where['nickname']) && $where['nickname'] !== '') {
            $where['like'] = $where['nickname'];
            unset($where['nickname']);
        }
        if (isset($where['data']) && $where['data']) {
            $where['time'] = $where['data'];
        }
        return $this->dao->getAgentUserIds($where);
    }

    /**
     * Nhận danh sách các nhà quảng bá
     * @param array $where
     * @param string $field
     * @param int $page
     * @param int $limit
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getSairList(array $where, string $field = '*')
    {
        $where_data = [];
        if (isset($where['uid'])) {
            if (isset($where['type'])) {
                $type = (int)$where['type'];
                $type = in_array($type, [1, 2]) ? $type : 0;
                $uids = $this->getUserSpredadUids((int)$where['uid'], $type);
                $where_data['uid'] = count($uids) > 0 ? $uids : 0;
            }
            if (isset($where['data']) && $where['data']) {
                $where_data['time'] = $where['data'];
            }
            if (isset($where['nickname']) && $where['nickname']) {
                $where_data['like'] = $where['nickname'];
            }
            $where_data['status'] = 1;
        }
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getSairList($where_data, '*', $page, $limit);
        $count = $this->dao->count($where_data);
        return compact('list', 'count');
    }

    /**
     * Nhận số liệu thống kê về người quảng bá
     * @param array $where
     * @param string $field
     * @param int $page
     * @param int $limit
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getSairCount(array $where)
    {
        $where_data = [];
        if (isset($where['uid'])) {
            if (isset($where['type'])) {
                $uids = $this->getColumn(['spread_uid' => $where['uid']], 'uid');
                switch ((int)$where['type']) {
                    case 1:
                        $where_data['uid'] = count($uids) > 0 ? $uids : 0;
                        break;
                    case 2:
                        if (count($uids))
                            $spread_uid_two = $this->dao->getColumn([['spread_uid', 'IN', $uids]], 'uid');
                        else
                            $spread_uid_two = [];
                        $where_data['uid'] = count($spread_uid_two) > 0 ? $spread_uid_two : 0;
                        break;
                    default:
                        if (count($uids)) {
                            if ($spread_uid_two = $this->dao->getColumn([['spread_uid', 'IN', $uids]], 'uid')) {
                                $uids = array_merge($uids, $spread_uid_two);
                                $uids = array_unique($uids);
                                $uids = array_merge($uids);
                            }
                        }
                        $where_data['uid'] = count($uids) > 0 ? $uids : 0;
                        break;
                }
            }
            if (isset($where['data']) && $where['data']) {
                $where_data['time'] = $where['data'];
            }
            if (isset($where['nickname']) && $where['nickname']) {
                $where_data['like'] = $where['nickname'];
            }
            $where_data['status'] = 1;
        }
        return $this->dao->count($where_data);
    }

    /**
     * Viết thông tin người dùng
     * @param array $data
     * @return bool
     */
    public function create(array $data)
    {
        if (!$this->dao->save($data))
            throw new AdminException('Đã lưu thành công');
        return true;
    }

    /**
     * đặt lại mật khẩu
     * @param $id
     * @param string $password
     * @return mixed
     */
    public function resetPwd(int $uid, string $password)
    {
        if (!$this->dao->update($uid, ['pwd' => $password]))
            throw new AdminException('Đặt lại mật khẩu không thành công');
        return true;
    }

    /**
     * Tăng số lượng người quảng bá
     * @param int $uid
     * @param int $num
     * @return bool
     * @throws Exception
     */
    public function incSpreadCount(int $uid, int $num = 1)
    {
        if (!$this->dao->incField($uid, 'spread_count', $num))
            throw new AdminException('Không thể tăng số lượng người được thăng chức');
        return true;
    }


    /**
     * Đặt loại đăng nhập của người dùng
     * @param int $uid
     * @param string $type
     * @return bool
     * @throws Exception
     */
    public function setLoginType(int $uid, string $type = 'h5')
    {
        if (!$this->dao->update($uid, ['login_type' => $type]))
            throw new AdminException('Không thể đặt loại đăng nhập');
        return true;
    }

    /**
     * Thiết lập nhà quảng bá
     * @param int $uid
     * @param int $is_promoter
     * @return bool
     * @throws Exception
     */
    public function setIsPromoter(int $uid, $is_promoter = 1)
    {
        if (!$this->dao->update($uid, ['is_promoter' => $is_promoter]))
            throw new AdminException('Không thiết lập được công cụ quảng bá');
        return true;
    }

    /**
     * Thiết lập nhóm người dùng
     * @param $uids
     * @param int $group_id
     */
    public function setUserGroup($uids, int $group_id)
    {
        return $this->dao->batchUpdate($uids, ['group_id' => $group_id], 'uid');
    }

    /**
     * Tăng số dư người dùng
     * @param int $uid
     * @param float $old_now_money
     * @param float $now_money
     * @return bool
     * @throws Exception
     */
    public function addNowMoney(int $uid, $old_now_money, $now_money)
    {
        if (!$this->dao->update($uid, ['now_money' => bcadd($old_now_money, $now_money, 2)]))
            throw new AdminException('Không thể tăng số dư người dùng');
        return true;
    }

    /**
     * Giảm số dư của người dùng
     * @param int $uid
     * @param float $old_now_money
     * @param float $now_money
     * @return bool
     * @throws Exception
     */
    public function cutNowMoney(int $uid, $old_now_money, $now_money)
    {
        if ($old_now_money > $now_money) {
            $money = ['now_money' => bcsub($old_now_money, $now_money, 2)];
        } else {
            $money = ['now_money' => 0];
        }
        if (!$this->dao->update($uid, $money, 'uid'))
            throw new AdminException('Không thể giảm số dư của người dùng');
        return true;
    }

    /**
     * Giảm hoa hồng cho người dùng
     * @param int $uid
     * @param float $brokerage_price
     * @param float $price
     * @return bool
     * @throws Exception
     */
    public function cutBrokeragePrice(int $uid, $brokerage_price, $price)
    {
        if (!$this->dao->update($uid, ['brokerage_price' => bcsub($brokerage_price, $price, 2)]))
            throw new AdminException('Không thể giảm hoa hồng cho người dùng');
        return true;
    }

    /**
     * Tăng điểm người dùng
     * @param int $uid
     * @param float $old_integral
     * @param float $integral
     * @return bool
     * @throws Exception
     */
    public function addIntegral(int $uid, $old_integral, $integral)
    {
        if (!$this->dao->update($uid, ['integral' => bcadd($old_integral, $integral, 2)]))
            throw new AdminException('Không thể tăng điểm người dùng');
        return true;
    }

    /**
     * Giảm điểm người dùng
     * @param int $uid
     * @param float $old_integral
     * @param float $integral
     * @return bool
     * @throws Exception
     */
    public function cutIntegral(int $uid, $old_integral, $integral)
    {
        if (!$this->dao->update($uid, ['integral' => bcsub($old_integral, $integral, 2)]))
            throw new AdminException('Không thể giảm điểm người dùng');
        return true;
    }

    /**
     * Tăng trải nghiệm người dùng
     * @param int $uid
     * @param float $old_exp
     * @param float $exp
     * @return bool
     * @throws Exception
     */
    public function addExp(int $uid, float $old_exp, float $exp)
    {
        if (!$this->dao->update($uid, ['exp' => bcadd($old_exp, $exp, 2)]))
            throw new AdminException('Không thể tăng trải nghiệm người dùng');
        return true;
    }

    /**
     * Giảm trải nghiệm người dùng
     * @param int $uid
     * @param float $old_exp
     * @param float $exp
     * @return bool
     * @throws Exception
     */
    public function cutExp(int $uid, float $old_exp, float $exp)
    {
        if (!$this->dao->update($uid, ['exp' => bcsub($old_exp, $exp, 2)]))
            throw new AdminException('Giảm thất bại trong trải nghiệm người dùng');
        return true;
    }

    /**
     * Nhận thẻ người dùng
     * @param $uid
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getUserLablel(array $uids)
    {
        /** @var UserLabelRelationServices $services */
        $services = app()->make(UserLabelRelationServices::class);
        $userlabels = $services->getUserLabelList($uids);
        $data = [];
        foreach ($uids as $uid) {
            $labels = array_filter($userlabels, function ($item) use ($uid) {
                if ($item['uid'] == $uid) {
                    return true;
                }
            });
            $data[$uid] = implode(',', array_column($labels, 'label_name'));
        }
        return $data;
    }

    /**
     * Danh sách thành viên
     * @param array $where
     * @return array
     */
    public function index(array $where)
    {
        /** @var UserWechatuserServices $userWechatUser */
        $userWechatUser = app()->make(UserWechatuserServices::class);
        $fields = 'u.*,w.country,w.province,w.city,w.sex,w.unionid,w.openid,w.user_type as w_user_type,w.groupid,w.tagid_list,w.subscribe,w.subscribe_time';
        [$list, $count] = $userWechatUser->getWhereUserList($where, $fields);
        if ($list) {
            $uids = array_column($list, 'uid');
            $userlabel = $this->getUserLablel($uids);
            $userGroup = app()->make(UserGroupServices::class)->getUsersGroupName(array_unique(array_column($list, 'group_id')));
            $userExtract = app()->make(UserExtractServices::class)->getUsersSumList($uids);
            $levelName = app()->make(SystemUserLevelServices::class)->getUsersLevel(array_unique(array_column($list, 'level')));
            $userLevel = app()->make(UserLevelServices::class)->getUsersLevelInfo($uids);
            $agentLevel = app()->make(AgentLevelServices::class)->getAgentLevelArr();
            $spread_names = $this->dao->getColumn([['uid', 'in', array_unique(array_column($list, 'spread_uid'))]], 'nickname', 'uid');
            foreach ($list as &$item) {
                if (empty($item['addres'])) {
                    if (!empty($item['country']) || !empty($item['province']) || !empty($item['city'])) {
                        $item['addres'] = $item['country'] . $item['province'] . $item['city'];
                    }
                }
                $item['status'] = ($item['status'] == 1) ? 'Bình thường' : 'cấm';
                $item['birthday'] = $item['birthday'] ? date('Y-m-d', (int)$item['birthday']) : '';
                $item['extract_count_price'] = $userExtract[$item['uid']] ?? 0;//Rút tiền tích lũy
                $item['spread_uid_nickname'] = $item['spread_uid'] ? ($spread_names[$item['spread_uid']] ?? '') . '(' . $item['spread_uid'] . ')' : 'không có';
                //Loại người dùng
                if ($item['user_type'] == 'routine') {
                    $item['user_type'] = 'Chương trình nhỏ';
                } else if ($item['user_type'] == 'wechat') {
                    $item['user_type'] = 'Tài khoản chính thức';
                } else if ($item['user_type'] == 'h5') {
                    $item['user_type'] = 'H5';
                } else if ($item['user_type'] == 'pc') {
                    $item['user_type'] = 'PC';
                } else if ($item['user_type'] == 'app' || $item['user_type'] == 'apple') {
                    $item['user_type'] = 'APP';
                } else $item['user_type'] = 'khác';
                if ($item['sex'] == 1) {
                    $item['sex'] = 'nam giới';
                } else if ($item['sex'] == 2) {
                    $item['sex'] = 'nữ giới';
                } else $item['sex'] = 'Bảo mật';
                //Tên cấp độ
                $item['level'] = $levelName[$item['level']] ?? 'không có';
                //Tên nhóm
                $item['group_id'] = $userGroup[$item['group_id']] ?? 'không có';
                //Cấp độ người dùng
                $item['vip_name'] = false;
                $levelInfo = $userLevel[$item['uid']] ?? null;
                if ($levelInfo) {
                    if ($levelInfo && ($levelInfo['is_forever'] || time() < $levelInfo['valid_time'])) {
                        $item['vip_name'] = $item['level'] != 'không có' ? $item['level'] : false;
                    }
                }
                $item['agent_level_name'] = $agentLevel[$item['agent_level']] ?? 'không có';
                $item['labels'] = $userlabel[$item['uid']] ?? '';
                $item['isMember'] = $item['is_money_level'] > 0 ? 1 : 0;
                $item['svip_over_day'] = $item['overdue_time'] ? ceil(($item['overdue_time'] - time()) / 86400) : '';
                $item['svip_overdue_time'] = date('Y-m-d', $item['overdue_time']);
                if (strpos($item['avatar'], '/statics/system_images/') !== false) {
                    $item['avatar'] = set_file_url($item['avatar']);
                }
            }
        }

        return compact('count', 'list');
    }

    /**
     * Nhận dữ liệu trang đã sửa đổi
     * @param int $id
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function edit(int $id)
    {
        $user = $this->getUserInfo($id);
        if (!$user)
            throw new AdminException('Dữ liệu không tồn tại');
        $f = array();
        $f[] = Form::input('uid', 'ID người dùng', $user->getData('uid'))->disabled(true);
        $f[] = Form::input('real_name', 'tên thật', $user->getData('real_name'));
        $f[] = Form::input('phone', 'số điện thoại', $user->getData('phone'));

        $f[] = Form::date('birthday', 'Sinh nhật', $user->getData('birthday') ? date('Y-m-d', $user->getData('birthday')) : '');
        $f[] = Form::input('card_id', 'số CMND', $user->getData('card_id'));
        $f[] = Form::input('addres', 'Địa chỉ người dùng', $user->getData('addres'));
        $f[] = Form::textarea('mark', 'Nhận xét của người dùng', $user->getData('mark'));
        $f[] = Form::input('pwd', 'Mật khẩu đăng nhập')->type('password')->placeholder('Vui lòng để trống nếu bạn không muốn thay đổi mật khẩu của mình.');
        $f[] = Form::input('true_pwd', 'Xác nhận mật khẩu')->type('password')->placeholder('Vui lòng để trống nếu bạn không muốn thay đổi mật khẩu của mình.');

        //Truy vấn tất cả các cấp thành viên cao hơn thành viên hiện tại
//        $grade = app()->make(UserLevelServices::class)->getUerLevelInfoByUid($id, 'grade');
        $systemLevelList = app()->make(SystemUserLevelServices::class)->getWhereLevelList([], 'id,name');
        $setOptionLevel = function () use ($systemLevelList) {
            $menus = [];
            foreach ($systemLevelList as $menu) {
                $menus[] = ['value' => $menu['id'], 'label' => $menu['name']];
            }
            return $menus;
        };
        $f[] = Form::select('level', 'Cấp độ người dùng', (int)$user->getData('level'))->setOptions(FormBuilder::setOptions($setOptionLevel))->filterable(true);
        $systemGroupList = app()->make(UserGroupServices::class)->getGroupList();
        $setOptionGroup = function () use ($systemGroupList) {
            $menus = [];
            foreach ($systemGroupList as $menu) {
                $menus[] = ['value' => $menu['id'], 'label' => $menu['group_name']];
            }
            return $menus;
        };
        $f[] = Form::select('group_id', 'Nhóm người dùng', $user->getData('group_id'))->setOptions(FormBuilder::setOptions($setOptionGroup))->filterable(true);
        $systemLabelList = app()->make(UserLabelServices::class)->getLabelList();
        $labels = app()->make(UserLabelRelationServices::class)->getUserLabels($user['uid']);
        $setOptionLabel = function () use ($systemLabelList) {
            $menus = [];
            foreach ($systemLabelList as $menu) {
                $menus[] = ['value' => $menu['id'], 'label' => $menu['label_name']];
            }
            return $menus;
        };
        $f[] = Form::select('label_id', 'Thẻ người dùng', $labels)->setOptions(FormBuilder::setOptions($setOptionLabel))->filterable(true)->multiple(true);
        $f[] = Form::radio('spread_open', 'Trình độ thăng hạng', $user->getData('spread_open'))->info('Sau khi vô hiệu hóa trình độ khuyến mãi của người dùng, người dùng sẽ không có quyền phân phối theo bất kỳ chế độ phân phối nào.')->options([['value' => 1, 'label' => 'cho phép'], ['value' => 0, 'label' => 'Vô hiệu hóa']]);
        //Mô hình phân phối Renren Distribution
        $storeBrokerageStatus = sys_config('store_brokerage_statu', 1);
        if ($storeBrokerageStatus == 1) {
            $f[] = Form::radio('is_promoter', 'Quyền của nhà quảng cáo', $user->getData('is_promoter'))->info('Bật hoặc tắt quyền khuyến mãi của người dùng theo chế độ phân phối được chỉ định')->options([['value' => 1, 'label' => 'bật lên'], ['value' => 0, 'label' => 'đóng cửa']]);
        }
        $f[] = Form::radio('status', 'Trạng thái người dùng', $user->getData('status'))->options([['value' => 1, 'label' => 'bật lên'], ['value' => 0, 'label' => 'khóa']]);
        return create_form('biên tập', $f, Url::buildUrl('/user/user/' . $id), 'PUT');
    }

    /**
     * Thêm biểu mẫu người dùng
     * @param int $id
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function saveForm()
    {
        $f = array();
        $f[] = Form::input('real_name', 'tên thật', '')->placeholder('Vui lòng nhập tên thật của bạn');
        $f[] = Form::input('phone', 'số điện thoại', '')->placeholder('Vui lòng nhập số điện thoại di động')->required();
        $f[] = Form::date('birthday', 'Sinh nhật', '')->placeholder('Vui lòng chọn ngày sinh');
        $f[] = Form::input('card_id', 'số CMND', '')->placeholder('Vui lòng nhập số ID của bạn');
        $f[] = Form::input('addres', 'Địa chỉ người dùng', '')->placeholder('Vui lòng nhập địa chỉ người dùng');
        $f[] = Form::textarea('mark', 'Nhận xét của người dùng', '')->placeholder('Vui lòng nhập nhận xét của người dùng');
        $f[] = Form::input('pwd', 'Mật khẩu đăng nhập')->type('password')->placeholder('Vui lòng nhập mật khẩu đăng nhập của bạn');
        $f[] = Form::input('true_pwd', 'Xác nhận mật khẩu')->type('password')->placeholder('Vui lòng xác nhận lại mật khẩu');
        $systemLevelList = app()->make(SystemUserLevelServices::class)->getWhereLevelList([], 'id,name');
        $setOptionLevel = function () use ($systemLevelList) {
            $menus = [];
            foreach ($systemLevelList as $menu) {
                $menus[] = ['value' => $menu['id'], 'label' => $menu['name']];
            }
            return $menus;
        };
        $f[] = Form::select('level', 'Cấp độ người dùng', '')->setOptions(FormBuilder::setOptions($setOptionLevel))->filterable(true);
        $systemGroupList = app()->make(UserGroupServices::class)->getGroupList();
        $setOptionGroup = function () use ($systemGroupList) {
            $menus = [];
            foreach ($systemGroupList as $menu) {
                $menus[] = ['value' => $menu['id'], 'label' => $menu['group_name']];
            }
            return $menus;
        };
        $f[] = Form::select('group_id', 'Nhóm người dùng', '')->setOptions(FormBuilder::setOptions($setOptionGroup))->filterable(true);
        $systemLabelList = app()->make(UserLabelServices::class)->getLabelList();
        $setOptionLabel = function () use ($systemLabelList) {
            $menus = [];
            foreach ($systemLabelList as $menu) {
                $menus[] = ['value' => $menu['id'], 'label' => $menu['label_name']];
            }
            return $menus;
        };
        $f[] = Form::select('label_id', 'Thẻ người dùng', '')->setOptions(FormBuilder::setOptions($setOptionLabel))->filterable(true)->multiple(true);
        $f[] = Form::radio('spread_open', 'Trình độ thăng hạng', 1)->info('Sau khi vô hiệu hóa trình độ khuyến mãi của người dùng, người dùng sẽ không có quyền phân phối theo bất kỳ chế độ phân phối nào.')->options([['value' => 1, 'label' => 'cho phép'], ['value' => 0, 'label' => 'Vô hiệu hóa']]);
        //Mô hình phân phối Renren Distribution
        $storeBrokerageStatus = sys_config('store_brokerage_statu', 1);
        if ($storeBrokerageStatus == 1) {
            $f[] = Form::radio('is_promoter', 'Quyền của nhà quảng cáo', 0)->info('Bật hoặc tắt quyền khuyến mãi của người dùng theo chế độ phân phối được chỉ định')->options([['value' => 1, 'label' => 'bật lên'], ['value' => 0, 'label' => 'đóng cửa']]);
        }
        $f[] = Form::radio('status', 'Trạng thái người dùng', 1)->options([['value' => 1, 'label' => 'bật lên'], ['value' => 0, 'label' => 'khóa']]);
        return create_form('Thêm người dùng', $f, $this->url('/user/user'), 'POST');
    }

    /**
     * Xử lý gửi sửa đổi
     * @param int $id
     * @param array $data
     * @return bool
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function updateInfo(int $id, array $data)
    {
        $user = $this->getUserInfo($id);
        if (!$user) {
            throw new AdminException('Dữ liệu không tồn tại');
        }
        $res1 = false;
        $res2 = false;
        $edit = array();
        if ($data['money_status'] && $data['money']) {//Số dư tăng hoặc giảm
            /** @var UserMoneyServices $userMoneyServices */
            $userMoneyServices = app()->make(UserMoneyServices::class);
            if ($data['money_status'] == 1) {//Tăng
                $edit['now_money'] = bcadd($user['now_money'], $data['money'], 2);
                $res1 = $userMoneyServices->income('system_add', $user['uid'], $data['money'], $edit['now_money'], $data['adminId'] ?? 0, $data['mark']);
                //Thêm hồ sơ nạp tiền
                $recharge_data = [
                    'order_id' => app()->make(StoreOrderCreateServices::class)->getNewOrderId('cz'),
                    'uid' => $id,
                    'price' => $data['money'],
                    'recharge_type' => 'system',
                    'paid' => 1,
                    'add_time' => time(),
                    'give_price' => 0,
                    'channel_type' => 'system',
                    'pay_time' => time(),
                ];
                /** @var UserRechargeServices $rechargeServices */
                $rechargeServices = app()->make(UserRechargeServices::class);
                $rechargeServices->save($recharge_data);
            } else if ($data['money_status'] == 2) {//giảm bớt
                if ($user['now_money'] > $data['money']) {
                    $edit['now_money'] = bcsub($user['now_money'], $data['money'], 2);
                } else {
                    $edit['now_money'] = 0;
                    $data['money'] = $user['now_money'];
                }
                $res1 = $userMoneyServices->income('system_sub', $user['uid'], $data['money'], $edit['now_money'], $data['adminId'] ?? 0, $data['mark']);
            }
            event('OutPushListener', ['user_update_push', ['uid' => $id, 'type' => 'money', 'value' => $data['money_status'] == 2 ? -floatval($data['money']) : $data['money']]]);
        } else {
            $res1 = true;
        }
        if ($data['integration_status'] && $data['integration']) {//Điểm tăng hoặc giảm
            /** @var UserBillServices $userBill */
            $userBill = app()->make(UserBillServices::class);
            $integral_data = ['link_id' => $data['adminId'] ?? 0, 'number' => $data['integration']];
            if ($data['integration_status'] == 1) {//Tăng
                $edit['integral'] = bcadd($user['integral'], $data['integration'], 2);
                $integral_data['balance'] = $edit['integral'];
                $integral_data['title'] = 'Hệ thống cộng điểm';
                $integral_data['mark'] = $data['mark'] == '' ? 'Hệ thống đã thêm' . floatval($data['integration']) . 'tích phân' : $data['mark'];
                $res2 = $userBill->incomeIntegral($user['uid'], 'system_add', $integral_data);
            } else if ($data['integration_status'] == 2) {//giảm bớt
                $edit['integral'] = bcsub($user['integral'], $data['integration'], 2);
                $integral_data['balance'] = $edit['integral'];
                $integral_data['title'] = 'Hệ thống giảm điểm';
                $integral_data['mark'] = $data['mark'] == '' ? 'Hệ thống đã khấu trừ' . floatval($data['integration']) . 'tích phân' : $data['mark'];
                $res2 = $userBill->expendIntegral($user['uid'], 'system_sub', $integral_data);
            }
            event('OutPushListener', ['user_update_push', ['uid' => $id, 'type' => 'point', 'value' => $data['integration_status'] == 2 ? -intval($data['integration']) : $data['integration']]]);
        } else {
            $res2 = true;
        }
        //Sửa đổi thông tin cơ bản
        if (!isset($data['is_other']) || !$data['is_other']) {
            app()->make(UserLabelRelationServices::class)->setUserLabel([$id], $data['label_id']);
            if (isset($data['pwd']) && $data['pwd'] && $data['pwd'] != $user['pwd']) {
                $edit['pwd'] = $data['pwd'];
            }
            if (isset($data['spread_open'])) {
                $edit['spread_open'] = $data['spread_open'];
            }
            $edit['status'] = $data['status'];
            $edit['real_name'] = $data['real_name'];
            $edit['card_id'] = $data['card_id'];
            $edit['birthday'] = strtotime($data['birthday']);
            $edit['mark'] = $data['mark'];
            $edit['is_promoter'] = $data['is_promoter'];
            $edit['level'] = $data['level'];
            $edit['phone'] = $data['phone'];
            $edit['addres'] = $data['addres'];
            $edit['group_id'] = $data['group_id'];
            if ($user['level'] != $data['level']) {
                /** @var UserLevelServices $userLevelService */
                $userLevelService = app()->make(UserLevelServices::class);
                $userLevelService->setUserLevel((int)$user['uid'], (int)$data['level']);
            }
            if ($data['is_promoter'] == 0) {
                app()->make(SpreadApplyServices::class)->delete(['uid' => $user['uid']]);
            }
        }
        if ($edit) $res3 = $this->dao->update($id, $edit);

        else $res3 = true;
        if ($res1 && $res2 && $res3)
            return true;
        else throw new AdminException('Sửa đổi không thành công');
    }

    /**
     * Chỉnh sửa khác
     * @param $id
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function editOther($id, $type)
    {
        $user = $this->getUserInfo($id);
        if (!$user) {
            throw new AdminException('Dữ liệu không tồn tại');
        }
        $f = array();
        if ($type == 'money') {
            $f[] = Form::radio('money_status', 'Sửa đổi số dư', 1)->options([['value' => 1, 'label' => 'Tăng'], ['value' => 2, 'label' => 'giảm bớt']]);
            $f[] = Form::number('money', 'Sự cân bằng', 0)->min(0)->max(999999.99);
        } else {
            $f[] = Form::radio('integration_status', 'Sửa đổi điểm', 1)->options([['value' => 1, 'label' => 'Tăng'], ['value' => 2, 'label' => 'giảm bớt']]);
            $f[] = Form::number('integration', 'tích phân', 0)->min(0)->precision(0)->max(999999);
        }
        $f[] = Form::input('mark', 'Nhận xét', '')->type('textarea')->required();
        return create_form($type == 'money' ? 'Sửa đổi số dư' : 'Sửa đổi điểm', $f, Url::buildUrl('/user/update_other/' . $id), 'PUT');
    }

    /**
     * Thiết lập các nhóm thành viên
     * @param $id
     * @return mixed
     */
    public function setGroup($uids)
    {
        /** @var UserGroupServices $groupServices */
        $groupServices = app()->make(UserGroupServices::class);
        $userGroup = $groupServices->getGroupList();
        if (count($uids) == 1) {
            $user = $this->getUserInfo($uids[0], ['group_id']);
            $setOptionUserGroup = function () use ($userGroup) {
                $menus = [];
                foreach ($userGroup as $menu) {
                    $menus[] = ['value' => $menu['id'], 'label' => $menu['group_name']];
                }
                return $menus;
            };
            $field[] = Form::select('group_id', 'Nhóm người dùng', $user->getData('group_id') != 0 ? $user->getData('group_id') : '')->setOptions(FormBuilder::setOptions($setOptionUserGroup))->filterable(true);
        } else {
            $setOptionUserGroup = function () use ($userGroup) {
                $menus = [];
                foreach ($userGroup as $menu) {
                    $menus[] = ['value' => $menu['id'], 'label' => $menu['group_name']];
                }
                return $menus;
            };
            $field[] = Form::select('group_id', 'Nhóm người dùng')->setOptions(FormBuilder::setOptions($setOptionUserGroup))->filterable(true);
        }
        $field[] = Form::hidden('uids', implode(',', $uids));
        return create_form('Thiết lập nhóm người dùng', $field, Url::buildUrl('/user/save_set_group'), 'PUT');
    }

    /**
     * Lưu nhóm thành viên
     * @param $id
     * @return mixed
     */
    public function saveSetGroup($uids, int $group_id)
    {
        /** @var UserGroupServices $userGroup */
        $userGroup = app()->make(UserGroupServices::class);
        if (!$userGroup->getGroup($group_id)) {
            throw new AdminException('Nhóm này không tồn tại');
        }
        if (!$this->setUserGroup($uids, $group_id)) {
            throw new AdminException('Không thể đặt nhóm hoặc không có thay đổi');
        }
        return true;
    }

    /**
     * Đặt nhãn người dùng
     * @param $uids
     * @return mixed
     */
    public function setLabel($uids)
    {
        /** @var UserLabelServices $labelServices */
        $labelServices = app()->make(UserLabelServices::class);
        $userLabel = $labelServices->getLabelList();
        if (count($uids) == 1) {
            $lids = app()->make(UserLabelRelationServices::class)->getUserLabels($uids[0]);
            $setOptionUserLabel = function () use ($userLabel) {
                $menus = [];
                foreach ($userLabel as $menu) {
                    $menus[] = ['value' => $menu['id'], 'label' => $menu['label_name']];
                }
                return $menus;
            };
            $field[] = Form::select('label_id', 'Thẻ người dùng', $lids)->setOptions(FormBuilder::setOptions($setOptionUserLabel))->filterable(true)->multiple(true);
        } else {
            $setOptionUserLabel = function () use ($userLabel) {
                $menus = [];
                foreach ($userLabel as $menu) {
                    $menus[] = ['value' => $menu['id'], 'label' => $menu['label_name']];
                }
                return $menus;
            };
            $field[] = Form::select('label_id', 'Thẻ người dùng')->setOptions(FormBuilder::setOptions($setOptionUserLabel))->filterable(true)->multiple(true);
        }
        $field[] = Form::hidden('uids', implode(',', $uids));
        return create_form('Đặt nhãn người dùng', $field, Url::buildUrl('/user/save_set_label'), 'PUT');
    }

    /**
     * Lưu nhãn người dùng
     * @return mixed
     */
    public function saveSetLabel($uids, $labels, $label_type = 0)
    {
        foreach ($labels as $id) {
            if (!app()->make(UserLabelServices::class)->getLable((int)$id)) {
                throw new AdminException('Thẻ không tồn tại hoặc đã bị xóa');
            }
        }
        /** @var UserLabelRelationServices $services */
        $services = app()->make(UserLabelRelationServices::class);
        if (!$services->setUserLabel($uids, $labels, $label_type)) {
            throw new AdminException('Không đặt được nhãn');
        }
        return true;
    }

    /**
     * Cấp độ thành viên miễn phí
     * @param int $uid
     * @return mixed
     * */
    public function giveLevel($id)
    {
        if (!$this->getUserInfo($id)) {
            throw new AdminException('Người dùng không tồn tại');
        }
        //Truy vấn tất cả các cấp thành viên cao hơn thành viên hiện tại
        $grade = app()->make(UserLevelServices::class)->getUerLevelInfoByUid($id, 'grade');
        $systemLevelList = app()->make(SystemUserLevelServices::class)->getWhereLevelList(['grade', '>', $grade ?? 0], 'id,name');

        $setOptionlevel = function () use ($systemLevelList) {
            $menus = [];
            foreach ($systemLevelList as $menu) {
                $menus[] = ['value' => $menu['id'], 'label' => $menu['name']];
            }
            return $menus;
        };
        $field[] = Form::select('level_id', 'Cấp độ người dùng')->setOptions(FormBuilder::setOptions($setOptionlevel))->filterable(true);
        return create_form('Cấp độ miễn phí', $field, Url::buildUrl('/user/save_give_level/' . $id), 'PUT');
    }

    /**
     * Triển khai cấp độ thành viên miễn phí
     * @param int $id
     * @param int $level_id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function saveGiveLevel(int $id, int $level_id)
    {
        if (!$this->getUserInfo($id)) {
            throw new AdminException('Người dùng không tồn tại');
        }
        /** @var SystemUserLevelServices $systemLevelServices */
        $systemLevelServices = app()->make(SystemUserLevelServices::class);
        /** @var UserLevelServices $userLevelServices */
        $userLevelServices = app()->make(UserLevelServices::class);
        //Truy vấn cấp độ thành viên hiện được chọn
        $systemLevel = $systemLevelServices->getLevel($level_id);
        if (!$systemLevel) throw new AdminException('Cấp độ không tồn tại hoặc đã bị xóa');
        //Kiểm tra xem bạn có cấp độ thành viên này không
        $level = $userLevelServices->getWhereLevel(['uid' => $id, 'level_id' => $level_id], 'valid_time,is_forever');
        if ($level && $level['status'] == 1 && $level['is_del'] == 0) {
            throw new AdminException('Người dùng này đã có cấp độ người dùng này và không thể tặng quà nữa.');
        }
        //Lưu thông tin thành viên
        if (!$userLevelServices->setUserLevel($id, $level_id, $systemLevel)) {
            throw new AdminException('Quà tặng không thành công');
        }
        return true;
    }

    /**
     * Thời gian thành viên trả phí miễn phí
     * @param $id
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2023/03/01
     */
    public function giveLevelTime($id)
    {
        $userInfo = $this->getUserInfo($id);
        if (!$userInfo) {
            throw new AdminException('Người dùng không tồn tại');
        }
        if ($userInfo['is_ever_level'] == 1) {
            $timeDiff = 'Vĩnh viễn';
        } else {
            $timeDiff = $userInfo['overdue_time'] < time() ? 'không có' : date('Y-m-d H:i:s', $userInfo['overdue_time']);
        }
        $dayDiff = $userInfo['overdue_time'] > time() ? intval(($userInfo['overdue_time'] - time()) / 86400) : 0;
        $field[] = Form::input('time_diff', 'Thời gian hết hạn', $timeDiff)->readonly(true);
        if ($userInfo['is_ever_level'] == 0) {
            $field[] = Form::input('day_diff', 'Số ngày còn lại', $dayDiff)->readonly(true);
            $field[] = Form::number('days', 'tăng thời lượng(bầu trời)')->precision(0)->required();
        }
        return create_form('Thời gian thành viên trả phí miễn phí', $field, Url::buildUrl('/user/save_give_level_time/' . $id), 'PUT');
    }

    /**
     * Thực hiện thời hạn thành viên trả phí miễn phí
     * @param int $id
     * @param int $days
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function saveGiveLevelTime(int $id, int $days)
    {
        $userInfo = $this->getUserInfo($id);
        if ($userInfo->is_ever_level == 1) {
            return true;
        }
        if (!$userInfo) {
            throw new AdminException('Người dùng không tồn tại');
        }
        if ($days == 0) throw new AdminException('Số ngày tặng quà không được0');
        if ($days < -1) throw new AdminException('Ngày nhập sai');
        if ($userInfo->is_money_level == 0) {
            $userInfo->is_money_level = 3;
            if ($days == -1) {
                $userInfo->is_ever_level = 1;
                $time = 0;
            } else {
                $userInfo->overdue_time = $time = time() + ($days * 86400);
            }
        } else {
            if ($days == -1) {
                $userInfo->is_ever_level = 1;
                $time = 0;
            } else {
                $userInfo->overdue_time = $time = $userInfo->overdue_time + ($days * 86400);
            }
        }
        $userInfo->save();
        /** @var StoreOrderCreateServices $storeOrderCreateService */
        $storeOrderCreateService = app()->make(StoreOrderCreateServices::class);
        $orderInfo = [
            'uid' => $id,
            'order_id' => $storeOrderCreateService->getNewOrderId(),
            'type' => 3,
            'member_type' => 0,
            'pay_type' => 'admin',
            'paid' => 1,
            'pay_time' => time(),
            'is_free' => 1,
            'overdue_time' => $time,
            'vip_day' => $days,
            'add_time' => time()
        ];
        /** @var OtherOrderServices $otherOrder */
        $otherOrder = app()->make(OtherOrderServices::class);
        $otherOrder->save($orderInfo);
        return true;
    }

    /**
     * Xóa cấp độ thành viên
     * @paran int $uid
     * @paran boolean
     * */
    public function cleanUpLevel($uid)
    {
        if (!$this->getUserInfo($uid))
            throw new AdminException('Người dùng không tồn tại');
        /** @var UserLevelServices $services */
        $services = app()->make(UserLevelServices::class);
        return $this->transaction(function () use ($uid, $services) {
            $res = $services->delUserLevel($uid);
            $res1 = $this->dao->update($uid, ['clean_time' => time(), 'level' => 0, 'exp' => 0], 'uid');
            if (!$res && !$res1)
                throw new AdminException('Xóa không thành công');
            return true;
        });
    }

    /**
     * Chi tiết người dùng
     * @param int $uid
     * @param array $userIfno
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getUserDetailed(int $uid, $userIfno = [])
    {
        /** @var UserAddressServices $userAddress */
        $userAddress = app()->make(UserAddressServices::class);
        $field = 'real_name,phone,province,city,district,detail,post_code';
        $address = $userAddress->getUserDefaultAddress($uid, $field);
        if (!$address) {
            $address = $userAddress->getUserAddressList($uid, $field);
            $address = $address[0] ?? [];
        }
        $userInfo = $this->getUserInfo($uid);
        return [
            ['name' => 'Địa chỉ giao hàng mặc định', 'value' => $address ? 'người nhận hàng:' . $address['real_name'] . 'mã bưu điện:' . $address['post_code'] . ' Số điện thoại của người nhận hàng:' . $address['phone'] . ' Địa chỉ:' . $address['province'] . ' ' . $address['city'] . ' ' . $address['district'] . ' ' . $address['detail'] : ''],
            ['name' => 'số điện thoại', 'value' => $userInfo['phone']],
            ['name' => 'Tên', 'value' => ''],
            ['name' => 'Biệt danh WeChat', 'value' => $userInfo['nickname']],
            ['name' => 'hình đại diện', 'value' => $userInfo['avatar']],
            ['name' => 'Thư', 'value' => ''],
            ['name' => 'Sinh nhật', 'value' => ''],
            ['name' => 'tích phân', 'value' => $userInfo['integral']],
            ['name' => 'Nhà quảng bá cấp cao', 'value' => $userInfo['spread_uid'] ? $this->getUserInfo($userInfo['spread_uid'], ['nickname'])['nickname'] ?? '' : ''],
            ['name' => 'Số dư tài khoản', 'value' => $userInfo['now_money']],
            ['name' => 'tổng thu nhập hoa hồng', 'value' => app()->make(UserBillServices::class)->getBrokerageSum($uid)],
            ['name' => 'Tổng số tiền rút', 'value' => app()->make(UserExtractServices::class)->getUserExtract($uid)],
        ];

    }

    /**
     * Có được khả năng chi tiêu của người dùng và điểm cân bằng của người dùng trong thông tin chi tiết về người dùng, v.v.
     * @param $uid
     * @return array[]
     */
    public function getHeaderList(int $uid, $userInfo = [])
    {
        if (!$userInfo) {
            $userInfo = $this->getUserInfo($uid);
        }
        /** @var StoreOrderServices $orderServices */
        $orderServices = app()->make(StoreOrderServices::class);
        $where = ['uid' => $uid, 'paid' => 1, 'refund_status' => 0, 'pid' => 0];
        return [
            [
                'title' => 'Sự cân bằng',
                'value' => $userInfo['now_money'] ?? 0,
                'key' => 'Nhân dân tệ',
            ],
            [
                'title' => 'Tổng số đơn đặt hàng',
                'value' => $orderServices->count($where),
                'key' => 'Cái bút',
            ],
            [
                'title' => 'Tổng lượng tiêu thụ',
                'value' => $orderServices->together($where, 'pay_price'),
                'key' => 'Nhân dân tệ',
            ],
            [
                'title' => 'tích phân',
                'value' => $userInfo['integral'] ?? 0,
                'key' => '',
            ],
            [
                'title' => 'Đơn đặt hàng trong tháng này',
                'value' => $orderServices->count($where + ['time' => 'month']),
                'key' => 'Cái bút',
            ],
            [
                'title' => 'Số tiền chi tiêu tháng này',
                'value' => $orderServices->together($where + ['time' => 'month'], 'pay_price'),
                'key' => 'Nhân dân tệ',
            ]
        ];
    }


    /**
     * Lấy tổng số điểm, số lần check-in và số dư thay đổi trong hồ sơ người dùng
     * @param $uid
     * @return array
     */
    public function getUserBillCountData($uid)
    {
        /** @var UserBillServices $userBill */
        $userBill = app()->make(UserBillServices::class);
        $integral_count = $userBill->getIntegralCount($uid);
        $sign_count = $userBill->getSignCount($uid);
        $balanceChang_count = $userBill->getBrokerageCount($uid);
        return [$integral_count, $sign_count, $balanceChang_count];
    }

    /**
     * Chi tiết người dùng
     * @param int $uid
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function read(int $uid)
    {
        $userInfo = $this->getUserInfo($uid);
        if (!$userInfo) {
            throw new AdminException('Dữ liệu không tồn tại');
        }
        $userInfo['avatar'] = strpos($userInfo['avatar'], 'http') === false ? (sys_config('site_url') . $userInfo['avatar']) : $userInfo['avatar'];
        $userInfo['overdue_time'] = date('Y-m-d H:i:s', $userInfo['overdue_time']);
        $userInfo['birthday'] = $userInfo['birthday'] < 0 ? 0 : $userInfo['birthday'];
        if ($userInfo['addres'] == '') {
            $defaultAddressInfo = app()->make(UserAddressServices::class)->getUserDefaultAddress($uid);
            if ($defaultAddressInfo) {
                $userInfo['addres'] = $defaultAddressInfo['province'] . $defaultAddressInfo['city'] . $defaultAddressInfo['district'] . $defaultAddressInfo['detail'];
            } else {
                $userInfo['addres'] = '';
            }
        }
        $userInfo['vip_name'] = app()->make(SystemUserLevelServices::class)->value(['id' => $userInfo['level']], 'name');
        $userInfo['group_name'] = app()->make(UserGroupServices::class)->value(['id' => $userInfo['group_id']], 'group_name');
        $userInfo['spread_uid_nickname'] = $this->dao->value(['uid' => $userInfo['spread_uid']], 'nickname') . '/' . $userInfo['spread_uid'];
        $userInfo['label_list'] = implode(',', array_column(app()->make(UserLabelRelationServices::class)->getUserLabelList([$uid]), 'label_name'));
        return [
            'uid' => $uid,
            'userinfo' => $this->getUserDetailed($uid, $userInfo),
            'headerList' => $this->getHeaderList($uid, $userInfo),
            'count' => $this->getUserBillCountData($uid),
            'ps_info' => $userInfo
        ];
    }

    /**
     * Kết bạn
     * @param int $id
     * @param string $field
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getFriendList(int $id, string $field = 'uid,nickname,level,add_time,spread_time')
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getList(['spread_uid' => $id], $field, $page, $limit);
        /** @var SystemUserLevelServices $systemLevelServices */
        $systemLevelServices = app()->make(SystemUserLevelServices::class);
        $systemLevelList = $systemLevelServices->getWhereLevelList([], 'id,name');
        if ($systemLevelServices) $systemLevelServices = array_combine(array_column($systemLevelList, 'id'), $systemLevelList);
        foreach ($list as &$item) {
            $item['type'] = $systemLevelServices[$item['level']]['name'] ?? 'Chưa có';
            $item['add_time'] = $item['spread_time'] && is_numeric($item['spread_time']) ? date('Y-m-d H:i:s', $item['spread_time']) : '';
        }
        $count = $this->dao->count(['spread_uid' => $id]);
        return compact('list', 'count');
    }

    /**
     * Nhận thông tin người dùng cá nhân
     * @param $id người dùngid
     * @return mixed
     */
    public function oneUserInfo(int $id, string $type)
    {
        switch ($type) {
            case 'spread':
//                /** @var UserFriendsServices $services */
//                $services = app()->make(UserFriendsServices::class);
//                return $services->getFriendList(['uid' => $id], ['level', 'nickname']);
                return $this->getFriendList($id);
            case 'order':
                /** @var StoreOrderServices $services */
                $services = app()->make(StoreOrderServices::class);
                return $services->getUserOrderList($id);
            case 'integral':
                /** @var UserBillServices $services */
                $services = app()->make(UserBillServices::class);
                return $services->getIntegralList($id, [], 'title,number,balance,mark,add_time,frozen_time,pm');
            case 'sign':
                /** @var UserBillServices $services */
                $services = app()->make(UserBillServices::class);
                return $services->getSignList($id, [], 'title,number,mark,add_time');
            case 'coupon':
                /** @var StoreCouponUserServices $services */
                $services = app()->make(StoreCouponUserServices::class);
                return $services->getUserCouponList($id);
            case 'balance_change':
                /** @var UserMoneyServices $services */
                $services = app()->make(UserMoneyServices::class);
                return $services->balanceList(['uid' => $id]);
            default:
                throw new AdminException('Lỗi tham số');
        }
    }

    /**Lấy số lượt truy cập của người dùng tại một thời điểm cụ thể
     * @param $time
     * @param $week
     * @return int
     */
    public function todayLastVisits($time, $week)
    {
        return $this->dao->todayLastVisit($time, $week);
    }

    /**Nhận người dùng mới vào một thời điểm cụ thể
     * @param $time
     * @param $week
     * @return int
     */
    public function todayAddVisits($time, $week)
    {
        return $this->dao->todayAddVisit($time, $week);
    }

    /**
     * biểu đồ người dùng
     */
    public function userChart()
    {
        $starday = date('Y-m-d', strtotime('-30 day'));
        $yesterday = date('Y-m-d', strtotime('+1 day'));

        $user_list = $this->dao->userList($starday, $yesterday);
        $chartdata = [];
        $data = [];
        $chartdata['legend'] = ['Số lượng người dùng'];//Phân loại
        $chartdata['yAxis']['maxnum'] = 0;//Số lượng giá trị tối đa
        $chartdata['xAxis'] = [date('m-d')];//Xgiá trị trục
        $chartdata['series'] = [0];//Giá trị loại 1
        if (!empty($user_list)) {
            foreach ($user_list as $k => $v) {
                $data['day'][] = $v['day'];
                $data['count'][] = $v['count'];
                if ($chartdata['yAxis']['maxnum'] < $v['count'])
                    $chartdata['yAxis']['maxnum'] = $v['count'];
            }
            $chartdata['xAxis'] = $data['day'];//Xgiá trị trục
            $chartdata['series'] = $data['count'];//Giá trị loại 1
        }
        $chartdata['bing_xdata'] = ['Người dùng chưa tiêu dùng', 'Một người dùng tiêu dùng', 'giữ chân khách hàng', 'Khách hàng quay lại'];
        $color = ['#5cadff', '#b37feb', '#19be6b', '#ff9900'];
        $pay[0] = $this->dao->count(['pay_count' => 0]);
        $pay[1] = $this->dao->count(['pay_count' => 1]);
        $pay[2] = $this->dao->userCount(1);
        $pay[3] = $this->dao->userCount(2);
        foreach ($pay as $key => $item) {
            $bing_data[] = ['name' => $chartdata['bing_xdata'][$key], 'value' => $pay[$key], 'itemStyle' => ['color' => $color[$key]]];
        }
        $chartdata['bing_data'] = $bing_data;
        return $chartdata;
    }

    /***********************************************/
    /************ Dịch vụ api giao diện người dùng *******************/
    /************************************************/

    /**
     * Thông tin người dùng
     * @param $info
     * @return mixed
     */
    public function userInfo($info)
    {
        /** @var UserBillServices $userBill */
        $userBill = app()->make(UserBillServices::class);
        $uid = (int)$info['uid'];
        $broken_time = intval(sys_config('extract_time'));
        $search_time = time() - 86400 * $broken_time;
        //thời gian chuyển đổi
        $search_time = '1970/01/01' . ' - ' . date('Y/m/d H:i:s', $search_time);
        //Hoa hồng có thể được rút
        // giảm giá +
        $brokerage_commission = (string)$userBill->getUsersBokerageSum(['uid' => $uid, 'pm' => 1], $search_time);
        //Hoa hồng hoàn tiền -
        $refund_commission = (string)$userBill->getUsersBokerageSum(['uid' => $uid, 'pm' => 0], $search_time);
        $info['broken_commission'] = bcsub($brokerage_commission, $refund_commission, 2);
        if ($info['broken_commission'] < 0)
            $info['broken_commission'] = 0;
        $info['commissionCount'] = bcsub($info['brokerage_price'], $info['broken_commission'], 2);
        if ($info['commissionCount'] < 0)
            $info['commissionCount'] = 0;
        return $info;
    }

    /**
     * Trung tâm cá nhân
     * @param array $user
     */
    public function personalHome(array $user, $tokenData)
    {
        $userInfo = $user;
        $uid = (int)$user['uid'];
        /** @var StoreCouponUserServices $storeCoupon */
        $storeCoupon = app()->make(StoreCouponUserServices::class);
        /** @var UserBillServices $userBill */
        $userBill = app()->make(UserBillServices::class);
        /** @var UserExtractServices $userExtract */
        $userExtract = app()->make(UserExtractServices::class);
        /** @var StoreOrderServices $storeOrder */
        $storeOrder = app()->make(StoreOrderServices::class);
        /** @var UserLevelServices $userLevel */
        $userLevel = app()->make(UserLevelServices::class);
        /** @var StoreServiceServices $storeService */
        $storeService = app()->make(StoreServiceServices::class);
        /** @var WechatUserServices $wechatUser */
        $wechatUser = app()->make(WechatUserServices::class);
        /** @var UserInvoiceServices $userInvoice */
        $userInvoice = app()->make(UserInvoiceServices::class);
        /** @var MemberCardServices $memberCardService */
        $memberCardService = app()->make(MemberCardServices::class);
        /** @var StoreProductRelationServices $collect */
        $collect = app()->make(StoreProductRelationServices::class);
        /** @var MessageSystemServices $messageSystemServices */
        $messageSystemServices = app()->make(MessageSystemServices::class);
        /** @var DiyServices $diyServices */
        $diyServices = app()->make(DiyServices::class);
        /** @var AgentLevelServices $agentLevelServices */
        $agentLevelServices = app()->make(AgentLevelServices::class);
        //Kiểm tra xem thành viên trả phí có được bật hay không
        $isOpenMember = $memberCardService->isOpenMemberCard();
        $user['is_open_member'] = $isOpenMember;
        $user['agent_level_name'] = '';
        if ($user['agent_level']) {
            $levelInfo = $agentLevelServices->getLevelInfo((int)$user['agent_level'], 'id,name,status,grade');
            if (!$levelInfo['status']) {
                $levelInfo = $agentLevelServices->get([
                    ['grade', '<', $levelInfo['grade']],
                    ['is_del', '=', 0],
                    ['status', '=', 1]
                ], ['id', 'name', 'status', 'grade']);
            }
            $user['agent_level_name'] = $levelInfo && $levelInfo['name'] && $levelInfo['status'] ? $levelInfo['name'] : '';
        }
        //Thành viên nhận được phiếu giảm giá
        // $couponService->sendMemberCoupon($uid);
        //Kiểm tra xem tư cách thành viên đã hết hạn chưa
        $this->offMemberLevel($uid, $userInfo);
        $wechatUserInfo = $wechatUser->getOne(['uid' => $uid, 'user_type' => $tokenData['type']]);
        $user['is_complete'] = $wechatUserInfo['is_complete'] ?? 0;
        $user['couponCount'] = $storeCoupon->getUserValidCouponCount((int)$uid);
        $user['like'] = app()->make(StoreProductRelationServices::class)->getUserCollectCount($user['uid']);
        $user['orderStatusNum'] = $storeOrder->getOrderData($uid);
        $user['notice'] = 0;
        /** @var UserMoneyServices $userMoney */
        $userMoney = app()->make(UserMoneyServices::class);

        $user['recharge'] = $userMoney->sum([
            ['uid', '=', $uid], ['pm', '=', 1], ['type', 'in', ['recharge', 'system_add', 'extract', 'register_system_add', 'lottery_add']]
        ], 'number');
        $user['orderStatusSum'] = bcsub((string)$user['recharge'], (string)$user['now_money'], 2);
        $user['extractTotalPrice'] = $userExtract->getExtractSum(['uid' => $uid, 'status' => 1]);//Rút tiền tích lũy
        $user['extractPrice'] = $user['brokerage_price'];//Có thể rút
        $user['statu'] = (int)sys_config('store_brokerage_statu');
        if (!$user['is_promoter']) {
            $price = $storeOrder->sum(['paid' => 1, 'refund_status' => 0, 'uid' => $user['uid']], 'pay_price');
            if (is_brokerage_statu($price)) {
                $this->dao->update($uid, ['is_promoter' => 1], 'uid');
                $user['is_promoter'] = 1;
            }
        }
        /** @var UserBrokerageServices $frozenPrices */
        $frozenPrices = app()->make(UserBrokerageServices::class);
        $user['broken_commission'] = $frozenPrices->getUserFrozenPrice($uid);
        if ($user['broken_commission'] < 0)
            $user['broken_commission'] = 0;
        $user['commissionCount'] = bcsub((string)$user['brokerage_price'], (string)$user['broken_commission'], 2);
        if ($user['commissionCount'] < 0)
            $user['commissionCount'] = 0;
        if (!sys_config('member_func_status'))
            $user['vip'] = false;
        else {
            $userLevel = $userLevel->getUerLevelInfoByUid($user['uid']);
            $user['vip'] = (bool)$userLevel;
            if ($user['vip']) {
                $user['vip_id'] = $userLevel['id'] ?? 0;
                $user['vip_icon'] = set_file_url($userLevel['icon']) ?? '';
                $user['vip_name'] = $userLevel['name'] ?? '';
            }
        }
        $user['yesterDay'] = $frozenPrices->getUsersBokerageSum(['uid' => $uid, 'pm' => 1], 'yesterday');
        $user['recharge_switch'] = (int)sys_config('recharge_switch');//Công tắc nạp tiền
        $user['adminid'] = $storeService->checkoutIsService(['uid' => $uid, 'status' => 1, 'customer' => 1]);
        if ($user['phone'] && $user['user_type'] != 'h5') {
            $user['switchUserInfo'][] = $userInfo;
            $h5UserInfo = $this->dao->getOne(['account' => $user['phone'], 'user_type' => 'h5']);
            if ($h5UserInfo) {
                $user['switchUserInfo'][] = $h5UserInfo;
            }
        } else if ($user['phone'] && $user['user_type'] == 'h5') {
            $wechatUserInfo = $this->getOne([['phone', '=', $user['phone']], ['user_type', '<>', 'h5']]);
            if ($wechatUserInfo) {
                $user['switchUserInfo'][] = $wechatUserInfo;
            }
            $user['switchUserInfo'][] = $userInfo;
        } else if (!$user['phone']) {
            $user['switchUserInfo'][] = $userInfo;
        }
        $user['broken_day'] = (int)sys_config('extract_time');//Thời gian đóng băng hoa hồng
        $user['balance_func_status'] = (int)sys_config('balance_func_status', 0);
        $invoice_func = $userInvoice->invoiceFuncStatus();
        $user['invioce_func'] = $invoice_func['invoice_func'];
        $user['special_invoice'] = $invoice_func['special_invoice'];
        $user['collectCount'] = $collect->count(['uid' => $uid]);
        $user['spread_status'] = $this->checkUserPromoter($user['uid']);
        $user['pay_vip_status'] = $user['is_ever_level'] || ($user['is_money_level'] && $user['overdue_time'] > time());
        $user['member_style'] = (int)$diyServices->getColorChange('member');
        if ($user['is_ever_level']) {
            $user['vip_status'] = 1;//thành viên thường trực
        } else {
            if (!$user['is_money_level'] && $user['overdue_time'] && $user['overdue_time'] < time()) {
                $user['vip_status'] = -1;//Hết hạn
            } else if (!$user['overdue_time'] && !$user['is_money_level']) {
                $user['vip_status'] = 2;//Không vượt qua
            } else if ($user['is_money_level'] && $user['overdue_time'] && $user['overdue_time'] > time()) {
                $user['vip_status'] = 3;//Đã kích hoạt, chưa hết hạn
            }
        }
        $user['svip_open'] = (bool)sys_config('member_card_status');
        /** @var StoreServiceRecordServices $servicesRecord */
        $servicesRecord = app()->make(StoreServiceRecordServices::class);
        $service_num = $servicesRecord->sum(['user_id' => $uid], 'mssage_num');
        $message = $messageSystemServices->count(['uid' => $uid, 'look' => 0, 'is_del' => 0]);
        $user['service_num'] = $service_num + $message;
        /** @var AgentLevelServices $userSpread */
        $agentLevel = app()->make(AgentLevelServices::class);
        $user['spread_level_count'] = $agentLevel->count(['status' => 1, 'is_del' => 0]);
        $user['extract_type'] = sys_config('extract_type');
        $user['integral'] = intval($user['integral']);
        $user['is_agent_level'] = $agentLevelServices->count(['status' => 1, 'is_del' => 0]) > 0 ? 1 : 0;
        $user['division_open'] = (int)sys_config('division_status', 0);
        $user['agent_apply_open'] = (int)sys_config('agent_apply_open', 0);
        $user['is_default_avatar'] = $user['avatar'] == sys_config('h5_avatar') ? 1 : 0;
        $user['avatar'] = strpos($user['avatar'], '/statics/system_images/') !== false ? set_file_url($user['avatar']) : $user['avatar'];
        $user['member_func_status'] = (int)sys_config('member_func_status');
        $user['visit_num'] = app()->make(StoreProductLogServices::class)->getCountByUser($uid);
        return $user;
    }

    /**
     * Thống kê quỹ người dùng
     * @param int $uid
     */
    public function balance(int $uid)
    {
        $userInfo = $this->getUserInfo($uid);
        if (!$userInfo) {
            throw new AdminException('Người dùng không tồn tại');
        }
        /** @var UserBillServices $userBill */
        $userBill = app()->make(UserBillServices::class);
        /** @var StoreOrderServices $storeOrder */
        $storeOrder = app()->make(StoreOrderServices::class);
        $user['now_money'] = $userInfo['now_money'];//Tổng quỹ hiện tại
        $user['recharge'] = $userBill->getRechargeSum($uid);//Nạp tiền tích lũy
        $user['orderStatusSum'] = $storeOrder->sum(['uid' => $uid, 'paid' => 1, 'is_del' => 0], 'pay_price');//Tiêu thụ tích lũy
        return $user;
    }

    /**
     * Thông tin người dùng sửa đổi
     * @param Request $request
     * @return mixed
     */
    public function eidtNickname(int $uid, array $data)
    {
        $info = $this->dao->get(['uid' => $uid]);
        if (!$info) {
            throw new ApiException('Người dùng không tồn tại');
        }
        if (!$this->dao->update($uid, $data, 'uid')) {
            throw new ApiException('Sửa đổi không thành công');
        }

        //Thông tin thay đổi người dùng sự kiện tùy chỉnh
        event('CustomEventListener', ['user_change_info', [
            'uid' => $uid,
            'nickname' => $info['nickname'],
            'phone' => $info['phone'],
            'avatar' => $info['avatar'],
            'add_time' => date('Y-m-d H:i:s', $info['add_time']),
            'user_type' => $info['user_type'],
        ]]);

        return true;
    }

    /**
     * Nhận thứ hạng của người quảng bá
     * @param $data Điều kiện truy vấn
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getRankList(array $data)
    {
        $startTime = strtotime('this week Monday');
        $endTime = time();
        switch ($data['type']) {
            case 'week':
                $startTime = strtotime('this week Monday');
                break;
            case 'month':
                $startTime = strtotime('last month');
                break;
        }
        [$page, $limit] = $this->getPageValue();
        $field = 't0.uid,t0.spread_uid,count(t1.spread_uid) AS count,t0.add_time,t0.nickname,t0.avatar';
        return $this->dao->getAgentRankList([$startTime, $endTime], $field, $page, $limit);
    }

    /**
     * Trình quảng bá liên kết âm thầm
     * @param int $uid
     * @param int $spreadUid
     * @param $code
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function spread(int $uid, int $spreadUid, $code, $agent_id)
    {
        $userInfo = $this->dao->getOne(['uid' => $uid]);
        if (!$userInfo) {
            throw new ApiException('Dữ liệu không tồn tại');
        }
        if ($code && !$spreadUid) {
            /** @var QrcodeServices $qrCode */
            $qrCode = app()->make(QrcodeServices::class);
            if ($info = $qrCode->getOne(['id' => $code, 'status' => 1])) {
                if ($info['third_type'] == 'agent') {
                    $agent_id = $info['third_id'];
                } else {
                    $spreadUid = $info['third_id'];
                }
            }
        }
        if ($agent_id) {
            $spreadInfo = $this->dao->getOne(['uid' => $agent_id]);
            if ($agent_id == $uid) {
                return 'Tôi không thể giới thiệu bản thân mình';
            } else if (!$userInfo) {
                return 'Người dùng không tồn tại';
            } else if (!$spreadInfo) {
                return 'Người dùng cao cấp không tồn tại';
            } else if ($userInfo->is_division) {
                return 'Bạn là bộ phận kinh doanh,Không thể bị ràng buộc trở thành nhân viên của người khác';
            } else if ($userInfo->is_agent) {
                return 'Bạn là một đại lý,Không thể bị ràng buộc trở thành nhân viên của người khác';
            } else if (app()->make(LoginServices::class)->updateUserInfo(['code' => $agent_id, 'is_staff' => 1], $userInfo, false)) {
                return 'Ràng buộc nhân viên cửa hàng thành công!';
            }
        }
        if ($spreadUid == 0) return 'Không bị ràng buộc';
        $userSpreadUid = $this->dao->value(['uid' => $spreadUid], 'spread_uid');
        //Ghi lại mối quan hệ bạn bè
        if ($spreadUid && $uid && $spreadUid != $uid) {
            /** @var UserFriendsServices $serviceFriend */
            $serviceFriend = app()->make(UserFriendsServices::class);
            $serviceFriend->saveFriend([
                'uid' => $uid,
                'friends_uid' => $spreadUid,
            ]);
        }
        $check = false;
        if (sys_config('brokerage_bindind') == 1) {
            if (sys_config('store_brokerage_binding_status') == 1) {
                if (!$userInfo['spread_uid']) {
                    $check = true;
                }
            } elseif (sys_config('store_brokerage_binding_status') == 2 && (($userInfo['spread_time'] + (sys_config('store_brokerage_binding_time') * 86400)) < time())) {
                $check = true;
            } elseif (sys_config('store_brokerage_binding_status') == 3) {
                $check = true;
            }
        } elseif (sys_config('brokerage_bindind') == 2) {
            if ($userInfo['add_time'] == $userInfo['last_time'] && $userInfo['spread_uid'] == 0) {
                $check = true;
            }
        }
        if ($userInfo['uid'] == $spreadUid || $userInfo['uid'] == $userSpreadUid) $check = false;
        if ($check) {
            $spreadInfo = $this->dao->get($spreadUid, ['division_id', 'agent_id', 'staff_id']);
            $data = [];
            $data['spread_uid'] = $spreadUid;
            $data['spread_time'] = time();
            $data['division_id'] = $spreadInfo['division_id'];
            $data['agent_id'] = $spreadInfo['agent_id'];
            $data['staff_id'] = $spreadInfo['staff_id'];
            if (!$this->dao->update($uid, $data, 'uid')) {
                throw new ApiException('Không thể ràng buộc mối quan hệ thăng tiến');
            }
            return 'Liên kết với cấp trên thành công, uid cấp trên là' . $spreadUid;
        } else {
            return 'Không bị ràng buộc';
        }
    }

    /**
     * Thêm bản ghi truy cập
     * @param Request $request
     * @return mixed
     */
    public function setVisit(array $data)
    {
        $userInfo = $this->getUserInfo($data['uid'], 'uid,user_type');
        if (!$userInfo) {
            throw new ApiException('Dữ liệu không tồn tại');
        }
        $data['channel_type'] = $userInfo['user_type'];
        $data['add_time'] = time();
        /** @var WechatUserServices $wechatUserServices */
        $wechatUserServices = app()->make(WechatUserServices::class);
        $wechatUser = $wechatUserServices->get(['uid' => $userInfo['uid'], 'user_type' => $userInfo['user_type']]);
        if (!$wechatUser) {
            $wechatUser = $wechatUserServices->get(['uid' => $userInfo['uid']]);
        }
        if ($wechatUser) {
            $data['province'] = $wechatUser['province'];
        }
        /** @var UserVisitServices $userVisit */
        $userVisit = app()->make(UserVisitServices::class);
        if ($userVisit->save($data)) {
            return true;
        } else {
            throw new ApiException('Thiết lập không thành công');
        }
    }

    /**
     * Nhận trạng thái hoạt động
     * @return mixed
     */
    public function activity()
    {
        /** @var StoreBargainServices $storeBragain */
        $storeBragain = app()->make(StoreBargainServices::class);
        /** @var StoreCombinationServices $storeCombinaion */
        $storeCombinaion = app()->make(StoreCombinationServices::class);
        /** @var StoreSeckillServices $storeSeckill */
        $storeSeckill = app()->make(StoreSeckillServices::class);
        $data['is_bargin'] = (bool)$storeBragain->validBargain();
        $data['is_pink'] = (bool)$storeCombinaion->validCombination();
        $data['is_seckill'] = (bool)$storeSeckill->getSeckillCount();
        return $data;
    }

    /**
     * Có được người quảng bá cấp dưới của người dùng
     * @param int $uid người dùng hiện tại
     * @param int $grade Cấp 0 Cấp 1 1 Cấp 2
     * @param string $orderBy loại
     * @param string $keyword
     * @return array|bool
     */
    public function getUserSpreadGrade(int $uid = 0, $grade = 0, $orderBy = '', $keyword = '')
    {
        $user = $this->getUserInfo($uid);
        if (!$user) {
            throw new AdminException('Người dùng không tồn tại');
        }
        $spread_one_ids = $this->getUserSpredadUids($uid, 1);
        $spread_two_ids = $this->getUserSpredadUids($uid, 2);
        $data = [
            'total' => count($spread_one_ids),
            'totalLevel' => count($spread_two_ids),
            'list' => []
        ];
        if (sys_config('brokerage_level', 2) == 1) {
            $data['count'] = $data['total'];
        } else {
            $data['count'] = $data['total'] + $data['totalLevel'];
        }
        /** @var UserStoreOrderServices $userStoreOrder */
        $userStoreOrder = app()->make(UserStoreOrderServices::class);
        $list = [];
        if ($grade == 0) {
            if ($spread_one_ids) $list = $userStoreOrder->getUserSpreadCountList($spread_one_ids, $orderBy, $keyword);
        } else {
            if ($spread_two_ids) $list = $userStoreOrder->getUserSpreadCountList($spread_two_ids, $orderBy, $keyword);
        }
        foreach ($list as &$item) {
            if (isset($item['spread_time']) && $item['spread_time']) {
                $item['time'] = date('Y/m/d', $item['spread_time']);
            }
        }
        $data['list'] = $list;
        $data['brokerage_level'] = (int)sys_config('brokerage_level', 2);
        return $data;
    }

    /**
     * Nhận một nhà quảng báuids
     * @param int $uid
     * @param bool $one
     * @return array
     */
    public function getUserSpredadUids(int $uid, int $type = 0)
    {
        $uids = $this->dao->getColumn(['spread_uid' => $uid, 'is_del' => 0], 'uid');
        if ($type === 1) {
            return $uids;
        }
        if ($uids) {
            $uidsTwo = $this->dao->getColumn([['spread_uid', 'in', $uids], ['is_del', '=', 0]], 'uid');
            if ($type === 2) {
                return $uidsTwo;
            }
            if ($uidsTwo) {
                $uids = array_merge($uids, $uidsTwo);
            }
        }
        return $uids;
    }


    /**
     * Phát hiện xem người dùng có phải là người quảng bá hay không
     * @param int $uid
     * @param $user
     * @return bool
     */
    public function checkUserPromoter(int $uid, $user = [])
    {
        if (!$user) {
            $user = $this->getUserInfo($uid, 'spread_open,is_promoter');
        }
        if (!$user) {
            return false;
        }
        //Phân phối có được kích hoạt không?
        if (!sys_config('brokerage_func_status')) {
            return false;
        }
        if (isset($user['spread_open']) && !$user['spread_open']) {
            return false;
        }
        /** @var StoreOrderServices $storeOrder */
        $storeOrder = app()->make(StoreOrderServices::class);
        $sumPrice = $storeOrder->sum(['uid' => $uid, 'paid' => 1], 'pay_price');//Tiêu thụ tích lũy
        $store_brokerage_statu = sys_config('store_brokerage_statu');
        $store_brokerage_price = sys_config('store_brokerage_price');
        if ($user['is_promoter'] || $store_brokerage_statu == 2 || ($store_brokerage_statu == 3 && $sumPrice > $store_brokerage_price)) {
            if (!$user['is_promoter']) {
                $this->dao->update($uid, ['is_promoter' => 1]);
            }
            return true;
        }
        return false;
    }

    /**
     * Đồng bộ hóa người dùng người hâm mộ WeChat(Giao diện phụ trợ)
     * @return bool
     */
    public function syncWechatUsers()
    {
        $appid = sys_config('wechat_appid');
        $appSecret = sys_config('wechat_appsecret');
        if (!$appid || !$appSecret) {
            throw new AdminException('Trước tiên hãy định cấu hình chương trình mini appid, appSecret và các tham số khác');
        }
        $key = md5('sync_wechat_users');
        //Nhấp chuột một lần một ngày
        if (CacheService::get($key)) {
            return true;
        }
        $next_openid = null;
        do {
            $result = WechatService::getUsersList($next_openid);
            $userOpenids = $result['data'];
            //Chia mảng lớn
            $opemidArr = array_chunk($userOpenids, 100);
            foreach ($opemidArr as $openids) {
                //Tham gia đồng bộ hóa|Cập nhật hàng đợi người dùng
                UserJob::dispatch([$openids]);
            }
            $next_openid = $result['next_openid'];
        } while ($next_openid != null);
        CacheService::set($key, 1, 3600 * 24);
        return true;
    }

    /**
     * Nhập người dùng người hâm mộ WeChat
     * @param array $openids
     * @return bool
     */
    public function importUser(array $noBeOpenids)
    {
        if (!$noBeOpenids) {
            return true;
        }
        $dataAll = $data = [];
        $time = time();
        foreach ($noBeOpenids as $openid) {
            try {
                $info = WechatService::getUserInfo($openid);
                $info = is_object($info) ? $info->toArray() : $info;
            } catch (\Throwable $e) {
                $info = [];
            }
            if (!$info) continue;
            $data['nickname'] = $info['nickname'] ?? '';
            $data['headimgurl'] = $info['headimgurl'] ?? '';
            $userInfoData = $this->setUserInfo($data);
            if (!$userInfoData) {
                throw new AdminException('Lưu trữ thông tin người dùng không thành công');
            }
            $data['uid'] = $userInfoData['uid'];
            $data['subscribe'] = $info['subscribe'] ?? 1;
            $data['unionid'] = $info['unionid'] ?? '';
            $data['openid'] = $info['openid'] ?? '';
            $data['sex'] = $info['sex'] ?? 0;
            $data['language'] = $info['language'] ?? '';
            $data['city'] = $info['city'] ?? '';
            $data['province'] = $info['province'] ?? '';
            $data['country'] = $info['country'] ?? '';
            $data['subscribe_time'] = $info['subscribe_time'] ?? '';
            $data['groupid'] = $info['groupid'] ?? 0;
            $data['remark'] = $info['remark'] ?? '';
            $data['tagid_list'] = isset($info['tagid_list']) && $info['tagid_list'] ? implode(',', $info['tagid_list']) : '';
            $data['add_time'] = $time;
            $data['is_complete'] = 1;
            $dataAll[] = $data;
        }
        if ($dataAll) {
            /** @var WechatUserServices $wechatUser */
            $wechatUser = app()->make(WechatUserServices::class);
            if (!$wechatUser->saveAll($dataAll)) {
                throw new AdminException('Lưu trữ thông tin người dùng không thành công');
            }
        }
        return true;
    }

    /** Sửa đổi thời gian thành viên và trạng thái thành viên
     * @param int $vip_day Số ngày thành viên
     * @param array $user_id người dùngid
     * @param int $is_money_level Kênh nguồn thành viên
     * @param bool $member_type Loại thẻ thành viên
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function setMemberOverdueTime($vip_day, int $user_id, int $is_money_level, $member_type = false)
    {
        if ($vip_day == 0) throw new ApiException('Số ngày không thể0');
        $user_info = $this->getUserInfo($user_id, 'is_money_level,overdue_time');
        if (!$user_info) throw new ApiException('Người dùng không tồn tại');
        if (!$member_type) $member_type = "month";
        if ($member_type == 'ever') {
            $overdue_time = 0;
            $is_ever_level = 1;
        } else {
            if ($user_info['is_money_level'] == 0) {
                $overdue_time = bcadd(bcmul($vip_day, 86400, 0), time(), 0);
            } else {
                $overdue_time = bcadd(bcmul($vip_day, 86400, 0), $user_info['overdue_time'], 0);
            }
            $is_ever_level = 0;
        }
        $setData['overdue_time'] = $overdue_time;
        $setData['is_ever_level'] = $is_ever_level;
        $setData['is_money_level'] = $is_money_level ?: 0;
        // if ($user_info['level'] == 0) $setData['level'] = 1;
        return $this->dao->update(['uid' => $user_id], $setData);
    }

    /**
     * Tư cách thành viên hết hạn và thay đổi trạng thái thành thành viên bình thường
     * @param $uid
     * @param $userInfo
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function offMemberLevel($uid, $userInfo = [])
    {
        if (!$uid) return false;
        if (!$userInfo) {
            $userInfo = $this->dao->get($uid, ['is_ever_level', 'is_money_level', 'overdue_time']);
        }
        if (!$userInfo) return false;
        if ($userInfo['is_ever_level'] == 0 && $userInfo['is_money_level'] > 0 && $userInfo['overdue_time'] < time()) {
            $this->dao->update(['uid' => $uid], ['is_money_level' => 0/*, 'overdue_time' => 0*/]);
            return false;
        }
        return $userInfo;
    }

    /**
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getUserInfoList(array $where, $field = "*")
    {
        return $this->dao->getUserInfoList($where, $field);
    }

    /**
     * Tăng hoa hồng cho việc quảng bá người dùng
     * @param int $uid
     * @param int $spread_uid
     * @param array $userInfo
     * @param array $spread_user
     * @return bool|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function addBrokeragePrice(int $uid, int $spread_uid, array $userInfo = [], array $spread_user = [])
    {
        if (!$uid || !$spread_uid) {
            return false;
        }
        //Chức năng phân phối trung tâm mua sắm có được bật hay không 0 tắt 1 bật
        if (!sys_config('brokerage_func_status')) return true;
        if (!sys_config('brokerage_user_status')) return true;
        //Nhận và đặt đơn giá hoa hồng khuyến mãi
        $brokerage_price = sys_config('uni_brokerage_price', 0);
        //Nhận giới hạn hoa hồng khuyến mãi hàng ngày
        $day_brokerage_price_upper = sys_config('day_brokerage_price_upper', 0);
        if (!floatval($brokerage_price) || !floatval($day_brokerage_price_upper)) {
            return true;
        }
        if (!$userInfo) {
            $userInfo = $this->getUserInfo($uid);
        }
        if (!$userInfo) {
            return false;
        }

        //Kiểm tra xem người dùng này đã đăng xuất dựa trên số điện thoại di động hay chưa, sẽ không phát sinh hoa hồng khuyến mãi
        if ($userInfo['phone'] != '' && $this->dao->getCount(['phone' => $userInfo['phone'], 'is_del' => 1])) {
            return false;
        }
        //Theo truy vấn openid, người dùng này đã đăng xuất và không có hoa hồng khuyến mãi.
        $wechatUserServices = app()->make(WechatUserServices::class);
        $openidArray = $wechatUserServices->getColumn(['uid' => $uid], 'openid', 'id');
        if ($wechatUserServices->getCount([['openid', 'in', $openidArray], ['is_del', '=', 1]])) {
            return false;
        }

        if (!$spread_user) {
            $spread_user = $this->dao->getOne(['uid' => $spread_uid, 'status' => 1]);
        }
        if (!$spread_user) {
            return false;
        }
        if (!$this->checkUserPromoter($spread_uid, $spread_user)) {
            return false;
        }
        /** @var UserBrokerageServices $userBrokerageServices */
        $userBrokerageServices = app()->make(UserBrokerageServices::class);
        // -1không có giới hạn
        if ($day_brokerage_price_upper != -1) {
            if ($day_brokerage_price_upper <= 0) {
                return true;
            } else {
                //Có được người dùng cấp cao và nhận hoa hồng khi quảng bá người dùng ngay hôm nay
                $spread_day_brokerage = $userBrokerageServices->getUserBrokerageSum($spread_uid, ['brokerage_user'], 'today');
                //Vượt quá giới hạn trên
                if (($spread_day_brokerage + $brokerage_price) > $day_brokerage_price_upper) {
                    return true;
                }
            }
        }

        $spreadPrice = $spread_user['brokerage_price'];
        // Số tiền sau khi giảm hoa hồng cho nhà quảng cáo cấp trên
        $balance = bcadd($spreadPrice, $brokerage_price, 2);

        return $this->transaction(function () use ($uid, $spread_uid, $brokerage_price, $userInfo, $balance, $userBrokerageServices) {
            // Thêm hồ sơ giảm giá
            $res1 = $userBrokerageServices->income('get_user_brokerage', $spread_uid, [
                'nickname' => $userInfo['nickname'],
                'number' => floatval($brokerage_price)
            ], $balance, $uid);
            // Thêm số dư người dùng
            $res2 = $this->dao->bcInc($spread_uid, 'brokerage_price', $brokerage_price, 'uid');
            //Gửi tin nhắn mẫu kiếm tiền hoa hồng cho cấp trên của bạn
            /** @var StoreOrderTakeServices $storeOrderTakeServices */
            $storeOrderTakeServices = app()->make(StoreOrderTakeServices::class);
            $storeOrderTakeServices->sendBackOrderBrokerage([], $spread_uid, $brokerage_price, 'user');
            return $res1 && $res2;
        });
    }

    /**
     * Trở nên vượt trộiuid
     * @param int $uid
     * @param array $userInfo
     * @param bool $is_spread
     * @return int|mixed
     */
    public function getSpreadUid(int $uid, $userInfo = [], $is_spread = true)
    {
        if (!$uid) {
            return 0;
        }
        //Chức năng phân phối trung tâm mua sắm có được bật hay không 0 tắt 1 bật
        if (!sys_config('brokerage_func_status')) return -1;
        if (!$userInfo) {
            $userInfo = $this->getUserInfo($uid);
        }
        if (!$userInfo) {
            return 0;
        }
        //Cấp trên không cần kiểm tra việc tự mua
        if ($is_spread) {
            //Bắt đầu tự mua
            $is_self_brokerage = sys_config('is_self_brokerage', 0);
            if ($is_self_brokerage && $is_spread) {
                return $uid;
            }
        }

        //loại ràng buộc
        $store_brokergae_binding_status = sys_config('store_brokerage_binding_status', 1);
        if ($store_brokergae_binding_status == 1 || $store_brokergae_binding_status == 3) {
            return $userInfo['spread_uid'];
        }
        //Loại ràng buộc phân phối là khoảng thời gian và chưa hết hạn.
        $store_brokerage_binding_time = sys_config('store_brokerage_binding_time', 30);
        if ($store_brokergae_binding_status == 2 && ($userInfo['spread_time'] + $store_brokerage_binding_time * 24 * 3600) > time()) {
            return $userInfo['spread_uid'];
        }
        return -1;
    }

    /**
     * Lấy danh sách bộ phận/đại lý/nhân viên
     * @param array $where
     * @param string $field
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getDivisionList(array $where = [], string $field = '*')
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getList($where, $field, $page, $limit);
        $count = $this->dao->count($where);
        return compact('list', 'count');
    }

    /**
     * Thêm thông tin khi chỉnh sửa thông tin người dùng
     * @param $uid
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getUserSaveInfo($uid)
    {
        /** @var UserLabelServices $userLabelServices */
        $userLabelServices = app()->make(UserLabelServices::class);
        /** @var UserLabelRelationServices $userLabelRelationServices */
        $userLabelRelationServices = app()->make(UserLabelRelationServices::class);
        /** @var UserLabelCateServices $userLabelCateServices */
        $userLabelCateServices = app()->make(UserLabelCateServices::class);
        /** @var UserGroupServices $userGroupServices */
        $userGroupServices = app()->make(UserGroupServices::class);
        /** @var SystemUserLevelServices $systemUserLevelServices */
        $systemUserLevelServices = app()->make(SystemUserLevelServices::class);
        $userInfo = $this->dao->get($uid);
        if ($userInfo) {
            $label_ids = $userLabelRelationServices->getUserLabels($uid);
            $userInfo['label_id'] = !empty($label_ids) ? $userLabelServices->getLabelList(['ids' => $label_ids], ['id', 'label_name']) : [];
            $userInfo['birthday'] = (int)$userInfo['birthday'] ? date('Y-m-d', (int)$userInfo['birthday']) : '';
            $userInfo['level'] = $userInfo['level'] != 0 ? $userInfo['level'] : '';
            $userInfo['group_id'] = $userInfo['group_id'] != 0 ? $userInfo['group_id'] : '';
            if ($userInfo['addres'] == '') {
                $defaultAddressInfo = app()->make(UserAddressServices::class)->getUserDefaultAddress($uid);
                if ($defaultAddressInfo) {
                    $userInfo['addres'] = $defaultAddressInfo['province'] . $defaultAddressInfo['city'] . $defaultAddressInfo['district'] . $defaultAddressInfo['detail'];
                } else {
                    $userInfo['addres'] = '';
                }
            }
        }
        $levelInfo = $systemUserLevelServices->getWhereLevelList([], 'id,name');
        $groupInfo = $userGroupServices->getGroupList();
        $labelInfo = $userLabelCateServices->getUserLabel($uid);
        return compact('userInfo', 'levelInfo', 'groupInfo', 'labelInfo');
    }


    /**
     * Phần thưởng đăng ký người dùng mới
     * @param int $id
     * @return bool
     * @throws Exception
     *
     * @date 2022/09/28
     * @author yyw
     */
    public function rewardNewUser(int $id)
    {
        $user = $this->getUserInfo($id);
        if (!$user) {
            throw new AdminException('Dữ liệu không tồn tại');
        }
        $res1 = false;
        $res2 = false;
        $reward_money = sys_config('reward_money');
        $reward_integral = sys_config('reward_integral');
        $edit = array();
        if ($reward_money > 0) {//số dư tăng lên
            /** @var UserMoneyServices $userMoneyServices */
            $userMoneyServices = app()->make(UserMoneyServices::class);
            $edit['now_money'] = bcadd($user['now_money'], $reward_money, 2);
            $res1 = $userMoneyServices->income('register_system_add', $user['uid'], $reward_money, $edit['now_money'], 1);
        } else {
            $res1 = true;
        }
        if ($reward_integral > 0) {//Điểm tăng
            /** @var UserBillServices $userBill */
            $userBill = app()->make(UserBillServices::class);
            $integral_data = ['link_id' => 1, 'number' => $reward_integral];
            $edit['integral'] = bcadd($user['integral'], $reward_integral, 2);
            $integral_data['balance'] = $edit['integral'];
            $integral_data['title'] = 'Đăng ký người dùng mới tăng điểm';
            $integral_data['mark'] = 'Số lượt đăng ký người dùng mới tăng lên' . floatval($reward_integral) . 'tích phân';
            $res2 = $userBill->incomeIntegral($user['uid'], 'system_add', $integral_data);
        } else {
            $res2 = true;
        }
        if ($edit) {
            $res3 = $this->dao->update($id, $edit);
        } else {
            $res3 = true;
        }
        if ($res1 && $res2 && $res3) {
            return true;
        } else {
            throw new AdminException('Sửa đổi không thành công');
        }
    }

    /**
     * Đẩy thông tin người dùng
     * @param $data
     * @param $pushUrl
     * @return bool
     */
    public function userUpdate($data, $pushUrl)
    {
        return out_push($pushUrl, $data, 'Cập nhật thông tin người dùng');
    }
}
