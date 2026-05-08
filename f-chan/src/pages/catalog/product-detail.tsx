import HorizontalDivider from "@/components/horizontal-divider";
import { Suspense } from "react";
import { useAtomValue } from "jotai";
import { useNavigate, useParams } from "react-router-dom";
import { productDetailState } from "@/state";
import { formatPrice } from "@/utils/format";
import ShareButton from "./share-buttont";
import RelatedProducts from "./related-products";
import { useAddToCart } from "@/hooks";
import { Button } from "zmp-ui";
import Section from "@/components/section";
import { ProductItemSkeleton } from "@/components/skeleton";
import Carousel from "@/components/carousel";

function ProductDetailSkeleton() {
  return (
    <div className="w-full p-4 space-y-4 bg-section">
      <div className="w-full aspect-square rounded-lg bg-skeleton animate-pulse" />
      <div className="space-y-2">
        <div className="h-6 w-24 bg-skeleton animate-pulse rounded-lg" />
        <div className="h-4 w-full bg-skeleton animate-pulse rounded-lg" />
        <div className="h-4 w-3/4 bg-skeleton animate-pulse rounded-lg" />
      </div>
    </div>
  );
}

function ProductImages({
  image,
  images,
  name,
  id,
}: {
  image: string;
  images?: string[];
  name: string;
  id: number;
}) {
  const gallery =
    images && images.length > 0
      ? images
      : [image].filter(Boolean);

  if (gallery.length <= 1) {
    return (
      <img
        key={id}
        src={gallery[0] ?? image}
        alt={name}
        className="w-full aspect-square object-cover rounded-lg bg-skeleton"
        style={{ viewTransitionName: `product-image-${id}` }}
      />
    );
  }

  return (
    <Carousel
      slides={gallery.map((src, i) => (
        <img
          key={i}
          src={src}
          alt={`${name} ${i + 1}`}
          className="w-full aspect-square object-cover rounded-lg bg-skeleton"
          style={i === 0 ? { viewTransitionName: `product-image-${id}` } : {}}
        />
      ))}
    />
  );
}

function ProductDetailContent() {
  const { id } = useParams();
  const product = useAtomValue(productDetailState(Number(id)))!;

  const navigate = useNavigate();
  const { addToCart } = useAddToCart(product);

  if (!product) return null;

  const hasAttributes = Array.isArray(product.attributes) && product.attributes.length > 0;
  const detailText = (product.detail ?? "").trim();
  const hasHtmlDetail = detailText.includes("<");

  return (
    <div className="w-full h-full flex flex-col">
      <div className="flex-1 overflow-y-auto">
        <div className="w-full p-4 pb-2 space-y-4 bg-section">
          <ProductImages
            id={product.id}
            image={product.image}
            images={product.images}
            name={product.name}
          />
          <div>
            <div className="text-xl font-bold text-primary">
              {formatPrice(product.price)}
            </div>
            {product.originalPrice && (
              <div className="text-2xs space-x-0.5">
                <span className="text-subtitle line-through">
                  {formatPrice(product.originalPrice)}
                </span>
                <span className="text-danger">
                  -
                  {100 -
                    Math.round((product.price * 100) / product.originalPrice)}
                  %
                </span>
              </div>
            )}
            <div className="text-sm mt-1">{product.name}</div>
          </div>
          <ShareButton product={product} />
        </div>
        <>
          <div className="bg-background h-2 w-full" />
          <Section title="Mô tả sản phẩm">
            {detailText ? (
              hasHtmlDetail ? (
                <div
                  className="text-sm text-subtitle p-4 pt-2 prose prose-sm max-w-none"
                  dangerouslySetInnerHTML={{ __html: detailText }}
                />
              ) : (
                <div className="text-sm whitespace-pre-wrap text-subtitle p-4 pt-2">
                  {detailText}
                </div>
              )
            ) : (
              <div className="text-sm text-subtitle p-4 pt-2">
                Đang cập nhật mô tả sản phẩm.
              </div>
            )}
          </Section>
        </>
        {hasAttributes && (
          <>
            <div className="bg-background h-2 w-full" />
            <Section title="Thuộc tính sản phẩm">
              <div className="px-4 py-2 space-y-2">
                {product.attributes?.map((attribute) => (
                  <div
                    key={attribute.name}
                    className="flex items-start gap-2 text-sm"
                  >
                    <div className="text-subtitle min-w-24">{attribute.name}:</div>
                    <div className="text-body">{attribute.values.join(", ")}</div>
                  </div>
                ))}
              </div>
            </Section>
          </>
        )}
        <div className="bg-background h-2 w-full" />
        <Section title="Sản phẩm khác">
          <RelatedProducts currentProductId={product.id} />
        </Section>
      </div>

      <HorizontalDivider />
      <div className="flex-none grid grid-cols-2 gap-2 py-3 px-4 bg-section">
        <Button
          variant="tertiary"
          onClick={() => {
            addToCart(1, { toast: true });
          }}
        >
          Thêm vào giỏ
        </Button>
        <Button
          onClick={() => {
            addToCart(1);
            navigate("/cart", { viewTransition: true });
          }}
        >
          Mua ngay
        </Button>
      </div>
    </div>
  );
}

export default function ProductDetailPage() {
  return (
    <Suspense
      fallback={
        <div className="w-full p-4 space-y-4">
          <ProductDetailSkeleton />
          <div className="grid grid-cols-2 gap-4 pt-2">
            <ProductItemSkeleton />
            <ProductItemSkeleton />
          </div>
        </div>
      }
    >
      <ProductDetailContent />
    </Suspense>
  );
}
