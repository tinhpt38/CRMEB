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
namespace app\adminapi\controller\v1\setting;

use app\adminapi\controller\AuthController;
use app\services\message\SystemNotificationServices;
use crmeb\services\CacheService;
use think\facade\App;

/**
 * Class SystemRole
 * @package app\adminapi\controller\v1\setting
 */
class SystemNotification extends AuthController
{
    /**
     * SystemRole constructor.
     * @param App $app
     * @param SystemNotificationServices $services
     */
    public function __construct(App $app, SystemNotificationServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Hiển thị danh sách tài nguyên
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index()
    {
        $where = $this->request->getMore([
            ['type', ''],
        ]);
        return app('json')->success($this->services->getNotList($where));
    }

    /**
     * Thêm tin nhắn
     * @return \think\Response
     * @throws \FormBuilder\Exception\FormBuilderException
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/2/19
     */
    public function notForm($id)
    {
        return app('json')->success($this->services->getNotForm($id));
    }

    /**
     * Lưu tin nhắn tùy chỉnh
     * @param $id
     * @return \think\Response
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/2/20
     */
    public function notFormSave($id)
    {
        $data = $this->request->postMore([
            ['custom_trigger', ''],
            ['name', ''],
            ['mark', ''],
        ]);
        $this->services->notFormSave($id, $data);
        return app('json')->success('Đã lưu thành công');
    }

    /**
     * Xóa tin nhắn
     * @param $id
     * @return \think\Response
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/2/20
     */
    public function delNot($id)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');
        $this->services->delete($id);
        return app('json')->success('Xóa thành công');
    }


    /**
     * hiển thị chỉnh sửa
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function info()
    {
        $where = $this->request->getMore([
            ['type', ''],
            ['id', 0]
        ]);
        if (!$where['id']) return app('json')->fail('Lỗi tham số');
        return app('json')->success($this->services->getNotInfo($where));
    }

    /**
     * Lưu tài nguyên mới
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function save()
    {
        $data = $this->request->postMore([
            ['id', 0],
            ['type', ''],
            ['name', ''],
            ['title', ''],
            ['is_system', 0],
            ['is_app', 0],
            ['is_wechat', 0],
            ['is_routine', 0],
            ['is_sms', 0],
            ['is_ent_wechat', 0],
            ['system_title', ''],
            ['system_text', ''],
            ['tempid', ''],
            ['tempkey', ''],
            ['content', ''],
            ['ent_wechat_text', ''],
            ['url', ''],
            ['wechat_id', ''],
            ['routine_id', ''],
            ['mark', ''],
            ['sms_id', ''],
            ['key_list', ''],
            ['sms_text', ''],
            ['wechat_link', ''],
            ['routine_link', ''],
            ['wechat_to_routine', ''],
        ]);
        if ($data['mark'] == 'verify_code') $data['type'] = 'is_sms';
        if (!$data['id']) return app('json')->fail('Lỗi tham số');
        if ($this->services->saveData($data)) {
            CacheService::clear();
            return app('json')->success('Sửa đổi thành công');
        } else {
            return app('json')->fail('Sửa đổi không thành công');
        }
    }

    /**
     * Sửa đổi trạng thái tin nhắn
     * @param $type
     * @param $status
     * @param $id
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function set_status($type, $status, $id)
    {
        if ($type == '' || $status == '' || $id == 0) return app('json')->fail('Lỗi tham số');
        $this->services->update($id, [$type => $status]);
        $res = $this->services->getOneNotce(['id' => $id]);
        CacheService::clear();
        return app('json')->success('Thiết lập thành công');
    }
}
