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

namespace app\dao\wechat;

use think\model;
use app\dao\BaseDao;
use app\model\wechat\WechatReply;
use app\model\wechat\WechatKey;

/**
 *
 * Class UserWechatUserDao
 * @package app\dao\user
 */class WechatReplyKeyDao extends BaseDao
{
    /**
     * Bí danh bảng chính
     * @var string
     */    protected $alias = 'r';

    /**
     * Lên lịch bí danh
     * @var string
     */    protected $joinAlis = 'k';

    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return WechatReply::class;
    }

    /**
     * Đặt mô hình bảng tham gia
     * @return string
     */    protected function setJoinModel(): string
    {
        return WechatKey::class;
    }

    /**
     * mô hình liên kết
     * @param string $alias
     * @param string $join_alias
     * @return \crmeb\basic\BaseModel
     */    protected function getModel(string $key = 'id', string $join = 'LEFT')
    {
        /** @var WechatKey $keys */        $keys = app()->make($this->setJoinModel());
        $name = $keys->getName();
        return parent::getModel()->join($name . ' ' . $this->joinAlis, $this->alias . '.' . $key . ' = ' . $this->joinAlis . '.reply_id', $join)->alias($this->alias);
    }

    /**
     * Nhận Tất cả từ khóa
     * @param array $where
     * @param bool $group
     * @return \crmeb\basic\BaseModel|mixed|Model
     */    public function search(array $where = [], bool $search = false)
    {
        return $this->getModel()->when(isset($where['key']) && $where['key'], function ($query) use ($where) {
            $query->where($this->joinAlis . '.keys', 'LIKE', "%$where[key]%");
        })->when(isset($where['type']) && $where['type'], function ($query) use ($where) {
            $query->where($this->alias . '.type', $where['type']);
        })->when(isset($where['key_type']) && $where['key_type'] !== '', function ($query) use ($where) {
            $query->where($this->joinAlis . '.key_type', $where['key_type']);
        })->where($this->joinAlis . '.keys', '<>', 'subscribe')
            ->where($this->joinAlis . '.keys', '<>', 'default');
    }

    /**
     * Nhận danh sách trả lời từ khóa
     * @param array $where
     * @param bool $group
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getReplyKeyList(array $where, int $page, int $limit)
    {
        return $this->search($where)->page($page, $limit)->group($this->alias . '.id')->field($this->alias . '.*,' . $this->joinAlis . '.keys')->select()->toArray();
    }

    /**
     * Lấy số lượng mặt hàng có điều kiện
     * @param array $where
     * @param bool $search
     * @return int
     */    public function count(array $where = [], bool $search = true)
    {
        return $this->search($where, $search)->group($this->alias . '.id')->count();
    }
}
