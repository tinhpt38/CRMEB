// +---------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +---------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +---------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +---------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +---------------------------------------------------------------------

// import parseTime, formatTime and set to filter
/**
 * Trạng thái trực tiếp
 * @param {String} value
 */
export function liveReviewStatusFilter(value) {
  const statusMap = {
    101: 'Phát sóng trực tiếp',
    102: 'Chưa bắt đầu',
    103: 'đã kết thúc',
    104: 'đã kết thúc',
    105: 'Phát sóng trực tiếp',
    106: 'Phát sóng trực tiếp',
    107: 'đã kết thúc',
  };
  return statusMap[value];
}

/**
 * Xem lại trạng thái
 * @param {String} value
 */
export function liveStatusFilter(value) {
  const statusMap = {
    0: 'Chưa được xem xét ',
    1: 'Đang xem xét',
    2: 'Tán thành',
    3: 'Đánh giá không thành công',
  };
  return statusMap[value];
}

/**
 * Dấu thời gian theo thời gian
 * @param {String} data
 */
export function formatDate(data) {
  let date = new Date(data);
  let DD = (date.getDate() < 10 ? '0' + date.getDate() : date.getDate()) + '/';
  let MM = (date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1) + '/';
  let YY = date.getFullYear();
  let hh = (date.getHours() < 10 ? '0' + date.getHours() : date.getHours()) + ':';
  let mm = (date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes()) + ':';
  let ss = date.getSeconds() < 10 ? '0' + date.getSeconds() : date.getSeconds();
  return DD + MM + YY + ' ' + hh + mm + ss;
}

/**
 * @description Loại phòng phát sóng trực tiếp
 */
export function broadcastType(type) {
  const typeMap = {
    0: 'Truyền hình trực tiếp trên thiết bị di động',
    1: 'Đẩy phát trực tuyến',
  };
  return typeMap[type];
}

/**
 * @description Có nên tắt lượt thích và bình luận không
 */
export function filterClose(value) {
  return value ? '✔' : '✖';
}

/**
 * @description Kiểu hiển thị trực tiếp
 */
export function broadcastDisplayType(type) {
  const typeMap = {
    0: 'Màn hình dọc',
    1: 'Màn hình ngang',
  };
  return typeMap[type];
}

// bộ lọc công khai
export function filterEmpty(val) {
  let _result = '-';
  if (!val) {
    return _result;
  }
  _result = val;
  return _result;
}

/**
 * @description Loại người dùng
 */
export function userType(type) {
  const typeMap = {
    routine: 'Chương trình nhỏ',
    'wechat ': 'WeChat',
    h5: 'H5',
  };
  return typeMap[type];
}

/**
 * @description Loại nguồn truy cập
 */
export function sourceType(type) {
  const typeMap = {
    0: 'PCkết thúc',
    1: 'Tài khoản chính thức',
    2: 'Chương trình nhỏ',
    3: 'H5',
  };
  return typeMap[type];
}
