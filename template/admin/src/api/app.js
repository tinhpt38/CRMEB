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
 * @description Thông báo mẫu chương trình nhỏ - danh sách
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function routineListApi(data) {
  return request({
    url: 'app/routine',
    method: 'get',
    params: data,
  });
}

/**
 * @description  Đồng bộ hóa tin nhắn đăng ký
 */
export function routineSyncTemplate() {
  return request({
    url: `app/routine/syncSubscribe`,
    method: 'GET',
  });
}

/**
 * @description  Đồng bộ hóa tin nhắn mẫu WeChat
 */
export function wechatSyncTemplate() {
  return request({
    url: `app/wechat/syncSubscribe`,
    method: 'GET',
  });
}

/**
 * @description Tin nhắn mẫu chương trình nhỏ -- Thêm biểu mẫu mới
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function routineCreateApi() {
  return request({
    url: 'app/routine/create',
    method: 'get',
  });
}

/**
 * @description Thông báo mẫu chương trình nhỏ - chỉnh sửa biểu mẫu
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function routineEditApi(id) {
  return request({
    url: `app/routine/${id}/edit`,
    method: 'get',
  });
}

/**
 * @description Thông báo mẫu chương trình nhỏ - sửa đổi trạng thái
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function routineSetStatusApi(data) {
  return request({
    url: `app/routine/set_status/${data.id}/${data.status}`,
    method: 'PUT',
  });
}

/**
 * @description Tài khoản chính thức -- Cấu hình tài khoản chính thức -- Menu WeChat
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function wechatMenuApi(data) {
  return request({
    url: `app/wechat/menu`,
    method: 'get',
  });
}

/**
 * @description Tài khoản chính thức -- Cấu hình tài khoản chính thức -- Gửi menu WeChat
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function MenuApi(data) {
  return request({
    url: `app/wechat/menu`,
    method: 'post',
    data,
  });
}

/**
 * @description Tin nhắn mẫu WeChat - danh sách
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function wechatListApi(data) {
  return request({
    url: 'app/wechat/template',
    method: 'get',
    params: data,
  });
}
/**
 * @description Tin nhắn mẫu WeChat - thêm biểu mẫu mới
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function wechatCreateApi() {
  return request({
    url: 'app/wechat/template/create',
    method: 'get',
  });
}

/**
 * @description Tin nhắn mẫu WeChat - chỉnh sửa biểu mẫu
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function wechatEditApi(id) {
  return request({
    url: `app/wechat/template/${id}/edit`,
    method: 'get',
  });
}

/**
 * @description Tin nhắn mẫu WeChat -- sửa đổi trạng thái
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function wechatSetStatusApi(data) {
  return request({
    url: `app/wechat/template/set_status/${data.id}/${data.status}`,
    method: 'PUT',
  });
}

/**
 * @description  Tự động trả lời -- Theo dõi Trả lời Từ khóa Trả lời Lưu
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function replyApi(data) {
  return request({
    url: data.url,
    method: 'post',
    data: data.key,
  });
}
/**
 * @description  Tải xuống gói chương trình nhỏ
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function routineDownload(data) {
  return request({
    url: 'app/routine/download',
    method: 'post',
    data,
  });
}
/**
 * @description  Dữ liệu trang tải xuống chương trình nhỏ
 */
export function routineInfo() {
  return request({
    url: 'app/routine/info',
    method: 'get',
  });
}

// ==================== Tải lên tự động CI chương trình nhỏ ====================

/**
 * @description Nhận trạng thái môi trường chạy CI của chương trình mini
 */
export function routineCIEnvironment() {
  return request({
    url: 'app/routine/ci/environment',
    method: 'get',
  });
}

/**
 * @description Nhận hướng dẫn cài đặt môi trường
 */
export function routineCIGuide() {
  return request({
    url: 'app/routine/ci/guide',
    method: 'get',
  });
}

/**
 * @description Nhận cấu hình tải lên chương trình nhỏ
 */
export function routineCIConfig() {
  return request({
    url: 'app/routine/ci/config',
    method: 'get',
  });
}

/**
 * @description Lưu khóa tải lên chương trình mini
 * @param {Object} data { key_content: Nội dung chính }
 */
export function routineCISaveKey(data) {
  return request({
    url: 'app/routine/ci/private_key',
    method: 'post',
    data,
  });
}

/**
 * @description Tải lên mã chương trình nhỏ
 * @param {Object} data { version: số phiên bản, desc: mô tả, is_live: Có bật phát sóng trực tiếp hay không }
 */
export function routineCIUpload(data) {
  return request({
    url: 'app/routine/ci/upload',
    method: 'post',
    data,
  });
}

/**
 * @description Nhận mã QR xem trước chương trình mini
 * @param {Object} data { page_path: Đường dẫn trang xem trước }
 */
export function routineCIPreview(data) {
  return request({
    url: 'app/routine/ci/preview',
    method: 'post',
    data,
  });
}

/**
 * @description  Trả lời tự động -- danh sách từ khóa
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function keywordListApi(params) {
  return request({
    url: `app/wechat/keyword`,
    method: 'get',
    params,
  });
}

/**
 * @description  Trả lời tự động - trạng thái sửa đổi từ khóa
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function keywordsetStatusApi(data) {
  return request({
    url: `app/wechat/keyword/set_status/${data.id}/${data.status}`,
    method: 'PUT',
  });
}

/**
 * @description  Trả lời tự động -- chi tiết
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function keywordsinfoApi(url, data) {
  return request({
    url: url,
    method: 'get',
    params: data.key,
  });
}

/**
 * @description  Quản lý đồ họa và văn bản -- mới
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function wechatNewsAddApi(data) {
  return request({
    url: `/app/wechat/news`,
    method: 'POST',
    data,
  });
}

/**
 * @description  Quản lý đồ họa và văn bản -- danh sách
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function wechatNewsListApi(params) {
  return request({
    url: `app/wechat/news`,
    method: 'GET',
    params,
  });
}

/**
 * @description  Quản lý đồ họa và văn bản -- chi tiết
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function wechatNewsInfotApi(id) {
  return request({
    url: `app/wechat/news/${id}`,
    method: 'GET',
  });
}

/**
 * @description  Quản lý hình ảnh và văn bản - gửi hình ảnh và văn bản
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function wechatPushApi(data) {
  return request({
    url: `app/wechat/push`,
    method: 'POST',
    data,
  });
}

/**
 * @description  Người dùng WeChat -- danh sách
 * @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function wechatUserListtApi(params) {
  return request({
    url: `app/wechat/user`,
    method: 'GET',
    params,
  });
}

/**
 * @description  Người dùng WeChat -- nhóm người dùng và thẻ
 */
export function tagListtApi() {
  return request({
    url: `app/wechat/user/tag_group`,
    method: 'GET',
  });
}

/**
 * @description  Người dùng WeChat -- nhóm người dùng và chỉnh sửa nhãn
 * @param {String} param url {String} Địa chỉ yêu cầu
 */
export function groupsEditApi(url) {
  return request({
    url: url,
    method: 'GET',
  });
}

/**
 * @description  Thẻ người dùng -- danh sách
 */
export function wechatTagListApi() {
  return request({
    url: `app/wechat/tag`,
    method: 'GET',
  });
}

/**
 * @description  Thẻ người dùng -- thêm biểu mẫu
 */
export function wechatTagCreateApi() {
  return request({
    url: `app/wechat/tag/create`,
    method: 'GET',
  });
}

/**
 * @description  Thẻ người dùng - chỉnh sửa biểu mẫu
 *  @param {Number} param id {Number} Nhãnid
 */
export function wechatTagEditApi(id) {
  return request({
    url: `app/wechat/tag/${id}/edit`,
    method: 'GET',
  });
}

/**
 * @description  Nhóm người dùng - danh sách
 */
export function wechatGroupListApi() {
  return request({
    url: `app/wechat/group`,
    method: 'GET',
  });
}

/**
 * @description  Nhóm người dùng - thêm biểu mẫu
 */
export function wechatGroupCreateApi() {
  return request({
    url: `app/wechat/group/create`,
    method: 'GET',
  });
}

/**
 * @description  Nhóm người dùng - chỉnh sửa biểu mẫu
 *  @param {Number} param id {Number} Nhãnid
 */
export function wechatGroupEditApi(id) {
  return request({
    url: `app/wechat/group/${id}/edit`,
    method: 'GET',
  });
}

/**
 * @description  Hành vi người dùng -- danh sách
 */
export function wechatActionListApi(params) {
  return request({
    url: `app/wechat/action`,
    method: 'GET',
    params,
  });
}

/**
 * Tải xuống mã QR
 * @param id
 */
export function downloadReplyCode(id) {
  return request({
    url: `app/wechat/code_reply/${id}`,
    method: 'GET',
  });
}

/**
 * Danh sách thành phố
 */
export function cityList() {
  return request({
    url: `setting/city/full_list`,
    method: 'GET',
  });
}

/**
 * @description  Dịch vụ khách hàng trả lời tự động -- danh sách từ khóa
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function kefuAutoReplyListApi(params) {
  return request({
    url: `app/kefu/auto_reply/list`,
    method: 'get',
    params,
  });
}

/**
 * @description  Dịch vụ khách hàng trả lời tự động để thêm biểu mẫu chỉnh sửa
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function kefuAutoReplyForm(id) {
  return request({
    url: `app/kefu/auto_reply/form/` + id,
    method: 'get',
  });
}

/**
 * @description Liên kết chương trình nhỏ - danh sách
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function routineSchemeList(data) {
  return request({
    url: 'app/routine/scheme_list',
    method: 'get',
    params: data,
  });
}

/**
 * @description Liên kết chương trình nhỏ -- Tạo và sửa đổi biểu mẫu
 * @param {Number} param id {Number} Nhãnid
 */
export function routineSchemeForm(id) {
  return request({
    url: `app/routine/scheme_form/${id}`,
    method: 'get',
  });
}

/**
 * @description Liên kết chương trình nhỏ - xóa
 * @param {Number} param id {Number} Nhãnid
 */
export function routineSchemeDel(id) {
  return request({
    url: `app/routine/scheme_del/${id}`,
    method: 'delete',
  });
}
