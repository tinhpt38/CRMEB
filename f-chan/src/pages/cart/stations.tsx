import { StationSkeleton } from "@/components/skeleton";
import {
  selectedStationIdState,
  stationsState,
  userLocationState,
} from "@/state";
import type { Station } from "@/types";
import { useAtomValue, useSetAtom } from "jotai";
import { Suspense } from "react";
import toast from "react-hot-toast";
import { useNavigate } from "react-router-dom";

function Station({
  station,
  recommended,
  onSelect,
}: {
  station: Station;
  recommended: boolean;
  onSelect: () => void;
}) {
  return (
    <button
      className="flex items-center space-x-4 p-4 pr-2 bg-section rounded-lg text-left"
      onClick={onSelect}
    >
      <img src={station.image} className="h-14 w-14 rounded-lg bg-skeleton" />
      <div className="flex-1 space-y-0.5">
        <div className="flex items-center gap-2">
          <div className="text-sm">{station.name}</div>
          {recommended ? (
            <span className="text-[10px] px-1.5 py-0.5 rounded-full bg-accentSoft text-primary">
              Gần bạn nhất
            </span>
          ) : null}
        </div>
        <div className="text-xs text-inactive">{station.address}</div>
        {station.distance ? (
          <div className="text-xs text-primary">{station.distance}</div>
        ) : null}
      </div>
    </button>
  );
}

function Stations() {
  const stations = useAtomValue(stationsState);
  const selectedStationId = useAtomValue(selectedStationIdState);
  const setSelectedStationId = useSetAtom(selectedStationIdState);
  const refreshLocation = useSetAtom(userLocationState);
  const refreshStations = useSetAtom(stationsState);
  const navigate = useNavigate();

  return (
    <>
      <div className="flex items-center justify-between px-1 pb-1">
        <p className="text-xs text-subtitle">
          Chọn cửa hàng nhận hàng. Mini app sẽ gợi ý điểm gần bạn khi có quyền vị trí.
        </p>
        <button
          type="button"
          className="text-xs text-primary shrink-0"
          onClick={() => {
            refreshLocation();
            refreshStations();
          }}
        >
          Cập nhật vị trí
        </button>
      </div>
      {stations.map((station, index) => (
        <Station
          key={station.id}
          station={station}
          recommended={index === 0 && !!station.distance}
          onSelect={() => {
            setSelectedStationId(station.id);
            toast.success(
              selectedStationId === station.id
                ? "Đã chọn cửa hàng nhận hàng"
                : "Đã thay đổi điểm nhận hàng"
            );
            navigate(-1);
          }}
        />
      ))}
    </>
  );
}

function StationsPage() {
  return (
    <div className="p-4 space-y-2 flex flex-col">
      <Suspense
        fallback={
          <>
            <StationSkeleton />
            <StationSkeleton />
            <StationSkeleton />
            <StationSkeleton />
          </>
        }
      >
        <Stations />
      </Suspense>
    </div>
  );
}

export default StationsPage;
