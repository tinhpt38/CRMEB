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
namespace app\services\product\product;

use app\dao\product\product\StoreProductLabelCateDao;
use app\services\BaseServices;
use crmeb\exceptions\AdminException;
use crmeb\services\FormBuilder as Form;
use think\facade\Route as Url;

class StoreProductLabelCateServices extends BaseServices
{
    /** @var int Tên danh mục tối đa */
    protected const MAX_LABEL_CATE_NAME_LENGTH = 32;

    public function __construct(StoreProductLabelCateDao $dao)
    {
        $this->dao = $dao;
    }

    public function getLabelCateList(array $where = [])
    {
        $list = $this->dao->getLabelCateList($where, 'id,name,sort,add_time');
        foreach ($list as &$item) {
            $item['add_time'] = date('Y-m-d H:i:s', $item['add_time']);
        }
        return $list;
    }

    public function labelCateForm($id = 0)
    {
        $info = $id ? $this->dao->get($id) : [];
        $f[] = Form::input('name', 'Tên danh mục', $info['name'] ?? '')
            ->maxlength(self::MAX_LABEL_CATE_NAME_LENGTH)
            ->placeholder('Vui lòng nhập tên danh mục')
            ->required('Vui lòng nhập tên danh mục');
        $f[] = Form::number('sort', 'Thứ tự', (int)($info['sort'] ?? 0))->min(0)->precision(0);
        return create_form($id ? 'Chỉnh sửa danh mục' : 'Thêm danh mục', $f, Url::buildUrl('/product/label_cate/save/' . $id), 'POST');
    }

    public function labelCateSave($id, $data)
    {
        if (empty($data['name'])) {
            throw new AdminException('Vui lòng nhập tên danh mục');
        }
        if (mb_strlen((string)$data['name']) > self::MAX_LABEL_CATE_NAME_LENGTH) {
            throw new AdminException('Tên danh mục không được vượt quá ' . self::MAX_LABEL_CATE_NAME_LENGTH . ' ký tự');
        }
        if ($id) {
            $this->dao->update($id, $data);
        } else {
            $data['add_time'] = time();
            $this->dao->save($data);
        }
        return true;
    }

    public function labelCateDel($id)
    {
        $count = app()->make(StoreProductLabelServices::class)->getCount(['cate_id' => $id, 'is_del' => 0]);
        if($count) throw new AdminException('Có các thẻ thuộc danh mục này và không thể xóa được');
        $this->dao->update($id, ['is_del' => 1]);
        return true;
    }
}
