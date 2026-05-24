import type { CrmebAddress, UserInfo } from "@/types";

export function formatCrmebAddressLine(addr: CrmebAddress): string {
  return [addr.province, addr.city, addr.district, addr.detail]
    .map((s) => String(s ?? "").trim())
    .filter(Boolean)
    .join(", ");
}

function parseCrmebAddressRow(a: unknown): CrmebAddress | null {
  if (!a || typeof a !== "object") return null;
  const x = a as Record<string, unknown>;
  return {
    id: Number(x.id ?? 0),
    real_name: String(x.real_name ?? ""),
    phone: String(x.phone ?? ""),
    province: String(x.province ?? ""),
    city: String(x.city ?? ""),
    district: String(x.district ?? ""),
    detail: String(x.detail ?? ""),
    is_default: Number(x.is_default ?? 0),
  };
}

export function normalizeCrmebAddressListPayload(raw: unknown): unknown[] {
  if (Array.isArray(raw)) return raw;
  if (raw && typeof raw === "object" && Array.isArray((raw as { list?: unknown }).list)) {
    return (raw as { list: unknown[] }).list;
  }
  return [];
}

/**
 * Gộp GET /userinfo và địa chỉ giao hàng CRMEB vào UserInfo hiển thị trong mini app.
 */export function enrichUserInfoFromCrmebRecords(
  base: UserInfo,
  profile: Record<string, unknown> | undefined | null,
  addressRowsRaw: unknown
): UserInfo {
  const p = profile ?? {};
  const rows = normalizeCrmebAddressListPayload(addressRowsRaw)
    .map(parseCrmebAddressRow)
    .filter((x): x is CrmebAddress => x !== null);
  const defaultAddr = rows.find((a) => a.is_default === 1) ?? rows[0];

  const crmebPhone = String(p.phone ?? "").trim();
  const nameFromProfile = String(p.nickname ?? p.real_name ?? "").trim();
  const avatarFromProfile = String(p.avatar ?? "").trim();
  const email = String(p.email ?? "").trim();
  let address = String((p as { addres?: unknown }).addres ?? "").trim();
  if (!address && defaultAddr) {
    address = formatCrmebAddressLine(defaultAddr);
  }

  const integralRaw = p.integral;
  const integral =
    integralRaw === undefined || integralRaw === null || integralRaw === ""
      ? base.integral
      : Number(integralRaw);

  return {
    id: String(p.uid ?? base.id),
    name: nameFromProfile || base.name,
    avatar: avatarFromProfile || base.avatar,
    phone: crmebPhone || base.phone,
    email: email || base.email,
    address: address || base.address,
    integral: Number.isFinite(integral) ? integral : base.integral,
  };
}
