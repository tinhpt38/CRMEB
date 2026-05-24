import { CrmebClientError } from "./client";

export function formatCrmebError(error: unknown, fallback: string): string {
  if (error instanceof CrmebClientError) {
    const msg = error.crmebError?.msg?.trim();
    if (msg) return msg;
  }
  if (error instanceof Error && error.message.trim()) {
    return error.message.trim();
  }
  return fallback;
}
