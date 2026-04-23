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

namespace app\dao\article;


use app\dao\BaseDao;
use app\model\article\ArticleCategory;

/**
 * Phân loại bài viết
 * Class ArticleCategoryDao
 * @package app\dao\article
 */
class ArticleCategoryDao extends BaseDao
{
    /**
     * Thiết lập mô hình
     * @return string
     */
    protected function setModel(): string
    {
        return ArticleCategory::class;
    }

    /**
     * Nhận danh sách các danh mục bài viết
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return mixed
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList(array $where, int $page = 0, int $limit = 0)
    {
        return $this->search($where)->when(!$page && !$limit, function ($query) use ($page, $limit) {
            $query->page($page, $limit);
        })->order('sort desc,id desc')->select()->toArray();
    }

    /**
     * Nhận phân loại bài viết tại quầy lễ tân
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getArticleCategory()
    {
        return $this->search(['hidden' => 0, 'is_del' => 0, 'status' => 1, 'pid' => 0])->with(['children'])
            ->order('sort DESC,id DESC')
            ->field('id,pid,title')
            ->select()->toArray();
    }

    /**
     * Phân loại bài viết phụ
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getArticleTwoCategory()
    {
        return $this->getModel()
            ->where('hidden', 0)
            ->where('is_del', 0)
            ->where('status', 1)
            ->order('sort DESC,id DESC')
            ->field('id,pid,title')
            ->select()->toArray();
    }

    /**
     * Thêm và sửa đổi danh sách danh mục ưu việt đã chọn
     * @param array $where
     * @return array
     * @throws \ReflectionException
     */
    public function getMenus(array $where)
    {
        return $this->search($where)->order('sort desc,id desc')->column('title,pid,id,is_del,status');
    }

    /**
     * danh sách cây
     * @param array $where
     * @param array $field
     * @return array
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/9/7
     */
    public function getTreeList(array $where, array $field)
    {
        return $this->search($where)->field($field)->order('sort desc,id desc')->select()->toArray();
    }
}
