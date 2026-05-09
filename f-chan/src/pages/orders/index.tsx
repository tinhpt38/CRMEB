import { Tabs } from "zmp-ui";
import OrderList from "./order-list";
import { ordersState } from "@/state";
import { useSetAtom } from "jotai";
import { useEffect } from "react";
import { useLocation, useNavigate, useParams } from "react-router-dom";

function OrdersPage() {
  const { status } = useParams();
  const navigate = useNavigate();
  const location = useLocation();

  const refreshPending = useSetAtom(ordersState("pending"));
  const refreshShipping = useSetAtom(ordersState("shipping"));
  const refreshCompleted = useSetAtom(ordersState("completed"));

  // Luôn tải lại danh sách khi vào /orders hoặc đổi tab — trạng thái đơn khớp CRMEB (admin/CK/COD/…).
  useEffect(() => {
    if (!location.pathname.startsWith("/orders")) return;
    refreshPending();
    refreshShipping();
    refreshCompleted();
  }, [
    location.pathname,
    refreshPending,
    refreshShipping,
    refreshCompleted,
  ]);

  return (
    <Tabs
      className="h-full flex flex-col"
      activeKey={status}
      onChange={(status) => navigate(`/orders/${status}`)}
    >
      <Tabs.Tab key="pending" label="Đang xử lý">
        <OrderList ordersState={ordersState("pending")} />
      </Tabs.Tab>
      <Tabs.Tab key="shipping" label="Đang giao">
        <OrderList ordersState={ordersState("shipping")} />
      </Tabs.Tab>
      <Tabs.Tab key="completed" label="Lịch sử">
        <OrderList ordersState={ordersState("completed")} />
      </Tabs.Tab>
    </Tabs>
  );
}

export default OrdersPage;
