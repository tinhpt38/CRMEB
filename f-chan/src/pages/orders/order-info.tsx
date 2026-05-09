import { HomeIcon, LocationMarkerLineIcon } from "@/components/vectors";
import { Order } from "@/types";
import { Icon, List } from "zmp-ui";
import DeliverySummary from "../cart/delivery-summary";

function OrderInfo(props: { order: Order }) {
  const payLabel =
    props.order.payTypeName ||
    (props.order.payType === "vn_cod"
      ? "Thanh toán khi nhận hàng (COD)"
      : props.order.payType === "vn_bank"
        ? "Chuyển khoản ngân hàng / VietQR"
        : props.order.payType === "offline"
          ? "Thanh toán ngoại tuyến"
          : props.order.payType || "");

  return (
    <List noSpacing className="bg-section rounded-lg">
      {props.order.delivery.type === "pickup" ? (
        <DeliverySummary
          icon={<HomeIcon />}
          title="Giao đến"
          subtitle={props.order.delivery.name}
          description={props.order.delivery.address}
        />
      ) : (
        <DeliverySummary
          icon={<LocationMarkerLineIcon />}
          title="Giao đến"
          subtitle={props.order.delivery.alias}
          description={props.order.delivery.address}
        />
      )}
      {payLabel ? (
        <List.Item prefix={<Icon icon="zi-check" />} title="Phương thức thanh toán">
          <span className="text-xs text-inactive">{payLabel}</span>
        </List.Item>
      ) : null}
      {props.order.payType === "vn_bank" && props.order.bankPayGuide ? (
        <List.Item prefix={<Icon icon="zi-note" />} title="Hướng dẫn chuyển khoản">
          <span className="text-xs text-inactive whitespace-pre-wrap break-words">
            {props.order.bankPayGuide}
          </span>
        </List.Item>
      ) : null}
      {props.order.note && (
        <List.Item prefix={<Icon icon="zi-note" />} title="Ghi chú">
          <span className="text-xs text-inactive">{props.order.note}</span>
        </List.Item>
      )}
    </List>
  );
}

export default OrderInfo;
