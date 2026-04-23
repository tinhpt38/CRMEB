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
 * Đăng nhập dịch vụ khách hàng
 * @param mật khẩu tài khoản người dùng đối tượng dữ liệu
 */
export function kefuLogin(data) {
  return request.post("login", data, {
    noAuth: true,
    kefu: true,
  });
}

/**
 * Lấy danh sách người dùng trò chuyện dịch vụ khách hàng ở bên trái
 * @constructor
 */
export function record(data) {
  return request.get("user/record", data, {
    noAuth: true,
    kefu: true,
  });
}

/**
 * Kỹ năng phục vụ khách hàng
 * @constructor
 */
export function speeChcraft(data) {
  return request.get("service/speechcraft", data, {
    noAuth: true,
    kefu: true,
  });
}

/**
 * Danh sách chuyển dịch vụ khách hàng
 * @constructor
 */
export function transferList(data) {
  return request.get("service/transfer_list", data, {
    noAuth: true,
    kefu: true,
  });
}

/**
 * Hồ sơ mua sản phẩm
 * @constructor
 */
export function productCart(id, data) {
  return request.get("product/cart/" + id, data, {
    noAuth: true,
    kefu: true,
  });
}

/**
 * Đồ nóng
 * @constructor
 */
export function productHot(id, data) {
  return request.get("product/hot/" + id, data, {
    noAuth: true,
    kefu: true,
  });
}

/**
 * Chi tiết sản phẩm
 * @constructor
 */
export function productVisit(id, data) {
  return request.get("product/visit/" + id, data, {
    noAuth: true,
    kefu: true,
  });
}

/**
 * Danh sách trò chuyện của người dùng dịch vụ khách hàng
 * @constructor
 */
export function serviceList(data) {
  return request.get("service/list", data, {
    noAuth: true,
    kefu: true,
  });
}

/**
 * Chuyển dịch vụ khách hàng
 * @constructor
 */
export function serviceTransfer(data) {
  return request.post("service/transfer", data, {
    noAuth: true,
    kefu: true,
  });
}

/**
 * Chi tiết dịch vụ khách hàng
 * @constructor
 */
export function serviceInfo(data) {
  return request.get("service/info", data, {
    noAuth: true,
    kefu: true,
  });
}

/**
 * Thông tin tiêu đề phản hồi dịch vụ khách hàng
 * @constructor
 */
export function serviceFeedBack() {
  return request.get("user/service/feedback");
}

/**
 * Phản hồi về dịch vụ khách hàng
 * @constructor
 */
export function feedBackPost(data) {
  return request.post("user/service/feedback", data);
}

/**
 * Phát hiện đăng nhậpcode
 * @constructor
 */
export function codeStauts(data) {
  return request.get("user/code", data);
}
/**
 * Nhận cổng dịch vụ khách hàng
 * @constructor
 */
export function getWorkermanUrl(data) {
  return request.get(
    "get_workerman_url",
    {},
    {
      noAuth: true,
    }
  );
}

/**
 * Dịch vụ khách hàng quét mã QR để đăng nhậpcode
 * @constructor
 */
export function kefuScanLogin(data) {
  return request.post("user/code", data);
}
