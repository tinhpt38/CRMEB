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

namespace app\services\article;

use app\dao\article\ArticleCategoryDao;
use app\services\BaseServices;
use crmeb\exceptions\AdminException;
use crmeb\services\FormBuilder as Form;
use crmeb\utils\Arr;
use think\facade\Route as Url;

/**
 * Class ArticleCategoryServices
 * @package app\services\article
 * @method getArticleCategory()
 * @method getArticleTwoCategory()
 */class ArticleCategoryServices extends BaseServices
{
    /**
     * ArticleCategoryServices constructor.
     * @param ArticleCategoryDao $dao
     */    public function __construct(ArticleCategoryDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Nhận danh sách các danh mục bài viết
     * @param array $where
     * @return array
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getList(array $where)
    {
        $list = $this->dao->getList($where);
        $list = get_tree_children($list);
        return compact('list');
    }

    /**
     * Tạo biểu mẫu sửa đổi
     * @param int $id
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     */    public function createForm(int $id)
    {
        $method = 'POST';
        $url = '/cms/category';
        if ($id) {
            $info = $this->dao->get($id);
            $method = 'PUT';
            $url = $url . '/' . $id;
            $pid = $info['pid'];
        } else {
            $pid = '';
        }
        $f = array();
        $f[] = Form::hidden('id', $info['id'] ?? 0);
        $f[] = Form::select('pid', 'Phân loại cao cấp', (int)($info['pid'] ?? ''))->setOptions($this->menus($pid))->filterable(1);
        $f[] = Form::input('title', 'Tên danh mục', $info['title'] ?? '')->maxlength(20)->required();
        $f[] = Form::input('intr', 'Giới thiệu phân loại', $info['intr'] ?? '')->type('textarea')->required();
        $f[] = Form::frameImage('image', 'Hình ảnh rao vặt', Url::buildUrl(config('app.admin_prefix', 'admin') . '/widget.images/index', array('fodder' => 'image')), $info['image'] ?? '')->icon('el-icon-picture-outline')->width('950px')->height('560px')->props(['footer' => false]);
        $f[] = Form::number('sort', 'loại', (int)($info['sort'] ?? 0))->precision(0);
        $f[] = Form::radio('status', 'Trạng thái', $info['status'] ?? 1)->options([['value' => 1, 'label' => 'trình diễn'], ['value' => 0, 'label' => 'trốn']]);
        return create_form($id ? 'Chỉnh sửa danh mục' :'Thêm danh mục', $f, Url::buildUrl($url), $method);
    }

    /**
     * Lưu
     * @param array $data
     * @return mixed
     */    public function save(array $data)
    {
        return $this->dao->save($data);
    }

    /**
     * Sửa
     * @param array $data
     * @return mixed
     */    public function update(array $data)
    {
        return $this->dao->update($data['id'], $data);
    }

    /**
     * Xóa
     * @param int $id
     * @return mixed
     */    public function del(int $id)
    {
        /** @var ArticleServices $articleService */        $articleService = app()->make(ArticleServices::class);
        $pidCount = $this->dao->count(['pid' => $id]);
        if ($pidCount > 0) throw new AdminException('Danh mục này có các danh mục phụ và không thể xóa được.');
        $count = $articleService->count(['cid' => $id]);
        if ($count > 0) {
            throw new AdminException('Có những bài viết thuộc thể loại này và không thể xóa được');
        } else {
            return $this->dao->delete($id);
        }
    }

    /**
     * Sửa đổi trạng thái
     * @param int $id
     * @param int $status
     * @return mixed
     */    public function setStatus(int $id, int $status)
    {
        return $this->dao->update($id, ['status' => $status]);
    }

    /**
     * Nhận dữ liệu kết hợp phân loại cấp đầu tiên
     * @param string $pid
     * @return array[]
     */    public function menus($pid = '')
    {
        $list = $this->dao->getMenus(['pid' => 0]);
        $menus = [['value' => 0, 'label' => 'danh mục hàng đầu']];
        if ($pid === 0) return $menus;
        if ($pid != '') $menus = [];
        foreach ($list as $menu) {
            $menus[] = ['value' => $menu['id'], 'label' => $menu['title']];
        }
        return $menus;
    }

    /**
     * danh sách cây
     * @return array
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/9/7
     */    public function getTreeList()
    {
        return get_tree_children($this->dao->getTreeList(['is_del' => 0, 'status' => 1, 'hidden' => 0], ['id', 'id as value', 'title as label', 'title', 'pid']), 'children', 'id');
//        return sort_list_tier($this->dao->getMenus([]));
    }
}
