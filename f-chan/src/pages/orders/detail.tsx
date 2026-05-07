import { loadable } from "jotai/utils";
import { useAtomValue } from "jotai";
import { useParams } from "react-router-dom";
import { orderDetailState } from "@/state";
import OrderSummary from "./order-summary";
import OrderInfo from "./order-info";
import { OrderSummarySkeleton } from "@/components/skeleton";

function OrderDetailPage() {
  const { id } = useParams();
  const orderId = String(id ?? "");

  const orderLoadable = useAtomValue(loadable(orderDetailState(orderId)));

  if (orderLoadable.state !== "hasData") {
    return (
      <div className="w-full p-4 space-y-2">
        <OrderSummarySkeleton />
      </div>
    );
  }

  const order = orderLoadable.data;
  if (!order) return null;

  return (
    <div className="w-full p-4 space-y-2">
      <OrderInfo order={order} />
      <OrderSummary full order={order} />
    </div>
  );
}

export default OrderDetailPage;
