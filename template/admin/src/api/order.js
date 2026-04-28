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
 * @description Quản lý đơn hàng--danh sách
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function orderList(data) {
  return request({
    url: '/order/list',
    method: 'get',
    params: data,
  });
}

/**
 * @description Thống kê tiêu đề hóa đơn
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function orderInvoiceChart(data) {
  return request({
    url: 'order/invoice/chart',
    method: 'get',
    params: data,
  });
}

/**
 * @description Thống kê tiêu đề hóa đơn
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function orderInvoiceList(data) {
  return request({
    url: 'order/invoice/list',
    method: 'get',
    params: data,
  });
}

/**
 * @description Đơn đặt hàng Gửi hóa đơn
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function orderInvoiceSet(id, data) {
  return request({
    url: `order/invoice/set/${id}`,
    method: 'post',
    data,
  });
}

/**
 * @description Chi tiết hóa đơn đặt hàng；
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function orderInvoiceInfo(id) {
  return request({
    url: `order/invoice_order_info/${id}`,
    method: 'get',
  });
}

/**
 * @description Dữ liệu đơn hàng--danh sách
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function getOrdes(data) {
  return request({
    url: '/order/chart',
    method: 'get',
    params: data,
  });
}

/**
 * @description Dữ liệu chỉnh sửa mẫu đơn đặt hàng
 * @param {Number} param id {Number} Đặt hàngid
 */
export function getOrdeDatas(id) {
  return request({
    url: `/order/edit/${id}`,
    method: 'get',
  });
}

/**
 * @description Dữ liệu chi tiết mẫu đơn đặt hàng
 * @param {Number} param id {Number} Đặt hàngid
 */
export function getDataInfo(id) {
  return request({
    url: `/order/info/${id}`,
    method: 'get',
  });
}

/**
 * @description Chi tiết mẫu đơn đặt hàng dữ liệu mới
 * @param {Number} param id {Number} Đặt hàngid
 */
export function getDataInfoNew(id) {
  return request({
    url: `/refund/info/${id}`,
    method: 'get',
  });
}

/**
 * @description Sửa đổi thông tin nhận xét
 * @param {Number} param data.id {Number} Đặt hàngid
 * @param {String} param data.remark {String} Bình luận
 */
export function putRemarkData(data) {
  return request({
    url: `/order/remark/${data.id}`,
    method: 'put',
    data: data.remark,
  });
}

/**
 * @description Nhận hồ sơ đặt hàng
 * @param {Number} param data.id {Number} Đặt hàngid
 * @param {String} param data.datas {String} Thông số phân trang
 */
export function getOrderRecord(data) {
  return request({
    url: `/order/status/${data.id}`,
    method: 'get',
    params: data.datas,
  });
}

/**
 * @description Nhận dữ liệu biểu mẫu hoàn tiền
 * @param {Number} param id {Number} Đặt hàngid
 */
export function getRefundFrom(id) {
  return request({
    url: `/order/refund/${id}`,
    method: 'get',
  });
}
/**
 * @description Đền bù
 * @param {Number} param id {Number} Đặt hàngid
 */
export function refundPrice(id, data) {
  return request({
    url: `/order/refund/${id}`,
    method: 'put',
    data,
  });
}

/**
 * @description Phiên bản mới-Nhận dữ liệu biểu mẫu hoàn tiền
 * @param {Number} param id {Number} Đặt hàngid
 */
export function getNewRefundFrom(id) {
  return request({
    url: `/refund/refund/${id}`,
    method: 'get',
  });
}

/**
 * @description Nhận công ty chuyển phát nhanh
 */
export function getExpressData(status) {
  return request({
    url: `/order/express_list?status=${status || ''}`,
    method: 'get',
  });
}

/**
 * @description Nhận dữ liệu biểu mẫu không hoàn lại tiền
 * @param {Number} param id {Number} Đặt hàngid
 */
export function getnoRefund(id) {
  return request({
    url: `/order/no_refund/${id}`,
    method: 'get',
  });
}
/**
 * @description Phiên bản mới - Nhận dữ liệu biểu mẫu không hoàn lại tiền
 * @param {Number} param id {Number} Đặt hàngid
 */
export function getNewnoRefundFrom(id) {
  return request({
    url: `/refund/no_refund/${id}`,
    method: 'get',
  });
}

/**
 * @description Gửi mẫu đơn gửi vận chuyển
 * @param {Number} param data.id {Number} Đặt hàngid
 * @param {Object} param data.datas {Object} thông tin biểu mẫu
 */
export function putDelivery(data) {
  return request({
    url: `/order/delivery/${data.id}`,
    method: 'put',
    data: data.datas,
  });
}

export function orderSheetInfo() {
  return request({
    url: '/order/sheet_info',
    method: 'get',
  });
}

/**
 * Danh sách tất cả người giao hàng
 */
export function deliveryList() {
  return request({
    url: '/order/delivery/index',
    method: 'get',
  });
}

/**
 * Lấy danh sách tất cả người giao hàng khi đặt hàng
 */
export function orderDeliveryList() {
  return request({
    url: '/order/delivery/list',
    method: 'get',
  });
}

/**
 * Danh sách sửa đổi trạng thái tài khoản
 * @param {*} data data
 */
export function orderDeliveryStatus(data) {
  return request({
    url: `/order/delivery/set_status/${data.id}/${data.status}`,
    method: 'get',
  });
}

/**
 * Chỉnh sửa mẫu người giao hàng
 * @param {*} id id
 */
export function orderDeliveryEdit(id) {
  return request({
    url: `/order/delivery/${id}/edit`,
    method: 'get',
  });
}

/**
 * Thêm mẫu người giao hàng mới
 */
export function orderDeliveryAdd() {
  return request({
    url: '/order/delivery/add',
    method: 'get',
  });
}

/**
 * Mẫu biểu mẫu điện tử
 * @param {com} data Số công ty chuyển phát nhanh
 */
export function orderExpressTemp(data) {
  return request({
    url: '/order/express/temp',
    method: 'get',
    params: data,
  });
}

/**
 * @description Danh sách đơn hàng phụ---phân chia đơn hàng
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function splitOrderList(id) {
  return request({
    url: `order/split_order/${id}`,
    method: 'get',
  });
}

/**
 * @description Lấy danh sách các mặt hàng có thể chia nhỏ trong một đơn hàng
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function splitCartInfo(id) {
  return request({
    url: `order/split_cart_info/${id}`,
    method: 'get',
  });
}

/**
 * @description Chia đơn hàng và gửi hàng
 * @param {Number} param data.id {Number} Đặt hàngid
 * @param {Object} param data.datas {Object} thông tin biểu mẫu
 */
export function splitDelivery(data) {
  return request({
    url: `/order/split_delivery/${data.id}`,
    method: 'put',
    data: data.datas,
  });
}

/**
 * @description Nhận mẫu hoàn trả điểm
 * @param {Number} param id {Number} Đặt hàngid
 */
export function refundIntegral(id) {
  return request({
    url: `/order/refund_integral/${id}`,
    method: 'get',
  });
}

/**
 * @description Thanh toán ngay
 * @param {String} param path {String} Địa chỉ yêu cầu
 * @param {String} param method {String} Phương thức yêu cầu
 */
export function payOffline(path, method) {
  return request({
    url: path,
    method: method,
  });
}

/**
 * @description Mẫu thông tin vận chuyển
 * @param {Number} param id {Number} Đặt hàngid
 */
export function getDistribution(id) {
  return request({
    url: `/order/distribution/${id}`,
    method: 'get',
  });
}

/**
 * @description Thông tin hậu cần đặt hàng
 * @param {Number} param id {Number} Đặt hàngid
 */
export function getExpress(id) {
  return request({
    url: `/order/express/${id}`,
    method: 'get',
  });
}

/**
 * @description  Xóa đơn hàng
 * @param {String} param data {String} Nội dung xác nhận
 */
export function putWrite(data) {
  return request({
    url: '/order/write',
    method: 'post',
    data: data,
  });
}

/**
 * @description Quản lý đơn hàng -- Xuất khẩu
 */
export function storeOrderApi(data) {
  return request({
    url: `export/storeOrder`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Viết ra một đơn hàng
 */
export function writeUpdate(order_id) {
  return request({
    url: `order/write_update/${order_id}`,
    method: 'put',
  });
}

/**
 * Thu ngân đặt hàng
 */
export function orderScanList(data) {
  return request({
    url: 'order/scan_list',
    method: 'get',
    params: data,
  });
}

/**
 * Mã thanh toán ngoại tuyến
 */
export function orderOfflineScan(id) {
  return request({
    url: 'order/offline_scan',
    method: 'get',
    params: id,
  });
}

/**
 * @description Đơn hàng sau bán hàng
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function orderRefundList(data) {
  return request({
    url: 'refund/list',
    method: 'get',
    params: data,
  });
}

/**
 * @description Hồ sơ lô hàng số lượng lớn
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function queueIndex(data) {
  return request({
    url: 'queue/index',
    method: 'get',
    params: data,
  });
}

/**
 * @description Vận chuyển số lượng lớn-Hướng dẫn sử dụng
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function handBatchDelivery(data) {
  return request({
    url: 'order/hand/batch_delivery',
    method: 'get',
    params: data,
  });
}
/**
 * @description tải về
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function batchOrderDelivery(id, type, catchType) {
  return request({
    url: `export/batchOrderDelivery/${id}/${type}/${catchType}`,
    method: 'get',
  });
}
/**
 * @description Đơn hàng Points Mall -- Xuất khẩu
 */
export function storeIntegralOrder(data) {
  return request({
    url: `export/storeIntegralOrder`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Danh sách nhiệm vụ - Xem
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function deliveryLog(id, type, data) {
  return request({
    url: `queue/delivery/log/${id}/${type}`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Tải xuống Bảng so sánh các công ty Logistics
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function exportExpressList(id) {
  return request({
    url: 'export/expressList',
    method: 'get',
  });
}

/**
 * @description Lô hàng số lượng lớn-tự động
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function otherBatchDelivery(data) {
  return request({
    url: 'order/other/batch_delivery',
    method: 'post',
    data,
  });
}
/**
 * @description Tính toán số tiền vận chuyển của người bán
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function orderPrice(data) {
  return request({
    url: 'order/price',
    method: 'post',
    data,
  });
}
/**
 * @description Hủy vận chuyển của người bán
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function shipmentCancelOrder(id, data) {
  return request({
    url: `order/shipment_cancel_order/${id}`,
    method: 'post',
    data: data,
  });
}
/**
 * @description Thực hiện lại
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function queueAgain(id, type) {
  return request({
    url: `queue/again/do_queue/${id}/${type}`,
    method: 'get',
  });
}
/**
 * @description Xóa nhiệm vụ ngoại lệ
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function queueDel(id, type) {
  return request({
    url: `queue/del/wrong_queue/${id}/${type}`,
    method: 'get',
  });
}

/**
 * @description Dừng tác vụ
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function stopWrongQueue(id) {
  return request({
    url: `queue/stop/wrong_queue/${id}`,
    method: 'get',
  });
}

/**
 * @description Danh sách chuyển phát nhanh
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function kuaidiComsList() {
  return request({
    url: `order/kuaidi_coms`,
    method: 'get',
  });
}

/**
 * @description Sửa đổi thông tin nhận xét đơn hàng hoàn tiền
 * @param {Number} param data.id {Number} Đặt hàngid
 * @param {String} param data.remark {String} Bình luận
 */
export function putRefundRemarkData(data) {
  return request({
    url: `/refund/remark/${data.id}`,
    method: 'put',
    data: data.remark,
  });
}

/**
 * @description Hóa đơn nhập khẩu
 */
export function importExpress(data) {
  return request({
    url: '/order/delivery/import_express',
    method: 'get',
    params: data,
  });
}

/**
 * @description In danh sách phân phối
 * Thứ tự id @paramid
 */
export function distributionOrder(id) {
  return request({
    url: `/order/print/shipping/${id}`,
    method: 'get',
  });
}
/**
 * @description Quản lý hóa đơn
 * @param id hóa đơnid
 */
export function invoiceIssuanceUrl(id) {
  return request({
    url: `/order/invoice_issuance_url/${id}`,
    method: 'get',
  });
}
/**
 * @description Tải hóa đơn xuống
 * @param id hóa đơnid
 */
export function downInvoice(id) {
  return request({
    url: `/order/down_invoice/${id}`,
    method: 'get',
  });
}
/**
 * @description Xuất hóa đơn âm
 * @param id hóa đơnid
 */
export function redInvoiceIssuance(id) {
  return request({
    url: `/order/red_invoice_issuance/${id}`,
    method: 'get',
  });
}
/**
 * @description Sửa đổi trạng thái hóa đơn
 * @param id id hóa đơn
 * Thông tin hóa đơn dữ liệu @param
 */
export function saveInvoiceInfo(id, data) {
  return request({
    url: `/order/save_invoice_info/${id}`,
    method: 'post',
    data: data,
  });
}
/**
 * @description Tìm kiếm phân loại hóa đơn
 * Tên @param Tên danh mục hóa đơn
 */
export function invoiceCategory(name) {
  return request({
    url: `/order/invoice_category`,
    method: 'get',
    params: name,
  });
}
/**
 * @description Gửi cấu hình hóa đơn điện tử
 * Thông tin hóa đơn dữ liệu @param
 */
export function saveBasics(data) {
  return request({
    url: `/marketing/integral_config/save_basics`,
    method: 'post',
    data,
  });
}
/**
 * @description Nhận cấu hình hóa đơn điện tử
 */
export function invoiceConfig() {
  return request({
    url: `/order/elec_invoice_config`,
    method: 'get',
  });
}

// Sửa đổi địa chỉ giao hàng
export function editAddress(data) {
  return request({
    url: `/order/edit_address/${data.id}`,
    method: 'post',
    data,
  });
}
