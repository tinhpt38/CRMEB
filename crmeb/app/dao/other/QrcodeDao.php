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

namespace app\dao\other;

use app\dao\BaseDao;
use app\model\other\Qrcode;

/**
 *
 * Class QrcodeDao
 * @package app\dao\other
 */class QrcodeDao extends BaseDao
{

    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return Qrcode::class;
    }

    /**
     * Nhận mã QR
     * @param $id
     * @param string $type
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getQrcode($id, $type = 'id')
    {
        return $this->getModel()->where($type, $id)->find();
    }

    /**
     * Sửa đổi trạng thái sử dụng mã QR
     * @param $id
     * @param string $type
     * @return mixed
     */    public function scanQrcode($id, $type = 'id')
    {
        return $this->getModel()->where($type, $id)->inc('scan')->update();
    }
}
