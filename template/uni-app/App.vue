<script>
import { HTTP_REQUEST_URL } from "./config/app";
import {
  getShopConfig,
  silenceAuth,
  getSystemVersion,
  basicConfig,
  remoteRegister,
} from "@/api/public";
import Auth from "@/libs/wechat.js";
import Routine from "./libs/routine.js";
import { silenceBindingSpread } from "@/utils";
import { getCrmebCopyRight, getThemeInfo } from "@/api/api.js";
import { getLangJson, getLangVersion } from "@/api/user.js";
import { mapGetters } from "vuex";
import colors from "@/mixins/color.js";
import Cache from "@/utils/cache";
import { debug } from "util";
import { applyTheme } from "@/utils/theme.js";

export default {
  globalData: {
    spid: 0,
    code: 0,
    isLogin: false,
    userInfo: {},
    MyMenus: [],
    globalData: false,
    isIframe: false,
    tabbarShow: true,
    windowHeight: 0,
    locale: "",
  },
  mixins: [colors],
  computed: mapGetters(["isLogin", "cartNum"]),
  watch: {
    isLogin: {
      deep: true, //Giám sát độ sâu được đặt thành true
      handler: function (newV, oldV) {
        if (newV) {
          // this.getCartNum()
        } else {
          this.$store.commit("indexData/setCartNum", "");
        }
      },
    },
    cartNum(newCart, b) {
      this.$store.commit("indexData/setCartNum", newCart + "");
      if (newCart > 0) {
        uni.setTabBarBadge({
          index: Number(uni.getStorageSync("FOOTER_ADDCART")) || 2,
          text: newCart + "",
        });
      } else {
        uni.hideTabBarRedDot({
          index: Number(uni.getStorageSync("FOOTER_ADDCART")) || 2,
        });
      }
    },
  },
  onShow() {
    const queryData = uni.getEnterOptionsSync(); // uni-appHỗ trợ phiên bản 3.5.1+
    if (queryData.query.spread) {
      this.$Cache.set("spread", queryData.query.spread);
      this.globalData.spid = queryData.query.spread;
      this.globalData.pid = queryData.query.spread;
      silenceBindingSpread(this.globalData);
    }
    if (queryData.query.spid) {
      this.$Cache.set("spread", queryData.query.spid);
      this.globalData.spid = queryData.query.spid;
      this.globalData.pid = queryData.query.spid;
      silenceBindingSpread(this.globalData);
    }
    if (queryData.query.agent_id) {
      this.$Cache.set("agent_id", queryData.query.agent_id);
      this.globalData.agent_id = queryData.query.agent_id;
      silenceBindingSpread(this.globalData);
    }
    // #ifdef MP
    if (queryData.query.scene) {
      let param = this.$util.getUrlParams(
        decodeURIComponent(queryData.query.scene)
      );
      if (param.pid) {
        this.$Cache.set("spread", param.pid);
        this.globalData.spid = param.pid;
        this.globalData.pid = param.pid;
      } else {
        switch (queryData.scene) {
          //Quét mã chương trình mini
          case 1047:
            this.globalData.code = queryData.query.scene;
            break;
          //Nhấn và giữ mã applet nhận dạng hình ảnh
          case 1048:
            this.globalData.code = queryData.query.scene;
            break;
          //Mã applet lựa chọn album điện thoại di động
          case 1049:
            this.globalData.code = queryData.query.scene;
            break;
          //Nhập trực tiếp chương trình mini
          case 1001:
            this.globalData.spid = queryData.query.scene;
            break;
        }
      }
      silenceBindingSpread(this.globalData);
    }
    // #endif
  },
  async onLaunch(option) {
    uni.hideTabBar();
    let that = this;
    basicConfig().then((res) => {
      uni.setStorageSync("BASIC_CONFIG", res.data);
    });
    // #ifdef H5
    if (
      option.query.hasOwnProperty("mdType") &&
      option.query.mdType == "iframeWindow"
    ) {
      this.globalData.isIframe = true;
    } else {
      this.globalData.isIframe = false;
    }
    if (!this.isLogin && option.query.hasOwnProperty("remote_token")) {
      this.remoteRegister(option.query.remote_token);
    }
    // #endif
    let previewThemeId = uni.getStorageSync("previewThemeId");
    applyTheme(previewThemeId);
    getLangVersion().then((res) => {
      let version = res.data.version;
      if (version != uni.getStorageSync("LANG_VERSION")) {
        getLangJson().then((res) => {
          let value = Object.keys(res.data)[0];
          Cache.set("locale", Object.keys(res.data)[0]);
          this.$i18n.setLocaleMessage(value, res.data[value]);
          uni.setStorageSync("localeJson", res.data);
        });
      }
      uni.setStorageSync("LANG_VERSION", version);
    });

    // #ifdef APP-PLUS || H5
    uni.getSystemInfo({
      success: function (res) {
        // Nếu trang chủ không có tiêu đề thì sẽ lấy được chiều cao của toàn bộ trang. Nếu các trang bên trong có tiêu đề gốc thì chiều cao của khung nhìn phải giảm xuống.
        // Thanh trạng thái động và có thể truy cập được. Thanh tiêu đề được cố định và mã hóa cứng.44px
        let height = res.windowHeight - res.statusBarHeight - 44;
        // #ifdef H5 || APP-PLUS
        that.globalData.windowHeight = res.windowHeight + "px";
        // #endif
        // // #ifdef APP-PLUS
        // that.globalData.windowHeight = height + 'px'
        // // #endif
      },
    });
    // #endif
    // #ifdef MP
    if (HTTP_REQUEST_URL == "") {
      console.error(
        "Vui lòng định cấu hình tệp config.js trong thư mục gốc 'HTTP_REQUEST_URL'\n\nVui lòng sửa đổi [Chi tiết trong công cụ dành cho nhà phát triển】->【AppID】Thay đổi nó thành của riêng bạnAppid\n\nVui lòng chuyển đến phần phụ trợ [Chương trình nhỏ]】->【Cấu hình chương trình nhỏ] Điền thông tin của riêng bạn appId and AppSecret"
      );
      return false;
    }

    const updateManager = wx.getUpdateManager();
    const startParamObj = wx.getEnterOptionsSync();
    if (wx.canIUse("getUpdateManager") && startParamObj.scene != 1154) {
      const updateManager = wx.getUpdateManager();
      updateManager.onCheckForUpdate(function (res) {
        if (res.hasUpdate) {
          updateManager.onUpdateFailed(function () {
            return that.Tips({
              title: "Tải xuống phiên bản mới không thành công",
            });
          });
          updateManager.onUpdateReady(function () {
            wx.showModal({
              title: "Cập nhật mẹo",
              content: "Phiên bản mới đã được tải xuống. Bạn có muốn khởi động lại ứng dụng hiện tại không?？",
              success(res) {
                if (res.confirm) {
                  updateManager.applyUpdate();
                }
              },
            });
          });
          updateManager.onUpdateFailed(function () {
            wx.showModal({
              title: "phiên bản mới được tìm thấy",
              content: "Vui lòng xóa applet hiện tại và khởi động lại tìm kiếm để mở nó....",
            });
          });
        }
      });
    }
    // #endif
    // Nhận chiều cao điều hướng；
    uni.getSystemInfo({
      success: function (res) {
        that.globalData.navHeight =
          res.statusBarHeight * (750 / res.windowWidth) + 91;
      },
    });
    // #ifdef MP
    let menuButtonInfo = uni.getMenuButtonBoundingClientRect();
    that.globalData.navH = menuButtonInfo.top * 2 + menuButtonInfo.height / 2;
    const version = uni.getSystemInfoSync().SDKVersion;
    if (Routine.compareVersion(version, "2.21.3") >= 0) {
      that.$Cache.set("MP_VERSION_ISNEW", true);
    } else {
      that.$Cache.set("MP_VERSION_ISNEW", false);
    }
    // #endif

    // #ifdef MP
    // Ủy quyền im lặng chương trình nhỏ
    // if (!this.$store.getters.isLogin) {
    // 	Routine.getCode()
    // 		.then(code => {
    // 			this.silenceAuth(code);
    // 		})
    // 		.catch(res => {
    // 			uni.hideLoading();
    // 		});
    // }
    // #endif
    // #ifdef H5
    // Thêm số liệu thống kê trò chuyện crmeb
    // var __s = document.createElement('script');
    // __s.src = `${HTTP_REQUEST_URL}/api/get_script`;
    // document.head.appendChild(__s);

    fetch(`${HTTP_REQUEST_URL}/api/get_script`)
      .then((response) => response.text())
      .then((content) => {
        // Hãy thử phân tích xem đó có phải là HTML hay không (với<script>Nhãn）
        const isHTML = content.trim().startsWith("<script");

        let externalScripts = [];
        let inlineScripts = [];

        if (isHTML) {
          // Trường hợp 1: Với<script>Thẻ, được phân tích cú pháp bằng DOMParser
          const parser = new DOMParser();
          const doc = parser.parseFromString(content, "text/html");
          const scripts = doc.querySelectorAll("script");

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
            const newScript = document.createElement("script");
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
              const newScript = document.createElement("script");
              newScript.textContent = script.textContent;
              document.body.appendChild(newScript);
            });
          })
          .catch((error) =>
            console.error("Failed to load external scripts:", error)
          );
      })
      .catch((error) => console.error("Error fetching script:", error));

    // #endif
    getCrmebCopyRight().then((res) => {
      uni.setStorageSync("copyRight", res.data);
    });
  },
  onHide() {
    // #ifdef H5
    this.$Cache.clear("snsapiKey");
    // #endif
    this.$Cache.clear("previewThemeId");
  },
  methods: {
    remoteRegister(remote_token) {
      remoteRegister({
        remote_token,
      }).then((res) => {
        let data = res.data;
        if (data.get_remote_login_url) {
          location.href = data.get_remote_login_url;
        } else {
          this.$store.commit("LOGIN", {
            token: data.token,
            time: data.expires_time - this.$Cache.time(),
          });
          this.$store.commit("SETUID", data.userInfo.uid);
          location.reload();
        }
      });
    },
    // Ủy quyền im lặng chương trình nhỏ
    // silenceAuth(code) {
    // 	let that = this;
    // 	let spread = that.globalData.spid ? that.globalData.spid : '';
    // 	silenceAuth({
    // 			code: code,
    // 			spread_spid: spread,
    // 			spread_code: that.globalData.code
    // 		})
    // 		.then(res => {
    // 			if (res.data.token !== undefined && res.data.token) {
    // 				uni.hideLoading();
    // 				let time = res.data.expires_time - this.$Cache.time();
    // 				that.$store.commit('LOGIN', {
    // 					token: res.data.token,
    // 					time: time
    // 				});
    // 				that.$store.commit('SETUID', res.data.userInfo.uid);
    // 				that.$store.commit('UPDATE_USERINFO', res.data.userInfo);
    // 			}
    // 		})
    // 		.catch(res => {});
    // },
  },
};
</script>

<style>
@import url("@/plugin/emoji-awesome/css/tuoluojiang.css");
@import url("@/plugin/animate/animate.min.css");
@import "static/css/base.css";
@import "static/iconfont/iconfont.css";
@import "static/css/guildford.css";
@import "static/css/style.scss";
@import "static/css/unocss.css";
@import "static/fonts/font.scss";

view {
  box-sizing: border-box;
}

page {
  font-family: "Google Sans", "Product Sans", system-ui, -apple-system,
    BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
}

.bg-color-red {
  background-color: var(--view-theme) !important;
}

.syspadding {
  padding-top: var(--status-bar-height);
}

.flex {
  display: flex;
}

.uni-scroll-view::-webkit-scrollbar {
  /* Ẩn thanh cuộn nhưng vẫn có chức năng cuộn */
  display: none;
}

::-webkit-scrollbar {
  width: 0;
  height: 0;
  color: transparent;
}

.uni-system-open-location .map-content.fix-position {
  height: 100vh;
  top: 0;
  bottom: 0;
}

.open-location {
  width: 100%;
  height: 100vh;
}
</style>
