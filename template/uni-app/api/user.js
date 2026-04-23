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
 * Lấy thông tin người dùng
 * 
 */
export function getUserInfo() {
	return request.get('user');
}


/**
 * Thiết lập chia sẻ người dùng
 * 
 */
export function userShare() {
	return request.post('user/share');
}

/**
 * h5Đăng nhập người dùng
 * @param mật khẩu tài khoản người dùng đối tượng dữ liệu
 */
export function loginH5(data) {
	return request.post("login", data, {
		noAuth: true
	});
}

/**
 * h5Đăng nhập số điện thoại di động của người dùng
 * Đối tượng dữ liệu @param Số điện thoại di động của người dùng chỉ có thể là
 */
export function loginMobile(data) {
	return request.post("login/mobile", data, {
		noAuth: true
	});
}

/**
 * Mã xác minhkey
 */
export function getCodeApi() {
	return request.get("verify_code", {}, {
		noAuth: true
	});
}

/**
 * h5Người dùng gửi mã xác minh
 * Đối tượng dữ liệu @param Số điện thoại di động của người dùng
 */
export function registerVerify(data) {
	return request.post("register/verify", data, {
		noAuth: true
	});
}

/**
 * h5Đăng ký số điện thoại di động của người dùng
 * Đối tượng dữ liệu @param Số điện thoại người dùng Mã xác minh Mật khẩu
 */
export function register(data) {
	return request.post("register", data, {
		noAuth: true
	});
}

/**
 * Người dùng số điện thoại di động thay đổi mật khẩu
 * Đối tượng dữ liệu @param Số điện thoại người dùng Mã xác minh Mật khẩu
 */
export function registerReset(data) {
	return request.post("register/reset", data, {
		noAuth: true
	});
}

/**
 * Nhận menu trung tâm người dùng
 *
 */
export function getMenuList() {
	return request.get("menu/user", {}, {
		noAuth: true
	});
}

/*
 * Đăng nhập thông tin người dùng
 * */
export function postSignUser(sign) {
	return request.post("sign/user", sign);
}

/**
 * Nhận cấu hình đăng ký
 * 
 */
export function getSignConfig() {
	return request.get('sign/config')
}

/**
 * Nhận danh sách đăng ký
 * @param object data
 */
export function getSignList(data) {
	return request.get('sign/list', data);
}

/**
 * Đăng nhập người dùng
 */
export function setSignIntegral() {
	return request.post('sign/integral')
}

/**
 * Danh sách đăng ký(năm)
 * @param object data
 * 
 */
export function getSignMonthList(data) {
	return request.get('sign/month', data)
}

/**
 * trạng thái hoạt động
 * 
 */
export function userActivity() {
	return request.get('user/activity');
}

/*
 * Chi tiết quỹ（types|0=tất cả,1=Sự tiêu thụ,2=nạp tiền,3=Hạ giá,4=Rút tiền mặt）
 * */
export function getCommissionInfo(q, types) {
	return request.get("spread/commission/" + types, q);
}

/*
 * Kỷ lục điểm
 * */
export function getIntegralList(q) {
	return request.get("integral/list", q);
}

/**
 * Nhận hình ảnh poster phân phối
 * 
 */
export function spreadBanner() {
	//#ifdef H5 || APP-PLUS
	return request.get('spread/banner', {
		type: 2
	});
	//#endif
	//#ifdef MP
	return request.get('spread/banner', {
		type: 1
	});
	//#endif

}

/**
 *
 * Có được người dùng khuyến mãi cấp một và cấp hai
 * @param object data
 */
export function spreadPeople(data) {
	return request.post('spread/people', data);
}

/**
 * 
 * Hoa hồng khuyến mại/số tiền rút
 * @param int type
 */
export function spreadCount(type) {
	return request.get('spread/count/' + type);
}

/*
 * dữ liệu khuyến mãi
 * */
export function getSpreadInfo() {
	return request.get("commission");
}


/**
 * 
 * Đơn hàng khuyến mãi
 * @param object data
 */
export function spreadOrder(data) {
	return request.post('spread/order', data);
}

/**
 * 
 * Phòng kinh doanh/Lệnh khuyến mại
 * @param object data
 */
export function divisionOrder(data) {
	return request.post('division/order', data);
}

/*
 * Nhận thứ hạng của người quảng bá
 * */
export function getRankList(q) {
	return request.get("rank", q);
}

/*
 * Nhận xếp hạng hoa hồng
 * */
export function getBrokerageRank(q) {
	return request.get("brokerage_rank", q);
}

/**
 * Đơn xin rút tiền
 * @param object data
 */
export function extractCash(data) {
	return request.post('extract/cash', data)
}

/**
 * Ngân hàng rút tiền/số tiền rút tối thiểu
 * 
 */
export function extractBank() {
	return request.get('extract/bank');
}

/**
 * Danh sách cấp thành viên
 * 
 */
export function userLevelGrade() {
	return request.get('user/level/grade');
}

/**
 * Nhận một nhiệm vụ cấp độ nhất định
 * @param nhiệm vụ int idid
 */
export function userLevelTask(id) {
	return request.get('user/level/task/' + id);
}


/**
 * Kiểm tra xem người dùng có thể trở thành thành viên không
 * 
 */
export function userLevelDetection() {
	return request.get('user/level/detection');
}

/**
 * 
 * danh sách địa chỉ
 * @param object data
 */
export function getAddressList(data) {
	return request.get('address/list', data);
}

/**
 * Đặt địa chỉ mặc định
 * @param int id
 */
export function setAddressDefault(id) {
	return request.post('address/default/set', {
		id: id
	})
}

/**
 * Sửa đổi Thêm địa chỉ
 * @param object data
 */
export function editAddress(data) {
	return request.post('address/edit', data);
}

/**
 * Xóa địa chỉ
 * @param int id
 * 
 */
export function delAddress(id) {
	return request.post('address/del', {
		id: id
	})
}

/**
 * Nhận một địa chỉ duy nhất
 * @param int id 
 */
export function getAddressDetail(id) {
	return request.get('address/detail/' + id);
}

/**
 * Sửa đổi thông tin người dùng
 * @param object
 */
export function userEdit(data) {
	return request.post('user/edit', data);
}

/*
 * Đăng xuất
 * */
export function getLogout() {
	return request.get("logout");
}
/**
 * Nạp tiền chương trình nhỏ
 * 
 */
export function rechargeRoutine(data) {
	return request.post('recharge/routine', data)
}
/*
 * Nạp tiền tài khoản chính thức
 * 
 */
export function rechargeWechat(data) {
	return request.post("recharge/wechat", data);
}
/*
 * Nạp tiền tài khoản chính thức
 * 
 */
export function recharge(data) {
	return request.post("recharge/recharge", data);
}
/**
 * Nhận địa chỉ mặc định
 * 
 */
export function getAddressDefault() {
	return request.get('address/default');
}

/**
 * Lựa chọn số tiền nạp
 */
export function getRechargeApi() {
	return request.get("recharge/index");
}

/**
 * Hồ sơ đăng nhập
 */
export function setVisit(data) {
	return request.post('user/set_visit', {
		...data
	}, {
		noAuth: true
	});
}

/**
 * Danh sách dịch vụ khách hàng
 */
export function serviceList() {
	return request.get("user/service/list");
}
/**
 * Chi tiết dịch vụ khách hàng
 */
export function getChatRecord(data) {
	return request.get("v2/user/service/record", data);
}

/**
 * Trình quảng bá liên kết âm thầm
 * @param {Object} puid
 */
export function spread(puid) {
	return request.post("user/spread", puid);
}

/**
 * Chi tiết thành viên
 */
export function getlevelInfo() {
	return request.get("user/level/info");
}

/**
 * Danh sách trải nghiệm thành viên
 */
export function getlevelExpList(data) {
	return request.get("user/level/expList", data);
}


/**
 * Đăng nhập số điện thoại di động trực tiếp WeChat
 */
export function phoneWxSilenceAuth(data) {
	return request.post('v2/phone_wx_silence_auth', data, {
		noAuth: true
	});
}

/**
 * Chương trình mini đăng nhập trực tiếp bằng số điện thoại di động
 */
export function phoneSilenceAuth(data) {
	return request.post('v2/phone_silence_auth', data, {
		noAuth: true
	});
}

/**
 * Danh sách hóa đơn người dùng
 * @param {Object} data
 */
export function invoiceList(data) {
	return request.get('v2/invoice', data, {
		noAuth: true
	});
}

/**
 * Người dùng đã thêm|Sửa hóa đơn
 * @param {Object} data
 */
export function invoiceSave(data) {
	return request.post('v2/invoice/save', data, {
		noAuth: true
	});
}

/**
 * Người dùng xóa hóa đơn
 * @param {Object} data
 */
export function invoiceDelete(id) {
	return request.get('v2/invoice/del/' + id);
}

/**
 * Nhận hóa đơn mặc định của người dùng
 * @param {Object} type
 */
export function invoiceDefault(type) {
	return request.get('v2/invoice/get_default/' + type);
}

/**
 * Chi tiết hóa đơn riêng của người dùng
 * @param {Object} id
 */
export function invoiceDetail(id) {
	return request.get('v2/invoice/detail/' + id);
}

/**
 * Đơn đặt hàng để lập hóa đơn
 * @param {Object} id
 */
export function invoiceOrder(data) {
	return request.post('v2/order/make_up_invoice', data);
}

/**
 * Đăng ký xuất hóa đơn theo chi tiết đơn hàng
 * @param {Object} id
 */
export function makeUpinvoice(data) {
	return request.post('v2/order/make_up_invoice', data);
}

/**
 * Giao diện chính của thẻ thành viên
 */
export function memberCard() {
	return request.get('user/member/card/index');
}

/**
 * Bí mật thẻ nhận thẻ thành viên
 * @param {Object} data
 */
export function memberCardDraw(data) {
	return request.post('user/member/card/draw', data);
}

/**
 * Mua thẻ thành viên
 * @param {Object} data
 */
export function memberCardCreate(data) {
	return request.post('user/member/card/create', data);
}

/**
 * Phiếu giảm giá thành viên
 */
export function memberCouponsList() {
	return request.get('user/member/coupons/list');
}

/**
 * svipSản phẩm được đề xuất
 * @param {Object} id
 */
export function groomList(id, data) {
	return request.get(`groom/list/${id}`, data);
}

/**
 * Thành viên trả phí kết thúc
 * @param {Object} data
 */
export function memberOverdueTime(data) {
	return request.get('user/member/overdue/time', data);
}

/**
 * Nhận phiên bản mới của thông tin áp phích được chia sẻ
 * 
 */
export function spreadMsg() {
	return request.get('user/spread_info');
}


/**
 * Chuyển liên kết hình ảnhbase64
 * 
 */
export function imgToBase(data) {
	return request.post('image_base64', data);
}

/**
 * Lấy mã QR của chương trình mini
 * 
 */
export function routineCode(data) {
	return request.get('user/routine_code', data);
}

/**
 * Trung tâm tin nhắn
 */
export function serviceRecord(data) {
	return request.get('user/record', data);
}

/**
 * Trung tâm tin nhắn-Danh sách tin nhắn trang web
 */
export function messageSystem(data) {
	return request.get('user/message_system/list', data);
}

/**
 * Chi tiết danh sách tin nhắn của Trung tâm-Trang web
 */
export function getMsgDetails(id) {
	return request.get('user/message_system/detail/' + id);
}

/**
 * Trung tâm tin nhắn đã đọc/xóa tin nhắn
 */
export function msgLookDel(data) {
	return request.get('user/message_system/edit_message', data);
}

/**
 * Đăng nhập tài khoản Apple
 * @param {Object} data
 */
export function appleLogin(data) {
	return request.post('apple_login', data, {
		noAuth: true
	});
}

/*
 * Nhận thỏa thuận về quyền riêng tư
 * */
export function getUserAgreement(type) {
	return request.get(`get_agreement/${type}`, {}, {
		noAuth: true
	});
}

/**
 * Nhận danh sách các cấp độ phân phối
 * @param nhiệm vụ int idid
 */
export function agentLevelList() {
	return request.get('v2/agent/level_list');
}

/**
 * Nhận danh sách nhiệm vụ phân phối
 * @param nhiệm vụ int idid
 */
export function agentLevelTaskList(id) {
	return request.get('v2/agent/level_task_list?id=' + id);
}

/**
 * Nhận chi tiết thanh toán
 * @param nhiệm vụ int idid
 */
export function friendDetail(id) {
	return request.get('order/friend_detail?order_id=' + id);
}

/**
 * danh sách nhân viên
 * @param object data
 * 
 */
export function clerkPeople(data) {
	return request.get('agent/get_staff_list', data)
}

/**
 * 
 * Tỷ lệ nhân viên
 * @param object data
 */
export function setClerkPercent(data) {
	return request.post('agent/set_staff_percent', data);
}

/**
 * 
 * Xóa nhân viên
 * @param object data
 */
export function delClerkPercent(id) {
	return request.get(`agent/del_staff/${id}`);
}

/**
 * Đăng xuất người dùng
 * @param int id
 * 
 */
export function cancelUser() {
	return request.get('user_cancel');
}
/**
 * Nhận loại đa ngôn ngữ
 */

export function getLangList() {
	return request.get('get_lang_type_list', {}, {
		noAuth: true
	})
}

/**
 * Nhận nhiều ngôn ngữJSON
 */

export function getLangJson() {
	return request.get('get_lang_json', {}, {
		noAuth: true
	})
}

/**
 * Nhận xem có nên chuyển đổi nhiều ngôn ngữ hay không
 */

export function getLangVersion() {
	return request.get('lang_version', {}, {
		noAuth: true
	})
}

/**
 * 
 * Chương trình mini liên kết số điện thoại di động
 * @param object data
 */
export function mpBindingPhone(data) {
	return request.post('v2/routine/binding_phone', data);
}

/**
 *  Công tắc nhắc nhở đăng nhập
 */

export function changeRemindStatus(status) {
	return request.get(`sign/remind/${status}`, {}, {
		noAuth: true
	})
}


/**
 * Ràng buộc nhân viên
 * 
 */
export function spreadAgent(data) {
	return request.post(`agent/spread`, data);
}

// Người dùng xác nhận chuyển nhượng người bán
export function transferInfoApi(data) {
	return request.get(`transfer/info`, data);
}