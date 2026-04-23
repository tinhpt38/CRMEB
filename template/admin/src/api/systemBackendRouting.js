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
 * Đồng bộ hóa quyền định tuyến
 */
export function syncRoute(appName) {
  return request({
    url: `system/route/sync_route/${appName}`,
    method: 'get',
  });
}
/**
 * Thêm danh mục định tuyến
 */
export function routeCate(appName) {
  return request({
    url: `system/route_cate/create?app_name=${appName}`,
    method: 'get',
  });
}
/**
 * cây định tuyến
 */
export function routeList(apiType) {
  return request({
    url: `system/route/tree?app_name=${apiType}`,
    method: 'get',
  });
}

/**
 * Giao diện thêm/chỉnh sửa
 * @param {*} data
 * @returns
 */
export function routeSave(data) {
  return request({
    url: `system/route/${data.id}`,
    method: 'post',
    data,
  });
}

/**
 * Chi tiết thông tin giao diện
 * @param {*} data
 * @returns
 */
export function routeDet(id) {
  return request({
    url: `system/route/${id}`,
    method: 'get',
  });
}
/**
 * Trình chỉnh sửa phân loại giao diện
 * @param {*} data
 * @returns
 */
export function routeEdit(id, appName) {
  return request({
    url: `system/route_cate/${id}/edit?app_name=${appName}`,
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
export function routeDel(id) {
  return request({
    url: 'system/route/' + id,
    method: 'delete',
  });
}
/**
 * @description xóa bỏ
 */
export function routeCateDel(id) {
  return request({
    url: 'system/route_cate/' + id,
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
