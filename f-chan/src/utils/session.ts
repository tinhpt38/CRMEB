import CONFIG from "@/config";

/** Người dùng đã bấm đăng xuất — không gọi lại /zalo/auth cho đến khi chủ động đăng nhập. */export function isSessionLoggedOut(): boolean {
  try {
    return localStorage.getItem(CONFIG.STORAGE_KEYS.SESSION_LOGGED_OUT) === "1";
  } catch {
    return false;
  }
}

export function setSessionLoggedOut(loggedOut: boolean): void {
  try {
    if (loggedOut) {
      localStorage.setItem(CONFIG.STORAGE_KEYS.SESSION_LOGGED_OUT, "1");
    } else {
      localStorage.removeItem(CONFIG.STORAGE_KEYS.SESSION_LOGGED_OUT);
    }
  } catch {
    // ignore
  }
}
