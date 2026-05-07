/**
 * Cấu hình Zalo Mini App ↔ CRMEB
 *
 * Chỉnh sửa file này trước khi deploy.
 */

/** URL backend CRMEB của bạn (không có dấu / cuối) */
export const BASE_URL = 'https://your-crmeb-domain.com';

/** Tên header token — phải khớp với CRMEB backend */
export const TOKEN_NAME = 'Authori-zation';

/** Thời gian timeout HTTP (ms) */
export const TIMEOUT = 30_000;

/** Key lưu token trong Zalo Mini App storage */
export const TOKEN_STORAGE_KEY = 'crmeb_token';

/** Key lưu thông tin user */
export const USER_STORAGE_KEY = 'crmeb_user';
