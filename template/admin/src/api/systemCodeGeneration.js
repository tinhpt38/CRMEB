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
 * @description Tạo mã - danh sách lựa chọn menu
 */
export function crudMenus() {
  return request({
    url: '/system/crud/menus',
    method: 'get',
  });
}
/**
 * @description Tạo mã - danh sách chọn bảng sql
 */
export function crudColumnType() {
  return request({
    url: '/system/crud/column_type',
    method: 'get',
  });
}
/**
 * @description Tạo mã - gửi bước đầu tiên
 */
export function crudFilePath(data) {
  return request({
    url: '/system/crud/file_path',
    method: 'post',
    data,
  });
}

/**
 * @description Tạo mã - danh sách
 */
export function crudList(data) {
  return request({
    url: '/system/crud',
    method: 'get',
    params: data,
  });
}
/**
 * @description Tạo mã - Tệp xem danh sách
 */
export function crudDet(id) {
  return request({
    url: `/system/crud/${id}`,
    method: 'get',
  });
}

/**
 * @description Tạo mã - Tải xuống
 */
export function crudDownload(id) {
  return request({
    url: `/system/crud/download/${id}`,
    method: 'get',
  });
}
/**
 * @description Danh sách từ điển dữ liệu
 */
export function crudDataDictionary(where) {
  return request({
    url: `/system/crud/data_dictionary`,
    method: 'get',
    params: where,
  });
}
/**
 * @description Lấy tên bảng có thể được liên kết
 */
export function crudAssociationTable() {
  return request({
    url: `/system/crud/association_table`,
    method: 'get',
  });
}
/**
 * @description Nhận chi tiết bảng
 */
export function crudAssociationTableName(tableName) {
  return request({
    url: `/system/crud/association_table/${tableName}`,
    method: 'get',
  });
}
/**
 * @description Xem từ điển dữ liệu
 */
export function crudDataDictionaryList(id) {
  return request({
    url: `/system/crud/data_dictionary/${id}`,
    method: 'get',
  });
}
/**
 * @description Lưu từ điển dữ liệu
 */
export function saveCrudDataDictionaryList(id, data) {
  return request({
    url: `/system/crud/data_dictionary/${id}`,
    method: 'post',
    data,
  });
}
/**
 * @description Tạo mã - chỉnh sửa tập tin
 */
export function crudSaveFile(id, data) {
  return request({
    url: `/system/crud/save_file/${id}`,
    method: 'post',
    data,
  });
}

/**
 * @description Lấy danh sách từ điển dữ liệu
 */
export function getDataDictionaryList(data) {
  return request({
    url: `/system/crud/data_dictionary_list`,
    method: 'get',
    params: data,
  });
}
/**
 * @description Lấy từ điển dữ liệu để thêm và sửa đổi biểu mẫu
 */
export function getDataDictionaryForm(id) {
  return request({
    url: `/system/crud/data_dictionary_list/create/${id}`,
    method: 'get',
  });
}

/**
 * @description Xem danh sách nội dung từ điển dữ liệu
 */
export function getDataDictionaryInfoList(data) {
  return request({
    url: `/system/crud/data_dictionary/info_list/${data.id}`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Xem nội dung từ điển dữ liệu
 */
export function getDataDictionaryInfo(cid, id, pid) {
  return request({
    url: `/system/crud/data_dictionary/info_create/${cid}/${id}/${pid}`,
    method: 'get',
  });
}
