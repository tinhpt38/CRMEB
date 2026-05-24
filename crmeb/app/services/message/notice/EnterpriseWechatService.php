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

namespace app\services\message\notice;

use app\jobs\notice\EnterpriseWechatJob;
use app\services\message\NoticeService;
use crmeb\services\HttpService;
use think\facade\Log;

/**
 * Gửi tin nhắn qua WeChat của công ty
 * Created by PhpStorm.
 * User: xurongyao <763569752@qq.com>
 * Date: 2021/9/22 1:23 PM
 */class EnterpriseWechatService extends NoticeService
{
    /**
     * Xác định xem quyền có được bật hay không
     * @var bool
     */    private $isOpen = true;

    /**
     * Có bật quyền hay không
     * @param string $mark
     * @return $this
     */    public function isOpen(string $mark)
    {
        $this->isOpen = $this->noticeInfo['is_ent_wechat'] == 1 && $this->noticeInfo['url'] !== '';
        return $this;

    }

    /**
     * Gửi tin nhắn CSKH WeChat của công ty
     * @param $data
     */    public function weComSend($data)
    {
        if ($this->noticeInfo['is_ent_wechat'] == 1 && $this->noticeInfo['url'] !== '') {
            $url = $this->noticeInfo['url'];
            $ent_wechat_text = $this->noticeInfo['ent_wechat_text'];
            try {
                $str = $ent_wechat_text;
                foreach ($data as $key => $item) {
                    $str = str_replace('{' . $key . '}', $item, $str);
                }
                $s = explode('\n', $str);
                $d = '';
                foreach ($s as $item) {
                    $d .= $item . "\n>";
                }
                $d = substr($d, 0, strlen($d) - 2);
                HttpService::postRequest($url, json_encode([
                    'msgtype' => 'markdown',
                    'markdown' => ['content' => $d]
                ]));
            } catch (\Throwable $e) {
                Log::error('Không gửi được tin nhắn nhóm doanh nghiệp,Lý do thất bại:' . $e->getMessage());

            }
        }
    }
}
