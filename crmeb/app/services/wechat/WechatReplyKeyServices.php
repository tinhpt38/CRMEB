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

namespace app\services\wechat;

use app\services\BaseServices;
use app\dao\wechat\WechatReplyKeyDao;

/**
 *
 * Class UserWechatuserServices
 * @package app\services\user
 */class WechatReplyKeyServices extends BaseServices
{

    /**
     * Người xây dựng
     * WechatReplyKeyServices constructor.
     * @param WechatReplyKeyDao $dao
     */    public function __construct(WechatReplyKeyDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @param array $where
     * @return mixed
     */    public function getReplyKeyAll(array $where)
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getReplyKeyList($where, $page, $limit);
        $count = $this->dao->count($where);
        return compact('list', 'count');
    }
}
