import { useAtomValue } from "jotai";
import { useLocation, useNavigate } from "react-router-dom";
import {
  categoriesStateUpwrapped,
  loadableFirstStationState,
  loadableSelectedStationState,
  loadableUserInfoState,
  pageHeaderContextState,
} from "@/state";
import { useMemo } from "react";
import { useRouteHandle } from "@/hooks";
import { getConfig } from "@/utils/template";
import headerIllus from "@/static/header-illus.svg";
import SearchBar from "./search-bar";
import TransitionLink from "./transition-link";
import { Icon } from "zmp-ui";
import { DefaultUserAvatar } from "./vectors";
import { shareProduct } from "@/utils/shareProduct";

export default function Header() {
  const categories = useAtomValue(categoriesStateUpwrapped);
  const navigate = useNavigate();
  const location = useLocation();
  const [handle, match] = useRouteHandle();
  const pageHeaderContext = useAtomValue(pageHeaderContextState);
  const userInfo = useAtomValue(loadableUserInfoState);
  const selectedStation = useAtomValue(loadableSelectedStationState);
  const firstStation = useAtomValue(loadableFirstStationState);
  const stationForHeader =
    selectedStation.state === "hasData" && selectedStation.data
      ? selectedStation.data
      : firstStation.state === "hasData" && firstStation.data
        ? firstStation.data
        : null;

  const title = useMemo(() => {
    if (pageHeaderContext?.title) {
      return pageHeaderContext.title;
    }
    if (handle) {
      if (typeof handle.title === "function") {
        return handle.title({ categories, params: match.params });
      } else {
        return handle.title;
      }
    }
  }, [handle, categories, match.params, pageHeaderContext?.title]);

  const showBack = location.key !== "default" && !handle?.noBack;

  return (
    <div
      className="w-full flex flex-col px-4 bg-primary text-primaryForeground pt-st overflow-hidden bg-no-repeat bg-right-top"
      style={{
        backgroundImage: `url(${headerIllus})`,
      }}
    >
      <div className="w-full min-h-12 pr-[90px] flex py-2 space-x-2 items-center">
        {handle?.logo ? (
          <>
            <img
              src={getConfig((c) => c.template.logoUrl)}
              className="flex-none w-8 h-8 rounded-full"
            />
            <TransitionLink to="/stations" className="flex-1 overflow-hidden">
              <div className="flex items-center space-x-1">
                <h1 className="text-lg font-bold">
                  {stationForHeader?.name ?? getConfig((c) => c.template.shopName)}
                </h1>
                <Icon icon="zi-chevron-right" />
              </div>
              <p className="overflow-x-auto whitespace-nowrap text-2xs">
                {stationForHeader?.address ??
                  getConfig((c) => c.template.shopAddress)}
              </p>
            </TransitionLink>
          </>
        ) : (
          <>
            {showBack && (
              <div
                className="py-1 px-2 cursor-pointer"
                onClick={() => navigate(-1)}
              >
                <Icon icon="zi-arrow-left" />
              </div>
            )}
            <div className="flex-1 min-w-0 flex items-center gap-2">
              <div className="text-xl font-medium truncate">{title}</div>
              {pageHeaderContext?.shareProduct && (
                <button
                  type="button"
                  className="flex-none p-1.5 rounded-full active:bg-white/10"
                  aria-label="Chia sẻ cho bạn bè"
                  onClick={() => shareProduct(pageHeaderContext.shareProduct!)}
                >
                  <Icon icon="zi-share" />
                </button>
              )}
            </div>
          </>
        )}
      </div>
      {handle?.search && (
        <div className="w-full py-2 flex space-x-2">
          <SearchBar
            onFocus={() => {
              if (location.pathname !== "/search") {
                navigate("/search", { viewTransition: true });
              }
            }}
          />
          <TransitionLink to="/profile">
            {userInfo.state === "hasData" && userInfo.data ? (
              <img
                className="w-8 h-8 rounded-full"
                src={userInfo.data.avatar}
              />
            ) : (
              <DefaultUserAvatar
                width={32}
                height={32}
                className={userInfo.state === "loading" ? "animate-pulse" : ""}
              />
            )}
          </TransitionLink>
        </div>
      )}
    </div>
  );
}
