import { useBindPhone } from "@/hooks";
import { useState } from "react";
import toast from "react-hot-toast";
import { useNavigate } from "react-router-dom";
import { Button, Input } from "zmp-ui";

/**
 * Trang gắn số điện thoại 2 bước:
 *  Bước 1 – Nhập số điện thoại → gửi OTP qua SMS
 *  Bước 2 – Nhập mã OTP → xác nhận & gắn vào tài khoản
 */
export default function BindPhonePage() {
  const navigate = useNavigate();
  const { sendOtp, verifyAndBind } = useBindPhone();

  const [step, setStep] = useState<"phone" | "otp">("phone");
  const [phone, setPhone] = useState("");
  const [otp, setOtp] = useState("");
  const [loading, setLoading] = useState(false);
  const [countdown, setCountdown] = useState(0);

  const startCountdown = () => {
    setCountdown(60);
    const timer = setInterval(() => {
      setCountdown((c) => {
        if (c <= 1) {
          clearInterval(timer);
          return 0;
        }
        return c - 1;
      });
    }, 1000);
  };

  const handleSendOtp = async () => {
    if (!phone.trim()) {
      toast.error("Vui lòng nhập số điện thoại");
      return;
    }
    setLoading(true);
    try {
      await sendOtp(phone.trim());
      toast.success("Mã OTP đã được gửi đến " + phone);
      setStep("otp");
      startCountdown();
    } catch (err: any) {
      toast.error(err?.crmebError?.msg ?? err?.message ?? "Không thể gửi OTP");
    } finally {
      setLoading(false);
    }
  };

  const handleResend = async () => {
    if (countdown > 0) return;
    setLoading(true);
    try {
      await sendOtp(phone.trim());
      toast.success("Đã gửi lại OTP");
      startCountdown();
    } catch (err: any) {
      toast.error(err?.crmebError?.msg ?? err?.message ?? "Không thể gửi OTP");
    } finally {
      setLoading(false);
    }
  };

  const handleVerify = async () => {
    if (!otp.trim()) {
      toast.error("Vui lòng nhập mã OTP");
      return;
    }
    setLoading(true);
    try {
      await verifyAndBind(phone.trim(), otp.trim());
      toast.success("Gắn số điện thoại thành công!");
      navigate(-1);
    } catch (err: any) {
      toast.error(err?.crmebError?.msg ?? err?.message ?? "Mã OTP không đúng hoặc đã hết hạn");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-full flex flex-col justify-between p-4 bg-background">
      <div className="space-y-4">
        <p className="text-sm text-subtitle">
          {step === "phone"
            ? "Nhập số điện thoại để nhận mã xác minh qua SMS."
            : `Nhập mã OTP vừa được gửi đến ${phone}.`}
        </p>

        {step === "phone" ? (
          <div className="bg-section rounded-lg p-4">
            <Input
              label="Số điện thoại"
              placeholder="0901234567"
              value={phone}
              onChange={(e) => setPhone(e.target.value)}
              type="tel"
            />
          </div>
        ) : (
          <div className="bg-section rounded-lg p-4 space-y-3">
            <Input
              label="Mã OTP"
              placeholder="6 chữ số"
              value={otp}
              onChange={(e) => setOtp(e.target.value)}
              type="number"
              maxLength={6}
            />
            <button
              className={`text-xs ${countdown > 0 ? "text-subtitle" : "text-primary"}`}
              onClick={handleResend}
              disabled={countdown > 0 || loading}
            >
              {countdown > 0 ? `Gửi lại sau ${countdown}s` : "Gửi lại OTP"}
            </button>
          </div>
        )}
      </div>

      <div className="space-y-2">
        {step === "phone" ? (
          <Button fullWidth onClick={handleSendOtp} loading={loading}>
            Gửi mã OTP
          </Button>
        ) : (
          <>
            <Button fullWidth onClick={handleVerify} loading={loading}>
              Xác nhận
            </Button>
            <Button
              fullWidth
              type="neutral"
              onClick={() => { setStep("phone"); setOtp(""); }}
              disabled={loading}
            >
              Đổi số điện thoại
            </Button>
          </>
        )}
      </div>
    </div>
  );
}
