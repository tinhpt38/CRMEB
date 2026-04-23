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
 * @param {Number} param id {Number} Dữ liệu kết hợpid
 */
export function membershipDataListApi(data) {
  return request({
    url: 'agent/level',
    // url: `setting/group_data`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Danh sách dữ liệu tổng hợp - chỉnh sửa biểu mẫu
 * @param {Number} param id {Number} Danh sách dữ liệu kết hợpid
 * @param {Object} param data {Object} Đối tượng id dữ liệu kết hợp
 */
export function membershipDataEditApi(data, url) {
  return request({
    url: url,
    method: 'get',
    params: data,
  });
}

/**
 * @description Danh sách dữ liệu kết hợp - biểu mẫu mới
 * @param {Number} param id {Number} Dữ liệu kết hợpid
 */
export function membershipDataAddApi(id, url) {
  return request({
    url: url,
    // url: `setting/group_data/create`,
    method: 'get',
    params: id,
  });
}
/**
 * @description Cấu hình nhiệm vụ phân phối
 * @param {Number} param id {Number} Dữ liệu kết hợpid
 */
export function getTaskNumFormApi(id) {
  return request({
    url: `agent/get_task_num_form/${id}`,
    method: 'get',
  });
}
/**
 * @description Danh sách dữ liệu kết hợp - sửa đổi trạng thái
 * @param {Object} param data {Object} Giá trị truyền danh sách dữ liệu kết hợp
 */
export function membershipSetApi(url) {
  return request({
    url: url,
    // url: `/setting/group_data/set_status/${data.id}/${data.status}`,
    method: 'PUT',
  });
}

/**
 * @description Danh sách dữ liệu kết hợp - sửa đổi trạng thái
 * @param {Object} param data {Object} Giá trị truyền danh sách dữ liệu kết hợp
 */
export function levelTaskSetApi(url) {
  return request({
    url: url,
    // url: `/setting/group_data/set_status/${data.id}/${data.status}`,
    method: 'PUT',
  });
}

/**
 * @description Danh sách nhiệm vụ cấp độ
 * @param {Number} param id {Number} Dữ liệu kết hợpid
 */
export function levelTaskListDataAddApi(data) {
  return request({
    url: 'agent/level_task',
    // url: `setting/group_data`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Danh sách dữ liệu tổng hợp - chỉnh sửa biểu mẫu
 * @param {Number} param id {Number} Danh sách dữ liệu kết hợpid
 * @param {Object} param data {Object} Đối tượng id dữ liệu kết hợp
 */
export function levelTaskDataEditApi(data, url) {
  return request({
    url: url,
    method: 'get',
    params: data,
  });
}

/**
 * @description Danh sách dữ liệu kết hợp - biểu mẫu mới
 * @param {Number} param id {Number} Dữ liệu kết hợpid
 */
export function levelTaskDataAddApi(id, url) {
  return request({
    url: url,
    // url: `setting/group_data/create`,
    method: 'get',
    params: id,
  });
}
