// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2024 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

import {
	SUBSCRIBE_MESSAGE
} from '../config/cache.js';

export function auth() {
	let tmplIds = {};
	let messageTmplIds = uni.getStorageSync(SUBSCRIBE_MESSAGE);
	tmplIds = messageTmplIds ? JSON.parse(messageTmplIds) : {};
	return tmplIds;
}

/**
 * Id tin nhắn đăng ký sau khi thanh toán thành công
 * Đăng ký Xác nhận thông báo đã nhận Thanh toán đơn hàng thành công Nhắc nhở quản trị viên đơn hàng mới 
 */
export function openPaySubscribe() {
	let tmplIds = auth();
	return subscribe([
		tmplIds.order_pay_success,
		tmplIds.order_deliver_success,
		tmplIds.order_postage_success,
	]);
}

/**
 * Đặt hàng tin nhắn đăng ký liên quan
 * Giao hàng Giao hàng Hủy đơn hàng
 */
export function openOrderSubscribe() {
	let tmplIds = auth();
	return subscribe([
		tmplIds.order_take,
		tmplIds.integral_accout
	]);
}

/**
 * Đăng ký tin nhắn rút tiền
 * Thông báo thành công và thất bại
 */
export function openExtrctSubscribe() {
	let tmplIds = auth();
	return subscribe([
		tmplIds.user_extract
	]);
}

/**
 * Trận chiến nhóm thành công
 */
export function openPinkSubscribe() {
	let tmplIds = auth();
	return subscribe([
		tmplIds.order_user_groups_success
	]);
}

/**
 * Thương lượng thành công
 */
export function openBargainSubscribe() {
	let tmplIds = auth();
	return subscribe([
		tmplIds.bargain_success
	]);
}

/**
 * Hoàn tiền đơn hàng
 */
export function openOrderRefundSubscribe() {
	let tmplIds = auth();
	return subscribe([
		tmplIds.order_refund
	]);
}

/**
 * Nạp tiền thành công
 */
export function openRechargeSubscribe() {
	let tmplIds = auth();
	return subscribe([
		tmplIds.recharge_success
	]);
}

/**
 * Rút tiền thành công
 */
export function openRevenueSubscribe() {
	let tmplIds = auth();
	return subscribe([
		tmplIds.revenue_received
	]);
}

/**
 * Gọi lên giao diện đăng ký
 * mẫu tmplIds mảngid
 */
export function subscribe(subscrip443tionmessagee502call) {
	 let weChat = wx;
	return new Promise((reslove, reject) => {
		weChat.requestSubscribeMessage({
			tmplIds: subscrip443tionmessagee502call,
			success(res) {
				return reslove(res);
			},
			fail(res) {
				return reslove(res);
			}
		})
	});
}
