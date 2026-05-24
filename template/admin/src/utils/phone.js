/** Regex số di động Việt Nam: 0xxxxxxxxx hoặc +84/84xxxxxxxxx */
export const VN_PHONE_PATTERN = /^(?:\+84|84|0)(3|5|7|8|9)\d{8}$/;

export function normalizeVnPhone(val) {
  return String(val || '').replace(/\s+/g, '');
}

export function isValidVnPhone(val) {
  return VN_PHONE_PATTERN.test(normalizeVnPhone(val));
}

export function vnPhoneRule(message = 'Định dạng số điện thoại di động không chính xác') {
  return {
    validator: (_rule, value, callback) => {
      if (value === undefined || value === null || value === '') {
        callback();
        return;
      }
      if (isValidVnPhone(value)) {
        callback();
        return;
      }
      callback(new Error(message));
    },
    trigger: 'blur',
  };
}
