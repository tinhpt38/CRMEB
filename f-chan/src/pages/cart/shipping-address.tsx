import {
  crmebAddressesState,
  loadableCityListState,
  loadableSelectedCrmebAddressState,
  loadableUserInfoState,
  selectedCrmebAddressIdState,
} from "@/state";
import { CityNode, CrmebAddress } from "@/types";
import { CrmebApiClient } from "@/utils/crmeb/client";
import { getCrmebToken } from "@/utils/crmeb/token";
import { getConfig } from "@/utils/template";
import { isValidVnPhone, VN_PHONE_ERROR } from "@/utils/phone";
import { useAtom, useAtomValue, useSetAtom } from "jotai";
import { useCallback, useEffect, useMemo, useState } from "react";
import toast from "react-hot-toast";
import { useNavigate } from "react-router-dom";
import { Button, Icon, Input, Sheet } from "zmp-ui";

// ---------------------------------------------------------------------------
// SearchableSelect — bottom sheet với ô tìm kiếm
// ---------------------------------------------------------------------------

interface SelectOption {
  value: string;
  label: string;
}

interface SearchableSelectProps {
  label: React.ReactNode;
  placeholder?: string;
  value: string;
  options: SelectOption[];
  onChange: (value: string) => void;
  disabled?: boolean;
}

function SearchableSelect({
  label,
  placeholder = "Chọn…",
  value,
  options,
  onChange,
  disabled = false,
}: SearchableSelectProps) {
  const [open, setOpen] = useState(false);
  const [query, setQuery] = useState("");

  const filtered = useMemo(
    () =>
      options.filter((o) =>
        o.label.toLowerCase().includes(query.toLowerCase())
      ),
    [options, query]
  );

  const handleClose = useCallback(() => {
    setOpen(false);
    setQuery("");
  }, []);

  const handleSelect = useCallback(
    (opt: SelectOption) => {
      onChange(opt.value);
      handleClose();
    },
    [onChange, handleClose]
  );

  const displayLabel = options.find((o) => o.value === value)?.label ?? "";

  return (
    <>
      <button
        type="button"
        disabled={disabled}
        onClick={() => !disabled && setOpen(true)}
        className={`w-full flex items-center justify-between rounded-lg border px-3 py-2.5 text-sm transition-colors ${
          disabled
            ? "bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed"
            : "bg-white border-gray-300 text-title active:border-primary"
        }`}
      >
        <span className="flex flex-col items-start gap-0.5 text-left">
          <span className="text-xs text-subtitle">{label}</span>
          <span className={displayLabel ? "text-title" : "text-gray-400"}>
            {displayLabel || placeholder}
          </span>
        </span>
        <Icon icon="zi-chevron-down" size={18} className="text-subtitle flex-shrink-0" />
      </button>

      <Sheet
        visible={open}
        onClose={handleClose}
        title={typeof label === "string" ? label : undefined}
        maskClosable
      >
        <div className="px-4 pb-4 flex flex-col" style={{ maxHeight: "70vh" }}>
          <div className="py-3">
            <Input
              value={query}
              onChange={(e) => setQuery(e.target.value)}
              placeholder="Tìm kiếm…"
              clearable
              className="w-full"
            />
          </div>
          <div className="overflow-y-auto flex-1 divide-y divide-gray-100">
            {filtered.length === 0 && (
              <p className="py-4 text-center text-sm text-subtitle">
                Không có kết quả
              </p>
            )}
            {filtered.map((opt) => (
              <button
                key={opt.value}
                type="button"
                onClick={() => handleSelect(opt)}
                className={`w-full text-left px-2 py-3 text-sm transition-colors active:bg-gray-50 ${
                  opt.value === value ? "text-primary font-medium" : "text-title"
                }`}
              >
                {opt.label}
                {opt.value === value && (
                  <Icon icon="zi-check" size={16} className="ml-2 inline text-primary" />
                )}
              </button>
            ))}
          </div>
        </div>
      </Sheet>
    </>
  );
}

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
  const userInfoLoadable = useAtomValue(loadableUserInfoState);

  // Địa chỉ
  const cityListLoadable = useAtomValue(loadableCityListState);
  const cityList: CityNode[] =
    cityListLoadable.state === "hasData" ? cityListLoadable.data : [];

  const [province, setProvince] = useState(initial?.province ?? "");
  const [district, setDistrict] = useState(initial?.district ?? "");
  const [realName, setRealName] = useState(initial?.real_name ?? "");
  const [phone, setPhone] = useState(initial?.phone ?? "");

  useEffect(() => {
    if (initial) return;
    if (userInfoLoadable.state !== "hasData" || !userInfoLoadable.data) return;
    if (!realName) setRealName(userInfoLoadable.data.name ?? "");
    if (!phone) setPhone(userInfoLoadable.data.phone ?? "");
  }, [initial, userInfoLoadable, realName, phone]);

  // Các option tỉnh/thành
  const provinceOptions: SelectOption[] = useMemo(
    () => cityList.map((p) => ({ value: p.n, label: p.n })),
    [cityList]
  );

  // Phường/xã phụ thuộc tỉnh được chọn (cấp 2 trong city_list)
  const districtOptions: SelectOption[] = useMemo(() => {
    const found = cityList.find((p) => p.n === province);
    if (!found) return [];
    return found.c.map((d) => ({ value: d.n, label: d.n }));
  }, [cityList, province]);

  const handleProvinceChange = useCallback((val: string) => {
    setProvince(val);
    setDistrict("");
  }, []);

  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    const fd = new FormData(e.currentTarget);
    const real_name = realName.trim();
    const phoneValue = phone.trim();
    const detail = (fd.get("detail") as string)?.trim();
    const districtVal = districtOptions.length
      ? district
      : (fd.get("district_text") as string)?.trim();

    if (!province || !districtVal || !detail || !real_name || !phoneValue) {
      toast.error("Vui lòng điền đầy đủ thông tin");
      return;
    }

    if (!isValidVnPhone(phoneValue)) {
      toast.error(VN_PHONE_ERROR);
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
        address: { province, city: province, district: districtVal },
        real_name,
        post_code: "",
        phone: phoneValue,
        detail,
        is_default: initial ? initial.is_default : true,
        id: initial?.id ?? 0,
        type: 1,
      };

      const res = await client.post<any>("/address/edit", payload);

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
            value={realName}
            onChange={(e) => setRealName(e.target.value)}
          />
          <Input
            name="phone"
            label="Số điện thoại"
            placeholder="0901234567"
            required
            value={phone}
            onChange={(e) => setPhone(e.target.value)}
          />
        </div>

        <div className="bg-section p-4 mt-2 grid gap-4">
          {cityListLoadable.state === "loading" ? (
            <p className="text-sm text-subtitle py-2">Đang tải danh sách tỉnh/thành…</p>
          ) : (
            <>
              <SearchableSelect
                label="Tỉnh / Thành phố"
                placeholder="Chọn tỉnh/thành phố"
                value={province}
                options={provinceOptions}
                onChange={handleProvinceChange}
              />
              {districtOptions.length > 0 ? (
                <SearchableSelect
                  label="Phường / Xã"
                  placeholder={province ? "Chọn phường/xã" : "Chọn tỉnh/thành trước"}
                  value={district}
                  options={districtOptions}
                  onChange={setDistrict}
                  disabled={!province}
                />
              ) : (
                <Input
                  name="district_text"
                  label={<>Phường / Xã <span className="text-danger">*</span></>}
                  placeholder="Tên phường/xã"
                  required
                  defaultValue={initial?.district}
                />
              )}
            </>
          )}

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

  useEffect(() => {
    refreshAddresses();
  }, [refreshAddresses]);

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
          const isSelected =
            selectedId === addr.id || (selectedId === null && addr.is_default === 1);
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
