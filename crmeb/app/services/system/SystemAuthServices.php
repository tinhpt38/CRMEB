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

namespace app\services\system;


use app\services\BaseServices;
use crmeb\exceptions\AdminException;
use crmeb\services\HttpService;

/**
 * ủy quyền thương mại
 * Class SystemAuthServices
 * @package app\services\system
 */
class SystemAuthServices extends BaseServices
{

    /**
     * Nộp đơn xin ủy quyền
     * @param array $data
     * @return bool
     */
    public function authApply(array $data)
    {
        $res = HttpService::postRequest('http://authorize.crmeb.net/api/auth_apply', $data);
        if ($res === false) {
            throw new AdminException('Ứng dụng không thành công');
        }
        $res = json_decode($res, true);
        if (isset($res['status'])) {
            if ($res['status'] == 400) {
                throw new AdminException('Ứng dụng không thành công');
            } else {
                return true;
            }
        }
    }
}
