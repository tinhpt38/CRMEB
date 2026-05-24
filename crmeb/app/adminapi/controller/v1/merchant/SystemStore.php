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
namespace app\adminapi\controller\v1\merchant;

use think\facade\App;
use app\adminapi\controller\AuthController;
use app\services\system\store\SystemStoreServices;

/**
 * Bộ điều khiển quản lý cửa hàng
 * Class SystemAttachment
 * @package app\admin\controller\system
 *
 */class SystemStore extends AuthController
{
    /**
     * Người xây dựng
     * SystemStore constructor.
     * @param App $app
     * @param SystemStoreServices $services
     */    public function __construct(App $app, SystemStoreServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Danh sách cửa hàng
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function index()
    {
        $where = $this->request->getMore([
            [['keywords', 's'], ''],
            [['type', 'd'], 0]
        ]);
        return app('json')->success($this->services->getStoreList($where));
    }

    /**
     * Nhận tiêu đề cửa hàng
     * @return mixed
     */    public function get_header()
    {
        $count = $this->services->getStoreData();
        return app('json')->success(compact('count'));
    }

    /**
     * Cài đặt cửa hàng
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function get_info()
    {
        [$id] = $this->request->getMore([
            [['id', 'd'], 0],
        ], true);
        $info = $this->services->getStoreDispose($id);
        return app('json')->success(compact('info'));
    }

    /**
     * Lựa chọn vị trí
     * @return mixed
     */    public function select_address()
    {
        $key = sys_config('tengxun_map_key');
        if (!$key) return app('json')->fail('Vui lòng định cấu hình bản đồ TencentKEY');
        return app('json')->success(compact('key'));
    }

    /**
     * Đặt xem một cửa hàng có được hiển thị hay không
     * @param string $is_show
     * @param string $id
     * @return mixed
     */    public function set_show($is_show = '', $id = '')
    {
        ($is_show == '' || $id == '') && app('json')->fail('Lỗi tham số');
        $res = $this->services->update((int)$id, ['is_show' => (int)$is_show]);
        if ($res) {
            return app('json')->success('Thiết lập thành công');
        } else {
            return app('json')->fail('Thiết lập không thành công');
        }
    }

    /**
     * Lưu và sửa đổi thông tin cửa hàng
     * @param int $id
     * @return mixed
     */    public function save($id = 0)
    {
        $data = $this->request->postMore([
            ['name', ''],
            ['introduction', ''],
            ['image', ''],
            ['oblong_image', ''],
            ['phone', ''],
            ['address', ''],
            ['detailed_address', ''],
            ['latlng', ''],
            ['day_time', []],
        ]);
        $this->validate($data, \app\adminapi\validate\merchant\SystemStoreValidate::class, 'save');

        $data['address'] = implode(',', $data['address']);
        $data['latlng'] = explode(',', $data['latlng']);
        if (!isset($data['latlng'][0]) || !isset($data['latlng'][1])) {
            return app('json')->fail('Vui lòng chọn vị trí cửa hàng');
        }
        $data['latitude'] = $data['latlng'][0];
        $data['longitude'] = $data['latlng'][1];
        $data['day_time'] = implode(' - ', $data['day_time']);
        unset($data['latlng']);
        if ($data['image'] && strstr($data['image'], 'http') === false) {
            $site_url = sys_config('site_url');
            $data['image'] = $site_url . $data['image'];
        }
        $this->services->saveStore((int)$id, $data);
        return app('json')->success('Thiết lập thành công');
    }

    /**
     * Xóa cửa hàng khôi phục
     * @param $id
     * @return mixed
     */    public function delete($id)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');
        $storeInfo = $this->services->get($id);
        if (!$storeInfo) {
            return app('json')->fail('Dữ liệu không tồn tại');
        }
        if ($storeInfo->is_del == 1) {
            $storeInfo->is_del = 0;
            if (!$storeInfo->save())
                return app('json')->fail('Khôi phục không thành công');
            else
                return app('json')->success('Khôi phục thành công');
        } else {
            $storeInfo->is_del = 1;
            if (!$storeInfo->save())
                return app('json')->fail('Xóa không thành công');
            else
                return app('json')->success('Xóa thành công');
        }
    }
}
