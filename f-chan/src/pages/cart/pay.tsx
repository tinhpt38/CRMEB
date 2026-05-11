import { useCheckout } from "@/hooks";
import { useAtom, useAtomValue } from "jotai";
import { cartTotalState, checkoutPaymentMethodState } from "@/state";
import { formatPrice } from "@/utils/format";
import { CrmebApiClient } from "@/utils/crmeb/client";
import {
  CrmebPayConfigItem,
  fetchCrmebPayConfig,
  getEnabledCheckoutPayMethods,
  normalizeCheckoutPaymentMethod,
} from "@/utils/crmeb/payConfig";
import { getCrmebToken } from "@/utils/crmeb/token";
import { getConfig } from "@/utils/template";
import { Button } from "zmp-ui";
import { useEffect, useMemo, useState } from "react";

export default function Pay() {
  const { totalAmount } = useAtomValue(cartTotalState);
  const checkout = useCheckout();
  const [paying, setPaying] = useState(false);
  const [paymentMethod, setPaymentMethod] = useAtom(checkoutPaymentMethodState);
  const [payMethods, setPayMethods] = useState<CrmebPayConfigItem[] | null>(null);

  useEffect(() => {
    const apiUrl = getConfig((c) => c.template.apiUrl);
    if (!apiUrl) return;

    const client = new CrmebApiClient({
      apiBaseUrl: apiUrl,
      getToken: () => getCrmebToken(),
    });

    fetchCrmebPayConfig(client)
      .then((methods) => setPayMethods(getEnabledCheckoutPayMethods(methods)))
      .catch(() => setPayMethods([]));
  }, []);

  const methods = useMemo(() => payMethods ?? [], [payMethods]);

  useEffect(() => {
    if (!methods.length) return;

    const normalized = normalizeCheckoutPaymentMethod(paymentMethod);
    const current = methods.find((method) => method.value === normalized);
    if (current) {
      if (normalized !== paymentMethod) setPaymentMethod(normalized);
      return;
    }

    setPaymentMethod(methods[0].value);
  }, [methods, paymentMethod, setPaymentMethod]);

  const selectedMethod = methods.find(
    (method) => method.value === normalizeCheckoutPaymentMethod(paymentMethod)
  );

  return (
    <div className="flex-none py-3 px-4 bg-section space-y-3">
      <div className="flex items-center space-x-2">
        <div className="space-y-1 flex-1">
          <div className="text-xs text-subtitle">Tổng thanh toán</div>
          <div className="text-sm font-medium text-primary">
            {formatPrice(totalAmount)}
          </div>
        </div>
        <Button
          onClick={async () => {
            if (!methods.length) return;
            setPaying(true);
            await checkout();
            setPaying(false);
          }}
          disabled={paying || !methods.length}
        >
          Thanh toán
        </Button>
      </div>
      {selectedMethod?.title ? (
        <p className="text-[11px] text-subtitle leading-snug">{selectedMethod.title}</p>
      ) : null}
      <div className="space-y-1">
        <div className="text-xs text-subtitle">Phương thức thanh toán</div>
        {methods.length ? (
          <div className="grid grid-cols-2 gap-2">
            {methods.map((method) => {
              const active =
                normalizeCheckoutPaymentMethod(paymentMethod) === method.value;
              const disabled = paying;
              return (
                <button
                  key={method.value}
                  type="button"
                  className={`py-2 rounded-lg text-xs border transition-colors ${
                    active
                      ? "border-primary text-primary bg-blue-50"
                      : "border-gray-200 text-subtitle bg-white"
                  }`}
                  onClick={() => setPaymentMethod(method.value)}
                  disabled={disabled}
                >
                  <div className="font-medium">{method.name}</div>
                  {method.title ? (
                    <div className="text-[10px] text-subtitle mt-0.5 line-clamp-2">
                      {method.title}
                    </div>
                  ) : null}
                </button>
              );
            })}
          </div>
        ) : payMethods === null ? (
          <p className="text-[11px] text-subtitle">Đang tải phương thức thanh toán...</p>
        ) : (
          <p className="text-[11px] text-subtitle">
            Chưa có phương thức thanh toán khả dụng. Vui lòng kiểm tra cấu hình cửa hàng trên CRMEB.
          </p>
        )}
      </div>
    </div>
  );
}
