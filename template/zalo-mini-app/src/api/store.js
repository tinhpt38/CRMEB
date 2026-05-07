/**
 * Store API — Sản phẩm, giỏ hàng, yêu thích
 */

import request from '@/utils/request';

// ─── Sản phẩm ────────────────────────────────────────────────────────────────

/**
 * Danh sách sản phẩm
 * @param {object} params
 * @param {number} params.sid        - ID danh mục con (0 = tất cả)
 * @param {number} params.cid        - ID danh mục cha (0 = tất cả)
 * @param {string} params.keyword    - Từ khóa tìm kiếm
 * @param {string} params.priceOrder - 'asc' | 'desc' | ''
 * @param {string} params.salesOrder - 'asc' | 'desc' | ''
 * @param {number} params.page       - Trang hiện tại
 * @param {number} params.limit      - Số phần tử mỗi trang
 */
export function getProductList(params = {}) {
  return request.get('product/list', params, { noAuth: true });
}

/**
 * Chi tiết sản phẩm
 * @param {number} id - ID sản phẩm
 */
export function getProductDetail(id) {
  return request.get('product/detail/' + id, {}, { noAuth: true });
}

/**
 * Đánh giá sản phẩm
 * @param {number} id     - ID sản phẩm
 * @param {number} type   - 0: tất cả, 1: có ảnh, 2: tốt, 3: trung bình, 4: tệ
 * @param {object} params - {page, limit}
 */
export function getProductReviews(id, type = 0, params = {}) {
  return request.get(`product/reply/list/${id}/${type}`, params, { noAuth: true });
}

/**
 * Số lượng đánh giá theo từng loại
 * @param {number} id - ID sản phẩm
 */
export function getProductReviewCount(id) {
  return request.get('product/reply/config/' + id, {}, { noAuth: true });
}

/**
 * Sản phẩm liên quan
 * @param {number} id - ID sản phẩm
 */
export function getRelatedProducts(id) {
  return request.get('product/related/' + id, {}, { noAuth: true });
}

// ─── Yêu thích ───────────────────────────────────────────────────────────────

/**
 * Thêm sản phẩm vào yêu thích
 * @param {number} id       - ID sản phẩm
 * @param {string} category - 'product' | 'product_seckill' | ...
 */
export function addToFavorites(id, category = 'product') {
  return request.post('collect/add', { id, product: category });
}

/**
 * Xóa sản phẩm khỏi yêu thích
 * @param {number|number[]} id - ID hoặc mảng ID
 * @param {string} category
 */
export function removeFromFavorites(id, category = 'product') {
  return request.post('collect/del', {
    id: Array.isArray(id) ? id.join(',') : id,
    product: category,
  });
}

/** Danh sách sản phẩm yêu thích của tôi */
export function getMyFavorites(params = {}) {
  return request.get('collect/user', params);
}

// ─── Giỏ hàng ────────────────────────────────────────────────────────────────

/**
 * Danh sách giỏ hàng
 * @param {number} numType - 0: đơn vị sản phẩm, 1: tổng số lượng
 */
export function getCart(numType = 0) {
  return request.get('cart/list', { numType });
}

/**
 * Thêm sản phẩm vào giỏ hàng
 * @param {object} data
 * @param {number} data.productId    - ID sản phẩm
 * @param {number} data.cartNum      - Số lượng
 * @param {string} data.uniqueId     - ID biến thể sản phẩm (nếu có)
 * @param {number} data.storeId      - ID cửa hàng (mặc định 0)
 * @param {number} data.combinationId - ID combo (nếu có)
 */
export function addToCart(data) {
  return request.post('cart/add', data);
}

/**
 * Cập nhật số lượng trong giỏ hàng
 * @param {number} cartId  - ID dòng giỏ hàng
 * @param {number} cartNum - Số lượng mới
 */
export function updateCart(cartId, cartNum) {
  return request.post('cart/update', { id: cartId, cartNum });
}

/**
 * Xóa sản phẩm khỏi giỏ hàng
 * @param {number|number[]} cartIds - ID hoặc mảng ID
 */
export function removeFromCart(cartIds) {
  return request.post('cart/del', {
    ids: Array.isArray(cartIds) ? cartIds.join(',') : cartIds,
  });
}

/** Số lượng sản phẩm trong giỏ hàng */
export function getCartCount() {
  return request.get('cart/count');
}

// ─── Phiếu giảm giá theo sản phẩm ───────────────────────────────────────────

/**
 * Danh sách phiếu giảm giá áp dụng cho sản phẩm
 * @param {number} productId
 */
export function getProductCoupons(productId) {
  return request.get('coupon/product/' + productId, {}, { noAuth: true });
}
