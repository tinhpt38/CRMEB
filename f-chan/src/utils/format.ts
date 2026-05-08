export function formatPrice(price: number) {
  return `${new Intl.NumberFormat("vi-VN", {
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  }).format(price)}đ`;
}

export function formatDistant(value: number) {
  return `${new Intl.NumberFormat("vi-VN", {
    minimumFractionDigits: 0,
    maximumFractionDigits: 1,
  }).format(value)} km`;
}
