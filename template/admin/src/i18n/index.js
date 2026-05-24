/*
 * @Author: From-wh from-wh@hotmail.com
 * @Date: 2023-03-09 18:02:23
 * @FilePath: /admin/src/i18n/index.js
 * @Description:
 */
import Vue from 'vue';
import VueI18n from 'vue-i18n';
import zhcnLocale from 'element-ui/lib/locale/lang/zh-CN';
import enLocale from 'element-ui/lib/locale/lang/en';
import zhtwLocale from 'element-ui/lib/locale/lang/zh-TW';
import viLocale from 'element-ui/lib/locale/lang/vi';
import store from '@/store/index.js';

import nextVi from '@/i18n/lang/vi.js';
import nextEn from '@/i18n/lang/en.js';
import nextZhtw from '@/i18n/lang/zh-tw.js';
import nextZhcn from '@/i18n/lang/zh-cn.js';

import pagesHomeVi from '@/i18n/pages/home/vi.js';
import pagesHomeEn from '@/i18n/pages/home/en.js';
import pagesHomeZhtw from '@/i18n/pages/home/zh-tw.js';
import pagesHomeZhcn from '@/i18n/pages/home/zh-cn.js';
import pagesLoginVi from '@/i18n/pages/login/vi.js';
import pagesLoginEn from '@/i18n/pages/login/en.js';
import pagesLoginZhtw from '@/i18n/pages/login/zh-tw.js';
import pagesLoginZhcn from '@/i18n/pages/login/zh-cn.js';
// Sử dụng plugin
Vue.use(VueI18n);

// Xác định nội dung quốc tế hóa ngôn ngữ
/**
 * Mô tả:
 * JS trong /src/i18n/lang là nội dung quốc tế của framework
 * JS trong /src/i18n/pages là nội dung quốc tế của từng giao diện
 */
const messages = {
  'zh-cn': {
    ...zhcnLocale,
    message: {
      ...nextZhcn,
      ...pagesHomeZhcn,
      ...pagesLoginZhcn,
    },
  },
  en: {
    ...enLocale,
    message: {
      ...nextEn,
      ...pagesHomeEn,
      ...pagesLoginEn,
    },
  },
  'zh-tw': {
    ...zhtwLocale,
    message: {
      ...nextZhtw,
      ...pagesHomeZhtw,
      ...pagesLoginZhtw,
    },
  },
  vi: {
    ...viLocale,
    message: {
      ...nextVi,
      ...pagesHomeVi,
      ...pagesLoginVi,
    },
  },
  'vi-vn': {
    ...viLocale,
    message: {
      ...nextVi,
      ...pagesHomeVi,
      ...pagesLoginVi,
    },
  },
};

// Xuất khẩu quốc tế hóa ngôn ngữ
export const i18n = new VueI18n({
  locale: store.state.themeConfig.themeConfig.globalI18n,
  fallbackLocale: 'vi-vn',
  messages,
  silentTranslationWarn: true, // Xóa cảnh báo quốc tế hóa
});
