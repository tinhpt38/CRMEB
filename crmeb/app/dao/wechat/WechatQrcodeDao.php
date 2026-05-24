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
namespace app\dao\wechat;


use app\dao\BaseDao;
use app\model\wechat\WechatQrcode;

class WechatQrcodeDao extends BaseDao
{
    /**
     * @return string
     */    protected function setModel(): string
    {
        return WechatQrcode::class;
    }

    /**
     * Nhận danh sách
     * @param $where
     * @param $page
     * @param $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getList($where, $page = 0, $limit = 0)
    {
        return $this->search($where)->with(['user', 'record' => function ($query) {
            $query->where('is_follow', 1)->whereDay('add_time', 'yesterday')->field('qid,count(distinct uid) as number')->bind(['y_follow' => 'number']);
        }])->when($page && $limit, function ($query) use ($page, $limit) {
            $query->page($page, $limit);
        })->order('id desc')->select()->toArray();
    }

    /**
     * Thời gian cập nhật
     * @param $id
     * @param $isFollow
     */    public function upFollowAndScan($id, $isFollow)
    {
        $this->getModel()->where('id', $id)->inc('scan')->when($isFollow, function ($query) {
            $query->inc('follow');
        })->update();
    }
}
