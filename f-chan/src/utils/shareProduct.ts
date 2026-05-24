import { Product } from "@/types";
import { getConfig } from "@/utils/template";
import { getBasePath } from "@/utils/zma";
import { getAppInfo, openShareSheet } from "zmp-sdk/apis";
import toast from "react-hot-toast";

function toAbsoluteUrl(url: string) {
  const value = url.trim();
  if (!value) return value;
  if (/^https?:\/\//i.test(value)) return value;

  const apiBase = getConfig((config) => config.template.apiUrl);
  if (!apiBase) return value;

  try {
    return new URL(value.replace(/^\/+/, ""), apiBase).toString();
  } catch {
    return value;
  }
}

function buildSharePath(productId: number) {
  const basePath = getBasePath().replace(/\/$/, "");
  return `${basePath}/product/${productId}`.replace(/\/{2,}/g, "/");
}

export async function shareProduct(
  product: Pick<Product, "id" | "name" | "image">,
) {
  const thumbnail = toAbsoluteUrl(product.image);
  if (!thumbnail) {
    toast.error("Sản phẩm chưa có ảnh để chia sẻ.");
    return;
  }

  try {
    await getAppInfo({});
  } catch {
    // Một số môi trường dev không trả app info; vẫn thử mở share sheet.
  }

  try {
    await openShareSheet({
      type: "zmp",
      data: {
        title: product.name,
        description: "Chi tiết sản phẩm trên Mini App",
        thumbnail,
        path: buildSharePath(product.id),
      },
    });
  } catch (error) {
    console.warn("shareProduct failed:", error);
    toast.error(
      "Không thể chia sẻ. Hãy mở Mini App từ Zalo (bản deploy) và cấu hình thông tin app trên Zalo Developers.",
    );
  }
}
