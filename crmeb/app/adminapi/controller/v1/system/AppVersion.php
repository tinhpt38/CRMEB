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

namespace app\adminapi\controller\v1\system;


use app\adminapi\controller\AuthController;
use app\services\system\AppVersionServices;
use think\facade\App;

/**
 *
 * Class AppVersion
 * @package app\adminapi\controller\v1\system
 */
class AppVersion extends AuthController
{
    /**
     * user constructor.
     * @param App $app
     * @param AppVersionServices $services
     */
    public function __construct(App $app, AppVersionServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Danh sách phiên bản
     * @return \think\Response
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/4/2
     */
    public function list()
    {
        [$platform] = $this->request->getMore([
            ['platform', '']
        ], true);
        return app('json')->success($this->services->versionList($platform));
    }

    /**
     * Thêm mẫu phiên bản
     * @param $id
     * @return \think\Response
     * @throws \FormBuilder\Exception\FormBuilderException
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/4/2
     */
    public function crate($id)
    {
        return app('json')->success($this->services->createForm($id));
    }

    /**
     * lưu dữ liệu
     * @return \think\Response
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/4/2
     */
    public function save()
    {
        $data = $this->request->postMore([
            ['id', 0],
            ['version', ''],
            ['platform', 1],
            ['info', ''],
            ['is_force', 1],
            ['url', ''],
            ['is_new', 1],
        ]);
        $id = $data['id'];
        unset($data['id']);
        $this->services->versionSave($id, $data);
        return app('json')->success('Đã thêm thành công');
    }

    /**
     * Xóa phiên bản ứng dụng
     * @param $id
     * @return \think\Response
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/4/2
     */
    public function del($id)
    {
        $this->services->delete($id);
        return app('json')->success('Xóa thành công');
    }
}
