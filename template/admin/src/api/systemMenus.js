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
 * @description Quyền--danh sách
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function getTable(data) {
  return request({
    url: '/setting/menus',
    method: 'get',
    params: data,
  });
}
/**
 * @description Quyền--Làm mới menu và quyền
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function getMenusUnique(data) {
  return request({
    url: '/setting/menus/unique',
    method: 'get',
    params: data,
  });
}

/**
 * Quyền -- Thêm
 */
export function addMenus() {
  return request({
    url: '/setting/menus/create',
    method: 'get',
  });
}

/**
 * Quyền -- Chỉnh sửa
 * @param id
 */
export function editMenus(id) {
  return request({
    url: '/setting/menus/' + id + '/edit',
    method: 'get',
  });
}

/**
 * @description Thêm Chỉnh sửa
 * @param {Object} param data {Object} tập trung
 * @param {String} param data.url {String} Địa chỉ
 * @param {String} param data.method {String} Phương thức yêu cầu
 * @param {Object} param data.datas {Object} Tham số truyền theo giá trị
 */
export function addMenusApi(data) {
  return request({
    url: data.url,
    method: data.method,
    data: data.datas,
  });
}

/**
 * @description Chi tiết biểu mẫu
 * @param {Number} param id {Number} luật lệid
 */
export function menusDetailsApi(id) {
  return request({
    url: `/setting/menus/${id}`,
    method: 'get',
  });
}

/**
 * @description Sửa đổi hiển thị
 * @param {Number} param data.id {Number} luật lệid
 * @param {Number} param data.is_show {Number} giá trị trạng thái
 */
export function isShowApi(data) {
  return request({
    url: `/setting/menus/show/${data.id}`,
    method: 'put',
    data,
  });
}

/**
 * @description Danh sách quyền
 */
export function getRuleList(cate_id) {
  return request({
    url: `/setting/ruleList?cate_id=${cate_id}`,
    method: 'get',
  });
}
/**
 * @description Danh sách quyền
 */
export function menusBatch(data) {
  return request({
    url: `setting/menus/batch`,
    method: 'post',
    data,
  });
}

/**
 * @description Danh sách cây phân loại quyền
 */
export function menusRuleCate(data) {
  return request({
    url: `setting/rule_cate`,
    method: 'get',
  });
}
