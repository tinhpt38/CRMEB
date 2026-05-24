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
/**
 * @author: thủy triều
 * @email: 442384644@qq.com
 * @date: 2023/8/3
 */
namespace app\adminapi\controller\v1\kefu;

use app\adminapi\controller\AuthController;
use app\services\wechat\WechatReplyServices;

class StoreServiceAutoReply extends AuthController
{
    /**
     * @return \think\Response
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/3
     */    public function autoReplyList()
    {
        $where = $this->request->getMore([
            ['key', ''],
            ['type', ''],
        ]);
        $where['key_type'] = 1;
        $list = app()->make(WechatReplyServices::class)->getKeyAll($where);
        return app('json')->success($list);
    }

    /**
     * Nhận biểu mẫu trả lời tự động
     * @param int $id
     * @return \think\Response
     * @throws \FormBuilder\Exception\FormBuilderException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/3
     */    public function autoReplyForm($id = 0)
    {
        return app('json')->success(app()->make(WechatReplyServices::class)->autoReplyForm($id));
    }

    /**
     * Lưu trả lời tự động
     * @param int $id
     * @return \think\Response
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/3
     */    public function autoReplySave($id = 0)
    {
        $data = $this->request->postMore([
            ['keys', ''],
            ['type', ''],
            ['data', ''],
            ['status', 1],
        ]);
        app()->make(WechatReplyServices::class)->autoReplySave($id, $data);
        return app('json')->success('Đã lưu thành công');
    }

    /**
     * Xóa thư trả lời tự động
     * @param $id
     * @return \think\Response
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/3
     */    public function autoReplyDel($id)
    {
        app()->make(WechatReplyServices::class)->autoReplyDel($id);
        return app('json')->success('Xóa thành công');
    }
}