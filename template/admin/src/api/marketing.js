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
 * @description Tạo phiếu giảm giá--danh sách
 * @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function couponListApi(params) {
  return request({
    url: 'marketing/coupon/list',
    method: 'get',
    params,
  });
}

/**
 * @description Tạo phiếu giảm giá--thêm biểu mẫu
 * type:Thêm phiếu giảm giá loại 0: chung, 1: danh mục, 2: hàng hóa
 */
export function couponCreateApi(type) {
  return request({
    url: `marketing/coupon/create/${type}`,
    method: 'get',
  });
}

/**
 * @description Tạo phiếu giảm giá--chỉnh sửa biểu mẫu
 */
export function couponEditeApi(id) {
  return request({
    url: `marketing/coupon/${id}/edit`,
    method: 'get',
  });
}

/**
 * @description Sản xuất phiếu giảm giá - xuất bản mẫu phiếu giảm giá
 * @param {Number} param id {Number} Phiếu giảm giáid
 */
export function couponSendApi(id) {
  return request({
    url: `marketing/coupon/issue/${id}`,
    method: 'get',
  });
}

/**
 * @description Quản lý đã xuất bản--danh sách
 * @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function releasedListApi(params) {
  return request({
    url: 'marketing/coupon/released',
    method: 'get',
    params,
  });
}

/**
 * @description Quản lý công bố--Hồ sơ tiếp nhận
 * @param {Number} param id {Number} Phiếu giảm giá đã đăngid
 */
export function releasedissueLogApi(id, params) {
  return request({
    url: `marketing/coupon/released/issue_log/${id}`,
    method: 'get',
    params,
  });
}

/**
 * @description Quản lý đã xuất bản--Sửa đổi biểu mẫu trạng thái
 * @param {Number} param id {Number} Phiếu giảm giá đã đăngid
 */
export function releaseStatusApi(id) {
  return request({
    url: `marketing/coupon/released/${id}/status`,
    method: 'get',
  });
}

/**
 * @description Danh sách phiếu giảm giá--có bật nó không
 * @param {*} data
 */
export function couponStatusApi(data) {
  return request({
    url: `marketing/coupon/status/${data.id}/${data.status}`,
    method: 'get',
  });
}

/**
 * @description Tạo phiếu giảm giá--lưu
 */
export function couponSaveApi(data) {
  return request({
    url: `marketing/coupon/save_coupon`,
    method: 'post',
    data,
  });
}

/**
 * @description Phiếu giảm giá
 * @param {*} id
 */
export function couponDetailApi(id) {
  return request({
    url: `marketing/coupon/copy/${id}`,
    method: 'get',
  });
}

/**
 * @description Hồ sơ thu thập thành viên - danh sách
 * @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function userListApi(params) {
  return request({
    url: `/marketing/coupon/user`,
    method: 'get',
    params,
  });
}

/**
 * @description Mặt hàng giảm giá - danh sách
 * @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function bargainListApi(params) {
  return request({
    url: `marketing/bargain`,
    method: 'get',
    params,
  });
}

/**
 * @description Hàng khuyến mại -- chi tiết
 * @param {Number} param id {Number} mặt hàng giá hờiid
 */
export function bargainInfoApi(id) {
  return request({
    url: `marketing/bargain/${id}`,
    method: 'get',
  });
}

/**
 * @description Vật phẩm được mặc cả -- Lưu Chỉnh sửa
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function bargainCreatApi(data) {
  return request({
    url: `marketing/bargain/${data.id}`,
    method: 'POST',
    data,
  });
}

/**
 * @description Mặt hàng được mặc cả - sửa đổi trạng thái
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function bargainSetStatusApi(data) {
  return request({
    url: `marketing/bargain/set_status/${data.id}/${data.status}`,
    method: 'PUT',
  });
}
/**
 * @description Các mặt hàng bán trước - sửa đổi trạng thái
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function advanceSetStatusApi(data) {
  return request({
    url: `marketing/advance/set_status/${data.id}/${data.status}`,
    method: 'PUT',
  });
}

/**
 * @description Các mặt hàng bán trước -- danh sách
 * @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function presellListApi(params) {
  return request({
    url: `marketing/advance/index`,
    method: 'get',
    params,
  });
}

/**
 * @description Các mặt hàng bán trước -- lưu chỉnh sửa
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function presellCreatApi(data) {
  return request({
    url: `marketing/advance/save/${data.id}`,
    method: 'POST',
    data,
  });
}

/**
 * @description Các mặt hàng bán trước -- chi tiết
 * @param {Number} param id {Number} Nhóm sản phẩmid
 */
export function presellInfoApi(id) {
  return request({
    url: `marketing/advance/info/${id}`,
    method: 'get',
  });
}

/**
 * @description Nhóm sản phẩm - danh sách
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function combinationListApi(params) {
  return request({
    url: `marketing/combination`,
    method: 'get',
    params,
  });
}

/**
 * @description Nhóm sản phẩm -- sửa đổi trạng thái
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function combinationSetStatusApi(data) {
  return request({
    url: `marketing/combination/set_status/${data.id}/${data.status}`,
    method: 'PUT',
  });
}

/**
 * @description Sản phẩm mua theo nhóm -- Thống kê mua theo nhóm
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function statisticsApi() {
  return request({
    url: `marketing/combination/statistics`,
    method: 'GET',
  });
}

/**
 * @description Nhóm sản phẩm -- chi tiết
 * @param {Number} param id {Number} Nhóm sản phẩmid
 */
export function combinationInfoApi(id) {
  return request({
    url: `marketing/combination/${id}`,
    method: 'get',
  });
}

/**
 * @description Nhóm sản phẩm -- lưu và chỉnh sửa
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function combinationCreatApi(data) {
  return request({
    url: `marketing/combination/${data.id}`,
    method: 'POST',
    data,
  });
}

/**
 * @description Sản phẩm mua theo nhóm -- Danh sách mua theo nhóm
 */
export function combineListApi(params) {
  return request({
    url: `marketing/combination/combine/list`,
    method: 'GET',
    params,
  });
}

/**
 * @description Sản phẩm mua theo nhóm -- danh sách người mua theo nhóm
 * @param {Number} param id {Number} Nhóm sản phẩmid
 */
export function orderPinkListApi(id) {
  return request({
    url: `marketing/combination/order_pink/${id}`,
    method: 'GET',
  });
}

/**
 * @description Sản phẩm Flash Sale -- Danh sách
 */
export function seckillListApi(params) {
  return request({
    url: `marketing/seckill`,
    method: 'GET',
    params,
  });
}

/**
 * @description Sản phẩm Flash Sale -- Chi tiết
 */
export function seckillInfoApi(id) {
  return request({
    url: `marketing/seckill/${id}`,
    method: 'GET',
  });
}

/**
 * @description Sản phẩm Flash Sale -- Lưu Chỉnh sửa
 */
export function seckillAddApi(data) {
  return request({
    url: `marketing/seckill/${data.id}`,
    method: 'post',
    data,
  });
}

/**
 * @description Sản phẩm Flash Sale -- Sửa đổi trạng thái
 */
export function seckillStatusApi(data) {
  return request({
    url: `marketing/seckill/set_status/${data.id}/${data.status}`,
    method: 'put',
  });
}

/**
 * @description Hoạt động Flash Sale -- Danh sách
 */
export function seckillActivityListApi(params) {
  return request({
    url: `marketing/seckill_activity/list`,
    method: 'GET',
    params,
  });
}

/**
 * @description Sản phẩm Flash Sale -- Lưu và chỉnh sửa hàng loạt
 */
export function seckillActivityAddApi(data) {
  return request({
    url: `marketing/seckill_activity/save/${data.id}`,
    method: 'post',
    data,
  });
}
/**
 * @description Sự kiện flash sale hàng loạt -- chi tiết
 */
export function seckillActivityInfoApi(id) {
  return request({
    url: `marketing/seckill_activity/info/${id}`,
    method: 'get',
  });
}

/**
 * @description Hoạt động flash sale -- sửa đổi trạng thái
 */
export function seckillActivityStatusApi(data) {
  return request({
    url: `marketing/seckill_activity/status/${data.id}/${data.status}`,
    method: 'put',
  });
}

/**
 * @description Nhật ký điểm -- Danh sách
 */
export function integralListApi(params) {
  return request({
    url: `marketing/integral`,
    method: 'GET',
    params,
  });
}

/**
 * @description Nhật ký điểm -- Đầu
 */
export function integralStatisticsApi(params) {
  return request({
    url: `marketing/integral/statistics`,
    method: 'GET',
    params,
  });
}

/**
 * @description Nhật ký điểm -- Đầu
 */
export function seckillTimeListApi() {
  return request({
    url: `marketing/seckill/time_list`,
    method: 'GET',
  });
}

/**
 * @description Danh sách sản phẩm -- Tiêu đề
 */
export function productAttrsApi(id, type) {
  return request({
    url: `product/product/attrs/${id}/${type}`,
    method: 'GET',
  });
}

/**
 * @description Mặt hàng giảm giá - danh sách
 * @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function bargainUserListApi(params) {
  return request({
    url: `marketing/bargain_list`,
    method: 'get',
    params,
  });
}

/**
 * @description Mặt hàng giảm giá - danh sách
 * @param {Object} param params {Object} Tham số truyền theo giá trị
 */
export function bargainUserInfoApi(id) {
  return request({
    url: `marketing/bargain_list_info/${id}`,
    method: 'get',
  });
}

/**
 * @description Quản lý đã xuất bản -- Xóa
 */
export function delCouponReleased(id) {
  return request({
    url: `marketing/coupon/released/${id}`,
    method: 'DELETE',
  });
}

/**
 * @description Nhật ký điểm -- Xuất
 */
export function userPointApi(data) {
  return request({
    url: `export/userPoint`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Hoạt động thương lượng tại cửa hàng -- Xuất khẩu
 */
export function stroeBargainApi(data) {
  return request({
    url: `export/storeBargain`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Nhóm cửa hàng -- Xuất khẩu
 */
export function storeCombinationApi(data) {
  return request({
    url: `export/storeCombination`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Khuyến mãi chớp nhoáng tại cửa hàng -- Xuất khẩu
 */
export function storeSeckillApi(data) {
  return request({
    url: `export/storeSeckill`,
    method: 'get',
    params: data,
  });
}

/**
 * @description Sản phẩm tích điểm -- Danh sách
 */
export function integralProductListApi(params) {
  return request({
    url: `marketing/integral_product`,
    method: 'GET',
    params,
  });
}

/**
 * @description Sản phẩm tích điểm -- Lưu Chỉnh sửa
 */
export function integralAddApi(data) {
  return request({
    url: `marketing/integral/${data.id}`,
    method: 'post',
    data,
  });
}

/**
 * @description Sản phẩm tích điểm -- (Nhiều) cứu
 */
export function integralAddBatch(data) {
  return request({
    url: `marketing/integral/batch`,
    method: 'post',
    data,
  });
}

/**
 * @description Sản phẩm tích điểm -- Chi tiết
 */
export function integralInfoApi(id) {
  return request({
    url: `marketing/integral/${id}`,
    method: 'GET',
  });
}
/**
 * @description Sản phẩm điểm -- Sửa đổi trạng thái
 */
export function integralIsShowApi(data) {
  return request({
    url: `marketing/integral/set_show/${data.id}/${data.is_show}`,
    method: 'put',
  });
}
/**
 * @description Quản lý đơn hàng điểm--danh sách
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function integralOrderList(data) {
  return request({
    url: 'marketing/integral/order/list',
    method: 'get',
    params: data,
  });
}

/**
 * @description Dữ liệu thứ tự điểm--danh sách
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function integralGetOrdes(data) {
  return request({
    url: 'marketing/integral/order/chart',
    method: 'get',
    params: data,
  });
}
/**
 * @description Thông tin hậu cần đặt hàng
 * @param {Number} param id {Number} Đặt hàngid
 */
export function getExpress(id) {
  return request({
    url: `marketing/integral/order/express/${id}`,
    method: 'get',
  });
}
/**
 * @description Nhận công ty chuyển phát nhanh
 */
export function getExpressData(status) {
  return request({
    url: `marketing/integral/order/express_list?status=` + status,
    method: 'get',
  });
}

/**
 * @description Dữ liệu chi tiết mẫu đơn đặt hàng
 * @param {Number} param id {Number} Đặt hàngid
 */
export function getIntegralOrderDataInfo(id) {
  return request({
    url: `marketing/integral/order/info/${id}`,
    method: 'get',
  });
}

/**
 * @description Mẫu thông tin vận chuyển
 * @param {Number} param id {Number} Đặt hàngid
 */
export function getIntegralOrderDistribution(id) {
  return request({
    url: `marketing/integral/order/distribution/${id}`,
    method: 'get',
  });
}

/**
 * @description Nhận hồ sơ đặt hàng
 * @param {Number} param data.id {Number} Đặt hàngid
 * @param {String} param data.datas {String} Thông số phân trang
 */
export function getIntegralOrderRecord(data) {
  return request({
    url: `marketing/integral/order/status/${data.id}`,
    method: 'get',
    params: data.datas,
  });
}

/**
 * @description Gửi mẫu đơn gửi vận chuyển
 * @param {Number} param data.id {Number} Đặt hàngid
 * @param {Object} param data.datas {Object} thông tin biểu mẫu
 */
export function integralOrderPutDelivery(data) {
  return request({
    url: `marketing/integral/order/delivery/${data.id}`,
    method: 'put',
    data: data.datas,
  });
}

/**
 * @description Sửa đổi thông tin nhận xét
 * @param {Number} param data.id {Number} Đặt hàngid
 * @param {String} param data.remark {String} Bình luận
 */
export function integralOrderPutRemarkData(data) {
  return request({
    url: `marketing/integral/order/remark/${data.id}`,
    method: 'put',
    data: data.remark,
  });
}
/**
 * @description Điểm nhận xét
 * @param {Number} param data.id {Number} Đặt hàngid
 * @param {String} param data.remark {String} Bình luận
 */
export function setPointRecordMark(id, data) {
  return request({
    url: `marketing/point_record/remark/${id}`,
    method: 'post',
    data,
  });
}

/**
 * Lấy danh sách tất cả người giao hàng khi đặt hàng
 */
export function orderDeliveryList() {
  return request({
    url: 'marketing/integral/order/delivery/list',
    method: 'get',
  });
}

/**
 * Mẫu biểu mẫu điện tử
 * @param {com} data Số công ty chuyển phát nhanh
 */
export function orderExpressTemp(data) {
  return request({
    url: 'marketing/integral/order/express/temp',
    method: 'get',
    params: data,
  });
}
/**
 * Danh sách thống kê điểm
 * @param {com} data
 */
export function pointRecordList(data) {
  return request({
    url: 'marketing/point_record',
    method: 'get',
    params: data,
  });
}
/**
 * Danh sách thống kê điểm
 * @param {com} data
 */
export function pointRecordRemark(id, data) {
  return request({
    url: `marketing/point_record/remark/${id}`,
    method: 'post',
    data,
  });
}

export function orderSheetInfo() {
  return request({
    url: 'marketing/integral/order/sheet_info',
    method: 'get',
  });
}
/**
 * Thống kê điểm hàng đầu
 * @param {com} data
 */
export function getPointBasic(data) {
  return request({
    url: 'marketing/point/get_basic',
    method: 'get',
    params: data,
  });
}

/**
 * Biểu đồ đường thống kê điểm
 * @param {com} data
 */
export function getPointTrend(data) {
  return request({
    url: 'marketing/point/get_trend',
    method: 'get',
    params: data,
  });
}

/**
 * @description Phân tích nguồn điểm
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function getChannel(params) {
  return request({
    url: '/marketing/point/get_channel',
    method: 'get',
    params,
  });
}
/**
 * @description Phân tích mức tiêu thụ điểm
 * @param {Object} param data {Object} Tham số truyền theo giá trị
 */
export function getType(params) {
  return request({
    url: '/marketing/point/get_type',
    method: 'get',
    params,
  });
}

/**
 * Thống kê tiêu diệt chớp nhoáng
 * @param {*} id
 * @param {*} params
 * @returns
 */
export function getseckillStatistics(id, params) {
  return request({
    url: `marketing/seckill/statistics/head/${id}`,
    method: 'get',
    params,
  });
}

/**
 * Flash kill người tham gia
 * @param {*} id
 * @param {*} params
 * @returns
 */
export function getseckillStatisticsPeople(id, params) {
  return request({
    url: `marketing/seckill/statistics/people/${id}`,
    method: 'get',
    params,
  });
}

/**
 * Đơn hàng flash sale
 * @param {*} id
 * @param {*} params
 * @returns
 */
export function getseckillStatisticsOrder(id, params) {
  return request({
    url: `marketing/seckill/statistics/order/${id}`,
    method: 'get',
    params,
  });
}

/**
 * Thống kê nhóm nhóm
 * @param {*} id
 * @param {*} params
 * @returns
 */
export function getcombinationStatistics(id, params) {
  return request({
    url: `marketing/combination/statistics/head/${id}`,
    method: 'get',
    params,
  });
}

/**
 * Danh sách nhóm nhóm
 * @param {*} id
 * @param {*} params
 * @returns
 */
export function getcombinationStatisticsPeople(id, params) {
  return request({
    url: `marketing/combination/statistics/list/${id}`,
    method: 'get',
    params,
  });
}

/**
 * Thứ tự nhóm
 * @param {*} id
 * @param {*} params
 * @returns
 */
export function getcombinationStatisticsOrder(id, params) {
  return request({
    url: `marketing/combination/statistics/order/${id}`,
    method: 'get',
    params,
  });
}

/**
 * Thống kê mặc cả
 * @param {*} id
 * @param {*} params
 * @returns
 */
export function getbargainStatistics(id, params) {
  return request({
    url: `marketing/bargain/statistics/head/${id}`,
    method: 'get',
    params,
  });
}

/**
 * Danh sách mặc cả
 * @param {*} id
 * @param {*} params
 * @returns
 */
export function getbargainStatisticsPeople(id, params) {
  return request({
    url: `marketing/bargain/statistics/list/${id}`,
    method: 'get',
    params,
  });
}

/**
 * lệnh mặc cả
 * @param {*} id
 * @param {*} params
 * @returns
 */
export function getbargainStatisticsOrder(id, params) {
  return request({
    url: `marketing/bargain/statistics/order/${id}`,
    method: 'get',
    params,
  });
}
/**
 * Danh sách phần thưởng đăng nhập
 * @param {com} data
 */
export function signRewards(data) {
  return request({
    url: 'marketing/sign/rewards',
    method: 'get',
    params: data,
  });
}
/**
 * Đã thêm phần thưởng đăng nhập
 * @param {com} data
 */
export function addSignRewards(data) {
  return request({
    url: 'marketing/sign/add_rewards',
    method: 'get',
    params: data,
  });
}
/**
 * Phần thưởng đăng nhập của biên tập viên
 */
export function editSignRewards(id) {
  return request({
    url: 'marketing/sign/edit_rewards/' + id,
    method: 'get',
  });
}

/**
 * Chỉnh sửa lễ cưới
 */
export function editNewbie(data) {
  return request({
    url: 'user/new_gift/save',
    method: 'post',
    data,
  });
}
/**
 * Chỉnh sửa lễ cưới
 */
export function getNewbie(data) {
  return request({
    url: 'user/new_gift',
    method: 'get',
  });
}

/**
 * Tham gia nhóm và thành lập nhóm ngay lập tức
 */
export function combineJoinApi(id) {
  return request({
    url: 'marketing/combination/immediately/' + id,
    method: 'get',
  });
}
