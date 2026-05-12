import { ShareDecor } from "@/components/vectors";
import { Product } from "@/types";
import { shareProduct } from "@/utils/shareProduct";
import { Icon } from "zmp-ui";

export default function ShareButton(props: { product: Product }) {
  return (
    <button
      type="button"
      className="relative w-full h-10 rounded-lg cursor-pointer overflow-hidden"
      onClick={() => shareProduct(props.product)}
    >
      <div className="absolute inset-0 bg-[var(--zaui-light-button-secondary-background)] opacity-50" />
      <ShareDecor className="absolute inset-0" />
      <div className="relative flex space-x-1 text-primary text-sm font-medium p-2">
        <div>Chia sẻ cho bạn bè</div>
        <Icon icon="zi-chevron-right" />
      </div>
    </button>
  );
}
