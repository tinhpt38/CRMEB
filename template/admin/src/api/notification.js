// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

import request from '@/libs/request';

/**
 * @description Nhận dữ liệu danh sách quản lý tin nhắn
 * @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function getNotificationList(type) {
  return request({
    url: `setting/notification/index?type=${type}`,
    method: 'get',
  });
}
/**
 * @description Nhận thu thập dữ liệu cài đặt quản lý tin nhắn
 * @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function getNotificationInfo(id, type) {
  return request({
    url: `setting/notification/info?id=${id}&type=${type}`,
    method: 'get',
  });
}

/**
 * @description Nhận thu thập dữ liệu cài đặt quản lý tin nhắn
 * @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function getNotificationSave(data) {
  return request({
    url: `setting/notification/save`,
    method: 'post',
    data,
  });
}

/**
 * @description Thiết lập thông báo trang web
 * @param {Number} param id {Number}
 */
export function noticeStatus(type, status, id) {
  return request({
    url: `setting/notification/set_status/${type}/${status}/${id}`,
    method: 'put',
  });
}

/**
 * @description Thêm mẫu tin nhắn sửa đổi
 * @param {Number} param id {Number} Tham số truyền theo giá trị
 */
export function notificationForm(id) {
  return request({
    url: `setting/notification/not_form/${id}`,
    method: 'get',
  });
}
