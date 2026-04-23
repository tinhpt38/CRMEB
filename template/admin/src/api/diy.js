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
 * @description Nhận danh mục
 */
export function categoryList() {
  return request({
    url: '/cms/category_list',
    method: 'get',
  });
}

/**
 * @description Khôi phục dữ liệu ban đầu của mẫu
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function recovery(id) {
  return request({
    url: 'diy/recovery/' + id,
    method: 'get',
  });
}

/**
 * @description Đặt dữ liệu ban đầu
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function setDefault(id) {
  return request({
    url: 'diy/set_recovery/' + id,
    method: 'get',
  });
}

/**
 * @description Lưu dữ liệu DIY
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function diySave(id, data) {
  return request({
    url: 'diy/save/' + id,
    method: 'post',
    data: data,
  });
}

/**
 * @description Lưu dữ liệu DIY
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function saveDiy(id, data) {
  return request({
    url: 'diy/diy_save/' + id,
    method: 'post',
    data: data,
  });
}

/**
 * @description Nhận dữ liệu trực quan
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function diyGetInfo(id, data) {
  return request({
    url: 'diy/get_info/' + id,
    method: 'get',
    params: data,
  });
}

/**
 * @description Sử dụng các mẫu tự làm(Hàng hóa sự kiện)
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function getGroomList(type, data) {
  return request({
    url: 'diy/groom_list/' + type,
    method: 'get',
    params: data,
  });
}

/**
 * @description Nhận danh sách sản phẩm
 */
export function getProduct(data) {
  return request({
    url: 'diy/get_product',
    method: 'get',
    params: data,
  });
}

/**
 * @description Nhận các trường thành phần tùy chỉnh
 */
export function getDiyField() {
  return request({
    url: 'diy_pro/text/field',
    method: 'get',
  });
}

/**
 * @description Nhận dữ liệu DIY
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function getDiyInfo(id) {
  return request({
    url: 'diy/get_diy_info/' + id,
    method: 'get',
  });
}

/**
 * @description Lấy danh sách liên kết
 */
export function getUrl() {
  return request({
    url: 'diy/get_url',
    method: 'get',
  });
}

/**
 * @description Nhận danh mục sản phẩm
 */
export function getCategory() {
  return request({
    url: 'diy/get_category',
    method: 'get',
  });
}

/**
 * @description Nhận phân loại sản phẩm cấp một hoặc cấp hai
 */
export function getByCategory(data) {
  return request({
    url: 'diy/get_by_category',
    method: 'get',
    params: data,
  });
}

/**
 * @description DIYDanh sách mẫu
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function diyList(data) {
  return request({
    url: 'diy/get_list',
    method: 'get',
    params: data,
  });
}

/**
 * @description Xóa dữ liệu DIY
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function diyDel(id) {
  return request({
    url: 'diy/del/' + id,
    method: 'delete',
  });
}

/**
 * @description Sử dụng các mẫu tự làm
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function setStatus(id) {
  return request({
    url: 'diy/set_status/' + id,
    method: 'put',
  });
}

/**
 * @description Sử dụng các mẫu tự làm(Xác định xem có hiển thị danh sách các cửa hàng xung quanh không)
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function storeStatus() {
  return request({
    url: 'diy/get_store_status',
    method: 'get',
  });
}

/**
 * @description Thêm mẫu
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function getDiyCreate() {
  return request({
    url: 'diy/create',
    method: 'get',
  });
}

/**
 * @description Đặt dữ liệu mặc định
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function getRecovery(id) {
  return request({
    url: 'diy/set_recovery/' + id,
    method: 'get',
  });
}

/**
 * @description Thêm thủ công,Dữ liệu danh sách cửa sổ bật lên
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function getProductList(params) {
  return request({
    url: 'diy/get_product_list',
    method: 'get',
    params,
  });
}

/**
 * @description Thay đổi màu sắc - thay đổi màu sắc bằng một cú nhấp chuột và gửi phân loại；
 */
export function colorChange(status, name) {
  return request({
    url: `diy/color_change/${status}/${name}`,
    method: 'put',
  });
}

/**
 * @description Thay đổi màu sắc - thông tin phân loại và thay đổi màu sắc chỉ bằng một cú nhấp chuột；
 */
export function getColorChange(name) {
  return request({
    url: `diy/get_color_change/${name}`,
    method: 'get',
  });
}

/**
 * @description Trung tâm cá nhân-Nhận thông tin；
 */
export function getMember() {
  return request({
    url: `diy/get_member`,
    method: 'get',
  });
}

/**
 * @description Chương trình nhỏ - mã QR；
 */
export function getRoutineCode(id) {
  return request({
    url: `diy/get_routine_code/${id}`,
    method: 'get',
  });
}

/**
 * @description Trung tâm cá nhân-Gửi thông tin；
 */
export function memberSave(data) {
  return request({
    url: `diy/member_save`,
    method: 'post',
    data: data,
  });
}

/**
 * @description Danh mục liên kết trang；
 */
export function pageCategory() {
  return request({
    url: `diy/get_page_category`,
    method: 'get',
  });
}

/**
 * @description Liên kết trang - nhận liên kết；
 */
export function pageLink(id) {
  return request({
    url: `diy/get_page_link/${id}`,
    method: 'get',
  });
}

/**
 * @description Liên kết trang-Gửi liên kết tùy chỉnh；
 */
export function saveLink(data, id) {
  return request({
    url: `diy/save_link/${id}`,
    method: 'post',
    data: data,
  });
}

/**
 * @description diyTừ tìm kiếm nóng trên trang；
 */
export function getWordsAll() {
  return request({
    url: `product/words/get_all`,
    method: 'get',
  });
}
/**
 * @description diyXuất mẫu
 */
export function exportDiyDataApi(id) {
  return request({
    url: `diy_pro/export/data/${id}`,
    method: 'get',
  });
}

/**
 * @description Lưu tên DIY
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function diyUpdateName(id, data) {
  return request({
    url: 'diy_pro/update/name/' + id,
    method: 'post',
    data: data,
  });
}

/** 5.6+Cách sử dụng phiên bản */

/**
 * @description Danh sách mẫu DIY
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function diyProList(data) {
  return request({
    url: 'diy_pro/get_list',
    method: 'get',
    params: data,
  });
}

/**
 * @description Nhận dữ liệu trực quan
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function diyProInfo(id, data) {
  return request({
    url: 'diy_pro/get_info/' + id,
    method: 'get',
    params: data,
  });
}

/**
 * @description Lưu dữ liệu DIY
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function diyProSave(id, data) {
  return request({
    url: 'diy_pro/save/' + id,
    method: 'post',
    data: data,
  });
}
/**
 * @description Lưu chủ đề trung tâm mua sắm
 * Kiểu lưu kiểu @param
 * Giá trị @param Giá trị lưới tự làm
 */
export function themeSave(id, data) {
  return request({
    url: 'theme/save/' + id,
    method: 'post',
    data: data,
  });
}
/**
 * @description Nhận chủ đề trung tâm mua sắm
 * @param id id chủ đề
 * Kiểu loại @param
 * @return {Object} Dữ liệu chủ đề
 */
export function themeInfo(id, type) {
  return request({
    url: 'theme/info/' + id + '/' + type,
    method: 'get',
  });
}

/**
 * @description Nhận danh sách bài viết
 */
export function getArticleList(data) {
  return request({
    url: 'theme/article',
    method: 'get',
    params: data,
  });
}

/**
 * @description Nhận danh sách phiếu giảm giá
 */
export function getCouponList(data) {
  return request({
    url: 'theme/coupon',
    method: 'get',
    params: data,
  });
}

/**
 * @description Nhận danh sách sản phẩm
 */
export function getProProduct(data) {
  return request({
    url: 'diy_pro/get_product',
    method: 'get',
    params: data,
  });
}

/**
 * @description Nhận danh sách các sản phẩm theo chủ đề
 */
export function getThemeProduct(data) {
  return request({
    url: 'theme/product',
    method: 'get',
    params: data,
  });
}

/**
 * @description Nhập chủ đề
 * @param data
 */
export function importTheme(data) {
  return request({
    url: 'theme/import',
    method: 'post',
    data,
  });
}
/**
 * @description Lưu tên chủ đề
 * @param id id chủ đề
 * Tên chủ đề dữ liệu @param
 */
export function saveThemeTitle(id, data) {
  return request({
    url: 'theme/save_title/' + id,
    method: 'post',
    data: data,
  });
}

/**
 * @description Lưu bìa chủ đề
 * @param id id chủ đề
 * @param địa chỉ ảnh bìa dữ liệu
 */
export function saveThemeImage(id, data) {
  return request({
    url: 'theme/save_image/' + id,
    method: 'post',
    data: data,
  });
}

/**
 * @description Lấy danh sách chủ đề
 */
export function getThemeList(data) {
  return request({
    url: 'theme/list',
    method: 'get',
    params: data,
  });
}
/**
 * @description Xuất chủ đề
 * Chủ đề id @paramid
 */
export function exportTheme(id) {
  return request({
    url: 'theme/export/' + id,
    method: 'get',
  });
}

/**
 * @description Truy vấn bản ghi xuất chủ đề (để bỏ phiếu)
 * Bản ghi tải xuống @param recordIdid
 */
export function getExportRecord(recordId) {
  return request({
    url: 'theme/export_record/' + recordId,
    method: 'get',
  });
}

/**
 * @description Sử dụng chủ đề
 * Chủ đề id @paramid
 */
export function useTheme(id) {
  return request({
    url: 'theme/use/' + id,
    method: 'get',
  });
}

/**
 * @description Lấy chủ đề hiện đang được sử dụng
 */
export function getThemeUsing() {
  return request({
    url: 'theme/using',
    method: 'get',
  });
}
/**
 * @description Khôi phục chủ đề
 * Chủ đề id @paramid
 */
export function restoreTheme(id) {
  return request({
    url: 'theme/restore/' + id,
    method: 'get',
  });
}

/**
 * @description Sử dụng dữ liệu chủ đề
 * @param id chủ đề hiện tạiid
 * @param data {theme_id, type}
 */
export function useThemeData(id, data) {
  return request({
    url: 'theme/use_data/' + id,
    method: 'get',
    params: data,
  });
}

/**
 * @description Xóa chủ đề
 * Chủ đề id @paramid
 */
export function deleteTheme(id) {
  return request({
    url: 'theme/del/' + id,
    method: 'delete',
  });
}

/**
 * @description Nhận danh sách các micropage
 * @param data
 */
export function getMicroPageList(data) {
  return request({
    url: 'theme/micro_page',
    method: 'get',
    params: data,
  });
}

