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
 * Đăng nhập
 * */
export function AccountLogin(data) {
  return request({
    url: '/login',
    method: 'post',
    data,
    kefu: true,
  });
}

/**
 * Lấy danh sách người dùng trò chuyện dịch vụ khách hàng ở bên trái
 * @constructor
 */
export function record(params) {
  return request({
    url: '/user/record',
    method: 'get',
    params,
    kefu: true,
  });
}

/**
 * Nhận thông tin chi tiết người dùng ở bên trái
 * @constructor
 */
export function userInfo(id) {
  return request({
    url: '/user/info/' + id,
    method: 'get',
    kefu: true,
  });
}

/**
 * Lấy danh sách đơn hàng của người dùng ở bên trái
 * @constructor
 */
export function getorderList(id, params) {
  return request({
    url: '/order/list/' + id,
    method: 'get',
    params,
    kefu: true,
  });
}

/**
 * Dịch vụ khách hàng giao hàng theo đơn đặt hàng
 * @constructor
 */
export function orderDelivery(id, data) {
  return request({
    url: '/order/delivery/' + id,
    method: 'post',
    data,
    kefu: true,
  });
}

/**
 * Thay đổi giá chỉ bằng một cú nhấp chuột
 */
export function editPriceApi(id, data) {
  return request({
    url: `/order/update/${id}`,
    method: 'put',
    data,
    kefu: true,
  });
}

/**
 * Dịch vụ khách hàng thay đổi giá đặt hàng
 * @constructor
 */
export function orderEdit(id) {
  return request({
    url: 'order/edit/' + id,
    method: 'get',
    kefu: true,
  });
}

/**
 * Mẫu hoàn trả đơn hàng dịch vụ khách hàng
 * @constructor
 */
export function orderRecord(id) {
  return request({
    url: 'order/refund_form/' + id,
    method: 'get',
    kefu: true,
  });
}

/**
 * Dịch vụ khách hàng Hoàn tiền đơn hàng
 * @constructor
 */
export function orderRefundApi(data) {
  return request({
    url: 'order/refund',
    method: 'post',
    data,
    kefu: true,
  });
}

/**
 * Hồ sơ mua sản phẩm
 * @constructor
 */
export function productCart(uid, params) {
  return request({
    url: 'product/cart/' + uid,
    method: 'get',
    params,
    kefu: true,
  });
}

/**
 * Chi tiết sản phẩm
 * @constructor
 */
export function productVisit(uid, params) {
  return request({
    url: 'product/visit/' + uid,
    method: 'get',
    params,
    kefu: true,
  });
}

/**
 * Đồ nóng
 * @constructor
 */
export function productHot(uid, params) {
  return request({
    url: 'product/hot/' + uid,
    method: 'get',
    params,
    kefu: true,
  });
}

/**
 * Kỹ năng phục vụ khách hàng
 * @constructor
 */
export function speeChcraft(params) {
  return request({
    url: 'service/speechcraft',
    method: 'get',
    params,
    kefu: true,
  });
}

/**
 * Danh sách chuyển dịch vụ khách hàng
 * @constructor
 */
export function transferList(params) {
  return request({
    url: 'service/transfer_list',
    method: 'get',
    params,
    kefu: true,
  });
}

/**
 * Danh sách chuyển dịch vụ khách hàng
 * @constructor
 */
export function serviceTransfer(params) {
  return request({
    url: 'service/transfer',
    method: 'post',
    params,
    kefu: true,
  });
}

/**
 * Thẻ người dùng dịch vụ khách hàng
 * @constructor
 */
export function userLabel(id) {
  return request({
    url: `user/label/${id}`,
    method: 'get',
    kefu: true,
  });
}

/**
 * Cập nhật nhãn người dùng dịch vụ khách hàng
 * @constructor
 */
export function userLabelPut(id, data) {
  return request({
    url: `user/label/${id}`,
    method: 'put',
    data,
    kefu: true,
  });
}

/**
 * Danh sách trò chuyện của người dùng dịch vụ khách hàng
 * @constructor
 */
export function serviceList(params) {
  return request({
    url: `service/list`,
    method: 'get',
    params,
    kefu: true,
  });
}

/**
 * Đăng xuất
 * @constructor
 */
export function AccountLogoutKefu() {
  return request({
    url: `user/logout`,
    method: 'post',
    kefu: true,
  });
}

/**
 * Nhận thông tin đăng nhập bằng cách quét mã QR
 * @constructor
 */
export function getSanCodeKey() {
  return request({
    url: `/key`,
    method: 'get',
    kefu: true,
  });
}

/**
 * Chi tiết sản phẩm
 * @constructor
 */
export function productInfo(id) {
  return request({
    url: `product/info/${id}`,
    method: 'get',
    kefu: true,
  });
}

/**
 * Lấy hình ảnh băng chuyền vàlogo
 */
export function loginInfoApi() {
  return request({
    url: '/login/info',
    method: 'get',
    kefu: true,
  });
}

/**
 * Ghi chú đặt hàng
 */
export function orderRemark(data) {
  return request({
    url: '/order/remark',
    method: 'post',
    data,
    kefu: true,
  });
}

/**
 * Chi tiết đặt hàng
 */
export function orderInfo(id) {
  return request({
    url: '/order/info/' + id,
    method: 'get',
    kefu: true,
  });
}

/**
 * Công ty hậu cần
 */
export function orderExport() {
  return request({
    url: '/order/export',
    method: 'get',
    kefu: true,
  });
}

/**
 * Mẫu công ty chuyển phát nhanh
 */
export function orderTemp(params) {
  return request({
    url: '/order/temp',
    method: 'get',
    params,
    kefu: true,
  });
}

/**
 * Lấy danh sách người giao hàng
 */
export function orderDeliveryAll() {
  return request({
    url: '/order/delivery_all',
    method: 'get',
    kefu: true,
  });
}

/**
 * Nhận nhân viên vận chuyển
 */
export function getSender() {
  return request({
    url: '/order/delivery_info',
    method: 'get',
    kefu: true,
  });
}

/**
 * Nhận phân loại giọng nói
 */
export function serviceCate(params) {
  return request({
    url: '/service/cate',
    method: 'get',
    params,
    kefu: true,
  });
}

/**
 * Sửa đổi lời nói của bạn
 */
export function serviceCateUpdate(id, params) {
  return request({
    url: 'service/speechcraft/' + id,
    method: 'PUT',
    params,
    kefu: true,
  });
}

/**
 * Thêm từ
 */
export function addSpeeChcraft(data) {
  return request({
    url: 'service/speechcraft',
    method: 'post',
    data,
    kefu: true,
  });
}

/**
 * Thêm danh mục
 */
export function addServiceCate(data) {
  return request({
    url: 'service/cate',
    method: 'post',
    data,
    kefu: true,
  });
}

/**
 * Sửa đổi phân loại
 */
export function editServiceCate(id, params) {
  return request({
    url: 'service/cate/' + id,
    method: 'PUT',
    params,
    kefu: true,
  });
}

/**
 * Quét mã để đăng nhập
 */
export function scanStatus(key, params) {
  return request({
    url: 'scan/' + key,
    method: 'get',
    params,
    kefu: true,
  });
}

/**
 * Quét mã để xóa
 */
export function orderVerificApi(id) {
  return request({
    url: `/order/verific/${id}`,
    method: 'get',
    kefu: true,
  });
}

/**
 * Nhóm người dùng dịch vụ khách hàng
 * @constructor
 */
export function userGroupApi() {
  return request({
    url: `user/group`,
    method: 'get',
    kefu: true,
  });
}

/**
 * Nhóm người dùng cài đặt dịch vụ khách hàng
 * @constructor
 */
export function putGroupApi(uid, id) {
  return request({
    url: `user/group/${uid}/${id}`,
    method: 'put',
    kefu: true,
  });
}

/**
 * Cấu hình dịch vụ khách hàng
 * @constructor
 */
export function kefuConfig() {
  return request({
    url: `config`,
    method: 'get',
    kefu: true,
  });
}

/**
 * Dịch vụ khách hàng ngẫu nhiên của khách hàng
 * @constructor
 */
export function serviceListApi(params) {
  return request({
    url: `tourist/user`,
    method: 'get',
    params,
    kefu: true,
  });
}

/**
 * Không gian quảng cáo của khách hàng
 * @constructor
 */
export function getAdvApi() {
  return request({
    url: `tourist/adv`,
    method: 'get',
    kefu: true,
  });
}

/**
 * Lịch sử trò chuyện của khách hàng
 * @constructor
 */
export function chatListApi(params) {
  return request({
    url: `tourist/chat`,
    method: 'get',
    params,
    kefu: true,
  });
}

/**
 * Khách hàng phản hồi dịch vụ khách hàng
 * @constructor
 */
export function feedbackDataApi() {
  return request({
    url: `tourist/feedback`,
    method: 'get',
    kefu: true,
  });
}

/**
 * Lời nhắc phản hồi của khách hàng
 * @constructor
 */
export function feedbackFromApi(data) {
  return request({
    url: `tourist/feedback`,
    method: 'post',
    data,
    kefu: true,
  });
}

/**
 * Khách truy cập của khách hàng có được số đơn đặt hàng của người dùng
 * @constructor
 */
export function getOrderApi(order_id, params) {
  return request({
    url: `tourist/order/${order_id}`,
    method: 'get',
    params,
    kefu: true,
  });
}

/**
 * Chi tiết sản phẩm của khách hàng
 * @constructor
 */
export function productApi(id) {
  return request({
    url: `tourist/product/${id}`,
    method: 'get',
    kefu: true,
  });
}

/**
 * Nhận liên kết dịch vụ khách hàng
 * @constructor
 */
export function getWorkermanUrl() {
  return request({
    url: `get_workerman_url`,
    method: 'get',
  });
}

/**
 * Sao chép và dán hình ảnh tải lên
 */
export function uploadImg(data) {
  return request({
    url: `upload`,
    method: 'post',
    data,
    kefu: true,
  });
}
