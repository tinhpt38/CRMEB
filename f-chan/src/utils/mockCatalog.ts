import bannersJson from "@/mock/banners.json";
import categoriesJson from "@/mock/categories.json";
import ordersJson from "@/mock/orders.json";
import productsJson from "@/mock/products.json";
import stationsJson from "@/mock/stations.json";
import {
  Banner,
  Category,
  Order,
  OrderStatus,
  Product,
  Station,
} from "@/types";

type MockProductRow = {
  id: number;
  categoryId: number;
  name: string;
  price: number;
  originalPrice?: number;
  image: string;
  detail?: string;
};

export function getMockBanners(): Banner[] {
  return (bannersJson as string[]).map((pic) => ({ pic, link: "" }));
}

export function getMockCategories(): Category[] {
  return categoriesJson as Category[];
}

export function getMockProducts(): (Product & { categoryId: number })[] {
  const categories = getMockCategories();
  return (productsJson as MockProductRow[]).map((row) => {
    const category =
      categories.find((c) => c.id === row.categoryId) ?? {
        id: row.categoryId,
        name: "",
        image: "",
      };
    return {
      id: row.id,
      name: row.name,
      price: row.price,
      originalPrice: row.originalPrice,
      image: row.image,
      categoryId: row.categoryId,
      category,
      detail: row.detail,
      attributes: [],
    };
  });
}

export function getMockStations(): Station[] {
  return stationsJson as Station[];
}

function normalizeMockOrder(raw: Record<string, unknown>): Order {
  const id = String(raw.id ?? "");
  return {
    ...(raw as unknown as Order),
    id,
  };
}

export function getMockOrders(status?: OrderStatus): Order[] {
  const orders = (ordersJson as Record<string, unknown>[]).map(normalizeMockOrder);
  if (!status) return orders;
  return orders.filter((order) => order.status === status);
}

export function getMockOrderById(orderId: string): Order | undefined {
  return getMockOrders().find((order) => order.id === orderId);
}
