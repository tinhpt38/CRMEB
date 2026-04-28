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

namespace app\services\system\admin;

use app\jobs\CheckQueueJob;
use app\services\BaseServices;
use app\services\order\StoreOrderServices;
use app\services\product\product\StoreProductReplyServices;
use app\services\product\product\StoreProductServices;
use app\services\system\log\SystemFileServices;
use app\services\user\UserExtractServices;
use crmeb\exceptions\AdminException;
use app\dao\system\admin\SystemAdminDao;
use app\services\system\SystemMenusServices;
use app\services\other\CacheServices;
use crmeb\services\CacheService;
use crmeb\services\FormBuilder;
use crmeb\services\workerman\ChannelService;
use think\facade\Config;
use think\facade\Event;
use think\Model;

/**
 * quản trị viênservice
 * Class SystemAdminServices
 * @package app\services\system\admin
 * @method getAdminIds(int $level) Nhận quản trị viên dựa trên cấp độ quản trị viênid
 * @method getOrdAdmin(string $field, int $level) Lấy tên của quản trị viên dưới cấp độ vàid
 */
class SystemAdminServices extends BaseServices
{

    /**
     * formTạo biểu mẫu
     * @var FormBuilder
     */
    protected $builder;

    /**
     * SystemAdminServices constructor.
     * @param SystemAdminDao $dao
     */
    public function __construct(SystemAdminDao $dao, FormBuilder $builder)
    {
        $this->dao = $dao;
        $this->builder = $builder;
    }

    /**
     * Đăng nhập quản trị viên
     * @param string $account
     * @param string $password
     * @return array|bool|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function verifyLogin(string $account, string $password)
    {
        $adminInfo = $this->dao->accountByAdmin($account);
        if (!$adminInfo || !password_verify($password, $adminInfo->pwd)) return false;
        if (!$adminInfo->status) {
            throw new AdminException('Bạn đã bị cấm đăng nhập');
        }
        $adminInfo->last_time = time();
        $adminInfo->last_ip = app('request')->ip();
        $adminInfo->login_count++;
        $adminInfo->save();

        return $adminInfo;
    }

    /**
     * Đăng nhập quản trị viên tập tin
     * @param string $account
     * @param string $password
     * @return array|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function verifyFileLogin(string $account, string $password)
    {
        $adminInfo = $this->dao->accountByAdmin($account);
        if (!$adminInfo) {
            throw new AdminException('Quản trị viên không tồn tại');
        }
        if (!$adminInfo->status) {
            throw new AdminException('Bạn đã bị cấm đăng nhập');
        }
        if (!password_verify($password, $adminInfo->file_pwd)) {
            throw new AdminException('Tài khoản hoặc mật khẩu không chính xác');
        }
        $adminInfo->last_time = time();
        $adminInfo->last_ip = app('request')->ip();
        $adminInfo->login_count++;
        $adminInfo->save();

        return $adminInfo;
    }

    /**
     * Đăng nhập phụ trợ để nhận menutoken
     * @param string $account
     * @param string $password
     * @param string $type
     * @return array|bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function login(string $account, string $password, string $type, string $key = '')
    {
        $adminInfo = $this->verifyLogin($account, $password);
        if (!$adminInfo) return false;
        $tokenInfo = $this->createToken($adminInfo->id, $type, $adminInfo->pwd);
        /** @var SystemMenusServices $services */
        $services = app()->make(SystemMenusServices::class);
        [$menus, $uniqueAuth] = $services->getMenusList($adminInfo->roles, (int)$adminInfo['level']);
        $remind = Config::get('app.console_remind', false);
        if ($remind) {
            [$queue, $timer] = Event::until('AdminLoginListener', [$key]);
        }

        //Đăng nhập quản trị viên sự kiện tùy chỉnh
        event('CustomEventListener', ['admin_login', [
            'id' => $adminInfo->getData('id'),
            'account' => $adminInfo->getData('account'),
            'head_pic' => get_file_link($adminInfo->getData('head_pic')),
            'level' => $adminInfo->getData('level'),
            'real_name' => $adminInfo->getData('real_name'),
            'login_time' => date('Y-m-d H:i:s'),
        ]]);

        return [
            'token' => $tokenInfo['token'],
            'expires_time' => $tokenInfo['params']['exp'],
            'menus' => $menus,
            'unique_auth' => $uniqueAuth,
            'user_info' => [
                'id' => $adminInfo->getData('id'),
                'account' => $adminInfo->getData('account'),
                'head_pic' => get_file_link($adminInfo->getData('head_pic')),
                'level' => $adminInfo->getData('level'),
                'real_name' => $adminInfo->getData('real_name'),
            ],
            'logo' => sys_config('site_logo'),
            'logo_square' => sys_config('site_logo_square'),
            'version' => get_crmeb_version(),
            'newOrderAudioLink' => get_file_link(sys_config('new_order_audio_link', '')),
            'queue' => $queue ?? true,
            'timer' => $timer ?? true,
            'site_name' => sys_config('site_name'),
            'site_func' => sys_config('model_checkbox', ['seckill', 'bargain', 'combination']),
        ];
    }

    /**
     * Nhận thông tin đăng nhập và các thông tin khác trước khi đăng nhập
     * @return array
     */
    public function getLoginInfo()
    {
        $key = uniqid();
        CheckQueueJob::dispatchSecs(1, [$key]);
        $data = [
            'slide' => sys_data('admin_login_slide') ?? [],
            'logo_square' => sys_config('site_logo_square'), //trong suốt
            'logo_rectangle' => sys_config('site_logo'), //quảng trường
            'login_logo' => sys_config('login_logo'), //Đăng nhập
            'site_name' => sys_config('site_name'),
            'copyright' => sys_config('nncnL_crmeb_copyright', ''),
            'version' => get_crmeb_version(),
            'key' => $key,
            'login_captcha' => 0
        ];

        try {
            $cacheServices = app()->make(CacheServices::class);
            if (!$cacheServices->checkDbCache('write_md5', get_crmeb_version_vode())) {
                // Thực hiện ghi dữ liệu
                app()->make(SystemFileServices::class)->writeMd5();
                $cacheServices->setDbCache('write_md5', get_crmeb_version_vode());
            }
        } catch (\ReflectionException $e) {
        }

        if (CacheService::get('login_captcha', 1) > 1) {
            $data['login_captcha'] = 1;
        }
        return $data;
    }

    /**
     * Danh sách quản trị viên
     * @param array $where
     * @return array
     */
    public function getAdminList(array $where)
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getList($where, $page, $limit);
        $count = $this->dao->count($where);

        /** @var SystemRoleServices $service */
        $service = app()->make(SystemRoleServices::class);
        $allRole = $service->getRoleArray();
        foreach ($list as &$item) {
            if ($item['roles']) {
                $roles = [];
                foreach ($item['roles'] as $id) {
                    if (isset($allRole[$id])) $roles[] = $allRole[$id];
                }
                if ($roles) {
                    $item['roles'] = implode(',', $roles);
                } else {
                    $item['roles'] = '';
                }
            }
            $item['_add_time'] = date('Y-m-d H:i:s', $item['add_time']);
            $item['_last_time'] = $item['last_time'] ? date('Y-m-d H:i:s', $item['last_time']) : '';
        }
        return compact('list', 'count');
    }

    /**
     * Tạo biểu mẫu quản trị
     * @param int $level
     * @param array $formData
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function createAdminForm(int $level, array $formData = [])
    {
        $f[] = $this->builder->input('account', 'Tài khoản quản trị viên', $formData['account'] ?? '')->required('Vui lòng điền vào tài khoản quản trị viên');
        if (empty($formData)) {
            $f[] = $this->builder->input('pwd', 'Mật khẩu quản trị viên')->type('password')->required('Vui lòng điền mật khẩu quản trị viên');
            $f[] = $this->builder->input('conf_pwd', 'Xác nhận mật khẩu')->type('password')->required('Vui lòng nhập mật khẩu xác nhận');
        } else {
            $f[] = $this->builder->input('pwd', 'Mật khẩu quản trị viên')->type('password');
            $f[] = $this->builder->input('conf_pwd', 'Xác nhận mật khẩu')->type('password');
        }
        $f[] = $this->builder->input('real_name', 'Tên quản trị viên', $formData['real_name'] ?? '')->required('Vui lòng nhập tên quản trị viên');

        /** @var SystemRoleServices $service */
        $service = app()->make(SystemRoleServices::class);
        $options = $service->getRoleFormSelect($level);
        if (isset($formData['roles'])) {
            foreach ($formData['roles'] as &$item) {
                $item = intval($item);
            }
        }
        $f[] = $this->builder->select('roles', 'Vai trò quản trị viên', $formData['roles'] ?? [])->setOptions(FormBuilder::setOptions($options))->multiple(true)->required('Vui lòng chọn vai trò quản trị viên');
        $f[] = $this->builder->radio('status', 'Trạng thái', $formData['status'] ?? 1)->options([['label' => 'Hoạt động', 'value' => 1], ['label' => 'đóng cửa', 'value' => 0]]);
        return $f;
    }

    /**
     * Thêm biểu mẫu quản trị viên để có được
     * @param int $level
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function createForm(int $level)
    {
        return create_form('Quản trị viên đã thêm', $this->createAdminForm($level), $this->url('/setting/admin'));
    }

    /**
     * Tạo quản trị viên
     * @param array $data
     * @return bool
     */
    public function create(array $data)
    {
        if ($data['conf_pwd'] != $data['pwd']) {
            throw new AdminException('Mật khẩu nhập hai lần không nhất quán');
        }
        unset($data['conf_pwd']);

        if (strlen(trim($data['pwd'])) < 6 || strlen(trim($data['pwd'])) > 32) {
            throw new AdminException('Mật khẩu tài khoản phải từ 6 đến 32 ký tự');
        }

        if ($this->dao->count(['account' => $data['account'], 'is_del' => 0])) {
            throw new AdminException('Tài khoản quản trị viên đã tồn tại');
        }

        $data['pwd'] = $this->passwordHash($data['pwd']);
        $data['add_time'] = time();
        $data['roles'] = implode(',', $data['roles']);
        $data['head_pic'] = '/statics/system_images/admin_head_pic.png';

        return $this->transaction(function () use ($data) {
            if ($this->dao->save($data)) {
                return true;
            } else {
                throw new AdminException('Thêm không thành công');
            }
        });
    }

    /**
     * Sửa đổi biểu mẫu quản trị viên
     * @param int $level
     * @param int $id
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function updateForm(int $level, int $id)
    {
        $adminInfo = $this->dao->get($id);
        if (!$adminInfo) {
            throw new AdminException('Quản trị viên không tồn tại');
        }
        if ($adminInfo->is_del) {
            throw new AdminException('Quản trị viên đã xóa');
        }
        return create_form('Được sửa đổi bởi quản trị viên', $this->createAdminForm($level, $adminInfo->toArray()), $this->url('/setting/admin/' . $id), 'PUT');
    }

    /**
     * Sửa đổi quản trị viên
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function save(int $id, array $data)
    {
        if (!$adminInfo = $this->dao->get($id)) {
            throw new AdminException('Quản trị viên không tồn tại');
        }
        if ($adminInfo->is_del) {
            throw new AdminException('Quản trị viên đã xóa');
        }
        //Thay đổi mật khẩu
        if ($data['pwd']) {

            if (!$data['conf_pwd']) {
                throw new AdminException('Vui lòng nhập mật khẩu xác nhận');
            }

            if ($data['conf_pwd'] != $data['pwd']) {
                throw new AdminException('Mật khẩu nhập hai lần không nhất quán');
            }

            if (strlen(trim($data['pwd'])) < 6 || strlen(trim($data['pwd'])) > 32) {
                throw new AdminException('Mật khẩu tài khoản phải từ 6 đến 32 ký tự');
            }

            $adminInfo->pwd = $this->passwordHash($data['pwd']);
        }
        //Sửa đổi tài khoản
        if (isset($data['account']) && $data['account'] != $adminInfo->account && $this->dao->isAccountUsable($data['account'], $id)) {
            throw new AdminException('Tài khoản quản trị viên đã tồn tại');
        }
        if (isset($data['roles'])) {
            $adminInfo->roles = implode(',', $data['roles']);
        }
        $adminInfo->real_name = $data['real_name'] ?? $adminInfo->real_name;
        $adminInfo->account = $data['account'] ?? $adminInfo->account;
        $adminInfo->status = $data['status'];
        if ($adminInfo->save()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Sửa đổi thông tin quản trị viên hiện tại
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateAdmin(int $id, array $data)
    {
        $adminInfo = $this->dao->get($id);
        if (!$adminInfo)
            throw new AdminException('Không tìm thấy thông tin quản trị viên');
        if ($adminInfo->is_del) {
            throw new AdminException('Quản trị viên đã xóa');
        }
        if (!$data['real_name'])
            throw new AdminException('Tên quản trị viên không được để trống');
        if ($data['pwd']) {
            if (!password_verify($data['pwd'], $adminInfo['pwd']))
                throw new AdminException('Sai mật khẩu ban đầu');
            if (!$data['new_pwd'])
                throw new AdminException('Vui lòng nhập mật khẩu mới');
            if (!$data['conf_pwd'])
                throw new AdminException('Vui lòng nhập mật khẩu xác nhận');
            if ($data['new_pwd'] != $data['conf_pwd'])
                throw new AdminException('Mật khẩu nhập hai lần không nhất quán');
            $adminInfo->pwd = $this->passwordHash($data['new_pwd']);
        }

        $adminInfo->real_name = $data['real_name'];
        $adminInfo->head_pic = $data['head_pic'];
        if ($adminInfo->save()) {
            CacheService::clear();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Đặt mật khẩu quản lý tập tin quản trị viên hiện tại
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function setFilePassword(int $id, array $data)
    {
        $adminInfo = $this->dao->get($id);
        if (!$adminInfo)
            throw new AdminException('Không tìm thấy thông tin quản trị viên');
        if ($adminInfo->is_del) {
            throw new AdminException('Quản trị viên đã xóa');
        }
        if ($data['file_pwd']) {
            if ($adminInfo->level != 0) throw new AdminException('sự cho phép bị từ chối');
            if (!$data['conf_file_pwd'])
                throw new AdminException('Vui lòng nhập mật khẩu xác nhận');
            if ($data['file_pwd'] != $data['conf_file_pwd'])
                throw new AdminException('Mật khẩu nhập hai lần không nhất quán');
            $adminInfo->file_pwd = $this->passwordHash($data['file_pwd']);
        }
        if ($adminInfo->save())
            return true;
        else
            return false;
    }


    /** Vị trí đặt hàng phụ trợ, nhận xét, thanh toán thành công và nhắc nhở tin nhắn phụ trợ
     * @param $event
     */
    public function adminNewPush()
    {
        try {
            /** @var StoreOrderServices $orderServices */
            $orderServices = app()->make(StoreOrderServices::class);
            $data['ordernum'] = $orderServices->count(['is_del' => 0, 'status' => 1, 'shipping_type' => 1]);
            /** @var StoreProductServices $productServices */
            $productServices = app()->make(StoreProductServices::class);
            $data['inventory'] = $productServices->count(['type' => 5]);
            /** @var StoreProductReplyServices $replyServices */
            $replyServices = app()->make(StoreProductReplyServices::class);
            $data['commentnum'] = $replyServices->count(['is_reply' => 0]);
            /** @var UserExtractServices $extractServices */
            $extractServices = app()->make(UserExtractServices::class);
            $data['reflectnum'] = $extractServices->getCount(['status' => 0]); //Rút tiền mặt
            $data['msgcount'] = intval($data['ordernum']) + intval($data['inventory']) + intval($data['commentnum']) + intval($data['reflectnum']);
            ChannelService::instance()->send('ADMIN_NEW_PUSH', $data);
        } catch (\Exception $e) {
        }
    }
}
