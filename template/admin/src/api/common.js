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

export function ajCaptcha(params) {
  return request({
    url: 'ajcaptcha',
    method: 'get',
    params: params,
  });
}

export function ajCaptchaCheck(data) {
  return request({
    url: 'ajcheck',
    method: 'post',
    data: data,
  });
}

/**
 * @description Bảng--Xóa
 * @param {Number} param id {Number} Cấu hìnhid
 */
export function tableDelApi(data) {
  return request({
    url: data.url,
    method: data.method,
    data: data.ids,
    kefu: data.kefu || '',
  });
}

/**
 * Nhận lời nhắc về tin nhắn
 */
export function jnoticeRequest() {
  return request({
    url: 'jnotice',
    method: 'GET',
  });
}

/**
 * Lấylogo
 */
export function getLogo() {
  return request({
    url: 'logo',
    method: 'GET',
  });
}
