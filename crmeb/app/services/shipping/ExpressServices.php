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

namespace app\services\shipping;


use app\dao\shipping\ExpressDao;
use app\services\BaseServices;
use app\services\serve\ServeServices;
use crmeb\exceptions\AdminException;
use crmeb\services\CacheService;
use crmeb\services\express\Express;
use crmeb\services\FormBuilder as Form;

/**
 * Dữ liệu hậu cần
 * Class ExpressServices
 * @package app\services\shipping
 * @method save(array $data) lưu dữ liệu
 * @method get(int $id, ?array $field = []) Nhận dữ liệu
 * @method delete(int $id, ?string $key = null) Xóa dữ liệu
 * @method update($id, array $data, ?string $key = null) Sửa đổi dữ liệu
 */
class ExpressServices extends BaseServices
{
    public $_cacheKey = "plat_express_list";

    //Truy vấn hậu cần công ty hậu cầncode
    public $express_code = [
        'yunda' => 'yunda',
        'yundakuaiyun' => 'yunda56',
        'ems' => 'EMS',
        'youzhengguonei' => 'chinapost',
        'huitongkuaidi' => 'HTKY',
        'baishiwuliu' => 'BSKY',
        'shentong' => 'STO',
        'jd' => 'JD',
        'zhongtong' => 'ZTO',
        'zhongtongkuaiyun' => 'ZTO56',
    ];

    /**
     * Người xây dựng
     * ExpressServices constructor.
     * @param ExpressDao $dao
     */
    public function __construct(ExpressDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Nhận thông tin hậu cần
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getExpressList(array $where)
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->getExpressList($where, '*', $page, $limit);
        $count = $this->dao->count($where);
        return compact('list', 'count');
    }

    public function apiExpressList()
    {
        return $this->dao->getExpressList([], '*', 0, 0);
    }

    /**
     * Hình thức hậu cần
     * @param array $formData
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function createExpressForm(array $formData = [])
    {
        if (isset($formData['partner_id']) && $formData['partner_id'] == 1) $field[] = Form::input('account', 'tài khoản hàng tháng', $formData['account'] ?? '');
        if (isset($formData['partner_key']) && $formData['partner_key'] == 1) $field[] = Form::input('key', 'Mật khẩu quyết toán hàng tháng', $formData['key'] ?? '');
        if (isset($formData['net']) && $formData['net'] == 1) $field[] = Form::input('net_name', 'Điểm đón', $formData['net_name'] ?? '')->required();
        if (isset($formData['check_man']) && $formData['check_man'] == 1) $field[] = Form::input('courier_name', 'Tên hãng vận chuyển chuyển phát nhanh', $formData['courier_name'] ?? '')->required();
        if (isset($formData['partner_name']) && $formData['partner_name'] == 1) $field[] = Form::input('customer_name', 'Tên tài khoản khách hàng', $formData['customer_name'] ?? '')->required();
        if (isset($formData['is_code']) && $formData['is_code'] == 1) $field[] = Form::input('code_name', 'Số mang biểu mẫu điện tử', $formData['code_name'] ?? '')->required();
        $field[] = Form::number('sort', 'loại', (int)($formData['sort'] ?? 0))->precision(0);
        $field[] = Form::radio('is_show', 'Có bật hay không', $formData['is_show'] ?? 1)->options([['value' => 0, 'label' => 'trốn'], ['value' => 1, 'label' => 'cho phép']]);
        return $field;
    }

    /**
     * Tạo một biểu mẫu thông tin hậu cần để có được
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function createForm()
    {
        return create_form('Thêm công ty hậu cần', $this->createExpressForm(), $this->url('/freight/express'));
    }

    /**
     * Sửa đổi việc thu thập mẫu thông tin hậu cần
     * @param int $id
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function updateForm(int $id)
    {
        $express = $this->dao->get($id);
        if (!$express) {
            throw new AdminException('Dữ liệu không tồn tại');
        }
        return create_form('Chỉnh sửa Công ty Logistics', $this->createExpressForm($express->toArray()), $this->url('/freight/express/' . $id), 'PUT');
    }

    /**
     * Nền tảng để nhận chuyển phát nhanh
     * @return array|mixed
     */
    public function getPlatExpress()
    {
        /** @var ServeServices $expressService */
        $expressService = app()->make(ServeServices::class);
        /** @var CacheService $cacheService */
        $cacheService = app()->make(CacheService::class);
        $data = [];
        if ($list = $cacheService::get($this->_cacheKey)) {
            $data = json_decode($list, true);
        } else {
            $list = $expressService->express()->express(0, 0, 1000);
            if (isset($list['data'])) {
                $cacheService->set($this->_cacheKey, json_encode($list['data']), 3600);
                $data = $list['data'];
            }
        }
        return $data;
    }

    /**
     * Lấy thông tin hậu cần và kết hợp nó thành một mảng mới để trả về
     * @param array $where
     * @return array
     */
    public function express(array $where = [], string $k = 'id')
    {
        $list = $this->expressList($where);
        $data = [];
        if ($list) {
            foreach ($list as $k => $v) {
                $data[$k]['id'] = $v['id'];
                $data[$k]['value'] = $v['name'];
                $data[$k]['code'] = $v['code'];
            }
        }
        return $data;
    }

    /**
     * Lấy thông tin hậu cần và kết hợp nó thành một mảng mới để trả về
     * @param array $where
     * @return array
     */
    public function expressSelectForm(array $where = [])
    {
        $list = $this->expressList();
        //$list = $this->dao->getExpress($where, 'name', 'id');
        $data = [];
        foreach ($list as $key => $value) {
            $data[] = ['label' => $value['name'], 'value' => $value['code']];
        }
        return $data;
    }

    public function expressList($where = [])
    {
        if (empty($where)) $where = ['is_show' => 1];
        return $this->dao->getExpressList($where, 'id,name,code,partner_id,partner_key,net,account,key,net_name', 0, 0);
    }

    /**
     * Điều tra công ty hậu cần
     * @param string $cacheName
     * @param string $expressNum
     * @param string|null $com
     * @param string $phone
     * @return array
     */
    public function query(string $cacheName, string $expressNum, string $com = null, $phone = '')
    {
        $resultData = CacheService::get($cacheName, null);
        if (!is_array($resultData) || empty($resultData)) {
            $data = [];
            $cacheTime = 0;
            switch ((int)sys_config('logistics_type')) {
                case 1:
                    /** @var ServeServices $services */
                    $services = app()->make(ServeServices::class);
                    $result = $services->express()->query($expressNum, $com, $phone);
                    if (isset($result['ischeck']) && $result['ischeck'] == 1) {
                        $cacheTime = 0;
                    } else {
                        $cacheTime = 1800;
                    }
                    foreach ($result['content'] ?? [] as $item) {
                        $data[] = ['time' => $item['time'], 'status' => $item['status']];
                    }
                    break;
                case 2:
                    /** @var Express $services */
                    $services = app()->make(Express::class, ['aliyun_express']);
                    $result = $services->query($expressNum, '', sys_config('system_express_app_code'));
                    if (is_array($result) &&
                        isset($result['result']) &&
                        isset($result['result']['deliverystatus']) &&
                        $result['result']['deliverystatus'] >= 3)
                        $cacheTime = 0;
                    else
                        $cacheTime = 1800;
                    $data = $result['result']['list'] ?? [];
                    break;
            }
            CacheService::set($cacheName, $data, $cacheTime);
            return $data;
        }

        return $resultData;
    }

    /**
     * Công ty Logistics đồng bộ
     * @return bool
     */
    public function syncExpress()
    {
        $expressList = $this->getPlatExpress();
        $data = $data_all = [];
        $this->dao->delete([['id', '>', 0]]);
        foreach ($expressList as $express) {
            $data['name'] = $express['name'] ?? '';
            $data['code'] = $express['code'] ?? '';
            $data['partner_id'] = $express['partner_id'] ?? '';
            $data['partner_key'] = $express['partner_key'] ?? '';
            $data['check_man'] = $express['check_man'] ?? '';
            $data['partner_name'] = $express['partner_name'] ?? '';
            $data['is_code'] = $express['is_code'] ?? '';
            $data['net'] = $express['net'] ?? '';
            $data['is_show'] = 0;
            $data['status'] = 0;
            if ($express['partner_id'] == 0 && $express['partner_key'] == 0 && $express['net'] == 0 && $express['check_man'] == 0 && $express['partner_name'] == 0 && $express['is_code'] == 0) {
                $data['status'] = 1;
            }
            $data_all[] = $data;
        }
        if ($data_all) {
            $this->dao->saveAll($data_all);
        }
        return true;
    }

    /** Truy vấn một công ty chuyển phát nhanh
     * @param array $where
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getOneByWhere(array $where)
    {
        return $this->dao->getOne($where);
    }


}
