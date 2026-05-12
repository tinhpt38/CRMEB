// React core
import { createElement } from "react";
import { createRoot } from "react-dom/client";
import { RouterProvider } from "react-router-dom";
import { getDefaultStore } from "jotai";

// Router
import router from "@/router";
import { miniAppThemeState } from "@/state";
import { bootstrapMiniAppTheme, getActiveMiniAppTheme } from "@/utils/theme";

// ZaUI stylesheet
import "zmp-ui/zaui.css";
// Tailwind stylesheet
import "@/css/tailwind.scss";
// Your stylesheet
import "@/css/app.scss";

// Expose app configuration
import appConfig from "../app-config.json";

if (!window.APP_CONFIG) {
  window.APP_CONFIG = appConfig;
}

async function mountApp() {
  await bootstrapMiniAppTheme();
  getDefaultStore().set(miniAppThemeState, getActiveMiniAppTheme());

  const root = createRoot(document.getElementById("app")!);
  root.render(createElement(RouterProvider, { router }));
}

void mountApp();
