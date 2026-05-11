import TransitionLink from "@/components/transition-link";
import { useCustomerSupport } from "@/hooks";
import { loadableUserInfoState } from "@/state";
import { useAtomValue } from "jotai";
import { Icon } from "zmp-ui";

type MenuItem = {
  key: string;
  label: string;
  hint?: string;
  to?: string;
  onClick?: () => void;
};

export default function ProfileMenu() {
  const userInfo = useAtomValue(loadableUserInfoState);
  const openSupport = useCustomerSupport();

  if (userInfo.state !== "hasData" || !userInfo.data) {
    return null;
  }

  const items: MenuItem[] = [
    {
      key: "address",
      label: "Địa chỉ nhận hàng",
      hint: "Quản lý địa chỉ giao hàng CRMEB",
      to: "/shipping-address",
    },
    {
      key: "support",
      label: "Hỗ trợ qua Zalo OA",
      hint: "Chat với cửa hàng",
      onClick: openSupport,
    },
  ];

  if (!userInfo.data.phone) {
    items.splice(1, 0, {
      key: "bind-phone",
      label: "Gắn số điện thoại",
      hint: "Lấy từ Zalo, không cần OTP",
      to: "/profile/bind-phone",
    });
  }

  return (
    <div className="bg-white rounded-lg border-[0.5px] border-black/15 overflow-hidden">
      {items.map((item, index) => {
        const content = (
          <>
            <div className="flex-1 min-w-0">
              <div className="text-sm">{item.label}</div>
              {item.hint ? (
                <div className="text-2xs text-subtitle truncate">{item.hint}</div>
              ) : null}
            </div>
            <Icon icon="zi-chevron-right" className="text-subtitle" />
          </>
        );

        const className = `flex items-center gap-3 px-4 py-3 active:bg-section ${
          index > 0 ? "border-t border-black/10" : ""
        }`;

        if (item.to) {
          return (
            <TransitionLink key={item.key} to={item.to} className={className}>
              {content}
            </TransitionLink>
          );
        }

        return (
          <button
            key={item.key}
            type="button"
            className={`${className} w-full text-left`}
            onClick={item.onClick}
          >
            {content}
          </button>
        );
      })}
    </div>
  );
}
