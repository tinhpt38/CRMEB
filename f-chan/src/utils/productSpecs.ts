import { Product, ProductAttribute, ProductVariant } from "@/types";

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

export function normalizeSkuDimensions(raw: Record<string, any>): ProductAttribute[] {
  const fromProductAttr = Array.isArray(raw?.productAttr) ? raw.productAttr : [];
  return fromProductAttr
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
}

export function normalizeProductVariants(
  raw: Record<string, any>,
  detailPayload: Record<string, any>,
  apiUrl: string
): ProductVariant[] {
  const productValue = raw?.productValue;
  const specType = Number(detailPayload?.spec_type ?? 0) === 1;

  if (!specType) {
    const unique = String(raw?.spec_unique ?? "").trim();
    if (!unique) return [];
    return [
      {
        unique,
        suk: "mặc định",
        label: "Mặc định",
        price: Number(detailPayload?.price ?? 0),
        originalPrice: detailPayload?.ot_price
          ? Number(detailPayload.ot_price)
          : undefined,
        stock: Number(detailPayload?.stock ?? 0),
        image: resolveImageUrl(detailPayload?.image, apiUrl) || undefined,
      },
    ];
  }

  const entries = Array.isArray(productValue)
    ? productValue
    : productValue && typeof productValue === "object"
      ? Object.values(productValue)
      : [];

  return entries
    .map((sku: any) => {
      const unique = String(sku?.unique ?? "").trim();
      const suk = String(sku?.suk ?? "").trim();
      if (!unique || !suk) return null;
      return {
        unique,
        suk,
        label: suk.replace(/,/g, " / "),
        price: Number(sku?.price ?? detailPayload?.price ?? 0),
        originalPrice: sku?.ot_price ? Number(sku.ot_price) : undefined,
        stock: Number(sku?.stock ?? 0),
        image: resolveImageUrl(sku?.image ?? detailPayload?.image, apiUrl) || undefined,
      } as ProductVariant;
    })
    .filter((sku: ProductVariant | null): sku is ProductVariant => sku !== null)
    .filter((sku) => sku.stock > 0 || entries.length === 1);
}

export function buildDefaultSelection(
  dimensions: ProductAttribute[],
  variants: ProductVariant[]
): string[] {
  if (!dimensions.length) return [];
  const preferred =
    variants.find((variant) => variant.stock > 0) ?? variants[0];
  if (!preferred) return dimensions.map((dimension) => dimension.values[0] ?? "");

  const parts = preferred.suk.split(",").map((part) => part.trim());
  return dimensions.map((dimension, index) => parts[index] ?? dimension.values[0] ?? "");
}

export function matchProductVariant(
  variants: ProductVariant[],
  dimensions: ProductAttribute[],
  selectedValues: string[]
): ProductVariant | undefined {
  if (!variants.length) return undefined;
  if (!dimensions.length) return variants[0];

  const expectedSuk = selectedValues.join(",");
  const exact = variants.find((variant) => variant.suk === expectedSuk);
  if (exact) return exact;

  return variants.find((variant) => {
    const parts = variant.suk.split(",").map((part) => part.trim());
    return dimensions.every((_, index) => parts[index] === selectedValues[index]);
  });
}

export function buildCartProductSnapshot(
  product: Product,
  variant?: ProductVariant
): Product {
  if (!variant) return product;

  return {
    ...product,
    price: variant.price,
    originalPrice: variant.originalPrice ?? product.originalPrice,
    image: variant.image || product.image,
    variantLabel: variant.label,
    defaultUnique: variant.unique,
  };
}
