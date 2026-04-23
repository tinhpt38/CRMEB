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

use app\dao\article\ArticleDao;
use app\services\BaseServices;
use app\services\wechat\WechatNewsCategoryServices;
use crmeb\exceptions\AdminException;

/**
 * Class ArticleServices
 * @package app\services\article
 */
class ArticleServices extends BaseServices
{
    /**
     * ArticleServices constructor.
     * @param ArticleDao $dao
     */
    public function __construct(ArticleDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Nhận danh sách
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList(array $where, int $page = 0, int $limit = 0)
    {
        if (!$page && !$limit) {
            [$page, $limit] = $this->getPageValue();
        }
        $where['ids'] = app()->make(WechatNewsCategoryServices::class)->getNewIds();
        $list = $this->dao->getList($where, $page, $limit);
        foreach ($list as &$item) {
            $item['store_name'] = $item['storeInfo']['store_name'] ?? '';
            $item['copy_url'] = sys_config('site_url') . '/pages/extension/news_details/index?id=' . $item['id'];
            $item['copy_url_pc'] = sys_config('site_url') . '/news_detail?id=' . $item['id'];
            unset($item['content']);
        }
        $count = $this->dao->count($where);
        return compact('list', 'count');
    }

    /**
     * Thêm bài viết biên tập mới
     * @param array $data
     * @return mixed
     */
    public function save(array $data)
    {
        /** @var ArticleContentServices $articleContentService */
        $articleContentService = app()->make(ArticleContentServices::class);
        $content['content'] = htmlspecialchars($data['content']);
        $id = $data['id'];
        unset($data['content'], $data['id']);
        $info = $this->transaction(function () use ($id, $data, $articleContentService, $content) {
            if ($id) {
                $info = $this->dao->update($id, $data);
                $content['nid'] = $id;
                $res = $info && $articleContentService->update($id, $content, 'nid');
            } else {
                unset($data['id']);
                $data['add_time'] = time();
                $info = $this->dao->save($data);
                $content['nid'] = $info->id;
                $res = $info && $articleContentService->save($content);
            }
            if (!$res) {
                throw new AdminException('Lưu không thành công');
            } else {
                return $info;
            }
        });
        return $info;
    }

    /**
     * Nhận chi tiết bài viết
     * @param int $id
     * @return array
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function read(int $id)
    {
        $info = $this->dao->read($id);
        $info['cid'] = (int)$info['cid'];
        $info['content'] = htmlspecialchars_decode($info['content']);
        return compact('info');
    }

    /**
     * Xóa bài viết
     * @param int $id
     */
    public function del(int $id)
    {
        /** @var ArticleContentServices $articleContentService */
        $articleContentService = app()->make(ArticleContentServices::class);
        $this->transaction(function () use ($id, $articleContentService) {
            $res = $this->dao->delete($id);
            $res = $res && $articleContentService->del($id);
            if (!$res) {
                throw new AdminException('Xóa không thành công');
            }
        });
    }

    /**
     * Sản phẩm liên quan đến bài viết
     * @param int $id
     * @param int $product_id
     * @return mixed
     */
    public function bindProduct(int $id, int $product_id = 0)
    {
        return $this->dao->update($id, ['product_id' => $product_id]);
    }

    /**
     * Nhận số lượng
     * @param array $where
     * @param bool $search
     * @return int
     */
    public function count(array $where = [], bool $search = true): int
    {
        return $this->search($where, $search)->count();
    }

    /**
     * Lấy một phần dữ liệu
     * @param int $id
     * @return mixed
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getInfo(int $id)
    {
        $info = $this->dao->read($id);
        $info->visit = intval($info['visit']) + 1;
        if (!$info->save())
            throw new AdminException('Vui lòng kiểm tra lại sau');
        if ($info) {
            $info = $info->toArray();
            $info['visit'] = (int)$info['visit'];
            $info['add_time'] = date('Y-m-d', $info['add_time']);
            $info['content'] = htmlspecialchars_decode($info['content']);
        }
        return $info;
    }

    /**
     * Nhận danh sách bài viết
     * @param $new_id
     * @return int
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function articleList($new_id)
    {
        return $this->dao->articleLists($new_id);
    }

    /**
     * Chi tiết hình ảnh và văn bản
     * @param $new_id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function articlesList($new_id)
    {
        return $this->dao->articleContentList($new_id);
    }

    /**
     * Thành phần tùy chỉnh-Bài viết
     * @param $where
     * @return array
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2026/1/12
     */
    public function getThemeArticle($where)
    {
        $sort = $where['sort'] ? 'desc' : 'asc';
        switch ($where['order']) {
            case 0:
                $order = 'visit ' . $sort;
                break;
            case 1:
                $order = 'add_time ' . $sort;
                break;
            default:
                $order = 'sort desc';
                break;
        }
        if ($where['ids'] != '') {
            $where['in_ids'] = explode(',', $where['ids']);
            $where['limit'] = 1000;
        } else {
            $where['in_ids'] = [];
        }
        $limit = (int)$where['limit'];
        unset($where['order'], $where['sort'], $where['limit'], $where['ids']);
        $list = $this->dao->getList($where, 1, $limit, $order);
        $data = [];
        foreach ($list as &$item) {
            $data[] = [
                'title' => $item['title'],
                'id' => $item['id'],
                'image' => $item['image_input'][0],
                'cid_name' => $item['catename'],
                'synopsis' => $item['synopsis'],
                'visit' => $item['visit'],
                'add_time' => date('Y-m-d H:i:s', $item['add_time']),
            ];
        }
        if (empty($where['in_ids'])) return $data;
        // Sẽ$listChuyển đổi thành mảng với id làm khóa
        $list = array_column($data, null, 'id');
        $data = [];
        // Duyệt qua các id ở đâu và truy xuất các bài viết tương ứng theo thứ tự
        foreach ($where['in_ids'] as $id) {
            if (isset($list[$id])) {
                $data[] = $list[$id];
            }
        }
        return $data;
    }
}
