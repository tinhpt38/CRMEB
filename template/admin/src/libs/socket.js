// +---------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +---------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +---------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +---------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +---------------------------------------------------------------------

import { wss, getCookies, setCookies } from '@/libs/util';
import Setting from '@/setting';
import { getWorkermanUrl } from '@/api/kefu';
import Vue from 'vue';
const vm = new Vue();
let wsAdminSocketUrl = getCookies('WS_ADMIN_URL') || '';
let wsKefuSocketUrl = getCookies('WS_CHAT_URL') || '';
const createEmptySocket = () => ({
  send() {
    return Promise.resolve({ status: false });
  },
  $on() {},
  $off() {},
});

class wsSocket {
  constructor(opt) {
    this.ws = null;
    this.opt = opt || {};
    this.init(opt.key);
  }

  onOpen(key = false) {
    this.opt.open && this.opt.open();
    let that = this;
    // this.send({
    //     type: 'login',
    //     data: util.cookies.get('token')
    // }).then(() => {
    //     that.ping();
    // });
    that.ping();
    this.socketStatus = true;
  }

  init(key) {
    let wsUrl = '';
    if (key == 1) {
      wsUrl = wsAdminSocketUrl;
    }
    if (key == 2) {
      wsUrl = wsKefuSocketUrl;
    }
    if (wsUrl) {
      this.ws = new WebSocket(wsUrl);
      this.ws.onopen = this.onOpen.bind(this);
      this.ws.onerror = this.onError.bind(this);
      this.ws.onmessage = this.onMessage.bind(this);
      this.ws.onclose = this.onClose.bind(this);
    }
  }

  ping() {
    var that = this;
    this.timer = setInterval(function () {
      that.send({ type: 'ping' });
    }, 10000);
  }

  send(data) {
    return new Promise((resolve, reject) => {
      try {
        this.ws.send(JSON.stringify(data));
        resolve({ status: true });
      } catch (e) {
        reject({ status: false });
      }
    });
  }

  onMessage(res) {
    this.opt.message && this.opt.message(res);
  }

  onClose() {
    this.timer && clearInterval(this.timer);
    this.opt.close && this.opt.close();
  }

  onError(e) {
    this.opt.error && this.opt.error(e);
  }

  $on(...args) {
    vm.$on(...args);
  }

  $off(...args) {
    vm.$off(...args);
  }
}

function createSocket(key) {
  return getWorkermanUrl()
    .then((res) => {
      wsAdminSocketUrl = res.data.admin || wsAdminSocketUrl;
      wsKefuSocketUrl = res.data.chat || wsKefuSocketUrl;
      wsAdminSocketUrl && setCookies('WS_ADMIN_URL', wsAdminSocketUrl);
      wsKefuSocketUrl && setCookies('WS_CHAT_URL', wsKefuSocketUrl);
    })
    .catch(() => {
      // Dùng URL cache local nếu API lấy workerman lỗi.
    })
    .then(
      () =>
        new Promise((resolve) => {
          const ws = new wsSocket({
            key,
            open() {
              resolve(ws);
              vm.$emit('socket_open', key);
            },
            error(e) {
              // Tránh Promise reject toàn cục làm vỡ trang khi WS không khả dụng.
              vm.$emit('socket_error', { e, key });
              resolve(createEmptySocket());
            },
            message(res) {
              try {
                const { type, data = {} } = JSON.parse(res.data);
                vm.$emit(type, data);
              } catch (e) {
                vm.$emit('socket_parse_error', { e, key, raw: res.data });
              }
            },
            close(e) {
              vm.$emit('close', { e, key });
            },
          });
          if (!ws.ws) resolve(createEmptySocket());
        })
    );
}

export const adminSocket = createSocket(1);
export const Socket = createSocket(2);
