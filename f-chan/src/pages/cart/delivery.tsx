import HorizontalDivider from "@/components/horizontal-divider";
import Section from "@/components/section";
import { StationSkeleton } from "@/components/skeleton";
import TransitionLink from "@/components/transition-link";
import {
  HomeIcon,
  LocationMarkerLineIcon,
  LocationMarkerPackageIcon,
  PackageDeliveryIcon,
  PlusIcon,
  ShipperIcon,
} from "@/components/vectors";
import {
  deliveryModeState,
  loadableSelectedCrmebAddressState,
  loadableUserInfoState,
  pickupContactState,
  selectedStationIdState,
  selectedStationState,
  stationsState,
  userLocationState,
} from "@/state";
import { CrmebApiClient } from "@/utils/crmeb/client";
import { getCrmebToken } from "@/utils/crmeb/token";
import { getConfig } from "@/utils/template";
import { useAtom, useAtomValue, useSetAtom } from "jotai";
import { Suspense, useEffect, useState } from "react";
import { Input } from "zmp-ui";
import DeliverySummary from "./delivery-summary";

function ShippingAddressSummary() {
  const loadable = useAtomValue(loadableSelectedCrmebAddressState);

  if (loadable.state === "loading") {
    return (
      <div className="h-16 flex items-center justify-center text-subtitle text-xs">
        Đang tải địa chỉ…
      </div>
    );
  }

  const address = loadable.state === "hasData" ? loadable.data : null;

  if (!address) {
    return (
      <TransitionLink
        className="flex flex-col space-y-2 justify-center items-center p-4 w-full"
        to="/shipping-address"
      >
        <LocationMarkerPackageIcon />
        <div className="flex space-x-1 items-center text-center p-2">
          <PlusIcon width={16} height={16} />
          <span className="text-sm font-medium">Thêm địa chỉ nhận hàng</span>
        </div>
      </TransitionLink>
    );
  }

  const fullAddress = [address.detail, address.district, address.city, address.province]
    .filter(Boolean)
    .join(", ");

  return (
    <DeliverySummary
      icon={<LocationMarkerLineIcon />}
      title="Địa chỉ nhận hàng"
      subtitle={`${address.real_name} • ${address.phone}`}
      description={fullAddress}
      linkTo="/shipping-address"
    />
  );
}

function SelectedStationSummary() {
  const selectedStation = useAtomValue(selectedStationState);
  if (!selectedStation) {
    return (
      <TransitionLink
        className="flex flex-col space-y-2 justify-center items-center p-4 w-full"
        to="/stations"
      >
        <HomeIcon />
        <div className="flex space-x-1 items-center text-center p-2">
          <PlusIcon width={16} height={16} />
          <span className="text-sm font-medium">Chọn cửa hàng nhận hàng</span>
        </div>
      </TransitionLink>
    );
  }

  const description = [selectedStation.address, selectedStation.distance]
    .filter(Boolean)
    .join(" • ");

  return (
    <DeliverySummary
      icon={<HomeIcon />}
      title="Nhận hàng tại"
      subtitle={selectedStation.name}
      description={description}
      linkTo="/stations"
    />
  );
}

function PickupContactFields() {
  const [contact, setContact] = useAtom(pickupContactState);
  const userInfoLoadable = useAtomValue(loadableUserInfoState);
  const addressLoadable = useAtomValue(loadableSelectedCrmebAddressState);

  useEffect(() => {
    const userInfo =
      userInfoLoadable.state === "hasData" ? userInfoLoadable.data : null;
    const address =
      addressLoadable.state === "hasData" ? addressLoadable.data : null;

    setContact((prev) => {
      const real_name =
        prev.real_name.trim() ||
        userInfo?.name?.trim() ||
        address?.real_name?.trim() ||
        "";
      const phone =
        prev.phone.trim() ||
        userInfo?.phone?.trim() ||
        address?.phone?.trim() ||
        "";
      if (real_name === prev.real_name && phone === prev.phone) return prev;
      return { real_name, phone };
    });
  }, [addressLoadable, setContact, userInfoLoadable]);

  return (
    <div className="p-4 pt-0 grid gap-4">
      <Input
        name="pickup_real_name"
        label="Họ tên người nhận"
        placeholder="Nguyễn Văn A"
        required
        value={contact.real_name}
        onChange={(e) =>
          setContact((prev) => ({ ...prev, real_name: e.target.value }))
        }
      />
      <Input
        name="pickup_phone"
        label="Số điện thoại"
        placeholder="0901234567"
        required
        value={contact.phone}
        onChange={(e) =>
          setContact((prev) => ({ ...prev, phone: e.target.value }))
        }
      />
    </div>
  );
}

function PickupStationSync() {
  const stations = useAtomValue(stationsState);
  const [selectedStationId, setSelectedStationId] = useAtom(selectedStationIdState);

  useEffect(() => {
    if (!stations.length) return;
    if (selectedStationId && stations.some((station) => station.id === selectedStationId)) {
      return;
    }
    setSelectedStationId(stations[0].id);
  }, [stations, selectedStationId, setSelectedStationId]);

  return null;
}

function Delivery() {
  const [selectedDeliveryMode, setSelectedDeliveryMode] =
    useAtom(deliveryModeState);
  const [pickupEnabled, setPickupEnabled] = useState(true);
  const refreshLocation = useSetAtom(userLocationState);
  const refreshStations = useSetAtom(stationsState);

  useEffect(() => {
    const apiUrl = getConfig((config) => config.template.apiUrl);
    if (!apiUrl) return;

    const client = new CrmebApiClient({
      apiBaseUrl: apiUrl,
      getToken: () => getCrmebToken(),
    });

    client
      .get<{ store_self_mention?: boolean }>("/basic_config")
      .then((data) => setPickupEnabled(!!data?.store_self_mention))
      .catch(() => setPickupEnabled(true));
  }, []);

  useEffect(() => {
    if (selectedDeliveryMode !== "pickup") return;
    refreshLocation();
    refreshStations();
  }, [selectedDeliveryMode, refreshLocation, refreshStations]);

  useEffect(() => {
    if (!pickupEnabled && selectedDeliveryMode === "pickup") {
      setSelectedDeliveryMode("shipping");
    }
  }, [pickupEnabled, selectedDeliveryMode, setSelectedDeliveryMode]);

  const deliveryOptions = [
    {
      type: "shipping" as const,
      name: "Giao tận nơi",
      icon: <ShipperIcon />,
    },
    ...(pickupEnabled
      ? [
          {
            type: "pickup" as const,
            name: "Tự đến lấy",
            icon: <PackageDeliveryIcon />,
          },
        ]
      : []),
  ];

  return (
    <Section title="Hình thức giao hàng" className="rounded-lg">
      <div
        className={`grid gap-4 p-4 pt-2 ${
          deliveryOptions.length > 1 ? "grid-cols-2" : "grid-cols-1"
        }`}
      >
        {deliveryOptions.map((option) => (
          <button
            key={option.type}
            className={"flex justify-center items-center space-x-2 text-base font-medium bg-background rounded-full h-12 px-3.5 ".concat(
              selectedDeliveryMode === option.type
                ? "border border-primary text-primary"
                : ""
            )}
            onClick={() => setSelectedDeliveryMode(option.type)}
          >
            {option.icon}
            <span>{option.name}</span>
          </button>
        ))}
      </div>
      <HorizontalDivider />
      {selectedDeliveryMode === "shipping" ? (
        <ShippingAddressSummary />
      ) : (
        <Suspense fallback={<StationSkeleton />}>
          <PickupStationSync />
          <SelectedStationSummary />
          <PickupContactFields />
        </Suspense>
      )}
    </Section>
  );
}

export default Delivery;
