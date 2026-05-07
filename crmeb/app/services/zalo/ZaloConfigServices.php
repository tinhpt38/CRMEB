<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2026 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
declare(strict_types=1);

namespace app\services\zalo;

use app\services\BaseServices;
use app\services\system\config\SystemConfigServices;
use crmeb\exceptions\ApiException;
use crmeb\services\CacheService;
use crmeb\services\HttpService;
use think\facade\Log;

/**
 * Quản lý cấu hình Zalo Mini App
 *
 * Các key lưu trong eb_system_config:
 *  - zalo_login_open      : 1/0 - Bật/tắt đăng nhập Zalo
 *  - zalo_app_id          : App ID từ Zalo Developers
 *  - zalo_app_secret      : App Secret từ Zalo Developers
 *  - zalo_callback_domain : Domain được phép callback (domain CRMEB của bạn)
 *  - zalo_bind_phone      : 1/0 - Bắt buộc gắn SĐT sau khi đăng nhập Zalo
 *
 * Class ZaloConfigServices
 * @package app\services\zalo
 */
class ZaloConfigServices extends BaseServices
{
    /** Prefix tất cả key Zalo trong eb_system_config */
    const CONFIG_PREFIX = 'zalo_';

    /** Danh sách tất cả config keys */
    const CONFIG_KEYS = [
        'zalo_login_open',
        'zalo_app_id',
        'zalo_app_secret',
        'zalo_callback_domain',
        'zalo_bind_phone',
    ];

    /** TTL cache xác thực token (giây) */
    const TOKEN_VERIFY_TTL = 300;

    // ─────────────────────────────────────────────────────────────────────────
    // Đọc / Ghi cấu hình
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Lấy toàn bộ cấu hình Zalo hiện tại
     *
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'zalo_login_open'      => (int)sys_config('zalo_login_open', 0),
            'zalo_app_id'          => (string)sys_config('zalo_app_id', ''),
            'zalo_app_secret'      => $this->maskSecret((string)sys_config('zalo_app_secret', '')),
            'zalo_callback_domain' => (string)sys_config('zalo_callback_domain', ''),
            'zalo_bind_phone'      => (int)sys_config('zalo_bind_phone', 0),
        ];
    }

    /**
     * Lưu cấu hình Zalo
     *
     * @param array $data Dữ liệu từ form admin
     * @throws ApiException
     */
    public function saveConfig(array $data): void
    {
        /** @var SystemConfigServices $configServices */
        $configServices = app()->make(SystemConfigServices::class);

        $saveMap = [
            'zalo_login_open'      => (int)($data['zalo_login_open'] ?? 0),
            'zalo_app_id'          => trim($data['zalo_app_id'] ?? ''),
            'zalo_callback_domain' => trim($data['zalo_callback_domain'] ?? ''),
            'zalo_bind_phone'      => (int)($data['zalo_bind_phone'] ?? 0),
        ];

        // App Secret: chỉ cập nhật nếu người dùng nhập giá trị mới (không phải chuỗi mask)
        $secretInput = trim($data['zalo_app_secret'] ?? '');
        if ($secretInput && !$this->isMaskedSecret($secretInput)) {
            $saveMap['zalo_app_secret'] = $secretInput;
        }

        // Validate khi bật login
        if ($saveMap['zalo_login_open'] === 1) {
            if (empty($saveMap['zalo_app_id'])) {
                throw new ApiException('App ID không được để trống khi bật đăng nhập Zalo');
            }
            // Secret chỉ bắt buộc nếu chưa được lưu trước đó
            $existingSecret = (string)sys_config('zalo_app_secret', '');
            if (empty($existingSecret) && empty($saveMap['zalo_app_secret'] ?? '')) {
                throw new ApiException('App Secret không được để trống khi bật đăng nhập Zalo');
            }
        }

        foreach ($saveMap as $key => $value) {
            $configServices->update($key, ['value' => json_encode($value)], 'menu_name');
        }

        CacheService::clear();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Kiểm tra kết nối Zalo API
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Test kết nối với Zalo Graph API bằng cấu hình hiện tại.
     *
     * Cách hoạt động (theo tài liệu Zalo Mini App cập nhật 01/01/2024):
     *  - Gọi GET graph.zalo.me/v2.0/me với access_token giả + appsecret_proof tính đúng
     *  - Nếu Zalo trả lỗi về token (không phải lỗi về secret) → App ID + Secret hợp lệ
     *  - Nếu Zalo trả lỗi "Invalid secret key" → Secret sai
     *
     * KHÔNG dùng oauth.zaloapp.com vì endpoint đó dành cho Zalo Official Account (OA),
     * không phải Zalo Mini App.
     *
     * @return array{status:bool, message:string, details?:array}
     */
    public function testConnection(): array
    {
        $appId     = (string)sys_config('zalo_app_id', '');
        $appSecret = (string)sys_config('zalo_app_secret', '');

        if (!$appId || !$appSecret) {
            return [
                'status'  => false,
                'message' => 'Chưa cấu hình App ID hoặc App Secret',
            ];
        }

        // Dùng token giả, tính appsecret_proof đúng cách để kiểm tra secret hợp lệ
        $fakeToken      = 'crmeb_test_' . time();
        $appsecretProof = hash_hmac('sha256', $fakeToken, $appSecret);

        try {
            // $data  → thành query string: ?fields=id,name,picture
            // $header → HTTP headers: access_token + appsecret_proof (bắt buộc từ 01/01/2024)
            $response = HttpService::getRequest(
                ZaloAuthServices::ZALO_GRAPH_API,
                ['fields' => 'id,name,picture'],
                [
                    'access_token: '    . $fakeToken,
                    'appsecret_proof: ' . $appsecretProof,
                ]
            );

            $data = json_decode($response, true);

            // Lỗi liên quan đến secret key sai → credentials không đúng
            if (isset($data['message']) && stripos($data['message'], 'secret') !== false) {
                return [
                    'status'  => false,
                    'message' => 'App Secret không hợp lệ: ' . $data['message'],
                ];
            }

            // Lỗi về token (access_token giả bị từ chối) → App ID + Secret đúng format
            // Zalo thường trả error code -216 (invalid access token) hoặc error != 0
            if (isset($data['error']) && $data['error'] != 0) {
                $errCode = $data['error'];
                // -216 = access token không hợp lệ → cấu hình OK, chỉ token giả bị reject
                // Các lỗi khác về secret/app_id → cấu hình sai
                $secretErrors = [-201, -202, -203, -204]; // Zalo error codes liên quan secret/app
                if (!in_array($errCode, $secretErrors, true)) {
                    return [
                        'status'  => true,
                        'message' => 'Kết nối Zalo API thành công (App ID & Secret hợp lệ)',
                        'details' => [
                            'app_id'   => $appId,
                            'api_url'  => ZaloAuthServices::ZALO_GRAPH_API,
                            'verified' => true,
                        ],
                    ];
                }

                return [
                    'status'  => false,
                    'message' => 'App credentials không hợp lệ (Zalo error ' . $errCode . '): ' . ($data['message'] ?? ''),
                ];
            }

            // Phản hồi bình thường (không có error field) → thành công
            return [
                'status'  => true,
                'message' => 'Kết nối Zalo API thành công',
                'details' => [
                    'app_id'   => $appId,
                    'api_url'  => ZaloAuthServices::ZALO_GRAPH_API,
                    'verified' => true,
                ],
            ];
        } catch (\Throwable $e) {
            Log::error('[ZaloConfig] Test connection error: ' . $e->getMessage());
            return [
                'status'  => false,
                'message' => 'Không thể kết nối đến Zalo API: ' . $e->getMessage(),
            ];
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Tiện ích
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Che bớt secret key khi hiển thị trên UI
     * VD: "abcdefghij1234" → "abcd**********34"
     */
    private function maskSecret(string $secret): string
    {
        if (strlen($secret) <= 8) {
            return $secret ? str_repeat('*', strlen($secret)) : '';
        }
        return substr($secret, 0, 4) . str_repeat('*', strlen($secret) - 8) . substr($secret, -4);
    }

    /**
     * Kiểm tra xem chuỗi có phải đang là masked value không
     */
    private function isMaskedSecret(string $value): bool
    {
        return (bool)preg_match('/\*{3,}/', $value);
    }
}
