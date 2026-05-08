import { useBindPhone } from "@/hooks";
import { useState } from "react";
import toast from "react-hot-toast";
import { useNavigate } from "react-router-dom";
import { Button } from "zmp-ui";

/**
 * Trang gắn số điện thoại — lấy trực tiếp từ Zalo (không cần OTP).
 *
 * Luồng:
 *  1. User nhấn "Lấy số điện thoại từ Zalo"
 *  2. Zalo hiện hộp thoại xin quyền chia sẻ số điện thoại
 *  3. Sau khi user đồng ý, phone được gắn tự động vào tài khoản CRMEB
 */
export default function BindPhonePage() {
  const navigate = useNavigate();
  const { bindFromZalo } = useBindPhone();
  const [loading, setLoading] = useState(false);

  const handleBind = async () => {
    setLoading(true);
    try {
      await bindFromZalo();
      toast.success("Gắn số điện thoại thành công!");
      navigate(-1);
    } catch (err: any) {
      const msg =
        err?.crmebError?.msg ??
        err?.message ??
        "Không thể lấy số điện thoại từ Zalo";
      toast.error(msg);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-full flex flex-col justify-between p-4 bg-background">
      <div className="space-y-4">
        <p className="text-sm text-subtitle leading-relaxed">
          Gắn số điện thoại Zalo của bạn vào tài khoản để nhận thông báo đơn hàng
          và sử dụng đầy đủ tính năng.
        </p>
        <div className="bg-section rounded-lg p-4 text-sm text-subtitle space-y-1">
          <p>• Zalo sẽ hỏi xác nhận trước khi chia sẻ số điện thoại.</p>
          <p>• Số điện thoại chỉ dùng để liên hệ liên quan đến đơn hàng.</p>
        </div>
      </div>

      <Button fullWidth onClick={handleBind} loading={loading}>
        Lấy số điện thoại từ Zalo
      </Button>
    </div>
  );
}
