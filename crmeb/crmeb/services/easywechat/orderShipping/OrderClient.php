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
namespace crmeb\services\easywechat\orderShipping;

use crmeb\exceptions\AdminException;
use crmeb\services\CacheService;
use EasyWeChat\Core\Exceptions\HttpException;


class OrderClient extends BaseOrder
{
    const cache_prefix = 'mini_order';

    const express_company = 'ZTO';   // Công ty chuyển phát nhanh vận chuyển mặc định là (ZTO Express）

    /**
     * @var
     */
    protected $cache;

    /**
     * Xử lý danh bạ
     * @param array $contact
     * @return array
     *
     * @date 2023/05/10
     * @author yyw
     */
    protected function handleContact(array $contact = []): array
    {
        if (isset($contact)) {
            if (isset($contact['consignor_contact']) && $contact['consignor_contact']) {
                $contact['consignor_contact'] = Utility::encryptTel($contact['consignor_contact']);
            }
            if (isset($contact['receiver_contact']) && $contact['receiver_contact']) {
                $contact['receiver_contact'] = Utility::encryptTel($contact['receiver_contact']);
            }
        }
        return $contact;
    }

    /**
     * vận chuyển
     * @param string $out_trade_no
     * @param int $logistics_type
     * @param array $shipping_list
     * @param string $payer_openid
     * @param int $delivery_mode
     * @param bool $is_all_delivered
     * @return array
     * @throws HttpException
     *
     * @date 2023/05/10
     * @author yyw
     */
    public function shippingByTradeNo(string $out_trade_no, int $logistics_type, array $shipping_list, string $payer_openid, $path, int $delivery_mode = 1, bool $is_all_delivered = true)
    {
        if (!$this->checkManaged()) {
            throw new AdminException('Hãy thử lại sau khi kích hoạt dịch vụ quản lý đơn hàng chương trình mini');
        }
        $params = [
            'order_key' => [
                'order_number_type' => 1,
                'mchid' => $this->config['config']['mini_program']['merchant_id'],
                'out_trade_no' => $out_trade_no,
            ],
            'logistics_type' => $logistics_type,
            'delivery_mode' => $delivery_mode,
            'upload_time' => date(DATE_RFC3339),
            'payer' => [
                'openid' => $payer_openid
            ]
        ];
        if ($delivery_mode == 2) {
            $params['is_all_delivered'] = $is_all_delivered;
        }

        if ($logistics_type == 1) {
            foreach ($shipping_list as $shipping) {
                $contact = $this->handleContact($shipping['contact'] ?? []);
                $params['shipping_list'][] = [
                    'tracking_no' => $shipping['tracking_no'] ?? '',
                    'express_company' => $shipping['express_company'],
                    'item_desc' => $shipping['item_desc'],
                    'contact' => $contact
                ];
            }
        } else {
            $params['shipping_list'] = $shipping_list;
        }

        // Đường nhảy
        $this->setMesJumpPath($path);
        return $this->shipping($params);
    }

    /**
     * Truy vấn danh sách đơn hàng
     * @param $params
     * @return array
     * @throws HttpException
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2025/8/14
     */
    public function shippingOrderList($params)
    {
        if (!$this->checkManaged()) {
            throw new AdminException('Hãy thử lại sau khi kích hoạt dịch vụ quản lý đơn hàng chương trình mini');
        }
        return $this->orderList($params);
    }


    /**
     * thứ tự kết hợp
     * @param string $out_trade_no
     * @param int $logistics_type
     * @param array $sub_orders
     * @param string $payer_openid
     * @param int $delivery_mode
     * @param bool $is_all_delivered
     * @return array
     * @throws HttpException
     *
     * @date 2023/05/10
     * @author yyw
     */
    public function combinedShippingByTradeNo(string $out_trade_no, int $logistics_type, array $sub_orders, string $payer_openid, int $delivery_mode = 2, bool $is_all_delivered = false)
    {
        if (!$this->checkManaged()) {
            throw new AdminException('Hãy thử lại sau khi kích hoạt dịch vụ quản lý đơn hàng chương trình mini');
        }
        $params = [
            'order_key' => [
                'order_number_type' => 1,
                'mchid' => $this->config['config']['mini_program']['merchant_id'],
                'out_trade_no' => $out_trade_no,
            ],
            'upload_time' => date(DATE_RFC3339),
            'payer' => [
                'openid' => $payer_openid
            ]
        ];

        foreach ($sub_orders as $order) {
            $sub_order = [
                'order_key' => [
                    'order_number_type' => 1,
                    'mchid' => $this->config['config']['mini_program']['merchant_id'],
                    'out_trade_no' => $order['out_trade_no'],
                    'logistics_type' => $logistics_type,
                ],
                'delivery_mode' => $delivery_mode,
                'is_all_delivered' => $is_all_delivered
            ];
            foreach ($sub_orders['shipping_list'] as $shipping) {
                $contact = $this->handleContact($shipping['contact'] ?? []);
                $sub_order['shipping_list'][] = [
                    'tracking_no' => $shipping['tracking_no'] ?? '',
                    'express_company' => isset($shipping['express_company']) ? $this->getDelivery($shipping['express_company']) : '',
                    'item_desc' => $shipping['item_desc'],
                    'contact' => $contact
                ];
            }
            $params['sub_orders'][] = $sub_order;
        }

        return $this->combinedShipping($params);
    }


    /**
     * Ký nhận thông báo
     * @param string $merchant_trade_no
     * @param string $received_time
     * @return array
     * @throws HttpException
     *
     * @date 2023/05/10
     * @author yyw
     */
    public function notifyConfirmByTradeNo(string $merchant_trade_no, string $received_time)
    {
        $params = [
            'merchant_id' => $this->config['config']['mini_program']['merchant_id'],
            'merchant_trade_no' => $merchant_trade_no,
            'received_time' => $received_time
        ];
        return $this->notifyConfirm($params);
    }

    /**
     * Thiết lập kết nối nhảy
     * @param $path
     * @return array
     * @throws \EasyWeChat\Core\Exceptions\HttpException
     *
     * @date 2023/05/10
     * @author yyw
     */
    public function setMesJumpPathAndCheck($path)
    {
        if (!$this->checkManaged()) {
            throw new AdminException('Hãy thử lại sau khi kích hoạt dịch vụ quản lý đơn hàng chương trình mini');
        }
        return $this->setMesJumpPath($path);
    }

    /**
     * Đặt trạng thái kích hoạt dịch vụ quản lý chương trình mini
     * @return bool
     * @throws HttpException
     *
     * @date 2023/05/09
     * @author yyw
     */
    public function setManaged()
    {
        $res = $this->isManaged();
        if ($res['is_trade_managed']) {
            $key = self::cache_prefix . '_is_trade_managed';
            CacheService::set($key, $res['is_trade_managed']);
            return true;
        } else {
            return false;
        }

    }

    /**
     * @return bool
     * @throws HttpException
     *
     * @date 2023/05/10
     * @author yyw
     */
    public function checkManaged()
    {
        $key = self::cache_prefix . '_is_trade_managed';
        if (CacheService::get($key)) {
            return true;
        } else {
            return $this->setManaged();
        }
    }

    /**
     * Đồng bộ hóa với danh sách hậu cần WeChat
     * @return array
     * @throws HttpException
     *
     * @date 2023/05/10
     * @author yyw
     */
    public function setDeliveryList()
    {
        $list = $this->getDeliveryList();
        if ($list) {
            $key = self::cache_prefix . '_delivery_list';
            $data = array_column($list['delivery_list'], 'delivery_id', 'delivery_name');
            // Tạo bộ đệm
            CacheService::set($key, json_encode($data));

            return $data;
        } else {
            throw new AdminException('Danh sách công ty hậu cần ngoại lệ');
        }
    }

    /**
     * Lấy mã công ty logistic
     * @param $company_name
     * @return array|mixed
     * @throws HttpException
     *
     * @date 2023/05/10
     * @author yyw
     */
    public function getDelivery($company_name)
    {
        $key = self::cache_prefix . '_delivery_list';
        if (!CacheService::get($key)) {
            $date = $this->setDeliveryList();
            $express_company = $date[$company_name] ?? '';
        } else {
            $express_company = json_decode(CacheService::get($key), true)[$company_name] ?? '';
        }
        if (empty($express_company)) {
            $express_company = self::express_company;
        }

        return $express_company;
    }
}
