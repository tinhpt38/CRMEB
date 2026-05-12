import { getConfig } from "@/utils/template";
import type { MiniAppTheme, MiniAppThemeResponse } from "@/types";
import { normalizeCrmebEnvelope } from "@/utils/crmeb/envelope";

const FONT_FAMILY_CSS: Record<string, string> = {
  system:
    '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
  inter:
    '"Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
};

export const DEFAULT_MINI_APP_THEME: MiniAppTheme = {
  shopName: "",
  logoUrl: "",
  faviconUrl: "",
  primary: "#52b361",
  primaryForeground: "#ffffff",
  background: "#f7f7f8",
  foreground: "#0d0d0d",
  section: "#ffffff",
  subtitle: "#6f7071",
  inactive: "#a9adb2",
  danger: "#f50000",
  gradient: "#52b361",
  secondary: "#d1f0db",
  secondaryForeground: "#135328",
  fontFamily: "system",
  fontFamilyCss:
    '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
  baseFontSize: 15,
  headerColor: "#52b361",
  statusBar: "default",
};

const THEME_CACHE_KEY = "fchanMiniAppTheme";
const THEME_VERSION_CACHE_KEY = "fchanMiniAppThemeVersion";

type ThemeCachePayload = {
  version: number;
  theme: MiniAppTheme;
};

let activeTheme: MiniAppTheme = { ...DEFAULT_MINI_APP_THEME };

function hexToRgba(hex: string, alpha: number) {
  const normalized = hex.replace("#", "");
  if (normalized.length !== 6) {
    return hex;
  }
  const r = parseInt(normalized.slice(0, 2), 16);
  const g = parseInt(normalized.slice(2, 4), 16);
  const b = parseInt(normalized.slice(4, 6), 16);
  return `rgba(${r}, ${g}, ${b}, ${alpha})`;
}

function readThemeCache(): ThemeCachePayload | null {
  try {
    const raw = localStorage.getItem(THEME_CACHE_KEY);
    const version = Number(localStorage.getItem(THEME_VERSION_CACHE_KEY) || 0);
    if (!raw || !version) return null;
    const theme = JSON.parse(raw) as MiniAppTheme;
    return { version, theme };
  } catch {
    return null;
  }
}

function writeThemeCache(payload: ThemeCachePayload) {
  localStorage.setItem(THEME_CACHE_KEY, JSON.stringify(payload.theme));
  localStorage.setItem(THEME_VERSION_CACHE_KEY, String(payload.version));
}

function joinApiUrl(baseUrl: string, path: string) {
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

function resolveFontFamilyCss(theme: Partial<MiniAppTheme> | null | undefined) {
  if (theme?.fontFamilyCss) {
    return theme.fontFamilyCss;
  }
  const key = theme?.fontFamily || "system";
  return FONT_FAMILY_CSS[key] || FONT_FAMILY_CSS.system;
}

function resolveAssetUrl(url: string) {
  const value = url.trim();
  if (!value) return "";
  if (/^https?:\/\//i.test(value)) return value;

  const apiBase = getConfig((config) => config.template.apiUrl);
  if (!apiBase) return value;

  try {
    const base = new URL(apiBase);
    return value.startsWith("/")
      ? `${base.origin}${value}`
      : new URL(value, apiBase).toString();
  } catch {
    return value;
  }
}

function normalizeTheme(theme: Partial<MiniAppTheme> | null | undefined): MiniAppTheme {
  const merged = {
    ...DEFAULT_MINI_APP_THEME,
    ...(theme ?? {}),
  };

  return {
    ...merged,
    shopName: merged.shopName.replace(/\s+/g, " ").trim(),
    logoUrl: resolveAssetUrl(merged.logoUrl),
    faviconUrl: resolveAssetUrl(merged.faviconUrl),
    fontFamilyCss: resolveFontFamilyCss(merged),
  };
}

export function getActiveMiniAppTheme() {
  return activeTheme;
}

export function applyTheme(theme: MiniAppTheme) {
  const resolved = normalizeTheme(theme);
  activeTheme = resolved;

  const root = document.documentElement;
  const set = (name: string, value: string) => {
    root.style.setProperty(name, value);
  };

  set("--primary", resolved.primary);
  set("--primaryForeground", resolved.primaryForeground);
  set("--background", resolved.background);
  set("--foreground", resolved.foreground);
  set("--section", resolved.section);
  set("--subtitle", resolved.subtitle);
  set("--inactive", resolved.inactive);
  set("--danger", resolved.danger);
  set("--tabIndicator", resolved.foreground);
  set("--accentSoft", hexToRgba(resolved.primary, 0.08));
  set("--font-sans", resolved.fontFamilyCss);
  set("--secondary", resolved.secondary);
  set("--secondaryForeground", resolved.secondaryForeground);

  set("--zaui-light-button-primary-background", resolved.primary);
  set("--zaui-light-button-primary-text", resolved.primaryForeground);
  set("--zaui-light-button-secondary-background", resolved.secondary);
  set("--zaui-light-button-secondary-text", resolved.secondaryForeground);
  set("--zaui-light-button-tertiary-text", resolved.foreground);
  set("--zaui-light-button-primary-background-pressed", resolved.primary);
  set("--zaui-light-button-secondary-background-pressed", resolved.secondary);
  set("--zaui-light-tabbar-active-line", resolved.primary);
  set("--zaui-light-tabbar-label-active", resolved.primary);

  root.style.fontSize = `${resolved.baseFontSize}px`;

  const themeColor = resolved.headerColor || resolved.primary;
  document
    .querySelector('meta[name="theme-color"]')
    ?.setAttribute("content", themeColor);

  if (resolved.shopName) {
    document.title = resolved.shopName;
  }
}

async function fetchRemoteTheme(): Promise<ThemeCachePayload | null> {
  const apiBase = getConfig((config) => config.template.apiUrl);
  if (!apiBase) return null;

  const url = joinApiUrl(apiBase, "zalo/theme");
  const headers: Record<string, string> = {
    Accept: "application/json",
  };
  if (isNgrokUrl(url)) {
    headers["ngrok-skip-browser-warning"] = "true";
  }

  const res = await fetch(url, { headers });
  if (!res.ok) return null;

  const raw = await res.json();
  const normalized = normalizeCrmebEnvelope<MiniAppThemeResponse>(raw);
  if (!normalized.ok || !normalized.data?.theme) {
    return null;
  }

  return {
    version: normalized.data.version ?? 0,
    theme: normalizeTheme(normalized.data.theme),
  };
}

async function loadRemoteTheme(): Promise<MiniAppTheme | null> {
  try {
    const remote = await fetchRemoteTheme();
    if (!remote) return null;

    writeThemeCache(remote);
    applyTheme(remote.theme);
    return remote.theme;
  } catch (error) {
    console.warn("Failed to load mini app theme:", error);
    return null;
  }
}

export async function bootstrapMiniAppTheme() {
  const cached = readThemeCache();
  if (cached?.theme) {
    applyTheme(cached.theme);
  } else {
    applyTheme(DEFAULT_MINI_APP_THEME);
  }

  await loadRemoteTheme();
}

export async function refreshMiniAppTheme() {
  return loadRemoteTheme();
}

export function resolveMiniAppBrand() {
  const theme = getActiveMiniAppTheme();
  const fallbackShopName = getConfig((config) => config.template.shopName);
  const fallbackLogo = getConfig((config) => config.template.logoUrl);

  return {
    shopName: theme.shopName || fallbackShopName,
    logoUrl: theme.logoUrl || fallbackLogo,
  };
}

export const MINI_APP_THEME_STORAGE = {
  theme: THEME_CACHE_KEY,
  version: THEME_VERSION_CACHE_KEY,
} as const;
