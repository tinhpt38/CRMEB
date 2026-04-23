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
 * Nhận danh sách giỏ hàng
 * @param numType boolean số lượng giỏ hàng thực sự,false=Giỏ hàng số lượng sản phẩm
 */
export function getCartCounts(numType) {
	return request.get("cart/count", {
		numType: numType === undefined ? 0 : numType
	});
}
/**
 * Nhận danh sách giỏ hàng
 * 
 */
export function getCartList(data) {
	return request.get("cart/list", data);
}

/**
 * Sửa đổi giỏ hàng
 * 
 */
export function getResetCart(data) {
	return request.post("v2/reset_cart", data);
}

/**
 * Sửa đổi số lượng giỏ hàng
 * @param int cartId id giỏ hàng
 * @param số lượng sửa đổi số int
 */
export function changeCartNum(cartId, number) {
	return request.post("cart/num", {
		id: cartId,
		number: number
	});
}
/**
 * Xóa giỏ hàng
 * @param object ids join(',') cắt thành chuỗi
 */
export function cartDel(ids) {
	if (typeof ids === 'object')
		ids = ids.join(',');
	return request.post('cart/del', {
		ids: ids
	});
}
/**
 * danh sách đặt hàng
 * @param object data
 */
export function getOrderList(data) {
	return request.get('order/list', data);
}

/**
 * Đặt hàng thông tin sản phẩm
 * @param string unique 
 */
export function orderProduct(unique) {
	return request.post('order/product', {
		unique: unique
	});
}

/**
 * Đánh giá đơn hàng
 * @param object data
 * 
 */
export function orderComment(data) {
	return request.post('order/comment', data);
}

/**
 * Thanh toán đơn hàng
 * @param object data
 */
export function orderPay(data) {
	return request.post('order/pay', data);
}

/**
 * Xóa các đơn hàng đã hoàn tiền và bị từ chối hoàn tiền
 * @param string uni
 * 
 */
export function refundOrderDel(uni) {
	return request.get('order/refund/del/' + uni, {});
}

/**
 * Thống kê đơn hàng
 */
export function orderData() {
	return request.get('order/data')
}

/**
 * Hủy đơn hàng
 * @param string id
 * 
 */
export function orderCancel(id) {
	return request.post('order/cancel', {
		id: id
	});
}

/**
 * Xóa đơn hàng đã hoàn thành
 * @param string uni
 * 
 */
export function orderDel(uni) {
	return request.post('order/del', {
		uni: uni
	});
}

/**
 * Chi tiết đơn hàng quà tặng
 * @param string uni 
 */
export function getGiftOrderDetail(id) {
	return request.get('order/gift_detail/' + id);
}
/**
 * Chi tiết đặt hàng
 * @param string uni 
 */
export function getOrderDetail(uni, cart_id) {
	return request.get('order/detail/' + uni + `${cart_id ? `/${cart_id}`:''}`);
}
/**
 * Chi tiết đơn hàng hoàn tiền
 * @param string uni 
 */
export function getRefundOrderDetail(uni, cart_id) {
	return request.get('order/refund_detail/' + uni + `${cart_id ? `/${cart_id}`:''}`);
}

/**
 * Đặt hàng lại
 * @param string uni
 * 
 */
export function orderAgain(uni) {
	return request.post('order/again', {
		uni: uni
	});
}

/**
 * Biên nhận đơn hàng
 * @param string uni
 * 
 */
export function orderTake(uni) {
	return request.post('order/take', {
		uni: uni
	});
}

/**
 * Truy vấn thông tin hậu cần đặt hàng
 * @returns {*}
 */
export function express(uni, type) {
	return request.get("order/express/" + uni + `${type?'/refund':''}`);
}
/**
 * Truy vấn thông tin hậu cần đặt hàng
 * @returns {*}
 */
export function adminExpress(uni, type) {
	return request.get("admin/order/express/" + uni + `${type?'/refund':''}`);
}

/**
 * Nhận lý do hoàn tiền
 * 
 */
export function ordeRefundReason() {
	return request.get('order/refund/reason');
}

/**
 * Đánh giá hoàn tiền đơn hàng
 * @param object data
 */
export function orderRefundVerify(data) {
	return request.post('order/refund/verify', data);
}

/**
 * Xác nhận đơn hàng Nhận thông tin chi tiết đơn hàng
 * @param string cartId
 */
export function orderConfirm(data) {
	return request.post('order/confirm', data);
}

/**
 * Biết liệu chuyển phát nhanh và nhận hàng tại cửa hàng có hiển thị trên trang xác nhận đơn hàng hay không
 * @param string cartId
 */
export function checkShipping(cartId, news) {
	return request.post('order/check_shipping', {
		cartId,
		'new': news
	});
}

/**
 * Nhận phiếu giảm giá có thể được sử dụng với số tiền hiện tại
 * @param string price
 * 
 */
export function getCouponsOrderPrice(price, data) {
	return request.get('coupons/order/' + price, data)
}

/**
 * Tạo đơn hàng
 * @param string key
 * @param object data
 * 
 */
export function orderCreate(key, data) {
	return request.post('order/create/' + key, data);
}

/**
 * Tính số tiền đặt hàng
 * @param key
 * @param data
 * @returns {*}
 */
export function postOrderComputed(key, data) {
	return request.post("order/computed/" + key, data);
}

/**
 * Phiếu giảm giá đặt hàng
 * @param key
 * @param data
 * @returns {*}
 */
export function orderCoupon(orderId) {
	return request.post("v2/order/product_coupon/" + orderId);
}

/**
 * Tính số tiền thanh toán ngoại tuyến của thành viên
 * @param {Object} data
 */
export function offlineCheckPrice(data) {
	return request.post("order/offline/check/price", data);
}

/**
 * Quét mã để thanh toán ngoại tuyến
 * @param {Object} data
 */
export function offlineCreate(data) {
	return request.post("order/offline/create", data);
}

/**
 * Chuyển đổi phương thức thanh toán
 */
export function orderOfflinePayType() {
	return request.get('order/offline/pay/type');
}

/**
 * Hồ sơ hóa đơn
 */
export function orderInvoiceList(data) {
	return request.get('v2/order/invoice_list', data);
}

/**
 * Chi tiết đơn hàng hóa đơn
 * @param {Object} id
 */
export function orderInvoiceDetail(id) {
	return request.get(`v2/order/invoice_detail/${id}`);
}


/**
 * thanh toán Alipay
 * @param {Object} key
 * @param {Object} quitUrl
 */
export function aliPay(key, quitUrl) {
	return request.get('ali_pay', {
		key,
		quitUrl
	}, {
		noAuth: true
	});
}


/**
 * Gửi số đơn đặt hàng hậu cần trả lại
 * @param {Object} data
 */
export function refundExpress(data) {
	return request.post("order/refund/express", data);
}

/**
 * Danh sách giỏ hàng chuyên mục
 */
export function vcartList() {
	return request.get("v2/cart_list");
}

/**
 * Danh sách sản phẩm hoàn tiền
 */
export function refundGoodsList(orderId) {
	return request.get(`order/refund/cart_info/${orderId}`);
}

/**
 * Danh sách sản phẩm xin hoàn tiền
 */
export function postRefundGoods(data) {
	return request.post(`order/refund/cart_info`, data);
}

/**
 * Gửi sản phẩm hoàn tiền
 */
export function returnGoodsSubmit(id, data) {
	return request.post(`order/refund/apply/${id}`, data);
}

/**
 * Danh sách đơn hàng mới phiên bản 2.1
 * @param object data
 */
export function getNewOrderList(data) {
	return request.get('order/refund/list', data);
}

/**
 * Chi tiết đơn hàng hoàn tiền
 * @param string uni 
 */
export function refundOrderDetail(uni) {
	return request.get('order/refund/detail/' + uni);
}

/**
 * Từ bỏ việc xin hoàn tiền
 * @param string uni 
 */
export function cancelRefundOrder(uni) {
	return request.post('order/refund/cancel/' + uni);
}

/**
 * Kiểm tra thông tin đơn hàng
 * @param object data
 */
export function getCashierOrder(orderId, type) {
	return request.get(`order/cashier/${orderId}/${type}`);
}

/**
 * Lấy địa chỉ hóa đơn
 * @param object data
 */
export function getInvoiceLink(id) {
	return request.get(`v2/order/down_invoice/${id}`);
}

/**
 * nhận quà
 * @param orderId
 * @param data
 */
export function orderReceiveGift(orderId, data) {
	return request.post("order/receive_gift/" + orderId, data);
}