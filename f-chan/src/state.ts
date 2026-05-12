import { atom } from "jotai";
import {
  atomFamily,
  atomWithRefresh,
  atomWithStorage,
  loadable,
  unwrap,
} from "jotai/utils";
import {
  Banner,
  Cart,
  Category,
  CityNode,
  CrmebAddress,
  Delivery,
  Location,
  Order,
  OrderStatus,
  CheckoutPaymentMethod,
  PickupContact,
  Product,
  ProductAttribute,
  ShippingAddress,
  Station,
  UserInfo,
} from "@/types";
import { resolveOrderPayMeta } from "@/utils/crmeb/payConfig";
import { requestWithFallback } from "@/utils/request";
import {
  authorize,
  getAccessToken,
  getPhoneNumber,
  getSetting,
  getUserInfo,
} from "zmp-sdk/apis";
import toast from "react-hot-toast";
import { calculateDistance } from "./utils/location";
import { formatDistant } from "./utils/format";
import { resolveUserLocation } from "./utils/deviceLocation";
import {
  normalizeProductVariants,
  normalizeSkuDimensions,
} from "./utils/productSpecs";
import CONFIG from "./config";
import { getConfig } from "./utils/template";
import { CrmebApiClient } from "./utils/crmeb/client";
import { getCrmebToken, setCrmebToken } from "./utils/crmeb/token";
import { enrichUserInfoFromCrmebRecords } from "./utils/crmeb/userProfile";
import { isSessionLoggedOut, setSessionLoggedOut } from "./utils/session";

/**
 * Resolve a CRMEB image path to an absolute URL.
 * CRMEB thường trả về đường dẫn tương đối như `/uploads/attach/xxx.jpg`.
 * Hàm này ghép domain từ apiUrl nếu URL chưa phải tuyệt đối.
 */
function resolveImageUrl(url: string | undefined | null, apiUrl: string): string {
  if (!url) return "";
  if (url.startsWith("http://") || url.startsWith("https://")) return url;
  try {
    const origin = new URL(apiUrl).origin;
    return `${origin}${url.startsWith("/") ? "" : "/"}${url}`;
  } catch {
    return url;
  }
}

function normalizeProductAttributes(raw: Record<string, any>): ProductAttribute[] {
  const fromProductAttr = Array.isArray(raw?.productAttr) ? raw.productAttr : [];
  const attrsFromProductAttr = fromProductAttr
    .map((attr: any) => {
      const name = String(attr?.attr_name ?? attr?.name ?? "").trim();
      const valuesFromText = String(attr?.attr_values ?? attr?.values ?? "")
        .split(",")
        .map((value) => value.trim())
        .filter(Boolean);
      const valuesFromArray = Array.isArray(attr?.attr_value)
        ? attr.attr_value
            .map((item: any) =>
              String(item?.attr ?? item?.value ?? item?.label ?? "").trim()
            )
            .filter(Boolean)
        : [];
      const values = valuesFromArray.length ? valuesFromArray : valuesFromText;
      if (!name || !values.length) return null;
      return { name, values };
    })
    .filter((attr: ProductAttribute | null): attr is ProductAttribute => attr !== null);

  if (attrsFromProductAttr.length) return attrsFromProductAttr;

  const fromAttrInfo = raw?.attrInfo;
  if (!fromAttrInfo || typeof fromAttrInfo !== "object") return [];

  return Object.entries(fromAttrInfo)
    .map(([name, value]) => {
      const safeName = String(name).trim();
      const safeValue = String(value ?? "").trim();
      if (!safeName || !safeValue) return null;
      return { name: safeName, values: [safeValue] };
    })
    .filter((attr: ProductAttribute | null): attr is ProductAttribute => attr !== null);
}

export const userInfoKeyState = atom(0);

export type PageHeaderContext = {
  title?: string;
  shareProduct?: Pick<Product, "id" | "name" | "image">;
};

export const pageHeaderContextState = atom<PageHeaderContext | undefined>(
  undefined
);

export const userInfoState = atom<Promise<UserInfo | undefined>>(
  async (get) => {
    get(userInfoKeyState);

    if (isSessionLoggedOut()) {
      return undefined;
    }

    const isDev = !window.ZJSBridge;
    const apiUrl = getConfig((config) => config.template.apiUrl);
    const useCrmebLive = Boolean(apiUrl && !isDev);

    // Cache local chỉ dùng cho môi trường dev hoặc không cấu hình CRMEB.
    // Khi Mini App chạy CRMEB thật: luôn đồng bộ `/zalo/auth` + `/userinfo` + địa chỉ để
    // tài khoản đã có trên CRMEB (web/H5) được điền đúng vào app — tránh giữ bản LS cũ.
    let fallbackFromStorage: UserInfo | undefined;
    const savedUserInfo = localStorage.getItem(CONFIG.STORAGE_KEYS.USER_INFO);
    if (savedUserInfo) {
      try {
        const parsed = JSON.parse(savedUserInfo) as UserInfo;
        if (!useCrmebLive) {
          return parsed;
        }
        fallbackFromStorage = parsed;
      } catch {
        localStorage.removeItem(CONFIG.STORAGE_KEYS.USER_INFO);
      }
    }

    // Nhánh CRMEB: dùng Zalo access_token để tạo/đăng nhập tài khoản CRMEB.
    // Không gọi getSetting ở đây để tránh block khi user chưa cấp quyền scope.userInfo.
    if (useCrmebLive) {
      try {
        // Zalo SDK ≥2.35: token mặc định chỉ đủ lấy user id; phải xin scope.userInfo
        // *trước* getAccessToken thì Graph /me mới trả name/picture (tránh Zalo_xxxxxx).
        if (!isDev) {
          try {
            await authorize({ scopes: ["scope.userInfo"] });
          } catch (e) {
            console.warn("Zalo authorize scope.userInfo (trước CRMEB /zalo/auth):", e);
          }
        }
        const accessToken = await getAccessToken();
        const client = new CrmebApiClient({
          apiBaseUrl: apiUrl,
          getToken: () => getCrmebToken(),
        });

        const result = await client.post<{
          token: string;
          expires_time: number;
          userInfo: any;
        }>("/zalo/auth", {
          access_token: accessToken,
          source: "fchan",
        });

        if (result?.token) setCrmebToken(result.token);
        setSessionLoggedOut(false);

        const crmUser = result?.userInfo ?? {};
        let mapped: UserInfo = {
          id: String(crmUser.uid ?? crmUser.id ?? ""),
          name: String(crmUser.nickname ?? crmUser.name ?? ""),
          avatar: String(crmUser.avatar ?? ""),
          phone: String(crmUser.phone ?? ""),
          email: "",
          address: "",
        };

        try {
          const [profile, addressPayload] = await Promise.all([
            client.get<Record<string, unknown>>("/userinfo"),
            client.get<unknown>("/address/list").catch(() => []),
          ]);
          mapped = enrichUserInfoFromCrmebRecords(mapped, profile, addressPayload);
        } catch (enrichErr) {
          console.warn("CRMEB enrich user profile failed:", enrichErr);
        }

        localStorage.setItem(
          CONFIG.STORAGE_KEYS.USER_INFO,
          JSON.stringify(mapped)
        );
        return mapped;
      } catch (error) {
        console.warn("CRMEB Zalo login failed:", error);
        if (fallbackFromStorage) return fallbackFromStorage;
        return undefined;
      }
    }

    // Nhánh dev / không có apiUrl: dùng Zalo SDK trực tiếp.
    try {
      const {
        authSetting: {
          "scope.userInfo": grantedUserInfo,
          "scope.userPhonenumber": grantedPhoneNumber,
        },
      } = await getSetting({});

      if (grantedUserInfo || isDev) {
        const { userInfo } = await getUserInfo({});
        const phone =
          grantedPhoneNumber || isDev ? await get(phoneState) : "";
        setSessionLoggedOut(false);
        return {
          id: userInfo.id,
          name: userInfo.name,
          avatar: userInfo.avatar,
          phone,
          email: "",
          address: "",
        };
      }
    } catch {
      // getSetting/getUserInfo có thể fail trong một số môi trường — bỏ qua.
    }

    return undefined;
  }
);

export const loadableUserInfoState = loadable(userInfoState);

export const phoneState = atom(async () => {
  let phone = "";
  try {
    const { token } = await getPhoneNumber({});
    // Gọi API server để decode token thành số điện thoại thực.
    // Tham khảo: https://mini.zalo.me/documents/api/getPhoneNumber/
    // phone = await decodePhoneToken(token);
    void token;
  } catch (error) {
    console.warn(error);
  }
  return phone;
});

export const bannersState = atom(() =>
  (async () => {
    const apiUrl = getConfig((config) => config.template.apiUrl);
    try {
      const client = new CrmebApiClient({
        apiBaseUrl: apiUrl,
        getToken: () => getCrmebToken(),
      });

      // Gọi endpoint chuyên biệt cho banner - dữ liệu cấu hình tại
      // Admin → Cài đặt → Cấu hình dữ liệu (routine_home_banner)
      // Backend: GET /api/home/banner → v1.PublicController::homeBanner
      const res = await client.get<Record<string, any>>("/home/banner");
      const bannerList: Array<any> = res?.banner ?? [];
      if (bannerList.length) {
        return bannerList
          .map((b): Banner | null => {
            // Dùng || (không phải ??) vì CRMEB có thể trả về empty string ""
            // routine_home_bast_banner trả về field "img"; các group khác dùng "pic"/"image"/"url"
            const pic = resolveImageUrl(b?.img || b?.pic || b?.image || b?.url, apiUrl);
            if (!pic) return null;
            return { pic, link: b?.link || b?.url || b?.url2 || "" };
          })
          .filter((b): b is Banner => b !== null);
      }

      // Fallback: dùng ảnh sản phẩm đầu tiên nếu chưa có banner nào được cấu hình
      const products = await client.get<Array<any>>("/products");
      return (Array.isArray(products) ? products : [])
        .slice(0, 5)
        .map((p): Banner | null => {
          // recommend_image thường là "" (empty string) → || để fallback đúng
          const pic = resolveImageUrl(p?.image || p?.recommend_image, apiUrl);
          if (!pic) return null;
          return { pic, link: "" };
        })
        .filter((b): b is Banner => b !== null);
    } catch (error) {
      console.warn("Failed to load banners from CRMEB:", error);
      return [];
    }
  })()
);

export const tabsState = atom(["Tất cả", "Nam", "Nữ", "Trẻ em"]);

export const selectedTabIndexState = atom(0);

export const categoriesState = atom(async () => {
  const apiUrl = getConfig((config) => config.template.apiUrl);
  try {
    const client = new CrmebApiClient({
      apiBaseUrl: apiUrl,
      getToken: () => getCrmebToken(),
    });

    const raw = await client.get<Array<any>>("/category");
    return (raw ?? []).map((c) => ({
      id: Number(c?.id ?? 0),
      name: String(c?.cate_name ?? c?.name ?? ""),
      image: resolveImageUrl(c?.pic ?? c?.image, apiUrl),
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
  const apiUrl = getConfig((config) => config.template.apiUrl);
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

        const sliderRaw = p?.slider_image;
        const rawSliderList: string[] = Array.isArray(sliderRaw)
          ? sliderRaw.map(String).filter(Boolean)
          : typeof sliderRaw === "string" && sliderRaw
            ? sliderRaw.split(",").map((s) => s.trim()).filter(Boolean)
            : [];
        const images: string[] | undefined = rawSliderList.length
          ? rawSliderList.map((s) => resolveImageUrl(s, apiUrl))
          : undefined;

        return {
          id: Number(p?.id ?? 0),
          name: String(p?.store_name ?? p?.name ?? ""),
          price: Number(p?.price ?? 0),
          originalPrice,
          image: resolveImageUrl(p?.image || p?.recommend_image, apiUrl),
          images,
          categoryId,
          category,
          detail: String(p?.store_info ?? p?.description ?? ""),
          attributes: [],
          specType: Number(p?.spec_type ?? 0) === 1,
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

/**
 * Fetch full product detail from CRMEB `GET /product/detail/:id/0`.
 * Returns the list-level Product enriched with `detail` (HTML) and `images`.
 * Falls back to the list-level entry when CRMEB integration is disabled or the
 * call fails.
 */
export const productDetailState = atomFamily((id: number) =>
  atom(async (get) => {
  const apiUrl = getConfig((config) => config.template.apiUrl);

  // Base product from the list (provides category, price, etc.)
  const base = await get(productState(id));

  if (!apiUrl) return base;

    try {
      const client = new CrmebApiClient({
        apiBaseUrl: apiUrl,
        getToken: () => getCrmebToken(),
      });

      // CRMEB: GET /product/detail/:id/:type  (type 0 = normal)
      const raw = await client.get<Record<string, any>>(
        `/product/detail/${id}/0`
      );

      if (!raw) return base;
      const detailPayload =
        raw?.storeInfo && typeof raw.storeInfo === "object" ? raw.storeInfo : raw;

      const sliderRaw = detailPayload.slider_image;
      const rawSliderList: string[] = Array.isArray(sliderRaw)
        ? sliderRaw.map(String).filter(Boolean)
        : typeof sliderRaw === "string" && sliderRaw
          ? sliderRaw.split(",").map((s: string) => s.trim()).filter(Boolean)
          : [];
      const images: string[] | undefined = rawSliderList.length
        ? rawSliderList.map((s) => resolveImageUrl(s, apiUrl))
        : base?.images;

      const originalPriceRaw = detailPayload.ot_price ?? detailPayload.origin_price;
      const originalPrice = originalPriceRaw
        ? Number(originalPriceRaw)
        : base?.originalPrice;
      const specType = Number(detailPayload.spec_type ?? 0) === 1;
      const skuDimensions = normalizeSkuDimensions(raw);
      const variants = normalizeProductVariants(raw, detailPayload, apiUrl);
      const defaultUnique =
        String(raw?.spec_unique ?? variants[0]?.unique ?? "").trim() || undefined;
      const attributes = skuDimensions.length
        ? skuDimensions
        : normalizeProductAttributes(raw);

      return {
        ...(base ?? {}),
        id: Number(detailPayload.id ?? id),
        name: String(
          detailPayload.store_name ?? detailPayload.name ?? base?.name ?? ""
        ),
        price: Number(detailPayload.price ?? base?.price ?? 0),
        originalPrice,
        image: resolveImageUrl(detailPayload.image || base?.image, apiUrl),
        images,
        detail: String(
          detailPayload.description ??
            detailPayload.content ??
            detailPayload.store_info ??
            detailPayload.detail ??
            base?.detail ??
            ""
        ),
        attributes,
        specType,
        skuDimensions,
        variants,
        defaultUnique,
        category: base?.category ?? { id: 0, name: "", image: "" },
      } as Product;
    } catch (error) {
      console.warn("Failed to load product detail from CRMEB:", error);
      return base;
    }
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
  return products.filter((product) =>
    product.name.toLowerCase().includes(keyword.toLowerCase())
  );
});

export const productsByCategoryState = atomFamily((id: String) =>
  atom(async (get) => {
    const products = await get(productsState);
    return products.filter((product) => String(product.categoryId) === id);
  })
);

function mapCrmebStoreToStation(raw: any, apiUrl: string): Station {
  const lat = Number(raw?.latitude ?? 0);
  const lng = Number(raw?.longitude ?? 0);
  const fullAddress = [raw?.address, raw?.detailed_address]
    .map((v) => String(v ?? "").trim())
    .filter(Boolean)
    .join(", ");

  return {
    id: Number(raw?.id ?? 0),
    name: String(raw?.name ?? ""),
    image: resolveImageUrl(raw?.image || raw?.oblong_image, apiUrl),
    address: fullAddress,
    location: {
      lat: Number.isFinite(lat) ? lat : 0,
      lng: Number.isFinite(lng) ? lng : 0,
    },
  };
}

export const userLocationState = atomWithRefresh(async () => resolveUserLocation());

export const stationsState = atomWithRefresh(async (get) => {
  const location = await get(userLocationState);
  const apiUrl = getConfig((config) => config.template.apiUrl);
  let storeRows: any[] = [];

  if (apiUrl) {
    try {
      const client = new CrmebApiClient({
        apiBaseUrl: apiUrl,
        getToken: () => getCrmebToken(),
      });

      const raw = await client.get<Record<string, any>>("/store_list", {
        ...(location
          ? {
              latitude: location.lat,
              longitude: location.lng,
            }
          : {}),
      });
      storeRows = Array.isArray(raw?.list?.list)
        ? raw.list.list
        : Array.isArray(raw?.list)
          ? raw.list
          : [];
    } catch (error) {
      console.warn("Failed to load stores from CRMEB:", error);
    }
  }

  const stationsWithDistance = storeRows.length
    ? storeRows.map((item) => {
        const station = mapCrmebStoreToStation(item, apiUrl);
        const distanceKmFromApi = Number(item?.distance);
        const distanceKm =
          Number.isFinite(distanceKmFromApi) && distanceKmFromApi >= 0
            ? distanceKmFromApi
            : location
              ? calculateDistance(
                  location.lat,
                  location.lng,
                  station.location.lat,
                  station.location.lng
                )
              : undefined;

        return {
          ...station,
          distanceKm,
          distance:
            distanceKm !== undefined ? formatDistant(distanceKm) : undefined,
        };
      })
    : (await requestWithFallback<Station[]>("/stations", [])).map((station) => ({
        ...station,
        distanceKm: location
          ? calculateDistance(
              location.lat,
              location.lng,
              station.location.lat,
              station.location.lng
            )
          : undefined,
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

  return stationsWithDistance.sort((left, right) => {
    if (left.distanceKm === undefined && right.distanceKm === undefined) {
      return left.id - right.id;
    }
    if (left.distanceKm === undefined) return 1;
    if (right.distanceKm === undefined) return -1;
    return left.distanceKm - right.distanceKm;
  });
});

export const selectedStationIdState = atomWithStorage<number | null>(
  CONFIG.STORAGE_KEYS.SELECTED_STATION_ID,
  null
);

export const firstStationState = atom(async (get) => {
  const stations = await get(stationsState);
  return stations[0] ?? null;
});

export const loadableFirstStationState = loadable(firstStationState);

export const selectedStationState = atom(async (get) => {
  const stations = await get(stationsState);
  if (!stations.length) return null;

  const selectedId = get(selectedStationIdState);
  if (selectedId) {
    const selected = stations.find((station) => station.id === selectedId);
    if (selected) return selected;
  }

  return stations[0];
});

export const loadableSelectedStationState = loadable(selectedStationState);

export const shippingAddressState = atomWithStorage<
  ShippingAddress | undefined
>(CONFIG.STORAGE_KEYS.SHIPPING_ADDRESS, undefined);

function toOrderStatusFromCrmeb(raw: any): OrderStatus {
  // Dùng `_status._type` từ CRMEB tidyOrder (nguồn tin cậy nhất).
  // Bảng mapping theo docs/order-flow-vietnam-mapping.md:
  //   -2 = Đã hoàn tiền          → Lịch sử
  //   -1 = Đang hoàn tiền        → Lịch sử (đơn đã qua giao hàng, đang xử lý hoàn)
  //    0 = Chờ thanh toán        → Đang xử lý
  //    1 = Đang xử lý (paid)     → Đang xử lý
  //    2 = Đang giao             → Đang giao
  //    3 = Đã nhận/chờ đánh giá  → Lịch sử  (admin xác nhận giao thành công)
  //    4 = Hoàn tất / Đã hủy     → Lịch sử
  //    9 = Chờ đối soát CK/COD   → Đang xử lý
  const type = raw?._status?._type;
  if (typeof type === "number") {
    if (type === 2) return "shipping";
    if (type === 1 || type === 0 || type === 9) return "pending";
    // type 3, 4, -1, -2 và bất kỳ trạng thái kết thúc khác → Lịch sử
    return "completed";
  }

  // Fallback khi API không trả _status (gọi trực tiếp từ /order/list cũ).
  // status CRMEB: 0=xử lý, 1=đang giao, 2=đã nhận/chờ đánh giá, 3=hoàn tất, 4=split
  const numeric = raw?.status;
  if (numeric === 0) return "pending";
  if (numeric === 1) return "shipping";
  // status 2 (đã nhận), 3 (hoàn tất), 4 (split xong) → Lịch sử
  if (numeric === 2 || numeric === 3 || numeric === 4) return "completed";

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

function mapCrmebCartToCartItem(cart: any, apiUrl: string): Cart[number] {
  const productInfo = cart?.productInfo ?? cart?.product ?? {};
  const attrInfo = productInfo?.attrInfo ?? cart?.attrInfo;
  const categoryId = Number(String(productInfo?.cate_id ?? 0).split(",")[0] ?? 0);
  const originalPriceRaw =
    attrInfo?.ot_price ?? productInfo?.ot_price ?? productInfo?.origin_price;
  const unique = String(cart?.unique ?? attrInfo?.unique ?? "").trim() || undefined;
  const variantLabel = attrInfo?.suk
    ? String(attrInfo.suk).replace(/,/g, " / ")
    : undefined;

  return {
    product: {
      id: Number(productInfo?.id ?? productInfo?.product_id ?? 0),
      name: String(productInfo?.store_name ?? productInfo?.name ?? ""),
      price: Number(
        cart?.truePrice ??
          cart?.price ??
          attrInfo?.price ??
          productInfo?.truePrice ??
          productInfo?.price ??
          0
      ),
      originalPrice: originalPriceRaw ? Number(originalPriceRaw) : undefined,
      image: resolveImageUrl(attrInfo?.image || productInfo?.image, apiUrl),
      category: {
        id: categoryId,
        name: "",
        image: "",
      },
      detail: undefined,
      variantLabel,
      defaultUnique: unique,
    },
    quantity: Number(cart?.cart_num ?? cart?.quantity ?? 0),
    ...(unique ? { unique } : {}),
  };
}

function mapCrmebOrderToFchanOrder(raw: any, apiUrl: string): Order {
  const itemsRaw = raw?.cartInfo ?? raw?.cart_info ?? [];
  const items = (Array.isArray(itemsRaw) ? itemsRaw : []).map((cart) => mapCrmebCartToCartItem(cart, apiUrl));

  const shippingType = Number(raw?.shipping_type ?? 1);
  const systemStoreRaw = raw?.system_store ?? raw?.systemStore;
  const systemStore =
    systemStoreRaw && typeof systemStoreRaw === "object"
      ? (systemStoreRaw as Record<string, unknown>)
      : null;
  const delivery: Delivery =
    shippingType === 2
      ? {
          type: "pickup",
          stationId: Number(raw?.store_id ?? systemStore?.id ?? 0),
          name: String(
            systemStore?.name ?? raw?._store_name ?? raw?.store_name ?? ""
          ),
          address: [systemStore?.address, systemStore?.detailed_address]
            .map((value) => String(value ?? "").trim())
            .filter(Boolean)
            .join(", "),
          contactName: String(raw?.real_name ?? "").trim() || undefined,
          contactPhone: String(raw?.user_phone ?? "").trim() || undefined,
          verifyCode:
            String(raw?.verify_code ?? raw?._verify_code ?? "").trim() ||
            undefined,
        }
      : {
          type: "shipping",
          alias: String(raw?.real_name ?? ""),
          province: String(raw?.user_province ?? raw?.province ?? ""),
          city: String(raw?.user_city ?? raw?.city ?? ""),
          district: String(raw?.user_district ?? raw?.district ?? ""),
          address: String(raw?.user_address ?? ""),
          name: String(raw?.real_name ?? ""),
          phone: String(raw?.user_phone ?? ""),
        };

  const total =
    Number(raw?.pay_price ?? raw?.total_price ?? raw?.total ?? 0) || 0;

  // `note` isn't clearly named in CRMEB responses; keep it safe.
  const note = String(raw?.remark ?? raw?.note ?? "");
  const { payType, payTypeName } = resolveOrderPayMeta(
    raw && typeof raw === "object" ? (raw as Record<string, unknown>) : {}
  );
  const bankPayGuide = String(raw?.vn_bank_pay_guide ?? "").trim();
  const bankQrRaw = String(raw?.vn_bank_pay_qr_image ?? "").trim();
  const bankPayQrUrl = bankQrRaw ? resolveImageUrl(bankQrRaw, apiUrl) : "";
  const st = raw?._status && typeof raw._status === "object" ? raw._status : {};
  const statusTitle = String(st._title ?? "").trim();
  const statusMessage = String(st._msg ?? "").trim();
  const crmebStatusTypeRaw = st._type;
  const crmebStatusTypeNum =
    typeof crmebStatusTypeRaw === "number"
      ? crmebStatusTypeRaw
      : Number(crmebStatusTypeRaw);
  const stopRaw = raw?.stop_time;
  const stopTime =
    typeof stopRaw === "number"
      ? stopRaw
      : stopRaw
        ? Number(stopRaw)
        : undefined;

  // Mã vận đơn và nhà vận chuyển — có trong /order/detail/:uni
  const deliveryId = String(raw?.delivery_id ?? "").trim() || undefined;
  const deliveryName = String(raw?.delivery_name ?? "").trim() || undefined;
  const deliveryType = String(raw?.delivery_type ?? "").trim() || undefined;

  // ID nội bộ DB (số nguyên) — khác với order_id (string hiển thị); dùng cho /order/refund/apply/:id
  const dbId = raw?.id && !Number.isNaN(Number(raw.id)) ? Number(raw.id) : undefined;

  // _is_back = CRMEB cho phép hoàn tiền / trả hàng với đơn này
  const isBack = st._is_back === true || st._is_back === 1;

  return {
    id: String(raw?.order_id ?? raw?.id ?? raw?.uni ?? ""),
    status: toOrderStatusFromCrmeb(raw),
    paymentStatus: toPaymentStatusFromCrmeb(raw),
    createdAt: parseCrmebDate(raw?._add_time ?? raw?.add_time ?? raw?.create_time),
    receivedAt: parseCrmebDate(raw?._add_time ?? raw?.add_time ?? raw?.create_time),
    items,
    delivery,
    total,
    note,
    ...(payType ? { payType } : {}),
    ...(payTypeName ? { payTypeName } : {}),
    ...(bankPayGuide ? { bankPayGuide } : {}),
    ...(bankPayQrUrl ? { bankPayQrUrl } : {}),
    ...(statusTitle ? { statusTitle } : {}),
    ...(statusMessage ? { statusMessage } : {}),
    ...(Number.isFinite(crmebStatusTypeNum) ? { crmebStatusType: crmebStatusTypeNum } : {}),
    ...(stopTime !== undefined && Number.isFinite(stopTime) && stopTime > 0
      ? { stopTime }
      : {}),
    ...(deliveryId ? { deliveryId } : {}),
    ...(deliveryName ? { deliveryName } : {}),
    ...(deliveryType ? { deliveryType } : {}),
    ...(dbId ? { dbId } : {}),
    isBack,
  };
}

function extractCrmebOrderList(raw: any): any[] {
  if (Array.isArray(raw)) return raw;
  if (Array.isArray(raw?.list)) return raw.list;
  if (Array.isArray(raw?.list?.list)) return raw.list.list;
  return [];
}

export const ordersState = atomFamily((status: OrderStatus) =>
  atomWithRefresh(async () => {
    const apiUrl = getConfig((config) => config.template.apiUrl);
    try {
      const client = new CrmebApiClient({
        apiBaseUrl: apiUrl,
        getToken: () => getCrmebToken(),
      });

      const rawOrders = await client.get<any>("/order/list");
      const mapped = extractCrmebOrderList(rawOrders).map((o) =>
        mapCrmebOrderToFchanOrder(o, apiUrl)
      );
      return mapped.filter((order) => order.status === status);
    } catch (error) {
      console.warn("Failed to load orders from CRMEB:", error);
      return [];
    }
  })
);

export const orderDetailState = atomFamily((orderId: string) =>
  atom(async () => {
    if (!orderId) return undefined;

    const apiUrl = getConfig((config) => config.template.apiUrl);
    const client = new CrmebApiClient({
      apiBaseUrl: apiUrl,
      getToken: () => getCrmebToken(),
    });

    const raw = await client.get<any>(`/order/detail/${orderId}`);
    const payload = raw?.orderInfo && typeof raw.orderInfo === "object" ? raw.orderInfo : raw;
    return mapCrmebOrderToFchanOrder(payload, apiUrl);
  })
);

export const deliveryModeState = atomWithStorage<Delivery["type"]>(
  CONFIG.STORAGE_KEYS.DELIVERY,
  "shipping"
);

export const pickupContactState = atomWithStorage<PickupContact>(
  CONFIG.STORAGE_KEYS.PICKUP_CONTACT,
  { real_name: "", phone: "" }
);

/**
 * Phương thức thanh toán người dùng chọn tại checkout.
 */
export const checkoutPaymentMethodState = atomWithStorage<CheckoutPaymentMethod>(
  "checkout_payment_method",
  "vn_cod"
);

// ---------------------------------------------------------------------------
// Địa chỉ CRMEB — danh sách, lựa chọn, địa chỉ hiển thị
// ---------------------------------------------------------------------------

function mapCrmebAddress(a: any): CrmebAddress {
  return {
    id: Number(a?.id ?? 0),
    real_name: String(a?.real_name ?? ""),
    phone: String(a?.phone ?? ""),
    province: String(a?.province ?? ""),
    city: String(a?.city ?? ""),
    district: String(a?.district ?? ""),
    detail: String(a?.detail ?? ""),
    is_default: Number(a?.is_default ?? 0),
  };
}

/**
 * Danh sách địa chỉ của user trên CRMEB.
 * atomWithRefresh → gọi refreshAddresses() để reload sau khi thêm/sửa.
 * Đọc `userInfoKeyState` để danh sách địa chỉ được tính lại khi login/bind phone/logout (cùng key với user).
 */
export const crmebAddressesState = atomWithRefresh(async (get) => {
  get(userInfoKeyState);
  const apiUrl = getConfig((config) => config.template.apiUrl);
  const token = getCrmebToken();
  if (!apiUrl || !token) return [] as CrmebAddress[];
  try {
    const client = new CrmebApiClient({
      apiBaseUrl: apiUrl,
      getToken: () => token,
    });
    const raw = await client.get<Array<any>>("/address/list");
    return (raw ?? []).map(mapCrmebAddress);
  } catch {
    return [] as CrmebAddress[];
  }
});

/** ID địa chỉ đang được chọn (persist qua localStorage) */
export const selectedCrmebAddressIdState = atomWithStorage<number | null>(
  CONFIG.STORAGE_KEYS.CRMEB_ADDRESS_ID,
  null
);

/**
 * Địa chỉ đang được chọn.
 * Ưu tiên: selectedId → is_default → phần tử đầu tiên → null
 */
export const selectedCrmebAddressState = atom(async (get) => {
  const addresses = await get(crmebAddressesState);
  if (!addresses.length) return null;
  const selectedId = get(selectedCrmebAddressIdState);
  if (selectedId !== null) {
    const found = addresses.find((a) => a.id === selectedId);
    if (found) return found;
  }
  return addresses.find((a) => a.is_default === 1) ?? addresses[0] ?? null;
});

export const loadableSelectedCrmebAddressState = loadable(
  selectedCrmebAddressState
);

// ---------------------------------------------------------------------------
// Dữ liệu tỉnh/thành từ CRMEB — dùng cho form chọn địa chỉ
// ---------------------------------------------------------------------------

/**
 * Tải cây tỉnh/huyện từ GET /city_list.
 * Không cần auth — endpoint public.
 * Cache tự nhiên nhờ atom (không refresh trong session).
 */
export const cityListState = atom<Promise<CityNode[]>>(async () => {
  const apiUrl = getConfig((config) => config.template.apiUrl);
  if (!apiUrl) return [];
  try {
    const client = new CrmebApiClient({
      apiBaseUrl: apiUrl,
      getToken: () => getCrmebToken(),
    });
    const raw = await client.get<CityNode[]>("/city_list");
    return Array.isArray(raw) ? raw : [];
  } catch (error) {
    console.warn("Failed to load city list:", error);
    return [];
  }
});

export const loadableCityListState = loadable(cityListState);
