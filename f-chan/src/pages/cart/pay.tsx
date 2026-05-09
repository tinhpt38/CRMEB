import { useCheckout } from "@/hooks";
import { useAtom, useAtomValue } from "jotai";
import { cartTotalState, checkoutPaymentMethodState } from "@/state";
import { formatPrice } from "@/utils/format";
import { CrmebApiClient } from "@/utils/crmeb/client";
import { getCrmebToken } from "@/utils/crmeb/token";
import { getConfig } from "@/utils/template";
import { Button } from "zmp-ui";
import { useEffect, useMemo, useState } from "react";
import { CheckoutPaymentMethod } from "@/types";

type MallPayFlags = {
  offline: boolean;
  vnCod: boolean;
  vnBank: boolean;
};

export default function Pay() {
  const { totalAmount } = useAtomValue(cartTotalState);
  const checkout = useCheckout();
  const [paying, setPaying] = useState(false);
  const [paymentMethod, setPaymentMethod] = useAtom(checkoutPaymentMethodState);
  const [payFlags, setPayFlags] = useState<MallPayFlags | null>(null);

  useEffect(() => {
    const apiUrl = getConfig((c) => c.template.apiUrl);
    if (!apiUrl) return;

    const client = new CrmebApiClient({
      apiBaseUrl: apiUrl,
      getToken: () => getCrmebToken(),
    });

    client
      .get<{
        offline_pay_status?: boolean;
        vn_cod_pay_status?: boolean;
        vn_bank_pay_status?: boolean;
      }>("/basic_config")
      .then((data) => {
        setPayFlags({
          offline: !!data?.offline_pay_status,
          vnCod: !!data?.vn_cod_pay_status,
          vnBank: !!data?.vn_bank_pay_status,
        });
      })
      .catch(() => {
        setPayFlags({ offline: true, vnCod: true, vnBank: true });
      });
  }, []);

  const methods = useMemo(() => {
    const list: Array<{
      value: CheckoutPaymentMethod;
      label: string;
      hint?: string;
      enabled: boolean;
    }> = [
      {
        value: "cod",
        label: "COD",
        hint: "Khi nhận hàng",
        enabled: payFlags === null ? true : payFlags.vnCod,
      },
      {
        value: "bank_transfer",
        label: "CK / VietQR",
        hint: "Chuyển khoản",
        enabled: payFlags === null ? true : payFlags.vnBank,
      },
      {
        value: "other",
        label: "Khác",
        hint: "Ngoại tuyến",
        enabled: payFlags === null ? true : payFlags.offline,
      },
    ];
    return list;
  }, [payFlags]);

  useEffect(() => {
    const current = methods.find((m) => m.value === paymentMethod);
    if (current && !current.enabled) {
      const first = methods.find((m) => m.enabled);
      if (first) setPaymentMethod(first.value);
    }
  }, [methods, paymentMethod, setPaymentMethod]);

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
            setPaying(true);
            await checkout();
            setPaying(false);
          }}
          disabled={paying}
        >
          Thanh toán
        </Button>
      </div>
      <p className="text-[11px] text-subtitle leading-snug">
        COD: thanh toán khi nhận hàng. CK/VietQR: chuyển khoản và chờ shop đối soát.
        Ngoại tuyến: cửa hàng xác nhận thủ công.
      </p>
      <div className="space-y-1">
        <div className="text-xs text-subtitle">Phương thức thanh toán</div>
        <div className="grid grid-cols-3 gap-2">
          {methods.map((method) => {
            const active = paymentMethod === method.value;
            const disabled = paying || !method.enabled;
            return (
              <button
                key={method.value}
                type="button"
                className={`py-2 rounded-lg text-xs border transition-colors ${
                  active
                    ? "border-primary text-primary bg-blue-50"
                    : "border-gray-200 text-subtitle bg-white"
                } ${!method.enabled ? "opacity-40" : ""}`}
                onClick={() => setPaymentMethod(method.value)}
                disabled={disabled}
              >
                <div className="font-medium">{method.label}</div>
                {method.hint ? (
                  <div className="text-[10px] text-subtitle mt-0.5">{method.hint}</div>
                ) : null}
              </button>
            );
          })}
        </div>
      </div>
    </div>
  );
}
