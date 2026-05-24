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


use app\dao\service\StoreServiceSpeechcraftDao;
use app\services\BaseServices;
use crmeb\exceptions\AdminException;
use crmeb\services\FormBuilder;
use think\Model;

/**
 * Kỹ năng nói
 * Class StoreServiceSpeechcraftServices
 * @package app\services\kefu\service
 * @method array|Model|null get($id, ?array $field = [], ?array $with = []) Lấy một phần dữ liệu
 * @method update($id, array $data, ?string $key = null) Cập nhật dữ liệu
 */class StoreServiceSpeechcraftServices extends BaseServices
{

    /**
     * StoreServiceSpeechcraftServices constructor.
     * @param StoreServiceSpeechcraftDao $dao
     */    public function __construct(StoreServiceSpeechcraftDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getSpeechcraftList(array $where)
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getSpeechcraftList($where, $page, $limit);
        foreach ($list as &$item) {
            if (!$item['cate_name']) $item['cate_name'] = 'Mặc định hệ thống';
        }
        $count = $this->dao->count($where);
        return compact('list', 'count');
    }

    /**
     * Tạo biểu mẫu
     * @return mixed
     */    public function createForm()
    {
        return create_form('Thêm từ', $this->speechcraftForm(), $this->url('/app/wechat/speechcraft'), 'POST');
    }

    /**
     * @param int $id
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function updateForm(int $id)
    {
        $info = $this->dao->get($id);
        if (!$info) {
            throw new AdminException('Nội dung bài phát biểu bạn sửa không tồn tại');
        }
        return create_form('Kỹ năng chỉnh sửa', $this->speechcraftForm($info->toArray()), $this->url('/app/wechat/speechcraft/' . $id), 'PUT');
    }

    /**
     * @param array $infoData
     * @return mixed
     */    protected function speechcraftForm(array $infoData = [])
    {
        /** @var StoreServiceSpeechcraftCateServices $services */        $services = app()->make(StoreServiceSpeechcraftCateServices::class);
        $cateList = $services->getCateList(['owner_id' => 0, 'type' => 1]);
        $data = [];
        $data[] = ['value' => 0, 'label' => 'Phân loại mặc định'];
        foreach ($cateList['data'] as $item) {
            $data[] = ['value' => $item['id'], 'label' => $item['name']];
        }
        $form[] = FormBuilder::select('cate_id', 'Phân loại kỹ năng nói', $infoData['cate_id'] ?? '')->setOptions($data);
        $form[] = FormBuilder::textarea('title', 'tiêu đề tu từ', $infoData['title'] ?? '')->required();
        $form[] = FormBuilder::textarea('message', 'Nội dung diễn ngôn', $infoData['message'] ?? '')->required();
        $form[] = FormBuilder::number('sort', 'loại', (int)($infoData['sort'] ?? 0));
        return $form;
    }
}
