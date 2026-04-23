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
 * @description tải lên
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function upload(data, config) {
  return request({
    url: 'file/video_upload',
    method: 'post',
    file: true,
    data,
  });
}
/**
 * @description Tải lên url lưu trữ đám mây
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function ossUpload(url, data) {
  return request({
    url,
    method: 'post',
    file: true,
    data,
  });
}
