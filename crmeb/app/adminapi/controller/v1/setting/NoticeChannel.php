<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------

namespace app\adminapi\controller\v1\setting;

use app\adminapi\controller\AuthController;
use app\services\message\NoticeChannelServices;
use think\facade\App;

/**
 * Quản lý kênh thông báo tập trung.
 */
class NoticeChannel extends AuthController
{
    public function __construct(App $app, NoticeChannelServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    public function index()
    {
        $where = $this->request->getMore([
            ['channel_type', ''],
        ]);
        return app('json')->success($this->services->getList($where));
    }

    public function save()
    {
        $data = $this->request->postMore([
            ['channel_type', 'telegram'],
            ['channel_key', ''],
            ['name', ''],
            ['status', 1],
            ['bot_token', ''],
            ['chat_id', ''],
        ]);
        $this->services->saveChannel($data);
        return app('json')->success('Đã lưu thành công');
    }

    public function update($id)
    {
        $data = $this->request->postMore([
            ['channel_type', 'telegram'],
            ['channel_key', ''],
            ['name', ''],
            ['status', 1],
            ['bot_token', ''],
            ['chat_id', ''],
        ]);
        $this->services->updateChannel((int)$id, $data);
        return app('json')->success('Cập nhật thành công');
    }

    public function setStatus($id, $status)
    {
        if ($id === '' || $status === '') return app('json')->fail('Lỗi tham số');
        $this->services->update((int)$id, ['status' => (int)$status]);
        return app('json')->success('Thiết lập thành công');
    }

    public function delete($id)
    {
        if (!$id) return app('json')->fail('Lỗi tham số');
        $this->services->deleteChannel((int)$id);
        return app('json')->success('Xóa thành công');
    }

    public function telegramOptions()
    {
        return app('json')->success($this->services->getTelegramOptions());
    }

    public function testTelegram()
    {
        $data = $this->request->postMore([
            ['bot_token', ''],
            ['chat_id', ''],
            ['text', 'Thong bao test tu CRMEB'],
        ]);
        $this->services->testTelegram($data);
        return app('json')->success('Đã gửi thử Telegram');
    }
}

