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
 * @description Phân phối -- Danh sách
 * @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function agentListApi(params) {
  return request({
    url: 'agent/index',
    method: 'get',
    params,
  });
}

/**
 * @description Sửa đổi người dùng cao cấp
 * @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function agentSpreadApi(data) {
  return request({
    url: 'agent/spread',
    method: 'PUT',
    data,
  });
}

/**
 * @description Phân phối -- Tiêu đề
 * @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function statisticsApi(params) {
  return request({
    url: 'agent/statistics',
    method: 'get',
    params,
  });
}

/**
 * @description Phân phối -- Nhà quảng bá,danh sách đặt hàng
 * @param {Object} param params {Object} Tham số truyền theo giá trị
 * @param {String} param url {String} Địa chỉ yêu cầu
 */
export function stairListApi(url, params) {
  return request({
    url: url,
    method: 'get',
    params,
  });
}

/**
 * @description Phân phối - mã QR khuyến mãi tài khoản công khai
 * @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function lookCodeApi(params) {
  return request({
    url: 'agent/look_code',
    method: 'get',
    params,
  });
}

/**
 * @description Phân phối - chương trình mini mã QR khuyến mãi
 * @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function lookxcxCodeApi(params) {
  return request({
    url: 'agent/look_xcx_code',
    method: 'get',
    params,
  });
}

/**
 * @description Phân phối - mã QR khuyến mãi h5
 * @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function lookh5CodeApi(params) {
  return request({
    url: 'agent/look_h5_code',
    method: 'get',
    params,
  });
}

/**
 * @description Phân phối - Xuất danh sách khuyến mãi của người dùng
 */
export function userAgentApi(data) {
  return request({
    url: `export/userAgent`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Đơn vị kinh doanh--Danh sách
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function regionList(data) {
  return request({
    url: 'agent/division/list',
    method: 'get',
    params: data,
  });
}

/**
 * @description Đơn đăng ký đại lý--Danh sách
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function divisionList(data) {
  return request({
    url: 'agent/division/agent_apply/list',
    method: 'get',
    params: data,
  });
}
/**
 * @description Thống kê đơn vị kinh doanh--danh sách
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function divisionStatistics(data) {
  return request({
    url: 'agent/division/statistics',
    method: 'get',
    params: data,
  });
}

/**
 * @description Thêm đại lý--Biểu mẫu
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function agentFrom(uid) {
  return request({
    url: `agent/division/agent/create/${uid}`,
    method: 'get',
  });
}

/**
 * @description Đánh giá đại lý
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function divisionFrom(id, type) {
  return request({
    url: `agent/division/examine_apply/${id}/${type}`,
    method: 'get',
  });
}

/**
 * @description Thêm bộ phận kinh doanh--mẫu
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function regionFrom(uid) {
  return request({
    url: `agent/division/create/${uid}`,
    method: 'get',
  });
}
/**
 * @description Danh sách đơn vị kinh doanh
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function clerkList(data) {
  return request({
    url: `agent/division/down_list`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Chuyển đổi trạng thái đơn vị kinh doanh--danh sách
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function isShowApi(data) {
  return request({
    url: `agent/division/set_status/${data.status}/${data.id}`,
    method: 'put',
  });
}

/**
 * @description Biểu mẫu bổ sung nhân viên
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function staffAddFrom(uid) {
  return request({
    url: `agent/division/staff/create/${uid}`,
    method: 'get',
  });
}

/**
 * @description Danh sách ứng dụng--Nhà phân phối
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function spreadList(data) {
  return request({
    url: 'agent/spread/apply/list',
    method: 'get',
    params: data,
  });
}

/**
 * @description Đánh giá--Nhà phân phối
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function spreadFrom(id, uid, type, data) {
  return request({
    url: `agent/spread/apply/examine/${id}/${uid}/${type}`,
    method: 'post',
    data,
  });
}
