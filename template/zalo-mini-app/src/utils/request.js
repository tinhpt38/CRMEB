/**
 * HTTP Request Wrapper cho Zalo Mini App ↔ CRMEB
 *
 * Đặc điểm:
 *  - Tự động gắn Authorization header từ token đã lưu
 *  - Tự động chuyển hướng về trang login khi token hết hạn (code 401)
 *  - Xử lý thống nhất code lỗi trả về từ CRMEB
 *  - Dùng Zalo Mini App SDK (zmp) cho storage, không cần localStorage
 *
 * Cài đặt:
 *  import request from '@/utils/request';
 */

import { navigateTo } from 'zmp-ui';

// ─── Cấu hình ────────────────────────────────────────────────────────────────

/** URL gốc của backend CRMEB - thay bằng domain thực tế của bạn */
const BASE_URL = 'https://your-crmeb-domain.com/api';

/** Tên header Authorization (giống uni-app của CRMEB) */
const TOKEN_NAME = 'Authori-zation';

/** Timeout mặc định (ms) */
const TIMEOUT = 30000;

// ─── Storage helpers (Zalo Mini App dùng zmp storage) ────────────────────────

export function getToken() {
  try {
    return zmp.getStorageSync({ key: 'crmeb_token' }).data || '';
  } catch {
    return '';
  }
}

export function setToken(token) {
  zmp.setStorageSync({ key: 'crmeb_token', data: token });
}

export function removeToken() {
  zmp.removeStorageSync({ key: 'crmeb_token' });
}

// ─── Xử lý phản hồi lỗi chuẩn CRMEB ─────────────────────────────────────────

function handleError(res) {
  const { status, msg } = res;

  if (status === 401) {
    // Token hết hạn → xóa token, chuyển về trang login
    removeToken();
    navigateTo({ url: '/pages/login/index' });
    return Promise.reject({ status, msg: msg || 'Phiên đăng nhập hết hạn' });
  }

  if (status === 402) {
    // Cảnh báo từ hệ thống (modal)
    zmp.showModal({
      title: 'Thông báo',
      content: msg || 'Có lỗi xảy ra',
      showCancel: false,
      confirmText: 'Đã hiểu',
    });
    return Promise.reject({ status, msg });
  }

  return Promise.reject(msg || 'Lỗi hệ thống');
}

// ─── Hàm request cốt lõi ─────────────────────────────────────────────────────

/**
 * @param {string}  url      - Path API (không có /api/ prefix), vd: 'product/list'
 * @param {string}  method   - 'GET' | 'POST' | 'PUT' | 'DELETE'
 * @param {object}  data     - Body hoặc query params
 * @param {object}  options
 * @param {boolean} options.noAuth   - true = không cần token (trang công khai)
 * @param {boolean} options.noVerify - true = không kiểm tra code phản hồi
 */
function baseRequest(url, method, data = {}, { noAuth = false, noVerify = false } = {}) {
  const token = getToken();

  if (!noAuth && !token) {
    navigateTo({ url: '/pages/login/index' });
    return Promise.reject({ msg: 'Chưa đăng nhập' });
  }

  const header = {
    'Content-Type': 'application/json',
  };
  if (token) {
    header[TOKEN_NAME] = 'Bearer ' + token;
  }

  return new Promise((resolve, reject) => {
    zmp.request({
      url: `${BASE_URL}/${url}`,
      method: method.toUpperCase(),
      header,
      data,
      timeout: TIMEOUT,
      success(res) {
        const body = res.data;

        if (noVerify) {
          return resolve(body);
        }

        // CRMEB trả về status 200 = thành công
        if (body && body.status === 200) {
          return resolve(body.data, body);
        }

        reject(handleError(body || {}));
      },
      fail(err) {
        reject('Kết nối thất bại, vui lòng kiểm tra mạng');
      },
    });
  });
}

// ─── Export shorthand methods ─────────────────────────────────────────────────

const request = {};

['get', 'post', 'put', 'delete'].forEach((method) => {
  request[method] = (url, data, opts) => baseRequest(url, method, data, opts || {});
});

export default request;
