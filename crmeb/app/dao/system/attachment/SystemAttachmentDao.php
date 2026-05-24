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

namespace app\dao\system\attachment;

use app\dao\BaseDao;
use app\model\system\attachment\SystemAttachment;

/**
 *
 * Class SystemAttachmentDao
 * @package app\dao\attachment
 */class SystemAttachmentDao extends BaseDao
{

    /**
     * Thiết lập mô hình
     * @return string
     */    protected function setModel(): string
    {
        return SystemAttachment::class;
    }

    /**
     * Lấy danh sách hình ảnh
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getList(array $where, int $page, int $limit)
    {
        return $this->search($where)->where('module_type', 1)->page($page, $limit)->order('att_id DESC')->select()->toArray();
    }

    /**
     * hình ảnh chuyển động
     * @param array $data
     * @return \crmeb\basic\BaseModel
     */    public function move(array $data)
    {
        return $this->getModel()->whereIn('att_id', $data['images'])->update(['pid' => $data['pid']]);
    }

    /**
     * Nhận tên
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getLikeNameList(array $where, int $page, int $limit)
    {
        return $this->search($where)->page($page, $limit)->order('att_id desc')->select()->toArray();
    }

    /**
     * Tạo hệ thống của ngày hôm qua
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getYesterday()
    {
        return $this->getModel()->whereTime('time', 'yesterday')->where('module_type', 2)->field(['name', 'att_dir', 'att_id', 'image_type'])->select();
    }

    /**
     * Xóa poster được tạo của ngày hôm qua
     * @throws \Exception
     */    public function delYesterday()
    {
        $this->getModel()->whereTime('time', 'yesterday')->where('module_type', 2)->delete();
    }

    /**
     * Lấy dữ liệu hình ảnh được tải lên bằng cách quét mã QR
     * @param $scan_token
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author thủy triều
     * @email 442384644@qq.com
     * @date 2023/06/13
     */    public function scanUploadImage($scan_token)
    {
        return $this->getModel()->where('scan_token', $scan_token)->field('att_dir,att_id')->select()->toArray();
    }
}
