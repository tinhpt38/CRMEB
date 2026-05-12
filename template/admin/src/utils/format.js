import { formatDate } from '@/utils/validate';

const DEFAULT_DATETIME_PATTERN = 'dd/MM/yyyy hh:mm:ss';
const DEFAULT_EMPTY_DATETIME = '-';

function toDate(value) {
  if (value === null || value === undefined || value === '') return null;

  if (value instanceof Date) {
    return Number.isNaN(value.getTime()) ? null : value;
  }

  if (typeof value === 'number' && Number.isFinite(value)) {
    const timestamp = value > 1e12 ? value : value * 1000;
    const date = new Date(timestamp);
    return Number.isNaN(date.getTime()) ? null : date;
  }

  const text = String(value).trim();
  if (!text || text === '0') return null;

  const date = new Date(text.includes('-') ? text.replace(/-/g, '/') : text);
  return Number.isNaN(date.getTime()) ? null : date;
}

export function formatDateTime(value, options = {}) {
  const { empty = DEFAULT_EMPTY_DATETIME, pattern = DEFAULT_DATETIME_PATTERN } = options;
  const text = value === null || value === undefined ? '' : String(value).trim();

  if (!text) return empty;
  if (/^chưa/i.test(text)) return text;

  const date = toDate(value);
  if (!date) return text;

  return formatDate(date, pattern);
}

export function formatVnd(value) {
  const num = Number(value || 0);
  if (Number.isNaN(num)) return '--';
  return `${num.toLocaleString('vi-VN')} đ`;
}
