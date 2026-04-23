// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2024 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

// #ifdef H5
import WechatJSSDK from "@/plugin/jweixin-module/index.js";


import {
	getWechatConfig,
	wechatAuth,
	getShopConfig,
	wechatAuthV2
} from "@/api/public";
import {
	WX_AUTH,
	STATE_KEY,
	LOGINTYPE,
	BACK_URL
} from '@/config/cache';
import {
	parseQuery
} from '@/utils';
import store from '@/store';
import Cache from '@/utils/cache';

class AuthWechat {

	constructor() {
		//Đối tượng khởi tạo WeChat
		this.instance = WechatJSSDK;
		//Có nên khởi tạo hay không
		this.status = false;

		this.initConfig = {};

	}

	isAndroid() {
		let u = navigator.userAgent;
		return u.indexOf('Android') > -1 || u.indexOf('Adr') > -1;
	}

	signLink() {
		// if (typeof window.entryUrl === 'undefined' || window.entryUrl === '') {
		// 	window.entryUrl = encodeURI(window.location.href)
		// }
		return /(Android)/i.test(navigator.userAgent) ? document.location.href : window.entryUrl;
	}

	/**
	 * khởi tạowechat(Chia sẻ cấu hình)
	 */
	wechat() {
		return new Promise((resolve, reject) => {
			// if (this.status && !this.isAndroid()) return resolve(this.instance);
			getWechatConfig()
				.then(res => {
					this.instance.config(res.data);
					this.initConfig = res.data;
					this.status = true;
					this.instance.ready(() => {
						resolve(this.instance);
					})
				}).catch(err => {
					this.status = false;
					reject(err);
				});
		});
	}

	/**
	 * Xác minh xem nó có được khởi tạo hay không
	 */
	verifyInstance() {
		let that = this;
		return new Promise((resolve, reject) => {
			if (that.instance === null && !that.status) {
				that.wechat().then(res => {
					resolve(that.instance);
				}).catch(() => {
					return reject();
				})
			} else {
				return resolve(that.instance);
			}
		})
	}
	// Địa chỉ dùng chung của tài khoản chính thức WeChat
	openAddress() {
		return new Promise((resolve, reject) => {
			this.wechat().then(wx => {
				this.toPromise(wx.openAddress).then(res => {
					resolve(res);
				}).catch(err => {
					reject(err);
				});
			}).catch(err => {
				reject(err);
			})
		});
	}

	// Nhận vĩ độ và kinh độ；
	location() {
		return new Promise((resolve, reject) => {
			this.wechat().then(wx => {
				this.toPromise(wx.getLocation, {
					type: 'wgs84'
				}).then(res => {
					resolve(res);
				}).catch(err => {
					reject(err);
				});
			}).catch(err => {
				reject(err);
			})
		});
	}

	// Sử dụng bản đồ tích hợp của WeChat để xem giao diện vị trí；
	seeLocation(config) {
		return new Promise((resolve, reject) => {
			this.wechat().then(wx => {
				this.toPromise(wx.openLocation, config).then(res => {
					resolve(res);
				}).catch(err => {
					reject(err);
				});
			}).catch(err => {
				reject(err);
			})
		});
	}

	/**
	 * WeChat trả tiền
	 * @param {Object} config
	 */
	pay(config) {
		return new Promise((resolve, reject) => {
			this.wechat().then((wx) => {
				this.toPromise(wx.chooseWXPay, config).then(res => {
					resolve(res);
				}).catch(res => {
					reject(res);
				});
			}).catch(res => {
				reject(res);
			});
		});
	}

	toPromise(fn, config = {}) {
		return new Promise((resolve, reject) => {
			fn({
				...config,
				success(res) {
					resolve(res);
				},
				fail(err) {
					reject(err);
				},
				complete(err) {
					reject(err);
				},
				cancel(err) {
					reject(err);
				}
			});
		});
	}

	/**
	 * Tự động hủy cấp phép
	 */
	oAuth(snsapiBase, url) {
		// if (uni.getStorageSync('authIng')) return

		if (uni.getStorageSync(WX_AUTH) && store.state.app.token && snsapiBase == 'snsapi_base') return;
		let {
			code
		} = parseQuery();
		let snsapiCode = uni.getStorageSync('snsapiCode')
		if (code instanceof Array) {
			code = code[code.length - 1]
		}
		if (snsapiCode instanceof Array) {
			snsapiCode = snsapiCode[snsapiCode.length - 1]
		}
		if (!code || code == snsapiCode) {
			uni.setStorageSync('authIng', true)
			return this.toAuth(snsapiBase, url);
		} else {
			// if(Cache.has('snsapiKey'))
			// 	return this.auth(code).catch(error=>{
			// 		uni.showToast({
			// 			title:error,
			// 			icon:'none'
			// 		})
			// 	})
		}
	}

	clearAuthStatus() {

	}

	/**
	 * Quyền truy cập đăng nhập được ủy quyềntoken
	 * @param {Object} code
	 */
	auth(code) {
		return new Promise((resolve, reject) => {
			let loginType = Cache.get(LOGINTYPE);
			wechatAuthV2(code, parseInt(Cache.get("spread")))
				.then(({
					data
				}) => {
					// store.commit("LOGIN", {
					// 	token: data.token,
					// 	time: Cache.strTotime(data.expires_time) - Cache.time()
					// });
					// store.commit("SETUID", data.userInfo.uid);
					Cache.set(WX_AUTH, code);
					Cache.clear(STATE_KEY);
					resolve(data);
				})
				.catch(reject);
		});
	}

	/**
	 * Nhận địa chỉ sau khi ủy quyền nhảy
	 * @param {Object} appId
	 */
	getAuthUrl(appId, snsapiBase, backUrl) {
		// if (backUrl) {
		// 	let backUrlArr = backUrl.split('&');
		// 	let newUrlArr = backUrlArr.filter(item => {
		// 		if (item.indexOf('code=') === -1) {
		// 			return item;
		// 		}
		// 	});
		// 	backUrl = newUrlArr.join('&');
		// }

		// let url = `${location.origin}${backUrl}`
		// if (url.indexOf('?') === -1) {
		// 	url = url + '?'
		// } else {
		// 	url = url + '&'
		// }
		const redirect_uri = encodeURIComponent(location.href);
		const state = Cache.get('login_back_url') || '/pages/user/index';
		// uni.setStorageSync(STATE_KEY, state);
		uni.removeStorageSync(BACK_URL);
		if (snsapiBase === 'snsapi_base') {
			return `https://open.weixin.qq.com/connect/oauth2/authorize?appid=${appId}&redirect_uri=${redirect_uri}&response_type=code&scope=snsapi_base&state=${state}&connect_redirect=1#wechat_redirect`;
		} else {
			return `https://open.weixin.qq.com/connect/oauth2/authorize?appid=${appId}&redirect_uri=${redirect_uri}&response_type=code&scope=snsapi_userinfo&state=${state}&connect_redirect=1#wechat_redirect`;
		}

	}

	/**
	 * Chuyển đến đăng nhập tự động
	 */
	toAuth(snsapiBase, backUrl) {
		let that = this;
		this.wechat().then(wx => {
			location.href = this.getAuthUrl(that.initConfig.appId, snsapiBase, backUrl);
		})
	}

	/**
	 * Sự kiện ràng buộc
	 * @param {Object} name tên sự kiện
	 * @param {Object} config tham số
	 */
	wechatEvevt(name, config) {
		let that = this;
		return new Promise((resolve, reject) => {
			let configDefault = {
				fail(res) {
					if (that.instance) return reject({
						is_ready: true,
						wx: that.instance
					});
					that.verifyInstance().then(wx => {
						return reject({
							is_ready: true,
							wx: wx
						});
					})
				},
				success(res) {
					return resolve(res, 2222);
				}
			};
			Object.assign(configDefault, config);
			that.wechat().then(wx => {
				if (typeof name === 'object') {
					name.forEach(item => {
						wx[item] && wx[item](configDefault)
					})
				} else {
					wx[name] && wx[name](configDefault)
				}
			})
		});
	}


	isWeixin() {
		return navigator.userAgent.toLowerCase().indexOf("micromessenger") !== -1;
	}

}

export default new AuthWechat();
// #endif
