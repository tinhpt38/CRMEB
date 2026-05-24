import Banners from "./banners";
import Category from "./category";
import ProductGrid from "@/components/product-grid";
import Section from "@/components/section";
import { useAtomValue } from "jotai";
import {
  bestSellerProductsState,
  featuredProductsState,
  newProductsState,
  productsState,
  proposedProductsState,
} from "@/state";

const HomePage: React.FunctionComponent = () => {
  const bestSellerProducts = useAtomValue(bestSellerProductsState);
  const proposedProducts = useAtomValue(proposedProductsState);
  const newestProducts = useAtomValue(newProductsState);
  const featuredProducts = useAtomValue(featuredProductsState);
  const allProducts = useAtomValue(productsState);

  return (
    <div className="min-h-full space-y-2 py-2">
      <Category />
      <div className="bg-section">
        <Banners />
      </div>
      {bestSellerProducts.length > 0 ? (
        <Section title="Mặt hàng bán chạy">
          <ProductGrid products={bestSellerProducts} />
        </Section>
      ) : null}
      {proposedProducts.length > 0 ? (
        <Section title="Sản phẩm được đề xuất">
          <ProductGrid products={proposedProducts} />
        </Section>
      ) : null}
      {newestProducts.length > 0 ? (
        <Section title="Sản phẩm mới">
          <ProductGrid products={newestProducts} />
        </Section>
      ) : null}
      {featuredProducts.length > 0 ? (
        <Section title="Sản phẩm nổi bật">
          <ProductGrid products={featuredProducts} />
        </Section>
      ) : null}
      <Section title="Tất cả sản phẩm">
        <ProductGrid products={allProducts} />
      </Section>
    </div>
  );
};

export default HomePage;
