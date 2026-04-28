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
import { getCookies } from '@/libs/util';

/**
 * @description Cài đặt Cài đặt hệ thống Cài đặt ứng dụng Tiêu đề cài đặt
 * @param {Object} param data {Object} Loại tham số giá trị
 */
export function headerListApi(data) {
  return request({
    url: 'setting/config/header_basics',
    method: 'get',
    params: data,
  });
}

/**
 * @description Cài đặt Cài đặt hệ thống Cài đặt ứng dụng Chỉnh sửa biểu mẫu
 * @param {Object} param data {Object} Loại tham số giá trị
 */
export function dataFromApi(data, url) {
  return request({
    url: url,
    method: 'get',
    params: data,
  });
}

/**
 * @description Cài đặt Danh sách cài đặt SMS
 * @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function tempListApi(params) {
  return request({
    url: params.url,
    method: 'get',
    params: params.data,
  });
}

/**
 * @description Cài đặt SMS Cài đặt Mẫu đơn đăng ký
 * @param {Object} param data {Object} Loại tham số giá trị
 */
export function tempCreateApi() {
  return request({
    url: 'notify/sms/temp/create',
    method: 'get',
  });
}

/**
 * @description Cài đặt Cài đặt SMS Đăng nhập
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function configApi(data) {
  return request({
    url: 'serve/login',
    method: 'post',
    data,
  });
}

/**
 * @description Cài đặt SMS Đổi mật khẩu
 */
export function serveModifyApi(data) {
  return request({
    url: 'serve/modify',
    method: 'post',
    data,
  });
}

/**
 * @description Cài đặt SMS Sửa đổi số điện thoại di động
 */
export function updateHoneApi(data) {
  return request({
    url: 'serve/update_phone',
    method: 'post',
    data,
  });
}

/**
 * @description Cài đặt Cài đặt SMS Thay đổi mật khẩu tài khoản
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
// export function configApi (data) {
//     return request({
//         url: 'notify/sms/config',
//         method: 'post',
//         data
//     });
// }

/**
 * @description Cài đặt Cài đặt SMS Gửi mã xác minh
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function captchaApi(data) {
  return request({
    url: 'serve/captcha',
    method: 'post',
    data,
  });
}
/**
 * @description Xác minh mã xác minh
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function checkCaptchaApi(data) {
  return request({
    url: 'serve/checkCode',
    method: 'post',
    data,
  });
}

/**
 * @description Cài đặt Cài đặt SMS Đăng ký
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function registerApi(data) {
  return request({
    url: 'serve/register',
    method: 'post',
    data,
  });
}

/**
 * @description Cài đặt Cài đặt SMS Số lượng tin nhắn SMS còn lại
 */
export function smsNumberApi() {
  return request({
    url: 'notify/sms/number',
    method: 'get',
  });
}

/**
 * @description Cài đặt Cài đặt SMS Thông tin người dùng nền tảng
 */
export function serveInfoApi() {
  return request({
    url: 'serve/info',
    method: 'get',
  });
}

/**
 * @description Sửa đổi chữ ký SMS
 */
export function serveSign(data) {
  return request({
    url: 'serve/sms/sign',
    method: 'PUT',
    data,
  });
}

/**
 * Đăng nhập dịch vụ khách hàng
 */
export function kefuLogin(id) {
  return request({
    url: `app/wechat/kefu/login/${id}`,
    method: 'get',
  });
}

/**
 * Danh sách cụm từ dịch vụ khách hàng
 */
export function wechatSpeechcraft(data) {
  return request({
    url: `app/wechat/speechcraft`,
    method: 'get',
    params: data,
  });
}

/**
 * Từ dịch vụ khách hàngSửa đổi
 */
export function speechcraftEdit(id) {
  return request({
    url: `app/wechat/speechcraft/${id}/edit`,
    method: 'get',
  });
}

/**
 * Thêm kỹ năng dịch vụ khách hàng
 */
export function speechcraftCreate() {
  return request({
    url: `app/wechat/speechcraft/create`,
    method: 'get',
  });
}

/**
 * Phản hồi về dịch vụ khách hàng
 */
export function kefuFeedBack(params) {
  return request({
    url: `app/feedback`,
    method: 'get',
    params,
  });
}

/**
 * Phản hồi về dịch vụ khách hàng
 */
export function kefuFeedBackEdit(id) {
  return request({
    url: `app/feedback/${id}/edit`,
    method: 'get',
  });
}

/**
 * @description Thành lập công ty hậu cần SMS
 */
export function exportAllApi() {
  return request({
    url: 'serve/export_all',
    method: 'get',
  });
}

/**
 * Có kích hoạt biểu mẫu điện tử hay không
 */
// export function serveDumpOpen () {
//     return request({
//         url: `serve/dump_open`,
//         method: 'get'
//     });
// }

/**
 * Khai trương hậu cần
 */
export function serveOpen() {
  return request({
    url: `serve/open`,
    method: 'get',
  });
}

/**
 * @description Cài đặt Bảng điều khiển công ty hậu cần SMS
 */
export function exportTempApi(params) {
  return request({
    url: 'serve/export_temp',
    method: 'get',
    params,
  });
}

/**
 * @description Cài đặt SMS 2= Mẫu điện tử，3 = Danh sách yêu cầu hậu cần
 */
export function serveRecordListApi(params) {
  return request({
    url: 'serve/record',
    method: 'get',
    params,
  });
}

/**
 * @description Thiết lập SMS để kích hoạt các dịch vụ khác
 */
export function serveOpnOtherApi(params) {
  return request({
    url: 'serve/open',
    method: 'get',
    params,
  });
}

/**
 * @description Thiết lập SMS để kích hoạt biểu mẫu điện tử
 */
export function serveOpnExpressApi(data) {
  return request({
    url: 'serve/opn_express',
    method: 'post',
    data,
  });
}

/**
 * @description Cài đặt SMS Kích hoạt dịch vụ SMS
 */
export function serveSmsOpenApi(params) {
  return request({
    url: 'serve/sms/open',
    method: 'get',
    params,
  });
}

/**
 * @description Cài đặt Cài đặt SMS Gói thanh toán
 */
export function smsPriceApi(params) {
  return request({
    url: 'serve/meal_list',
    method: 'get',
    params,
  });
}

/**
 * @description Cài đặt Cài đặt SMS Mã thanh toán
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function payCodeApi(data) {
  return request({
    url: 'serve/pay_meal',
    method: 'post',
    data,
  });
}

/**
 * @description Cài đặt Cài đặt SMS Gửi bản ghi
 */
export function smsRecordApi(params) {
  return request({
    url: 'notify/sms/record',
    method: 'get',
    params,
  });
}

/**
 * @description Chi tiết cài đặt cửa hàng
 */
export function storeApi() {
  return request({
    url: 'merchant/store',
    method: 'GET',
  });
}

/**
 * @description Cài đặt cửa hàng Nhận bản đồkey
 */
export function keyApi() {
  return request({
    url: 'merchant/store/address',
    method: 'GET',
  });
}

/**
 * @description Cài đặt cửa hàng Gửi dữ liệu,
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function storeAddApi(data) {
  return request({
    url: `merchant/store/${data.id}`,
    method: 'POST',
    data,
  });
}

/**
 * @description Thiết lập danh sách công ty logistics
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function freightListApi(params) {
  return request({
    url: 'freight/express',
    method: 'get',
    params,
  });
}

/**
 * @description Thành lập công ty hậu cần để thêm biểu mẫu
 */
export function freightCreateApi() {
  return request({
    url: '/freight/express/create',
    method: 'get',
  });
}

/**
 * @description Thiết lập biểu mẫu chỉnh sửa công ty hậu cần
 * @param {Number} param id {Number} Công ty hậu cầnid
 */
export function freightEditApi(id) {
  return request({
    url: `freight/express/${id}/edit`,
    method: 'get',
  });
}

/**
 * @description Đặt công ty hậu cần để sửa đổi trạng thái
 * @param {Number} param id {Number} Công ty hậu cầnid
 */
export function freightStatusApi(data) {
  return request({
    url: `freight/express/set_status/${data.id}/${data.status}`,
    method: 'PUT',
  });
}

/**
 * @description Công ty Chuyển phát nhanh Logistics Đồng bộ
 */
export function freightSyncExpressApi() {
  return request({
    url: `freight/express/sync_express`,
    method: 'get',
  });
}

/**
 * @description Phân loại kỹ năng nói
 */
export function speechcraftcate() {
  return request({
    url: `app/wechat/speechcraftcate`,
    method: 'get',
  });
}
/**
 * @description Phân loại mã kênh
 */
export function wechatQrcodeTree() {
  return request({
    url: `app/wechat_qrcode/cate/list`,
    method: 'get',
  });
}

/**
 * @description Nhận biểu mẫu tạo danh mục
 */
export function speechcraftcateCreate() {
  return request({
    url: `app/wechat/speechcraftcate/create`,
    method: 'get',
  });
}
/**
 * @description Lấy mẫu phân loại tạo và chỉnh sửa mã kênh
 */
export function wechatQrcodeCreate(id) {
  return request({
    url: `app/wechat_qrcode/cate/create/${id}`,
    method: 'get',
  });
}

/**
 * @description Sửa đổi phân loại giọng nói(Nhận biểu mẫu)
 */
export function speechcraftcateEdit(id) {
  return request({
    url: `app/wechat/speechcraftcate/${id}/edit`,
    method: 'get',
  });
}

/**
 * @description Thiết lập danh sách quản lý danh tính
 * @param {Number} param id {Number} Công ty hậu cầnid
 */
export function roleListApi(params) {
  return request({
    url: `setting/role`,
    method: 'GET',
    params,
  });
}
/**
 * @description Lấy danh sách mã kênh
 * @param {Number} param id {Number} Công ty hậu cầnid
 */
export function wechatQrcodeList(params) {
  return request({
    url: `app/wechat_qrcode/list`,
    method: 'GET',
    params,
  });
}

/**
 * @description Cài đặt Quản lý danh tính Sửa đổi trạng thái
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function roleSetStatusApi(data) {
  return request({
    url: `setting/role/set_status/${data.id}/${data.status}`,
    method: 'PUT',
  });
}

/**
 * @description Thiết lập quản lý danh tính ==Chỉnh sửa mới
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function roleCreateApi(data) {
  return request({
    url: `setting/role/${data.id}`,
    method: 'post',
    data,
  });
}

/**
 * @description Thiết lập quản lý danh tính ==Chi tiết
 * @param {Number} param id {Number} Quản lý danh tínhid
 */
export function roleInfoApi(id) {
  return request({
    url: `setting/role/${id}/edit`,
    method: 'get',
  });
}

/**
 * @description Thiết lập quản lý danh tính ==Danh sách quyền
 */
export function menusListApi() {
  return request({
    url: `setting/role/create`,
    method: 'get',
  });
}

/**
 * @description Cài đặt Quản lý dịch vụ khách hàng -- Danh sách
 * @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function kefuListApi(params) {
  return request({
    url: `app/wechat/kefu`,
    method: 'get',
    params,
  });
}

/**
 * @description Cài đặt Quản lý dịch vụ khách hàng -- Chọn người dùng
 *  @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function kefucreateApi(params) {
  return request({
    url: `app/wechat/kefu/create`,
    method: 'get',
    params,
  });
}

/**
 * @description Cài đặt Quản lý dịch vụ khách hàng -- Thêm dịch vụ khách hàng
 *  @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function kefuaddApi() {
  return request({
    url: `app/wechat/kefu/add`,
    method: 'get',
  });
}

/**
 * @description Cài đặt Quản lý dịch vụ khách hàng -- Thêm dịch vụ khách hàng để lưu
 *  @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function kefuAddApi(data) {
  return request({
    url: `app/wechat/kefu`,
    method: 'post',
    data,
  });
}

/**
 * @description Cài đặt Quản lý dịch vụ khách hàng -- Sửa đổi trạng thái
 *  @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function kefusetStatusApi(data) {
  return request({
    url: `app/wechat/kefu/set_status/${data.id}/${data.status}`,
    method: 'PUT',
  });
}

/**
 * @description Đặt mã kênh - sửa đổi trạng thái
 *  @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function wechatQrcodeStatusApi(data) {
  return request({
    url: `app/wechat_qrcode/set_status/${data.id}/${data.status}`,
    method: 'PUT',
  });
}
/**
 * @description Lấy danh sách người dùng mã kênh
 *  @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function getUserList(params) {
  return request({
    url: `app/wechat_qrcode/user_list/${params.id}`,
    method: 'get',
    params,
  });
}
/**
 * @description Cài đặt Chi tiết chỉnh sửa mã kênh Nhận được
 *  @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function wechatQrcodeDetail(id) {
  return request({
    url: `app/wechat_qrcode/info/${id}`,
    method: 'get',
  });
}
/**
 * @description  Sản xuất mã kênh--lưu
 */
export function wechatQrcodeSaveApi(id, data) {
  return request({
    url: `app/wechat_qrcode/save/${id}`,
    method: 'post',
    data,
  });
}
/**
 * @description Cài đặt Quản lý dịch vụ khách hàng -- Chỉnh sửa biểu mẫu
 *  @param {Number} param id {Number} dịch vụ khách hàngid
 */
export function kefuEditApi(id) {
  return request({
    url: `app/wechat/kefu/${id}/edit`,
    method: 'GET',
  });
}

/**
 * @description Cài đặt Quản lý dịch vụ khách hàng -- Danh sách lịch sử trò chuyện
 *  @param {Number} param id {Number} dịch vụ khách hàngid
 *  @param {Object} param params {Object} Truyền tham số
 */
export function kefuRecordApi(params, id) {
  return request({
    url: `app/wechat/kefu/record/${id}`,
    method: 'GET',
    params,
  });
}

/**
 * @description Cài đặt Quản lý dịch vụ khách hàng -- Xem danh sách cuộc trò chuyện
 *  @param {Object} param params {Object} Truyền tham số
 */
export function kefuChatlistApi(params) {
  return request({
    url: `app/wechat/kefu/chat_list`,
    method: 'GET',
    params,
  });
}

/**
 * @description Cài đặt SMS - kiểm tra xem bạn đã đăng nhập chưa
 */
export function isLoginApi() {
  return request({
    url: `notify/sms/is_login`,
    method: 'GET',
  });
}

/**
 * @description Cài đặt SMS -- đăng xuất
 */
export function logoutApi() {
  return request({
    url: `notify/sms/logout`,
    method: 'GET',
  });
}

/**
 * @description Cài đặt Dữ liệu thành phố -- Danh sách
 *  @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function cityListApi(id) {
  return request({
    url: `setting/city/list/${id}`,
    method: 'get',
  });
}

/**
 * @description Cài đặt Thành phố Thêm -- Biểu mẫu
 *  @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function cityAddApi(id) {
  return request({
    url: `setting/city/add/${id}`,
    method: 'get',
  });
}

/**
 * @description Cài đặt Sửa đổi thành phố -- Biểu mẫu
 *  @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function cityApi(id) {
  return request({
    url: `setting/city/${id}/edit`,
    method: 'get',
  });
}

/**
 * @description Thiết lập mẫu vận chuyển hàng hóa -- Danh sách
 *  @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function templatesApi(data) {
  return request({
    url: `setting/shipping_templates/list`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Thiết lập mẫu vận chuyển hàng hóa -- Dữ liệu thành phố
 */
export function templatesCityListApi(data) {
  return request({
    url: `setting/shipping_templates/city_list`,
    method: 'get',
  });
}

/**
 * @description Thiết lập mẫu vận chuyển hàng hóa -- Gửi biểu mẫu sửa đổi；
 */
export function templatesSaveApi(id, data) {
  return request({
    url: `setting/shipping_templates/save/${id}`,
    method: 'post',
    data,
  });
}

/**
 * @description Thiết lập mẫu vận chuyển hàng hóa -- Gửi biểu mẫu sửa đổi；
 */
export function shipTemplatesApi(id) {
  return request({
    url: `setting/shipping_templates/${id}/edit`,
    method: 'get',
  });
}

/**
 * @description Cài đặt cửa hàng -- số danh mục danh sách cửa hàng；
 */
export function storeGetHeaderApi() {
  return request({
    url: `merchant/store/get_header`,
    method: 'get',
  });
}

/**
 * @description Cài đặt cửa hàng - danh sách cửa hàng；
 */
export function merchantStoreApi(data) {
  return request({
    url: `merchant/store`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Cài đặt cửa hàng -- Cài đặt cửa hàng；
 */
export function storeSetShowApi(id, is_show) {
  return request({
    url: `merchant/store/set_show/${id}/${is_show}`,
    method: 'put',
  });
}

/**
 * @description Cài đặt cửa hàng - lưu trữ thông tin sửa đổi；
 */
export function storeGetInfoApi(id) {
  return request({
    url: `merchant/store/get_info/${id}`,
    method: 'get',
  });
}

/**
 * @description Cài đặt cửa hàng - danh sách nhân viên cửa hàng；
 */
export function storeStaffApi(data) {
  return request({
    url: `merchant/store_staff`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Cài đặt cửa hàng - thêm nhân viên cửa hàng mới；
 */
export function storeStaffCreateApi() {
  return request({
    url: `merchant/store_staff/create`,
    method: 'get',
  });
}

/**
 * @description Cài đặt cửa hàng - thêm nhân viên cửa hàng mới；
 */
export function storeStaffEditApi(id) {
  return request({
    url: `merchant/store_staff/${id}/edit`,
    method: 'get',
  });
}

/**
 * @description Cài đặt thư ký -- Cài đặt thư ký hiển thị và ẩn；
 */
export function storeStaffSetShowApi(id, is_show) {
  return request({
    url: `merchant/store_staff/set_show/${id}/${is_show}`,
    method: 'put',
  });
}

/**
 * @description Cài đặt đơn hàng -- danh sách đơn hàng xác nhận；
 */
export function verifyOrderApi(data) {
  return request({
    url: `merchant/verify_order`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Cài đặt đơn hàng -- tiêu đề đơn hàng xóa bỏ；
 */
export function verifySpreadInfoApi(uid) {
  return request({
    url: `merchant/verify/spread_info/${uid}`,
    method: 'get',
  });
}

/**
 * Lấy danh sách cửa hàng được nhân viên tìm kiếm
 */
export function merchantStoreListApi() {
  return request({
    url: `merchant/store_list`,
    method: 'get',
  });
}

/**
 * Xóa bộ nhớ đệm dữ liệu thành phố
 */
export function cityCleanCacheApi() {
  return request({
    url: `setting/city/clean_cache`,
    method: 'get',
  });
}
/**
 *Cấu hình lưu trữ-nhận tiêu đề cấu hình lưu trữ đám mây
 */
export function storageConfigApi() {
  return request({
    url: `system/config/storage/config`,
    method: 'get',
  });
}
/**
 *Cấu hình lưu trữ-nhận tiêu đề cấu hình lưu trữ đám mây
 */
export function storageSwitchApi(data) {
  return request({
    url: `system/config/storage/config`,
    method: 'post',
    data,
  });
}

/**
 * @description Cấu hình lưu trữ-lấy biểu mẫu cấu hình lưu trữ đám mây
 */
export function addConfigApi(type) {
  return request({
    url: `system/config/storage/form/${type}`,
    method: 'get',
  });
}

/**
 * @description Cấu hình lưu trữ-Nhận biểu mẫu tạo lưu trữ đám mây
 */
export function addStorageApi(type) {
  return request({
    url: `system/config/storage/create/${type}`,
    method: 'get',
  });
}

/**
 * @description Cấu hình lưu trữ-lấy danh sách lưu trữ đám mây
 */
export function storageListApi(data) {
  return request({
    url: `system/config/storage`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Không gian đồng bộ hóa cấu hình lưu trữ
 */
export function storageSynchApi(type) {
  return request({
    url: `system/config/storage/synch/${type}`,
    method: 'put',
  });
}
/**
 * @description Lưu trạng thái sửa đổi cấu hình
 */
export function storageStatusApi(id) {
  return request({
    url: `system/config/storage/status/${id}`,
    method: 'put',
  });
}

/**
 * @description Cấu hình lưu trữ-sửa đổi tên miền không gian
 */
export function editStorageApi(id) {
  return request({
    url: `system/config/storage/domain/${id}`,
    method: 'get',
  });
}
/**
 * @description Lưu cấu hình - Nhận hình thu nhỏ
 */
export function positionInfoApi() {
  return request({
    url: `setting/config_list/31`,
    method: 'get',
  });
}
/**
 * @description Lưu cấu hình - Lưu hình thu nhỏ
 */
export function positionPostApi(data) {
  return request({
    url: `setting/config/save_basics`,
    method: 'post',
    data,
  });
}

/**
 * @description Lưu chuyển đổi cấu hình
 */
export function saveType(type) {
  return request({
    url: `system/config/storage/save_type/${type}`,
    method: 'get',
  });
}

/**
 * @description Đa ngôn ngữ - danh sách các loại ngôn ngữ
 */
export function langTypeList(data) {
  return request({
    url: `setting/lang_type/list`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Đa ngôn ngữ - Trình chỉnh sửa mới cho loại ngôn ngữ
 * @param {Number} param id {Number}
 */
export function langTypeForm(id) {
  return request({
    url: `setting/lang_type/form/${id}`,
    method: 'get',
  });
}

/**
 * @description Đa ngôn ngữ - Danh sách chi tiết ngôn ngữ
 */
export function langCodeList(data) {
  return request({
    url: `setting/lang_code/list`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Nhận thông tin ngôn ngữ
 */
export function langCodeInfo(data) {
  return request({
    url: `setting/lang_code/info`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Chỉnh sửa chi tiết ngôn ngữ
 */
export function langCodeSettingSave(data) {
  return request({
    url: `setting/lang_code/save`,
    method: 'post',
    data,
  });
}

/**
 * @description Danh sách quốc gia
 */
export function langCountryList(data) {
  return request({
    url: `setting/lang_country/list`,
    method: 'get',
    params: data,
  });
}
/**
 * Thêm biểu mẫu ngôn ngữ
 * @param {*} id
 * @returns
 */
export function langCountryForm(id) {
  return request({
    url: `setting/lang_country/form/${id}`,
    method: 'get',
  });
}
/**
 * Thêm biểu mẫu ngôn ngữ
 * @param {*} id
 * @returns
 */
export function langTypeStatus(id, status) {
  return request({
    url: `setting/lang_type/status/${id}/${status}`,
    method: 'put',
  });
}

/**
 * @description Dịch bằng một cú nhấp chuột
 */
export function langCodeTranslate(data) {
  return request({
    url: `setting/lang_code/translate`,
    method: 'post',
    data,
  });
}

/**
 * @description tạo mã
 */
export function codeCrud(data) {
  return request({
    url: `system/crud`,
    method: 'post',
    data,
  });
}
/**
 * @description Quét mã để tải lên liên kết để nhận
 */
export function scanUploadQrcode(pid) {
  return request({
    url: `file/scan_upload/qrcode?pid=${pid}`,
    method: 'get',
  });
}
/**
 * @description Quét mã QR để tải ảnh lên để nhận
 */
export function scanUploadGet(scan_token) {
  return request({
    url: `file/scan_upload/image/${scan_token}`,
    method: 'get',
  });
}
/**
 * @description Tải lên hình ảnh
 */
export function fileUpload(data) {
  return request({
    url: `file/upload`,
    method: 'post',
    headers: {
      'Authori-zation': 'Bearer ' + getCookies('token'),
      'content-type': 'multipart/form-data;' + 'Bearer ' + getCookies('token'),
    },
    data,
  });
}
/**
 * @description Quét mã để tải ảnh lên
 */
export function scanUpload(data) {
  return request({
    url: `image/scan_upload`,
    method: 'post',
    headers: {
      'content-type': 'multipart/form-data;',
    },
    data,
  });
}
/**
 * Tìm kiếm thực đơn
 */
export function menusSearch(data) {
  return request({
    url: `menusSearch`,
    method: 'post',
    data,
  });
}

/**
 * PCCấu hình menu đầu cuối
 * @param {*} data
 * @returns
 */
export function pcHomeMenusSave(data) {
  return request({
    url: `setting/group_data/save_all`,
    method: 'post',
    data,
  });
}

/**
 * Nhận cấu hình menu PC
 * @param {*} data
 * @returns
 */
export function pcHomeMenus(name) {
  return request({
    url: `setting/group_data?config_name=${name}`,
    method: 'get',
  });
}

/**
 * Danh sách máy in
 * @param {*} type
 * @returns
 */
export function printList(data) {
  return request({
    url: `/system/ticket/list`,
    method: 'get',
    params: data,
  });
}

/**
 * Tạo máy in
 * @param {*} type
 * @returns
 */
export function printForm(id) {
  return request({
    url: `/system/ticket/form/${id}`,
    method: 'get',
  });
}
/**
 * Chuyển đổi trạng thái máy in
 * @param {*} type
 * @returns
 */
export function printSetStatus(data) {
  return request({
    url: `/system/ticket/set_status/${data.id}/${data.status}`,
    method: 'post',
  });
}

/**
 * Lưu cấu hình hóa đơn
 * @returns
 */
export function printSaveContent(id, data) {
  return request({
    url: `/system/ticket/save_content/${id}`,
    method: 'post',
    data,
  });
}
/**
 * Nhận cấu hình hóa đơn
 */
export function printContent(id) {
  return request({
    url: `/system/ticket/content/${id}`,
    method: 'get',
  });
}

/**
 * Phân loại danh sách liên kết
 * @param {*} type
 * @returns
 */
export function diyLinkCategoryListApi() {
  return request({
    url: `/diy/link/category`,
    method: 'get',
  });
}
/**
 * @description Thêm/chỉnh sửa danh mục
 */
export function linkCategoryFormApi(cate_id, pid) {
  return request({
    url: `diy/link/category/form/${cate_id}/${pid}`,
    method: 'get',
  });
}
/**
 * @description danh sách
 */
export function linkListApi(data) {
  return request({
    url: `diy/link/list/${data.id}`,
    method: 'get',
    params: data,
  });
}
/**
 * @description Tạo/chỉnh sửa liên kết
 */
export function linkCreateApi(data) {
  return request({
    url: `diy/link/save/${data.id}`,
    method: 'post',
    data,
  });
}
