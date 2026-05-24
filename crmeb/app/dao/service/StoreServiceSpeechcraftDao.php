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

namespace app\dao\service;


use app\dao\BaseDao;
use app\model\service\StoreServiceSpeechcraft;

/**
 * Kỹ năng phục vụ khách hàngdao
 * Class StoreServiceSpeechcraftDao
 * @package app\dao\service
 */class StoreServiceSpeechcraftDao extends BaseDao
{
    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return StoreServiceSpeechcraft::class;
    }

    /**
     * Lấy danh sách các từ
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getSpeechcraftList(array $where, int $page, int $limit)
    {
        return $this->search($where)->with(['cateName'])->when($page && $limit, function ($query) use ($page, $limit) {
            $query->page($page, $limit);
        })->order('sort DESC')->select()->toArray();
    }
}
