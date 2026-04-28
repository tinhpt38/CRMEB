// +---------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +---------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +---------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +---------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +---------------------------------------------------------------------

export const forEach = (arr, fn) => {
  if (!arr.length || !fn) return;
  let i = -1;
  let len = arr.length;
  while (++i < len) {
    let item = arr[i];
    fn(item, i, arr);
  }
};

/**
 * @param {Array} arr1
 * @param {Array} arr2
 * @description Lấy giao điểm của hai mảng, Các phần tử của hai mảng là giá trị số hoặc chuỗi
 */
export const getIntersection = (arr1, arr2) => {
  let len = Math.min(arr1.length, arr2.length);
  let i = -1;
  let res = [];
  while (++i < len) {
    const item = arr2[i];
    if (arr1.indexOf(item) > -1) res.push(item);
  }
  return res;
};

/**
 * @param {Array} arr1
 * @param {Array} arr2
 * @description Lấy sự kết hợp của hai mảng, Các phần tử của hai mảng là giá trị số hoặc chuỗi
 */
export const getUnion = (arr1, arr2) => {
  return Array.from(new Set([...arr1, ...arr2]));
};

/**
 * @param {Array} target mảng mục tiêu
 * @param {Array} arr Mảng cần truy vấn
 * @description Xác định xem mảng được truy vấn có ít nhất một phần tử chứa trong mảng đích hay không
 */
export const hasOneOf = (targetarr, arr) => {
  return targetarr.some((_) => Arr.indexOf(_) > -1);
};

/**
 * @param {String|Number} value Chuỗi hoặc giá trị cần được xác thực
 * @param {*} validList danh sách để xác minh
 */
export function oneOf(value, validList) {
  for (let i = 0; i < validList.length; i++) {
    if (value === validList[i]) {
      return true;
    }
  }
  return false;
}

/**
 * @param {Number} timeStamp Xác định xem định dạng dấu thời gian có phải là mili giây không
 * @returns {Boolean}
 */
const isMillisecond = (timeStamp) => {
  const timeStr = String(timeStamp);
  return timeStr.length > 10;
};

/**
 * @param {Number} timeStamp Dấu thời gian đến
 * @param {Number} currentTime dấu thời gian hiện tại
 * @returns {Boolean} Dấu thời gian đến có sớm hơn dấu thời gian hiện tại hay không
 */
const isEarly = (timeStamp, currentTime) => {
  return timeStamp < currentTime;
};

/**
 * @param {Number} num giá trị số
 * @returns {String} chuỗi đã xử lý
 * @description Nếu giá trị truyền vào nhỏ hơn 10 tức là chỉ có 1 chữ số thì thêm vào đằng trước0
 */
const getHandledValue = (num) => {
  return num < 10 ? '0' + num : num;
};

/**
 * @param {Number} timeStamp Dấu thời gian đến
 * @param {Number} startType Kiểu định dạng của chuỗi thời gian được trả về, được chuyển vào'year'sau đó trả về thời gian đầy đủ vào đầu năm
 */
const getDate = (timeStamp, startType) => {
  const d = new Date(timeStamp * 1000);
  const year = d.getFullYear();
  const month = getHandledValue(d.getMonth() + 1);
  const date = getHandledValue(d.getDate());
  const hours = getHandledValue(d.getHours());
  const minutes = getHandledValue(d.getMinutes());
  const second = getHandledValue(d.getSeconds());
  let resStr = '';
  if (startType === 'year') resStr = year + '-' + month + '-' + date + ' ' + hours + ':' + minutes + ':' + second;
  else resStr = month + '-' + date + ' ' + hours + ':' + minutes;
  return resStr;
};

/**
 * @param {String|Number} timeStamp Dấu thời gian
 * @returns {String} chuỗi thời gian tương đối
 */
export const getRelativeTime = (timeStamp) => {
  // Xác định xem dấu thời gian đến hiện tại ở định dạng giây hay mili giây
  const IS_MILLISECOND = isMillisecond(timeStamp);
  // Nếu nó ở định dạng mili giây, hãy chuyển nó sang định dạng giây.
  if (IS_MILLISECOND) Math.floor((timeStamp /= 1000));
  // Dấu thời gian đến có thể là loại số hoặc chuỗi và được chuyển đổi thống nhất thành loại số ở đây.
  timeStamp = Number(timeStamp);
  // Nhận dấu thời gian hiện tại
  const currentTime = Math.floor(Date.parse(new Date()) / 1000);
  // Xác định xem dấu thời gian đến có sớm hơn dấu thời gian hiện tại không
  const IS_EARLY = isEarly(timeStamp, currentTime);
  // Nhận sự khác biệt giữa hai dấu thời gian
  let diff = currentTime - timeStamp;
  // Nếu IS_EARLY sai, chênh lệch sẽ bị đảo ngược
  if (!IS_EARLY) diff = -diff;
  let resStr = '';
  const dirStr = IS_EARLY ? 'phía trước' : 'mặt sau';
  // Nhỏ hơn hoặc bằng 59 giây
  if (diff <= 59) resStr = diff + 'Thứ hai' + dirStr;
  // Hơn 59 giây, nhỏ hơn hoặc bằng 59 phút 59 giây
  else if (diff > 59 && diff <= 3599) resStr = Math.floor(diff / 60) + 'phút' + dirStr;
  // Trên 59 phút 59 giây, nhỏ hơn hoặc bằng 23 giờ 59 phút 59 giây
  else if (diff > 3599 && diff <= 86399) resStr = Math.floor(diff / 3600) + 'Giờ' + dirStr;
  // Hơn 23 giờ, 59 phút và 59 giây, nhỏ hơn hoặc bằng 29 ngày, 59 phút và 59 giây
  else if (diff > 86399 && diff <= 2623859) resStr = Math.floor(diff / 86400) + 'ngày' + dirStr;
  // Hơn 29 ngày, 59 phút và 59 giây, ít hơn 364 ngày, 23 giờ, 59 phút và 59 giây và dấu thời gian đến sớm hơn dấu thời gian hiện tại
  else if (diff > 2623859 && diff <= 31567859 && IS_EARLY) resStr = getDate(timeStamp);
  else resStr = getDate(timeStamp, 'year');
  return resStr;
};

/**
 * @returns {String} Tên trình duyệt hiện tại
 */
export const getExplorer = () => {
  const ua = window.navigator.userAgent;
  const isExplorer = (exp) => {
    return ua.indexOf(exp) > -1;
  };
  if (isExplorer('MSIE')) return 'IE';
  else if (isExplorer('Firefox')) return 'Firefox';
  else if (isExplorer('Chrome')) return 'Chrome';
  else if (isExplorer('Opera')) return 'Opera';
  else if (isExplorer('Safari')) return 'Safari';
};

/**
 * @description Sự kiện ràng buộc on(element, event, handler)
 */
export const on = (function () {
  if (document.addEventListener) {
    return function (element, event, handler) {
      if (element && event && handler) {
        element.addEventListener(event, handler, false);
      }
    };
  } else {
    return function (element, event, handler) {
      if (element && event && handler) {
        element.attachEvent('on' + event, handler);
      }
    };
  }
})();

/**
 * @description sự kiện gỡ bỏ ràng buộc off(element, event, handler)
 */
export const off = (function () {
  if (document.removeEventListener) {
    return function (element, event, handler) {
      if (element && event) {
        element.removeEventListener(event, handler, false);
      }
    };
  } else {
    return function (element, event, handler) {
      if (element && event) {
        element.detachEvent('on' + event, handler);
      }
    };
  }
})();

/**
 * Xác định xem một đối tượng có khóa hay không. Nếu khóa tham số thứ hai được truyền vào, nó sẽ xác định xem đối tượng obj có thuộc tính khóa hay không.
 * Nếu tham số khóa không được truyền vào, hãy xác định xem đối tượng obj có cặp khóa-giá trị hay không
 */
export const hasKey = (obj, key) => {
  if (key) return key in obj;
  else {
    let keysArr = Object.keys(obj);
    return keysArr.length;
  }
};

/**
 * @param {*} obj1 sự vật
 * @param {*} obj2 vật thể
 * @description Xác định xem hai đối tượng có bằng nhau hay không. Giá trị của hai đối tượng này chỉ có thể là số hoặc chuỗi.
 */
export const objEqual = (obj1, obj2) => {
  const keysArr1 = Object.keys(obj1);
  const keysArr2 = Object.keys(obj2);
  if (keysArr1.length !== keysArr2.length) return false;
  else if (keysArr1.length === 0 && keysArr2.length === 0) return true;
  /* eslint-disable-next-line */ else return !keysArr1.some((key) => obj1[key] != obj2[key]);
};

/**
 * Loại bỏ nhiều chữ số thập phân trong phép tính nhân
 * Giá trị trả về @param arg1, tham số arg2 được nhân với
 */
export const accMul = (arg1, arg2) => {
  var m = 0,
    s1 = arg1.toString(),
    s2 = arg2.toString();
  try {
    m += s1.split('.')[1].length;
  } catch (e) {}
  try {
    m += s2.split('.')[1].length;
  } catch (e) {}
  return (Number(s1.replace('.', '')) * Number(s2.replace('.', ''))) / Math.pow(10, m);
};
