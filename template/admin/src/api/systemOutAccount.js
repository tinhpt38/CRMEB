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
 * @description danh sách
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function accountListApi(data) {
  return request({
    url: '/setting/system_out_account/index',
    method: 'get',
    params: data,
  });
}

/**
 * @description Trạng thái sửa đổi tài khoản bên ngoài
 * @param {Object} param data {Object} Giá trị vượt qua
 */
export function setShowApi(data) {
  return request({
    url: `setting/system_out_account/set_status/${data.id}/${data.status}`,
    method: 'PUT',
  });
}

/**
 * @description Thêm tài khoản bên ngoài
 * @param {Object} param data {Object} Giá trị vượt qua
 */
export function outSaveApi(data) {
  return request({
    url: `setting/system_out_account/save`,
    method: 'post',
    data,
  });
}

/**
 * @description Sửa đổi tài khoản bên ngoài
 * @param {Object} param id {Number} tài khoảnID
 * @param {Object} param data {Object} Giá trị vượt qua
 */
export function outSavesApi(data) {
  return request({
    url: `setting/system_out_account/update/${data.id}`,
    method: 'post',
    data,
  });
}

/**
 * Đẩy cài đặt tài khoản bên ngoài
 * @param {*} id
 * @returns
 */
export function outSetUp(id) {
  return request({
    url: `setting/system_out_account/set_up/${id}`,
    method: 'get',
  });
}

/**
 * Danh sách giao diện bên ngoài
 */
export function interfaceList() {
  return request({
    url: `setting/system_out_interface/list`,
    method: 'get',
  });
}

/**
 * Thiết lập thông tin đẩy
 * @param {*} data
 * @returns
 */
export function setUpPush(data) {
  return request({
    url: `setting/system_out_account/set_up/${data.id}`,
    method: 'put',
    data,
  });
}
/**
 * Giao diện thêm/chỉnh sửa
 * @param {*} data
 * @returns
 */
export function interfaceSave(data) {
  return request({
    url: `setting/system_out_interface/save/${data.id}`,
    method: 'post',
    data,
  });
}

/**
 * Chi tiết thông tin giao diện
 * @param {*} data
 * @returns
 */
export function interfaceDet(id) {
  return request({
    url: `setting/system_out_interface/info/${id}`,
    method: 'get',
  });
}

/**
 * @description Sửa đổi tên
 * @param {Object} data data {Object} Giá trị vượt qua
 */
export function interfaceEditName(data) {
  return request({
    url: `setting/system_out_interface/edit_name`,
    method: 'PUT',
    data,
  });
}

/**
 * @description xóa bỏ
 */
export function interfaceDel(id) {
  return request({
    url: 'setting/system_out_interface/del/' + id,
    method: 'delete',
  });
}

/**
 * Chi tiết thông tin giao diện
 * @param {*} data
 * @returns
 */
export function textOutUrl(data) {
  return request({
    url: `setting/system_out_account/text_out_url`,
    method: 'post',
    data,
  });
}
