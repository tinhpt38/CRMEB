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
namespace app\services\system;

use app\dao\system\SystemSignRewardDao;
use app\services\BaseServices;
use crmeb\exceptions\AdminException;
use crmeb\services\FormBuilder as Form;
use think\facade\Route as Url;

/**
 * @author: thủy triều
 * @email: 442384644@qq.com
 * @date: 2023/7/28
 */class SystemSignRewardServices extends BaseServices
{
    /**
     * @param SystemSignRewardDao $dao
     */    public function __construct(SystemSignRewardDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Danh sách phần thưởng đăng nhập
     * @param int $type
     * @return array
     * @throws \ReflectionException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/7/31
     */    public function getList($type = 0)
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->selectList(['type' => $type], '*', $page, $limit, 'days');
        $count = $this->dao->count(['type' => $type]);
        return compact('list', 'count');
    }

    /**
     * Đã thêm và sửa đổi hình thức phần thưởng đăng nhập
     * @param int $id
     * @param int $type
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/7/31
     */    public function rewardsForm($id = 0, $type = 0)
    {
        $info = $this->dao->get($id);
        if ($info) $type = $info['type'];
        $form[] = Form::hidden('type', $type);
        $form[] = Form::number('days', $type == 1 ? 'Số ngày nhận phòng tích lũy' : 'Số ngày nhận phòng liên tiếp', (int)($info['days'] ?? 0))->max(sys_config('sign_mode') == 1 ? 7 : 30);
        $form[] = Form::number('point', 'Tặng điểm', (int)($info['point'] ?? 0))->controls(false)->max(999)->min(0);
        $form[] = Form::number('exp', 'Trải nghiệm quà tặng', (int)($info['exp'] ?? 0))->controls(false)->max(999)->min(0);
        return create_form($type == 1 ? 'Phần thưởng đăng nhập tích lũy' : 'Phần thưởng đăng nhập liên tục', $form, Url::buildUrl('/marketing/sign/save_rewards/' . $id), 'POST');
    }

    /**
     * Lưu phần thưởng đăng nhập
     * @param $id
     * @param $data
     * @return bool
     * @throws \ReflectionException
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/10
     */    public function saveRewards($id, $data)
    {
        if ($id) {
            $this->dao->update($id, $data);
        } else {
            if ($this->dao->count(['type' => $data['type'], 'days' => $data['days']])) {
                throw new AdminException('Phần thưởng đăng nhập đã tồn tại');
            } else {
                $this->dao->save($data);
            }
        }
        return true;
    }

    /**
     * Nhận dữ liệu phần thưởng đăng nhập tích lũy hoặc liên tục
     * @param $type
     * @param $days
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author: thủy triều
     * @email: 442384644@qq.com
     * @date: 2023/8/1
     */    public function getSignRewards($type, $days)
    {
        $info = $this->dao->get(['type' => $type, 'days' => $days]);
        if ($info) return [true, $info['point'], $info['exp']];
        return [false, 0, 0];
    }
}