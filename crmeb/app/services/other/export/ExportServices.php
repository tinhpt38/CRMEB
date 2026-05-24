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

namespace app\services\other\export;

use app\services\activity\bargain\StoreBargainServices;
use app\services\activity\combination\StoreCombinationServices;
use app\services\activity\seckill\StoreSeckillServices;
use app\services\BaseServices;
use app\services\order\StoreOrderServices;
use app\services\product\product\StoreCategoryServices;
use app\services\product\product\StoreDescriptionServices;
use app\services\product\product\StoreProductServices;
use app\services\product\sku\StoreProductAttrResultServices;
use app\services\user\member\MemberCardServices;
use app\services\user\UserServices;
use crmeb\services\SpreadsheetExcelService;

class ExportServices extends BaseServices
{
    /**
     * Xuất file khách hàng
     * @param $where
     * @return array
     */    public function exportUserList($where)
    {
        /** @var UserServices $userServices */        $userServices = app()->make(UserServices::class);
        $data = $userServices->index($where)['list'];
        $header = ['ID khách hàng', 'biệt danh', 'tên thật', 'giới tính', 'Điện thoại', 'Hạng khách hàng', 'Nhóm khách hàng', 'Thẻ khách hàng', 'Loại Khách hàng', 'Số dư Khách hàng', 'Lần đăng nhập cuối cùng', 'Thời gian đăng ký', 'Có nên đăng xuất không'];
        $filename = 'Danh sách Khách hàng_' . date('YmdHis', time());
        $export = $fileKey = [];
        if (!empty($data)) {
            $i = 0;
            foreach ($data as $item) {
                $one_data = [
                    'uid' => $item['uid'],
                    'nickname' => $item['nickname'],
                    'real_name' => $item['real_name'],
                    'sex' => $item['sex'],
                    'phone' => $item['phone'],
                    'level' => $item['level'],
                    'group_id' => $item['group_id'],
                    'labels' => $item['labels'],
                    'user_type' => $item['user_type'],
                    'now_money' => $item['now_money'],
                    'last_time' => date('Y-m-d H:i:s', $item['last_time']),
                    'add_time' => date('Y-m-d H:i:s', $item['add_time']),
                    'is_del' => $item['is_del'] ? 'Đã đăng xuất' : 'Bình thường'
                ];
                $export[] = $one_data;
                if ($i == 0) {
                    $fileKey = array_keys($one_data);
                }
                $i++;
            }
        }
        return compact('header', 'fileKey', 'export', 'filename');
    }

    /**
     * Xuất đơn hàng
     * @param $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function exportOrderList($where)
    {
        $header = ['Số đơn hàng', 'Tên Người nhận hàng', 'Số điện thoại của Người nhận hàng', 'Địa chỉ giao hàng', 'Tên sản phẩm', 'Đặc điểm kỹ thuật', 'Số lượng', 'giá', 'tổng giá', 'Thanh toán thực tế', 'Trạng thái thanh toán', 'Thời gian thanh toán', 'Trạng thái đơn hàng', 'thời gian đặt hàng', 'Nhận xét của Khách hàng', 'Nhận xét của người bán', 'thông tin biểu mẫu'];
        $filename = 'danh sách đặt hàng_' . date('YmdHis', time());
        $export = $fileKey = [];
        /** @var StoreOrderServices $orderServices */        $orderServices = app()->make(StoreOrderServices::class);
        $data = $orderServices->getOrderList($where)['data'];
        if (!empty($data)) {
            $i = 0;
            foreach ($data as $item) {
                if ($item['paid'] == 1) {
                    switch ($item['pay_type']) {
                        case 'weixin':
                            $item['pay_type_name'] = 'Thanh toán WeChat';
                            break;
                        case 'yue':
                            $item['pay_type_name'] = 'Thanh toán bằng số dư';
                            break;
                        case 'offline':
                            $item['pay_type_name'] = 'Thanh toán ngoại tuyến';
                            break;
                        default:
                            $item['pay_type_name'] = 'Các khoản thanh toán khác';
                            break;
                    }
                } else {
                    switch ($item['pay_type']) {
                        default:
                            $item['pay_type_name'] = 'Chưa thanh toán';
                            break;
                        case 'offline':
                            $item['pay_type_name'] = 'Thanh toán ngoại tuyến';
                            break;
                    }
                }
                if ($item['paid'] == 0 && $item['status'] == 0) {
                    $item['status_name'] = 'Chưa thanh toán';
                } else if ($item['paid'] == 1 && $item['status'] == 0 && $item['shipping_type'] == 1 && $item['refund_status'] == 0) {
                    $item['status_name'] = 'Không được vận chuyển';
                } else if ($item['paid'] == 1 && $item['status'] == 0 && $item['shipping_type'] == 2 && $item['refund_status'] == 0) {
                    $item['status_name'] = 'Không được viết tắt';
                } else if ($item['paid'] == 1 && $item['status'] == 1 && $item['shipping_type'] == 1 && $item['refund_status'] == 0) {
                    $item['status_name'] = 'Đang chờ nhận';
                } else if ($item['paid'] == 1 && $item['status'] == 1 && $item['shipping_type'] == 2 && $item['refund_status'] == 0) {
                    $item['status_name'] = 'Không được viết tắt';
                } else if ($item['paid'] == 1 && $item['status'] == 2 && $item['refund_status'] == 0) {
                    $item['status_name'] = 'Đang chờ đánh giá';
                } else if ($item['paid'] == 1 && $item['status'] == 3 && $item['refund_status'] == 0) {
                    $item['status_name'] = 'Hoàn thành';
                } else if ($item['paid'] == 1 && $item['refund_status'] == 1) {
                    $item['status_name'] = 'Hoàn tiền';
                } else if ($item['paid'] == 1 && $item['refund_status'] == 2) {
                    $item['status_name'] = 'Đã hoàn tiền';
                }
                $custom_form = '';
                foreach ($item['custom_form'] as $custom_form_value) {
                    if (is_string($custom_form_value['value'])) {
                        $custom_form .= $custom_form_value['title'] . '：' . $custom_form_value['value'] . '；';
                    } elseif (is_array($custom_form_value['value'])) {
                        $custom_form .= $custom_form_value['title'] . '：' . implode(',', $custom_form_value['value']) . '；';
                    }
                }

//                $goodsName = [];
//                foreach ($item['_info'] as $value) {
//                    $_info = $value['cart_info'];
//                    $sku = '';
//                    if (isset($_info['productInfo']['attrInfo'])) {
//                        if (isset($_info['productInfo']['attrInfo']['suk'])) {
//                            $sku = '(' . $_info['productInfo']['attrInfo']['suk'] . ')';
//                        }
//                    }
//                    if (isset($_info['productInfo']['store_name'])) {
//                        $goodsName[] = implode(' ',
//                            [$_info['productInfo']['store_name'],
//                                $sku,
//                                "[{$_info['cart_num']} * {$_info['truePrice']}]"
//                            ]);
//                    }
//                }
//                $one_data = [
//                    'order_id' => $item['order_id'],
//                    'real_name' => $item['real_name'],
//                    'user_phone' => $item['user_phone'],
//                    'user_address' => $item['user_address'],
//                    'goods_name' => $goodsName ? implode("\n", $goodsName) : '',
//                    'total_price' => $item['total_price'],
//                    'pay_price' => $item['pay_price'],
//                    'pay_type_name' => $item['pay_type_name'],
//                    'pay_time' => $item['pay_time'] > 0 ? date('Y-m-d H:i', (int)$item['pay_time']) : 'Chưa có',
//                    'status_name' => $item['status_name'] ?? 'trạng thái không xác định',
//                    'add_time' => $item['add_time'],
//                    'mark' => $item['mark'],
//                    'remark' => $item['remark'],
//                    'custom_form' => $custom_form,
//                ];
                $goodsInfo = [];
                foreach ($item['_info'] as $value) {
                    $goodsInfo[] = [
                        $value['cart_info']['productInfo']['store_name'],
                        $value['cart_info']['productInfo']['attrInfo']['suk'],
                        $value['cart_info']['cart_num'],
                        $value['cart_info']['truePrice'],
                    ];
                }
                $one_data = [
                    $item['order_id'],
                    $item['real_name'],
                    $item['user_phone'],
                    $item['user_address'],
                    $goodsInfo,
                    $item['total_price'],
                    $item['pay_price'],
                    $item['pay_type_name'],
                    $item['pay_time'] > 0 ? date('Y-m-d H:i', (int)$item['pay_time']) : 'Chưa có',
                    $item['status_name'] ?? 'trạng thái không xác định',
                    $item['add_time'],
                    $item['mark'],
                    $item['remark'],
                    $custom_form,
                ];
                $export[] = $one_data;
                if ($i == 0) {
                    $fileKey = array_keys($one_data);
                }
                $i++;
            }
        }
        return compact('header', 'fileKey', 'export', 'filename');
    }

    /**
     * Xuất đơn hàng
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function exportOrderDeliveryList()
    {
        $header = ['Đơn hàngID', 'Số đơn hàng', 'Tên thể hiện', 'Mã nhanh', 'Số theo dõi nhanh', 'Tên Người nhận hàng', 'Số điện thoại của Người nhận hàng', 'Địa chỉ giao hàng', 'Thông tin sản phẩm', 'Thanh toán thực tế', 'Nhận xét của Khách hàng'];
        $filename = 'Hóa đơn_' . date('YmdHis', time());
        $export = $fileKey = [];
        /** @var StoreOrderServices $orderServices */        $orderServices = app()->make(StoreOrderServices::class);
        $data = $orderServices->getOrderList(['status' => 1, 'shipping_type' => 1, 'virtual_type' => 0, 'pid' => 0])['data'];
        if (!empty($data)) {
            $i = 0;
            foreach ($data as $item) {
                $goodsName = [];
                foreach ($item['_info'] as $value) {
                    $_info = $value['cart_info'];
                    $sku = '';
                    if (isset($_info['productInfo']['attrInfo'])) {
                        if (isset($_info['productInfo']['attrInfo']['suk'])) {
                            $sku = '(' . $_info['productInfo']['attrInfo']['suk'] . ')';
                        }
                    }
                    if (isset($_info['productInfo']['store_name'])) {
                        $goodsName[] = implode(' ',
                            [$_info['productInfo']['store_name'],
                                $sku,
                                "[{$_info['cart_num']} * {$_info['truePrice']}]"
                            ]);
                    }
                }
                $one_data = [
                    'id' => $item['id'],
                    'order_id' => $item['order_id'],
                    'delivery_name' => '',
                    'delivery_code' => '',
                    'delivery_id' => '',
                    'real_name' => $item['real_name'],
                    'user_phone' => $item['user_phone'],
                    'user_address' => $item['user_address'],
                    'goods_name' => $goodsName ? implode("\n", $goodsName) : '',
                    'pay_price' => $item['pay_price'],
                    'mark' => $item['mark'],
                ];
                $export[] = $one_data;
                if ($i == 0) {
                    $fileKey = array_keys($one_data);
                }
                $i++;
            }
        }
        return compact('header', 'fileKey', 'export', 'filename');
    }

    /**
     * Xuất file sản phẩm
     * @param $where
     * @return array
     */    public function exportProductList($where)
    {
        /** @var StoreProductServices $productServices */        $productServices = app()->make(StoreProductServices::class);
        [$page, $limit] = $this->getPageValue();
        $cateIds = [];
        if (isset($where['cate_id']) && $where['cate_id']) {
            /** @var StoreCategoryServices $storeCategory */            $storeCategory = app()->make(StoreCategoryServices::class);
            $cateIds = $storeCategory->getColumn(['pid' => (int)$where['cate_id']], 'id');
        }
        if ($cateIds) {
            $cateIds[] = $where['cate_id'];
            $where['cate_id'] = $cateIds;
        }
        $productList = $productServices->dao->getList($where, $page, $limit);
        $header = [
            'Số mặt hàng',
            'Tên sản phẩm', 'Loại sản phẩm', 'Danh mục sản phẩm(Cấp 1)', 'Danh mục sản phẩm(Cấp 2)', 'Đơn vị sản phẩm',
            'Số lượng bán', 'Số lượng mua tối thiểu',
            'Loại đặc điểm kỹ thuật', 'Tên đặc điểm kỹ thuật', 'giá bán', 'giá chéo', 'giá thành', 'Trong kho', 'cân nặng', 'Dung tích', 'Mã sản phẩm', 'mã vạch',
            'Giới thiệu sản phẩm', 'Từ khóa sản phẩm', 'Mật khẩu sản phẩm',
            'Mua và nhận điểm'
        ];
        $filename = 'Xuất file sản phẩm_' . date('YmdHis', time());
        $virtualType = ['Hàng thông thường', 'Thẻ bí mật/đĩa mạng', 'Mã giảm giá', 'hàng ảo'];
        $export = $fileKey = [];
        if (!empty($productList)) {
            $productList = array_column($productList, null, 'id');
            $productIds = array_column($productList, 'id');
            $descriptionArr = app()->make(StoreDescriptionServices::class)->getColumn([['product_id', 'in', $productIds], ['type', '=', 0]], 'description', 'product_id');
            $cateIds = implode(',', array_column($productList, 'cate_id'));
            /** @var StoreCategoryServices $categoryService */            $categoryService = app()->make(StoreCategoryServices::class);
            $cateList = $categoryService->getCateParentAndChildName($cateIds);
            $attrResultArr = app()->make(StoreProductAttrResultServices::class)->getColumn([['product_id', 'in', $productIds], ['type', '=', 0]], 'result', 'product_id');
            $i = 0;
            foreach ($attrResultArr as $product_id => &$attrResult) {
                $attrResult = json_decode($attrResult, true);
                foreach ($attrResult['value'] as &$value) {
                    $productInfo = $productList[$product_id];
                    $cateName = array_filter($cateList, function ($val) use ($productInfo) {
                        if (in_array($val['id'], explode(',', $productInfo['cate_id']))) {
                            return $val;
                        }
                    });
                    $skuArr = array_combine(array_column($attrResult['attr'], 'value'), $value['detail']);
                    $attrArr = [];
                    foreach ($attrResult['attr'] as $attrArray) {
                        // Chuyển đổi từng mảng con thành 'value' Và 'detail' kết hợp thành chuỗi
                        if (isset($attrArray['detail'][0]['value'])) {
                            $attrArray['detail'] = array_column($attrArray['detail'], 'value');
                        }
                        $detailString = implode(',', $attrArray['detail']); // Chuyển đổi mảng chi tiết thành chuỗi được phân tách bằng dấu phẩy
                        $attrArr[] = $attrArray['value'] . '=' . $detailString;
                    }
                    $attrString = implode(';', $attrArr);
                    if (reset($cateName)['one'] == null) {
                        $cate_name_one = reset($cateName)['two'] ?? '';
                        $cate_name_two = '';
                    } else {
                        $cate_name_one = reset($cateName)['one'] ?? '';
                        $cate_name_two = reset($cateName)['two'] ?? '';
                    }
                    $one_data = [
                        'id' => intval($product_id),
                        'store_name' => $productInfo['store_name'],
                        'virtual_type' => $virtualType[$productInfo['virtual_type']],
                        'cate_name_one' => $cate_name_one,
                        'cate_name_two' => $cate_name_two,
                        'unit_name' => $productInfo['unit_name'],
                        'ficti' => intval($productInfo['ficti']),
                        'min_qty' => intval($productInfo['min_qty']),
                        'spec_type' => intval($productInfo['spec_type']) == 1 ? 'Nhiều thông số kỹ thuật' : 'Đặc điểm kỹ thuật đơn',
                        'sku_name' => implode(',', $value['detail']),
                        'price' => floatval($value['price']),
                        'ot_price' => floatval($value['ot_price']),
                        'cost' => floatval($value['cost']),
                        'stock' => intval($value['stock']),
                        'weight' => intval($value['weight'] ?? 0),
                        'volume' => intval($value['volume'] ?? 0),
                        'bar_code' => $value['bar_code'] ?? '',
                        'bar_code_number' => $value['bar_code_number'] ?? '',
                        'store_info' => $productInfo['store_info'],
                        'keyword' => $productInfo['keyword'],
                        'command_word' => $productInfo['command_word'],
                        'give_integral' => $productInfo['give_integral'],
                    ];
                    $export[] = $one_data;
                    if ($i == 0) {
                        $fileKey = array_keys($one_data);
                    }
                    $i++;
                }
            }
        }
        return compact('header', 'fileKey', 'export', 'filename');
    }

    /**
     * Xuất file Sản phẩm trả giá
     * @param $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function exportBargainList($where)
    {
        $header = ['tên thương lượng', 'giá khởi điểm', 'giá thấp nhất', 'Số lượng người tham gia', 'số lượng thành công', 'hàng còn lại', 'trạng thái hoạt động', 'Thời gian hoạt động', 'Thêm thời gian'];
        $filename = 'Lịch sử trả giá_' . date('YmdHis', time());
        $export = $fileKey = [];
        /** @var StoreBargainServices $bargainServices */        $bargainServices = app()->make(StoreBargainServices::class);
        $data = $bargainServices->getStoreBargainList($where)['list'];
        if (!empty($data)) {
            $i = 0;
            foreach ($data as $item) {
                $one_data = [
                    'title' => $item['title'],
                    'price' => $item['price'],
                    'min_price' => $item['min_price'],
                    'count_people_all' => $item['count_people_all'],
                    'count_people_success' => $item['count_people_success'],
                    'quota' => $item['quota'],
                    'start_name' => $item['start_name'],
                    'activity_time' => $item['start_time'] . 'ĐẾN' . $item['stop_time'],
                    'add_time' => $item['add_time']
                ];
                $export[] = $one_data;
                if ($i == 0) {
                    $fileKey = array_keys($one_data);
                }
                $i++;
            }
        }
        return compact('header', 'fileKey', 'export', 'filename');
    }

    /**
     * Sản phẩm mua chung xuất khẩu
     * @param $where
     * @return array
     */    public function exportCombinationList($where)
    {
        $header = ['Tên nhóm', 'Giá nhóm', 'giá gốc', 'Số người trong nhóm', 'Số lượng người tham gia', 'Số lượng nhóm', 'hàng còn lại', 'trạng thái hoạt động', 'Thời gian hoạt động', 'Thêm thời gian'];
        $filename = 'Đơn hàng mua chung_' . date('YmdHis', time());
        $export = $fileKey = [];
        /** @var StoreCombinationServices $combinationServices */        $combinationServices = app()->make(StoreCombinationServices::class);
        $data = $combinationServices->systemPage($where)['list'];
        if (!empty($data)) {
            $i = 0;
            foreach ($data as $item) {
                $one_data = [
                    'title' => $item['title'],
                    'price' => $item['price'],
                    'ot_price' => $item['ot_price'],
                    'count_people' => $item['count_people'],
                    'count_people_all' => $item['count_people_all'],
                    'count_people_pink' => $item['count_people_pink'],
                    'quota' => $item['quota'],
                    'start_name' => $item['start_name'],
                    'activity_time' => $item['start_time'] . 'ĐẾN' . $item['stop_time'],
                    'add_time' => $item['add_time']
                ];
                $export[] = $one_data;
                if ($i == 0) {
                    $fileKey = array_keys($one_data);
                }
                $i++;
            }
        }
        return compact('header', 'fileKey', 'export', 'filename');
    }

    /**
     * Xuất flash sale
     * @param $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function exportSeckillList($where)
    {
        $header = ['tên bán flash', 'giá bán chớp nhoáng', 'giá gốc', 'hàng còn lại', 'trạng thái hoạt động', 'Thời gian hoạt động', 'Thêm thời gian'];
        $filename = 'Sản phẩm Flash Sale_' . date('YmdHis', time());
        $export = $fileKey = [];
        /** @var StoreSeckillServices $seckillServices */        $seckillServices = app()->make(StoreSeckillServices::class);
        $data = $seckillServices->systemPage($where)['list'];
        if (!empty($data)) {
            $i = 0;
            foreach ($data as $item) {
                $one_data = [
                    'title' => $item['title'],
                    'price' => $item['price'],
                    'ot_price' => $item['ot_price'],
                    'quota' => $item['quota'],
                    'start_name' => $item['start_name'],
                    'activity_time' => $item['start_time'] . 'ĐẾN' . $item['stop_time'],
                    'add_time' => $item['add_time']
                ];
                $export[] = $one_data;
                if ($i == 0) {
                    $fileKey = array_keys($one_data);
                }
                $i++;
            }
        }
        return compact('header', 'fileKey', 'export', 'filename');
    }

    /**
     * Xuất thẻ thành viên
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */    public function exportMemberCard($id)
    {
        /** @var MemberCardServices $memberCardServices */        $memberCardServices = app()->make(MemberCardServices::class);
        $data = $memberCardServices->getExportData(['batch_card_id' => $id]);
        $header = ['Số thẻ thành viên', 'mật khẩu', 'Người nhận', 'Số điện thoại di động của người nhận', 'Thời gian thu thập', 'Có nên sử dụng không'];
        $filename = $data['title'] . 'danh sách lô_' . date('YmdHis', time());
        $export = $fileKey = [];
        if (!empty($data['data'])) {
            $userIds = array_column($data['data']->toArray(), 'use_uid');
            /** @var  UserServices $userService */            $userService = app()->make(UserServices::class);
            $userList = $userService->getColumn([['uid', 'in', $userIds]], 'nickname,phone,real_name', 'uid');


            $i = 0;
            foreach ($data['data'] as $item) {
                $one_data = [
                    'card_number' => $item['card_number'],
                    'card_password' => $item['card_password'],
                    'user_name' => $userList[$item['use_uid']]['real_name'] ?? $userList[$item['use_uid']]['nickname'] ?? '',
                    'user_phone' => $userList[$item['use_uid']]['phone'] ?? "",
                    'use_time' => $item['use_time'],
                    'use_uid' => $item['use_uid'] ? 'Đã nhận' : 'Không được thu thập'
                ];
                $export[] = $one_data;
                if ($i == 0) {
                    $fileKey = array_keys($one_data);
                }
                $i++;
            }
        }
        return compact('header', 'fileKey', 'export', 'filename');
    }

    /**
     * Xuất yêu cầu thực
     * @param $header exceltiêu đề
     * @param $title tiêu đề
     * @param array $export Điền dữ liệu
     * @param string $filename Lưu tên tập tin
     * @param string $suffix Lưu hậu tố tập tin
     * @param bool $is_save true|false Có nên lưu vào địa phương hay không
     * @return mixed
     */    public function export($header, $title_arr, $export = [], $filename = '', $suffix = 'xlsx', $is_save = false)
    {
        $title = isset($title_arr[0]) && !empty($title_arr[0]) ? $title_arr[0] : 'Xuất dữ liệu';
        $name = isset($title_arr[1]) && !empty($title_arr[1]) ? $title_arr[1] : 'Xuất dữ liệu';
        $info = isset($title_arr[2]) && !empty($title_arr[2]) ? $title_arr[2] : date('Y-m-d H:i:s', time());

        $path = SpreadsheetExcelService::instance()->setExcelHeader($header)
            ->setExcelTile($title, $name, $info)
            ->setExcelContent($export)
            ->excelSave($filename, $suffix, $is_save);
        $path = $this->siteUrl() . $path;
        return [$path];
    }

    /**
     * Lấy tên miền giao diện hệ thống
     * @return string
     */    public function siteUrl()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $domainName = $_SERVER['HTTP_HOST'];
        return $protocol . $domainName;
    }


    /**
     * Xuất tiền của Khách hàng
     * @param $data Xuất dữ liệu
     */    public function userFinance($data = [])
    {
        $export = [];
        if (!empty($data)) {
            foreach ($data as $value) {
                $export[] = [
                    $value['uid'],
                    $value['nickname'],
                    $value['pm'] == 0 ? '-' . $value['number'] : $value['number'],
                    $value['title'],
                    $value['mark'],
                    $value['add_time'],
                ];
            }
        }
        $header = ['thành viênID', 'biệt danh', 'Số tiền/Điểm', 'kiểu', 'Nhận xét', 'Thời gian tạo'];
        $title = ['Giám sát quỹ', 'Giám sát quỹ', date('Y-m-d H:i:s', time())];
        $filename = 'Giám sát quỹ_' . date('YmdHis', time());
        $suffix = 'xlsx';
        $is_save = true;
        return $this->export($header, $title, $export, $filename, $suffix, $is_save);
    }

    /**
     * Xuất hoa hồng Khách hàng
     * @param $data Xuất dữ liệu
     */    public function userCommission($data = [])
    {
        $export = [];
        if (!empty($data)) {
            foreach ($data as &$value) {
                $export[] = [
                    $value['nickname'],
                    $value['sum_number'],
                    $value['now_money'],
                    $value['brokerage_price'],
                    $value['extract_price'],
                ];
            }
        }
        $header = ['Biệt hiệu/Tên', 'Tổng số tiền hoa hồng', 'Số dư tài khoản', 'Hoa hồng tài khoản', 'Hoa hồng rút tiền'];
        $title = ['Kỷ lục vàng', 'Kỷ lục vàng' . time(), ' Thời gian thế hệ：' . date('Y-m-d H:i:s', time())];
        $filename = 'Kỷ lục vàng_' . date('YmdHis', time());
        $suffix = 'xlsx';
        $is_save = true;
        return $this->export($header, $title, $export, $filename, $suffix, $is_save);
    }

    /**
     * Xuất điểm Khách hàng
     * @param $data Xuất dữ liệu
     */    public function userPoint($data = [])
    {
        $export = [];
        if (!empty($data)) {
            foreach ($data as $key => $item) {
                $export[] = [
                    $item['id'],
                    $item['title'],
                    $item['balance'],
                    $item['number'],
                    $item['mark'],
                    $item['nickname'],
                    $item['add_time'],
                ];
            }
        }
        $header = ['số seri', 'tiêu đề', 'Điểm trước khi thay đổi', 'Thay đổi điểm', 'Nhận xét', 'Biệt hiệu WeChat của Khách hàng', 'Thêm thời gian'];
        $title = ['Nhật ký điểm', 'Nhật ký điểm' . time(), 'Thời gian thế hệ：' . date('Y-m-d H:i:s', time())];
        $filename = 'Nhật ký điểm_' . date('YmdHis', time());
        $suffix = 'xlsx';
        $is_save = true;
        return $this->export($header, $title, $export, $filename, $suffix, $is_save);
    }

    /**
     * Xuất nạp tiền Khách hàng
     * @param $data Xuất dữ liệu
     */    public function userRecharge($data = [])
    {
        $export = [];
        if (!empty($data)) {
            foreach ($data as $item) {
                $item['_pay_time'] = $item['pay_time'] ? date('Y-m-d H:i:s', $item['pay_time']) : 'Chưa có';
                $item['_add_time'] = $item['add_time'] ? date('Y-m-d H:i:s', $item['add_time']) : 'Chưa có';
                $item['paid_type'] = $item['paid'] ? 'trả' : 'Chưa thanh toán';

                $export[] = [
                    $item['nickname'],
                    $item['order_id'],
                    $item['price'],
                    $item['paid_type'],
                    $item['_recharge_type'],
                    $item['_pay_time'],
                    $item['paid'] == 1 && $item['refund_price'] == $item['price'] ? 'Đã hoàn tiền' : 'Không hoàn lại tiền'
                ];
            }
        }
        $header = ['Biệt hiệu/Tên', 'Số đơn hàng', 'Số tiền nạp', 'Có nên trả tiền không', 'Loại nạp tiền', 'Thời gian thanh toán', 'Có hoàn lại tiền không'];
        $title = ['Lịch sử nạp tiền', 'Lịch sử nạp tiền' . time(), ' Thời gian thế hệ：' . date('Y-m-d H:i:s', time())];
        $filename = 'Lịch sử nạp tiền_' . date('YmdHis', time());
        $suffix = 'xlsx';
        $is_save = true;
        return $this->export($header, $title, $export, $filename, $suffix, $is_save);
    }

    /**
     * Xuất khuyến mãi của Khách hàng
     * @param $data Xuất dữ liệu
     */    public function userAgent($data = [])
    {
        $export = [];
        if (!empty($data)) {
            foreach ($data as $index => $item) {
                $export[] = [
                    $item['uid'],
                    $item['nickname'],
                    $item['phone'],
                    $item['spread_count'],
                    $item['spread_order']['order_count'],
                    $item['spread_order']['order_price'],
                    $item['brokerage_money'],
                    $item['extract_count_price'],
                    $item['extract_count_num'],
                    $item['brokerage_price'],
                    $item['spread_name'],
                ];
            }
        }
        $header = ['ID Khách hàng', 'biệt danh', 'số điện thoại', 'Số lượng Khách hàng được thăng cấp', 'Số lượng đặt hàng khuyến mãi', 'Số lượng đặt hàng khuyến mãi', 'số tiền hoa hồng', 'Số tiền đã rút', 'Số lần rút tiền', 'Số tiền mặt chưa rút', 'Nhà quảng bá cấp cao'];
        $title = ['Quảng bá Khách hàng', 'Thúc đẩy xuất khẩu Khách hàng' . time(), ' Thời gian thế hệ：' . date('Y-m-d H:i:s', time())];
        $filename = 'Quảng bá Khách hàng_' . date('YmdHis', time());
        $suffix = 'xlsx';
        $is_save = true;
        return $this->export($header, $title, $export, $filename, $suffix, $is_save);
    }

    /**
     * Xuất file khách hàng WeChat
     * @param $data Xuất dữ liệu
     */    public function wechatUser($data = [])
    {
        $export = [];
        if (!empty($data)) {
            foreach ($data as $index => $item) {
                $export[] = [
                    $item['nickname'],
                    $item['sex'],
                    $item['country'] . $item['province'] . $item['city'],
                    $item['subscribe'] == 1 ? 'tập trung vào' : 'Không theo dõi',
                ];
            }
        }
        $header = ['tên', 'giới tính', 'khu vực', 'Có nên theo dõi tài khoản công khai hay không'];
        $title = ['Xuất file khách hàng WeChat', 'Xuất file khách hàng WeChat' . time(), ' Thời gian thế hệ：' . date('Y-m-d H:i:s', time())];
        $filename = 'Xuất file khách hàng WeChat_' . date('YmdHis', time());
        $suffix = 'xlsx';
        $is_save = true;
        return $this->export($header, $title, $export, $filename, $suffix, $is_save);
    }

    /**
     * Quỹ đặt hàng xuất khẩu
     * @param $data Xuất dữ liệu
     */    public function orderFinance($data = [])
    {
        $export = [];
        if (!empty($data)) {
            foreach ($data as $info) {
                $time = $info['pay_time'];
                $price = $info['total_price'] + $info['pay_postage'];
                $zhichu = $info['coupon_price'] + $info['deduction_price'] + $info['cost'];
                $profit = ($info['total_price'] + $info['pay_postage']) - ($info['coupon_price'] + $info['deduction_price'] + $info['cost']);
                $deduction = $info['deduction_price'];//Trừ điểm
                $coupon = $info['coupon_price'];//giảm giá
                $cost = $info['cost'];//trị giá
                $export[] = [$time, $price, $zhichu, $cost, $coupon, $deduction, $profit];
            }
        }
        $header = ['thời gian', 'doanh thu(Nhân dân tệ)', 'chi tiêu(Nhân dân tệ)', 'trị giá', 'giảm giá', 'Trừ điểm', 'lợi nhuận(Nhân dân tệ)'];
        $title = ['thống kê Tài chính', 'thống kê Tài chính', date('Y-m-d H:i:s', time())];
        $filename = 'thống kê Tài chính_' . date('YmdHis', time());
        $suffix = 'xlsx';
        $is_save = true;
        return $this->export($header, $title, $export, $filename, $suffix, $is_save);
    }

    /**
     * Hoạt động mặc cả của cửa hàng xuất khẩu
     * @param $data Xuất dữ liệu
     */    public function storeBargain($data = [])
    {
        $export = [];
        if (!empty($data)) {
            foreach ($data as $index => $item) {
                $export[] = [
                    $item['title'],
                    $item['info'],
                    format_vnd($item['price']),
                    $item['bargain_num'],
                    $item['status'] ? 'Hoạt động' : 'đóng cửa',
                    empty($item['start_time']) ? '' : date('Y-m-d H:i:s', (int)$item['start_time']),
                    empty($item['stop_time']) ? '' : date('Y-m-d H:i:s', (int)$item['stop_time']),
                    $item['sales'],
                    $item['quota'],
                    empty($item['add_time']) ? '' : $item['add_time'],
                ];
            }
        }
        $header = ['Tên hoạt động mặc cả', 'Giới thiệu về hoạt động thương lượng', 'Số tiền mặc cả', 'Số lần Khách hàng mặc cả mỗi lần', 'Tình trạng thương lượng', 'Thời gian mở cửa giao dịch', 'Thời gian kết thúc thương lượng', 'Doanh số bán hàng', 'phiên bản giới hạn', 'Thêm thời gian'];
        $title = ['Xuất file Sản phẩm trả giá', 'Thông tin sản phẩm' . time(), ' Thời gian thế hệ：' . date('Y-m-d H:i:s', time())];
        $filename = 'Xuất file Sản phẩm trả giá_' . date('YmdHis', time());
        $suffix = 'xlsx';
        $is_save = true;
        return $this->export($header, $title, $export, $filename, $suffix, $is_save);
    }

    /**
     * Xuất nhóm cửa hàng
     * @param $data Xuất dữ liệu
     */    public function storeCombination($data = [])
    {
        $export = [];
        if (!empty($data)) {
            foreach ($data as $item) {
                $export[] = [
                    $item['id'],
                    $item['title'],
                    $item['ot_price'],
                    $item['price'],
                    $item['quota'],
                    $item['count_people'],
                    $item['count_people_all'],
                    $item['count_people_pink'],
                    $item['sales'] ?? 0,
                    $item['is_show'] ? 'Hoạt động' : 'đóng cửa',
                    empty($item['stop_time']) ? '' : date('Y/m/d H:i:s', (int)$item['stop_time'])
                ];
            }
        }
        $header = ['số seri', 'Tên nhóm', 'giá gốc', 'Giá nhóm', 'phiên bản giới hạn', 'Số người trong nhóm', 'Số lượng người tham gia', 'Số lượng nhóm', 'Doanh số bán hàng', 'Tình trạng sản phẩm', 'thời gian kết thúc'];
        $title = ['Sản phẩm mua chung xuất khẩu', 'Thông tin sản phẩm' . time(), ' Thời gian thế hệ：' . date('Y-m-d H:i:s', time())];
        $filename = 'Sản phẩm mua chung xuất khẩu_' . date('YmdHis', time());
        $suffix = 'xlsx';
        $is_save = true;
        return $this->export($header, $title, $export, $filename, $suffix, $is_save);
    }

    /**
     * Xuất hoạt động flash sale của cửa hàng
     * @param $data Xuất dữ liệu
     */    public function storeSeckill($data = [])
    {
        $export = [];
        if (!empty($data)) {
            foreach ($data as $item) {
                if ($item['status']) {
                    if ($item['start_time'] > time())
                        $item['start_name'] = 'Sự kiện chưa bắt đầu';
                    else if ($item['stop_time'] < time())
                        $item['start_name'] = 'Sự kiện đã kết thúc';
                    else if ($item['stop_time'] > time() && $item['start_time'] < time())
                        $item['start_name'] = 'đang tiến hành';
                } else {
                    $item['start_name'] = 'Sự kiện đã kết thúc';
                }
                $export[] = [
                    $item['id'],
                    $item['title'],
                    $item['info'],
                    $item['ot_price'],
                    $item['price'],
                    $item['quota'],
                    $item['sales'],
                    $item['start_name'],
                    $item['stop_time'] ? date('Y-m-d H:i:s', $item['stop_time']) : '/',
                    $item['status'] ? 'Hoạt động' : 'đóng cửa',
                ];
            }
        }
        $header = ['số seri', 'Tiêu đề sự kiện', 'Giới thiệu hoạt động', 'giá gốc', 'giá bán chớp nhoáng', 'phiên bản giới hạn', 'Doanh số bán hàng', 'Trạng thái bán hàng chớp nhoáng', 'thời gian kết thúc', 'Trạng thái'];
        $title = ['Xuất file sản phẩm flash sale', ' ', ' Thời gian thế hệ：' . date('Y-m-d H:i:s', time())];
        $filename = 'Xuất file sản phẩm flash sale_' . date('YmdHis', time());
        $suffix = 'xlsx';
        $is_save = true;
        return $this->export($header, $title, $export, $filename, $suffix, $is_save);
    }

    /**
     * Cửa hàng xuất sản phẩm
     * @param $data Xuất dữ liệu
     */    public function storeProduct($data = [])
    {
        $export = [];
        if (!empty($data)) {
            foreach ($data as $index => $item) {
                $export[] = [
                    $item['store_name'],
                    $item['store_info'],
                    $item['cate_name'],
                    format_vnd($item['price']),
                    $item['stock'],
                    $item['sales'],
                    $item['visitor'],
                ];
            }
        }
        $header = ['Tên sản phẩm', 'Giới thiệu sản phẩm', 'Danh mục sản phẩm', 'giá', 'Trong kho', 'Doanh số bán hàng', 'Lượt xem'];
        $title = ['Xuất file sản phẩm', 'Thông tin sản phẩm' . time(), ' Thời gian thế hệ：' . date('Y-m-d H:i:s', time())];
        $filename = 'Xuất file sản phẩm_' . date('YmdHis', time());
        $suffix = 'xlsx';
        $is_save = true;
        return $this->export($header, $title, $export, $filename, $suffix, $is_save);
    }


    /**
     * Cửa hàng tự xuất điểm lấy hàng
     * @param $data Xuất dữ liệu
     */    public function storeMerchant($data = [])
    {
        $export = [];
        if (!empty($data)) {
            foreach ($data as $index => $item) {
                $export[] = [
                    $item['name'],
                    $item['phone'],
                    $item['address'] . '' . $item['detailed_address'],
                    $item['day_time'],
                    $item['is_show'] ? 'Hoạt động' : 'đóng cửa'
                ];
            }
        }
        $header = ['Tên điểm đón', 'Điểm đón', 'Địa chỉ', 'Giờ làm việc', 'Trạng thái'];
        $title = ['Xuất điểm đón', 'Thông tin điểm đón' . time(), ' Thời gian thế hệ：' . date('Y-m-d H:i:s', time())];
        $filename = 'Xuất điểm đón_' . date('YmdHis', time());
        $suffix = 'xlsx';
        $is_save = true;
        return $this->export($header, $title, $export, $filename, $suffix, $is_save);
    }

    public function memberCard($data = [])
    {
        $export = [];
        if (!empty($data)) {
            foreach ($data['data'] as $index => $item) {
                $export[] = [
                    $item['card_number'],
                    $item['card_password'],
                    $item['user_name'],
                    $item['user_phone'],
                    $item['use_time'],
                    $item['use_uid'] ? 'Đã nhận' : 'Không được thu thập'
                ];
            }
        }
        $header = ['Số thẻ thành viên', 'mật khẩu', 'Người nhận', 'Số điện thoại di động của người nhận', 'Thời gian thu thập', 'Có nên sử dụng không'];
        $title = ['Xuất thẻ thành viên', 'Xuất thẻ thành viên' . time(), ' Thời gian thế hệ：' . date('Y-m-d H:i:s', time())];
        $filename = $data['title'] ? ("Thành viên thẻ_" . trim(str_replace(["\r\n", "\r", "\\", "\n", "/", "<", ">", "=", " "], '', $data['title']))) : "";
        $suffix = 'xlsx';
        $is_save = true;
        return $this->export($header, $title, $export, $filename, $suffix, $is_save);
    }

    public function tradeData($data = [], $tradeTitle = "Thống kê giao dịch")
    {
        $export = $header = [];
        if (!empty($data)) {
            $header = ['thời gian'];
            $headerArray = array_column($data['series'], 'name');
            $header = array_merge($header, $headerArray);
            $export = [];
            foreach ($data['series'] as $index => $item) {
                foreach ($data['x'] as $k => $v) {
                    $export[$v]['time'] = $v;
                    $export[$v][] = $item['value'][$k];
                }
            }
        }
        $title = [$tradeTitle, $tradeTitle, ' Thời gian thế hệ：' . date('Y-m-d H:i:s', time())];
        $filename = $tradeTitle;
        $suffix = 'xlsx';
        $is_save = true;
        return $this->export($header, $title, $export, $filename, $suffix, $is_save);
    }


    /**
     * Thống kê sản phẩm
     * @param $data Xuất dữ liệu
     */    public function productTrade($data = [])
    {
        $export = [];
        if (!empty($data)) {
            foreach ($data as &$value) {
                $export[] = [
                    $value['time'],
                    $value['browse'],
                    $value['user'],
                    $value['cart'],
                    $value['order'],
                    $value['payNum'],
                    $value['pay'],
                    $value['cost'],
                    $value['refund'],
                    $value['refundNum'],
                    $value['changes'] . '%'
                ];
            }
        }
        $header = ['ngày/giờ', 'Lượt xem sản phẩm', 'Số lượng khách truy cập sản phẩm', 'Số lượng mặt hàng bổ sung được mua', 'Số lượng đặt hàng', 'Số lượng đã thanh toán', 'Số tiền thanh toán', 'số tiền chi phí', 'Số tiền hoàn lại', 'Số lần hoàn tiền', 'Tỷ lệ chuyển đổi từ khách truy cập sang thanh toán'];
        $title = ['Thống kê sản phẩm', 'Thống kê sản phẩm' . time(), ' Thời gian thế hệ：' . date('Y-m-d H:i:s', time())];
        $filename = 'Thống kê sản phẩm_' . date('YmdHis', time());
        $suffix = 'xlsx';
        $is_save = true;
        return $this->export($header, $title, $export, $filename, $suffix, $is_save);
    }

    public function userTrade($data = [])
    {
        $export = [];
        if (!empty($data)) {
            foreach ($data as &$value) {
                $export[] = [
                    $value['time'],
                    $value['user'],
                    $value['browse'],
                    $value['new'],
                    $value['paid'],
                    $value['vip'],
                ];
            }
        }
        $header = ['ngày/giờ', 'Số lượng khách truy cập', 'Lượt xem', 'Số lượng Khách hàng mới', 'Số lượng Khách hàng đã thực hiện giao dịch', 'Số lượng thành viên trả phí'];
        $title = ['Thống kê Khách hàng', 'Thống kê Khách hàng' . time(), ' Thời gian thế hệ：' . date('Y-m-d H:i:s', time())];
        $filename = 'Thống kê Khách hàng_' . date('YmdHis', time());
        $suffix = 'xlsx';
        $is_save = true;
        return $this->export($header, $title, $export, $filename, $suffix, $is_save);
    }

    /**
     * Xuất hồ sơ xác nhận
     * @param array $data
     * @return mixed|string[]
     * @author wuhaotian
     * @email 442384644@qq.com
     * @date 2025/9/9
     */    public function verifyOrder($data = [])
    {
        $export = [];
        if (!empty($data)) {
            foreach ($data as $item) {
                $productName = '';
                foreach ($item['_info'] as $productInfo) {
                    $productName .= $productInfo['cart_info']['productInfo']['store_name'] . ' ';
                }
                $export[] = [
                    $item['order_id'],
                    $item['real_name'] . '/' . $item['uid'],
                    $productName,
                    $item['pay_price'],
                    $item['clerk_name'],
                    $item['store_name'],
                    $item['pay_type_name'],
                    $item['status_name']['status_name'],
                    $item['add_time'],
                ];
            }
        }
        $header = ['Số đơn hàng', 'Thông tin Khách hàng', 'Thông tin sản phẩm', 'Số tiền thanh toán', 'người bảo lãnh', 'Cửa hàng xác nhận', 'Trạng thái thanh toán', 'Trạng thái đơn hàng', 'thời gian đặt hàng'];
        $title = ['Xuất hồ sơ xác nhận', 'Xuất hồ sơ xác nhận' . time(), ' Thời gian thế hệ：' . date('Y-m-d H:i:s', time())];
        $filename = 'Hồ sơ xác nhận_' . date('YmdHis', time());
        $suffix = 'xlsx';
        $is_save = true;
        return $this->export($header, $title, $export, $filename, $suffix, $is_save);
    }
}
