import ProfileActions from "./actions";
import FollowOA from "./follow-oa";
import Points from "./points";
import UserInfo from "./user-info";
import { loadableUserInfoState } from "@/state";
import { useAtomValue } from "jotai";
import TransitionLink from "@/components/transition-link";
import { Icon } from "zmp-ui";

function BindPhoneBanner() {
  const userInfo = useAtomValue(loadableUserInfoState);
  if (userInfo.state !== "hasData" || !userInfo.data) return null;
  if (userInfo.data.phone) return null;

  return (
    <TransitionLink
      to="/profile/bind-phone"
      className="flex items-center justify-between bg-yellow-50 border border-yellow-300 rounded-lg p-3"
    >
      <div className="flex items-center space-x-2">
        <Icon icon="zi-warning" className="text-yellow-500" />
        <div>
          <div className="text-sm font-medium text-yellow-800">Chưa có số điện thoại</div>
          <div className="text-xs text-yellow-600">Gắn số để nhận OTP và hoàn tất đơn hàng</div>
        </div>
      </div>
      <Icon icon="zi-chevron-right" className="text-yellow-500" />
    </TransitionLink>
  );
}

export default function ProfilePage() {
  return (
    <div className="min-h-full bg-background p-4 space-y-2.5">
      <UserInfo>
        <Points />
      </UserInfo>
      <BindPhoneBanner />
      <ProfileActions />
      <FollowOA />
    </div>
  );
}
