import config from '../../package.json';

// 1、window.localStorage Bộ đệm vĩnh viễn của trình duyệt
export const Local = {
  // Xem nhật ký cập nhật phiên bản v2.4.3
  setKey(key) {
    // @ts-ignore
    return `${config.name}:${key}`;
  },
  // Thiết lập bộ đệm vĩnh viễn
  set(key, val) {
    window.localStorage.setItem(Local.setKey(key), JSON.stringify(val));
  },
  // Nhận bộ đệm vĩnh viễn
  get(key) {
    let json = window.localStorage.getItem(Local.setKey(key));
    return JSON.parse(json);
  },
  // Xóa bộ nhớ đệm liên tục
  remove(key) {
    window.localStorage.removeItem(Local.setKey(key));
  },
  // Xóa tất cả bộ đệm vĩnh viễn
  clear() {
    window.localStorage.clear();
  },
};

// 2、window.sessionStorage Bộ đệm tạm thời của trình duyệt
export const Session = {
  // Thiết lập bộ đệm tạm thời
  set(key, val) {
    window.sessionStorage.setItem(Local.setKey(key), JSON.stringify(val));
  },
  // Nhận bộ đệm tạm thời
  get(key) {
    let json = window.sessionStorage.getItem(Local.setKey(key));
    return JSON.parse(json);
  },
  // Xóa bộ đệm tạm thời
  remove(key) {
    window.sessionStorage.removeItem(Local.setKey(key));
  },
  // Xóa tất cả bộ đệm tạm thời
  clear() {
    window.sessionStorage.clear();
  },
};
