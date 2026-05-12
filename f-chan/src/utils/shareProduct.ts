import { Product } from "@/types";
import { openShareSheet } from "zmp-sdk/apis";

export function shareProduct(product: Pick<Product, "id" | "name" | "image">) {
  openShareSheet({
    type: "zmp_deep_link",
    data: {
      title: product.name,
      thumbnail: product.image,
      path: `/product/${product.id}`,
    },
  });
}
