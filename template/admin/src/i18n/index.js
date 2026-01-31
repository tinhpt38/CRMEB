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
import store from '@/store/index.js';

import nextZhcn from '@/i18n/lang/zh-cn.js';
import nextEn from '@/i18n/lang/en.js';
import nextZhtw from '@/i18n/lang/zh-tw.js';
import nextViVn, { menuTitlesViVn } from '@/i18n/lang/vi-vn.js';

import pagesHomeZhcn from '@/i18n/pages/home/zh-cn.js';
import pagesHomeEn from '@/i18n/pages/home/en.js';
import pagesHomeZhtw from '@/i18n/pages/home/zh-tw.js';
import pagesHomeViVn from '@/i18n/pages/home/vi-vn.js';
import pagesLoginZhcn from '@/i18n/pages/login/zh-cn.js';
import pagesLoginEn from '@/i18n/pages/login/en.js';
import pagesLoginZhtw from '@/i18n/pages/login/zh-tw.js';
import pagesLoginViVn from '@/i18n/pages/login/vi-vn.js';
// 使用插件
Vue.use(VueI18n);

// 定义语言国际化内容
/**
 * 说明：
 * /src/i18n/lang 下的 js 为框架的国际化内容
 * /src/i18n/pages 下的 js 为各界面的国际化内容
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
  'vi-vn': {
    ...enLocale,
    message: {
      ...nextViVn,
      ...pagesHomeViVn,
      ...pagesLoginViVn,
    },
    // Bản dịch tên menu sidebar (backend trả về tiếng Trung → hiển thị tiếng Việt)
    ...menuTitlesViVn,
  },
};

// 导出语言国际化
export const i18n = new VueI18n({
  locale: store.state.themeConfig.themeConfig.globalI18n,
  fallbackLocale: 'en',
  messages,
  silentTranslationWarn: true, // 去除国际化警告
});

/**
 * i18n locale -> HTML lang (BCP 47)，trình duyệt nhận đúng ngôn ngữ trước，tránh Google tự dịch
 */
export function localeToHtmlLang(locale) {
  const map = { en: 'en', 'zh-cn': 'zh-CN', 'zh-tw': 'zh-Hant', 'vi-vn': 'vi' };
  return map[locale] || 'en';
}
