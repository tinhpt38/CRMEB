<?php

use think\facade\Route;

Route::get('surl/:id', function(\app\Request $request){
    return app()->make(\app\api\controller\v1\PublicController::class)->getSchemeUrl($request->param('id'));
});

Route::miss(function () {
    $appRequest = request()->pathinfo();
    if ($appRequest === null) {
        $appName = '';
    } else {
        $appRequest = str_replace('//', '/', $appRequest);
        $appName = explode('/', $appRequest)[0] ?? '';
    }

    switch (strtolower($appName)) {
        case config('app.admin_prefix', 'admin'):
        case 'kefu':
        case 'app':
            return view(app()->getRootPath() . 'public' . DS . config('app.admin_prefix', 'admin') . DS . 'index.html');
        case 'home':
            if (request()->isMobile()) {
                return redirect(app()->route->buildUrl('/'));
            } else {
                return view(app()->getRootPath() . 'public' . DS . 'home' . DS . 'index.html', [
                    'siteName'        => (string) sys_config('site_name', ''),
                    'siteUrl'         => (string) sys_config('site_url', ''),
                    'siteLogoUrl'     => (string) set_file_url(trim((string) sys_config('site_logo', ''))),
                    'miniAppDeeplink' => (string) sys_config('zalo_mini_app_deeplink', ''),
                    'miniAppQrUrl'    => (string) set_file_url(trim((string) sys_config('zalo_mini_app_qr_image', ''))),
                ]);
            }
        case 'pages':
            return view(app()->getRootPath() . 'public' . DS . 'index.html');
        default:
            if (!request()->isMobile()) {
                if (is_dir(app()->getRootPath() . 'public' . DS . 'home') && !request()->get('mdType')) {
                    return view(app()->getRootPath() . 'public' . DS . 'home' . DS . 'index.html', [
                        'siteName'        => (string) sys_config('site_name', ''),
                        'siteUrl'         => (string) sys_config('site_url', ''),
                        'siteLogoUrl'     => (string) set_file_url(trim((string) sys_config('site_logo', ''))),
                        'miniAppDeeplink' => (string) sys_config('zalo_mini_app_deeplink', ''),
                        'miniAppQrUrl'    => (string) set_file_url(trim((string) sys_config('zalo_mini_app_qr_image', ''))),
                    ]);
                } else {
                    if (request()->get('type')) {
                        return view(app()->getRootPath() . 'public' . DS . 'index.html');
                    } else {
                        return view(app()->getRootPath() . 'public' . DS . 'mobile.html', ['siteName' => sys_config('site_name'), 'siteUrl' => sys_config('site_url') . '/pages/index/index']);
                    }
                }
            } else {
                return view(app()->getRootPath() . 'public' . DS . 'index.html');
            }
    }
});
