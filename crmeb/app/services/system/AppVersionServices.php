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
declare (strict_types=1);

namespace app\services\system;

use app\dao\system\AppVersionDao;
use app\services\BaseServices;
use crmeb\services\FormBuilder as Form;
use think\facade\Route as Url;

/**
 * Class AppVersionServices
 * @package app\services\system
 */class AppVersionServices extends BaseServices
{
    /**
     * DiyServices constructor.
     * @param AppVersionDao $dao
     */    public function __construct(AppVersionDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Danh sách phiên bản
     * @param $platform
     * @return array
     */    public function versionList($platform)
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->versionList($platform, $page, $limit);
        foreach ($list as &$item) {
            $item['add_time'] = date('Y-m-d H:i:s', $item['add_time']);
        }
        $count = $this->dao->count(['platform' => $platform]);
        return compact('list', 'count');
    }

    /**
     * Thêm mẫu phiên bản
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     */    public function createForm($id = 0)
    {
        if ($id) {
            $info = $this->dao->get($id);
        }
        $field[] = Form::hidden('id', $info['id'] ?? 0);
        $field[] = Form::input('version', 'số phiên bản', $info['version'] ?? '')->col(24);
        $field[] = Form::radio('platform', 'loại nền tảng', $info['platform'] ?? 1)->options([['label' => 'Android', 'value' => 1], ['label' => 'IOS', 'value' => 2]]);
        $field[] = Form::input('info', 'Giới thiệu phiên bản', $info['info'] ?? '')->type('textarea');
        $field[] = Form::input('url', 'Liên kết tải xuống', $info['url'] ?? '')->appendRule('suffix', [
            'type' => 'div',
            'class' => 'tips-info',
            'domProps' => ['innerHTML' => 'Điền vào liên kết tải xuống. Đối với Android, đó là địa chỉ url của gói nén. Bấm để nâng cấp, gói nén sẽ tự động được tải xuống và thay thế để Cài đặt, ví dụ: tên miền/xxx.zip; đối với iOS, đó là địa chỉ liên kết của cửa hàng Ứng dụng, chẳng hạn sẽ chuyển thẳng đến AppStore.：itms-apps://itunes.apple.com/cn/app/id1234567890']
        ]);
        $field[] = Form::radio('is_force', 'lực lượng', $info['is_force'] ?? 1)->options([['label' => 'Hoạt động', 'value' => 1], ['label' => 'đóng cửa', 'value' => 0]]);
        $field[] = Form::radio('is_new', 'Đây có phải là cái mới nhất không', $info['is_new'] ?? 1)->options([['label' => 'Đúng', 'value' => 1], ['label' => 'KHÔNG', 'value' => 0]]);
        return create_form('Thông tin phiên bản', $field, Url::buildUrl('/system/version_save'), 'POST');

    }

    /**
     * lưu dữ liệu
     * @param $id
     * @param $data
     * @return mixed
     */    public function versionSave($id, $data)
    {
        if ($id) {
            return $this->transaction(function () use ($data, $id) {
                if ($data['is_new']) {
                    $this->dao->update(['platform' => $data['platform']], ['is_new' => 0]);
                }
                return $this->dao->update($id, $data);
            });
        } else {
            $data['is_del'] = 0;
            $data['add_time'] = time();
            return $this->transaction(function () use ($data) {
                $this->dao->update(['platform' => $data['platform']], ['is_new' => 0]);
                return $this->dao->save($data);
            });
        }
    }

    /**
     * Nhận thông tin phiên bản mới nhất theo hệ thống
     * @param $platform
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getNewInfo($platform)
    {
        $res = $this->dao->get(['platform' => $platform, 'is_new' => 1]);
        if ($res) {
            $res = $res->toArray();
            $res['time'] = date('Y-m-d H:i:s', $res['add_time']);
            return $res;
        } else {
            return [];
        }
    }
}
