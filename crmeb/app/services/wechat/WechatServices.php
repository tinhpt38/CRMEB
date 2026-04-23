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
use app\services\user\UserServices;
use app\services\user\UserVisitServices;
use crmeb\exceptions\ApiException;
use crmeb\services\app\MiniProgramService;
use crmeb\services\CacheService;
use crmeb\services\app\WechatService as WechatAuthService;
use crmeb\services\oauth\OAuth;
use crmeb\services\pay\Pay;
use crmeb\utils\Canvas;

/**
 *
 * Class WechatServices
 * @package app\services\wechat
 * @method value(array $where, ?string $field)
 */
class WechatServices extends BaseServices
{

    /**
     * WechatServices constructor.
     * @param WechatUserDao $dao
     */
    public function __construct(WechatUserDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Dịch vụ tài khoản công cộng WeChat
     * @return \think\Response
     * @throws \EasyWeChat\Server\BadRequestException
     */
    public function serve()
    {
        ob_clean();
        return WechatAuthService::serve();
    }

    /**
     * Dịch vụ tài khoản công cộng WeChat
     * @return \think\Response
     * @throws \EasyWeChat\Server\BadRequestException
     */
    public function miniServe()
    {
        ob_clean();
        return MiniProgramService::serve();
    }

    /**
     * Trả tiền gọi lại không đồng bộ
     * @return string
     * @throws \EasyWeChat\Core\Exceptions\FaultException
     */
    public function notify()
    {
        ob_clean();
        return WechatAuthService::handleNotify()->getContent();
    }

    /**
     * v3Hoàn vốn
     * @return string
     * @throws \EasyWeChat\Core\Exceptions\FaultException
     * @author Chờ gió tới
     * @email 136327134@qq.com
     * @date 2022/9/22
     */
    public function v3notify()
    {
        /** @var Pay $pay */
        $pay = app()->make(Pay::class, ['v3_wechat_pay']);
        return $pay->handleNotify()->getContent();
    }

    /**
     * Lấy thông tin cấu hình quyền tài khoản công cộng
     * @param $url
     * @return mixed
     */
    public function config($url)
    {
        return json_decode(WechatAuthService::jsSdk($url), true);
    }

    /**
     * Tài khoản chính thức được ủy quyền đăng nhập, quay lạitoken
     * @param $spread
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/12
     */
    public function authLogin($spread = '', $agent_id = '')
    {
        /** @var OAuth $oauth */
        $oauth = app()->make(OAuth::class);
        $wechatInfo = $oauth->oauth();
        if (!isset($wechatInfo['nickname'])) {
            $wechatInfo = $oauth->getUserInfo($wechatInfo['openid']);
            if (!isset($wechatInfo['nickname']))
                throw new ApiException('Ủy quyền không thành công');
            if (isset($wechatInfo['tagid_list']))
                $wechatInfo['tagid_list'] = implode(',', $wechatInfo['tagid_list']);
        } else {
            if (isset($wechatInfo['privilege'])) unset($wechatInfo['privilege']);
        }
        $wechatInfo['user_type'] = 'wechat';
        $openid = $wechatInfo['openid'];
        /** @var WechatUserServices $wechatUserServices */
        $wechatUserServices = app()->make(WechatUserServices::class);
        $user = $wechatUserServices->getAuthUserInfo($openid, 'wechat');
        $createData = [$openid, $wechatInfo, $spread, $agent_id, 'wechat', 'wechat'];
        $storeUserMobile = sys_config('store_user_mobile');
        if ($storeUserMobile && (($user && $user['phone'] == '') || !$user)) {
            $userInfoKey = md5($openid . '_' . time() . '_wechat');
            CacheService::set($userInfoKey, $createData, 7200);
            return ['bindPhone' => true, 'key' => $userInfoKey];
        }
        $user = $wechatUserServices->wechatOauthAfter($createData);
        $wechatUserServices->wechatUpdata([$user['uid'], $wechatInfo]);
        $token = $this->createToken((int)$user['uid'], 'api');
        if ($token) {
            app()->make(UserVisitServices::class)->loginSaveVisit($user);
            $token['bindPhone'] = false;
            return [
                'token' => $token['token'],
                'expires_time' => $token['params']['exp'],
                'bindPhone' => false
            ];
        } else {
            throw new ApiException('Đăng nhập không thành công');
        }
    }

    /**
     * Tài khoản chính thức buộc phải ràng buộc số điện thoại di động
     * @param $key
     * @param $phone
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/12
     */
    public function authBindingPhone($key, $phone)
    {
        [$openid, $wechatInfo, $spreadId, $agent_id, $login_type, $userType] = CacheService::get($key);
        $wechatInfo['phone'] = $phone;
        //Viết thông tin người dùng
        $user = app()->make(WechatUserServices::class)->wechatOauthAfter([$openid, $wechatInfo, $spreadId, $agent_id, $login_type, $userType]);
        $token = $this->createToken((int)$user['uid'], 'api');
        if ($token) {
            app()->make(UserVisitServices::class)->loginSaveVisit($user);
            return [
                'token' => $token['token'],
                'expires_time' => $token['params']['exp'],
                'bindName' => false
            ];
        } else {
            throw new ApiException('Đăng nhập không thành công');
        }
    }

    /**
     * Thu hút sự chú ý bằng mã QR
     * @return string[]
     * @throws \Exception
     */
    public function follow()
    {
        $canvas = Canvas::instance();
        $path = 'uploads/follow/';
        $imageType = 'jpg';
        $name = 'follow';
        $siteUrl = sys_config('site_url');
        $imageUrl = $path . $name . '.' . $imageType;
        $canvas->setImageUrl('statics/qrcode/follow.png')->setImageHeight(720)->setImageWidth(500)->pushImageValue();
        $wechatQrcode = sys_config('wechat_qrcode');
        if (($strlen = stripos($wechatQrcode, 'uploads')) !== false) {
            $wechatQrcode = substr($wechatQrcode, $strlen);
        }
        if (!$wechatQrcode)
            throw new ApiException('Vui lòng tải lên mã QR');
        $canvas->setImageUrl($wechatQrcode)->setImageHeight(344)->setImageWidth(344)->setImageLeft(76)->setImageTop(76)->pushImageValue();
        $image = $canvas->setFileName($name)->setImageType($imageType)->setPath($path)->setBackgroundWidth(500)->setBackgroundHeight(720)->starDrawChart();
        return ['path' => $image ? $siteUrl . '/' . $image : ''];
    }

    /**
     * Bạn có chú ý không?
     * @param int $uid
     * @return bool
     */
    public function isSubscribe(int $uid)
    {
        if ($uid) {
            $subscribe = (bool)$this->dao->value(['uid' => $uid], 'subscribe');
        } else {
            $subscribe = true;
        }
        return $subscribe;
    }

    /**
     * appĐăng nhập
     * @param array $userData
     * @param string $phone
     * @param string $userType
     * @return array|false
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function appAuth(array $userData, string $phone, string $userType = 'app')
    {
        $openid = $userData['openId'] ?? "";
        $userInfo = [
            'phone' => $phone,
            'unionid' => $userData['unionId'] ?? '',
            'headimgurl' => $userData['avatarUrl'] ?? '',
            'nickname' => $userData['nickName'] ?? '',
            'province' => $userData['province'] ?? '',
            'country' => $userData['country'] ?? '',
            'city' => $userData['city'] ?? '',
            'openid' => $openid,
        ];
        $login_type = $userType;
        $spreadId = $userInfo['spreadId'] ?? "";
        if (!$phone) {
            //Nhận xem có buộc liên kết số điện thoại di động hay không
            $storeUserMobile = sys_config('store_user_mobile');
            if ($userInfo['unionid'] && $storeUserMobile) {
                /** @var UserServices $userServices */
                $userServices = app()->make(UserServices::class);
                $uid = $this->dao->value(['unionid' => $userInfo['unionid'], 'is_del' => 0], 'uid');
                $res = $userServices->value(['uid' => $uid, 'is_del' => 0], 'phone');
                if (!$uid && !$res) {
                    return false;
                }
            } elseif ($openid && $storeUserMobile) {
                /** @var UserServices $userServices */
                $userServices = app()->make(UserServices::class);
                $uid = $this->dao->value(['openid' => $openid], 'uid');
                $res = $userServices->value(['uid' => $uid], 'phone');
                if (!$uid && !$res) {
                    return false;
                }
            }
        }
        /** @var WechatUserServices $wechatUser */
        $wechatUser = app()->make(WechatUserServices::class);
        //Cập nhật thông tin người dùng
        $user = $wechatUser->wechatOauthAfter([$openid, $userInfo, $spreadId, 0, $login_type, $userType]);
        $token = $this->createToken((int)$user['uid'], 'api');
        if ($token) {
            /** @var UserVisitServices $visitServices */
            $visitServices = app()->make(UserVisitServices::class);
            $visitServices->loginSaveVisit($user);
            return [
                'token' => $token['token'],
                'userInfo' => $user,
                'expires_time' => $token['params']['exp'],
                'isbind' => false
            ];
        } else
            throw new ApiException('Đăng nhập không thành công');
    }
}
