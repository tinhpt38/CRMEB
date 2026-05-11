import type { Location } from "@/types";
import { CrmebApiClient } from "@/utils/crmeb/client";
import { getConfig } from "@/utils/template";
import { authorize, getAccessToken, getLocation } from "zmp-sdk/apis";

type ZaloLocationPayload = {
  lat?: number | string;
  lng?: number | string;
  latitude?: number | string;
  longitude?: number | string;
};

function parseLocationPayload(payload: ZaloLocationPayload | null | undefined): Location | null {
  if (!payload) return null;

  const lat = Number(payload.lat ?? payload.latitude);
  const lng = Number(payload.lng ?? payload.longitude);
  if (!Number.isFinite(lat) || !Number.isFinite(lng) || (lat === 0 && lng === 0)) {
    return null;
  }

  return { lat, lng };
}

export async function resolveUserLocation(): Promise<Location | null> {
  const apiUrl = getConfig((config) => config.template.apiUrl);
  if (!apiUrl) return null;

  try {
    await authorize({ scopes: ["scope.userLocation"] });
  } catch (error) {
    console.warn("Zalo authorize scope.userLocation:", error);
  }

  let locationToken = "";
  try {
    const data = await getLocation({});
    locationToken = String(data?.token ?? "").trim();
  } catch (error) {
    console.warn("Zalo getLocation:", error);
    return null;
  }

  if (!locationToken) return null;

  try {
    const accessToken = String(await getAccessToken({})).trim();
    if (!accessToken) return null;

    const client = new CrmebApiClient({
      apiBaseUrl: apiUrl,
      getToken: () => null,
    });
    const payload = await client.post<ZaloLocationPayload>("/zalo/location", {
      access_token: accessToken,
      code: locationToken,
    });
    return parseLocationPayload(payload);
  } catch (error) {
    console.warn("Resolve user location failed:", error);
    return null;
  }
}
