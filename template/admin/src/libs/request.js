// +---------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +---------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +---------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +---------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +---------------------------------------------------------------------

import axios from 'axios';
import { Message } from 'element-ui';
import { getCookies, removeCookies } from '@/libs/util';
import Setting from '@/setting';
import router from '@/router';
const service = axios.create({
  baseURL: Setting.apiBaseURL,
  timeout: 100000, // Yêu cầu hết thời gian
});

axios.defaults.withCredentials = true; // mang theo bánh quy

//Yêu cầu chặn
service.interceptors.request.use(
  (config) => {
    if (config.kefu) {
      let baseUrl = Setting.apiBaseURL.replace(/adminapi/, 'kefuapi');
      config.baseURL = baseUrl;
    } else {
      config.baseURL = Setting.apiBaseURL;
    }
    if (config.file) {
      config.headers['Content-Type'] = 'multipart/form-data';
    }
    const token = getCookies('token');
    const kefuToken = getCookies('kefu_token');
    if (token || kefuToken) {
      config.headers['Authori-zation'] = config.kefu ? 'Bearer ' + kefuToken : 'Bearer ' + token;
    }
    return config;
  },
  (error) => {
    // do something with request error
    return Promise.reject(error);
  },
);

// response interceptor
service.interceptors.response.use(
  (response) => {
    let obj = {};
    if (!!response.data) {
      if (typeof response.data == 'string') {
        obj = JSON.parse(response.data);
      } else {
        obj = response.data;
      }
    }
    let status = response.data ? obj.status : 0;
    // let status = response.data ? response.data.status : 0;
    const code = status;
    switch (code) {
      case 200:
        return obj;
      case 401:
        localStorage.clear();
        removeCookies('token');
        removeCookies('expires_time');
        removeCookies('uuid');
        router.replace({ name: 'login' }).catch(() => {});
        return Promise.reject({ msg: 'Chưa đăng nhập' });
      case 402:
        removeCookies('kefuInfo');
        removeCookies('kefu_token');
        removeCookies('kefu_expires_time');
        removeCookies('kefu_uuid');
        router.replace({ path: '/kefu' }).catch(() => {});
        return Promise.reject({ msg: 'Chưa đăng nhập' });
      case 403:
        router.replace({ name: 'system_opendir_login' }).catch(() => {});
        return Promise.reject({ msg: 'sự cho phép bị từ chối' });
      default:
        return Promise.reject(obj || { msg: 'lỗi không xác định' });
    }
  },
  (error) => {
    Message.error(error.msg);
    return Promise.reject(error);
  },
);

export default service;
