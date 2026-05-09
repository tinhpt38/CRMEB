import { HomeIcon, LocationMarkerLineIcon } from "@/components/vectors";
import { Order } from "@/types";
import { Icon, List } from "zmp-ui";
import DeliverySummary from "../cart/delivery-summary";
import { useEffect, useState } from "react";

function formatRemaining(ms: number): string {
  if (ms <= 0) return "Đã hết hạn";
  const totalSec = Math.floor(ms / 1000);
  const h = Math.floor(totalSec / 3600);
  const m = Math.floor((totalSec % 3600) / 60);
  const s = totalSec % 60;
  if (h > 0) return `${h} giờ ${m} phút`;
  if (m > 0) return `${m} phút ${s} giây`;
  return `${s} giây`;
}

function PaymentCountdown(props: { stopTimeSec: number }) {
  const [, setTick] = useState(0);
  useEffect(() => {
    const id = window.setInterval(() => setTick((t) => t + 1), 1000);
    return () => window.clearInterval(id);
  }, []);

  const left = Math.max(0, props.stopTimeSec * 1000 - Date.now());

  return (
    <div className="text-xs text-danger font-medium px-4 py-2 bg-red-50 rounded-lg mx-4 mb-2">
      Hoàn tất thanh toán trong: {formatRemaining(left)}
    </div>
  );
}

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

  const showPayDeadline =
    props.order.stopTime &&
    (props.order.crmebStatusType === 0 || props.order.crmebStatusType === 9);

  return (
    <>
      {props.order.statusTitle ? (
        <div className="bg-section rounded-lg mx-0 mb-2 px-4 py-3 space-y-1">
          <div className="text-sm font-semibold text-primary">
            {props.order.statusTitle}
          </div>
          {props.order.statusMessage ? (
            <div className="text-xs text-subtitle whitespace-pre-wrap break-words">
              {props.order.statusMessage}
            </div>
          ) : null}
        </div>
      ) : null}
      {showPayDeadline && props.order.stopTime ? (
        <PaymentCountdown stopTimeSec={props.order.stopTime} />
      ) : null}
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
        {props.order.payType === "vn_bank" ? (
          <List.Item prefix={<Icon icon="zi-note" />} title="Mã đơn (nội dung CK)">
            <span className="text-xs font-mono text-primary">{props.order.id}</span>
          </List.Item>
        ) : null}
        {props.order.payType === "vn_bank" && props.order.bankPayGuide ? (
          <List.Item prefix={<Icon icon="zi-note" />} title="Hướng dẫn chuyển khoản">
            <span className="text-xs text-inactive whitespace-pre-wrap break-words">
              {props.order.bankPayGuide}
            </span>
          </List.Item>
        ) : null}
        {props.order.payType === "vn_bank" && props.order.bankPayQrUrl ? (
          <List.Item prefix={<Icon icon="zi-photo" />} title="QR chuyển khoản">
            <img
              src={props.order.bankPayQrUrl}
              alt="QR VietQR"
              className="max-w-[220px] rounded-lg border border-gray-100 mt-1"
            />
          </List.Item>
        ) : null}
        {props.order.note && (
          <List.Item prefix={<Icon icon="zi-note" />} title="Ghi chú">
            <span className="text-xs text-inactive">{props.order.note}</span>
          </List.Item>
        )}
      </List>
    </>
  );
}

export default OrderInfo;
