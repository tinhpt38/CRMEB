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

namespace app\dao\product\product;

use app\dao\BaseDao;
use app\model\product\product\StoreProductReply;

/**
 * Class StoreProductReplyDao
 * @package app\dao\product\product
 */
class StoreProductReplyDao extends BaseDao
{
    /**
     * Thiết lập mô hình
     * @return string
     */
    protected function setModel(): string
    {
        return StoreProductReply::class;
    }

    /**
     * Danh sách bình luận phụ trợ
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function sysPage(array $where, int $page, int $limit)
    {
        return $this->search($where)->page($page, $limit)->select()->toArray();
    }

    /**
     * Nhận bình luận gần đây tốt nhất
     * @param int $productId
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getProductReply(int $productId)
    {
        return $this->search(['product_id' => $productId, 'is_del' => 0, 'status' => 1])
            ->with(['cartInfo', 'userInfo'])
            ->order('add_time DESC,product_score DESC,service_score DESC,add_time DESC')
            ->find();
    }

    /**
     * Điều kiện bình luận
     * @param int $id
     * @param int $type
     * @return \crmeb\basic\BaseModel|mixed|\think\Model
     */
    public function replyWhere(int $id, int $type = 0)
    {
        return $this->search(['product_id' => $id, 'is_del' => 0, 'status' => 1])
            ->when($type == 1, function ($query) {
                $query->where('product_score', 5)->where('service_score', 5);
            })->when($type == 2, function ($query) {
                $query->where(function ($query0) {
                    $query0->where(function ($query1) {
                        $query1->where('product_score', '<>', 5)->whereOr('service_score', '<>', 5);
                    })->where(function ($query2) {
                        $query2->where('service_score', '>', 2)->where('product_score', '>', 2);
                    });
                });
            })->when($type == 3, function ($query) {
                $query->where(function ($query0) {
                    $query0->where('product_score', '<=', 2)->whereOr('service_score', '<=', 2);
                });
            });
    }

    /**
     * Số lượng bình luận
     * @param int $id
     * @param int $type
     * @return int
     */
    public function replyCount(int $id, int $type = 0)
    {
        return $this->replyWhere($id, $type)->count();
    }

    /**
     * Nội dung bình luận
     * @param int $id
     * @param int $type
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function replyList(int $id, int $type = 0, int $page = 0, int $limit = 0)
    {
        return $this->replyWhere($id, $type)->when($page && $limit, function ($query) use ($page, $limit) {
            $query->page($page, $limit);
        })->with(['cartInfo', 'userInfo'])->order('add_time desc')->select()->toArray();
    }
}
