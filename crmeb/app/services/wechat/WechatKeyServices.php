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

namespace app\services\wechat;


use app\dao\wechat\WechatKeyDao;
use app\services\BaseServices;

/**
 * Trình đơn WeChat
 * Class WechatMenuServices
 * @package app\services\wechat
 * @method delete($id, ?string $key = null)  Xóa
 * @method getOne(array $where)  Lấy một phần dữ liệu
 * @method count(array $where)  Số mục dữ liệu được đọc
 * @method saveAll(array $where)  Chèn dữ liệu
 * @method getColumn($where,$key)  Nhận một mảng trường
 */class WechatKeyServices extends BaseServices
{
    /**
     * Người xây dựng
     * WechatMenuServices constructor.
     * @param WechatKeyDao $dao
     */    public function __construct(WechatKeyDao $dao)
    {
        $this->dao = $dao;
    }

}
