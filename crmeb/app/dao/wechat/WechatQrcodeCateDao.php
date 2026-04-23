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
namespace app\dao\wechat;


use app\dao\BaseDao;
use app\model\wechat\WechatQrcodeCate;

class WechatQrcodeCateDao extends BaseDao
{
    /**
     * @return string
     */
    protected function setModel(): string
    {
        return WechatQrcodeCate::class;
    }

    /**
     * Danh sách phân loại mã kênh
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getCateList()
    {
        return $this->getModel()->where('is_del', 0)->select()->toArray();
    }
}