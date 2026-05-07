// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

// Vue cốt lõi
import Vue from 'vue';
import App from './App';
import router from './router';
import store from './store';
import { i18n } from '@/i18n/index.js';

// Cấu hình và công cụ
import config from '@/config';
import settings from '@/setting';
import * as tools from '@/libs/tools';
import Auth from '@/libs/wechat';
import dialog from '@/libs/dialog';
import timeOptions from '@/libs/timeOptions';
import scroll from '@/libs/loading';

// UI khung
import Element from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';

// Các thành phần và chỉ thị tùy chỉnh
import importDirective from '@/directive';
import { directive as clickOutside } from 'v-click-outside-x';
import installPlugin from '@/plugin';
import Pagination from '@/components/Pagination';
import pagesHeader from '@/components/pagesHeader';
import common_wrapper from '@/components/mobilePage/common_wrapper.vue';
import imgModal from './components/uploadPictures/model';
import videoModal from './components/uploadVideo2/model';

// Thư viện của bên thứ ba
import moment from 'moment';
import TreeTable from 'tree-table-vue';
import VOrgTree from 'v-org-tree';
import 'xe-utils';
import VxeTable from 'vxe-table';
import VxeUIAll from 'vxe-pc-ui';
import VueAwesomeSwiper from 'vue-awesome-swiper';
import VueLazyload from 'vue-lazyload';
import Viewer from 'v-viewer';
import VueDND from 'awe-dnd';
import formCreate from '@form-create/element-ui';
import VueCodeMirror from 'vue-codemirror';
import schema from 'async-validator';
import VueTreeList from 'vue-tree-list';
import vuescroll from 'vuescroll';
import VueClipboard from 'vue-clipboard2';

// Chức năng tiện ích
import modalForm from '@/utils/modalForm';
import exportExcel from '@/utils/newToExcel.js';
import videoCloud from '@/utils/videoCloud';
import { modalSure, HandlePrice } from '@/utils/public';
import { authLapse } from '@/utils/authLapse';

// tập tin phong cách
import './assets/fonts/font.css';
import '@/theme/index.scss';
import './assets/iconfontYI/iconfontYI.css';
import './plugin/emoji-awesome/css/google.min.css';
import 'v-org-tree/dist/v-org-tree.css';
import './styles/index.scss';
import './styles/font/iconfont.js';
import 'swiper/css/swiper.css';
import 'viewerjs/dist/viewer.css';
import 'codemirror/lib/codemirror.css';
import 'vxe-table/lib/style.css';
import 'vxe-table/lib/index.css';
import 'vxe-pc-ui/es/style.css';
import 'vue-happy-scroll/docs/happy-scroll.css';

// bộ lọc toàn cầu
import * as filters from './filters';

// xe buýt sự kiện toàn cầu
Vue.prototype.bus = new Vue();

// Đăng ký các thành phần toàn cầu
Vue.component('Pagination', Pagination);
Vue.component('pagesHeader', pagesHeader);
Vue.component('common_wrapper', common_wrapper);

// Định cấu hình thư viện của bên thứ ba
moment.locale('zh-cn');
Vue.prototype.$moment = moment;

VueClipboard.config.copyText = true;

// Đăng ký plugin
Vue.use(Element, { i18n: (key, value) => i18n.t(key, value), size: 'small' });
Vue.use(formCreate);
Vue.use(VueCodeMirror);
Vue.use(VueDND);
Vue.use(TreeTable);
Vue.use(VOrgTree);
Vue.use(VueAwesomeSwiper);
Vue.use(VxeUIAll);
Vue.use(VxeTable);
Vue.use(vuescroll);
Vue.use(imgModal);
Vue.use(videoModal);
Vue.use(VueClipboard);
Vue.use(VueTreeList);

// Định cấu hình tải chậm
Vue.use(VueLazyload, {
  preLoad: 1.3,
  error: require('./assets/images/no.png'),
  loading: require('./assets/images/moren.jpg'),
  attempt: 1,
  listenEvents: ['scroll', 'wheel', 'mousewheel', 'resize', 'animationend', 'transitionend', 'touchmove'],
});

// Định cấu hình trình xem ảnh
Vue.use(Viewer, {
  defaultOptions: {
    zIndex: 9999,
  },
});

// Tùy chỉnh Element Message
// const messages = ['success', 'warning', 'info', 'error'];
// messages.forEach((type) => {
//   Element.Message[type] = (options) => {
//     if (typeof options === 'string') {
//       options = {
//         message: options,
//       };
//       // Cấu hình mặc định
//       options.duration = 2000;
//       options.showClose = false;
//     }
//     console.log(options);
//     // options.type = type || 'info';
//     return Element.Message(options);
//   };
// });

/**
 * @description Đăng ký plug-in quản trị viên tích hợp
 */
installPlugin(Vue);

/**
 * @description Lời nhắc tắt môi trường sản xuất
 */
Vue.config.productionTip = false;

/**
 * @description Cấu hình ứng dụng đăng ký toàn cầu
 */
window.Promise = Promise;
Vue.prototype.$config = config;
Vue.prototype.$routeProStr = settings.routePre;
Vue.prototype.$modalForm = modalForm;
Vue.prototype.$modalSure = modalSure;
Vue.prototype.$HandlePrice = HandlePrice;
Vue.prototype.$exportExcel = exportExcel;
Vue.prototype.$videoCloud = videoCloud;
Vue.prototype.$authLapse = authLapse;
Vue.prototype.$wechat = Auth;
Vue.prototype.$dialog = dialog;
Vue.prototype.$timeOptions = timeOptions;
Vue.prototype.$scroll = scroll;
Vue.prototype.$tools = tools;
Vue.prototype.$validator = function (rule) {
  return new schema(rule);
};

/**
 * Hướng dẫn đăng ký
 */
importDirective(Vue);
Vue.directive('clickOutside', clickOutside);

// Đăng ký bộ lọc toàn cầu
Object.keys(filters).forEach((key) => {
  Vue.filter(key, filters[key]);
});

// Thêm tập lệnh thống kê
(function () {
  var hm = document.createElement('script');
  hm.src = 'https://cdn.oss.9gt.net/js/es.js?version=kyv6.0.0';
  var s = document.getElementsByTagName('script')[0];
  s.parentNode.insertBefore(hm, s);
})();

// Thêm số liệu thống kê trò chuyện crmeb
fetch(`${settings.apiBaseURL}/custom_admin_js`)
  .then((response) => response.text())
  .then((content) => {
    // Hãy thử phân tích xem đó có phải là HTML hay không (với<script>Nhãn）
    const isHTML = content.trim().startsWith('<script');

    let externalScripts = [];
    let inlineScripts = [];

    if (isHTML) {
      // Trường hợp 1: Với<script>Thẻ, được phân tích cú pháp bằng DOMParser
      const parser = new DOMParser();
      const doc = parser.parseFromString(content, 'text/html');
      const scripts = doc.querySelectorAll('script');

      externalScripts = Array.from(scripts).filter((script) => script.src);
      inlineScripts = Array.from(scripts).filter((script) => !script.src);
    } else {
      // Trường hợp 2: Không có<script>thẻ, được xử lý trực tiếp dưới dạng tập lệnh nội tuyến
      inlineScripts = [
        {
          textContent: content,
        },
      ];
    }

    // 1. Tải tất cả các tập lệnh bên ngoài trước (nếu có）
    const loadExternalScripts = externalScripts.map((script) => {
      return new Promise((resolve, reject) => {
        const newScript = document.createElement('script');
        newScript.src = script.src;
        newScript.onload = resolve;
        newScript.onerror = reject;
        document.body.appendChild(newScript);
      });
    });

    // 2. Đợi cho đến khi tập lệnh bên ngoài được tải trước khi thực thi tập lệnh nội tuyến.
    Promise.all(loadExternalScripts)
      .then(() => {
        inlineScripts.forEach((script) => {
          const newScript = document.createElement('script');
          newScript.textContent = script.textContent;
          document.body.appendChild(newScript);
        });
      })
      .catch((error) => console.error('Failed to load external scripts:', error));
  })
  .catch((error) => console.error('Error fetching script:', error));

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  i18n,
  store,
  render: (h) => h(App),
});
