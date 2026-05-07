/**
 * Auth API — Zalo Mini App ↔ CRMEB
 *
 * Luồng đăng nhập Zalo:
 *  1. Gọi loginWithZalo() → lấy access_token từ Zalo SDK
 *  2. Gửi access_token lên CRMEB: POST /api/zalo/auth
 *  3. Nhận JWT token, lưu vào storage
 *  4. Các request sau dùng token này trong Authorization header
 *
 * Luồng gắn SĐT (nếu cần):
 *  1. Người dùng nhập SĐT
 *  2. Gọi sendOtp(phone) để nhận mã OTP
 *  3. Người dùng nhập OTP
 *  4. Gọi bindPhone({phone, captcha}) để gắn SĐT
 */

import request, { setToken, removeToken } from '@/utils/request';
import { getAccessToken } from 'zmp-sdk/apis';

// ─── Zalo OAuth ───────────────────────────────────────────────────────────────

/**
 * Đăng nhập bằng tài khoản Zalo
 *
 * Tự động:
 *  1. Lấy access_token từ Zalo SDK
 *  2. Gửi lên CRMEB để xác thực
 *  3. Lưu token vào storage
 *
 * @param {number} spread - UID người giới thiệu (tuỳ chọn)
 * @returns {Promise<{token, expires_time, userInfo}>}
 */
export async function loginWithZalo(spread = 0) {
  // Lấy access_token từ Zalo SDK
  const { accessToken } = await getAccessToken();

  if (!accessToken) {
    throw new Error('Không lấy được access_token từ Zalo');
  }

  // Gửi lên CRMEB
  const result = await request.post(
    'zalo/auth',
    { access_token: accessToken, spread },
    { noAuth: true }
  );

  // Lưu token để dùng cho các request sau
  setToken(result.token);

  return result;
}

/**
 * Đăng xuất — xóa token cục bộ
 */
export async function logout() {
  try {
    await request.get('logout');
  } finally {
    removeToken();
  }
}

// ─── SMS / OTP ────────────────────────────────────────────────────────────────

/**
 * Lấy key để gửi OTP (bước 1 của quy trình gửi SMS)
 * @returns {Promise<{key, expire_time}>}
 */
export function getSmsKey() {
  return request.get('verify_code', {}, { noAuth: true });
}

/**
 * Gửi mã OTP về số điện thoại
 *
 * @param {object} params
 * @param {string} params.phone    - Số điện thoại
 * @param {string} params.type     - 'register' | 'reset' | 'bind'
 * @param {string} params.key      - Key lấy từ getSmsKey()
 * @returns {Promise}
 */
export function sendOtp({ phone, type = 'bind', key }) {
  return request.post('register/verify', { phone, type, key }, { noAuth: true });
}

// ─── Gắn số điện thoại ───────────────────────────────────────────────────────

/**
 * Gắn số điện thoại cho tài khoản Zalo đang đăng nhập
 *
 * Yêu cầu đã đăng nhập (có Bearer token).
 *
 * @param {object} params
 * @param {string} params.phone   - Số điện thoại
 * @param {string} params.captcha - Mã OTP
 * @returns {Promise}
 */
export function bindPhone({ phone, captcha }) {
  return request.post('zalo/bind_phone', { phone, captcha });
}

// ─── Đăng nhập bằng SĐT + mật khẩu (fallback) ───────────────────────────────

/**
 * Đăng nhập bằng tài khoản + mật khẩu (H5 mode, không dùng Zalo)
 * @param {object} data - {account, password}
 */
export function loginByPassword(data) {
  return request.post('login', data, { noAuth: true });
}

/**
 * Đăng nhập bằng SĐT + OTP
 * @param {object} data - {phone, captcha}
 */
export function loginByPhone(data) {
  return request.post('login/mobile', data, { noAuth: true });
}
