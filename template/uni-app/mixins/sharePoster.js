// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2021 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------
import { imageBase64 } from "@/api/public";
import {
  getProductCode, // Applet hàng hóa thông thườngcode
} from "@/api/store.js";
import {
  scombinationCode, // Chia sẻ nhómcode
  seckillCode, // bán chớp nhoáng
} from "@/api/activity.js";
import i18n from "../utils/lang.js";
let sysHeight = uni.getWindowInfo().statusBarHeight + "px";
export const sharePoster = {
  data() {
    return {
      //Thông số mã QR
      codeShow: false,
      cid: "1",
      codeVal: "", // Giá trị mã QR sẽ được tạo
      size: 200, // Kích thước mã QR
      unit: "upx", // đơn vị
      background: "#FFF", // màu nền
      foreground: "#000", // màu nền trước
      pdground: "#000", // Màu nhân vật
      codeIcon: "", // Biểu tượng mã QR
      iconsize: 40, // Kích thước biểu tượng mã QR
      lv: 3, // Mức độ chấp nhận lỗi mã QR, nói chung không cần đặt, mặc định là ổn
      onval: true, // valTự động tạo lại mã QR khi giá trị thay đổi
      loadMake: true, // Sau khi thành phần được tải, mã QR sẽ được tạo tự động.
      base64Show: 0,
      shareQrcode: 0,
      followCode: "",
      selectSku: {},
      currentPage: false,
      sysHeight: sysHeight,
      isShow: 0,
      storeImageBase64: "",
    };
  },
  methods: {
    qrR(res) {
      // #ifdef H5
      if (!this.$wechat.isWeixin() || this.shareQrcode != "1") {
        this.PromotionCode = res;
        this.followCode = "";
      }
      // #endif
      // #ifdef APP-PLUS
      this.PromotionCode = res;
      // #endif
    },
    getImageBase64() {
      let that = this;
      imageBase64(that.storeImage, this.storeInfo.wechat_code)
        .then((res) => {
          that.storeImageBase64 = res.data.image;
          if (this.storeInfo.wechat_code) {
            that.PromotionCode = res.data.code;
          }
        })
        .catch(() => {});
    },
    initPoster(arr2) {
      let that = this;
      uni.getImageInfo({
        src: that.PromotionCode,
        success() {
          if (arr2[2] == "") {
            //Nếu mã QR của người đăng không tồn tại, hãy tải lại.
            that.downloadFilePromotionCode(function (msgPromotionCode) {
              arr2[2] = msgPromotionCode;
              if (arr2[2] == "")
                return that.$util.Tips({
                  title: i18n.t(`Việc tạo mã QR áp phích không thành công`),
                });
              that.$util.PosterCanvas(
                arr2,
                that.storeInfo.store_name,
                that.storeInfo.price,
                that.storeInfo.ot_price,
                function (tempFilePath) {
                  that.$set(that, "posterImage", tempFilePath);
                  that.$set(that, "posterImageStatus", true);
                  that.$set(that, "canvasStatus", false);
                  that.$set(that, "actionSheetHidden", !that.actionSheetHidden);
                }
              );
            });
          } else {
            //Tạo áp phích quảng cáo
            that.$nextTick((e) => {
              that.$util.PosterCanvas(
                arr2,
                that.storeInfo.store_name,
                that.storeInfo.price,
                that.storeInfo.ot_price,
                function (tempFilePath) {
                  that.$set(that, "posterImage", tempFilePath);
                  that.$set(that, "posterImageStatus", true);
                  that.$set(that, "canvasStatus", false);
                  that.$set(that, "actionSheetHidden", !that.actionSheetHidden);
                }
              );
            });
          }
        },
        fail: function (res) {
          // #ifdef H5
          return that.$util.Tips({
            title: res,
          });
          // #endif
          // #ifdef MP
          return that.$util.Tips({
            title: i18n.t(`Đang tải áp phích,Vui lòng thử lại sau`),
          });
          // #endif
        },
      });
    },
    /**
     * Tạo áp phích
     */
    async goPoster(type) {
      let that = this;
      that.posters = false;
      that.$set(that, "canvasStatus", true);
      let arr2;
      // #ifdef MP
      let met =
        type === "scombination"
          ? scombinationCode(that.id)
          : type === "seckill"
          ? seckillCode(that.id, { time_id: this.time_id })
          : getProductCode(that.id);
      met
        .then((res) => {
          uni.downloadFile({
            url: that.setDomain(res.data.code),
            success: function (res) {
              that.$set(that, "isDown", false);
              that.$set(that, "PromotionCode", res.tempFilePath);
              if (typeof successFn == "function")
                successFn && successFn(res.tempFilePath);
              arr2 = [that.posterbackgd, that.storeImage, that.PromotionCode];
              that.initPoster(arr2);
            },
            fail: function () {
              that.$set(that, "isDown", false);
              that.$set(that, "PromotionCode", "");
            },
          });
        })
        .catch((err) => {
          that.$set(that, "isDown", false);
          that.$set(that, "PromotionCode", "");
          return that.$util.Tips({
            title: err,
          });
        });
      // #endif
      // #ifdef H5 || APP-PLUS
      arr2 = [that.posterbackgd, that.storeImageBase64, that.PromotionCode];
      if (!that.storeImageBase64)
        return that.$util.Tips({
          title: i18n.t(`Đang tải áp phích,Vui lòng thử lại sau`),
        });
      that.initPoster(arr2);
      // #endif
    },
    //Thay thế tên miền an toàn
    setDomain(url) {
      url = url ? url.toString() : "";
      //Đã bật gỡ lỗi cục bộ,Vui lòng đăng xuất để sản xuất
      if (url.indexOf("https://") > -1) return url;
      else return url.replace("http://", "https://");
    },
    //Nhận hình ảnh sản phẩm áp phích
    downloadFilestoreImage() {
      let that = this;
      uni.downloadFile({
        url: that.setDomain(that.storeInfo.image),
        success: function (res) {
          that.storeImage = res.tempFilePath;
          that.storeImageBase64 = res.tempFilePath;
        },
        fail: function () {
          return that.$util.Tips({
            title: "",
          });
          that.storeImage = "";
        },
      });
    },
    /**
     * Nhận mã QR phân phối sản phẩm
     * Hàm @param gọi lại hoàn tất tải xuống thành côngFn
     *
     */
    downloadFilePromotionCode(successFn) {
      let that = this;
      // #ifdef MP
      getProductCode(that.id)
        .then((res) => {
          uni.downloadFile({
            url: that.setDomain(res.data.code),
            success: function (res) {
              that.$set(that, "isDown", false);
              that.$set(that, "PromotionCode", res.tempFilePath);
              if (typeof successFn == "function")
                successFn && successFn(res.tempFilePath);
            },
            fail: function () {
              that.$set(that, "isDown", false);
              that.$set(that, "PromotionCode", "");
            },
          });
        })
        .catch((err) => {
          that.$set(that, "isDown", false);
          that.$set(that, "PromotionCode", "");
          return that.$util.Tips({
            title: err,
          });
        });
      // #endif
      // #ifdef APP-PLUS
      uni.downloadFile({
        url: that.setDomain(that.PromotionCode),
        success: function (res) {
          that.$set(that, "isDown", false);
          if (typeof successFn == "function")
            successFn && successFn(res.tempFilePath);
          else that.$set(that, "PromotionCode", res.tempFilePath);
        },
        fail: function () {
          that.$set(that, "isDown", false);
          that.$set(that, "PromotionCode", "");
        },
      });
      // #endif
    },
  },
};
