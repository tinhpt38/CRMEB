/**
 * Activity API — Flash sale, combo, điểm tích lũy đổi quà, mã giảm giá
 */

import request from '@/utils/request';

// ─── Flash sale (seckill) ─────────────────────────────────────────────────────

/**
 * Danh sách sản phẩm flash sale theo giờ
 * @param {object} params - {time, page, limit}
 */
export function getSeckillList(params = {}) {
  return request.get('seckill/list', params, { noAuth: true });
}

/** Chi tiết sản phẩm flash sale */
export function getSeckillDetail(id) {
  return request.get('seckill/detail/' + id, {}, { noAuth: true });
}

/** Danh sách khung giờ flash sale */
export function getSeckillTime() {
  return request.get('seckill/time', {}, { noAuth: true });
}

// ─── Combo (combination) ──────────────────────────────────────────────────────

/** Danh sách combo */
export function getCombinationList(params = {}) {
  return request.get('combination/list', params, { noAuth: true });
}

/** Chi tiết combo */
export function getCombinationDetail(id) {
  return request.get('combination/detail/' + id, {}, { noAuth: true });
}

/**
 * Tham gia nhóm mua combo
 * @param {object} data - {combinationId, pinkId (0=tạo nhóm mới, ID=vào nhóm có sẵn)}
 */
export function joinCombination(data) {
  return request.post('combination/add', data);
}

/** Danh sách nhóm đang mở (để vào nhóm có sẵn) */
export function getCombinationPink(id, params = {}) {
  return request.get('combination/pink/' + id, params, { noAuth: true });
}

// ─── Mặc cả (bargain) ────────────────────────────────────────────────────────

/** Danh sách sản phẩm đang mặc cả */
export function getBargainList(params = {}) {
  return request.get('bargain/list', params, { noAuth: true });
}

/** Chi tiết sản phẩm mặc cả */
export function getBargainDetail(id) {
  return request.get('bargain/detail/' + id, {}, { noAuth: true });
}

/**
 * Bắt đầu mặc cả
 * @param {number} bargainId
 */
export function startBargain(bargainId) {
  return request.post('bargain/create', { bargainId });
}

/**
 * Tham gia trợ giá cho bạn bè
 * @param {object} data - {bargainId, bargainUserTableId}
 */
export function helpBargain(data) {
  return request.post('bargain/help', data);
}

// ─── Điểm tích lũy đổi quà ───────────────────────────────────────────────────

/** Danh sách sản phẩm đổi điểm */
export function getIntegralProductList(params = {}) {
  return request.get('integral/list', params, { noAuth: true });
}

/** Chi tiết sản phẩm đổi điểm */
export function getIntegralProductDetail(id) {
  return request.get('integral/detail/' + id, {}, { noAuth: true });
}

// ─── Sản phẩm đặt trước (advance) ────────────────────────────────────────────

/** Danh sách sản phẩm đặt trước */
export function getAdvanceList(params = {}) {
  return request.get('advance/list', params, { noAuth: true });
}

/** Chi tiết sản phẩm đặt trước */
export function getAdvanceDetail(id) {
  return request.get('advance/detail/' + id, {}, { noAuth: true });
}
