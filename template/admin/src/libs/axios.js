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
import store from '@/store';
const addErrorLog = (errorInfo) => {
  const {
    statusText,
    status,
    request: { responseURL },
  } = errorInfo;
  let info = {
    type: 'ajax',
    code: status,
    mes: statusText,
    url: responseURL,
  };
  if (!responseURL.includes('save_error_logger')) store.dispatch('addErrorLog', info);
};

class HttpRequest {
  constructor(baseUrl = baseURL) {
    this.baseUrl = 'http://admin.crmeb.net/adminapi';
    this.queue = {};
  }
  getInsideConfig() {
    const config = {
      baseURL: this.baseUrl,
      headers: {
        //
      },
    };
    return config;
  }
  destroy(url) {
    delete this.queue[url];
    if (!Object.keys(this.queue).length) {
    }
  }
  interceptors(instance, url) {
    // yêu cầu chặn
    instance.interceptors.request.use(
      (config) => {
        // Thêm toàn cầuloading...
        if (!Object.keys(this.queue).length) {
          // Spin.show() // Không nên kích hoạt nó vì giao diện không thân thiện.
        }
        this.queue[url] = true;
        return config;
      },
      (error) => {
        return Promise.reject(error);
      },
    );
    // chặn phản ứng
    instance.interceptors.response.use(
      (res) => {
        this.destroy(url);
        const { data, status } = res;
        return { data, status };
      },
      (error) => {
        this.destroy(url);
        let errorInfo = error.response;
        if (!errorInfo) {
          const {
            request: { statusText, status },
            config,
          } = JSON.parse(JSON.stringify(error));
          errorInfo = {
            statusText,
            status,
            request: { responseURL: config.url },
          };
        }
        addErrorLog(errorInfo);
        return Promise.reject(error);
      },
    );
  }
  request(options) {
    const instance = axios.create();
    options = Object.assign(this.getInsideConfig(), options);
    this.interceptors(instance, options.url);
    return instance(options);
  }
}
export default HttpRequest;
