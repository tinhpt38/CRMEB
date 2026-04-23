// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2024 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

import request from "@/utils/request.js";
/**
 * 
 * Tất cả các giao diện hoạt động bao gồm: mua nhóm, thương lượng giá, flash sale
 *
 */

/**
 * Danh sách nhóm nhóm
 * 
 */
export function getCombinationList(data) {
	return request.get('combination/list', data, {
		noAuth: true
	});
}

/**
 * Chi tiết nhóm nhóm
 * 
 */
export function getCombinationDetail(id) {
	return request.get('combination/detail/' + id);
}

/**
 * Tham gia một nhóm, bắt đầu một nhóm
 */
export function getCombinationPink(id) {
	return request.get("combination/pink/" + id);
}

/**
 * Tham gia nhóm Hủy nhóm
 */
export function postCombinationRemove(data) {
	return request.post("combination/remove", data);
}

/**
 * Danh sách mặc cả
 */
export function getBargainList(data) {
	return request.get("bargain/list", data, {
		noAuth: true
	});
}

/**
 * Băng chuyền nhóm
 * 
 */
export function getCombinationBannerList(data) {
	return request.get('combination/banner_list', data, {
		noAuth: true
	});
}

/**
 * Số người trong nhóm
 * 
 */
export function getPink(data) {
	return request.get('pink', data, {
		noAuth: true
	});
}

/**
 * 
 * Danh sách mặc cả(Đã tham gia)
 * @param object data
 */
export function getBargainUserList(data) {
	return request.get('bargain/user/list', data);
}


/**
 * Chi tiết sản phẩm khuyến mại
 */
export function getBargainDetail(id, uid) {
	return request.get(`bargain/detail/${id}?bargainUid=${uid}`);
}

/**
 * Mặc cả Cho phép mặc cả thông tin người dùng
 */
export function postBargainStartUser(data) {
	return request.post("bargain/start/user", data);
}

/**
 * Đang đàm phán
 */
export function postBargainStart(bargainId) {
	return request.post("bargain/start", {
		bargainId: bargainId
	});
}

/**
 * Mặc cả Giúp bạn bè mặc cả
 */
export function postBargainHelp(data) {
	return request.post("bargain/help", data);
}

/**
 * Mặc cả giá, giảm số lượng
 */
export function postBargainHelpPrice(data) {
	return request.post("bargain/help/price", data);
}

/**
 * Thương lượng Trợ giúp thương lượng
 */
export function postBargainHelpList(data) {
	return request.post("bargain/help/list", data);
}

/**
 * Mặc cả: Mặc cả tổng số người, số lượng còn lại, thanh tiến trình và mức giá đã giảm.
 */
export function postBargainHelpCount(data) {
	return request.post("bargain/help/count", data);
}

/**
 * Mặc cả số lượt xem/chia sẻ/tham gia
 */
export function postBargainShare(bargainId) {
	return request.post("bargain/share", {
		bargainId: bargainId
	});
}

/**
 * Khoảng thời gian của sản phẩm flash sale
 * 
 */
export function getSeckillIndexTime() {
	return request.get('seckill/index', {}, {
		noAuth: true
	});
}

/**
 * Danh sách sản phẩm Flashsale
 * @param int time
 * @param object data
 */
export function getSeckillList(time, data) {
	return request.get('seckill/list/' + time, data, {
		noAuth: true
	});
}

/**
 * Chi tiết sản phẩm Flashsale
 * @param int id
 */
export function getSeckillDetail(id, data) {
	return request.get(`seckill/detail/${id}`, data);
}

/**
 * áp phích mặc cả
 * @param object data
 * 
 */
export function getBargainPoster(data) {
	return request.post('bargain/poster', data)
}

/**
 * Áp phích chia sẻ nhóm
 * @param object data
 * 
 */
export function getCombinationPoster(data) {
	return request.post('combination/poster', data)
}

/**
 * Giảm giá Hủy bỏ
 */
export function getBargainUserCancel(data) {
	return request.post("bargain/user/cancel", data);
}

/**
 * Lấy mã QR của chương trình mini flash sale
 */
export function seckillCode(id, data) {
	return request.get("seckill/code/" + id, data);
}

/**
 * Lấy mã QR của chương trình mini mua nhóm
 */
export function scombinationCode(id) {
	return request.get("combination/code/" + id);
}

/**
 * Nhận chi tiết poster giá hời
 */
export function getCombinationPosterData(id) {
	return request.get("combination/poster_info/" + id);
}


/**
 * Nhận chi tiết poster giá hời
 */
export function getBargainPosterData(id) {
	return request.get("bargain/poster_info/" + id);
}

/**
 * Nhận chi tiết thứ tự điểm
 */
export function integralOrderConfirm(data) {
	return request.post('store_integral/order/confirm', data);
}

/**
 * Nhận tạo đơn hàng điểm
 */
export function integralOrderCreate(data) {
	return request.post('store_integral/order/create', data);
}
/**
 * Nhận chi tiết thứ tự điểm
 * @param string cartId
 */
export function integralOrderDetails(order) {
	return request.get(`store_integral/order/detail/${order}`);
}

/**
 * Chi tiết sản phẩm điểm
 * @param int id
 * 
 */
export function getIntegralProductDetail(id) {
	return request.get('store_integral/detail/' + id, {}, {
		noAuth: true
	});
}

/**
 * Danh sách sản phẩm trung tâm điểm
 * @param object data
 */
export function getStoreIntegralList(data) {
	return request.get('store_integral/list', data, {
		noAuth: true
	});
}

/**
 * Danh sách đổi điểm
 * @param object data
 */
export function getIntegralOrderList(data) {
	return request.get('store_integral/order/list', data);
}

/**
 * Chi tiết đổi điểm
 */
export function getLogisticsDetails(orderId) {
	return request.get(`store_integral/order/express/${orderId}`);
}

/**
 * Xác nhận đã nhận được lệnh đổi điểm
 * @param object data
 */
export function orderTake(data) {
	return request.post(`store_integral/order/take`, data);
}

/**
 * Xóa lệnh đổi điểm
 * @param object data
 */
export function orderDel(data) {
	return request.post(`store_integral/order/del`, data);
}

/**
 * Danh sách sản phẩm trước khi bán
 */
export function getPresellList(data) {
	return request.get("advance/list", data);
}