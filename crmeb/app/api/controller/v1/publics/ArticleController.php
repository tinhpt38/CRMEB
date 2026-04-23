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
namespace app\api\controller\v1\publics;

use app\services\article\ArticleServices;

/**
 * bài viết
 * Class ArticleController
 * @package app\api\controller\publics
 */
class ArticleController
{
    protected $services;

    public function __construct(ArticleServices $services)
    {
        $this->services = $services;
    }

    /**
     * Danh sách bài viết
     * @param $cid
     * @return mixed
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function lst($cid)
    {
        if ($cid == 0) {
            $where = ['is_hot' => 1];
        } else {
            $where = ['cid' => $cid];
        }
        [$page, $limit] = $this->services->getPageValue();
        $list = $this->services->getList($where, $page, $limit)['list'];
        foreach ($list as &$item){
            $item['add_time'] = date('Y-m-d H:i', $item['add_time']);
        }
        return app('json')->success($list);
    }

    /**
     * Chi tiết bài viết
     * @param $id
     * @return mixed
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function details($id)
    {
        $info = $this->services->getInfo($id);
        return app('json')->success($info);
    }

    /**
     * Nhận các bài viết phổ biến
     * @return mixed
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function hot()
    {
        [$page, $limit] = $this->services->getPageValue();
        $list = $this->services->getList(['is_hot' => 1], $page, $limit)['list'];
        foreach ($list as &$item){
            $item['add_time'] = date('Y-m-d H:i', $item['add_time']);
        }
        return app('json')->success($list);
    }

    /**
     * @return mixed
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function new()
    {
        [$page, $limit] = $this->services->getPageValue();
        $list = $this->services->getList([], $page, $limit)['list'];
        foreach ($list as &$item){
            $item['add_time'] = date('Y-m-d H:i', $item['add_time']);
        }
        return app('json')->success($list);
    }

    /**
     * Nhận bài viết biểu ngữ hàng đầu
     * @return mixed
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function banner()
    {
        [$page, $limit] = $this->services->getPageValue();
        $list = $this->services->getList(['is_banner' => 1], $page, $limit)['list'];
        foreach ($list as &$item){
            $item['add_time'] = date('Y-m-d H:i', $item['add_time']);
        }
        return app('json')->success($list);
    }
}
