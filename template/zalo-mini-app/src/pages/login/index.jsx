/**
 * Trang đăng nhập Zalo Mini App
 *
 * Ví dụ minh họa cách dùng loginWithZalo() và bindPhone()
 */

import React, { useState } from 'react';
import { Page, Box, Button, Input, Text } from 'zmp-ui';
import { navigateTo } from 'zmp-ui';

import { loginWithZalo, getSmsKey, sendOtp, bindPhone } from '@/api/auth';

export default function LoginPage() {
  const [step, setStep]       = useState('zalo');  // 'zalo' | 'bind_phone'
  const [phone, setPhone]     = useState('');
  const [otp, setOtp]         = useState('');
  const [smsKey, setSmsKey]   = useState('');
  const [loading, setLoading] = useState(false);
  const [error, setError]     = useState('');

  // ─── Bước 1: Đăng nhập Zalo ───────────────────────────────────────────────

  async function handleZaloLogin() {
    setLoading(true);
    setError('');
    try {
      const result = await loginWithZalo();

      if (result.userInfo.phone) {
        // Đã có SĐT → vào trang chủ luôn
        navigateTo({ url: '/pages/home/index' });
      } else {
        // Chưa có SĐT → yêu cầu gắn
        setStep('bind_phone');
      }
    } catch (err) {
      setError(typeof err === 'string' ? err : 'Đăng nhập thất bại, vui lòng thử lại');
    } finally {
      setLoading(false);
    }
  }

  // ─── Bước 2a: Gửi OTP ────────────────────────────────────────────────────

  async function handleSendOtp() {
    if (!phone || phone.length < 9) {
      setError('Vui lòng nhập số điện thoại hợp lệ');
      return;
    }
    setLoading(true);
    setError('');
    try {
      const keyData = await getSmsKey();
      setSmsKey(keyData.key);
      await sendOtp({ phone, type: 'bind', key: keyData.key });
      zmp.showToast({ message: 'Đã gửi OTP', type: 'success', duration: 2000 });
    } catch (err) {
      setError(typeof err === 'string' ? err : 'Gửi OTP thất bại');
    } finally {
      setLoading(false);
    }
  }

  // ─── Bước 2b: Xác nhận OTP + gắn SĐT ────────────────────────────────────

  async function handleBindPhone() {
    if (!otp || otp.length !== 6) {
      setError('Vui lòng nhập mã OTP 6 chữ số');
      return;
    }
    setLoading(true);
    setError('');
    try {
      await bindPhone({ phone, captcha: otp });
      zmp.showToast({ message: 'Đăng nhập thành công', type: 'success', duration: 2000 });
      navigateTo({ url: '/pages/home/index' });
    } catch (err) {
      setError(typeof err === 'string' ? err : 'Xác minh OTP thất bại');
    } finally {
      setLoading(false);
    }
  }

  // ─── Render ───────────────────────────────────────────────────────────────

  return (
    <Page>
      <Box
        flexDirection="column"
        alignItems="center"
        justifyContent="center"
        style={{ minHeight: '100vh', padding: '24px' }}
      >
        {step === 'zalo' ? (
          <>
            <Text.Title style={{ marginBottom: 16 }}>Đăng nhập</Text.Title>
            <Text style={{ marginBottom: 32, textAlign: 'center', color: '#666' }}>
              Đăng nhập bằng tài khoản Zalo để tiếp tục mua sắm
            </Text>

            <Button
              fullWidth
              onClick={handleZaloLogin}
              loading={loading}
              style={{ backgroundColor: '#0068ff' }}
            >
              Đăng nhập bằng Zalo
            </Button>
          </>
        ) : (
          <>
            <Text.Title style={{ marginBottom: 8 }}>Xác minh số điện thoại</Text.Title>
            <Text style={{ marginBottom: 24, color: '#666', textAlign: 'center' }}>
              Vui lòng nhập số điện thoại để hoàn tất đăng ký
            </Text>

            <Input
              type="tel"
              placeholder="Số điện thoại"
              value={phone}
              onChange={(e) => setPhone(e.target.value)}
              style={{ width: '100%', marginBottom: 12 }}
            />

            <Box flexDirection="row" style={{ width: '100%', gap: 8, marginBottom: 16 }}>
              <Input
                type="number"
                placeholder="Mã OTP"
                value={otp}
                onChange={(e) => setOtp(e.target.value)}
                style={{ flex: 1 }}
              />
              <Button
                size="small"
                variant="secondary"
                onClick={handleSendOtp}
                loading={loading}
                disabled={!phone}
              >
                Gửi OTP
              </Button>
            </Box>

            <Button
              fullWidth
              onClick={handleBindPhone}
              loading={loading}
              disabled={!otp}
            >
              Xác nhận
            </Button>
          </>
        )}

        {error ? (
          <Text style={{ color: 'red', marginTop: 16, textAlign: 'center' }}>
            {error}
          </Text>
        ) : null}
      </Box>
    </Page>
  );
}
