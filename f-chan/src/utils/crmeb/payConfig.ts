import type { CrmebApiClient } from "@/utils/crmeb/client";

export type CrmebPayConfigItem = {
  icon?: string;
  name: string;
  value: string;
  title?: string;
  number?: number | string | null;
  payStatus: boolean;
};

const LEGACY_CHECKOUT_PAYMENT_METHODS: Record<string, string> = {
  cod: "vn_cod",
  bank_transfer: "vn_bank",
  other: "offline",
};

export function normalizeCheckoutPaymentMethod(value: string): string {
  const trimmed = value.trim();
  if (!trimmed) return "vn_cod";
  return LEGACY_CHECKOUT_PAYMENT_METHODS[trimmed] ?? trimmed;
}

export function checkoutPayMark(payType: string): string {
  switch (payType) {
    case "vn_bank":
      return "PAY_METHOD:BANK_TRANSFER";
    case "offline":
      return "PAY_METHOD:OTHER";
    case "vn_cod":
      return "PAY_METHOD:COD";
    default:
      return `PAY_METHOD:${payType.toUpperCase()}`;
  }
}

export async function fetchCrmebPayConfig(
  client: CrmebApiClient
): Promise<CrmebPayConfigItem[]> {
  const data = await client.get<CrmebPayConfigItem[]>("/pay/config");
  if (!Array.isArray(data)) return [];
  return data
    .map((item) => ({
      icon: item?.icon,
      name: String(item?.name ?? "").trim(),
      value: String(item?.value ?? "").trim(),
      title: item?.title ? String(item.title).trim() : undefined,
      number: item?.number ?? null,
      payStatus: !!item?.payStatus,
    }))
    .filter((item) => item.value.length > 0);
}

export function getEnabledCheckoutPayMethods(
  methods: CrmebPayConfigItem[]
): CrmebPayConfigItem[] {
  return methods.filter((item) => item.payStatus);
}

const ORDER_PAY_METHOD_MARKS: Record<string, string> = {
  COD: "vn_cod",
  BANK_TRANSFER: "vn_bank",
  OTHER: "offline",
};

const ORDER_PAY_TYPE_LABELS: Record<string, string> = {
  vn_cod: "Thanh toán khi nhận hàng (COD)",
  vn_bank: "Chuyển khoản ngân hàng / VietQR",
  vnpay: "VNPay",
  momo: "MoMo",
  zalopay: "ZaloPay",
  offline: "Thanh toán ngoại tuyến",
  yue: "Thanh toán số dư",
};

export function resolveOrderPayMeta(raw: Record<string, unknown>): {
  payType?: string;
  payTypeName?: string;
} {
  const mark = String(raw.mark ?? "").trim();
  const payTypeFromDb = String(raw.pay_type ?? "").trim();
  const statusPay =
    raw._status && typeof raw._status === "object"
      ? String((raw._status as Record<string, unknown>)._payType ?? "").trim()
      : "";

  let payType = payTypeFromDb;
  const markMatch = mark.match(/PAY_METHOD:([A-Z_]+)/i);
  if (markMatch) {
    const fromMark = ORDER_PAY_METHOD_MARKS[markMatch[1].toUpperCase()];
    if (fromMark) payType = fromMark;
  } else if (payTypeFromDb === "yue" && Number(raw.vn_cod_pay_status) === 1) {
    payType = "vn_cod";
  } else if (payTypeFromDb === "yue" && Number(raw.vn_bank_pay_status) === 1) {
    payType = "vn_bank";
  } else if (payTypeFromDb === "yue" && Number(raw.offlinePayStatus) === 1) {
    payType = "offline";
  }

  const payTypeName =
    (payType && ORDER_PAY_TYPE_LABELS[payType]) ||
    statusPay ||
    String(raw.pay_type_name ?? "").trim();

  return {
    ...(payType ? { payType } : {}),
    ...(payTypeName ? { payTypeName } : {}),
  };
}
