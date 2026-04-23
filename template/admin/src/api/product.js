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

/*
 * Lấy số lượng tiêu đề sản phẩm；
 * */
export function getGoodHeade(data) {
  return request({
    url: 'product/product/type_header',
    method: 'get',
    params: data,
  });
}

/*
 * Lấy số lượng tiêu đề sản phẩm；
 * */
export function getGoodsCategory(data) {
  return request({
    url: '/goods/goods_category',
    method: 'get',
    params: data,
  });
}

/**
 * @description Quản lý sản phẩm--danh sách
 */
export function getGoods(params) {
  return request({
    url: 'product/product',
    method: 'get',
    params,
  });
}

/**
 * @description Quản lý sản phẩm--lưu trữ tạm thời
 */
export function productCache() {
  return request({
    url: 'product/cache',
    method: 'get',
  });
}

/**
 * @description Quản lý sản phẩm--Hủy lưu tạm thời
 */
export function cacheDelete() {
  return request({
    url: 'product/cache',
    method: 'delete',
  });
}

/**
 * @description Quản lý sản phẩm--trên và ngoài kệ
 */
export function PostgoodsIsShow(id, isShow) {
  return request({
    url: `product/product/set_show/${id}/${isShow}`,
    method: 'put',
  });
}

/**
 * @description Thuộc tính sản phẩm - tải và dỡ hàng loạt
 * @param {Object} param data {Object} Đối tượng truyền theo giá trị
 */
export function productShowApi(data) {
  return request({
    url: `product/product/product_show`,
    method: 'put',
    data,
  });
}

/**
 * Thêm bình luận ảo
 * @param {*} data
 * @returns
 */
export function saveFictitiousReply(data) {
  return request({
    url: 'product/reply/save_fictitious_reply',
    method: 'post',
    data,
  });
}

/**
 * @description Thuộc tính sản phẩm -- loại bỏ hàng loạt
 * @param {Object} param data {Object} Đối tượng truyền theo giá trị
 */
export function productUnshowApi(data) {
  return request({
    url: `product/product/product_unshow`,
    method: 'put',
    data,
  });
}

/**
 * @description Quản lý sản phẩm--Phân loại
 */
export function treeListApi(type) {
  return request({
    url: `product/category/tree/${type}`,
    method: 'get',
  });
}

/**
 * @description Quản lý sản phẩm--Phân loại new
 */
export function cascaderListApi(type) {
  return request({
    url: `product/category/cascader/${type}`,
    method: 'get',
  });
}

/**
 * @description Quản lý sản phẩm--Chi tiết
 */
export function productInfoApi(id) {
  return request({
    url: `product/product/${id}`,
    method: 'get',
  });
}

/**
 * @description Quản lý sản phẩm--Gửi
 */
export function productAddApi(data) {
  return request({
    url: `product/product/${data.id}`,
    method: 'POST',
    data,
  });
}

/**
 * @description Danh mục sản phẩm -- Danh sách
 * @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function productListApi(params) {
  return request({
    url: 'product/category',
    method: 'get',
    params,
  });
}

/**
 * @description Danh mục sản phẩm -- Thêm biểu mẫu
 * @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function productCreateApi() {
  return request({
    url: 'product/category/create',
    method: 'get',
  });
}

/**
 * @description Danh mục sản phẩm -- Chỉnh sửa biểu mẫu
 * @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function productEditApi(id) {
  return request({
    url: `product/category/${id}`,
    method: 'get',
  });
}

/**
 * @description Danh mục sản phẩm -- Sửa đổi trạng thái
 * @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function setShowApi(data) {
  return request({
    url: `product/category/set_show/${data.id}/${data.is_show}`,
    method: 'PUT',
  });
}

/**
 * @description Chọn sản phẩm - danh sách
 */
export function changeListApi(params) {
  return request({
    url: `product/product/list`,
    method: 'GET',
    params,
  });
}

/**
 * @description Đánh giá sản phẩm -- Danh sách
 * @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function replyListApi(params) {
  return request({
    url: `product/reply`,
    method: 'get',
    params,
  });
}

/**
 * @description Đánh giá sản phẩm -- Trả lời
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function setReplyApi(data, id) {
  return request({
    url: `product/reply/set_reply/${id}`,
    method: 'PUT',
    data,
  });
}

/**
 * @description Nhận cấu hình sản phẩm sao chép
 */
export function copyConfigApi() {
  return request({
    url: `product/copy_config`,
    method: 'get',
  });
}

/**
 * @description Quản lý sản phẩm - lấy dữ liệu sản phẩm từ JD.com và Taobao
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function crawlFromApi(data) {
  return request({
    url: `product/copy`,
    method: 'POST',
    data,
  });
}

/**
 * @description Quản lý sản phẩm -- Gửi dữ liệu sản phẩm tới JD.com và Taobao
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function crawlSaveApi(data) {
  return request({
    url: `product/crawl/save`,
    method: 'POST',
    data,
  });
}

/**
 * @description Quản lý sản phẩm -- Tạo thuộc tính
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function generateAttrApi(data, id, type) {
  return request({
    url: `product/generate_attr/${id}/${type}`,
    method: 'POST',
    data,
  });
}

/**
 * @description Thuộc tính sản phẩm -- danh sách
 * @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function ruleListApi(params) {
  return request({
    url: `product/product/rule`,
    method: 'GET',
    params,
  });
}

/**
 * @description Thuộc tính sản phẩm -- thêm
 * @param {Number} param id {Number} tài sảnid
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function ruleAddApi(data, id) {
  return request({
    url: `product/product/rule/${id}`,
    method: 'POST',
    data,
  });
}

/**
 * @description Thuộc tính sản phẩm - chi tiết
 * @param {Number} param id {Number} tài sảnid
 */
export function ruleInfoApi(id) {
  return request({
    url: `product/product/rule/${id}`,
    method: 'get',
  });
}

/**
 * @description Đánh giá sản phẩm - đánh giá ảo
 * @id--sản phẩmid；
 */
export function fictitiousReply(id) {
  return request({
    url: `product/reply/fictitious_reply/${id}`,
    method: 'get',
  });
}

/**
 * @description Thuộc tính sản phẩm -- Nhận mẫu thuộc tính quy tắc
 */
export function productGetRuleApi() {
  return request({
    url: `product/product/get_rule`,
    method: 'get',
  });
}

/**
 * @description Sản phẩm -- Nhận mẫu vận chuyển
 */
export function productGetTemplateApi() {
  return request({
    url: `product/product/get_template`,
    method: 'get',
  });
}

/**
 * @description Nhận thông số tải lên
 */
export function productGetTempKeysApi(data) {
  return request({
    url: `product/product/get_temp_keys`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Cửa hàng sản phẩm -- Xuất khẩu
 */
export function storeProductApi(data) {
  return request({
    url: `export/storeProduct`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Thêm sản phẩm - phát hiện sự tồn tại của hoạt động
 */
export function checkActivityApi(id) {
  return request({
    url: `product/product/check_activity/${id}`,
    method: 'get',
  });
}

/**
 * @description Bổ sung và chỉnh sửa sản phẩm--Thẻ người dùng
 */
export function labelListApi() {
  return request({
    url: 'user/user_label',
    method: 'get',
  });
}
/**
 * @description Thành phần nhận thẻ người dùng
 */
export function productUserLabel() {
  return request({
    url: 'user/user_tree_label',
    method: 'get',
  });
}
/**
 * @description Loại tải lên
 */
export function uploadType() {
  return request({
    url: 'file/upload_type',
    method: 'get',
  });
}

/**
 * @description Nhập khẩu bí mật thẻ
 */
export function importCard(data) {
  return request({
    url: 'product/product/import_card',
    method: 'get',
    params: data,
  });
}

/**
 * @description Cài đặt lô sản phẩm
 * @param {Number} param id {Number} tài sảnid
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function batchSetting(data) {
  return request({
    url: `product/batch/setting`,
    method: 'POST',
    data,
  });
}

/**
 * @description Cấu hình loại sản phẩm
 */
export function getProductTypeConfig() {
  return request({
    url: 'product/product_type_config',
    method: 'get',
  });
}

/**
 * @description Thêm sản phẩm - thẻ sản phẩm
 */
export function productStoreLabel() {
  return request({
    url: 'product/product_label',
    method: 'get',
  });
}

/**
 * @description Thông số sản phẩm - danh sách
 * @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function paramListApi(params) {
  return request({
    url: `product/param/list`,
    method: 'GET',
    params,
  });
}

/**
 * @description Thông số sản phẩm - chi tiết
 * @param {Number} param id {Number} tham sốid
 */
export function paramInfoApi(id) {
  return request({
    url: `product/param/info/${id}`,
    method: 'get',
  });
}

/**
 * @description Thông số sản phẩm -- Thêm
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function paramSaveApi(data) {
  return request({
    url: `product/param/save/${data.id}`,
    method: 'POST',
    data,
  });
}

/**
 * @description Phân loại thẻ sản phẩm - danh sách
 * @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function labelCateListApi(params) {
  return request({
    url: `product/label_cate/list`,
    method: 'GET',
    params,
  });
}

/**
 * @description Phân loại thẻ sản phẩm -- Thêm
 * tham số yêu cầu dữ liệu
 */
export function productLabelCateFormApi(id) {
  return request({
    url: `product/label_cate/form/${id}`,
    method: 'get',
  });
}

/**
 * @description Danh sách thẻ sản phẩm
 * tham số yêu cầu dữ liệu
 */
export function productLabelListApi(data) {
  return request({
    url: `product/label/list`,
    method: 'get',
    params: data,
  });
}
/**
 * @description Danh sách thẻ sản phẩm -- tất cả
 * tham số yêu cầu dữ liệu
 */
export function productLabelUseListApi(data) {
  return request({
    url: `product/label/use_list`,
    method: 'get',
  });
}

/**
 * @description Mua lại thẻ sản phẩm
 * tham số yêu cầu dữ liệu
 */
export function productLabelInfoApi(data) {
  return request({
    url: `product/label/info/${data.id}`,
    method: 'get',
  });
}

/**
 * @description Lưu thẻ sản phẩm
 * tham số yêu cầu dữ liệu
 */
export function productLabelSaveApi(data) {
  return request({
    url: `product/label/save`,
    method: 'post',
    data: data,
  });
}
/**
 * @description Thẻ sản phẩm--sửa đổi trạng thái
 * @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function labelStatusApi(data) {
  return request({
    url: `product/label/status/${data.id}/${data.status}`,
    method: 'PUT',
  });
}
/**
 * @description Thẻ sản phẩm--sửa đổi trạng thái
 * @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function labelIsShowApi(data) {
  return request({
    url: `product/label/is_show/${data.id}/${data.is_show}`,
    method: 'PUT',
  });
}

/**
 * @description Dịch vụ bảo hành sản phẩm -- Danh sách
 * tham số yêu cầu dữ liệu
 */
export function productProtectionListApi(data) {
  return request({
    url: `product/protection/list`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Dịch vụ bảo hành sản phẩm -- Thêm
 * tham số yêu cầu dữ liệu
 */
export function productProtectionFormApi(id) {
  return request({
    url: `product/protection/form/${id}`,
    method: 'get',
  });
}

/**
 * @description Dịch vụ bảo lãnh hàng hóa
 * tham số yêu cầu dữ liệu
 */
export function productProtectionInfoApi(data) {
  return request({
    url: `product/protection/info`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Dịch vụ bảo hành sản phẩm--Sửa đổi trạng thái
 * @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function protectionStatusApi(data) {
  return request({
    url: `product/protection/status/${data.id}/${data.status}`,
    method: 'PUT',
  });
}

/**
 * @description Danh sách hoa hồng
 */
export function productBrokerage(id, type) {
  return request({
    url: `product/other_info/${id}/${type}`,
    method: 'get',
  });
}

/**
 * @description Hoa hồng Gửi
 */
export function productBrokerageUpdate(id, type, data) {
  return request({
    url: `product/other_save/${id}/${type}`,
    method: 'post',
    data,
  });
}

/**
 * @description Đánh giá hàng loạt ý kiến
 */
export function replyBatchStatus(data) {
  return request({
    url: `product/reply/batch_set_status`,
    method: 'post',
    data,
  });
}
