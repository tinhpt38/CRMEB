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
namespace app\services\message;


use app\dao\other\TemplateMessageDao;
use app\services\BaseServices;

/**
 * Lớp quản lý tin nhắn mẫu
 * Class TemplateMessageServices
 * @package app\services\other
 * @method getOne(array $where, ?string $field = '*')  Nhận tin nhắn
 * @method save(array $data) Thêm vào
 * @method get(int $id, ?array $field = []) Nhận tin nhắn
 * @method update($id, array $data, ?string $key = null) Cập nhật dữ liệu
 * @method delete($id, ?string $key = null) xóa bỏ
 */
class TemplateMessageServices extends BaseServices
{
    /**
     * tin nhắn mẫu
     * TemplateMessageServices constructor.
     * @param TemplateMessageDao $dao
     */
    public function __construct(TemplateMessageDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Nhận danh sách tin nhắn mẫu
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getTemplateList(array $where)
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getTemplateList($where, $page, $limit);
        foreach ($list as &$item) {
            if ($item['content']) $item['content'] = explode("\n", $item['content']);
        }
        $count = $this->dao->count($where);
        return compact('list', 'count');
    }

    /**
     * Nhận tin nhắn mẫuid
     * @param string $templateId
     * @param int $type
     * @return mixed
     */
    public function getTempId(string $templateId, int $type = 0)
    {
        return $this->dao->value(['type' => $type, 'tempkey' => $templateId, 'status' => 1], 'tempid');
    }

}
