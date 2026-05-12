<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
declare(strict_types=1);

namespace app\api\controller\v1\zalo;

use app\services\zalo\ZaloMiniAppThemeServices;
use think\Response;

/**
 * Theme công khai cho Zalo Mini App.
 */
class ZaloThemeController
{
    protected ZaloMiniAppThemeServices $services;

    public function __construct(ZaloMiniAppThemeServices $services)
    {
        $this->services = $services;
    }

    /**
     * GET /api/zalo/theme
     */
    public function theme(): Response
    {
        $payload = $this->services->getPublicThemePayload();
        $etag = 'W/"zalo-theme-' . $payload['version'] . '"';

        return app('json')->header([
            'ETag' => $etag,
            'Cache-Control' => 'public, max-age=60',
        ])->success($payload);
    }
}
