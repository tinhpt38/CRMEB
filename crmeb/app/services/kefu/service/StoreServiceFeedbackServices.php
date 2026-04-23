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

namespace app\services\kefu\service;


use app\dao\service\StoreServiceFeedbackDao;
use app\services\BaseServices;
use crmeb\exceptions\AdminException;
use crmeb\services\FormBuilder;

/**
 * Phản hồi về dịch vụ khách hàng
 * Class StoreServiceFeedbackServices
 * @package app\services\kefu\service
 */
class StoreServiceFeedbackServices extends BaseServices
{

    /**
     * StoreServiceFeedbackServices constructor.
     * @param StoreServiceFeedbackDao $dao
     */
    public function __construct(StoreServiceFeedbackDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Nhận danh sách phản hồi
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getFeedbackList(array $where)
    {
        [$page, $limit] = $this->getPageValue();
        $data = $this->dao->getFeedback($where, $page, $limit);
        $count = $this->dao->count($where);
        return compact('data', 'count');
    }

    /**
     *
     * @param int $id
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function editForm(int $id)
    {
        $feedInfo = $this->dao->get($id);
        if (!$feedInfo) {
            throw new AdminException('Không tìm thấy nội dung phản hồi');
        }
        $feedInfo = $feedInfo->toArray();
        $field = [
            FormBuilder::textarea('make', 'Nhận xét', $feedInfo['make'])->col(22),
        ];
        if (!$feedInfo['status']) {
            $field[] = FormBuilder::radio('status', 'tình trạng', 0)->setOptions([
                ['label' => 'Đã xử lý', 'value' => 1],
                ['label' => 'Chưa được xử lý', 'value' => 0]
            ]);
        }
        return create_form($feedInfo['status'] ? 'Nhận xét' : 'đối phó với', $field, $this->url('/app/feedback/' . $id), 'PUT');
    }
}
