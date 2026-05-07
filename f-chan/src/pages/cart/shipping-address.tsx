import {
  crmebAddressesState,
  loadableSelectedCrmebAddressState,
  selectedCrmebAddressIdState,
} from "@/state";
import { CrmebAddress } from "@/types";
import { CrmebApiClient } from "@/utils/crmeb/client";
import { getCrmebToken } from "@/utils/crmeb/token";
import { getConfig } from "@/utils/template";
import { useAtom, useAtomValue, useSetAtom } from "jotai";
import { useState } from "react";
import toast from "react-hot-toast";
import { useNavigate } from "react-router-dom";
import { Button, Icon, Input } from "zmp-ui";

// ---------------------------------------------------------------------------
// Form thêm / sửa địa chỉ
// ---------------------------------------------------------------------------

interface AddressFormProps {
  initial?: CrmebAddress;
  onSuccess: () => void;
  onCancel: () => void;
}

function AddressForm({ initial, onSuccess, onCancel }: AddressFormProps) {
  const [saving, setSaving] = useState(false);
  const refreshAddresses = useSetAtom(crmebAddressesState);
  const setSelectedId = useSetAtom(selectedCrmebAddressIdState);

  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    const fd = new FormData(e.currentTarget);
    const province = (fd.get("province") as string)?.trim();
    const city = (fd.get("city") as string)?.trim();
    const district = (fd.get("district") as string)?.trim();
    const detail = (fd.get("detail") as string)?.trim();
    const real_name = (fd.get("real_name") as string)?.trim();
    const phone = (fd.get("phone") as string)?.trim();

    if (!province || !city || !district || !detail || !real_name || !phone) {
      toast.error("Vui lòng điền đầy đủ thông tin");
      return;
    }

    const apiUrl = getConfig((c) => c.template.apiUrl);
    const token = getCrmebToken();
    if (!apiUrl || !token) {
      toast.error("Chưa đăng nhập. Vui lòng đăng nhập lại.");
      return;
    }

    setSaving(true);
    try {
      const client = new CrmebApiClient({ apiBaseUrl: apiUrl, getToken: () => token });
      const payload = {
        address: { province, city, district },
        real_name,
        post_code: "",
        phone,
        detail,
        is_default: initial ? initial.is_default : true,
        id: initial?.id ?? 0,
        type: 1, // type=1 bỏ qua kiểm tra city_id
      };

      const res = await client.post<any>("/address/edit", payload);

      // Nếu là địa chỉ mới, CRMEB trả về object địa chỉ trong data
      if (!initial && res?.id) {
        setSelectedId(Number(res.id));
      }

      refreshAddresses();
      toast.success(initial ? "Đã cập nhật địa chỉ" : "Đã thêm địa chỉ mới");
      onSuccess();
    } catch (err: any) {
      toast.error(err?.crmebError?.msg ?? err?.message ?? "Lỗi khi lưu địa chỉ");
    } finally {
      setSaving(false);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="flex flex-col space-y-0 h-full">
      <div className="flex-1 overflow-y-auto">
        <div className="bg-section p-4 grid gap-4">
          <Input
            name="real_name"
            label="Họ tên người nhận"
            placeholder="Nguyễn Văn A"
            required
            defaultValue={initial?.real_name}
          />
          <Input
            name="phone"
            label="Số điện thoại"
            placeholder="0901234567"
            required
            defaultValue={initial?.phone}
          />
        </div>

        <div className="bg-section p-4 mt-2 grid gap-4">
          <Input
            name="province"
            label={<>Tỉnh / Thành phố <span className="text-danger">*</span></>}
            placeholder="TP. Hồ Chí Minh"
            required
            defaultValue={initial?.province}
          />
          <Input
            name="city"
            label={<>Quận / Huyện <span className="text-danger">*</span></>}
            placeholder="Quận 7"
            required
            defaultValue={initial?.city}
          />
          <Input
            name="district"
            label={<>Phường / Xã <span className="text-danger">*</span></>}
            placeholder="Tân Thuận Đông"
            required
            defaultValue={initial?.district}
          />
          <Input
            name="detail"
            label={<>Số nhà, tên đường <span className="text-danger">*</span></>}
            placeholder="Z06 Số 13, đường Huỳnh Tấn Phát"
            required
            defaultValue={initial?.detail}
          />
        </div>
      </div>

      <div className="p-4 bg-section flex space-x-2">
        <Button className="flex-1" type="neutral" onClick={onCancel} disabled={saving}>
          Hủy
        </Button>
        <Button className="flex-1" htmlType="submit" loading={saving}>
          {initial ? "Cập nhật" : "Thêm địa chỉ"}
        </Button>
      </div>
    </form>
  );
}

// ---------------------------------------------------------------------------
// Danh sách địa chỉ
// ---------------------------------------------------------------------------

function AddressList() {
  const loadable = useAtomValue(loadableSelectedCrmebAddressState);
  const addresses = useAtomValue(crmebAddressesState);
  const [selectedId, setSelectedId] = useAtom(selectedCrmebAddressIdState);
  const [editTarget, setEditTarget] = useState<CrmebAddress | null | "new">(null);
  const navigate = useNavigate();
  const refreshAddresses = useSetAtom(crmebAddressesState);

  // Khi đang loading lần đầu
  if (loadable.state === "loading" && !addresses.length) {
    return (
      <div className="flex justify-center items-center p-8 text-subtitle text-sm">
        Đang tải địa chỉ…
      </div>
    );
  }

  if (editTarget !== null) {
    return (
      <AddressForm
        initial={editTarget === "new" ? undefined : editTarget}
        onSuccess={() => setEditTarget(null)}
        onCancel={() => setEditTarget(null)}
      />
    );
  }

  const handleSelect = (id: number) => {
    setSelectedId(id);
    navigate(-1);
  };

  const handleDelete = async (id: number) => {
    const apiUrl = getConfig((c) => c.template.apiUrl);
    const token = getCrmebToken();
    if (!apiUrl || !token) return;
    try {
      const client = new CrmebApiClient({ apiBaseUrl: apiUrl, getToken: () => token });
      await client.post("/address/del", { id });
      if (selectedId === id) setSelectedId(null);
      refreshAddresses();
      toast.success("Đã xóa địa chỉ");
    } catch {
      toast.error("Không thể xóa địa chỉ");
    }
  };

  return (
    <div className="flex flex-col h-full">
      <div className="flex-1 overflow-y-auto py-2 space-y-2 px-4">
        {addresses.length === 0 && (
          <div className="text-center text-subtitle text-sm py-8">
            Chưa có địa chỉ nào. Hãy thêm địa chỉ đầu tiên.
          </div>
        )}

        {addresses.map((addr) => {
          const isSelected = selectedId === addr.id ||
            (selectedId === null && addr.is_default === 1);
          return (
            <div
              key={addr.id}
              className={`bg-section rounded-lg p-4 border transition-colors ${
                isSelected ? "border-primary" : "border-transparent"
              }`}
            >
              <button
                className="w-full text-left"
                onClick={() => handleSelect(addr.id)}
              >
                <div className="font-medium text-sm">
                  {addr.real_name}
                  {addr.is_default === 1 && (
                    <span className="ml-2 text-xs text-primary border border-primary rounded px-1">
                      Mặc định
                    </span>
                  )}
                </div>
                <div className="text-xs text-subtitle mt-0.5">{addr.phone}</div>
                <div className="text-xs text-subtitle mt-1">
                  {[addr.detail, addr.district, addr.city, addr.province]
                    .filter(Boolean)
                    .join(", ")}
                </div>
              </button>

              <div className="flex space-x-3 mt-3 justify-end">
                <button
                  className="text-xs text-primary flex items-center space-x-1"
                  onClick={() => setEditTarget(addr)}
                >
                  <Icon icon="zi-edit-text" size={14} />
                  <span>Sửa</span>
                </button>
                <button
                  className="text-xs text-danger flex items-center space-x-1"
                  onClick={() => handleDelete(addr.id)}
                >
                  <Icon icon="zi-delete" size={14} />
                  <span>Xóa</span>
                </button>
              </div>
            </div>
          );
        })}
      </div>

      <div className="p-4 bg-section">
        <Button fullWidth onClick={() => setEditTarget("new")}>
          + Thêm địa chỉ mới
        </Button>
      </div>
    </div>
  );
}

// ---------------------------------------------------------------------------
// Export default
// ---------------------------------------------------------------------------

export default function ShippingAddressPage() {
  return <AddressList />;
}
