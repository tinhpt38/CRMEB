const PAY_METHOD_LABELS = {
  COD: 'COD',
  BANK_TRANSFER: 'Chuyển khoản ngân hàng',
  OTHER: 'Thanh toán khác',
};

export function parsePayMethodMark(mark) {
  const text = String(mark || '').trim();
  const match = text.match(/PAY_METHOD:\s*([A-Z_]+)/i);
  if (!match) return '';
  const code = match[1].toUpperCase();
  return PAY_METHOD_LABELS[code] || code.replace(/_/g, ' ');
}

export function getBuyerMessage(mark) {
  const text = String(mark || '').trim();
  if (!text) return '';
  return text.replace(/PAY_METHOD:\s*[A-Z_]+/gi, '').trim();
}

export function getOrderMarkSection(mark) {
  const paymentText = parsePayMethodMark(mark);
  const buyerMessage = getBuyerMessage(mark);

  if (paymentText) {
    return {
      visible: true,
      title: 'Phương thức thanh toán',
      value: paymentText,
    };
  }

  if (buyerMessage) {
    return {
      visible: true,
      title: 'Tin nhắn của người mua',
      value: buyerMessage,
    };
  }

  return {
    visible: false,
    title: '',
    value: '',
  };
}
