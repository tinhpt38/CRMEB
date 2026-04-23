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
 * Thống kê
 */
export function getStatisticsInfo() {
  return request.get(
    "admin/order/statistics",
    {},
    {
      login: true,
    },
  );
}
/**
 * Thống kê đặt hàng hàng tháng
 */
export function getStatisticsMonth(where) {
  return request.get("admin/order/data", where, {
    login: true,
  });
}
/**
 * Thống kê đặt hàng hàng tháng
 */
export function getAdminOrderList(where) {
  return request.get("admin/order/list", where, {
    login: true,
  });
}
/**
 * Thay đổi giá đặt hàng
 */
export function setAdminOrderPrice(data) {
  return request.post("admin/order/price", data, {
    login: true,
  });
}
/**
 * Ghi chú đặt hàng
 */
export function setAdminOrderRemark(data) {
  return request.post("admin/order/remark", data, {
    login: true,
  });
}
/**
 * Chi tiết đặt hàng
 */
export function getAdminOrderDetail(orderId) {
  return request.get(
    "admin/order/detail/" + orderId,
    {},
    {
      login: true,
    },
  );
}

/**
 * Chi tiết đơn hàng hoàn tiền
 */
export function getAdminRefundOrderDetail(orderId) {
  return request.get(
    "admin/refund_order/detail/" + orderId,
    {},
    {
      login: true,
    },
  );
}

/**
 * Lấy thông tin vận chuyển đơn hàng
 */
export function getAdminOrderDelivery(orderId) {
  return request.get(
    "admin/order/delivery/gain/" + orderId,
    {},
    {
      login: true,
    },
  );
}

/**
 * Lưu đơn hàng giao hàng
 */
export function setAdminOrderDelivery(id, data) {
  return request.post("admin/order/delivery/keep/" + id, data, {
    login: true,
  });
}
/**
 * Biểu đồ thống kê đơn hàng
 */
export function getStatisticsTime(data) {
  return request.get("admin/order/time", data, {
    login: true,
  });
}
/**
 * Thanh toán xác nhận đơn hàng thanh toán ngoại tuyến
 */
export function setOfflinePay(data) {
  return request.post("admin/order/offline", data, {
    login: true,
  });
}
/**
 * Hoàn tiền xác nhận đơn hàng
 */
export function setOrderRefund(data) {
  return request.post("admin/order/refund", data, {
    login: true,
  });
}

/**
 * Nhận công ty chuyển phát nhanh
 * @returns {*}
 */
export function getLogistics(data) {
  return request.get("logistics", data, {
    login: false,
  });
}

/**
 * Xóa đơn hàng
 * @returns {*}
 */
export function orderVerific(verify_code, is_confirm, auth = 0) {
  return request.post("order/order_verific", {
    verify_code,
    is_confirm,
    auth,
  });
}

/**
 * Nhận mẫu công ty hậu cần
 * @returns {*}
 */
export function orderExportTemp(data) {
  return request.get("admin/order/export_temp", data);
}

/**
 * Nhận cấu hình mặc định in lệnh
 * @returns {*}
 */
export function orderDeliveryInfo() {
  return request.get("admin/order/delivery_info");
}

/**
 * Danh sách người giao hàng
 * @returns {*}
 */
export function orderOrderDelivery() {
  return request.get("admin/order/delivery");
}

/**
 * Danh sách hoàn tiền
 * @returns {*}
 */
export function orderRefund_order(where) {
  return request.get("admin/refund_order/list", where, {
    login: true,
  });
}

/**
 * Ghi chú đặt hàng (Hoàn tiền）
 */
export function setAdminRefundRemark(data) {
  return request.post("admin/refund_order/remark", data, {
    login: true,
  });
}

/**
 * Đơn hàng đồng ý trả lại
 */
export function agreeExpress(data) {
  return request.post("admin/order/agreeExpress", data, {
    login: true,
  });
}

/**
 * Thống kê quản lý thương mại
 */
export function getManageStatistics() {
  return request.get(
    "admin/manage/statistics",
    {},
    {
      login: true,
    },
  );
}

/**
 * Danh sách sản phẩm nền tảng
 */
export function adminProductList(data) {
  return request.get("admin/manage/product", data);
}

/**
 * Tải và dỡ sản phẩm
 */
export function productSetShow(data) {
  return request.post("admin/manage/product/set_show", data, {
    login: true,
  });
}

/**
 * Lấy dữ liệu nhãn
 */
export function getProductLabel() {
  return request.get(
    "admin/manage/product/label",
    {},
    {
      login: true,
    },
  );
}

/**
 * Nhận dữ liệu phân loại
 */
export function getProductCate() {
  return request.get(
    "admin/manage/product/cate",
    {},
    {
      login: true,
    },
  );
}

/**
 * Sửa đổi nhãn sản phẩm
 */
export function postBatchProcess(data) {
  return request.post("admin/manage/product/save_label", data, {
    login: true,
  });
}

/**
 * Sửa đổi phân loại sản phẩm
 */
export function postManageSaveCate(data) {
  return request.post("admin/manage/product/save_cate", data, {
    login: true,
  });
}
/**
 * Thông số sản phẩm
 */
export function getManageProductAttr(id) {
  return request.get(
    `admin/manage/product/attr/${id}`,
    {},
    {
      login: true,
    },
  );
}

export function postUpdateAttrs(id, data) {
  return request.post(`admin/manage/product/save_attr/${id}`, data, {
    login: true,
  });
}

/**
 * Quản lý thống kê - lấy danh sách các mặt hàng có thể chia thành đơn hàng
 */
export function orderSplitInfo(id) {
  return request.get("admin/order/split_cart_info/" + id);
}

/**
 * Quản lý thống kê-Gửi
 */
export function orderSplitDelivery(id, data) {
  return request.put("admin/order/split_delivery/" + id, data);
}

/**
 * Danh sách người dùng nền tảng
 */
export function getUserList(data) {
  return request.get(`admin/manage/user`, data);
}

/**
 * Nền tảng-Sửa đổi số dư và điểm
 */
export function postUserUpdateOther(uid, data) {
  return request.post(`admin/manage/user/update/${uid}`, data);
}

/**
 * Danh sách nhóm nền tảng
 */
export function getGroupList() {
  return request.get(`admin/manage/user/group`);
}

/**
 * Nền tảng-Sửa đổi thông tin người dùng
 */
export function postUserUpdate(data) {
  return request.post(`admin/user/update`, data);
}

/**
 * Nền tảng-Phiếu giảm giá
 */
export function getUserCoupon(data) {
  return request.get(`admin/manage/user/coupon`, data);
}

/**
 * Thẻ người dùng nền tảng
 */
export function getUserLabel(uid) {
  return request.get(`admin/manage/user/label/${uid}`);
}

/**
 * Danh sách cấp nền tảng
 */
export function getLevelList() {
  return request.get(`admin/manage/user/level`);
}

/**
 * Chi tiết người dùng nền tảng
 */
export function getUserInfo(uid) {
  return request.get(`admin/manage/user/info/${uid}`);
}

/**
 * Danh sách hoàn tiền trên nền tảng
 */
export function adminRefundList(data) {
  return request.get("admin/refund_order/list", data);
}

/**
 * Chi tiết đặt hàng(Đền bù)
 */
export function getAdminRefundDetail(orderId) {
  return request.get(
    "admin/refund_order/detail/" + orderId,
    {},
    {
      login: true,
    },
  );
}

export function getTemplateOption() {
  return request.get(`admin/manage/product/shipping_temp`);
}

export function productCreate(data) {
  return request.post(`admin/manage/product/create`, data);
}

/**
 * Người giao hàng - ghi đơn hàng và lấy thông tin sản phẩm
 */
export function orderCartInfo(data) {
  return request.post("store/order/cart_info", data);
}

/**
 * Người giao hàng-Xác minh đơn hàng
 */
export function orderWriteoff(data) {
  return request.post("store/order/writeoff", data);
}
