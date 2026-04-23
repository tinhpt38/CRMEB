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
 * Giao diện công cộng, giao diện phiếu giảm giá , Tin tức ngành , Đăng ký số điện thoại di động
 *
 */
export function getAjcaptcha(data) {
  return request.get("ajcaptcha", data, {
    noAuth: true,
  });
}

export function ajcaptchaCheck(data) {
  return request.post("ajcheck", data, {
    noAuth: true,
  });
}

/**
 * Nhận dữ liệu trang chủ mà không được phép
 *
 */
export function getIndexData() {
  return request.get(
    "v2/index",
    {},
    {
      noAuth: true,
    },
  );
}
/**
 * Nhận loại máy chủ
 *
 */
export function getServerType() {
  return request.get(
    "v2/site_serve",
    {},
    {
      noAuth: true,
    },
  );
}

/**
 * Nhận ủy quyền đăng nhậplogin
 *
 */
export function getLogo() {
  return request.get(
    "wechat/get_logo",
    {},
    {
      noAuth: true,
    },
  );
}

/**
 * cứuform_id
 * @param string formId
 */
export function setFormId(formId) {
  return request.post("wechat/set_form_id", {
    formId: formId,
  });
}

/**
 * Nhận phiếu giảm giá
 * @param int couponId
 *
 */
export function setCouponReceive(couponId) {
  return request.post("coupon/receive", {
    couponId: couponId,
  });
}
/**
 * Danh sách phiếu giảm giá
 * @param object data
 */
export function getCoupons(data) {
  return request.get("v2/coupons", data, {
    noAuth: true,
  });
}
/**
 * Dữ liệu thành phần danh sách phiếu giảm giá trang chủ
 * @param object data
 */
export function getCouponsIndex(data) {
  return request.get("coupons", data, {
    noAuth: true,
  });
}

/**
 * phiếu giảm giá của tôi
 * @param int loại 0 tất cả 1 chưa sử dụng 2 đã sử dụng
 */
export function getUserCoupons(types, data) {
  return request.get("coupons/user/" + types, data);
}

/**
 * Trang chủ Phiếu giảm giá cho người mới
 *
 */
export function getNewCoupon() {
  return request.get("v2/new_coupon");
}

/**
 * Danh sách danh mục bài viết
 *
 */
export function getArticleCategoryList() {
  return request.get(
    "article/category/list",
    {},
    {
      noAuth: true,
    },
  );
}

/**
 * Danh sách bài viết
 * @param int cid
 *
 */
export function getArticleList(cid, data) {
  return request.get("article/list/" + cid, data, {
    noAuth: true,
  });
}

/**
 * Danh sách bài viết Hot
 *
 */
export function getArticleHotList() {
  return request.get(
    "article/hot/list",
    {},
    {
      noAuth: true,
    },
  );
}

/**
 * Danh sách băng chuyền bài viết
 *
 */
export function getArticleBannerList() {
  return request.get(
    "article/banner/list",
    {},
    {
      noAuth: true,
    },
  );
}

/**
 * Chi tiết bài viết
 * @param int id
 *
 */
export function getArticleDetails(id) {
  return request.get(
    "article/details/" + id,
    {},
    {
      noAuth: true,
    },
  );
}

/**
 * Số điện thoại di động + giao diện đăng nhập mã xác minh
 * @param object data
 */
export function loginMobile(data) {
  return request.post("login/mobile", data, {
    noAuth: true,
  });
}

/**
 * Nhận tin nhắn SMSKEY
 * @param object phone
 */
export function verifyCode() {
  return request.get(
    "verify_code",
    {},
    {
      noAuth: true,
    },
  );
}

/**
 * Mã xác minh đã được gửi
 * @param object phone
 */
export function registerVerify(
  phone,
  reset,
  key,
  captchaType,
  captchaVerification,
) {
  return request.post(
    "register/verify",
    {
      phone: phone,
      type: reset === undefined ? "reset" : reset,
      key: key,
      captchaType: captchaType,
      captchaVerification: captchaVerification,
    },
    {
      noAuth: true,
    },
  );
}

/**
 * Đăng ký số điện thoại di động
 * @param object data
 *
 */
export function phoneRegister(data) {
  return request.post("register", data, {
    noAuth: true,
  });
}

/**
 * Đổi mật khẩu số điện thoại di động
 * @param object data
 *
 */
export function phoneRegisterReset(data) {
  return request.post("register/reset", data, {
    noAuth: true,
  });
}

/**
 * Đăng nhập bằng số điện thoại + mật khẩu
 * @param object data
 *
 */
export function phoneLogin(data) {
  return request.post("login", data, {
    noAuth: true,
  });
}

/**
 * Chuyển đăng nhập H5
 * @param object data
 */
// #ifdef MP
export function switchH5Login() {
  return request.post("switch_h5", {
    from: "routine",
  });
}
// #endif

/*
 * h5Chuyển đổi đăng nhập tài khoản chính thức
 * */
// #ifdef H5
export function switchH5Login() {
  return request.post("switch_h5", {
    from: "wechat",
  });
}
// #endif

/**
 * Ràng buộc số điện thoại di động
 *
 */
export function bindingPhone(data) {
  return request.post("binding", data, {
    noAuth: true,
  });
}

/**
 * Ràng buộc số điện thoại di động
 *
 */
export function bindingUserPhone(data) {
  return request.post("user/binding", data);
}

/**
 * Đăng xuất
 *
 */
export function logout() {
  return request.get("logout");
}

/**
 * Nhận tin nhắn đăng kýid
 */
export function getTempIds() {
  return request.get(
    "wechat/temp_ids",
    {},
    {
      noAuth: true,
    },
  );
}

/**
 * Dữ liệu nhóm nhà
 */
export function pink() {
  return request.get(
    "pink",
    {},
    {
      noAuth: true,
    },
  );
}

/**
 * Nhận thông tin thành phố
 */
export function getCity() {
  return request.get(
    "city_list",
    {},
    {
      noAuth: true,
    },
  );
}

/**
 * Nhận danh sách
 */
export function getLiveList(page, limit) {
  return request.get(
    "wechat/live",
    {
      page,
      limit,
    },
    {
      noAuth: true,
    },
  );
}

/**
 * Nhận trang chủDIY；
 */
export function getDiy(id) {
  return request.get(
    `v2/diy/get_diy/default${id ? "?id=" + id : ""}`,
    {},
    {
      noAuth: true,
    },
  );
}

/**
 * Thay đổi màu bằng một cú nhấp chuột；
 */
export function colorChange(name) {
  return request.get(
    "v2/diy/color_change/" + name,
    {},
    {
      noAuth: true,
    },
  );
}

/**
 * Thu hút sự chú ý của tài khoản công cộng
 * @returns {*}
 */
export function follow() {
  return request.get(
    "wechat/follow",
    {},
    {
      noAuth: true,
    },
  );
}

/**
 * Thay đổi số điện thoại di động
 * @returns {*}
 */
export function updatePhone(data) {
  return request.post("user/updatePhone", data, {
    noAuth: true,
  });
}

/**
 * Cửa sổ bật lên phiếu giảm giá trang chủ
 * @returns {*}
 */
export function getCouponV2() {
  return request.get(
    "v2/get_today_coupon",
    {},
    {
      noAuth: true,
    },
  );
}

/**
 * Cửa sổ bật lên phiếu giảm giá người dùng mới
 * @returns {*}
 */
export function getCouponNewUser() {
  return request.get(
    "v2/new_coupon",
    {},
    {
      noAuth: true,
    },
  );
}

/**
 * Chọn nhanh dữ liệu trên trang chủ
 * @param {Object} data
 */
export function category(data) {
  return request.get("category", data, {
    noAuth: true,
  });
}

/**
 * Lịch sử tìm kiếm cá nhân
 * @param {Object} data
 */
export function searchList(data) {
  return request.get("v2/user/search_list", data, {
    noAuth: true,
  });
}

/**
 * Xóa lịch sử tìm kiếm
 */
export function clearSearch() {
  return request.get("v2/user/clean_search");
}
/**
 * Lấy cấu hình cơ bản của website
 */
export function siteConfig(data) {
  return request.get("site_config", data, {
    noAuth: true,
  });
}

/**
 * Appđăng nhập WeChat
 * @returns {*}
 */
export function wechatAppAuth(data) {
  return request.post("wechat/app_auth", data, {
    noAuth: true,
  });
}
/**
 * Nhận loại dịch vụ khách hàng
 * @returns {*}
 */
export function getCustomerType(data) {
  return request.get(
    "get_customer_type",
    {},
    {
      noAuth: true,
    },
  );
}

/**
 * Nhận quảng cáo màn hình mở
 * @returns {*}
 */
export function getOpenAdv(data) {
  return request.get(
    "get_open_adv",
    {},
    {
      noAuth: true,
    },
  );
}

/**
 * Nhận thông tin bản quyền
 */
export function getCrmebCopyRight() {
  return request.get(
    "copyright",
    {},
    {
      noAuth: true,
    },
  );
}
/**
 * Nhận giao diện phiên bản DIY
 * @param {Object} id
 */
export function getDiyVersion(name) {
  return request.get(
    `v2/diy/get_version/${name}`,
    {},
    {
      noAuth: true,
    },
  );
}
/**
 * Giao diện lấy thông tin chủ đề
 * @param {Object} id
 */
export function getThemeInfo(type, data) {
  return request.get(`theme_info/${type}`, data || {}, {
    noAuth: true,
  });
}

/**
 * Nhận thông tin đăng ký DIY
 * @param {Object} id
 */
export function getSign() {
  return request.get(
    "v2/diy/sign",
    {},
    {
      noAuth: true,
    },
  );
}
/**
 * @description Nhận danh sách các sản phẩm theo chủ đề
 */
export function getThemeProduct(data) {
  return request.get("theme/product", data, {
    noAuth: true,
  });
}
/**
 * @description Nhận danh sách bài viết
 */
export function getThemeArticle(data) {
  return request.get("theme/article", data, {
    noAuth: true,
  });
}
/**
 * @description Nhận danh sách phiếu giảm giá
 */
export function getThemeCoupon(data) {
  return request.get("theme/coupon", data, {
    noAuth: true,
  });
}

/**
 * Lấy thông tin người dùng(DIY)
 *
 */
export function getThemeUser() {
  return request.get(
    "theme/user",
    {},
    {
      noAuth: true,
    },
  );
}
