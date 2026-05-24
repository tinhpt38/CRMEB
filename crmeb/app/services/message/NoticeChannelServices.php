<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
declare (strict_types=1);

namespace app\services\message;

use app\dao\system\NoticeChannelDao;
use app\services\BaseServices;
use crmeb\exceptions\AdminException;
use crmeb\services\HttpService;

/**
 * Quản lý kênh thông báo tập trung.
 */class NoticeChannelServices extends BaseServices
{
    protected $dao;

    public function __construct(NoticeChannelDao $dao)
    {
        $this->dao = $dao;
    }

    public function getList(array $where): array
    {
        return $this->dao->getList($where);
    }

    public function saveChannel(array $data): bool
    {
        $payload = $this->normalizeData($data);
        $exists = $this->dao->getOne(['channel_key' => $payload['channel_key'], 'is_del' => 0]);
        if ($exists) {
            throw new AdminException('Mã kênh đã tồn tại, vui lòng dùng mã khác');
        }
        $payload['add_time'] = time();
        $payload['update_time'] = time();
        return (bool)$this->dao->save($payload);
    }

    public function updateChannel(int $id, array $data): bool
    {
        if (!$this->dao->get($id)) {
            throw new AdminException('Kênh không tồn tại');
        }
        $payload = $this->normalizeData($data);
        $exists = $this->dao->getOne(['channel_key' => $payload['channel_key'], 'is_del' => 0]);
        if ($exists && (int)$exists->id !== $id) {
            throw new AdminException('Mã kênh đã tồn tại, vui lòng dùng mã khác');
        }
        $payload['update_time'] = time();
        return (bool)$this->dao->update($id, $payload);
    }

    public function deleteChannel(int $id): bool
    {
        return (bool)$this->dao->update($id, ['is_del' => 1, 'update_time' => time()]);
    }

    public function getTelegramOptions(): array
    {
        return $this->dao->getTelegramOptions();
    }

    public function testTelegram(array $data): bool
    {
        $config = $this->buildConfig($data);
        $botToken = trim((string)($config['bot_token'] ?? ''));
        $chatId = trim((string)($config['chat_id'] ?? ''));
        if ($botToken === '' || $chatId === '') {
            throw new AdminException('Vui lòng cấu hình bot token và chat id');
        }
        $text = trim((string)($data['text'] ?? 'Thong bao test tu CRMEB'));
        HttpService::postRequest('https://api.telegram.org/bot' . $botToken . '/sendMessage', [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML',
            'disable_web_page_preview' => true,
        ]);
        return true;
    }

    protected function normalizeData(array $data): array
    {
        $channelType = trim((string)($data['channel_type'] ?? ''));
        $channelKey = trim((string)($data['channel_key'] ?? ''));
        $name = trim((string)($data['name'] ?? ''));
        if ($channelType === '' || $channelKey === '' || $name === '') {
            throw new AdminException('Vui lòng nhập loại kênh, mã kênh và tên kênh');
        }
        return [
            'channel_type' => $channelType,
            'channel_key' => $channelKey,
            'name' => $name,
            'status' => (int)($data['status'] ?? 1),
            'config' => json_encode($this->buildConfig($data), JSON_UNESCAPED_UNICODE),
        ];
    }

    protected function buildConfig(array $data): array
    {
        if (!empty($data['config']) && is_array($data['config'])) {
            return $data['config'];
        }
        return [
            'bot_token' => (string)($data['bot_token'] ?? ''),
            'chat_id' => (string)($data['chat_id'] ?? ''),
        ];
    }
}

