export type CrmebRawEnvelope<T = unknown> = {
  status: number | string;
  msg: string;
  data?: T;
};

export type CrmebApiError = {
  status: number | string;
  msg: string;
};

export type NormalizedCrmebResult<T> =
  | { ok: true; data: T }
  | { ok: false; error: CrmebApiError; raw: CrmebRawEnvelope };

type CrmebBusinessWrappedData<R = unknown> = {
  status: string;
  result: R;
};

function isRecord(value: unknown): value is Record<string, unknown> {
  return !!value && typeof value === "object" && !Array.isArray(value);
}

/**
 * CRMEB commonly returns:
 * - success:   { status: 200, msg: "...", data: <payload> }
 * - business:  { status: 200, msg: "...", data: { status: "NONE", result: <payload> } }
 * - fail:      { status: 400, msg: "...", (no data) }
 */
export function normalizeCrmebEnvelope<T = unknown>(
  raw: unknown
): NormalizedCrmebResult<T> {
  if (!isRecord(raw)) {
    return {
      ok: false,
      error: { status: "INVALID_ENVELOPE", msg: "CRMEB response is not an object" },
      raw: raw as CrmebRawEnvelope,
    };
  }

  const status = raw.status as CrmebRawEnvelope<T>["status"];
  const msg = raw.msg as string | undefined;
  const data = raw.data as unknown;

  if (status === undefined || msg === undefined) {
    return {
      ok: false,
      error: { status: "INVALID_ENVELOPE", msg: "CRMEB response missing status/msg" },
      raw: raw as CrmebRawEnvelope,
    };
  }

  if (status !== 200) {
    return {
      ok: false,
      error: { status, msg },
      raw: raw as CrmebRawEnvelope,
    };
  }

  // status === 200 => success. If business wrapper exists, unwrap `data.result`.
  if (data !== undefined) {
    if (isRecord(data) && "result" in data && "status" in data) {
      const wrapped = data as CrmebBusinessWrappedData<T>;
      return { ok: true, data: (wrapped.result as T) ?? (wrapped as unknown as T) };
    }
    return { ok: true, data: data as T };
  }

  return { ok: true, data: undefined as T };
}

