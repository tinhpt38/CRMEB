<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
declare (strict_types=1);

namespace app\dao\system;

use app\dao\BaseDao;
use app\model\system\NoticeEventChannel;

/**
 * Notice event-channel mapping DAO.
 */class NoticeEventChannelDao extends BaseDao
{
    protected function setModel(): string
    {
        return NoticeEventChannel::class;
    }

    public function getTelegramRouteRows(string $eventMark): array
    {
        return $this->search([])
            ->alias('ec')
            ->join('notice_channel c', 'c.id = ec.channel_id')
            ->where('ec.event_mark', $eventMark)
            ->where('ec.enabled', 1)
            ->where('c.status', 1)
            ->where('c.channel_type', 'telegram')
            ->where('c.is_del', 0)
            ->field([
                'ec.id',
                'ec.template_text',
                'ec.template_map',
                'c.config',
            ])
            ->order('ec.priority asc, ec.id asc')
            ->select()
            ->toArray();
    }

    public function getActiveTelegramChannelIdByEvent(string $eventMark): int
    {
        return (int)$this->search([])
            ->alias('ec')
            ->join('notice_channel c', 'c.id = ec.channel_id')
            ->where('ec.event_mark', $eventMark)
            ->where('ec.enabled', 1)
            ->where('c.channel_type', 'telegram')
            ->where('c.is_del', 0)
            ->value('ec.channel_id');
    }

    public function getOneByEventAndChannel(string $eventMark, int $channelId)
    {
        return $this->getOne(['event_mark' => $eventMark, 'channel_id' => $channelId]);
    }
}

