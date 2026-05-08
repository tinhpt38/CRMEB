import { useAtom, useAtomValue, useSetAtom } from "jotai";
import { MutableRefObject, useLayoutEffect, useMemo, useState } from "react";
import toast from "react-hot-toast";
import { UIMatch, useMatches, useNavigate } from "react-router-dom";
import {
  cartState,
  cartTotalState,
  crmebAddressesState,
  deliveryModeState,
  ordersState,
  selectedCrmebAddressState,
  userInfoKeyState,
  userInfoState,
} from "@/state";
import { Product } from "@/types";
import { getConfig } from "@/utils/template";
import { authorize, openChat } from "zmp-sdk/apis";
import { useAtomCallback } from "jotai/utils";
import { CrmebApiClient } from "@/utils/crmeb/client";
import { getCrmebToken, setCrmebToken } from "@/utils/crmeb/token";

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
  const refreshPermissions = () => setInfoKey((key) => key + 1);

  return async () => {
    const userInfo = await getStoredUserInfo();
    if (!userInfo) {
      await authorize({
        // Theo khuyến nghị Zalo Mini App: chỉ xin quyền khi thực sự cần.
        // Luồng đăng nhập chỉ cần scope.userInfo; số điện thoại xử lý ở flow riêng.
        scopes: ["scope.userInfo"],
      }).then(refreshPermissions);
      return await getStoredUserInfo();
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
 * Gắn số điện thoại vào tài khoản Zalo đang đăng nhập (SMS OTP).
 *
 * Bước 1 – sendOtp(phone): gọi POST /register/verify → gửi OTP qua SMS.
 * Bước 2 – verifyAndBind(phone, otp): gọi POST /zalo/bind_phone → lưu phone vào CRMEB.
 *           Trả về true nếu thành công, ném lỗi nếu thất bại.
 */
export function useBindPhone() {
  const apiUrl = getConfig((config) => config.template.apiUrl);
  const setUserInfoKey = useSetAtom(userInfoKeyState);

  const sendOtp = async (phone: string) => {
    if (!apiUrl) throw new Error("Chưa cấu hình apiUrl");
    const token = getCrmebToken();
    if (!token) throw new Error("Chưa đăng nhập CRMEB");
    const client = new CrmebApiClient({
      apiBaseUrl: apiUrl,
      getToken: () => token,
    });
    await client.post("/zalo/send_bind_otp", { phone });
  };

  const verifyAndBind = async (phone: string, otp: string) => {
    if (!apiUrl) throw new Error("Chưa cấu hình apiUrl");
    const token = getCrmebToken();
    if (!token) throw new Error("Chưa đăng nhập CRMEB");
    const client = new CrmebApiClient({
      apiBaseUrl: apiUrl,
      getToken: () => token,
    });
    await client.post("/zalo/bind_phone", { phone, captcha: otp });

    // Xóa userInfo cache để state.userInfoState đọc lại phone mới từ server
    localStorage.removeItem("userInfo");
    setUserInfoKey((k) => k + 1);
  };

  return { sendOtp, verifyAndBind };
}

export function useToBeImplemented() {
  return () =>
    toast("Chức năng dành cho các bên tích hợp phát triển...", {
      icon: "🛠️",
    });
}

export function useCheckout() {
  const { totalAmount } = useAtomValue(cartTotalState);
  const [cart, setCart] = useAtom(cartState);
  const requestInfo = useRequestInformation();
  const navigate = useNavigate();

  const refreshPendingOrders = useSetAtom(ordersState("pending"));
  const refreshShippingOrders = useSetAtom(ordersState("shipping"));
  const refreshCompletedOrders = useSetAtom(ordersState("completed"));
  const refreshAddresses = useSetAtom(crmebAddressesState);

  const deliveryMode = useAtomValue(deliveryModeState);

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

  const handleCrmebPayment = async (args: {
    payInfo: any;
    client: CrmebApiClient;
    uni: string | number;
  }) => {
    const { payInfo, client, uni } = args;

    // 1) If CRMEB provides a direct payment URL, open it.
    const payUrl = payInfo?.pay_url;
    if (typeof payUrl === "string" && payUrl.length > 0) {
      window.location.href = payUrl;
      return;
    }

    // 2) If CRMEB returns `jsConfig` (WeChat/JS config), current MVP
    // does not bridge it into Zalo checkout. Fallback to `yue`.
    if (payInfo?.jsConfig) {
      toast("CRMEB trả jsConfig (WeChat). Fallback thanh toán số dư (yue)...", {
        icon: "ℹ",
      });
      try {
        await client.post<any>("/order/pay", {
          uni,
          paytype: "yue",
          quitUrl: "",
          type: 0,
        });
        return;
      } catch (e) {
        console.warn("Fallback yue payment failed:", e);
      }
    }

    // 3) Last resort: offline.
    toast("Đang fallback thanh toán ngoại tuyến (offline)...", { icon: "ℹ" });
    await client.post<any>("/order/pay", {
      uni,
      paytype: "offline",
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
      await client.post<any>(`/order/computed/${orderKey}`, {
        addressId,
        couponId: 0,
        payType: "yue",
        useIntegral: 0,
        mark: "",
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
          payType: "yue",
          useIntegral: 0,
          mark: "",
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

      // 5) pay bằng số dư (yue)
      const payInfo = await client.post<any>("/order/pay", {
        uni: orderId,
        paytype: "yue",
        quitUrl: "",
        type: 0,
      });

      await handleCrmebPayment({ payInfo, client, uni: orderId });
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
