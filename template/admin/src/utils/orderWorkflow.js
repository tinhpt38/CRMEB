function normalizePayType(payType) {
  const raw = String(payType || '').toLowerCase();
  if (raw === 'cod' || raw === 'vn-cod') return 'vn_cod';
  if (raw === 'bank' || raw === 'vn-bank') return 'vn_bank';
  return raw;
}

function paymentStepLabel(payType) {
  switch (normalizePayType(payType)) {
    case 'vn_cod':
      return 'Xác nhận COD';
    case 'vn_bank':
      return 'Xác nhận CK';
    case 'offline':
      return 'Xác nhận TT';
    default:
      return 'Thanh toán';
  }
}

function resolveActiveStepIndex(row) {
  const paid = Number(row.paid) === 1;
  const status = Number(row.status);
  const refundStatus = Number(row.refund_status || 0);
  const shippingType = Number(row.shipping_type);

  if ([1, 3, 4].includes(refundStatus)) return 2;
  if (!paid) return 1;
  if (status === 0) return shippingType === 2 ? 3 : 2;
  if (status === 4 || status === 1) return 3;
  if (status === 2) return 4;
  if (status === 3) return 5;
  return 2;
}

function buildStandardSteps(row) {
  const shippingType = Number(row.shipping_type);
  return [
    { label: 'Đặt hàng' },
    { label: paymentStepLabel(row.pay_type) },
    { label: 'Chuẩn bị hàng' },
    { label: shippingType === 2 ? 'Nhận tại cửa hàng' : 'Giao hàng' },
    { label: 'Hoàn tất' },
  ];
}

function markStepStates(steps, activeIndex) {
  return steps.map((step, index) => {
    if (activeIndex >= steps.length) {
      return { ...step, state: 'done' };
    }
    if (index < activeIndex) {
      return { ...step, state: 'done' };
    }
    if (index === activeIndex) {
      return { ...step, state: 'current' };
    }
    return { ...step, state: 'pending' };
  });
}

export function buildOrderWorkflowSteps(row = {}) {
  const isCancel = Number(row.is_cancel) === 1;
  const isDel = Number(row.is_del) === 1;
  const refundStatus = Number(row.refund_status || 0);

  if (isCancel) {
    return [
      { label: 'Đặt hàng', state: 'done' },
      { label: 'Đã hủy', state: 'error' },
    ];
  }

  if (isDel) {
    return [
      { label: 'Đặt hàng', state: 'done' },
      { label: 'Đã xóa', state: 'error' },
    ];
  }

  if (refundStatus === 2) {
    return [
      { label: 'Đặt hàng', state: 'done' },
      { label: paymentStepLabel(row.pay_type), state: 'done' },
      { label: 'Đã hoàn tiền', state: 'error' },
    ];
  }

  if (refundStatus === 1) {
    return [
      { label: 'Đặt hàng', state: 'done' },
      { label: paymentStepLabel(row.pay_type), state: 'done' },
      { label: 'Đang hoàn tiền', state: 'current' },
    ];
  }

  const steps = buildStandardSteps(row);
  const activeIndex = resolveActiveStepIndex(row);
  return markStepStates(steps, activeIndex);
}
