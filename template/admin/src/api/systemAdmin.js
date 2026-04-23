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
export function adminListApi(data) {
  return request({
    url: '/setting/admin',
    method: 'get',
    params: data,
  });
}

/**
 * @description Quản trị viên thêm biểu mẫu
 */
export function adminFromApi() {
  return request({
    url: '/setting/admin/create',
    method: 'get',
  });
}

/**
 * @description Quản trị viên chỉnh sửa biểu mẫu
 * @param {Number} param id {Number} quản trị viênid
 */
export function adminEditFromApi(id) {
  return request({
    url: `/setting/admin/${id}/edit`,
    method: 'get',
  });
}

/**
 * @description Quản trị viên xóa
 * @param {Number} param id {Number} quản trị viênid
 */
export function adminDelFromApi(id) {
  return request({
    url: `/setting/admin/${id}`,
    method: 'DELETE',
  });
}

/**
 * @description Quản trị viên sửa đổi trạng thái
 * @param {Object} param data {Object} Giá trị vượt qua
 */
export function setShowApi(data) {
  return request({
    url: `setting/set_status/${data.id}/${data.status}`,
    method: 'PUT',
  });
}
