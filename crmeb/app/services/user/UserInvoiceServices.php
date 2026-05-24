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

namespace app\services\user;


use app\dao\user\UserInvoiceDao;
use app\services\BaseServices;
use crmeb\exceptions\ApiException;


/**
 * Class UserInvoiceServices
 * @package app\services\user
 */class UserInvoiceServices extends BaseServices
{
    /**
     * LiveAnchorServices constructor.
     * @param UserInvoiceDao $dao
     */    public function __construct(UserInvoiceDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Phát hiện Cài đặt hệ thống cho các chức năng lập hóa đơn
     * @param bool $is_speclial
     * @return bool|array
     */    public function invoiceFuncStatus(bool $is_speclial = true)
    {
        $invoice = (bool)sys_config('invoice_func_status', 0);
        if ($is_speclial) {
            $specialInvoice = sys_config('special_invoice_status', 0);
            return ['invoice_func' => $invoice, 'special_invoice' => $invoice && $specialInvoice];
        }
        return $invoice;
    }

    /**
     * Nhận thông tin hóa đơn riêng lẻ
     * @param int $id
     * @param int $uid
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getInvoice(int $id, int $uid = 0)
    {
        $invoice = $this->dao->getOne(['id' => $id, 'is_del' => 0]);
        if (!$invoice || ($uid && $invoice['uid'] != $uid)) {
            return [];
        }
        return $invoice->toArray();
    }

    /**
     * Kiểm tra xem có hóa đơn không
     * @param int $id
     * @param int $uid
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function checkInvoice(int $id, int $uid)
    {
        $invoice = $this->getInvoice($id, $uid);
        if (!$invoice) {
            throw new ApiException('Dữ liệu không tồn tại');
        }
        $invoice_func = $this->invoiceFuncStatus();
        if (!$invoice_func['invoice_func']) {
            throw new ApiException('Hóa đơn chưa được mở');
        }
        //Hóa đơn đặc biệt
        if ($invoice['type'] == 2) {
            if (!$invoice_func['special_invoice']) {
                throw new ApiException('Hóa đơn đặc biệt chưa được mở');
            }
        }
        return $invoice;
    }


    /**
     * Nhận danh sách hóa đơn Khách hàng
     * @param int $uid
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getUserList(int $uid, $where)
    {
        [$page, $limit] = $this->getPageValue();
        $where['is_del'] = 0;
        $where['uid'] = $uid;
        return $this->dao->getList($where, '*', $page, $limit);
    }

    /**
     * Nhận hóa đơn mặc định của Khách hàng
     * @param int $uid
     * @param string $field
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function getUserDefaultInvoice(int $uid, int $type, string $field = '*')
    {
        return $this->dao->getOne(['uid' => $uid, 'is_default' => 1, 'is_del' => 0, 'type' => $type], $field);
    }

    /**
     * Thêm mới|Sửa
     * @param int $uid
     * @param array $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function saveInvoice(int $uid, array $data)
    {
        $id = (int)$data['id'];
        $data['uid'] = $uid;
        unset($data['id']);
        $invoice = $this->dao->get(['uid' => $uid, 'name' => $data['name'], 'drawer_phone' => $data['drawer_phone'], 'is_del' => 0]);
        if ($id) {
            if ($invoice && $id != $invoice['id']) {
                throw new ApiException('Hóa đơn đã tồn tại');
            }
            if ($this->dao->update($id, $data, 'id')) {
                if ($data['is_default']) {
                    $this->setDefaultInvoice($uid, $id);
                }
                return ['type' => 'edit', 'msg' => 'Hóa đơn được sửa đổi thành công', 'data' => []];
            } else {
                throw new ApiException('Sửa đổi không thành công');
            }
        } else {
            if ($invoice) {
                throw new ApiException('Hóa đơn đã tồn tại');
            }
            if ($add_invoice = $this->dao->save($data)) {
                $id = (int)$add_invoice['id'];
                if ($data['is_default']) {
                    $this->setDefaultInvoice($uid, $id);
                }
                return ['type' => 'add', 'msg' => 'Đã thêm hóa đơn thành công', 'data' => ['id' => $id]];
            } else {
                throw new ApiException('Thêm không thành công');
            }
        }
    }

    /**
     * Đặt hóa đơn mặc định
     * @param int $id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function setDefaultInvoice(int $uid, int $id)
    {
        if (!$invoice = $this->getInvoice($id)) {
            throw new ApiException('Dữ liệu không tồn tại');
        }
        if ($invoice['uid'] != $uid) {
            throw new ApiException('Hoạt động trái phép');
        }
        if (!$this->dao->setDefault($uid, $id, $invoice['header_type'], $invoice['type'])) {
            throw new ApiException('Đặt hóa đơn mặc định không thành công');
        }
        return true;
    }

    /**
     * Xóa
     * @param $id
     * @throws \Exception
     */    public function delInvoice(int $uid, int $id)
    {
        if ($invoice = $this->getInvoice($id)) {
            if ($invoice['uid'] != $uid) {
                throw new ApiException('Hoạt động trái phép');
            }
            if (!$this->dao->update($id, ['is_del' => 1])) {
                throw new ApiException('Xóa không thành công');
            }
        }
        return true;
    }

}
