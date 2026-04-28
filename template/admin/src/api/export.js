// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

import request from '@/libs/request';

/**
 * Xuất danh sách người dùng
 */
export function exportUserList(data) {
  return request({
    url: '/export/user_list',
    method: 'get',
    params: data,
  });
}

/**
 * Xuất danh sách đơn hàng
 */
export function exportOrderList(data) {
  return request({
    url: '/export/order_list',
    method: 'get',
    params: data,
  });
}

/**
 * Xuất danh sách đơn hàng vận chuyển
 */
export function exportOrderDeliveryList(data) {
  return request({
    url: '/export/order_delivery_list',
    method: 'get',
    params: data,
  });
}

/**
 * Xuất danh sách sản phẩm
 */
export function exportProductList(data) {
  return request({
    url: '/export/product_list',
    method: 'get',
    params: data,
  });
}
/**
 * Xuất khẩu di chuyển sản phẩm
 */
export function exportProductExport(data) {
  return request({
    url: '/product/product_export',
    method: 'get',
    params: data,
  });
}
/**
 * Di chuyển và nhập khẩu sản phẩm
 */
export function importProductImport(data) {
  return request({
    url: '/product/product_import',
    method: 'post',
    data,
  });
}

/**
 * Danh sách mặc cả xuất khẩu
 */
export function exportBargainList(data) {
  return request({
    url: '/export/bargain_list',
    method: 'get',
    params: data,
  });
}

/**
 * Xuất danh sách nhóm nhóm
 */
export function exportCombinationList(data) {
  return request({
    url: '/export/combination_list',
    method: 'get',
    params: data,
  });
}

/**
 * Xuất danh sách flash sale
 */
export function exportSeckillList(data) {
  return request({
    url: '/export/seckill_list',
    method: 'get',
    params: data,
  });
}

/**
 * Xuất thẻ thành viên
 */
export function exportmberCardList(id) {
  return request({
    url: `/export/member_card/${id}`,
    method: 'get',
  });
}

/**
 * @description Xuất lệnh xác nhận；
 */
export function exportverifyOrderApi(data) {
  return request({
    url: `export/verify_order`,
    method: 'get',
    params: data,
  });
}
