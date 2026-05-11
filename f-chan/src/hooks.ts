import { useAtom, useAtomValue, useSetAtom } from "jotai";
import { MutableRefObject, useLayoutEffect, useMemo, useState } from "react";
import toast from "react-hot-toast";
import { UIMatch, useMatches, useNavigate } from "react-router-dom";
import {
  cartState,
  checkoutPaymentMethodState,
  crmebAddressesState,
  deliveryModeState,
  ordersState,
  selectedCrmebAddressState,
  userInfoKeyState,
  userInfoState,
} from "@/state";
import { Product } from "@/types";
import { getConfig } from "@/utils/template";
import {
  authorize,
  getAccessToken,
  getPhoneNumber,
  getSetting,
  openChat,
} from "zmp-sdk/apis";
import { useAtomCallback } from "jotai/utils";
import { CrmebApiClient } from "@/utils/crmeb/client";
import { clearCrmebToken, getCrmebToken, setCrmebToken } from "@/utils/crmeb/token";
import { setSessionLoggedOut } from "@/utils/session";
import CONFIG from "@/config";

/**
 * Xin quyền Zalo cho luồng đăng nhập: luôn xin `scope.userInfo` trước.
 * `scope.userPhonenumber` gọi tách — nếu Zalo chưa duyệt quyền ở cấp Mini App
 * thì gộp chung một lần authorize có thể fail toàn bộ và không lấy được user.
 */
async function authorizeZaloForLogin(): Promise<void> {
  try {
    await authorize({ scopes: ["scope.userInfo"] });
  } catch (e) {
    console.warn("Zalo authorize scope.userInfo:", e);
  }
  try {
    await authorize({ scopes: ["scope.userPhonenumber"] });
  } catch (e) {
    console.warn(
      "Zalo authorize scope.userPhonenumber (tuỳ chọn, có thể chưa được Zalo cấp quyền app):",
      e
    );
  }
}

export function useRealHeight(
  element: MutableRefObject<HTMLDivElement | null>,
  defaultValue?: number
) {
  const [height, setHeight] = useState(defaultValue ?? 0);
  useLayoutEffect(() => {
    if (element.current && typeof ResizeObserver !== "undefined") {
      const ro = new ResizeObserver((entries: ResizeObserverEntry[]) => {
        const [{ contentRect }] = entries;
        setHeight(contentRect.height);
      });
      ro.observe(element.current);
      return () => ro.disconnect();
    }
    return () => {};
  }, [element.current]);

  if (typeof ResizeObserver === "undefined") {
    return -1;
  }
  return height;
}

export function useRequestInformation() {
  const getStoredUserInfo = useAtomCallback(async (get) => {
    const userInfo = await get(userInfoState);
    return userInfo;
  });
  const setInfoKey = useSetAtom(userInfoKeyState);
  const refreshAddresses = useSetAtom(crmebAddressesState);
  const refreshPermissions = () => setInfoKey((key) => key + 1);

  return async () => {
    let userInfo = await getStoredUserInfo();
    const apiUrl = getConfig((c) => c.template.apiUrl);
    const isDev = !window.ZJSBridge;

    let triggeredLoginRefresh = false;
    if (!userInfo) {
      triggeredLoginRefresh = true;
      await authorizeZaloForLogin();
      setSessionLoggedOut(false);
      refreshPermissions();
      userInfo = await getStoredUserInfo();
    }

    if (triggeredLoginRefresh && userInfo && apiUrl && !isDev) {
      refreshAddresses();
    }

    // Khi bấm "Đăng ký hội viên", nếu đã login CRMEB nhưng chưa có phone thì
    // thử bind qua getPhoneNumber — chỉ gọi SDK khi setting cho thấy đã cấp quyền,
    // tránh popup/lỗi Zalo khi app chưa được duyệt scope.userPhonenumber.
    if (userInfo && apiUrl && !isDev && !userInfo.phone) {
      try {
        const crmebToken = getCrmebToken();
        if (crmebToken) {
          let phoneScopeOk = false;
          try {
            const { authSetting } = await getSetting({});
            phoneScopeOk =
              authSetting?.["scope.userPhonenumber"] === true;
          } catch {
            phoneScopeOk = false;
          }
          if (!phoneScopeOk) {
            return userInfo;
          }

          const [{ token: phoneToken }, accessToken] = await Promise.all([
            getPhoneNumber({}),
            getAccessToken(),
          ]);

          const client = new CrmebApiClient({
            apiBaseUrl: apiUrl,
            getToken: () => crmebToken,
          });
          const bindRes = await client.post<{ phone?: string }>(
            "/zalo/bind_phone_direct",
            { access_token: accessToken, phone_token: phoneToken }
          );

          if (bindRes?.phone) {
            const merged = { ...userInfo, phone: bindRes.phone };
            localStorage.setItem(
              CONFIG.STORAGE_KEYS.USER_INFO,
              JSON.stringify(merged)
            );
            userInfo = merged;
          }
          refreshPermissions();
        }
      } catch (error) {
        // Người dùng có thể từ chối cấp quyền phone; vẫn cho login bình thường.
        console.warn("Bind phone from register failed:", error);
      }
    }

    return userInfo;
  };
}

export function useAddToCart(product: Product) {
  const [cart, setCart] = useAtom(cartState);

  const currentCartItem = useMemo(
    () => cart.find((item) => item.product.id === product.id),
    [cart, product.id]
  );

  const addToCart = (
    quantity: number | ((oldQuantity: number) => number),
    options?: { toast: boolean }
  ) => {
    setCart((cart) => {
      const newQuantity =
        typeof quantity === "function"
          ? quantity(currentCartItem?.quantity ?? 0)
          : quantity;
      if (newQuantity <= 0) {
        cart.splice(cart.indexOf(currentCartItem!), 1);
      } else {
        if (currentCartItem) {
          currentCartItem.quantity = newQuantity;
        } else {
          cart.push({
            product,
            quantity: newQuantity,
          });
        }
      }
      return [...cart];
    });
    if (options?.toast) {
      toast.success("Đã thêm vào giỏ hàng");
    }
  };

  return { addToCart, cartQuantity: currentCartItem?.quantity ?? 0 };
}

export function useCustomerSupport() {
  return () =>
    openChat({
      type: "oa",
      id: getConfig((config) => config.template.oaIDtoOpenChat),
    });
}

/**
 * Gắn số điện thoại từ Zalo (không cần SMS OTP).
 *
 * Luồng:
 *  1. getAccessToken() + getPhoneNumber() từ Zalo SDK
 *  2. Gửi cả hai token lên POST /zalo/bind_phone_direct
 *  3. Backend decode phone_token → lấy số thực → lưu vào CRMEB
 */
export function useBindPhone() {
  const apiUrl = getConfig((config) => config.template.apiUrl);
  const setUserInfoKey = useSetAtom(userInfoKeyState);

  const bindFromZalo = async () => {
    if (!apiUrl) throw new Error("Chưa cấu hình apiUrl");
    const crmebToken = getCrmebToken();
    if (!crmebToken) throw new Error("Chưa đăng nhập CRMEB");

    const [{ token: phoneToken }, accessToken] = await Promise.all([
      getPhoneNumber({}),
      getAccessToken(),
    ]);

    const client = new CrmebApiClient({
      apiBaseUrl: apiUrl,
      getToken: () => crmebToken,
    });
    const res = await client.post<{ phone?: string }>("/zalo/bind_phone_direct", {
      access_token: accessToken,
      phone_token: phoneToken,
    });

    // Cập nhật userInfo cache với phone mới
    const saved = localStorage.getItem(CONFIG.STORAGE_KEYS.USER_INFO);
    if (saved) {
      try {
        const info = JSON.parse(saved);
        info.phone = res?.phone ?? info.phone;
        localStorage.setItem(CONFIG.STORAGE_KEYS.USER_INFO, JSON.stringify(info));
      } catch { /* ignore */ }
    }
    setUserInfoKey((k) => k + 1);
  };

  return { bindFromZalo };
}

export function useToBeImplemented() {
  return () =>
    toast("Chức năng dành cho các bên tích hợp phát triển...", {
      icon: "🛠️",
    });
}

export function useLogout() {
  const setUserInfoKey = useSetAtom(userInfoKeyState);
  const refreshAddresses = useSetAtom(crmebAddressesState);

  return () => {
    setSessionLoggedOut(true);
    clearCrmebToken();
    localStorage.removeItem(CONFIG.STORAGE_KEYS.USER_INFO);
    localStorage.removeItem(CONFIG.STORAGE_KEYS.CRMEB_ADDRESS_ID);
    setUserInfoKey((k) => k + 1);
    refreshAddresses();
    toast.success("Đã đăng xuất");
  };
}

export function useCheckout() {
  const [cart, setCart] = useAtom(cartState);
  const requestInfo = useRequestInformation();
  const navigate = useNavigate();

  const refreshPendingOrders = useSetAtom(ordersState("pending"));
  const refreshShippingOrders = useSetAtom(ordersState("shipping"));
  const refreshCompletedOrders = useSetAtom(ordersState("completed"));
  const refreshAddresses = useSetAtom(crmebAddressesState);

  const deliveryMode = useAtomValue(deliveryModeState);
  const checkoutPaymentMethod = useAtomValue(checkoutPaymentMethodState);

  const getSelectedAddress = useAtomCallback(async (get) =>
    get(selectedCrmebAddressState)
  );

  const extractCartId = (payload: any): string | null => {
    const raw =
      payload?.cartId ??
      payload?.id ??
      payload?.result?.cartId ??
      payload?.result?.id;
    if (raw === undefined || raw === null) return null;
    return String(raw);
  };

  const extractOrderKey = (payload: any): string => {
    return String(payload?.orderKey ?? payload?.result?.orderKey ?? "");
  };

  const extractOrderId = (payload: any): string => {
    return String(
      payload?.orderId ??
        payload?.order_id ??
        payload?.result?.orderId ??
        payload?.result?.order_id ??
        payload?.result?.id ??
        ""
    );
  };

  const resolveCrmebPayType = (): string => {
    if (checkoutPaymentMethod === "bank_transfer") return "vn_bank";
    if (checkoutPaymentMethod === "other") return "offline";
    return "vn_cod";
  };

  const resolveCheckoutMark = (): string => {
    if (checkoutPaymentMethod === "bank_transfer") return "PAY_METHOD:BANK_TRANSFER";
    if (checkoutPaymentMethod === "other") return "PAY_METHOD:OTHER";
    return "PAY_METHOD:COD";
  };

  const handleCrmebPayment = async (args: {
    payInfo: any;
    client: CrmebApiClient;
    uni: string | number;
    payType: string;
  }) => {
    const { payInfo, client, uni, payType } = args;

    // 1) If CRMEB provides a direct payment URL, open it.
    const payUrl = payInfo?.pay_url;
    if (typeof payUrl === "string" && payUrl.length > 0) {
      window.location.href = payUrl;
      return;
    }

    // 2) If CRMEB returns `jsConfig` (WeChat/JS config), current MVP
    // does not bridge it into Zalo checkout. Fallback to selected paytype.
    if (payInfo?.jsConfig) {
      toast("CRMEB trả jsConfig (WeChat). Fallback sang phương thức đã chọn...", {
        icon: "ℹ",
      });
      try {
        await client.post<any>("/order/pay", {
          uni,
          paytype: payType,
          quitUrl: "",
          type: 0,
        });
        return;
      } catch (e) {
        console.warn("Fallback selected payment failed:", e);
      }
    }

    // 3) Last resort: offline.
    toast("Đang thử lại xác nhận thanh toán đơn hàng...", { icon: "ℹ" });
    await client.post<any>("/order/pay", {
      uni,
      paytype: payType,
      quitUrl: "",
      type: 0,
    });
  };

  return async () => {
    try {
      if (deliveryMode !== "shipping") {
        toast.error("Tạm thời chưa hỗ trợ nhận tại cửa hàng (pickup).");
        return;
      }

      const userInfo = await requestInfo();
      if (!userInfo) throw new Error("Missing user info");

      const apiUrl = getConfig((config) => config.template.apiUrl);
      if (!apiUrl) {
        toast.error("Chưa cấu hình apiUrl. Vui lòng kiểm tra app-config.json.");
        return;
      }

      const client = new CrmebApiClient({
        apiBaseUrl: apiUrl,
        getToken: () => getCrmebToken(),
      });

      // Lấy địa chỉ đang được chọn từ CRMEB
      const selectedAddress = await getSelectedAddress();
      const addressId = selectedAddress?.id ?? 0;

      if (!addressId) {
        toast.error("Vui lòng thêm địa chỉ nhận hàng trước khi đặt hàng.");
        navigate("/shipping-address", { viewTransition: true });
        return;
      }

      // 1) local cart -> server cart
      const cartIdList: string[] = [];
      for (const item of cart) {
        const res = await client.post<any>("/cart/add", {
          productId: item.product.id,
          cartNum: item.quantity,
          uniqueId: "",
          new: 1,
          is_new: 0,
          combinationId: 0,
          secKillId: 0,
          bargainId: 0,
          advanceId: 0,
          pinkId: 0,
        });

        const cartId = extractCartId(res);
        if (cartId) cartIdList.push(cartId);
      }

      if (!cartIdList.length) throw new Error("Cart sync failed");
      const cartId = cartIdList.join(",");

      // 2) confirm -> orderKey
      const confirmData = await client.post<any>("/order/confirm", {
        cartId,
        new: 1,
        addressId,
        shipping_type: 1,
        is_gift: 0,
      });
      const orderKey = extractOrderKey(confirmData);
      if (!orderKey) throw new Error("Missing orderKey from /order/confirm");

      // 3) computed
      const payType = resolveCrmebPayType();
      const mark = resolveCheckoutMark();
      await client.post<any>(`/order/computed/${orderKey}`, {
        addressId,
        couponId: 0,
        payType,
        useIntegral: 0,
        mark,
        combinationId: 0,
        pinkId: 0,
        seckill_id: 0,
        bargainId: 0,
        shipping_type: 1,
        is_gift: 0,
      });

      // 4) create order
      const createData = await client.post<any>(
        `/order/create/${orderKey}`,
        {
          addressId,
          couponId: 0,
          payType,
          useIntegral: 0,
          mark,
          combinationId: 0,
          pinkId: 0,
          seckill_id: 0,
          bargainId: 0,
          shipping_type: 1,
          real_name: selectedAddress?.real_name || userInfo.name,
          phone: selectedAddress?.phone || userInfo.phone,
          store_id: 0,
          news: 0,
          new: 1,
          invoice_id: 0,
          advanceId: 0,
          custom_form: [],
          is_gift: 0,
          gift_mark: "",
        }
      );

      const orderId = extractOrderId(createData);
      if (!orderId) throw new Error("Missing orderId from /order/create");

      // 5) pay theo phương thức đã chọn (CRMEB side: offline flow)
      const payInfo = await client.post<any>("/order/pay", {
        uni: orderId,
        paytype: payType,
        quitUrl: "",
        type: 0,
      });

      await handleCrmebPayment({ payInfo, client, uni: orderId, payType });

      if (payType === "vn_bank") {
        toast.success(
          "Đã tạo đơn. Vui lòng chuyển khoản theo hướng dẫn và chờ shop xác nhận."
        );
      } else if (payType === "vn_cod") {
        toast.success("Đặt hàng thành công. Bạn thanh toán khi nhận hàng.");
      } else if (payType === "offline") {
        toast.success("Đã tạo đơn. Đang chờ cửa hàng xác nhận thanh toán.");
      } else {
        toast.success("Đặt hàng thành công.");
      }
    } catch (error) {
      console.warn(error);
      toast.error(
        "Thanh toán thất bại. Vui lòng kiểm tra nội dung lỗi bên trong Console."
      );
      return;
    }

    // Refresh orders + addresses after the payment step.
    setCart([]);
    refreshPendingOrders();
    refreshShippingOrders();
    refreshCompletedOrders();
    refreshAddresses();
    navigate("/orders", { viewTransition: true });
  };
}

export function useRouteHandle() {
  const matches = useMatches() as UIMatch<
    undefined,
    | {
        title?: string | Function;
        logo?: boolean;
        search?: boolean;
        noFooter?: boolean;
        noBack?: boolean;
        noFloatingCart?: boolean;
        scrollRestoration?: number;
      }
    | undefined
  >[];
  const lastMatch = matches[matches.length - 1];

  return [lastMatch.handle, lastMatch, matches] as const;
}
