<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
declare (strict_types=1);

namespace app\dao\system;

use app\dao\BaseDao;
use app\model\system\NoticeChannel;

/**
 * Notice channel DAO.
 */
class NoticeChannelDao extends BaseDao
{
    protected function setModel(): string
    {
        return NoticeChannel::class;
    }

    public function getList(array $where = []): array
    {
        return $this->search([])->where('is_del', 0)
            ->when($where['channel_type'] ?? '', function ($query, $channelType) {
                $query->where('channel_type', $channelType);
            })
            ->order('id desc')
            ->select()
            ->toArray();
    }

    public function getTelegramOptions(): array
    {
        return $this->search([])
            ->where('is_del', 0)
            ->where('status', 1)
            ->where('channel_type', 'telegram')
            ->field(['id', 'name', 'channel_key'])
            ->order('id desc')
            ->select()
            ->toArray();
    }
}

