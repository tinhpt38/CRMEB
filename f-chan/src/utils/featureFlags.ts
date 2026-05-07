import { getConfig } from "@/utils/template";

export type CrmebFeatureDomain = "catalog" | "orders" | "checkout";

export function isCrmebFeatureEnabled(domain: CrmebFeatureDomain): boolean {
  const apiUrl = getConfig((config) => config.template.apiUrl);
  const features = getConfig((config) => config.template.features ?? {});
  const explicit = (features as Record<string, unknown>)[domain];

  // If explicitly configured, obey it; still require apiUrl to be set.
  if (typeof explicit === "boolean") {
    return explicit && !!apiUrl;
  }

  // Default behavior: if apiUrl is set, assume enabled.
  return !!apiUrl;
}

