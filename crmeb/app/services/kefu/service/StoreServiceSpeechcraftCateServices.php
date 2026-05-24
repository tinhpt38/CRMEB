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


use app\dao\other\CategoryDao;
use app\services\other\CategoryServices;
use crmeb\exceptions\AdminException;
use crmeb\services\FormBuilder;

/**
 * Class StoreServiceSpeechcraftCateServices
 * @package app\services\kefu\service
 * @property CategoryDao dao
 */class StoreServiceSpeechcraftCateServices extends CategoryServices
{


    /**
     * Nhận mẫu phân loại
     * @param array $data
     * @return mixed
     */    public function serviceSpeechcraftCateForm(array $data = [])
    {
        $f[] = FormBuilder::input('name', 'Tên danh mục', $data['name'] ?? '')->required();
        $f[] = FormBuilder::number('sort', 'loại', (int)($data['sort'] ?? 0))->min(0);
        return $f;
    }

    /**
     * Nhận biểu mẫu tạo
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     */    public function createForm()
    {
        return create_form('Thêm danh mục', $this->serviceSpeechcraftCateForm(), $this->url('/app/wechat/speechcraftcate'), 'POST');
    }

    /**
     * Nhận biểu mẫu chỉnh sửa
     * @param int $id
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function editForm(int $id)
    {
        $cateInfo = $this->dao->get($id);
        if (!$cateInfo) {
            throw new AdminException('Danh mục không tồn tại');
        }
        return create_form('Sửa danh mục', $this->serviceSpeechcraftCateForm($cateInfo->toArray()), $this->url('/app/wechat/speechcraftcate/' . $id), 'PUT');
    }

}
