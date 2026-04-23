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
 * @description Phân loại tệp đính kèm--danh sách
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function getCategoryListApi(data) {
  return request({
    url: 'file/category',
    method: 'get',
    params: data,
  });
}

/**
 * @description Thêm danh mục
 */
export function createApi(id) {
  return request({
    url: 'file/category/create',
    method: 'get',
    params: id,
  });
}

/**
 * @description Chỉnh sửa danh mục
 * @param {Number} param id {Number} Phân loạiid
 */
export function categoryEditApi(id) {
  return request({
    url: `file/category/${id}/edit`,
    method: 'get',
  });
}

/**
 * @description Xóa danh mục
 * @param {Number} param id {Number} Phân loạiid
 */
export function categoryDelApi(id) {
  return request({
    url: `file/category/${id}`,
    method: 'DELETE',
  });
}

/**
 * @description Danh sách đính kèm
 * @param {Object} param data {Object} Giá trị vượt qua
 */
export function fileListApi(data) {
  return request({
    url: 'file/file',
    method: 'get',
    params: data,
  });
}

/**
 * @description Di chuyển danh mục và sửa đổi biểu mẫu danh mục đính kèm
 * @param {Object} param data {Object} Giá trị vượt qua
 */
export function moveApi(data) {
  return request({
    url: 'file/file/do_move',
    method: 'put',
    data,
  });
}

/**
 * @description Sửa đổi tên tệp đính kèm
 * @param {String} param ids {String} Một chuỗi được nối thành id hình ảnh
 */
export function fileUpdateApi(ids, data) {
  return request({
    url: 'file/file/update/' + ids,
    method: 'put',
    data,
  });
}

/**
 * @description Xóa tệp đính kèm
 * @param {String} param ids {String} Một chuỗi được nối thành id hình ảnh
 */
export function fileDelApi(ids) {
  return request({
    url: 'file/file/delete',
    method: 'post',
    data: ids,
  });
}
/**
 * @description Tải hình ảnh lên Internet
 */
export function onlineUpload(data) {
  return request({
    url: 'file/online_upload',
    method: 'post',
    data,
  });
}

/**
 * @description Xóa tải lên mã quét code
 */
export function scanUploadCode() {
  return request({
    url: 'file/scan_upload/qrcode ',
    method: 'delete',
  });
}

/**
 * @description Quản lý tài liệu-tải lên video
 */
export function videoCloudUpload(data) {
  return request({
    url: 'file/video_data_save',
    method: 'post',
    data,
  });
}