<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
declare (strict_types=1);

namespace app\services\message;

use app\dao\system\NoticeEventChannelDao;
use app\services\BaseServices;
use crmeb\services\HttpService;
use think\facade\Log;

/**
 * Router thông báo theo event + channel cấu hình tập trung.
 */class NoticeRouterServices extends BaseServices
{
    /**
     * @var NoticeEventChannelDao
     */    protected $dao;

    public function __construct(NoticeEventChannelDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Gửi Telegram theo routing table.
     * @param string $eventMark
     * @param array $data
     * @return int Số kênh đã thử gửi.
     */    public function dispatchTelegram(string $eventMark, array $data): int
    {
        if ($eventMark === '') {
            return 0;
        }

        $rows = $this->dao->getTelegramRouteRows($eventMark);

        if (!$rows) {
            return 0;
        }

        $sent = 0;
        foreach ($rows as $row) {
            $config = json_decode((string)($row['config'] ?? ''), true) ?: [];
            $botToken = trim((string)($config['bot_token'] ?? ''));
            $chatId = trim((string)($config['chat_id'] ?? ''));
            if ($botToken === '' || $chatId === '') {
                continue;
            }

            $payload = $this->resolvePayload($data, (string)($row['template_map'] ?? ''));
            $templateText = trim((string)($row['template_text'] ?? ''));
            if ($templateText === '') {
                $templateText = 'Don #{order_id} | Khach: {real_name} | SDT: {user_phone} | Gia tri: {pay_price}';
            }
            $message = $this->renderTemplate($templateText, $payload);

            try {
                HttpService::postRequest('https://api.telegram.org/bot' . $botToken . '/sendMessage', [
                    'chat_id' => $chatId,
                    'text' => $message,
                    'parse_mode' => 'HTML',
                    'disable_web_page_preview' => true,
                ]);
                $sent++;
            } catch (\Throwable $e) {
                Log::error('[NoticeRouterServices] Telegram send failed: ' . $e->getMessage());
            }
        }

        return $sent;
    }

    protected function renderTemplate(string $template, array $data): string
    {
        foreach ($data as $key => $value) {
            if (is_scalar($value)) {
                $template = str_replace('{' . $key . '}', (string)$value, $template);
            }
        }
        return $template;
    }

    protected function resolvePayload(array $data, string $mappingJson): array
    {
        $mapping = json_decode($mappingJson, true);
        if (!is_array($mapping) || !$mapping) {
            return $data;
        }

        $payload = $data;
        foreach ($mapping as $targetKey => $sourceKey) {
            if (is_string($targetKey) && is_string($sourceKey) && array_key_exists($sourceKey, $data)) {
                $payload[$targetKey] = $data[$sourceKey];
            }
        }

        return $payload;
    }
}

