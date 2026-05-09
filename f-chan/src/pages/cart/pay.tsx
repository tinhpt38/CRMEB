import { useCheckout } from "@/hooks";
import { useAtom, useAtomValue } from "jotai";
import { cartTotalState, checkoutPaymentMethodState } from "@/state";
import { formatPrice } from "@/utils/format";
import { Button } from "zmp-ui";
import { useState } from "react";
import { CheckoutPaymentMethod } from "@/types";

export default function Pay() {
  const { totalAmount } = useAtomValue(cartTotalState);
  const checkout = useCheckout();
  const [paying, setPaying] = useState(false);
  const [paymentMethod, setPaymentMethod] = useAtom(checkoutPaymentMethodState);

  const methods: Array<{ value: CheckoutPaymentMethod; label: string }> = [
    { value: "cod", label: "COD" },
    { value: "bank_transfer", label: "Chuyển khoản" },
    { value: "other", label: "Khác" },
  ];

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
      <div className="space-y-1">
        <div className="text-xs text-subtitle">Phương thức thanh toán</div>
        <div className="grid grid-cols-3 gap-2">
          {methods.map((method) => {
            const active = paymentMethod === method.value;
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
                disabled={paying}
              >
                {method.label}
              </button>
            );
          })}
        </div>
      </div>
    </div>
  );
}
