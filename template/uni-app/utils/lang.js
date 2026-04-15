import Vue from 'vue';
import VueI18n from 'vue-i18n'
import Cache from '@/utils/cache';

Vue.use(VueI18n)

const fallbackMessages = {
	'zh-CN': {
		mobile: {
			login: {
				phonePlaceholder: '输入手机号码',
				passwordPlaceholder: '填写登录密码',
				captchaPlaceholder: '填写验证码',
				submit: '登录',
				quickLogin: '快速登录',
				accountLogin: '账号登录',
				otherMethods: '其他方式登录',
				readAndAgree: '已阅读并同意',
				readAndAgreeFirst: '请先阅读并同意协议',
				userAgreement: '《用户协议》',
				privacyAgreement: '《隐私协议》',
				and: '与',
				phoneRequired: '请填写手机号码',
				phoneInvalid: '请输入正确的手机号码',
				captchaRequired: '请填写验证码',
				captchaInvalid: '请输入正确的验证码',
				accountRequired: '请填写账号',
				accountInvalid: '请输入正确的账号',
				passwordRequired: '请填写密码',
				passwordTooSimple: '您输入的密码过于简单',
				duplicateClick: '请勿重复点击',
				loading: '登录中',
				getUserInfoFailed: '获取用户信息失败',
				loginFailed: '登录失败',
				tipTitle: '提示',
				bindPhoneFirst: '请绑定手机号后，继续操作',
				errorInfoPrefix: '错误信息：',
				confirmLog: '用户点击确定',
				cancelLog: '用户点击取消',
				copyrightDefault: 'Copyright ©2024 CRMEB. All Rights'
			}
		},
		app: {
			configMissing:
				"请配置根目录下的config.js文件中的 'HTTP_REQUEST_URL'\\n\\n请修改开发者工具中【详情】->【AppID】改为自己的Appid\\n\\n请前往后台【小程序】->【小程序配置】填写自己的 appId and AppSecret",
			updateDownloadFailed: '新版本下载失败',
			updateTipTitle: '更新提示',
			updateRestartContent: '新版本已经下载好，是否重启当前应用？',
			newVersionFound: '发现新版本',
			deleteAndRestart: '请删除当前小程序，重启搜索打开...'
		}
	},
	'en-US': {
		mobile: {
			login: {
				phonePlaceholder: 'Enter phone number',
				passwordPlaceholder: 'Enter password',
				captchaPlaceholder: 'Enter verification code',
				submit: 'Login',
				quickLogin: 'Quick login',
				accountLogin: 'Account login',
				otherMethods: 'Other login methods',
				readAndAgree: 'I have read and agree to',
				readAndAgreeFirst: 'Please read and agree to the agreement first',
				userAgreement: 'User Agreement',
				privacyAgreement: 'Privacy Agreement',
				and: 'and',
				phoneRequired: 'Please enter phone number',
				phoneInvalid: 'Please enter a valid phone number',
				captchaRequired: 'Please enter verification code',
				captchaInvalid: 'Please enter a valid verification code',
				accountRequired: 'Please enter account',
				accountInvalid: 'Please enter a valid account',
				passwordRequired: 'Please enter password',
				passwordTooSimple: 'Password is too simple',
				duplicateClick: 'Please do not click repeatedly',
				loading: 'Logging in',
				getUserInfoFailed: 'Failed to get user info',
				loginFailed: 'Login failed',
				tipTitle: 'Notice',
				bindPhoneFirst: 'Please bind your phone number first to continue',
				errorInfoPrefix: 'Error: ',
				confirmLog: 'User clicked confirm',
				cancelLog: 'User clicked cancel',
				copyrightDefault: 'Copyright ©2024 CRMEB. All Rights'
			}
		},
		app: {
			configMissing:
				"Please configure 'HTTP_REQUEST_URL' in config.js under the project root.\\n\\nPlease update your AppID in Developer Tools: [Details] -> [AppID].\\n\\nThen configure appId and AppSecret in Admin: [Mini Program] -> [Mini Program Config].",
			updateDownloadFailed: 'Failed to download new version',
			updateTipTitle: 'Update notice',
			updateRestartContent: 'A new version is ready. Restart the app now?',
			newVersionFound: 'New version found',
			deleteAndRestart: 'Please delete the current mini program and reopen it.'
		}
	},
	'vi-VN': {
		mobile: {
			login: {
				phonePlaceholder: 'Nhap so dien thoai',
				passwordPlaceholder: 'Nhap mat khau dang nhap',
				captchaPlaceholder: 'Nhap ma xac minh',
				submit: 'Dang nhap',
				quickLogin: 'Dang nhap nhanh',
				accountLogin: 'Dang nhap tai khoan',
				otherMethods: 'Cach dang nhap khac',
				readAndAgree: 'Toi da doc va dong y',
				readAndAgreeFirst: 'Vui long doc va dong y dieu khoan truoc',
				userAgreement: 'Thoa thuan nguoi dung',
				privacyAgreement: 'Chinh sach rieng tu',
				and: 'va',
				phoneRequired: 'Vui long nhap so dien thoai',
				phoneInvalid: 'So dien thoai khong hop le',
				captchaRequired: 'Vui long nhap ma xac minh',
				captchaInvalid: 'Ma xac minh khong hop le',
				accountRequired: 'Vui long nhap tai khoan',
				accountInvalid: 'Tai khoan khong hop le',
				passwordRequired: 'Vui long nhap mat khau',
				passwordTooSimple: 'Mat khau qua don gian',
				duplicateClick: 'Vui long khong bam lap lai',
				loading: 'Dang dang nhap',
				getUserInfoFailed: 'Khong lay duoc thong tin nguoi dung',
				loginFailed: 'Dang nhap that bai',
				tipTitle: 'Thong bao',
				bindPhoneFirst: 'Vui long lien ket so dien thoai de tiep tuc',
				errorInfoPrefix: 'Loi: ',
				confirmLog: 'Nguoi dung bam dong y',
				cancelLog: 'Nguoi dung bam huy',
				copyrightDefault: 'Copyright ©2024 CRMEB. All Rights'
			}
		},
		app: {
			configMissing:
				"Vui long cau hinh 'HTTP_REQUEST_URL' trong tep config.js tai thu muc goc.\\n\\nHay cap nhat AppID trong cong cu phat trien: [Details] -> [AppID].\\n\\nSau do cau hinh appId va AppSecret trong trang quan tri: [Mini Program] -> [Mini Program Config].",
			updateDownloadFailed: 'Tai phien ban moi that bai',
			updateTipTitle: 'Thong bao cap nhat',
			updateRestartContent: 'Phien ban moi da tai xong, ban co muon khoi dong lai ung dung khong?',
			newVersionFound: 'Da phat hien phien ban moi',
			deleteAndRestart: 'Vui long xoa mini program hien tai va mo lai.'
		}
	}
};

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
	messages: Object.assign({}, fallbackMessages, uni.getStorageSync('localeJson') || {}),
	silentTranslationWarn: true, // 去除国际化警告
})
export default i18n
