import Carousel from "@/components/carousel";
import { useAtomValue } from "jotai";
import { bannersState } from "@/state";
import { useNavigate } from "react-router-dom";
import { openWebview } from "zmp-sdk/apis";
import { Banner } from "@/types";

/**
 * Resolve CRMEB banner link → điều hướng trong app hoặc mở webview.
 *
 * CRMEB trả về path dạng WeChat mini-app như `/pages/goods/details/id=42`
 * hoặc URL web đầy đủ. Hàm này cố gắng map sang router nội bộ trước,
 * fallback về openWebview nếu là URL tuyệt đối.
 */
function useBannerNavigator() {
  const navigate = useNavigate();

  return (banner: Banner) => {
    const link = banner.link ?? "";
    if (!link) return;

    // CRMEB product detail path: /pages/goods/details/id=42 hoặc /pages/goods/details?id=42
    const productMatch =
      link.match(/\/pages\/goods\/details[?/]id[=:]?(\d+)/) ??
      link.match(/\/product\/(\d+)/);
    if (productMatch) {
      navigate(`/product/${productMatch[1]}`, { viewTransition: true });
      return;
    }

    // CRMEB category path: /pages/goods/classify?id=5 hoặc /category/5
    const categoryMatch =
      link.match(/\/pages\/goods\/classify[?/]id[=:]?(\d+)/) ??
      link.match(/\/category\/(\d+)/);
    if (categoryMatch) {
      navigate(`/category/${categoryMatch[1]}`, { viewTransition: true });
      return;
    }

    // URL tuyệt đối → mở webview Zalo
    if (link.startsWith("http://") || link.startsWith("https://")) {
      openWebview({ url: link }).catch(console.warn);
      return;
    }

    // Các path nội bộ khác (ví dụ /search)
    if (link.startsWith("/")) {
      navigate(link, { viewTransition: true });
    }
  };
}

export default function Banners() {
  const banners = useAtomValue(bannersState);
  const handleBannerClick = useBannerNavigator();

  return (
    <Carousel
      slides={banners.map((banner, i) => (
        <div
          key={i}
          className={banner.link ? "cursor-pointer" : undefined}
          onClick={() => handleBannerClick(banner)}
        >
          <img
            className="w-full rounded"
            src={banner.pic}
            alt={`Banner ${i + 1}`}
          />
        </div>
      ))}
    />
  );
}
