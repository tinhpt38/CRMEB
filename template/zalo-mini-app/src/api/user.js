/**
 * User API — Thông tin cá nhân, địa chỉ, nạp tiền, hoa hồng, tích điểm
 */

import request from '@/utils/request';

// ─── Thông tin cá nhân ───────────────────────────────────────────────────────

/** Lấy thông tin người dùng hiện tại */
export function getUserInfo() {
  return request.get('user');
}

/**
 * Cập nhật thông tin cá nhân
 * @param {object} data - {nickname, avatar, birthday, ...}
 */
export function updateUserInfo(data) {
  return request.post('user/edit', data);
}

/** Thống kê tài chính người dùng (số dư, tích điểm...) */
export function getUserBalance() {
  return request.get('user/balance');
}

// ─── Địa chỉ giao hàng ───────────────────────────────────────────────────────

/** Danh sách địa chỉ */
export function getAddressList() {
  return request.get('address/list');
}

/** Địa chỉ mặc định */
export function getDefaultAddress() {
  return request.get('address/default');
}

/** Chi tiết địa chỉ */
export function getAddressDetail(id) {
  return request.get('address/detail/' + id);
}

/**
 * Thêm / cập nhật địa chỉ
 * @param {object} data
 * @param {string} data.realName    - Tên người nhận
 * @param {string} data.phone       - SĐT người nhận
 * @param {string} data.province    - Tỉnh/Thành phố
 * @param {string} data.city        - Quận/Huyện
 * @param {string} data.district    - Phường/Xã
 * @param {string} data.detail      - Địa chỉ chi tiết
 * @param {number} data.isDefault   - 1 = đặt làm mặc định
 * @param {number} [data.id]        - Truyền nếu cập nhật
 */
export function saveAddress(data) {
  return request.post('address/edit', data);
}

/**
 * Xóa địa chỉ
 * @param {number} id
 */
export function deleteAddress(id) {
  return request.post('address/del', { id });
}

// ─── Lịch sử giao dịch (ví) ──────────────────────────────────────────────────

/**
 * Lịch sử giao dịch trong ví
 * @param {object} params
 * @param {number} params.type  - 0: tất cả, 1: thu, 2: chi
 * @param {number} params.page
 * @param {number} params.limit
 */
export function getBillList(params = {}) {
  return request.get('user/bill/list', params);
}

// ─── Tích điểm ───────────────────────────────────────────────────────────────

/** Lịch sử tích điểm */
export function getIntegralList(params = {}) {
  return request.get('user/integral/list', params);
}

/** Điểm tích lũy hiện tại */
export function getIntegral() {
  return request.get('user/integral');
}

// ─── Nạp tiền ────────────────────────────────────────────────────────────────

/** Cấu hình nạp tiền (các mệnh giá gợi ý) */
export function getRechargeConfig() {
  return request.get('recharge/config');
}

/**
 * Tạo yêu cầu nạp tiền
 * @param {object} data
 * @param {number} data.price   - Số tiền
 * @param {string} data.payType - Phương thức thanh toán
 */
export function createRecharge(data) {
  return request.post('recharge/create', data);
}

// ─── Hoa hồng / Giới thiệu ───────────────────────────────────────────────────

/** Thông tin giới thiệu (link, mã, thống kê) */
export function getSpreadInfo() {
  return request.get('user/spread');
}

/** Danh sách người được giới thiệu */
export function getSpreadList(params = {}) {
  return request.get('user/spread/list', params);
}

/** Lịch sử hoa hồng */
export function getBrokerageList(params = {}) {
  return request.get('user/brokerage/list', params);
}

/** Yêu cầu rút hoa hồng */
export function applyExtract(data) {
  return request.post('user/extract/cash', data);
}

/** Cấu hình rút hoa hồng (số dư tối thiểu, tài khoản ngân hàng...) */
export function getExtractConfig() {
  return request.get('user/extract/config');
}

// ─── Thông báo ────────────────────────────────────────────────────────────────

/** Danh sách thông báo hệ thống */
export function getNotifications(params = {}) {
  return request.get('message/system/list', params);
}

/** Số thông báo chưa đọc */
export function getUnreadCount() {
  return request.get('message/system/count');
}

// ─── Cấp độ thành viên ───────────────────────────────────────────────────────

/** Thông tin cấp độ thành viên hiện tại */
export function getMemberLevel() {
  return request.get('user/level');
}

/** Danh sách các cấp độ thành viên */
export function getLevelList() {
  return request.get('user/level/list', {}, { noAuth: true });
}
