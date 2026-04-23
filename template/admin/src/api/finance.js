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
 * @description Giám sát quỹ -- Loại bộ lọc
 */
export function billTypeApi() {
  return request({
    url: 'finance/finance/bill_type',
    method: 'get',
  });
}

/**
 * @description Giám sát quỹ -- Danh sách
 * @param {Object} param data {Object} Giá trị vượt qua
 */
export function billListApi(data) {
  return request({
    url: 'finance/finance/list',
    method: 'get',
    params: data,
  });
}

/**
 * @description Hồ sơ Ủy ban -- Danh sách
 * @param {Object} param data {Object} Giá trị vượt qua
 */
export function commissionListApi(data) {
  return request({
    url: 'finance/finance/commission_list',
    method: 'get',
    params: data,
  });
}

/**
 * @description Hồ sơ Ủy ban -- Chi tiết
 * @param {Number} param id {Number} hồ sơ ủy banID
 */
export function commissionDetailApi(id) {
  return request({
    url: `finance/finance/user_info/${id}`,
    method: 'get',
  });
}

/**
 * @description Hồ sơ hoa hồng - danh sách rút tiền cá nhân
 * @param {Number} param id {Number} Người dùng hồ sơ hoa hồngID
 */
export function extractlistApi(id, data) {
  return request({
    url: `finance/finance/extract_list/${id}`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Đơn xin rút tiền - danh sách
 * @param {Object} param data {Object} Giá trị chuyển khoản của đơn rút tiền
 */
export function cashListApi(data) {
  return request({
    url: `finance/extract`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Đơn xin rút tiền - chỉnh sửa mẫu đơn
 * @param {Number} param id {Number} Đơn xin rút tiềnid
 */
export function cashEditApi(id) {
  return request({
    url: `finance/extract/${id}/edit`,
    method: 'get',
  });
}

/**
 * @description Đơn xin rút tiền - đơn bị từ chối
 * @param {Number} param id {Number} Đơn xin rút tiềnid
 */
export function refuseApi(id, data) {
  return request({
    url: `finance/extract/refuse/${id}`,
    method: 'put',
    data,
  });
}

/**
 * @description Đơn xin rút tiền -- Đăng ký thông qua
 * @param {Number} param id {Number} Đơn xin rút tiềnid
 */
export function adoptApi(id, data) {
  return request({
    url: `finance/extract/adopt/${id}`,
    method: 'put',
    data,
  });
}

/**
 * @description Hồ sơ nạp tiền - danh sách
 * @param {Object} param data {Object} Chuyển hồ sơ nạp tiền
 */
export function rechargelistApi(data) {
  return request({
    url: `finance/recharge`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Bản ghi nạp tiền - dữ liệu nạp tiền của người dùng
 * @param {Object} param data {Object} Truyền dữ liệu nạp lại người dùng
 */
export function userRechargeApi(data) {
  return request({
    url: `finance/recharge/user_recharge`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Hồ sơ nạp tiền - hình thức hoàn tiền
 * @param {Number} param data {Number} Kỷ lục nạp tiềnid
 */
export function refundEditApi(id) {
  return request({
    url: `finance/recharge/${id}/refund_edit`,
    method: 'get',
  });
}

/**
 * @description Hồ sơ tài chính -- Xuất quỹ người dùng
 * @param {Number} param data {Number} Thông số yêu cầudata
 */
export function userFinanceApi(data) {
  return request({
    url: `export/userFinance`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Hồ sơ hoa hồng - xuất hoa hồng người dùng
 * @param {Number} param data {Number} Thông số yêu cầudata
 */
export function userCommissionApi(data) {
  return request({
    url: `export/userCommission`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Hồ sơ nạp tiền của người dùng - Xuất hồ sơ nạp tiền của người dùng
 * @param {Number} param data {Number} Thông số yêu cầudata
 */
export function exportUserRechargeApi(data) {
  return request({
    url: `export/userRecharge`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Quản lý tài chính -- Thống kê dòng vốn
 * @param {Number} param data {Number} Thông số yêu cầudata
 */
export function getFlowList(data) {
  return request({
    url: `statistic/flow/get_list`,
    method: 'get',
    params: data,
  });
}
/**
 * @description Dòng vốn -- Nhận xét
 * @param {Number} param id {Number} Đơn xin rút tiềnid
 */
export function setMarks(id, data) {
  return request({
    url: `statistic/flow/set_mark/${id}`,
    method: 'post',
    data,
  });
}
/**
 * @description Quản Lý Tài Chính -- Danh Sách Cân Bằng
 * @param {Number} param data {Number} Thông số yêu cầudata
 */
export function getBalanceList(data) {
  return request({
    url: `finance/balance/list`,
    method: 'get',
    params: data,
  });
}
/**
 * @description Danh sách số dư--Ghi chú
 * @param {Number} balanceMark id {Number} Đơn xin rút tiềnid
 */
export function setBalanceMark(id, data) {
  return request({
    url: `finance/balance/set_mark/${id}`,
    method: 'post',
    data,
  });
}
