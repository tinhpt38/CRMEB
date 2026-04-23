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
 * @description Nhận cấu hình giao diện
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function crudApi(table_name) {
  return request({
    url: `system/crud/config/${table_name}`,
    method: 'get',
  });
}

/**
 * @description Giao diện danh sách
 */
export function getList(url, params) {
  return request({
    url: url,
    method: 'get',
    params,
  });
}
/**
 * @description Tạo giao diện
 */
export function getCreateApi(url) {
  return request({
    url: url,
    method: 'get',
  });
}

export function getStatusApi(url, data) {
  return request({
    url: url,
    method: 'put',
    data,
  });
}
/**
 * @description Tạo giao diện
 */
export function getEditApi(url) {
  return request({
    url: url,
    method: 'get',
  });
}
