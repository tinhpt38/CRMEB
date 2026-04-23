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
import wechat from "@/libs/wechat.js";

/**
 * Nhận cấu hình sdk WeChat
 * @returns {*}
 */
export function getWechatConfig() {
  return request.get(
    "wechat/config",
    {
      url: wechat.signLink(),
    },
    {
      noAuth: true,
    },
  );
}

/**
 * Nhận cấu hình sdk WeChat
 * @returns {*}
 */
export function wechatAuth(code, spread, login_type) {
  return request.get(
    "wechat/auth",
    {
      code,
      spread,
      login_type,
    },
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
 * Đăng nhập người dùng chương trình nhỏ
 * Đối tượng dữ liệu @param Thông tin đăng nhập của người dùng chương trình nhỏ
 */
export function login(data) {
  return request.post("wechat/mp_auth", data, {
    noAuth: true,
  });
}

/**
 * Ủy quyền im lặng
 * @param {Object} data
 */
export function silenceAuth(data) {
  //#ifdef MP
  return request.get("v2/wechat/silence_auth", data, {
    noAuth: true,
  });
  //#endif
  //#ifdef H5
  return request.get("v2/wechat/auth_type", data, {
    noAuth: true,
  });
  //#endif
}

/**
 * chia sẻ
 * @returns {*}
 */
export function getShare() {
  return request.get(
    "share",
    {},
    {
      noAuth: true,
    },
  );
}

/**
 * Đăng nhập tài khoản chính thức
 * @returns {*}
 */
export function wechatAuthLogin(data) {
  return request.get("v2/wechat/auth_login", data, {
    noAuth: true,
  });
}

/**
 * Áp phích thu hút sự chú ý
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
 * codeTạo người dùng
 * @returns {*}
 */
export function authType(data) {
  return request.get("v2/routine/auth_type", data, {
    noAuth: true,
  });
}

/**
 * Đăng nhập được ủy quyền
 * @returns {*}
 */
export function authLogin(data) {
  return request.get("v2/routine/auth_login", data, {
    noAuth: true,
  });
}

/**
 * Nhận hình ảnhbase64
 * @retins {*}
 * */
export function imageBase64(image, code) {
  return request.post(
    "image_base64",
    {
      image: image,
      code: code,
    },
    {
      noAuth: true,
    },
  );
}

/**
 * Tự động sao chép chức năng mật khẩu
 * @returns {*}
 */
export function copyWords() {
  return request.get(
    "copy_words",
    {},
    {
      noAuth: true,
    },
  );
}

/**
 * Tìm hiểu xem trung tâm mua sắm có bị buộc phải sử dụng số điện thoại di động hay không
 */
export function getShopConfig() {
  return request.get(
    "v2/bind_status",
    {},
    {
      noAuth: true,
    },
  );
}

/**
 * Chương trình mini liên kết số điện thoại di động
 * @param {Object} data
 */
export function routineBindingPhone(data) {
  return request.post("v2/routine/auth_binding_phone", data, {
    noAuth: true,
  });
}
/**
 * Chương trình mini liên kết số điện thoại di động
 * @param {Object} data
 */
export function wechatBindingPhone(data) {
  return request.post("v2/wechat/auth_binding_phone", data, {
    noAuth: true,
  });
}
/**
 * Chương trình nhỏ đăng nhập số điện thoại di động
 * @param {Object} data
 */
export function phoneLogin(data) {
  return request.post("v2/routine/phone_login", data, {
    noAuth: true,
  });
}

/**
 * Đăng nhập người dùng chương trình nhỏ
 * Đối tượng dữ liệu @param Thông tin đăng nhập của người dùng chương trình nhỏ
 */
export function routineLogin(data) {
  return request.get("v2/wechat/routine_auth", data, {
    noAuth: true,
  });
}

/**
 * Nhận cấu hình sdk WeChat
 * @returns {*}
 */
export function wechatAuthV2(code, spread) {
  return request.get(
    "v2/wechat/auth",
    {
      code,
      spread,
    },
    {
      noAuth: true,
    },
  );
}

/**
 * Nhận menu dưới cùng của thành phần
 * Đối tượng dữ liệu @param Nhận menu dưới cùng của thành phần
 */
export function getNavigation(data) {
  return request.get("theme/navigation", data, {
    noAuth: true,
  });
}
export function getSubscribe() {
  return request.get(
    "subscribe",
    {},
    {
      noAuth: true,
    },
  );
}

/**
 * Nhận thông tin phiên bản
 * @param loại hệ thống
 */
export function getUpdateInfo(type) {
  return request.get(
    "get_new_app/" + type,
    {},
    {
      noAuth: true,
    },
  );
}

/**
 * Lấy số phiên bản dữ liệu DIY trên trang chủ
 *
 */
export function getVersion(name) {
  return request.get(
    `v2/diy/get_version/${name}`,
    {},
    {
      noAuth: true,
    },
  );
}
/**
 * Nhận số phiên bản phân loại sản phẩm
 *
 */
export function getCategoryVersion(name) {
  return request.get(
    `category_version`,
    {},
    {
      noAuth: true,
    },
  );
}

/**
 * Thông tin cấu hình
 *
 */
export function basicConfig(name) {
  return request.get(
    `basic_config`,
    {},
    {
      noAuth: true,
    },
  );
}
/**
 * Thông tin phiên bản nền
 *
 */
export function getSystemVersion() {
  return request.get(
    `version`,
    {},
    {
      noAuth: true,
    },
  );
}

/**
 * iframeĐăng nhập
 *
 */
export function remoteRegister(data) {
  return request.get(`remote_register`, data, {
    noAuth: true,
  });
}
