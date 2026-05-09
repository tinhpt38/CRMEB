import ProfileActions from "./actions";
import FollowOA from "./follow-oa";
import Points from "./points";
import UserInfo from "./user-info";

// Số điện thoại được tự động lấy từ Zalo và bind qua /zalo/bind_phone_direct.
// Không hiển thị banner "Chưa có SĐT" — người dùng không cần tự thao tác.

export default function ProfilePage() {
  return (
    <div className="min-h-full bg-background p-4 space-y-2.5">
      <UserInfo>
        <Points />
      </UserInfo>
      <ProfileActions />
      <FollowOA />
    </div>
  );
}
