import { useAtom, useAtomValue, useSetAtom } from "jotai";
import { MutableRefObject, useLayoutEffect, useMemo, useState } from "react";
import toast from "react-hot-toast";
import { UIMatch, useMatches, useNavigate } from "react-router-dom";
import {
  cartState,
  cartTotalState,
  deliveryModeState,
  ordersState,
  userInfoKeyState,
  userInfoState,
} from "@/state";
import { Product } from "@/types";
import { getConfig } from "@/utils/template";
import { authorize, createOrder, openChat } from "zmp-sdk/apis";
import { useAtomCallback } from "jotai/utils";
import { CrmebApiClient } from "@/utils/crmeb/client";
import { getCrmebToken } from "@/utils/crmeb/token";
import { isCrmebFeatureEnabled } from "@/utils/featureFlags";

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
        scopes: ["scope.userInfo", "scope.userPhonenumber"],
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

  const deliveryMode = useAtomValue(deliveryModeState);

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

      const checkoutEnabled = isCrmebFeatureEnabled("checkout");
      const apiUrl = getConfig((config) => config.template.apiUrl);
      // Nếu chưa bật CRMEB checkout (hoặc chưa cấu hình apiUrl), fallback về demo Zalo template.
      if (!checkoutEnabled || !apiUrl) {
        await createOrder({
          amount: totalAmount,
          desc: "Thanh toán đơn hàng",
          item: cart.map((item) => ({
            id: item.product.id,
            name: item.product.name,
            price: item.product.price,
            quantity: item.quantity,
          })),
        });

        setCart([]);
        refreshPendingOrders();
        refreshShippingOrders();
        refreshCompletedOrders();
        navigate("/orders", { viewTransition: true });
        toast.success("Thanh toán thành công. Cảm ơn bạn đã mua hàng!", {
          icon: "🎉",
          duration: 5000,
        });
        return;
      }

      const client = new CrmebApiClient({
        apiBaseUrl: apiUrl,
        getToken: () => getCrmebToken(),
      });

      // 1) local cart -> server cart
      const cartIdList: string[] = [];
      for (const item of cart) {
        const res = await client.post<{ cartId: any }>("/cart/add", {
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

        if (res?.cartId !== undefined && res?.cartId !== null) {
          cartIdList.push(String(res.cartId));
        }
      }

      if (!cartIdList.length) throw new Error("Cart sync failed");
      const cartId = cartIdList.join(",");

      // 2) confirm -> orderKey
      const confirmData = await client.post<any>("/order/confirm", {
        cartId,
        new: 1,
        addressId: 0,
        shipping_type: 1,
        is_gift: 0,
      });
      const orderKey = confirmData?.orderKey;
      if (!orderKey) throw new Error("Missing orderKey from /order/confirm");

      // 3) computed
      await client.post<any>(`/order/computed/${orderKey}`, {
        addressId: 0,
        couponId: 0,
        payType: "",
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
          addressId: 0,
          couponId: 0,
          payType: "",
          useIntegral: 0,
          mark: "",
          combinationId: 0,
          pinkId: 0,
          seckill_id: 0,
          bargainId: 0,
          shipping_type: 1,
          real_name: userInfo.name,
          phone: userInfo.phone,
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

      const orderId = createData?.orderId ?? createData?.order_id;
      if (!orderId) throw new Error("Missing orderId from /order/create");

      // 5) pay
      const payInfo = await client.post<any>("/order/pay", {
        uni: orderId,
        paytype: "weixin",
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

    // Refresh orders after the payment step.
    setCart([]);
    refreshPendingOrders();
    refreshShippingOrders();
    refreshCompletedOrders();
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
