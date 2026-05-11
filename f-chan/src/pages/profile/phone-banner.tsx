import TransitionLink from "@/components/transition-link";
import { loadableUserInfoState } from "@/state";
import { CrmebApiClient } from "@/utils/crmeb/client";
import { getCrmebToken } from "@/utils/crmeb/token";
import { getConfig } from "@/utils/template";
import { useAtomValue } from "jotai";
import { useEffect, useState } from "react";
import { Icon } from "zmp-ui";

export default function PhoneBanner() {
  const userInfo = useAtomValue(loadableUserInfoState);
  const apiUrl = getConfig((config) => config.template.apiUrl);
  const [bindPhoneRequired, setBindPhoneRequired] = useState(false);

  useEffect(() => {
    if (!apiUrl) return;

    const client = new CrmebApiClient({
      apiBaseUrl: apiUrl,
      getToken: () => getCrmebToken(),
    });

    client
      .get<{ zalo_bind_phone?: number | boolean }>("/basic_config")
      .then((data) => {
        setBindPhoneRequired(!!data?.zalo_bind_phone);
      })
      .catch(() => {
        setBindPhoneRequired(false);
      });
  }, [apiUrl]);

  if (userInfo.state !== "hasData" || !userInfo.data || userInfo.data.phone) {
    return null;
  }

  return (
    <TransitionLink
      to="/profile/bind-phone"
      className="block rounded-lg border border-amber-200 bg-amber-50 p-3 active:bg-amber-100"
    >
      <div className="flex items-start gap-2">
        <Icon icon="zi-call" className="text-amber-600 mt-0.5" />
        <div className="flex-1 space-y-0.5">
          <div className="text-sm font-medium text-amber-900">
            {bindPhoneRequired
              ? "Cần gắn số điện thoại Zalo"
              : "Gắn số điện thoại Zalo"}
          </div>
          <div className="text-2xs text-amber-800 leading-relaxed">
            {bindPhoneRequired
              ? "Shop yêu cầu xác minh SĐT trước khi dùng đầy đủ tính năng."
              : "Giúp nhận thông báo đơn hàng và đồng bộ tài khoản CRMEB."}
          </div>
        </div>
        <Icon icon="zi-chevron-right" className="text-amber-700" />
      </div>
    </TransitionLink>
  );
}
