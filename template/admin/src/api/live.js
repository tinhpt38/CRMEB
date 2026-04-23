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
 * @description Danh sách phát sóng trực tiếp
 */
export function liveList(params) {
  return request({
    url: 'live/room/list',
    method: 'get',
    params,
  });
}

/**
 * @description Danh sách phát sóng trực tiếp
 */
export function liveAdd(data) {
  return request({
    url: 'live/room/add',
    method: 'post',
    data,
  });
}

/**
 * @description Chi tiết danh sách phát sóng trực tiếp
 */
export function liveDetail(id) {
  return request({
    url: 'live/room/detail/' + id,
    method: 'get',
  });
}

/**
 * @description Cài đặt phòng phát sóng trực tiếp có được hiển thị hay không
 */
export function liveShow(id, type) {
  return request({
    url: `live/room/set_show/${id}/${type}`,
    method: 'get',
  });
}

/**
 * @description Danh sách sản phẩm trực tiếp
 */
export function liveGoods(params) {
  return request({
    url: 'live/goods/list',
    method: 'get',
    params,
  });
}

/**
 * @description Tạo sản phẩm trực tiếp từ danh sách sản phẩm trực tiếp
 */
export function liveGoodsCreat(data) {
  return request({
    url: 'live/goods/create',
    method: 'post',
    data,
  });
}

/**
 * @description Thêm danh sách sản phẩm trực tiếp
 */
export function liveGoodsAdd(data) {
  return request({
    url: 'live/goods/add',
    method: 'post',
    data,
  });
}

/**
 * @description Thêm sản phẩm vào phòng phát sóng trực tiếp
 */
export function liveRoomGoodsAdd(data) {
  return request({
    url: 'live/room/add_goods',
    method: 'post',
    data,
  });
}

/**
 * @description Phòng phát sóng trực tiếp đồng bộ
 */
export function liveSyncRoom() {
  return request({
    url: 'live/room/syncRoom',
    method: 'get',
  });
}

/**
 * @description Đồng bộ hóa sản phẩm
 */
export function liveSyncGoods() {
  return request({
    url: 'live/goods/syncGoods',
    method: 'get',
  });
}

/**
 * @description Danh sách neo
 */
export function liveAuchorList(params) {
  return request({
    url: 'live/anchor/list',
    method: 'get',
    params,
  });
}

/**
 * @description Anchor thêm/sửa đổi biểu mẫu chuyển đổi
 */
export function liveAuchorAdd(id) {
  return request({
    url: 'live/anchor/add/' + id,
    method: 'get',
  });
}

/**
 * @description Chi tiết sản phẩm trực tiếp
 */
export function liveGoodsDetail(id) {
  return request({
    url: 'live/goods/detail/' + id,
    method: 'get',
  });
}

/**
 * @description Hiển thị sản phẩm trực tiếp
 */
export function liveGoodsShow(id, type) {
  return request({
    url: `live/goods/set_show/${id}/${type}`,
    method: 'get',
  });
}
