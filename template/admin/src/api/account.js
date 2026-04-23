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

/*
 * Đăng nhập
 * */
export function AccountLogin(data) {
  return request({
    url: '/login',
    method: 'post',
    data,
  });
}

/**
 * Đăng xuất
 * @constructor
 */
export function AccountLogout() {
  return request({
    url: '/setting/admin/logout',
    method: 'get',
  });
}

/**
 * Lấy hình ảnh băng chuyền vàlogo
 */
export function loginInfoApi() {
  return request({
    url: '/login/info',
    method: 'get',
  });
}

/**
 * Lấy dữ liệu thực đơn
 */
export function menusApi() {
  return request({
    url: '/menus',
    method: 'get',
  });
}

/**
 * Tìm kiếm dữ liệu thực đơn
 */
export function menusListApi() {
  return request({
    url: '/menusList',
    method: 'get',
  });
}

export function AccountRegister() {}
