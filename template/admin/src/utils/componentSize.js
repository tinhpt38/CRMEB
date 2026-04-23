import { Local } from '@/utils/storage.js';

// kích thước thành phần toàn cầu
export const globalComponentSize = Local.get('themeConfigPrev')
  ? Local.get('themeConfigPrev').globalComponentSize
  : 'small';
