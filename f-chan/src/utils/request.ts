import { getConfig } from "./template";

const API_URL = getConfig((config) => config.template.apiUrl);

export async function request<T>(
  path: string,
  options?: RequestInit
): Promise<T> {
  if (!API_URL) {
    throw new Error(`Missing apiUrl. Cannot fetch ${path}.`);
  }
  const response = await fetch(`${API_URL}${path}`, options);
  return response.json() as T;
}

export async function requestWithFallback<T>(
  path: string,
  fallbackValue: T
): Promise<T> {
  try {
    return await request<T>(path);
  } catch (error) {
    console.warn(
      "An error occurred while fetching data. Falling back to default value!"
    );
    console.warn({ path, error, fallbackValue });
    return fallbackValue;
  }
}

export async function requestWithPost<P, T>(
  path: string,
  payload: P
): Promise<T> {
  return await request<T>(path, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(payload),
  });
}
