import { getConfig } from "@/utils/template";
import {
  normalizeCrmebEnvelope,
  type CrmebApiError,
  type NormalizedCrmebResult,
} from "./envelope";

export type CrmebTokenGetter = () => Promise<string | null> | string | null;

export class CrmebClientError extends Error {
  public raw: unknown;
  public crmebError: CrmebApiError;

  constructor(message: string, crmebError: CrmebApiError, raw: unknown) {
    super(message);
    this.name = "CrmebClientError";
    this.crmebError = crmebError;
    this.raw = raw;
  }
}

export type CrmebClientOptions = {
  /**
   * Should return CRMEB JWT (the one received from `POST /api/zalo/auth`)
   */  getToken: CrmebTokenGetter;
  /**
   * Optional override. If omitted, uses `app-config.json -> template.apiUrl`.
   */  apiBaseUrl?: string;
};

function joinUrl(baseUrl: string, path: string) {
  const cleanBase = baseUrl.endsWith("/") ? baseUrl : `${baseUrl}/`;
  const cleanPath = path.replace(/^\/+/, "");
  return `${cleanBase}${cleanPath}`;
}

function isNgrokUrl(url: string) {
  try {
    const host = new URL(url).hostname;
    return host.endsWith(".ngrok-free.app") || host.endsWith(".ngrok.app");
  } catch {
    return false;
  }
}

export class CrmebApiClient {
  private getToken: CrmebTokenGetter;
  private apiBaseUrl?: string;

  constructor(opts: CrmebClientOptions) {
    this.getToken = opts.getToken;
    this.apiBaseUrl = opts.apiBaseUrl;
  }

  private getApiBaseUrl() {
    if (this.apiBaseUrl) return this.apiBaseUrl;
    return getConfig((config) => config.template.apiUrl);
  }

  private async buildHeaders(extra?: Record<string, string>) {
    const token = await this.getToken();
    const apiBaseUrl = this.getApiBaseUrl() || "";
    const headers: Record<string, string> = {
      Accept: "application/json",
      ...(extra ?? {}),
    };

    // ngrok free returns an interstitial HTML page unless this header is present.
    if (apiBaseUrl && isNgrokUrl(apiBaseUrl)) {
      headers["ngrok-skip-browser-warning"] = "true";
    }

    if (token) {
      const value = `Bearer ${token}`;
      // CRMEB middleware primarily reads `Authori-zation` and has fallback to `Authorization`.
      headers["Authori-zation"] = value;
      headers["Authorization"] = value;
    }

    return headers;
  }

  private async handleResponse<T>(res: Response, rawJson: unknown): Promise<T> {
    if (!res.ok) {
      // In case CRMEB returns non-JSON error (or network proxy interference)
      throw new CrmebClientError(
        `HTTP error: ${res.status}`,
        { status: res.status, msg: res.statusText || "HTTP_ERROR" },
        rawJson
      );
    }

    const normalized: NormalizedCrmebResult<T> =
      normalizeCrmebEnvelope<T>(rawJson);

    if (!normalized.ok) {
      throw new CrmebClientError(
        `CRMEB api error: ${normalized.error.msg}`,
        normalized.error,
        rawJson
      );
    }

    return normalized.data;
  }

  async request<T>(args: {
    path: string;
    method?: "GET" | "POST";
    query?: Record<string, string | number | boolean | undefined>;
    payload?: unknown;
    extraHeaders?: Record<string, string>;
    signal?: AbortSignal;
  }): Promise<T> {
    const apiBase = this.getApiBaseUrl();
    if (!apiBase) {
      throw new CrmebClientError(
        "Missing CRMEB api base URL. Please set `app-config.json -> template.apiUrl`.",
        { status: "NO_API_BASE", msg: "Missing apiUrl" },
        null
      );
    }

    const url = new URL(joinUrl(apiBase, args.path));
    if (args.query) {
      for (const [k, v] of Object.entries(args.query)) {
        if (v === undefined) continue;
        url.searchParams.set(k, String(v));
      }
    }

    const headers = await this.buildHeaders({
      ...(args.method === "POST" ? { "Content-Type": "application/json" } : {}),
      ...(args.extraHeaders ?? {}),
    });

    const res = await fetch(url.toString(), {
      method: args.method ?? "GET",
      headers,
      body:
        args.method === "POST" ? JSON.stringify(args.payload ?? {}) : undefined,
      signal: args.signal,
    });

    let rawJson: unknown = null;
    try {
      rawJson = await res.json();
    } catch {
      const rawText = await res.text().catch(() => "");
      throw new CrmebClientError(
        "CRMEB response is not JSON",
        { status: "INVALID_JSON", msg: "CRMEB response is not JSON" },
        {
          status: res.status,
          statusText: res.statusText,
          contentType: res.headers.get("content-type") || "",
          bodySnippet: rawText.slice(0, 500),
        }
      );
    }
    return this.handleResponse<T>(res, rawJson);
  }

  get<T>(path: string, query?: CrmebApiClient["request"]["query"]) {
    return this.request<T>({ path, method: "GET", query });
  }

  post<T>(path: string, payload?: unknown) {
    return this.request<T>({ path, method: "POST", payload });
  }
}

