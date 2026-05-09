const CONFIG = {
  STORAGE_KEYS: {
    USER_INFO: "userInfo",
    /** Đặt khi user đăng xuất — chặn auto login CRMEB qua Zalo access_token. */
    SESSION_LOGGED_OUT: "fchanSessionLoggedOut",
    // CRMEB JWT returned by `POST /api/zalo/auth`
    CRMEB_TOKEN: "crmebToken",
    DELIVERY: "delivery",
    SHIPPING_ADDRESS: "shippingAddress",
    // ID địa chỉ đang được chọn (từ CRMEB /address/list)
    CRMEB_ADDRESS_ID: "crmebAddressId",
  },
};

export default CONFIG;
