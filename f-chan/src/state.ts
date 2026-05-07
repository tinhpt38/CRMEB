import { atom } from "jotai";
import {
  atomFamily,
  atomWithRefresh,
  atomWithStorage,
  loadable,
  unwrap,
} from "jotai/utils";
import {
  Cart,
  Category,
  Delivery,
  Location,
  Order,
  OrderStatus,
  Product,
  ShippingAddress,
  Station,
  UserInfo,
} from "@/types";
import { requestWithFallback } from "@/utils/request";
import {
  getAccessToken,
  getLocation,
  getPhoneNumber,
  getSetting,
  getUserInfo,
} from "zmp-sdk/apis";
import toast from "react-hot-toast";
import { calculateDistance } from "./utils/location";
import { formatDistant } from "./utils/format";
import CONFIG from "./config";
import { getConfig } from "./utils/template";
import { CrmebApiClient } from "./utils/crmeb/client";
import { getCrmebToken, setCrmebToken } from "./utils/crmeb/token";
import { isCrmebFeatureEnabled } from "./utils/featureFlags";

export const userInfoKeyState = atom(0);

export const userInfoState = atom<Promise<UserInfo | undefined>>(
  async (get) => {
  get(userInfoKeyState);

  // Nếu người dùng đã chỉnh sửa thông tin tài khoản trước đó, sử dụng thông tin đã lưu trữ
  const savedUserInfo = localStorage.getItem(CONFIG.STORAGE_KEYS.USER_INFO);
  // Phía tích hợp có thể thay đổi logic này thành fetch từ server
  // const savedUserInfo = await fetchUserInfo({ token: await getAccessToken() });
  if (savedUserInfo) {
    return JSON.parse(savedUserInfo);
  }

  const {
    authSetting: {
      "scope.userInfo": grantedUserInfo,
      "scope.userPhonenumber": grantedPhoneNumber,
    },
  } = await getSetting({});
  const isDev = !window.ZJSBridge;
  const apiUrl = getConfig((config) => config.template.apiUrl);

  // Dev (or missing config) keeps existing demo behavior to avoid hard-blocking UI.
  if (!apiUrl || isDev) {
    if (grantedUserInfo || isDev) {
      const { userInfo } = await getUserInfo({});
      const phone =
        grantedPhoneNumber || isDev
          ? await get(phoneState)
          : "";
      return {
        id: userInfo.id,
        name: userInfo.name,
        avatar: userInfo.avatar,
        phone,
        email: "",
        address: "",
      };
    }
    return undefined;
  }

  // Integration path: login against CRMEB via Zalo access_token -> store CRMEB JWT.
  if (!grantedUserInfo && !isDev) {
    return undefined;
  }

  try {
    const accessToken = await getAccessToken();
    const client = new CrmebApiClient({
      apiBaseUrl: apiUrl,
      getToken: () => getCrmebToken(),
    });

    const result = await client.post<{
      token: string;
      expires_time: number;
      userInfo: any;
    }>("/zalo/auth", { access_token: accessToken });

    if (result?.token) setCrmebToken(result.token);

    const crmUser = result?.userInfo ?? {};
    const mapped: UserInfo = {
      id: String(crmUser.uid ?? crmUser.id ?? ""),
      name: String(crmUser.nickname ?? crmUser.name ?? ""),
      avatar: String(crmUser.avatar ?? ""),
      phone: String(crmUser.phone ?? ""),
      email: "",
      address: "",
    };

    localStorage.setItem(
      CONFIG.STORAGE_KEYS.USER_INFO,
      JSON.stringify(mapped)
    );

    return mapped;
  } catch (error) {
    console.warn("CRMEB Zalo login failed:", error);
    return undefined;
  }
});

export const loadableUserInfoState = loadable(userInfoState);

export const phoneState = atom(async () => {
  let phone = "";
  try {
    const { token } = await getPhoneNumber({});
    // Phía tích hợp làm theo hướng dẫn tại https://mini.zalo.me/documents/api/getPhoneNumber/ để chuyển đổi token thành số điện thoại người dùng ở server.
    // phone = await decodeToken(token);

    // Các bước bên dưới để demo chức năng, phía tích hợp có thể bỏ đi sau.
    toast(
      "Đã lấy được token chứa số điện thoại người dùng. Phía tích hợp cần decode token này ở server. Giả lập số điện thoại 0912345678...",
      {
        icon: "ℹ",
        duration: 10000,
      }
    );
    await new Promise((resolve) => setTimeout(resolve, 1000));
    phone = "0912345678";
    // End demo
  } catch (error) {
    console.warn(error);
  }
  return phone;
});

export const bannersState = atom(() =>
  (async () => {
    const catalogEnabled = isCrmebFeatureEnabled("catalog");
    const apiUrl = getConfig((config) => config.template.apiUrl);
    if (!catalogEnabled)
      return await requestWithFallback<string[]>("/banners", []);

    try {
      const client = new CrmebApiClient({
        apiBaseUrl: apiUrl,
        getToken: () => getCrmebToken(),
      });

      // Public endpoint: carousel/home content
      const res = await client.get<{ list?: Array<any> }>("/home/products");
      const list = res?.list ?? [];
      return list
        .map((item) => String(item?.image ?? item?.recommend_image ?? ""))
        .filter(Boolean);
    } catch (error) {
      console.warn("Failed to load banners from CRMEB:", error);
      return [];
    }
  })()
);

export const tabsState = atom(["Tất cả", "Nam", "Nữ", "Trẻ em"]);

export const selectedTabIndexState = atom(0);

export const categoriesState = atom(async () => {
  const catalogEnabled = isCrmebFeatureEnabled("catalog");
  const apiUrl = getConfig((config) => config.template.apiUrl);
  if (!catalogEnabled)
    return await requestWithFallback<Category[]>("/categories", []);

  try {
    const client = new CrmebApiClient({
      apiBaseUrl: apiUrl,
      getToken: () => getCrmebToken(),
    });

    const raw = await client.get<Array<any>>("/category");
    return (raw ?? []).map((c) => ({
      id: Number(c?.id ?? 0),
      name: String(c?.cate_name ?? c?.name ?? ""),
      image: String(c?.pic ?? c?.image ?? ""),
    }));
  } catch (error) {
    console.warn("Failed to load categories from CRMEB:", error);
    return [];
  }
});

export const categoriesStateUpwrapped = unwrap(
  categoriesState,
  (prev) => prev ?? []
);

export const productsState = atom(async (get) => {
  const categories = await get(categoriesState);
  const catalogEnabled = isCrmebFeatureEnabled("catalog");
  const apiUrl = getConfig((config) => config.template.apiUrl);
  if (!catalogEnabled) {
    const products = await requestWithFallback<(Product & { categoryId: number })[]>(
      "/products",
      []
    );
    return products.map((product) => ({
      ...product,
      category: categories.find((category) => category.id === product.categoryId)!,
    }));
  }

  try {
    const client = new CrmebApiClient({
      apiBaseUrl: apiUrl,
      getToken: () => getCrmebToken(),
    });

    const rawProducts = await client.get<Array<any>>("/products");
    return (rawProducts ?? []).map((p) => {
      const categoryId = Number(
        String(p?.cate_id ?? p?.categoryId ?? 0).split(",")[0]
      );

      const category =
        categories.find((c) => c.id === categoryId) ?? {
          id: categoryId,
          name: "",
          image: "",
        };

      const originalPriceRaw = p?.ot_price;
      const originalPrice = originalPriceRaw
        ? Number(originalPriceRaw)
        : undefined;

      return {
        id: Number(p?.id ?? 0),
        name: String(p?.store_name ?? p?.name ?? ""),
        price: Number(p?.price ?? 0),
        originalPrice,
        image: String(p?.image ?? p?.recommend_image ?? ""),
        categoryId,
        category,
        detail: undefined,
      } as Product & { categoryId: number };
    });
  } catch (error) {
    console.warn("Failed to load products from CRMEB:", error);
    return [];
  }
});

export const flashSaleProductsState = atom((get) => get(productsState));

export const recommendedProductsState = atom((get) => get(productsState));

export const productState = atomFamily((id: number) =>
  atom(async (get) => {
    const products = await get(productsState);
    return products.find((product) => product.id === id);
  })
);

export const cartState = atom<Cart>([]);

export const selectedCartItemIdsState = atom<number[]>([]);

export const cartTotalState = atom((get) => {
  const items = get(cartState);
  return {
    totalItems: items.length,
    totalAmount: items.reduce(
      (total, item) => total + item.product.price * item.quantity,
      0
    ),
  };
});

export const keywordState = atom("");

export const searchResultState = atom(async (get) => {
  const keyword = get(keywordState);
  const products = await get(productsState);
  await new Promise((resolve) => setTimeout(resolve, 1000));
  return products.filter((product) =>
    product.name.toLowerCase().includes(keyword.toLowerCase())
  );
});

export const productsByCategoryState = atomFamily((id: String) =>
  atom(async (get) => {
    await new Promise((resolve) => setTimeout(resolve, 1000));
    const products = await get(productsState);
    return products.filter((product) => String(product.categoryId) === id);
  })
);

export const stationsState = atom(async () => {
  let location: Location | undefined;
  try {
    const { token } = await getLocation({});
    // Phía tích hợp làm theo hướng dẫn tại https://mini.zalo.me/documents/api/getLocation/ để chuyển đổi token thành thông tin vị trí người dùng ở server.
    // location = await decodeToken(token);

    // Các bước bên dưới để demo chức năng, phía tích hợp có thể bỏ đi sau.
    toast(
      "Đã lấy được token chứa thông tin vị trí người dùng. Phía tích hợp cần decode token này ở server. Giả lập vị trí tại VNG Campus...",
      {
        icon: "ℹ",
        duration: 10000,
      }
    );
    await new Promise((resolve) => setTimeout(resolve, 1000));
    location = {
      lat: 10.773756,
      lng: 106.689247,
    };
    // End demo
  } catch (error) {
    console.warn(error);
  }

  const stations = await requestWithFallback<Station[]>("/stations", []);
  const stationsWithDistance = stations.map((station) => ({
    ...station,
    distance: location
      ? formatDistant(
          calculateDistance(
            location.lat,
            location.lng,
            station.location.lat,
            station.location.lng
          )
        )
      : undefined,
  }));

  return stationsWithDistance;
});

export const selectedStationIndexState = atom(0);

export const selectedStationState = atom(async (get) => {
  const index = get(selectedStationIndexState);
  const stations = await get(stationsState);
  return stations[index];
});

export const shippingAddressState = atomWithStorage<
  ShippingAddress | undefined
>(CONFIG.STORAGE_KEYS.SHIPPING_ADDRESS, undefined);

function toOrderStatusFromCrmeb(raw: any): OrderStatus {
  const type = raw?._status?._type;
  if (type === 1) return "pending";
  if (type === 2 || type === 3) return "shipping";
  if (type === 4) return "completed";

  // Fallbacks (sometimes response might provide numeric order status)
  const numeric = raw?.status;
  if (numeric === 0) return "pending";
  if (numeric === 1 || numeric === 2) return "shipping";
  if (numeric === 3 || numeric === 4) return "completed";

  return "pending";
}

function toPaymentStatusFromCrmeb(raw: any): Order["paymentStatus"] {
  // CRMEB uses `paid` as 1/0; failed payment might be represented differently per config.
  if (raw?.paid === 1) return "success";
  return "pending";
}

function parseCrmebDate(value: unknown): Date {
  if (value === undefined || value === null) return new Date();
  if (typeof value === "number") return new Date(value);
  const d = new Date(String(value));
  return Number.isNaN(d.getTime()) ? new Date() : d;
}

function mapCrmebCartToCartItem(cart: any): Cart[number] {
  const productInfo = cart?.productInfo ?? cart?.product ?? {};
  const categoryId = Number(String(productInfo?.cate_id ?? 0).split(",")[0] ?? 0);
  const originalPriceRaw = productInfo?.ot_price ?? productInfo?.origin_price;

  return {
    product: {
      id: Number(productInfo?.id ?? productInfo?.product_id ?? 0),
      name: String(productInfo?.store_name ?? productInfo?.name ?? ""),
      price: Number(cart?.truePrice ?? cart?.price ?? productInfo?.truePrice ?? productInfo?.price ?? 0),
      originalPrice: originalPriceRaw ? Number(originalPriceRaw) : undefined,
      image: String(productInfo?.image ?? ""),
      category: {
        id: categoryId,
        name: "",
        image: "",
      },
      detail: undefined,
    },
    quantity: Number(cart?.cart_num ?? cart?.quantity ?? 0),
  };
}

function mapCrmebOrderToFchanOrder(raw: any): Order {
  const itemsRaw = raw?.cartInfo ?? raw?.cart_info ?? [];
  const items = (Array.isArray(itemsRaw) ? itemsRaw : []).map(mapCrmebCartToCartItem);

  const shippingType = Number(raw?.shipping_type ?? 1);
  const delivery: Delivery =
    shippingType === 2
      ? { type: "pickup", stationId: 0 }
      : {
          type: "shipping",
          alias: "",
          address: "",
          name: "",
          phone: "",
        };

  const total =
    Number(raw?.pay_price ?? raw?.total_price ?? raw?.total ?? 0) || 0;

  // `note` isn't clearly named in CRMEB responses; keep it safe.
  const note = String(raw?.remark ?? raw?.note ?? "");

  return {
    id: Number(raw?.id ?? raw?.order_id ?? 0),
    status: toOrderStatusFromCrmeb(raw),
    paymentStatus: toPaymentStatusFromCrmeb(raw),
    createdAt: parseCrmebDate(raw?._add_time ?? raw?.add_time ?? raw?.create_time),
    receivedAt: parseCrmebDate(raw?._add_time ?? raw?.add_time ?? raw?.create_time),
    items,
    delivery,
    total,
    note,
  };
}

export const ordersState = atomFamily((status: OrderStatus) =>
  atomWithRefresh(async () => {
    const ordersEnabled = isCrmebFeatureEnabled("orders");
    const apiUrl = getConfig((config) => config.template.apiUrl);
    if (!ordersEnabled) {
      const allMockOrders = await requestWithFallback<Order[]>("/orders", []);
      return allMockOrders.filter((order) => order.status === status);
    }

    try {
      const client = new CrmebApiClient({
        apiBaseUrl: apiUrl,
        getToken: () => getCrmebToken(),
      });

      const rawOrders = await client.get<Array<any>>("/order/list");
      const mapped = (rawOrders ?? []).map(mapCrmebOrderToFchanOrder);
      return mapped.filter((order) => order.status === status);
    } catch (error) {
      console.warn("Failed to load orders from CRMEB:", error);
      return [];
    }
  })
);

export const orderDetailState = atomFamily((orderId: number) =>
  atom(async () => {
    const ordersEnabled = isCrmebFeatureEnabled("orders");
    const apiUrl = getConfig((config) => config.template.apiUrl);
    if (!ordersEnabled) {
      const allMockOrders = await requestWithFallback<Order[]>("/orders", []);
      return allMockOrders.find((order) => order.id === orderId);
    }

    const client = new CrmebApiClient({
      apiBaseUrl: apiUrl,
      getToken: () => getCrmebToken(),
    });

    const raw = await client.get<any>(`/order/detail/${orderId}`);
    return mapCrmebOrderToFchanOrder(raw);
  })
);

export const deliveryModeState = atomWithStorage<Delivery["type"]>(
  CONFIG.STORAGE_KEYS.DELIVERY,
  "shipping"
);
