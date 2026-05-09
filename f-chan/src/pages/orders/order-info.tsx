import { HomeIcon, LocationMarkerLineIcon } from "@/components/vectors";
import { Order } from "@/types";
import { CrmebApiClient } from "@/utils/crmeb/client";
import { getCrmebToken } from "@/utils/crmeb/token";
import { getConfig } from "@/utils/template";
import { Icon, List, Sheet, Button, Input } from "zmp-ui";
import DeliverySummary from "../cart/delivery-summary";
import { useEffect, useState } from "react";
import toast from "react-hot-toast";
import { useSetAtom } from "jotai";
import { ordersState } from "@/state";
import { useNavigate } from "react-router-dom";

// ---------------------------------------------------------------------------
// Countdown thanh toán
// ---------------------------------------------------------------------------

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

// ---------------------------------------------------------------------------
// Sheet yêu cầu hoàn tiền
// ---------------------------------------------------------------------------

function RefundSheet(props: { order: Order; onClose: () => void; onSuccess: () => void }) {
  const [reasons, setReasons] = useState<string[]>([]);
  const [selected, setSelected] = useState("");
  const [loading, setLoading] = useState(false);
  const [submitting, setSubmitting] = useState(false);

  useEffect(() => {
    const apiUrl = getConfig((c) => c.template.apiUrl);
    const token = getCrmebToken();
    if (!apiUrl || !token) return;
    setLoading(true);
    const client = new CrmebApiClient({ apiBaseUrl: apiUrl, getToken: () => token });
    client
      .get<any>("/order/refund/reason")
      .then((res) => {
        const list: string[] = Array.isArray(res)
          ? res
          : Array.isArray(res?.data)
            ? res.data
            : [];
        setReasons(list);
        if (list.length) setSelected(list[0]);
      })
      .catch(() => {})
      .finally(() => setLoading(false));
  }, []);

  const handleSubmit = async () => {
    if (!selected.trim()) {
      toast.error("Vui lòng chọn lý do hoàn tiền");
      return;
    }
    const apiUrl = getConfig((c) => c.template.apiUrl);
    const token = getCrmebToken();
    if (!apiUrl || !token) {
      toast.error("Chưa đăng nhập");
      return;
    }
    if (!props.order.dbId) {
      toast.error("Không tìm thấy ID đơn hàng");
      return;
    }
    setSubmitting(true);
    try {
      const client = new CrmebApiClient({ apiBaseUrl: apiUrl, getToken: () => token });
      await client.post(`/order/refund/apply/${props.order.dbId}`, {
        text: selected,
        refund_reason_wap_explain: selected,
        refund_reason_wap_img: "",
        refund_type: 1,
        refund_price: props.order.total,
        cart_ids: [],
      });
      toast.success("Đã gửi yêu cầu hoàn tiền. Shop sẽ xử lý sớm.");
      props.onSuccess();
    } catch (err: any) {
      toast.error(err?.crmebError?.msg ?? err?.message ?? "Không thể gửi yêu cầu");
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <Sheet
      visible
      onClose={props.onClose}
      title="Yêu cầu hoàn tiền"
      maskClosable
    >
      <div className="px-4 pb-6 flex flex-col gap-4">
        {loading ? (
          <p className="text-sm text-subtitle py-4 text-center">Đang tải lý do…</p>
        ) : (
          <>
            <p className="text-sm text-subtitle">Chọn lý do hoàn tiền:</p>
            <div className="flex flex-col gap-2">
              {reasons.map((r) => (
                <button
                  key={r}
                  type="button"
                  onClick={() => setSelected(r)}
                  className={`text-left px-4 py-2.5 rounded-lg border text-sm transition-colors ${
                    selected === r
                      ? "border-primary text-primary bg-primary/5"
                      : "border-gray-200 text-title"
                  }`}
                >
                  {selected === r && (
                    <Icon icon="zi-check" size={14} className="mr-1.5 inline text-primary" />
                  )}
                  {r}
                </button>
              ))}
              {!reasons.length && (
                <Input
                  label="Lý do hoàn tiền"
                  placeholder="Nhập lý do..."
                  value={selected}
                  onChange={(e) => setSelected(e.target.value)}
                />
              )}
            </div>
            <Button fullWidth loading={submitting} onClick={handleSubmit}>
              Gửi yêu cầu
            </Button>
          </>
        )}
      </div>
    </Sheet>
  );
}

// ---------------------------------------------------------------------------
// Sheet đánh giá nhanh 5 sao
// ---------------------------------------------------------------------------

function ReviewSheet(props: { order: Order; onClose: () => void; onSuccess: () => void }) {
  const [comment, setComment] = useState("");
  const [submitting, setSubmitting] = useState(false);

  const handleSubmit = async () => {
    const apiUrl = getConfig((c) => c.template.apiUrl);
    const token = getCrmebToken();
    if (!apiUrl || !token) {
      toast.error("Chưa đăng nhập");
      return;
    }

    const uniqueList = props.order.items
      .map((item) => item.unique)
      .filter(Boolean) as string[];

    if (!uniqueList.length) {
      toast.error("Không tìm thấy thông tin sản phẩm để đánh giá");
      return;
    }

    setSubmitting(true);
    try {
      const client = new CrmebApiClient({ apiBaseUrl: apiUrl, getToken: () => token });
      await Promise.all(
        uniqueList.map((unique) =>
          client.post("/order/comment", {
            unique,
            comment: comment.trim() || "Sản phẩm tốt, shop giao hàng nhanh!",
            pics: "",
            product_score: 5,
            service_score: 5,
          })
        )
      );
      toast.success("Cảm ơn bạn đã đánh giá!");
      props.onSuccess();
    } catch (err: any) {
      toast.error(err?.crmebError?.msg ?? err?.message ?? "Không thể gửi đánh giá");
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <Sheet visible onClose={props.onClose} title="Đánh giá đơn hàng" maskClosable>
      <div className="px-4 pb-6 flex flex-col gap-4">
        <div className="flex justify-center gap-2 py-2">
          {[1, 2, 3, 4, 5].map((star) => (
            <span key={star} className="text-3xl text-yellow-400">★</span>
          ))}
        </div>
        <p className="text-xs text-subtitle text-center">
          Cảm ơn bạn đã mua hàng! Đánh giá của bạn giúp shop cải thiện chất lượng.
        </p>
        <Input
          type="textarea"
          label="Nhận xét (không bắt buộc)"
          placeholder="Sản phẩm tốt, giao hàng nhanh..."
          value={comment}
          onChange={(e) => setComment(e.target.value)}
        />
        <Button fullWidth loading={submitting} onClick={handleSubmit}>
          Gửi đánh giá 5 ★
        </Button>
      </div>
    </Sheet>
  );
}

// ---------------------------------------------------------------------------
// OrderInfo chính
// ---------------------------------------------------------------------------

function OrderInfo(props: { order: Order }) {
  const [showRefund, setShowRefund] = useState(false);
  const [showReview, setShowReview] = useState(false);

  const refreshCompleted = useSetAtom(ordersState("completed"));
  const refreshShipping = useSetAtom(ordersState("shipping"));
  const navigate = useNavigate();

  const handleActionSuccess = () => {
    setShowRefund(false);
    setShowReview(false);
    refreshCompleted();
    refreshShipping();
    navigate(-1);
  };

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

  // Nút Đánh giá: chỉ khi _type = 3 (Đã nhận — chờ đánh giá)
  const canReview = props.order.crmebStatusType === 3;
  // Nút Hoàn tiền: khi CRMEB cho phép (_is_back) và đơn chưa/đang hoàn (không phải -1/-2)
  const canRefund =
    props.order.isBack &&
    props.order.crmebStatusType !== -1 &&
    props.order.crmebStatusType !== -2;

  const hasActions = canReview || canRefund;

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

        {/* Mã vận đơn + nhà vận chuyển — chỉ khi đang giao / có mã */}
        {props.order.deliveryName ? (
          <List.Item prefix={<Icon icon="zi-car" />} title="Nhà vận chuyển">
            <span className="text-xs text-inactive">{props.order.deliveryName}</span>
          </List.Item>
        ) : null}
        {props.order.deliveryId ? (
          <List.Item prefix={<Icon icon="zi-note" />} title="Mã vận đơn">
            <span className="text-xs font-mono text-primary">{props.order.deliveryId}</span>
          </List.Item>
        ) : null}

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

      {/* Nút hành động cho đơn đã giao (Lịch sử) */}
      {hasActions && (
        <div className="flex gap-3 mt-2">
          {canReview && (
            <Button
              className="flex-1"
              type="neutral"
              onClick={() => setShowReview(true)}
            >
              ★ Đánh giá
            </Button>
          )}
          {canRefund && (
            <Button
              className="flex-1"
              type="neutral"
              onClick={() => setShowRefund(true)}
            >
              Yêu cầu hoàn tiền
            </Button>
          )}
        </div>
      )}

      {showRefund && (
        <RefundSheet
          order={props.order}
          onClose={() => setShowRefund(false)}
          onSuccess={handleActionSuccess}
        />
      )}
      {showReview && (
        <ReviewSheet
          order={props.order}
          onClose={() => setShowReview(false)}
          onSuccess={handleActionSuccess}
        />
      )}
    </>
  );
}

export default OrderInfo;
