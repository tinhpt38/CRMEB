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
 * @description Tiêu đề trang chủ
 */
export function headerApi() {
  return request({
    url: 'home/header',
    method: 'get',
  });
}

/**
 * @description Biểu đồ đặt hàng tại nhà
 */
export function orderApi(params) {
  return request({
    url: 'home/order',
    method: 'get',
    params,
  });
}

/**
 * @description Biểu đồ đặt hàng tại nhà
 */
export function userApi() {
  return request({
    url: 'home/user',
    method: 'get',
  });
}

/**
 * @description Xếp hạng khối lượng giao dịch sản phẩm trên trang chủ
 */
export function rankApi() {
  return request({
    url: 'home/rank',
    method: 'get',
  });
}
