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
 * @description Quản lý bài viết--danh sách
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function cmsListApi(data) {
  return request({
    url: 'cms/cms',
    method: 'get',
    params: data,
  });
}

/**
 * @description Quản lý bài viết--Thêm người chỉnh sửa
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function cmsAddApi(data) {
  return request({
    url: 'cms/cms',
    method: 'post',
    data,
  });
}

/**
 * @description Quản lý bài viết--Chi tiết bài viết
 * @param {Number} param id {Number} bài báoid
 */
export function createApi(id) {
  return request({
    url: `cms/cms/${id}`,
    method: 'get',
  });
}

/**
 * @description Danh mục bài viết--Thêm biểu mẫu
 */
export function categoryAddApi() {
  return request({
    url: `cms/category/create`,
    method: 'GET',
  });
}

/**
 * @description Phân loại bài viết--danh sách
 * @param {Object} param params {Object} Giá trị vượt qua
 */
export function categoryListApi(params) {
  return request({
    url: `cms/category`,
    method: 'GET',
    params,
  });
}
/**
 * @description Phân loại bài viết--danh sách phiên bản mới
 * @param {Object} param params {Object} Giá trị vượt qua
 */
export function categoryTreeListApi() {
  return request({
    url: `cms/category_tree_list`,
    method: 'GET',
  });
}

/**
 * @description Danh mục bài viết--Chỉnh sửa biểu mẫu
 * @param {Number} param id {Number} bài báoid
 */
export function categoryEditApi(id) {
  return request({
    url: `cms/category/${id}/edit`,
    method: 'GET',
  });
}

/**
 * @description Danh mục bài viết--Trạng thái sửa đổi
 * @param {Object} param data {Object} Giá trị vượt qua
 */
export function statusApi(data) {
  return request({
    url: `cms/category/set_status/${data.id}/${data.status}`,
    method: 'put',
  });
}

/**
 * @description Danh mục bài viết--Sản phẩm liên quan
 * @param {Number} param id {Number} bài báoid
 * @param {Object} param data {Object} Giá trị vượt qua
 */
export function relationApi(data, id) {
  return request({
    url: `cms/cms/relation/${id}`,
    method: 'put',
    data,
  });
}
