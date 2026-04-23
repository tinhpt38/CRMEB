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
namespace app\services\out;

use app\dao\out\OutInterfaceDao;
use app\Request;
use app\services\BaseServices;
use app\services\system\SystemRouteCateServices;
use app\services\system\SystemRouteServices;
use crmeb\exceptions\AdminException;
use crmeb\exceptions\AuthException;

class OutInterfaceServices extends BaseServices
{
    public function __construct(OutInterfaceDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Xác minh quyền giao diện bên ngoài
     * @param Request $request
     * @return bool
     */
    public function verifyAuth(Request $request)
    {
        $rule = trim(strtolower($request->rule()->getRule()));
        $method = trim(strtolower($request->method()));
        $authList = app()->make(SystemRouteServices::class)->getColumn([['id', 'in', $request->outInfo()['rules']], ['app_name', '=', 'outapi']], 'method,path');
        $rolesAuth = [];
        foreach ($authList as $item) {
            $rolesAuth[trim(strtolower($item['method']))][] = trim(strtolower(str_replace(' ', '', $item['path'])));
        }
        if (in_array($rule, $rolesAuth[$method])) {
            return true;
        }
        $rule = str_replace('<', '{', $rule);
        $rule = str_replace('>', '}', $rule);
        if (in_array($rule, $rolesAuth[$method])) {
            return true;
        } else {
            throw new AuthException('Hiện tại bạn không có quyền truy cập');
        }
    }

    /**
     * Danh sách giao diện bên ngoài
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function outInterfaceList(): array
    {
        // Lấy danh sách phân loại định tuyến hệ thống
        $list = app()->make(SystemRouteCateServices::class)->selectList(['app_name' => 'outapi'], 'id,pid,name,name as title')->toArray();
        // Nhận danh sách định tuyến hệ thống
        $data = app()->make(SystemRouteServices::class)->selectList(['app_name' => 'outapi'], 'id,cate_id as pid,name,name as title')->toArray();
        // Duyệt qua danh sách phân loại và thêm các tuyến theo phân loại vào các nút con tương ứng
        foreach ($list as &$item) {
            foreach ($data as $k => $v) {
                if ($item['id'] == $v['pid']) {
                    $item['children'][] = $v;
                }
            }
        }
        // Trả về danh sách đầy đủ các giao diện bên ngoài
        return $list;
    }


    /**
     * Đã thêm tài liệu giao diện bên ngoài
     * @param $id
     * @param $data
     * @return bool
     */
    public function saveInterface($id, $data)
    {
        $data['request_params'] = json_encode($data['request_params']);
        $data['return_params'] = json_encode($data['return_params']);
        $data['error_code'] = json_encode($data['error_code']);
        if ($id) {
            $res = $this->dao->update($id, $data);
        } else {
            $res = $this->dao->save($data);
        }
        if (!$res) throw new AdminException('Lưu không thành công');
        return true;
    }

    /**
     * Tài liệu giao diện bên ngoài
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function interfaceInfo($id)
    {
        if (!$id) throw new AdminException('Lỗi tham số');
        $info = $this->dao->get($id);
        if (!$info) throw new AdminException('Dữ liệu không tồn tại');
        $info = $info->toArray();
        $info['request_params'] = json_decode($info['request_params']);
        $info['return_params'] = json_decode($info['return_params']);
        $info['error_code'] = json_decode($info['error_code']);
        return $info;
    }

    /**
     * Sửa đổi tên giao diện
     * @param $data
     * @return bool
     */
    public function editInterfaceName($data)
    {
        $res = $this->dao->update($data['id'], ['name' => $data['name']]);
        if (!$res) throw new AdminException('Sửa đổi không thành công');
        return true;
    }

    /**
     * Xóa giao diện
     * @param $id
     * @return bool
     */
    public function delInterface($id)
    {
        $res = $this->dao->update($id, ['is_del' => 1]);
        if (!$res) throw new AdminException('Xóa không thành công');
        return true;
    }
}