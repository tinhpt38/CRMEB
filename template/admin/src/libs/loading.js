// +---------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +---------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +---------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +---------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +---------------------------------------------------------------------

const events = [];

const $scroll = function (dom, fn) {
  events.push({ dom, fn });
  fn._index = events.length - 1;
};

$scroll.remove = function (fn) {
  fn._index && events.splice(fn._index, 1);
};

//tải kéo lên；
const Scroll = {
  addHandler: function (element, type, handler) {
    if (element.addEventListener) element.addEventListener(type, handler, false);
    else if (element.attachEvent) element.attachEvent('on' + type, handler);
    else element['on' + type] = handler;
  },
  listenTouchDirection: function () {
    this.addHandler(window, 'scroll', function () {
      const wh = window.innerHeight,
        st = window.scrollY;
      events
        .filter((e) => e.dom.scrollHeight && e.dom.scrollHeight > 0)
        .forEach((e) => {
          var dh = e.dom.scrollHeight;
          var s = Math.ceil((st / (dh - wh)) * 100);
          if (s > 85) e.fn();
        });
    });
  },
};

Scroll.listenTouchDirection();

export default $scroll;
export { Scroll };
