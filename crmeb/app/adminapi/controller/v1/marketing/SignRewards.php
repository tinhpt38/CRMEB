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
/**
 * @author: thủy triều
 * @email: 442384644@qq.com
 * @date: 2023/7/31
 */

namespace app\adminapi\controller\v1\marketing;

use app\adminapi\controller\AuthController;
use app\services\system\SystemSignRewardServices;
use think\facade\App;

class SignRewards extends AuthController
{
    /**
     * @param App $app
     * @param SystemSignRewardServices $services
     */
    public function __construct(App $app, SystemSignRewardServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Danh sách phần thưởng đăng nhập
     * @return \think\Response
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/7/31
     */
    public function index()
    {
        [$type] = $this->request->getMore([
            ['type', 0]
        ], true);
        $data = $this->services->getList($type);
        return app('json')->success($data);
    }

    /**
     * Đã thêm phần thưởng đăng nhập
     * @return \think\Response
     * @throws \FormBuilder\Exception\FormBuilderException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/7/31
     */
    public function addRewards()
    {
        [$type] = $this->request->getMore([
            ['type', 0]
        ], true);
        $data = $this->services->rewardsForm(0, $type);
        return app('json')->success($data);
    }

    /**
     * Sửa đổi phần thưởng đăng nhập
     * @param $id
     * @return \think\Response
     * @throws \FormBuilder\Exception\FormBuilderException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/7/31
     */
    public function editRewards($id)
    {
        $data = $this->services->rewardsForm($id);
        return app('json')->success($data);
    }

    /**
     * Lưu phần thưởng đăng nhập
     * @param $id
     * @return \think\Response
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/7/31
     */
    public function saveRewards($id)
    {
        $data = $this->request->postMore([
            ['type', 0],
            ['days', 0],
            ['point', 0],
            ['exp', 0]
        ]);
        $this->services->saveRewards($id, $data);
        return app('json')->success('Đã lưu thành công');
    }

    /**
     * Xóa phần thưởng đăng nhập
     * @param $id
     * @return \think\Response
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/7/31
     */
    public function delRewards($id)
    {
        $this->services->delete($id);
        return app('json')->success('Xóa thành công');
    }
}