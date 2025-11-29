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

import nextZhcn from '@/i18n/lang/zh-cn.js';
import nextEn from '@/i18n/lang/en.js';
import nextZhtw from '@/i18n/lang/zh-tw.js';
import nextVi from '@/i18n/lang/vi.js';

import pagesHomeZhcn from '@/i18n/pages/home/zh-cn.js';
import pagesHomeEn from '@/i18n/pages/home/en.js';
import pagesHomeZhtw from '@/i18n/pages/home/zh-tw.js';
import pagesHomeVi from '@/i18n/pages/home/vi.js';
import pagesLoginZhcn from '@/i18n/pages/login/zh-cn.js';
import pagesLoginEn from '@/i18n/pages/login/en.js';
import pagesLoginZhtw from '@/i18n/pages/login/zh-tw.js';
import pagesLoginVi from '@/i18n/pages/login/vi.js';
import pagesSystemZhcn from '@/i18n/pages/system/zh-cn.js';
import pagesSystemEn from '@/i18n/pages/system/en.js';
import pagesSystemZhtw from '@/i18n/pages/system/zh-tw.js';
import pagesSystemVi from '@/i18n/pages/system/vi.js';
import pagesFinanceZhcn from '@/i18n/pages/finance/zh-cn.js';
import pagesFinanceEn from '@/i18n/pages/finance/en.js';
import pagesFinanceZhtw from '@/i18n/pages/finance/zh-tw.js';
import pagesFinanceVi from '@/i18n/pages/finance/vi.js';
import pagesOrderZhcn from '@/i18n/pages/order/zh-cn.js';
import pagesOrderEn from '@/i18n/pages/order/en.js';
import pagesOrderZhtw from '@/i18n/pages/order/zh-tw.js';
import pagesOrderVi from '@/i18n/pages/order/vi.js';
import pagesProductZhcn from '@/i18n/pages/product/zh-cn.js';
import pagesProductEn from '@/i18n/pages/product/en.js';
import pagesProductZhtw from '@/i18n/pages/product/zh-tw.js';
import pagesProductVi from '@/i18n/pages/product/vi.js';
import pagesCommonZhcn from '@/i18n/pages/common/zh-cn.js';
import pagesCommonEn from '@/i18n/pages/common/en.js';
import pagesCommonZhtw from '@/i18n/pages/common/zh-tw.js';
import pagesCommonVi from '@/i18n/pages/common/vi.js';
import pagesSettingZhcn from '@/i18n/pages/setting/zh-cn.js';
import pagesSettingEn from '@/i18n/pages/setting/en.js';
import pagesSettingZhtw from '@/i18n/pages/setting/zh-tw.js';
import pagesSettingVi from '@/i18n/pages/setting/vi.js';
import viExtra from '@/i18n/lang/vi-extra.json';
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
      ...pagesSystemZhcn,
      ...pagesFinanceZhcn,
      ...pagesOrderZhcn,
      ...pagesProductZhcn,
      ...pagesCommonZhcn,
      ...pagesSettingZhcn,
    },
  },
  en: {
    ...enLocale,
    message: {
      ...nextEn,
      ...pagesHomeEn,
      ...pagesLoginEn,
      ...pagesSystemEn,
      ...pagesFinanceEn,
      ...pagesOrderEn,
      ...pagesProductEn,
      ...pagesCommonEn,
      ...pagesSettingEn,
    },
  },
  'zh-tw': {
    ...zhtwLocale,
    message: {
      ...nextZhtw,
      ...pagesHomeZhtw,
      ...pagesLoginZhtw,
      ...pagesSystemZhtw,
      ...pagesFinanceZhtw,
      ...pagesOrderZhtw,
      ...pagesProductZhtw,
      ...pagesCommonZhtw,
      ...pagesSettingZhtw,
    },
  },
  vi: {
    ...viLocale,
    message: {
      ...nextVi,
      ...pagesHomeVi,
      ...pagesLoginVi,
      ...pagesSystemVi,
      ...pagesFinanceVi,
      ...pagesOrderVi,
      ...pagesProductVi,
      ...pagesCommonVi,
      ...pagesSettingVi,
      ...viExtra,
    },
  },
};

// 导出语言国际化
export const i18n = new VueI18n({
  locale: store.state.themeConfig.themeConfig.globalI18n,
  fallbackLocale: 'zh-cn',
  messages,
  silentTranslationWarn: true, // 去除国际化警告
});
