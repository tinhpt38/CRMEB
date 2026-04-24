// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2024 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

import {
	HTTP_REQUEST_URL,
	HEADER,
	TOKENNAME,
	TIMEOUT
} from '@/config/app';
import {
	toLogin,
	checkLogin
} from '../libs/login';
import store from '../store';
import i18n from './lang.js';

function normalizeAssetDomain(payload) {
	// #ifdef H5
	const fromHosts = ['https://demo.crmeb.com', 'http://demo.crmeb.com'];
	const targetHost = window.location.protocol + '//' + window.location.host;

	const walk = (val) => {
		if (typeof val === 'string') {
			let next = val;
			for (let i = 0; i < fromHosts.length; i++) {
				if (next.indexOf(fromHosts[i]) > -1) {
					next = next.replace(new RegExp(fromHosts[i], 'g'), targetHost);
				}
			}
			return next;
		}
		if (Array.isArray(val)) return val.map(walk);
		if (val && typeof val === 'object') {
			const out = {};
			Object.keys(val).forEach((k) => {
				out[k] = walk(val[k]);
			});
			return out;
		}
		return val;
	};
	return walk(payload);
	// #endif
	// #ifndef H5
	return payload;
	// #endif
}

/**
 * Gửi yêu cầu
 */
function baseRequest(url, method, data, {
	noAuth = false,
	noVerify = false
}) {
	let Url = HTTP_REQUEST_URL,
		header = HEADER;

	if (!noAuth) {
		//Đăng nhập tự động sau khi hết hạn đăng nhập
		if (!store.state.app.token && !checkLogin()) {
			toLogin();
			return Promise.reject({
				msg: i18n.t(`Chưa đăng nhập`)
			});
		}
	}
	if (store.state.app.token) header[TOKENNAME] = 'Bearer ' + store.state.app.token;

	return new Promise((reslove, reject) => {
		if (uni.getStorageSync('locale')) {
			header['Cb-lang'] = uni.getStorageSync('locale')
		}
		uni.request({
			url: Url + '/api/' + url,
			method: method || 'GET',
			header: header,
			data: data || {},
			timeout: TIMEOUT,
			success: (res) => {
				if (res && res.data) {
					res.data = normalizeAssetDomain(res.data);
				}
				if (noVerify)
					reslove(res.data, res);
				else if (res.data.status == 200)
					reslove(res.data, res);
				else if (res.data.status == 401) {
					toLogin();
					reject(res.data);
				} else if (res.data.status == 402) {
					uni.showModal({
						title: i18n.t(`gợi ý`),
						content: res.data.msg,
						showCancel: false,
						confirmText: i18n.t(`tôi hiểu rồi`)
					});
				} else
					reject(res.data.msg || i18n.t(`Lỗi hệ thống`));
			},
			fail: (msg) => {
				let data = {
					mag: i18n.t(`Yêu cầu không thành công`),
					status: 1 //1Không có mạng
				}
				// #ifdef APP-PLUS
				reject(data);
				// #endif
				// #ifndef APP-PLUS
				reject(i18n.t(`Yêu cầu không thành công`));
				// #endif
			}
		})
	});
}

const request = {};

['options', 'get', 'post', 'put', 'head', 'delete', 'trace', 'connect'].forEach((method) => {
	request[method] = (api, data, opt) => baseRequest(api, method, data, opt || {})
});



export default request;