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
 * @description Quản lý người dùng--danh sách
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function userList(data) {
  return request({
    url: 'user/user',
    method: 'get',
    params: data,
  });
}

/**
 * @description Chỉnh sửa dữ liệu biểu mẫu
 * @param {Number} param id {Number} thành viênid
 */
export function getUserData(id) {
  return request({
    url: `user/user/${id}/edit`,
    method: 'get',
  });
}

/**
 * @description công tắc
 * @param {Number} param id {Number}
 */
export function memberCard(data) {
  return request({
    url: `user/member_ship/set_ship_status`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Chuyển đổi danh sách thành viên
 * @param {Number} param id {Number}
 */
export function memberCardStatus(data) {
  return request({
    url: `user/member_card/set_status`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Màn hình sửa đổi quản lý thành viên
 * @param {Object} param data {Object} Giá trị trạng thái đến, người dùngid
 */
export function isShowApi(data) {
  return request({
    url: `user/set_status/${data.status}/${data.id}`,
    method: 'put',
  });
}

/**
 * @description Danh sách phiếu giảm giá
 * @param {Object} param params {Object} Giá trị vượt qua
 */
export function couponApi(params) {
  return request({
    url: `marketing/coupon/grant`,
    method: 'get',
    params,
  });
}

/**
 * @description Gửi phiếu giảm giá
 * @param {Object} param data {Object} Giá trị vượt qua
 */
export function sendCouponApi(data) {
  return request({
    url: `marketing/coupon/user/grant`,
    method: 'POST',
    data,
  });
}

/**
 * @description Sửa đổi biểu mẫu cân bằng điểm
 * @param {Number} param id {Number} người dùngid
 */
export function editOtherApi(id, type) {
  return request({
    url: `user/edit_other/${id}/${type}`,
    method: 'get',
  });
}

/**
 * @description Quản lý thành viên-Chi tiết
 * @param {Number} param id {Number} người dùngid
 */
export function detailsApi(id) {
  return request({
    url: `user/user/${id}`,
    method: 'get',
  });
}

/**
 * @description Tùy chọn tab trong chi tiết quản lý thành viên
 * @param {Number} param id {Number} người dùngid
 */
export function infoApi(data) {
  return request({
    url: `user/one_info/${data.id}`,
    method: 'get',
    params: data.datas,
  });
}

/**
 * @description Cấp độ thành viên - danh sách
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function levelListApi(data) {
  return request({
    url: 'user/user_level/vip_list',
    method: 'get',
    params: data,
  });
}

/**
 * @description Cấp độ thành viên-Biểu mẫu chỉnh sửa
 * @param {Number} param id {Number} Cấp độ thành viênid
 */
export function levelEditApi(id) {
  return request({
    url: `user/user_level/set_value/${id}`,
    method: 'PUT',
  });
}

/**
 * @description Cấp độ thành viên-Sửa đổi Hiển thị Ẩn
 * @param {Number} param id {Number} Cấp độ thành viênid
 */
export function setShowApi(data) {
  return request({
    url: `user/user_level/set_show/${data.id}/${data.is_show}`,
    method: 'PUT',
  });
}

/**
 * @description Cấp độ thành viên-Biểu mẫu chỉnh sửa
 * @param {Number} param id {Number} Cấp độ thành viênid
 */
// export function addApi (data) {
//     return request({
//         url: 'user/user_level',
//         method: 'post',
//         data
//     });
// }

/**
 * @description Danh sách nhiệm vụ cấp thành viên
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function taskListApi(id, data) {
  return request({
    url: `user/user_level/task/${id}`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Nhiệm vụ cấp thành viên-sửa đổi hiển thị và ẩn
 * @param {Number} param data.id {Number} Nhiệm vụ cấp thành viênid
 * @param {Number} param data.is_show {Number} Nhiệm vụ cấp thành viên hiển thị và ẩn
 */
export function setTaskShowApi(data) {
  return request({
    url: `user/user_level/set_task_show/${data.id}/${data.is_show}`,
    method: 'PUT',
  });
}

/**
 * @description Nhiệm vụ cấp thành viên - liệu nhiệm vụ có được hoàn thành hay không
 * @param {Number} param data.id {Number} Nhiệm vụ cấp thành viênid
 * @param {Number} param data.is_must {Number} Có cần thiết phải hoàn thành nhiệm vụ cấp thành viên không?
 */
export function setTaskMustApi(data) {
  return request({
    url: `user/user_level/set_task_must/${data.id}/${data.is_must}`,
    method: 'PUT',
  });
}

/**
 * @description Nhiệm vụ cấp thành viên-Biểu mẫu chỉnh sửa biểu mẫu mới
 * @param {Object} param data {Object} Giá trị truyền đối tượng nhiệm vụ cấp thành viên
 */
export function createTaskApi(data) {
  return request({
    url: `/user/user_level/create_task`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Cấp độ thành viên-Tạo biểu mẫu
 * @param {Object} param data {Object} Giá trị truyền đối tượng nhiệm vụ cấp thành viên
 */
export function createApi(id) {
  return request({
    url: `user/user_level/create`,
    method: 'get',
    params: id,
  });
}

/**
 * @description Quản lý thành viên --- Cấp độ thành viên miễn phí
 * @param {Number} param id {Number} thành viênid
 */
export function giveLevelApi(id) {
  return request({
    url: `user/give_level/${id}`,
    method: 'get',
  });
}

/**
 * @description Quản lý thành viên --- Thời gian thành viên miễn phí
 * @param {Number} param id {Number} thành viênid
 */
export function giveLevelTimeApi(id) {
  return request({
    url: `user/give_level_time/${id}`,
    method: 'get',
  });
}

/**
 * @description Cấp độ thành viên-Xóa
 * @param {Number} param id {Number} Cấp độ thành viênid
 */
export function delLevelApi(id) {
  return request({
    url: `user/user_level/delete/${id}`,
    method: 'PUT',
  });
}

/**
 * @description Danh sách nhóm thành viên
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function userGroupApi(data) {
  return request({
    url: 'user/user_group/list',
    method: 'get',
    params: data,
  });
}

/**
 * @description Xóa thành viên --- Xóa nhóm
 * @param {Number} param id {Number} thành viênid
 */
export function groupDelApi(id) {
  return request({
    url: `user/user_group/del/${id}`,
    method: 'DELETE',
  });
}

/**
 * @description Thành viên Thêm biểu mẫu/Xóa biểu mẫu --- Biểu mẫu
 * @param {Number} param id {Number} thành viênid
 */
export function groupAddApi(id) {
  return request({
    url: `user/user_group/add/${id}`,
    method: 'get',
  });
}

/**
 * @description Trung tâm cá nhân --- Thay đổi mật khẩu
 * tham số yêu cầu dữ liệu
 */
export function updtaeAdmin(data) {
  return request({
    url: `setting/update_admin`,
    method: 'PUT',
    data,
  });
}
/**
 * @description Quản lý tập tin --- Đặt mật khẩu
 * tham số yêu cầu dữ liệu
 */
export function setFilePassword(data) {
  return request({
    url: `setting/set_file_password`,
    method: 'PUT',
    data,
  });
}

/**
 * @description Trung tâm cá nhân --- Đặt cấp độ thành viên
 * tham số yêu cầu dữ liệu
 */
export function userSetGroup(data) {
  return request({
    url: `user/set_group`,
    method: 'post',
    data,
  });
}

/**
 * @description Trung tâm cá nhân --- Danh sách thẻ thành viên
 * tham số yêu cầu dữ liệu
 */
export function userLabelApi(data) {
  return request({
    url: `user/user_label`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Nhận phân loại thẻ (tất cả)
 * tham số yêu cầu dữ liệu
 */
export function userLabelAll(data) {
  return request({
    url: `user/user_label_cate/all`,
    method: 'get',
    params: data,
  });
}

/**
 * Thêm người dùng
 */
export function getUserSaveForm() {
  return request({
    url: `/user/user/create`,
    method: 'get',
  });
}

/**
 * Đồng bộ hóa người dùng
 */
export function userSynchro() {
  return request({
    url: `/user/user/syncUsers`,
    method: 'get',
  });
}

/**
 * @description Nhận biểu mẫu chỉnh sửa phân loại nhãn người dùng
 * tham số yêu cầu dữ liệu
 */
export function userLabelEdit(id) {
  return request({
    url: `user/user_label_cate/${id}/edit`,
    method: 'get',
  });
}

/**
 * @description Nhận biểu mẫu tạo phân loại thẻ người dùng
 * tham số yêu cầu dữ liệu
 */
export function userLabelCreate(id) {
  return request({
    url: `user/user_label_cate/create`,
    method: 'get',
  });
}

/**
 * @description Trung tâm cá nhân --- Tạo biểu mẫu thẻ thành viên
 * tham số yêu cầu dữ liệu
 */
export function userLabelAddApi(id, cate_id) {
  return request({
    url: `user/user_label/add/${id}?cate_id=${cate_id ? cate_id : 0}`,
    method: 'get',
  });
}

/**
 * @description Trung tâm cá nhân --- Lấy biểu mẫu để đặt thẻ thành viên
 * tham số yêu cầu dữ liệu
 */
export function userSetLabelApi(data) {
  return request({
    url: `user/set_label`,
    method: 'post',
    data,
  });
}

/**
 * Danh sách thẻ hàng loạt
 */
export function userMemberBatch(data) {
  return request({
    url: '/user/member_batch/index',
    method: 'get',
    params: data,
  });
}

/**
 * Tạo thẻ hàng loạt
 * @param {*} id id
 */
export function memberBatchSave(id, data) {
  return request({
    url: `/user/member_batch/save/${id}`,
    method: 'post',
    data,
  });
}

/**
 * Danh sách các thao tác (cho phép, sửa đổi tên）
 * @param {*} id id
 */
export function memberBatchSetValue(id, data) {
  return request({
    url: `/user/member_batch/set_value/${id}`,
    method: 'get',
    params: data,
  });
}

/**
 * Danh sách thẻ thành viên
 * @param {*} id id
 */
export function userMemberCard(id, data) {
  return request({
    url: `/user/member_card/index/${id}`,
    method: 'get',
    params: data,
  });
}

/**
 * Xuất thẻ thành viên
 * @param {*} id id
 */
export function exportMemberCard(id) {
  return request({
    url: `/export/memberCard/${id}`,
    method: 'get',
  });
}

/**
 * Loại thành viên
 */
export function userMemberShip() {
  return request({
    url: '/user/member/ship',
    method: 'get',
  });
}

/**
 * Chỉnh sửa loại thành viên
 * @param {*} id id
 * @param {*} data data
 */
export function memberShipSave(id, data) {
  return request({
    url: `/user/member_ship/save/${id}`,
    method: 'post',
    data,
  });
}

/**
 * Đổi mã QR thẻ thành viên
 */
export function userMemberScan() {
  return request({
    url: '/user/member_scan',
    method: 'get',
  });
}

/**
 * Hồ sơ thẻ thành viên
 */
export function memberRecord(data) {
  return request({
    url: '/user/member/record',
    method: 'get',
    params: data,
  });
}

/**
 * Quyền thành viên
 */
export function memberRight() {
  return request({
    url: 'user/member/right',
    method: 'get',
  });
}

/**
 * Biên tập quyền thành viên
 * @param {*} data
 */
export function memberRightSave(data) {
  return request({
    url: `user/member_right/save/${data.id}`,
    method: 'post',
    data,
  });
}

/**
 * Chỉnh sửa Thỏa thuận thành viên
 * @param {*} id
 */
export function memberAgreementSave(id, data) {
  return request({
    url: `user/member_agreement/save/${id}`,
    method: 'post',
    data,
  });
}

/**
 * Thỏa thuận thành viên
 */
export function memberAgreement() {
  return request({
    url: `user/member/agreement`,
    method: 'get',
  });
}
/**
 * Thỏa thuận ứng dụng đại lý
 */
export function agentAgreement() {
  return request({
    url: `agent/division/agent_agreement/info`,
    method: 'get',
  });
}

/**
 * Thỏa thuận bảo quản cơ quan
 * @param {*} id
 */
export function agentAgreementSave(data) {
  return request({
    url: `agent/division/agent_agreement/save`,
    method: 'post',
    data,
  });
}

/**
 * Nhận thẻ người dùng
 */
export function getUserLabel(uid) {
  return request({
    url: `user/label/${uid}`,
    method: 'get',
  });
}

/**
 * Đặt nhãn người dùng
 */
export function putUserLabel(uid, data) {
  return request({
    url: `user/label/${uid}`,
    method: 'post',
    data,
  });
}

/**
 * @description Tạo người dùng
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function setUser(data) {
  return request({
    url: 'user/user',
    method: 'post',
    data,
  });
}

/**
 * @description Chỉnh sửa người dùng
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function editUser(data) {
  return request({
    url: 'user/user/' + data.uid,
    method: 'put',
    data,
  });
}
/**
 * @description Chỉnh sửa người dùng
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function saveSetLabel(data) {
  return request({
    url: 'user/save_set_label',
    method: 'put',
    data,
  });
}

/**
 * Lấy thông tin người dùng
 */
export function getUserInfo(uid) {
  return request({
    url: `user/user/user_save_info/${uid}`,
    method: 'get',
  });
}

/**
 * Danh sách đăng xuất của người dùng
 */
export function userCancelList(data) {
  return request({
    url: '/user/cancel_list',
    method: 'get',
    params: data,
  });
}
/**
 * Danh sách đăng xuất của người dùng
 */
export function userCancelSetMark(data) {
  return request({
    url: '/user/cancel/set_mark',
    method: 'post',
    data,
  });
}
