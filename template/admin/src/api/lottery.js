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
 * @description Xổ số lưới chín ô vuông -- danh sách
 */
export function lotteryListApi(data) {
  return request({
    url: 'marketing/lottery/list',
    method: 'get',
    params: data,
  });
}

/**
 * @description Xổ số Cửu Cung -- Chi tiết
 * Sự kiện xổ số @param idid
 */
export function lotteryDetailApi(id) {
  return request({
    url: `marketing/lottery/detail/${id}`,
    method: 'get',
  });
}

/**
 * @description Xổ số lưới chín ô vuông - chi tiết phiên bản mới
 * Sự kiện xổ số @param idid
 */
export function lotteryNewDetailApi(type) {
  return request({
    url: `marketing/lottery/factor_info/${type}`,
    method: 'get',
  });
}

/**
 * @description Xổ số lưới chín ô vuông -- Tạo
 */
export function lotteryCreateApi(data) {
  return request({
    url: `marketing/lottery/add`,
    method: 'post',
    data,
  });
}
/**
 **
 * @description Xổ số Cửu Cung -- Sửa đổi/Chỉnh sửa
 */
export function lotteryEditApi(id, data) {
  return request({
    url: `marketing/lottery/edit/${id}`,
    method: 'put',
    data,
  });
}

/**
 **
 * @description Xổ số Cửu Cung -- Xóa
 */
export function lotteryDelApi(id) {
  return request({
    url: `marketing/lottery/del/${id}`,
    method: 'delete',
  });
}

/**
 **
 * @description Xổ số lưới chín ô vuông - trạng thái hiển thị
 */
export function lotteryStatusApi(data) {
  return request({
    url: `marketing/lottery/set_status/${data.id}/${data.status}`,
    method: 'post',
  });
}

/**
 **
 * @description Xổ số Cửu Công - Kỷ lục trúng thưởng
 */
export function lotteryRecordList(data) {
  return request({
    url: `marketing/lottery/record/list`,
    method: 'get',
    params: data,
  });
}

/**
 **
 * @description Xổ số Jiugongge - Giải thưởng Vận chuyển/Xử lý nhận xét
 */
export function lotteryRecordDeliver(data) {
  return request({
    url: `marketing/lottery/record/deliver`,
    method: 'post',
    data,
  });
}

/**
 **
 * @description Danh sách xổ số
 */
export function lotteryList(data) {
  return request({
    url: `marketing/lottery/list`,
    method: 'get',
    params: data,
  });
}
/**
 **
 * @description Mua lại loại xổ số
 */
export function factorListApi(data) {
  return request({
    url: `marketing/lottery/factor/list`,
    method: 'get',
  });
}

/**
 **
 * @description Lưu cấu hình xổ số
 */
export function factorUseApi(data) {
  return request({
    url: `marketing/lottery/factor/use`,
    method: 'post',
    data,
  });
}

/**
 * @description Chuyển đổi trạng thái xổ số
 * @param data {Object} Giá trị vượt qua
 */
export function lotteryStatus(data) {
  return request({
    url: `marketing/lottery/set_status/${data.id}/${data.status}`,
    method: 'put',
  });
}
