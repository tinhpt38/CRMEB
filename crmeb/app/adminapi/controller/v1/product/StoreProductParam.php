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
namespace app\adminapi\controller\v1\product;

use app\adminapi\controller\AuthController;
use app\services\product\product\StoreProductParamServices;
use think\facade\App;

/**
 * Thông số sản phẩm
 * @author wuhaotian
 * @email 442384644@qq.com
 * @date 2024/12/17
 */
class StoreProductParam extends AuthController
{
    /**
     * @param App $app
     * @param StoreProductParamServices $services
     */
    public function __construct(App $app, StoreProductParamServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * Lấy danh sách tham số
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/12/17
     */
    public function getParamList()
    {
        $where = $this->request->getMore([
            ['name', '']
        ]);
        return app('json')->success($this->services->getParamList($where));
    }

    /**
     * Nhận chi tiết tham số
     * @param $id
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/12/17
     */
    public function getParamInfo($id)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');
        $info = $this->services->getParamInfo($id);
        return app('json')->success($info);
    }

    /**
     * Nhận giá trị tham số
     * @param $id
     * @return \think\Response
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/12/17
     */
    public function getParamValue($id)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');
        $info = $this->services->getParamValue($id);
        return app('json')->success($info);
    }

    /**
     * Lưu thông số
     * @param $id
     * @return \think\Response
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/12/17
     */
    public function saveParamData($id)
    {
        $data = $this->request->postMore([
            ['name', ''],
            ['value', []],
            ['sort', 0],
            ['status', 1]
        ]);
        if (!$data['name']) return app('json')->fail('Vui lòng nhập tên thông số');
        if (!count($data['value'])) return app('json')->fail('Vui lòng nhập giá trị tham số');
        $this->services->saveParamData($id, $data);
        return app('json')->success('Đã lưu thành công');
    }

    /**
     * Sửa đổi trạng thái tham số
     * @param $id
     * @param $status
     * @return \think\Response
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/12/17
     */
    public function setParamStatus($id, $status)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');
        $this->services->setParamStatus($id, $status);
        return app('json')->success('Sửa đổi thành công');
    }

    /**
     * Xóa tham số
     * @param $id
     * @return \think\Response
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2024/12/17
     */
    public function delParamData($id)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');
        $this->services->delParamData($id);
        return app('json')->success('Xóa thành công');
    }
}
