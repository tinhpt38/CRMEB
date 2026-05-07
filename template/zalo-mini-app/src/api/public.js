/**
 * Public API — Trang chủ, cấu hình, bài viết
 * Tất cả endpoint đây đều không cần đăng nhập (noAuth: true)
 */

import request from '@/utils/request';

// ─── Cấu hình hệ thống ───────────────────────────────────────────────────────

/** Cấu hình cơ bản của Mall (tên shop, logo, liên hệ...) */
export function getMallBasicConfig() {
  return request.get('basic_config', {}, { noAuth: true });
}

/** Dữ liệu trang chủ (banner, danh mục nổi bật, sản phẩm...) */
export function getIndexData() {
  return request.get('v2/index', {}, { noAuth: true });
}

/** Thông tin DIY trang chủ */
export function getDiyPage(id) {
  return request.get(`v2/diy/get_diy/default${id ? '?id=' + id : ''}`, {}, { noAuth: true });
}

// ─── Danh mục ────────────────────────────────────────────────────────────────

/** Danh sách danh mục sản phẩm */
export function getCategories(data) {
  return request.get('category', data || {}, { noAuth: true });
}

// ─── Phiếu giảm giá ──────────────────────────────────────────────────────────

/** Danh sách phiếu giảm giá đang hoạt động */
export function getCoupons(data) {
  return request.get('v2/coupons', data || {}, { noAuth: true });
}

/** Phiếu giảm giá cho người mới */
export function getNewUserCoupon() {
  return request.get('v2/new_coupon', {}, { noAuth: true });
}

/** Nhận phiếu giảm giá (cần đăng nhập) */
export function receiveCoupon(couponId) {
  return request.post('coupon/receive', { couponId });
}

/** Phiếu giảm giá của tôi */
export function getMyCoupons(type) {
  return request.get('coupons/user/' + (type || 0));
}

// ─── Bài viết ────────────────────────────────────────────────────────────────

/** Danh mục bài viết */
export function getArticleCategories() {
  return request.get('article/category/list', {}, { noAuth: true });
}

/** Danh sách bài viết theo danh mục */
export function getArticles(cid, data) {
  return request.get('article/list/' + cid, data || {}, { noAuth: true });
}

/** Chi tiết bài viết */
export function getArticleDetail(id) {
  return request.get('article/details/' + id, {}, { noAuth: true });
}

/** Bài viết nổi bật */
export function getHotArticles() {
  return request.get('article/hot/list', {}, { noAuth: true });
}

// ─── Tìm kiếm ────────────────────────────────────────────────────────────────

/** Lịch sử tìm kiếm của người dùng */
export function getSearchHistory(data) {
  return request.get('v2/user/search_list', data || {}, { noAuth: true });
}

/** Xóa lịch sử tìm kiếm */
export function clearSearchHistory() {
  return request.get('v2/user/clean_search');
}

// ─── Thành phố / địa chỉ ─────────────────────────────────────────────────────

/** Danh sách tỉnh/thành phố */
export function getCityList() {
  return request.get('city_list', {}, { noAuth: true });
}

// ─── Upload ───────────────────────────────────────────────────────────────────

/** Upload ảnh lên CRMEB */
export function uploadImage(filePath) {
  return new Promise((resolve, reject) => {
    zmp.uploadFile({
      url: `${BASE_URL}/upload/image`,
      filePath,
      name: 'file',
      header: { [TOKEN_NAME]: 'Bearer ' + getToken() },
      success(res) {
        try {
          const data = JSON.parse(res.data);
          if (data.status === 200) resolve(data.data);
          else reject(data.msg || 'Upload thất bại');
        } catch {
          reject('Lỗi phân tích phản hồi upload');
        }
      },
      fail() {
        reject('Upload thất bại, vui lòng thử lại');
      },
    });
  });
}
