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
use app\model\article\Article;
use think\exception\ValidateException;

/**
 * bài báodao
 * Class ArticleDao
 * @package app\dao\article
 */class ArticleDao extends BaseDao
{
    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return Article::class;
    }

    /**
     * Tìm kiếm bài viết
     * @param array $where
     * @param bool $search
     * @return \crmeb\basic\BaseModel
     * @throws \ReflectionException
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2023/03/20
     */    public function search(array $where = [], bool $search = false)
    {
        return parent::search($where, $search)
            ->when(isset($where['ids']) && count($where['ids']), function ($query) use ($where) {
                $query->whereNotIn('id', $where['ids']);
            })->when(isset($where['in_ids']) && count($where['in_ids']), function ($query) use ($where) {
                $query->whereIn('id', $where['in_ids']);
            });
    }

    /**
     * Nhận danh sách bài viết
     * @param array $where
     * @param int $page
     * @param int $limit
     * @param string $order
     * @return mixed
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getList(array $where, int $page, int $limit, string $order = 'sort desc,id desc')
    {
        return $this->search($where)->with(['content', 'storeInfo', 'cateName'])->page($page, $limit)->order($order)->select()->toArray();
    }

    /**
     * Lấy một phần dữ liệu
     * @param $id
     * @return mixed
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function read($id)
    {
        $data = $this->search()->with(['content', 'storeInfo', 'cateName'])->find($id);
        if (!$data) throw new ValidateException('Bài viết không tồn tại');
        $data['store_info'] = $data['storeInfo'];
        return $data;
    }

    /**
     * Các bài viết thuộc chuyên mục tin tức
     * @param $new_id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function articleLists($new_id)
    {
        return $this->getModel()->where('hide', 0)->where('id', 'in', $new_id)->select();
    }

    /**
     * Chi tiết hình ảnh và văn bản
     * @param $new_id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function articleContentList($new_id)
    {
        return $this->getModel()->where('hide', 0)->where('id', 'in', $new_id)->with('content')->select();
    }
}
