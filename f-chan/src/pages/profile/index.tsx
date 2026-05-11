import ProfileActions from "./actions";
import FollowOA from "./follow-oa";
import PhoneBanner from "./phone-banner";
import Points from "./points";
import ProfileMenu from "./menu";
import UserInfo from "./user-info";

export default function ProfilePage() {
  return (
    <div className="min-h-full bg-background p-4 space-y-2.5">
      <UserInfo>
        <PhoneBanner />
        <Points />
      </UserInfo>
      <ProfileMenu />
      <ProfileActions />
      <FollowOA />
    </div>
  );
}
