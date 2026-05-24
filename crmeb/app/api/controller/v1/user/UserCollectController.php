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
namespace app\api\controller\v1\user;

use app\Request;
use app\services\product\product\StoreProductRelationServices;


/**
 * Người dùng yêu thích
 * Class UserCollectController
 * @package app\api\controller\v1\user
 */class UserCollectController
{
    protected $services = NUll;

    /**
     * UserCollectController constructor.
     * @param StoreProductRelationServices $services
     */    public function __construct(StoreProductRelationServices $services)
    {
        $this->services = $services;
    }


    /**
     * Nhận mục yêu thích
     * @param Request $request
     * @return mixed
     */    public function collect_user(Request $request)
    {
        $uid = (int)$request->uid();
        return app('json')->success($this->services->getUserCollectProduct($uid));
    }

    /**
     * Thêm mới mục yêu thích
     * @param Request $request
     * @return mixed
     */    public function collect_add(Request $request)
    {
        [$id, $category] = $request->postMore([
            ['id', 0],
            ['category', 'product']
        ], true);
        if (!$id || !is_numeric($id)) return app('json')->fail('Lỗi tham số');
        $res = $this->services->productRelation((int)$id, $request->uid(), 'collect', $category);
        if (!$res) {
            return app('json')->fail('Bộ sưu tập không thành công');
        } else {
            return app('json')->success('Bộ sưu tập thành công');
        }
    }

    /**
     * Hủy yêu thích
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */    public function collect_del(Request $request)
    {
        [$id, $category] = $request->postMore([
            ['id', []],
            ['category', 'product']
        ], true);
        $uid = (int)$request->uid();
        $res = $this->services->unProductRelation($id, $uid, 'collect', $category);
        if (!$res) return app('json')->fail('Hủy không thành công');
        else return app('json')->success('Hủy thành công');
    }

    /**
     * Bộ sưu tập hàng loạt
     * @param Request $request
     * @return mixed
     */    public function collect_all(Request $request)
    {
        $collectInfo = $request->postMore([
            ['id', ''],
            ['category', 'product'],
        ]);
        $collectInfo['id'] = explode(',', $collectInfo['id']);
        if (!count($collectInfo['id'])) {
            return app('json')->fail('Lỗi tham số');
        }
        $uid = (int)$request->uid();
        $productIdS = $collectInfo['id'];
        $res = $this->services->productRelationAll($productIdS, $uid, 'collect', $collectInfo['category']);
        if (!$res) {
            return app('json')->fail('Bộ sưu tập không thành công');
        } else {
            return app('json')->success('Bộ sưu tập thành công');
        }
    }
}
