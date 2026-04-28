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

namespace app\services\wechat;

use app\services\BaseServices;
use app\dao\wechat\WechatUserDao;
use app\services\message\SystemNotificationServices;
use app\services\other\QrcodeServices;
use app\services\user\LoginServices;
use app\services\user\UserServices;
use app\services\user\UserVisitServices;
use crmeb\exceptions\ApiException;
use crmeb\services\CacheService;
use crmeb\services\app\MiniProgramService;
use crmeb\services\oauth\OAuth;

/**
 *
 * Class RoutineServices
 * @package app\services\wechat
 */
class RoutineServices extends BaseServices
{

    /**
     * RoutineServices constructor.
     * @param WechatUserDao $dao
     */
    public function __construct(WechatUserDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Trả về khóa bộ đệm của thông tin người dùng và trả về việc có buộc buộc liên kết số điện thoại di động hay không.
     * @param $code
     * @param $spread
     * @param $spid
     * @return array
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/12
     */
    public function authType($code, $spread, $spid)
    {
        $agent_id = 0;
        $userInfoConfig = app()->make(OAuth::class, ['mini_program'])->oauth($code, ['silence' => true]);
        if (!isset($userInfoConfig['openid'])) {
            throw new ApiException('Ủy quyền im lặng không thành công');
        }
        $routineInfo = ['unionid' => $userInfoConfig['unionid'] ?? ''];
        $info = app()->make(QrcodeServices::class)->getOne(['id' => $spread, 'status' => 1]);
        if ($spread && $info) {
            if ($info['third_type'] == 'agent') {
                $agent_id = $info['third_id'];
            } else {
                $spid = $info['third_id'];
            }
        }
        $openid = $userInfoConfig['openid'];
        $routineInfo['openid'] = $openid;
        $routineInfo['spid'] = $spid;
        $routineInfo['code'] = $spread;
        $routineInfo['session_key'] = $userInfoConfig['session_key'];
        $routineInfo['headimgurl'] = sys_config('h5_avatar');
        $createData = [$openid, $routineInfo, $spid, $agent_id, 'routine', 'routine'];
        $userInfoKey = md5($openid . '_' . time() . '_routine');
        CacheService::set($userInfoKey, $createData, 7200);
        $bindPhone = false;
        $user = app()->make(WechatUserServices::class)->getAuthUserInfo($openid, 'routine');
        if (sys_config('store_user_mobile') && (($user && $user['phone'] == '') || !$user)) $bindPhone = true;
        return ['bindPhone' => $bindPhone, 'key' => $userInfoKey];
    }

    /**
     * Nhận từ bộ đệmtoken
     * @param $key
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/12
     */
    public function authLogin($key)
    {
        $createData = CacheService::get($key);
        //Viết thông tin người dùng
        $user = app()->make(WechatUserServices::class)->wechatOauthAfter($createData);
        $token = $this->createToken((int)$user['uid'], 'api');
        if ($token) {
            app()->make(UserVisitServices::class)->loginSaveVisit($user);
            return [
                'token' => $token['token'],
                'expires_time' => $token['params']['exp'],
                'bindName' => (int)sys_config('get_avatar') && $user['avatar'] == sys_config('h5_avatar'),
            ];
        } else {
            throw new ApiException('Đăng nhập không thành công');
        }
    }

    /**
     * Tự động lấy liên kết số điện thoại di động
     * @param $code
     * @param $iv
     * @param $encryptedData
     * @param $spread
     * @param $spid
     * @param string $key
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function authBindingPhone($code, $iv, $encryptedData, $spread, $spid, $key = '')
    {
        $wechatInfo = [];
        $agent_id = 0;
        $userType = $login_type = 'routine';
        if ($key) {
            [$openid, $wechatInfo, $spreadId, $agent_id, $login_type, $userType] = CacheService::get($key);
        }

        /** @var OAuth $oauth */
        $oauth = app()->make(OAuth::class, ['mini_program']);
        [$userInfoCong, $userInfo] = $oauth->oauth($code, [
            'iv' => $iv,
            'encryptedData' => $encryptedData
        ]);
        $session_key = $userInfoCong['session_key'];
        if (!$userInfo || !isset($userInfo['purePhoneNumber'])) {
            throw new ApiException('Không thể lấy được thông tin người dùng');
        }

        $spreadId = $spid ?? 0;
        /** @var QrcodeServices $qrcode */
        $qrcode = app()->make(QrcodeServices::class);
        if ($spread && ($info = $qrcode->getOne(['id' => $spread, 'status' => 1]))) {
            $spreadId = $info['third_id'];
        }
        $openid = $userInfoCong['openid'];
        $wechatInfo['openid'] = $openid;
        $wechatInfo['unionid'] = $userInfoCong['unionid'] ?? '';
        $wechatInfo['spid'] = $spreadId;
        $wechatInfo['code'] = $spread;
        $wechatInfo['session_key'] = $session_key;
        $wechatInfo['phone'] = $userInfo['purePhoneNumber'];
        /** @var WechatUserServices $wechatUserServices */
        $wechatUserServices = app()->make(WechatUserServices::class);
        //Viết thông tin người dùng
        $user = $wechatUserServices->wechatOauthAfter([$openid, $wechatInfo, $spreadId, $agent_id, $login_type, $userType]);
        $token = $this->createToken((int)$user['uid'], 'api');
        if ($token) {
            app()->make(UserVisitServices::class)->loginSaveVisit($user);
            return [
                'token' => $token['token'],
                'expires_time' => $token['params']['exp'],
                'bindName' => (int)sys_config('get_avatar') && $user['avatar'] == sys_config('h5_avatar'),
            ];
        } else {
            throw new ApiException('Đăng nhập không thành công');
        }
    }

    /**
     * Chương trình nhỏ đăng nhập số điện thoại di động
     * @param $key
     * @param $phone
     * @param string $spread_code
     * @param string $spread_spid
     * @param string $code
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/12
     */
    public function phoneLogin($key, $phone, $spread = '', $agent_id = '', $spid = '', $code = '')
    {
        if ($code == '') {
            [$openid, $routineInfo, $spid, $agent_id, $login_type, $userType] = CacheService::get($key);
            $routineInfo['phone'] = $phone;
            $createData = [$openid, $routineInfo, $spid, $agent_id, $login_type, $userType];
        } else {
            $userInfoConfig = app()->make(OAuth::class, ['mini_program'])->oauth($code, ['silence' => true]);
            if (!isset($userInfoConfig['openid'])) {
                throw new ApiException('Ủy quyền im lặng không thành công');
            }
            $routineInfo = ['unionid' => $userInfoConfig['unionid'] ?? ''];
            $info = app()->make(QrcodeServices::class)->getOne(['id' => $spread, 'status' => 1]);
            if ($spread && $info) {
                $spid = $info['third_id'];
            }
            $openid = $userInfoConfig['openid'];
            $routineInfo['openid'] = $openid;
            $routineInfo['spid'] = $spid;
            $routineInfo['code'] = $spread;
            $routineInfo['session_key'] = $userInfoConfig['session_key'];
            $routineInfo['headimgurl'] = sys_config('h5_avatar');
            $routineInfo['phone'] = $phone;
            $createData = [$openid, $routineInfo, $spid, $agent_id, 'routine', 'routine'];
        }
        //Viết thông tin người dùng
        $user = app()->make(WechatUserServices::class)->wechatOauthAfter($createData);
        $token = $this->createToken((int)$user['uid'], 'api');
        if ($token) {
            app()->make(UserVisitServices::class)->loginSaveVisit($user);
            return [
                'token' => $token['token'],
                'expires_time' => $token['params']['exp'],
                'bindName' => (int)sys_config('get_avatar') && $user['avatar'] == sys_config('h5_avatar'),
            ];
        } else {
            throw new ApiException('Đăng nhập không thành công');
        }
    }

    /**
     * Chương trình mini liên kết số điện thoại di động
     * @param $code
     * @param $iv
     * @param $encryptedData
     * @return bool
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2023/02/24
     */
    public function bindingPhone($code, $iv, $encryptedData)
    {
        [$userInfoCong, $userInfo] = app()->make(OAuth::class, ['mini_program'])->oauth($code, [
            'iv' => $iv,
            'encryptedData' => $encryptedData
        ]);
        if (!$userInfo || !isset($userInfo['purePhoneNumber'])) {
            throw new ApiException('Không thể lấy được thông tin người dùng');
        }
        $uid = app()->make(WechatUserServices::class)->openidToUid($userInfoCong['openid']);
        $userServices = app()->make(UserServices::class);
        if ($userServices->count(['phone' => $userInfo['purePhoneNumber'], 'is_del' => 0])) {
            throw new ApiException('Số điện thoại di động đã được đăng ký');
        }
        $res = $userServices->update(['uid' => $uid], ['phone' => $userInfo['purePhoneNumber']]);
        if ($res) return true;
        throw new ApiException('Liên kết không thành công');
    }

    /**
     * Applet trả về sau khi tạo người dùnguid
     * @param $routine
     * @return array
     */
    public function routineOauth($routine)
    {
        $routineInfo['nickname'] = filter_emoji($routine['nickName']);//Tên
        $routineInfo['sex'] = $routine['gender'];//giới tính
        $routineInfo['language'] = $routine['language'];//ngôn ngữ
        $routineInfo['city'] = $routine['city'];//Thành phố
        $routineInfo['province'] = $routine['province'];//tỉnh
        $routineInfo['country'] = $routine['country'];//Quốc gia
        $routineInfo['headimgurl'] = $routine['avatarUrl'];//hình đại diện
        $routineInfo['openid'] = $routine['openId'];
        $routineInfo['session_key'] = $routine['session_key'];//khóa phiên
        $routineInfo['unionid'] = $routine['unionId'];//Mã định danh duy nhất của người dùng trên nền tảng mở
        $routineInfo['user_type'] = 'routine';//Loại người dùng
        $routineInfo['phone'] = $routine['phone'] ?? $routine['purePhoneNumber'] ?? '';
        $spid = $routine['spid'] ?? 0;//Uid mối quan hệ ràng buộc
        //Nhận xem có quét mã để vào chương trình mini hay không
        /** @var QrcodeServices $qrcode */
        $qrcode = app()->make(QrcodeServices::class);
        if (isset($routine['code']) && $routine['code'] && ($info = $qrcode->get($routine['code']))) {
            $spid = $info['third_id'];
        }
        return [$routine['openId'], $routineInfo, $spid, $routine['login_type'] ?? 'routine', 'routine'];
    }

    /**
     * Gọi lại thanh toán chương trình nhỏ
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \EasyWeChat\Core\Exceptions\FaultException
     */
    public function notify()
    {
        return MiniProgramService::handleNotify();
    }

    /**
     * Nhận tin nhắn đăng ký chương trình nhỏid
     * @return bool|mixed|null
     */
    public function tempIds()
    {
        return CacheService::remember('TEMP_IDS_LIST', function () {
            /** @var SystemNotificationServices $sysNotify */
            $sysNotify = app()->make(SystemNotificationServices::class);
            return $sysNotify->getColumn([['routine_tempid', '<>', '']], 'routine_tempid', 'mark');
        });
    }

    /**
     * Nhận danh sách phát sóng trực tiếp chương trình mini
     * @param $page
     * @param $limit
     * @return array|bool|mixed
     */
    public function live($page, $limit)
    {
        $list = CacheService::remember('WECHAT_LIVE_LIST_' . $page . '_' . $limit, function () use ($page, $limit) {
            $list = MiniProgramService::getLiveInfo((int)$page, (int)$limit);
            foreach ($list as &$item) {
                $item['_start_time'] = date('m-d H:i', $item['start_time']);
            }
            return $list;
        }, 600) ?: [];
        return $list;
    }

    /**
     * Cập nhật thông tin người dùng
     * @param $uid
     * @param array $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function updateUserInfo($uid, array $data)
    {
        /** @var UserServices $userServices */
        $userServices = app()->make(UserServices::class);
        $user = $userServices->getUserInfo($uid);
        if (!$user) {
            throw new ApiException('Dữ liệu không tồn tại');
        }
        $userInfo = [];
        $userInfo['nickname'] = filter_emoji($data['nickName'] ?? '');//Tên
        $userInfo['sex'] = $data['gender'] ?? '';//giới tính
        $userInfo['language'] = $data['language'] ?? '';//ngôn ngữ
        $userInfo['city'] = $data['city'] ?? '';//Thành phố
        $userInfo['province'] = $data['province'] ?? '';//tỉnh
        $userInfo['country'] = $data['country'] ?? '';//Quốc gia
        $userInfo['headimgurl'] = $data['avatarUrl'] ?? '';//hình đại diện
        $userInfo['is_complete'] = 1;
        /** @var LoginServices $loginService */
        $loginService = app()->make(LoginServices::class);
        $loginService->updateUserInfo($userInfo, $user);
        //Cập nhật thông tin người dùng
        if (!$this->dao->update(['uid' => $user['uid'], 'user_type' => 'routine'], $userInfo)) {
            throw new ApiException('Cập nhật không thành công');
        }
        return true;
    }
}
