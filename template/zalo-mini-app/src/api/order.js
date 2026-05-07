/**
 * Order API — Đặt hàng, thanh toán, hoàn tiền
 */

import request from '@/utils/request';

// ─── Tạo đơn hàng ────────────────────────────────────────────────────────────

/**
 * Tính toán giá đơn hàng trước khi đặt
 * @param {object} data
 * @param {number[]} data.cartIds      - Danh sách ID giỏ hàng
 * @param {number}   data.addressId    - ID địa chỉ giao hàng
 * @param {number}   data.couponId     - ID phiếu giảm giá (0 = không dùng)
 * @param {number}   data.useIntegral  - 1 = dùng điểm tích lũy
 * @param {number}   data.shippingType - 1: giao hàng, 2: tự lấy
 */
export function computeOrder(data) {
  return request.post('order/computed', data);
}

/**
 * Tạo đơn hàng mới
 * @param {object} data - Tương tự computeOrder, thêm:
 * @param {string} data.payType - 'weixin' | 'yue' | 'offline'
 */
export function createOrder(data) {
  return request.post('order/create', data);
}

// ─── Thanh toán ───────────────────────────────────────────────────────────────

/**
 * Lấy cấu hình phương thức thanh toán
 */
export function getPayConfig() {
  return request.get('pay/config');
}

/**
 * Thanh toán đơn hàng
 * @param {object} data
 * @param {string} data.uni      - Mã đơn hàng (order_id)
 * @param {string} data.payType  - Phương thức thanh toán
 */
export function payOrder(data) {
  return request.post('order/pay', data);
}

// ─── Danh sách đơn hàng ──────────────────────────────────────────────────────

/**
 * Danh sách đơn hàng của tôi
 * @param {object} params
 * @param {number} params.type  - 0: tất cả, 1: chờ thanh toán, 2: chờ giao,
 *                                3: đang giao, 4: đã nhận, 5: đã đánh giá
 * @param {number} params.page
 * @param {number} params.limit
 */
export function getOrderList(params = {}) {
  return request.get('order/list', params);
}

/**
 * Chi tiết đơn hàng
 * @param {string} orderId - Mã đơn hàng
 */
export function getOrderDetail(orderId) {
  return request.get('order/detail/' + orderId);
}

/**
 * Số lượng đơn hàng theo trạng thái
 */
export function getOrderTabCount() {
  return request.get('order/data');
}

// ─── Xử lý đơn hàng ──────────────────────────────────────────────────────────

/**
 * Xác nhận đã nhận hàng
 * @param {string} orderId
 */
export function confirmReceive(orderId) {
  return request.post('order/take', { orderId });
}

/**
 * Hủy đơn hàng
 * @param {string} orderId
 */
export function cancelOrder(orderId) {
  return request.post('order/cancel', { orderId });
}

/**
 * Xóa đơn hàng (đã hoàn thành hoặc đã hủy)
 * @param {string} orderId
 */
export function deleteOrder(orderId) {
  return request.post('order/del', { orderId });
}

// ─── Đánh giá ────────────────────────────────────────────────────────────────

/**
 * Gửi đánh giá đơn hàng
 * @param {object} data
 * @param {string} data.unique     - unique code của sản phẩm trong đơn
 * @param {number} data.productId
 * @param {number} data.star       - 1-5
 * @param {string} data.comment
 * @param {string[]} data.pics     - Danh sách URL ảnh đính kèm
 */
export function submitReview(data) {
  return request.post('order/reply', data);
}

// ─── Hoàn tiền / Đổi trả ─────────────────────────────────────────────────────

/**
 * Yêu cầu hoàn tiền
 * @param {object} data
 * @param {string} data.orderId
 * @param {string} data.text      - Lý do hoàn tiền
 * @param {string[]} data.pics
 */
export function applyRefund(data) {
  return request.post('order/refund', data);
}

/**
 * Danh sách đơn hoàn tiền
 */
export function getRefundList(params = {}) {
  return request.get('order/refund/list', params);
}

/**
 * Chi tiết đơn hoàn tiền
 * @param {string} uni
 */
export function getRefundDetail(uni) {
  return request.get('order/refund/detail/' + uni);
}

// ─── Vận chuyển ───────────────────────────────────────────────────────────────

/**
 * Theo dõi vận chuyển đơn hàng
 * @param {string} uni    - unique của đơn hàng
 * @param {number} type   - 0: tất cả, 1: hình ảnh
 */
export function trackShipping(uni, type = 0) {
  return request.get(`order/express/${uni}/${type}`);
}
