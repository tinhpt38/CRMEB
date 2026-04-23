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
 * Nhận chi tiết sản phẩm
 * @param int id
 * 
 */
export function getProductDetail(id) {
	return request.get('product/detail/' + id, {}, {
		noAuth: true
	});
}

/**
 * Quảng cáo mã QR chia sẻ sản phẩm
 * @param int id
 */
// #ifdef H5  || APP-PLUS
export function getProductCode(id) {
	return request.get('product/code/' + id, {}, {
		noAuth: true
	});
}
// #endif
// #ifdef MP
export function getProductCode(id) {
	return request.get('product/code/' + id, {
		user_type: 'routine',
	}, {
		noAuth: true
	});
}
// #endif

/**
 * Thêm vào mục yêu thích
 * @param int id
 * @param string category product=Sản phẩm thông thường,product_seckill=sản phẩm khuyến mại chớp nhoáng
 */
export function collectAdd(id, category) {
	return request.post('collect/add', {
		id: id,
		'product': category === undefined ? 'product' : category
	});
}

/**
 * Xóa sản phẩm yêu thích
 * @param int id
 * @param string category product=Sản phẩm thông thường,product_seckill=sản phẩm khuyến mại chớp nhoáng
 */
export function collectDel(id, category) {
	return request.post('collect/del', {
		id: id,
		category: category === undefined ? 'product' : category
	});
}

/**
 * Thêm mua xe
 * 
 */
export function postCartAdd(data) {
	return request.post('cart/add', data);
}

/**
 * Nhận danh sách danh mục
 * 
 */
export function getCategoryList() {
	return request.get('category', {}, {
		noAuth: true
	});
}

/**
 * Nhận danh sách sản phẩm
 * @param object data
 */
export function getProductslist(data) {
	return request.get('products', data, {
		noAuth: true
	});
}



/**
 * Nhận sản phẩm được đề xuất
 * 
 */
export function getProductHot(page, limit) {
	return request.get("product/hot", {
		page: page === undefined ? 1 : page,
		limit: limit === undefined ? 4 : limit
	}, {
		noAuth: true
	});
}
/**
 * Bộ sưu tập hàng loạt
 *
 * @param số sản phẩm id đối tượng join(',') cắt thành chuỗi
 * @param string category 
 */
export function collectAll(id, category) {
	return request.post('collect/all', {
		id: id,
		category: category === undefined ? 'product' : category
	});
}

/**
 * Hình ảnh băng chuyền và thông tin sản phẩm của sản phẩm trang chủ
 * @param int type 
 * 
 */
export function getGroomList(type, data) {
	return request.get('groom/list/' + type, data, {
		noAuth: true
	});
}

/**
 * Nhận danh sách yêu thích
 * @param object data
 */
export function getCollectUserList(data) {
	return request.get('collect/user', data)
}

/**
 * Nhận đánh giá sản phẩm
 * @param int id
 * @param object data
 * 
 */
export function getReplyList(id, data) {
	return request.get('reply/list/' + id, data, {noAuth: true})
}

/**
 * Số lượng đánh giá sản phẩm và xếp hạng tích cực
 * @param int id
 */
export function getReplyConfig(id) {
	return request.get('reply/config/' + id);
}

/**
 * Nhận từ khóa tìm kiếm nhận
 * 
 */
export function getSearchKeyword() {
	return request.get('search/keyword', {}, {
		noAuth: true
	});
}

/**
 * Danh sách cửa hàng
 * @returns {*}
 */
export function storeListApi(data) {
	return request.get("store_list", data);
}

/**
 * Danh sách gói hàng
 * @param int id
 * 
 */
export function storeDiscountsList(id) {
	return request.get('store_discounts/list/' + id, {}, {
		noAuth: true
	});
}

/**
 * Thêm, bớt, sửa đổi khi mua xe
 * 
 */
export function postCartNum(data) {
	return request.post('v2/set_cart_num', data);
}
/**
 * Ứng dụng đại lý
 * 
 */
export function create(data) {
	return request.post(`agent/apply/${data.id}`, data);
}

/**
 * Quy tắc đại lý
 * @param object data
 */
export function getAgentAgreement(data) {
	return request.get('agent/get_agent_agreement', {}, {
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
 * Mã xác minhkey
 */
export function getCodeApi() {
	return request.get("verify_code", {}, {
		noAuth: true
	});
}
/**
 * Nhận thông tin mẫu đại lý
 */
export function getHistoryData() {
	return request.get("agent/apply/info", {}, {
		noAuth: true
	});
}

/**
 * Nhận các thuộc tính của trang chủ
 * @returns {*}
 */
export function getAttr(id, type) {
	return request.get("v2/get_attr/" + id + "/" + type);
}
/**
 * Lấy danh sách sản phẩm trang chủ (tất cả đều hoạt động）
 * @param object data
 */
export function getHomeProducts(data) {
	return request.get('home/products', data, {
		noAuth: true
	});
}

/**
 * Chi tiết trước khi bán
 * @returns {*}
 */
export function getPresellProductDetail(id) {
	return request.get("advance/detail/" + id);
}

/**
 * Nhận danh sách lịch sử duyệt web
 * @param object data
 */
export function getVisitList(data) {
	return request.get('user/visit_list', data)
}

/**
 * Nhận danh sách lịch sử duyệt web-xóa 
 * @param object data
 */
export function deleteVisitList(data) {
	return request.delete('user/visit', data)
}

/**
 * Đăng ký giao diện chi tiết nhà phân phối
 *
 */
export function userSpreadInfo() {
	return request.get("user/spread/apply/info");
}

/**
 * Ứng dụng phân phối
 * @param data
 * 
 */
export function spreadCreateApi(id, data) {
	return request.post(`user/spread/apply/${id}`, data);
}

/**
 * Nhận giá
 * 
 */
export function realPrice(id, unique) {
	return request.get(`product/real_price/${id}/${unique}`, {}, {
		noAuth: true
	});
}