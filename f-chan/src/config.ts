const CONFIG = {
  STORAGE_KEYS: {
    USER_INFO: "userInfo",
    // CRMEB JWT returned by `POST /api/zalo/auth`
    CRMEB_TOKEN: "crmebToken",
    DELIVERY: "delivery",
    SHIPPING_ADDRESS: "shippingAddress",
    // ID địa chỉ đang được chọn (từ CRMEB /address/list)
    CRMEB_ADDRESS_ID: "crmebAddressId",
  },
};

export default CONFIG;
