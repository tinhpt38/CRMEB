/**
 * 2020.11.29 lyt tổ chức
 * Bộ sưu tập các công cụ phù hợp cho việc phát triển hàng ngày
 */

/**
 * Tỷ lệ phần trăm xác minh (không cho phép số thập phân)
 * @param val chuỗi giá trị hiện tại
 * @returns trả về chuỗi đã xử lý
 */
export function verifyNumberPercentage(val) {
  // Khớp không gian
  let v = val.replace(/(^\s*)|(\s*$)/g, '');
  // Chỉ có thể là số và dấu thập phân, không thể là đầu vào khác
  v = v.replace(/[^\d]/g, '');
  // không thể bắt đầu bằng 0
  v = v.replace(/^0/g, '');
  // Nếu số vượt quá 100 thì gán giá trị lớn nhất100
  v = v.replace(/^[1-9]\d\d{1,3}$/, '100');
  // Trả về kết quả
  return v;
}

/**
 * Tỷ lệ xác minh (có thể là số thập phân)
 * @param val chuỗi giá trị hiện tại
 * @returns trả về chuỗi đã xử lý
 */
export function verifyNumberPercentageFloat(val) {
  let v = verifyNumberIntegerAndFloat(val);
  // Nếu số vượt quá 100 thì gán giá trị lớn nhất100
  v = v.replace(/^[1-9]\d\d{1,3}$/, '100');
  // Sẽ không có đầu vào nào nữa sau khi vượt quá 100
  v = v.replace(/^100\.$/, '100');
  // Trả về kết quả
  return v;
}

// số thập phân hoặc số nguyên(Số âm không được phép)
export function verifyNumberIntegerAndFloat(val) {
  // Khớp không gian
  let v = val.replace(/(^\s*)|(\s*$)/g, '');
  // Chỉ có thể là số và dấu thập phân, không thể là đầu vào khác
  v = v.replace(/[^\d.]/g, '');
  // Bắt đầu bằng 0, chỉ có thể nhập một.
  v = v.replace(/^0{2}$/g, '0');
  // Đảm bảo rằng chữ số đầu tiên chỉ có thể là số chứ không phải dấu chấm
  v = v.replace(/^\./g, '');
  // Chỉ có thể xuất hiện 1 chữ số thập phân
  v = v.replace('.', '$#$').replace(/\./g, '').replace('$#$', '.');
  // Giữ 2 chữ số sau dấu thập phân
  v = v.replace(/^(\\-)*(\d+)\.(\d\d).*$/, '$1$2.$3');
  // Trả về kết quả
  return v;
}

// Xác thực số nguyên dương
export function verifiyNumberInteger(val) {
  // Khớp không gian
  let v = val.replace(/(^\s*)|(\s*$)/g, '');
  // di dời '.' , Để tránh các vấn đề khi áp dụng nhãn dán, chẳng hạn như 0.1.12.12
  v = v.replace(/[\\.]*/g, '');
  // Xóa số bắt đầu bằng 0, Để tránh các vấn đề khi áp dụng nhãn dán, chẳng hạn như 00121323
  v = v.replace(/(^0[\d]*)$/g, '0');
  // Nơi đầu tiên là0,Chỉ có thể xuất hiện một lần
  v = v.replace(/^0\d$/g, '0');
  // Chỉ khớp số
  v = v.replace(/[^\d]/g, '');
  // Trả về kết quả
  return v;
}

// Xóa ký tự và dấu cách tiếng Trung
export function verifyCnAndSpace(val) {
  // Nối các ký tự và dấu cách tiếng Trung
  let v = val.replace(/[\u4e00-\u9fa5\s]+/g, '');
  // Khớp không gian
  v = v.replace(/(^\s*)|(\s*$)/g, '');
  // Trả về kết quả
  return v;
}

// Xóa tiếng Anh và dấu cách
export function verifyEnAndSpace(val) {
  // Kết hợp tiếng Anh và dấu cách
  let v = val.replace(/[a-zA-Z]+/g, '');
  // Khớp không gian
  v = v.replace(/(^\s*)|(\s*$)/g, '');
  // Trả về kết quả
  return v;
}

// Không được phép có khoảng trống
export function verifyAndSpace(val) {
  // Khớp không gian
  let v = val.replace(/(^\s*)|(\s*$)/g, '');
  // Trả về kết quả
  return v;
}

// Đối với số tiền `,` phân biệt
export function verifyNumberComma(val) {
  // Gọi số thập phân hoặc số nguyên(Số âm không được phép)phương pháp
  let v = verifyNumberIntegerAndFloat(val);
  // Chuyển chuỗi thành mảng
  v = v.toString().split('.');
  // \B Khớp các ranh giới không phải từ là ký tự từ ở hai bên hoặc ký tự không phải từ ở cả hai bên
  v[0] = v[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
  // Mảng thành chuỗi
  v = v.join('.');
  // Trả về kết quả
  return v;
}

// So khớp văn bản thay đổi màu sắc (khi tìm kiếm）
export function verifyTextColor(val, text = '', color = 'red') {
  // Trả lại nội dung, thêm màu sắc
  let v = text.replace(new RegExp(val, 'gi'), `<span style='color: ${color}'>${val}</span>`);
  // Trả về kết quả
  return v;
}

// Chuyển số sang chữ tiếng Việt (đồng)
export function verifyNumberCnUppercase(val) {
  const digits = ['không', 'một', 'hai', 'ba', 'bốn', 'năm', 'sáu', 'bảy', 'tám', 'chín'];
  const unitBlock = ['', 'nghìn', 'triệu', 'tỷ'];
  let amount = String(val || '').replace(/[, ]/g, '');

  if (!/^\d+(\.\d+)?$/.test(amount)) return '';

  amount = amount.split('.')[0].replace(/^0+(?=\d)/, '');
  if (!amount) return 'không đồng';

  const readTriple = (triple, full = false) => {
    const [h, t, o] = triple.split('').map((n) => Number(n));
    let result = '';

    if (h > 0 || full) result += `${digits[h]} trăm`;

    if (t > 1) {
      result += `${result ? ' ' : ''}${digits[t]} mươi`;
      if (o === 1) result += ' mốt';
      else if (o === 4) result += ' tư';
      else if (o === 5) result += ' lăm';
      else if (o > 0) result += ` ${digits[o]}`;
    } else if (t === 1) {
      result += `${result ? ' ' : ''}mười`;
      if (o === 5) result += ' lăm';
      else if (o > 0) result += ` ${digits[o]}`;
    } else if (o > 0) {
      if (h > 0 || full) result += ' lẻ';
      result += `${result ? ' ' : ''}${digits[o]}`;
    }

    return result.trim();
  };

  const blocks = [];
  for (let i = amount.length; i > 0; i -= 3) {
    blocks.unshift(amount.substring(Math.max(0, i - 3), i).padStart(3, '0'));
  }

  const words = [];
  const totalBlocks = blocks.length;

  blocks.forEach((block, idx) => {
    if (block === '000') return;
    const unitIndex = (totalBlocks - idx - 1) % 4;
    const blockWords = readTriple(block, idx !== 0);
    words.push(`${blockWords}${unitBlock[unitIndex] ? ` ${unitBlock[unitIndex]}` : ''}`.trim());
  });

  return `${words.join(' ').replace(/\s+/g, ' ').trim()} đồng`;
}

// số điện thoại
export function verifyPhone(val) {
  // Hỗ trợ định dạng 10 số hoặc +84/84
  const phone = String(val || '').replace(/\s+/g, '');
  if (!/^(?:\+84|84|0)(3|5|7|8|9)\d{8}$/.test(phone)) return false;
  return true;

}

// Số điện thoại trong nước
export function verifyTelPhone(val) {
  // Định dạng phổ biến: 02412345678, 028-12345678, 0236 1234567
  const tel = String(val || '').replace(/\s+/g, '');
  if (!/^0\d{1,3}-?\d{7,8}$/.test(tel)) return false;
  return true;
}

// Đăng nhập tài khoản (Bắt đầu bằng một chữ cái, cho phép 5-16 byte, cho phép gạch dưới chữ và số)
export function verifyAccount(val) {
  // false: Tài khoản đăng nhập không chính xác
  if (!/^[a-zA-Z][a-zA-Z0-9_]{4,15}$/.test(val)) return false;
  // true: Tài khoản đăng nhập là chính xác
  else return true;
}

// mật khẩu (Bắt đầu bằng một chữ cái và có độ dài6~16giữa, chỉ có thể chứa chữ cái, số và dấu gạch dưới)
export function verifyPassword(val) {
  // false: Mật khẩu không đúng
  if (!/^[a-zA-Z]\w{5,15}$/.test(val)) return false;
  // true: Mật khẩu đúng
  else return true;
}

// Mật khẩu mạnh (Chữ cái + số + ký tự đặc biệt, độ dài từ 6-16)
export function verifyPasswordPowerful(val) {
  // false: Mật khẩu mạnh không chính xác
  if (
    !/^(?![a-zA-z]+$)(?!\d+$)(?![!@#$%^&\\.*]+$)(?![a-zA-z\d]+$)(?![a-zA-z!@#$%^&\\.*]+$)(?![\d!@#$%^&\\.*]+$)[a-zA-Z\d!@#$%^&\\.*]{6,16}$/.test(
      val,
    )
  )
    return false;
  // true: Mật khẩu mạnh là chính xác
  else return true;
}

// Độ mạnh mật khẩu
export function verifyPasswordStrength(val) {
  let v = '';
  // Yếu: thuần số, thuần chữ, thuần ký tự đặc biệt
  if (/^(?:\d+|[a-zA-Z]+|[!@#$%^&\\.*]+){6,16}$/.test(val)) v = 'yếu đuối';
  // Medium: chữ cái + số, chữ cái + ký tự đặc biệt, số + ký tự đặc biệt
  if (/^(?![a-zA-z]+$)(?!\d+$)(?![!@#$%^&\\.*]+$)[a-zA-Z\d!@#$%^&\\.*]{6,16}$/.test(val)) v = 'ở giữa';
  // Mạnh: chữ cái + số + ký tự đặc biệt
  if (
    /^(?![a-zA-z]+$)(?!\d+$)(?![!@#$%^&\\.*]+$)(?![a-zA-z\d]+$)(?![a-zA-z!@#$%^&\\.*]+$)(?![\d!@#$%^&\\.*]+$)[a-zA-Z\d!@#$%^&\\.*]{6,16}$/.test(
      val,
    )
  )
    v = 'mạnh mẽ';
  // Trả về kết quả
  return v;
}

// IPĐịa chỉ
export function verifyIPAddress(val) {
  // false: IPĐịa chỉ không chính xác
  // if (
  //   !/^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/.test(
  //     val,
  //   )
  // )
  //   return false;
  // // true: IPĐịa chỉ đúng
  // else return true;
  return true;
}

// Thư
export function verifyEmail(val) {
  // false: Email không chính xác
  if (
    !/^(([^<>()\\[\]\\.,;:\s@"]+(\.[^<>()\\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(
      val,
    )
  )
    return false;
  // true: Email là chính xác
  else return true;
}

// CMND
export function verifyIdCard(val) {
  // Chấp nhận CMND 9 số hoặc CCCD 12 số
  const id = String(val || '').replace(/\s+/g, '');
  if (!/^(\d{9}|\d{12})$/.test(id)) return false;
  return true;
}

// Tên
export function verifyFullName(val) {
  // Hỗ trợ tên tiếng Việt có dấu, dấu nháy và khoảng trắng
  const name = String(val || '').trim();
  if (!/^[A-Za-zÀ-ỹĂăÂâĐđÊêÔôƠơƯư\s'.-]{2,60}$/.test(name)) return false;
  return true;
}

// mã bưu chính
export function verifyPostalCode(val) {
  // false: Mã bưu chính không chính xác
  if (!/^\d{5}$/.test(val)) return false;
  // true: Mã bưu chính là chính xác
  else return true;
}

// url
export function verifyUrl(val) {
  // false: urlKhông đúng
  if (
    !/^(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})).?)(?::\d{2,5})?(?:[/?#]\S*)?$/i.test(
      val,
    )
  )
    return false;
  // true: urlChính xác
  else return true;
}

// biển số xe
export function verifyCarNum(val) {
  // false: Biển số xe không đúng
  const plate = String(val || '').toUpperCase().replace(/\s+/g, '');
  if (!/^\d{2}[A-Z]{1,2}-?\d{3,5}(\.\d{2})?$/.test(plate))
    return false;
  // true：Số biển số xe là chính xác
  else return true;
}
