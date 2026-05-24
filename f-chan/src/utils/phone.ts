/** Khớp regex backend `AddressValidate.php` (Phase 1.2). */
const VN_MOBILE_REGEX =
  /^(?:\+84|84|0)(3|5|7|8|9)\d{8}$|^0\d{1,3}-?\d{7,8}$/;

export function normalizeVnPhone(input: string): string {
  return input.replace(/[\s.-]/g, "").trim();
}

export function isValidVnPhone(input: string): boolean {
  const normalized = normalizeVnPhone(input);
  if (!normalized) return false;
  return VN_MOBILE_REGEX.test(normalized);
}

export const VN_PHONE_ERROR =
  "Số điện thoại không hợp lệ. Vui lòng nhập số di động Việt Nam (vd: 0901234567).";
