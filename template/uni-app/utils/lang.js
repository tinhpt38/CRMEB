import Vue from 'vue';
import VueI18n from 'vue-i18n'
import Cache from '@/utils/cache';

Vue.use(VueI18n)

let lang = '';
// #ifdef MP || APP-PLUS
lang = Cache.has('locale') ? Cache.get('locale') : 'zh-CN';
// #endif
// #ifdef H5
lang = Cache.has('locale') ? Cache.get('locale') : navigator.language;
// #endif
const i18n = new VueI18n({
	locale: lang,
	fallbackLocale: 'zh-CN',
	messages: uni.getStorageSync('localeJson'),
	silentTranslationWarn: true, // Xóa cảnh báo quốc tế hóa
})
export default i18n
