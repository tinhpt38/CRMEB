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

namespace app\services\diy;

use app\services\BaseServices;
use app\dao\diy\PageCategoryDao;
use crmeb\exceptions\AdminException;
use crmeb\services\CacheService;
use crmeb\services\FormBuilder as Form;
use think\facade\Route as Url;


/**
 * Class PageCategoryServices
 * @package app\services\diy
 */
class PageCategoryServices extends BaseServices
{

    protected $tree_page_category_key = 'tree_page_categroy';

    /**
     * PageCategoryServices constructor.
     * @param PageCategoryDao $dao
     */
    public function __construct(PageCategoryDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Nhận danh sách danh mục
     * @return bool|mixed|null
     */
    public function getCategroyList()
    {
//        return CacheService::remember($this->tree_page_category_key, function () {
        return $this->getSonCategoryList();
//        }, 86400);
    }

    /**
     * treeDanh sách danh mục
     * @param int $pid
     * @param string $parent_name
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getSonCategoryList($pid = 0)
    {
        $list = $this->dao->getList(['pid' => $pid], 'id,pid,type,name');
        $arr = [];
        if ($list) {
            foreach ($list as $item) {
                $item['title'] = $item['name'];
                $item['expand'] = true;
                $item['children'] = $this->getSonCategoryList($item['id']);
                $arr[] = $item;
            }
        }
        return $arr;
    }

    public function getLinkCategoryForm($cate_id = 0, $pid = 1)
    {
        $info = $this->dao->get($cate_id);
        $list = $this->dao->getList(['pid' => 1]);
        $data = [['value' => 1, 'label' => 'danh mục hàng đầu']];
        foreach ($list as $menu) {
            $data[] = ['value' => $menu['id'], 'label' => $menu['name']];
        }
        $pid = isset($info['pid']) ? $info['pid'] : $pid;
        $f[] = Form::hidden('id', $cate_id);
        $f[] = Form::select('pid', 'Phân loại cao cấp', (int)$pid)->setOptions($data)->filterable(true);
        $f[] = Form::input('name', 'Tên danh mục', $info['name'] ?? '')->required();
        $f[] = Form::input('type', 'Kiểu phân loại', $info['type'] ?? '')->required();
        $f[] = Form::number('sort', 'loại', (int)($info['sort'] ?? 0))->min(0)->precision(0);
        $f[] = Form::radio('status', 'Trạng thái', $info['status'] ?? 1)->options([['label' => 'trình diễn', 'value' => 1], ['label' => 'trốn', 'value' => 0]]);
        return create_form($cate_id ? 'Sửa danh mục' : 'Thêm danh mục', $f, Url::buildUrl('/diy/link/category/save/' . $cate_id), 'POST');
    }

    public function getLinkCategorySave($cate_id, $data)
    {
        if ($cate_id) {
            $res = $this->dao->update($cate_id, $data);
        } else {
            $data['add_time'] = time();
            $res = $this->dao->save($data);
        }
        if (!$res) {
            throw new AdminException('Lưu không thành công');
        } else {
            return true;
        }
    }

    public function getLinkCategoryDel($cate_id)
    {
        $res = $this->dao->delete($cate_id);
        if (!$res) {
            throw new AdminException('Xóa không thành công');
        } else {
            return true;
        }
    }
}
