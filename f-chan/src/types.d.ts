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
}

/**
 * Phương thức thanh toán hiển thị ở màn checkout.
 * `payType` thực tế vẫn map qua luồng offline của CRMEB.
 */
export type CheckoutPaymentMethod = "cod" | "bank_transfer" | "other";
