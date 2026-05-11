import { loadableUserInfoState } from "@/state";
import { useAtomValue } from "jotai";
import Barcode from "./barcode";
import barcodeIllusLeft from "@/static/barcode-illus-left.svg";
import barcodeIllusRight from "@/static/barcode-illus-right.svg";

function formatIntegral(value: number | undefined) {
  if (value === undefined || Number.isNaN(value)) return "—";
  return new Intl.NumberFormat("vi-VN").format(value);
}

export default function Points() {
  const userInfo = useAtomValue(loadableUserInfoState);
  const memberId =
    userInfo.state === "hasData" && userInfo.data?.id ? userInfo.data.id : "";
  const integral =
    userInfo.state === "hasData" ? userInfo.data?.integral : undefined;

  return (
    <div
      className="rounded-lg bg-primary text-white p-8 pt-6 bg-cover text-center"
      style={{
        backgroundImage: `url(${barcodeIllusLeft}), url(${barcodeIllusRight})`,
        backgroundRepeat: "no-repeat",
        backgroundPosition: "top left, bottom right",
        backgroundSize: "auto, auto",
      }}
    >
      <div className="text-xl font-medium opacity-95">
        {formatIntegral(integral)} điểm
      </div>
      <div className="opacity-95 text-2xs">
        {memberId ? `Mã thành viên: ${memberId}` : "Đăng nhập để xem mã thành viên"}
      </div>
      <div className="bg-white rounded-lg mt-2 py-2.5 space-y-2.5 flex flex-col items-center">
        <div className="text-2xs text-subtitle text-center">
          Quét mã tại quầy để tích điểm
        </div>
        <Barcode value={memberId} />
      </div>
    </div>
  );
}
