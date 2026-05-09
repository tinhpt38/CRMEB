<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2026 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------

namespace app\services\message\notice;

use app\services\message\NoticeRouterServices;
use app\services\message\NoticeService;
use crmeb\services\HttpService;
use think\facade\Log;

/**
 * Gửi thông báo qua Telegram Bot API.
 */
class TelegramService extends NoticeService
{
    /**
     * Gửi tin nhắn Telegram theo cấu hình của từng sự kiện thông báo.
     * @param array $data
     * @return bool
     */
    public function send(array $data): bool
    {
        // Ưu tiên routing kênh tập trung (event + channel registry).
        if (!empty($this->event)) {
            try {
                $sent = app()->make(NoticeRouterServices::class)->dispatchTelegram($this->event, $data);
                if ($sent > 0) {
                    return true;
                }
            } catch (\Throwable $e) {
                Log::error('Notice router Telegram lỗi:' . $e->getMessage());
            }
        }

        // Fallback legacy theo cấu hình cũ trong eb_system_notification.
        if (($this->noticeInfo['is_telegram'] ?? 0) != 1) {
            return true;
        }

        $botToken = trim((string)($this->noticeInfo['telegram_bot_token'] ?? ''));
        $chatId = trim((string)($this->noticeInfo['telegram_chat_id'] ?? ''));
        $template = (string)($this->noticeInfo['telegram_text'] ?? '');
        if ($botToken === '' || $chatId === '' || $template === '') {
            return true;
        }

        try {
            $text = $template;
            foreach ($data as $key => $value) {
                if (is_scalar($value)) {
                    $text = str_replace('{' . $key . '}', (string)$value, $text);
                }
            }

            $url = 'https://api.telegram.org/bot' . $botToken . '/sendMessage';
            HttpService::postRequest($url, [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true,
            ]);
        } catch (\Throwable $e) {
            Log::error('Không gửi được Telegram,Lý do thất bại:' . $e->getMessage());
        }

        return true;
    }
}
