import CONFIG from "@/config";
import { userInfoKeyState, userInfoState } from "@/state";
import { CrmebApiClient } from "@/utils/crmeb/client";
import { getCrmebToken } from "@/utils/crmeb/token";
import { getConfig } from "@/utils/template";
import { useAtomValue, useSetAtom } from "jotai";
import { FormEvent, useState } from "react";
import toast from "react-hot-toast";
import { useNavigate } from "react-router-dom";
import { Button, Input } from "zmp-ui";

function ProfileEditorPage() {
  const navigate = useNavigate();
  const userInfo = useAtomValue(userInfoState);
  const setUserInfoKey = useSetAtom(userInfoKeyState);
  const refreshUserInfo = () => setUserInfoKey((key) => key + 1);
  const [saving, setSaving] = useState(false);
  const apiUrl = getConfig((config) => config.template.apiUrl);

  const handleSubmit = async (e: FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    const data = new FormData(e.currentTarget);
    const nickname = String(data.get("name") ?? "").trim();
    const email = String(data.get("email") ?? "").trim();
    const address = String(data.get("address") ?? "").trim();

    if (!userInfo) {
      toast.error("Chưa có thông tin tài khoản");
      return;
    }

    setSaving(true);
    try {
      if (apiUrl && getCrmebToken()) {
        const client = new CrmebApiClient({
          apiBaseUrl: apiUrl,
          getToken: () => getCrmebToken(),
        });
        await client.post("/user/edit", {
          nickname,
          avatar: userInfo.avatar,
        });
      }

      const newUserInfo = {
        ...userInfo,
        name: nickname || userInfo.name,
        email,
        address,
      };
      localStorage.setItem(
        CONFIG.STORAGE_KEYS.USER_INFO,
        JSON.stringify(newUserInfo)
      );
      refreshUserInfo();
      toast.success("Đã cập nhật thông tin tài khoản");
      navigate(-1);
    } catch (error: any) {
      const msg =
        error?.crmebError?.msg ??
        error?.message ??
        "Không thể lưu thông tin tài khoản";
      toast.error(msg);
    } finally {
      setSaving(false);
    }
  };

  return (
    <form
      className="h-full flex flex-col justify-between"
      onSubmit={handleSubmit}
    >
      <div className="bg-section p-4 grid gap-4">
        <Input name="name" label="Họ tên" defaultValue={userInfo?.name} />
        <Input
          name="phone"
          label="Số điện thoại"
          placeholder="Lấy tự động từ Zalo"
          defaultValue={userInfo?.phone}
          readOnly
        />
        <Input
          name="email"
          label="Email"
          placeholder="Email (hiển thị cục bộ)"
          defaultValue={userInfo?.email}
        />
        <Input
          name="address"
          label="Địa chỉ"
          placeholder="Địa chỉ mặc định (hiển thị cục bộ)"
          defaultValue={userInfo?.address}
        />
        <p className="text-2xs text-subtitle leading-relaxed">
          Họ tên được lưu lên CRMEB. Số điện thoại gắn qua Zalo; địa chỉ giao
          hàng quản lý tại mục Địa chỉ nhận hàng.
        </p>
      </div>
      <div className="p-6 pt-4 bg-section">
        <Button htmlType="submit" fullWidth loading={saving}>
          Lưu thay đổi
        </Button>
      </div>
    </form>
  );
}

export default ProfileEditorPage;
