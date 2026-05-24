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

namespace app\dao\other;


use app\dao\BaseDao;
use app\model\other\TemplateMessage;

/**
 * tin nhắn mẫu
 * Class TemplateMessageDao
 * @package app\dao\other
 */class TemplateMessageDao extends BaseDao
{
    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return TemplateMessage::class;
    }

    /**
     * Nhận danh sách tin nhắn mẫu
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getTemplateList(array $where, int $page, int $limit)
    {

        return $this->getModel()->when(isset($where['name']) && $where['name']!='', function ($query) use ($where) {
            $query->where('name','LIKE', "%$where[name]%");
        })->when(isset($where['status']) && $where['status']!='', function ($query) use ($where) {
            $query->where('status',$where['status']);
        })->where('type',$where['type'])->page($page, $limit)->select()->toArray();
    }

}
