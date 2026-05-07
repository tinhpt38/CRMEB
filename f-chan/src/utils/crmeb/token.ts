import CONFIG from "@/config";

export function getCrmebToken(): string | null {
  try {
    return localStorage.getItem(CONFIG.STORAGE_KEYS.CRMEB_TOKEN);
  } catch {
    return null;
  }
}

export function setCrmebToken(token: string): void {
  localStorage.setItem(CONFIG.STORAGE_KEYS.CRMEB_TOKEN, token);
}

export function clearCrmebToken(): void {
  try {
    localStorage.removeItem(CONFIG.STORAGE_KEYS.CRMEB_TOKEN);
  } catch {
    // ignore
  }
}

