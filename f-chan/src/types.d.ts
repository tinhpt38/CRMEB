/** Node trong cây dữ liệu tỉnh/thành từ GET /city_list */
export interface CityNode {
  /** ID vùng */
  v: number;
  /** Tên vùng */
  n: string;
  /** Cấp con (Quận/Huyện hoặc Phường/Xã) */
  c: CityNode[];
}

export interface Banner {
  pic: string;
  /** Link điều hướng khi nhấn banner. Có thể là URL web hoặc path nội bộ CRMEB. */
  link?: string;
}

export interface UserInfo {
  id: string;
  name: string;
  avatar: string;
  phone: string;
  email: string;
  address: string;
  /** Điểm thưởng CRMEB (từ GET /userinfo). */
  integral?: number;
}

export interface Product {
  id: number;
  name: string;
  price: number;
  originalPrice?: number;
  image: string;
  images?: string[];
  category: Category;
  detail?: string;
  attributes?: ProductAttribute[];
}

export interface ProductAttribute {
  name: string;
  values: string[];
}

export interface Category {
  id: number;
  name: string;
  image: string;
}

export interface CartItem {
  product: Product;
  quantity: number;
  /** unique key từ CRMEB cartInfo — dùng cho POST /order/comment */
  unique?: string;
}

export type Cart = CartItem[];

export interface Location {
  lat: number;
  lng: number;
}

export interface ShippingAddress {
  alias: string;
  /** Tỉnh / Thành phố — bắt buộc khi lưu lên CRMEB */
  province: string;
  /** Quận / Huyện */
  city: string;
  /** Phường / Xã */
  district: string;
  /** Số nhà, tên đường (chi tiết) */
  address: string;
  name: string;
  phone: string;
}

/** Địa chỉ lưu trên server CRMEB (từ GET /address/list) */
export interface CrmebAddress {
  id: number;
  real_name: string;
  phone: string;
  province: string;
  city: string;
  district: string;
  detail: string;
  is_default: number;
}

export interface Station {
  id: number;
  name: string;
  image: string;
  address: string;
  location: Location;
}

export type Delivery =
  | ({
      type: "shipping";
    } & ShippingAddress)
  | {
      type: "pickup";
      stationId: number;
    };

export type OrderStatus = "pending" | "shipping" | "completed";
export type PaymentStatus = "pending" | "success" | "failed";

export interface Order {
  id: string;
  status: OrderStatus;
  paymentStatus: PaymentStatus;
  createdAt: Date;
  receivedAt: Date;
  items: CartItem[];
  delivery: Delivery;
  total: number;
  note: string;
  /** Mã pay_type từ CRMEB (vn_cod, vn_bank, offline, …) */
  payType?: string;
  /** Nhãn hiển thị từ CRMEB (`_status._payType`) */
  payTypeName?: string;
  /** Hướng dẫn CK/VietQR (cấu hình cửa hàng) khi đơn dùng vn_bank */
  bankPayGuide?: string;
  /** URL ảnh QR CK (CRMEB `vn_bank_pay_qr_image`) */
  bankPayQrUrl?: string;
  /** `_status._title` từ CRMEB */
  statusTitle?: string;
  /** `_status._msg` */
  statusMessage?: string;
  /** `_status._type` — khớp ảnh trạng thái / logic tab */
  crmebStatusType?: number;
  /** Unix timestamp — hết hạn giữ đơn thanh toán (`stop_time`) */
  stopTime?: number;
  /** ID nội bộ DB (số nguyên) — dùng cho POST /order/refund/apply/:id */
  dbId?: number;
  /** Mã vận đơn (`delivery_id`) */
  deliveryId?: string;
  /** Tên nhà vận chuyển (`delivery_name`) */
  deliveryName?: string;
  /** Loại giao hàng CRMEB: send | express | split */
  deliveryType?: string;
  /** Cho phép hoàn tiền / trả hàng (`_status._is_back`) */
  isBack?: boolean;
}

/**
 * Phương thức thanh toán ở màn đặt hàng f-chan — map sang CRMEB:
 * cod → vn_cod, bank_transfer → vn_bank, other → offline.
 */
export type CheckoutPaymentMethod = "cod" | "bank_transfer" | "other";
