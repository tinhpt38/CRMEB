// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2024 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

import { TOKENNAME, HTTP_REQUEST_URL } from "../config/app.js";
import store from "../store";
import i18n from "./lang.js";
import { pathToBase64 } from "@/plugin/image-tools/index.js";
// #ifdef APP-PLUS
import permision from "./permission.js";
// #endif
export default {
  /**
   * opt  object | string
   * to_url object | string
   * ví dụ:
   * this.Tips('/pages/test/test'); Nhảy mà không cần nhắc
   * this.Tips({title:'gợi ý'},'/pages/test/test'); nhắc nhở và nhảy
   * this.Tips({title:'gợi ý'},{tab:1,url:'/pages/index/index'}); Nhắc và chuyển đến bảng giá trị
   * tab=1 Chuyển đến bàn sau một khoảng thời gian nhất định
   * tab=2 Chuyển sang không phải bảng sau một khoảng thời gian nhất định
   * tab=3 Quay lại trang trước sau một khoảng thời gian nhất định
   * tab=4 Đóng tất cả các trang và mở một trang trong ứng dụng
   * tab=5 Đóng trang hiện tại và chuyển đến một trang trong ứng dụng
   */
  Tips: function (opt, to_url) {
    if (typeof opt == "string") {
      to_url = opt;
      opt = {};
    }
    let title = opt.title || "",
      icon = opt.icon || "none",
      endtime = opt.endtime || 2000,
      success = opt.success;
    if (title)
      uni.showToast({
        title: title,
        icon: icon,
        duration: endtime,
        success,
      });
    if (to_url != undefined) {
      if (typeof to_url == "object") {
        let tab = to_url.tab || 1,
          url = to_url.url || "";
        switch (tab) {
          case 1:
            //Chuyển đến sau một khoảng thời gian nhất định table
            setTimeout(function () {
              uni.switchTab({
                url: url,
              });
            }, endtime);
            break;
          case 2:
            //Chuyển đến trang không có bảng
            setTimeout(function () {
              uni.navigateTo({
                url: url,
              });
            }, endtime);
            break;
          case 3:
            //Quay lại trang trước
            setTimeout(function () {
              // #ifndef H5
              uni.navigateBack({
                delta: parseInt(url),
              });
              // #endif
              // #ifdef H5
              history.back();
              // #endif
            }, endtime);
            break;
          case 4:
            //Đóng tất cả các trang và mở một trang trong ứng dụng
            setTimeout(function () {
              uni.reLaunch({
                url: url,
              });
            }, endtime);
            break;
          case 5:
            //Đóng trang hiện tại và chuyển đến một trang trong ứng dụng
            setTimeout(function () {
              uni.redirectTo({
                url: url,
              });
            }, endtime);
            break;
        }
      } else if (typeof to_url == "function") {
        setTimeout(function () {
          to_url && to_url();
        }, endtime);
      } else {
        //Nhảy không chậm trễ khi không có lời nhắc
        setTimeout(
          function () {
            uni.navigateTo({
              url: to_url,
            });
          },
          title ? endtime : 0
        );
      }
    }
  },
  /**
   * Xóa một mảng khỏi mảng và tạo một mảng mới và trả về
   * @param array array Mảng cần xóa
   * @param int index Giá trị khóa của mảng cần xóa
   * @param string | int giá trị
   * @return array
   *
   */
  ArrayRemove: function (array, index, value) {
    const valueArray = [];
    if (array instanceof Array) {
      for (let i = 0; i < array.length; i++) {
        if (typeof index == "number" && array[index] != i) {
          valueArray.push(array[i]);
        } else if (typeof index == "string" && array[i][index] != value) {
          valueArray.push(array[i]);
        }
      }
    }
    return valueArray;
  },
  /**
   * Tạo áp phích để nhận văn bản
   * @param string text là văn bản đến
   * @param int num là độ dài byte được hiển thị trên một dòng
   * @return array
   */
  textByteLength: function (text, num) {
    let strLength = 0;
    let rows = 1;
    let str = 0;
    let arr = [];
    for (let j = 0; j < text.length; j++) {
      if (text.charCodeAt(j) > 255) {
        strLength += 2;
        if (strLength > rows * num) {
          strLength++;
          arr.push(text.slice(str, j));
          str = j;
          rows++;
        }
      } else {
        strLength++;
        if (strLength > rows * num) {
          arr.push(text.slice(str, j));
          str = j;
          rows++;
        }
      }
    }
    arr.push(text.slice(str, text.length));
    return [strLength, arr, rows]; //  [Tổng độ dài byte của văn bản được xử lý, mảng nội dung được hiển thị trên mỗi dòng và số dòng]
  },

  /**
   * Nhận poster chia sẻ sản phẩm
   * @param mảng arr2 tài liệu áp phích
   * @param chuỗi store_name văn bản tài liệu
   * Giá chuỗi @param
   * @param chuỗi ot_price giá gốc
   * Hàm @param hàm gọi lại thành côngFn
   *
   *
   */
  PosterCanvas: function (arr2, store_name, price, ot_price, successFn) {
    let that = this;
    uni.showLoading({
      title: i18n.t(`Áp phích đang được tạo`),
      mask: true,
    });
    const ctx = uni.createCanvasContext("myCanvas");
    ctx.clearRect(0, 0, 0, 0);

    /**
     * Chỉ có thể lấy được thông tin hình ảnh dưới tên miền hợp pháp,Không thể gỡ lỗi cục bộ
     *
     */
    ctx.fillStyle = "#fff";
    ctx.fillRect(0, 0, 750, 1250);
    uni.getImageInfo({
      src: arr2[0],
      success: function (res) {
        const WIDTH = res.width;
        const HEIGHT = res.height;
        // ctx.drawImage(arr2[0], 0, 0, WIDTH, 1050);
        ctx.drawImage(arr2[1], 0, 0, WIDTH, WIDTH);
        ctx.save();
        let r = 110;
        let d = r * 2;
        let cx = 480;
        let cy = 790;
        ctx.arc(cx + r, cy + r, r, 0, 2 * Math.PI);
        // ctx.clip();
        ctx.drawImage(arr2[2], cx, cy, d, d);
        ctx.restore();
        const CONTENT_ROW_LENGTH = 20;
        let [contentLeng, contentArray, contentRows] = that.textByteLength(
          store_name,
          CONTENT_ROW_LENGTH
        );
        if (contentRows > 2) {
          contentRows = 2;
          let textArray = contentArray.slice(0, 2);
          textArray[textArray.length - 1] += "…";
          contentArray = textArray;
        }
        ctx.setTextAlign("left");
        ctx.setFontSize(36);
        ctx.setFillStyle("#000");
        // let contentHh = 36 * 1.5;
        let contentHh = 36;
        for (let m = 0; m < contentArray.length; m++) {
          if (m) {
            ctx.fillText(contentArray[m], 50, 1000 + contentHh * m + 18, 1100);
          } else {
            ctx.fillText(contentArray[m], 50, 1000 + contentHh * m, 1100);
          }
        }
        ctx.setTextAlign("left");
        ctx.setFontSize(72);
        ctx.setFillStyle("#DA4F2A");
        ctx.fillText(i18n.t(`￥`) + price, 40, 820 + contentHh);

        ctx.setTextAlign("left");
        ctx.setFontSize(36);
        ctx.setFillStyle("#999");
        // Poster sản phẩm gạch chân giá
        if (ot_price) {
          ctx.fillText(i18n.t(`￥`) + ot_price, 50, 876 + contentHh);
          var underline = function (
            ctx,
            text,
            x,
            y,
            size,
            color,
            thickness,
            offset
          ) {
            var width = ctx.measureText(text).width;
            switch (ctx.textAlign) {
              case "center":
                x -= width / 2;
                break;
              case "right":
                x -= width;
                break;
            }

            y += size + offset;

            ctx.beginPath();
            ctx.strokeStyle = color;
            ctx.lineWidth = thickness;
            ctx.moveTo(x, y);
            ctx.lineTo(x + width, y);
            ctx.stroke();
          };
          underline(ctx, i18n.t(`￥`) + ot_price, 55, 865, 36, "#999", 2, 0);
        }
        ctx.setTextAlign("left");
        ctx.setFontSize(28);
        ctx.setFillStyle("#999");
        ctx.fillText(i18n.t(`Nhấn và quét để xem`), 490, 1030 + contentHh);
        ctx.draw(true, function () {
          uni.canvasToTempFilePath({
            canvasId: "myCanvas",
            fileType: "png",
            destWidth: WIDTH,
            destHeight: HEIGHT,
            success: function (res) {
              uni.hideLoading();
              successFn && successFn(res.tempFilePath);
            },
          });
        });
      },
      fail: function (err) {
        uni.hideLoading();
        that.Tips({
          title: i18n.t(`Không thể lấy được thông tin hình ảnh`),
        });
      },
    });
  },
  /**
   * Nhận áp phích chia sẻ nhóm/giá hời
   * @param mảng arr2 hình nền vật liệu poster
   * @param chuỗi store_name văn bản tài liệu
   * Giá chuỗi @param
   * @param chuỗi ot_price giá gốc
   * Hàm @param hàm gọi lại thành côngFn
   *
   *
   */
  bargainPosterCanvas: function (
    arr2,
    title,
    label,
    msg,
    price,
    wd,
    hg,
    successFn
  ) {
    let that = this;
    const ctx = uni.createCanvasContext("myCanvas");
    ctx.clearRect(0, 0, 0, 0);
    /**
     * Chỉ có thể lấy được thông tin hình ảnh dưới tên miền hợp pháp,Không thể gỡ lỗi cục bộ
     *
     */
    ctx.fillStyle = "#fff";
    ctx.fillRect(0, 0, wd * 2, hg * 2);
    uni.getImageInfo({
      src: arr2[0],
      success: function (res) {
        const WIDTH = res.width;
        const HEIGHT = res.height;
        ctx.drawImage(arr2[0], 0, 0, wd, hg);

        // Đảm bảo rằng tọa độ tương ứng với các mô hình khác nhau là chính xác
        let labelx = 0.65; //Nhãnx
        let labely = 0.166; //Nhãny
        let pricex = 0.1857; //giáx
        let pricey = 0.18; //giáx
        let codex = 0.385; //mã QR
        let codey = 0.77;
        let picturex = 0.1571; //Hình ảnh sản phẩm điểm trên bên trái
        let picturey = 0.2916;
        let picturebx = 0.6857; //Hình ảnh sản phẩm điểm dưới bên phải
        let pictureby = 0.4316;
        let msgx = 0.1036; //msg
        let msgy = 0.2306;
        let codew = 0.25;
        ctx.drawImage(
          arr2[1],
          wd * picturex,
          hg * picturey,
          wd * picturebx,
          hg * pictureby
        );
        ctx.drawImage(arr2[2], wd * codex, hg * codey, wd * codew, wd * codew);
        ctx.save();
        //tiêu đề
        const CONTENT_ROW_LENGTH = 32;
        let [contentLeng, contentArray, contentRows] = that.textByteLength(
          title,
          CONTENT_ROW_LENGTH
        );
        if (contentRows > 2) {
          contentRows = 2;
          let textArray = contentArray.slice(0, 2);
          textArray[textArray.length - 1] += "…";
          contentArray = textArray;
        }
        ctx.setTextAlign("left");
        ctx.setFillStyle("#000");
        if (contentArray.length < 2) {
          ctx.setFontSize(22);
        } else {
          ctx.setFontSize(20);
        }
        let contentHh = 8;
        for (let m = 0; m < contentArray.length; m++) {
          if (m) {
            ctx.fillText(contentArray[m], 20, 35 + contentHh * m + 18, 1100);
          } else {
            ctx.fillText(contentArray[m], 20, 35, 1100);
          }
        }
        // Gắn thẻ nội dung
        ctx.setTextAlign("left");
        ctx.setFontSize(16);
        ctx.setFillStyle("#FFF");
        ctx.fillText(label, wd * labelx, hg * labely);
        ctx.save();
        // giá
        ctx.setFillStyle("red");
        ctx.setFontSize(26);
        ctx.fillText(price, wd * pricex, hg * pricey);
        ctx.save();
        // msg
        ctx.setFillStyle("#333");
        ctx.setFontSize(16);
        ctx.fillText(msg, wd * msgx, hg * msgy);
        ctx.save();
        ctx.draw(true, () => {
          uni.canvasToTempFilePath({
            canvasId: "myCanvas",
            fileType: "png",
            quality: 1,
            success: (res) => {
              successFn && successFn(res.tempFilePath);
              uni.hideLoading();
            },
          });
        });
      },
      fail: function (err) {
        uni.hideLoading();
        that.Tips({
          title: i18n.t(`Không thể lấy được thông tin hình ảnh`),
        });
      },
    });
  },
  /**
   * Áp phích chia sẻ thông tin người dùng
   * @param mảng arr2 tài liệu áp phích 1 nền 0 mã QR
   * @param biệt danh chuỗi
   * @param chuỗi giá tên trang web
   * Hàm @param hàm gọi lại thành côngFn
   *
   *
   */
  userPosterCanvas: function (
    arr2,
    nickname,
    sitename,
    index,
    w,
    h,
    successFn
  ) {
    let that = this;
    const ctx = uni.createCanvasContext("myCanvas" + index);
    ctx.clearRect(0, 0, 0, 0);
    /**
     * Chỉ có thể lấy được thông tin hình ảnh dưới tên miền hợp pháp,Không thể gỡ lỗi cục bộ
     *
     */
    uni.getImageInfo({
      src: arr2[1],
      success: function (res) {
        const WIDTH = res.width;
        const HEIGHT = res.height;
        ctx.fillStyle = "#fff";
        ctx.fillRect(0, 0, w, h);
        ctx.drawImage(arr2[1], 0, 0, w, h);
        ctx.setTextAlign("left");
        ctx.setFontSize(12);
        ctx.setFillStyle("#333");

        // x:240 y:426
        let codex = 0.1906;
        let codey = 0.7746;
        let codeSize = 0.21666;
        let namex = 0.4283;
        let namey = 0.8215;
        let markx = 0.4283;
        let marky = 0.8685;
        ctx.drawImage(
          arr2[0],
          w * codex,
          h * codey,
          w * codeSize,
          w * codeSize
        );
        if (w < 270) {
          ctx.setFontSize(8);
        } else {
          ctx.setFontSize(10);
        }
        ctx.fillText(nickname, w * namex, h * namey);
        if (w < 270) {
          ctx.setFontSize(8);
        } else {
          ctx.setFontSize(10);
        }
        ctx.fillText(i18n.t(`mời bạn tham gia`) + sitename, w * markx, h * marky);
        ctx.save();
        ctx.draw(true, function () {
          uni.canvasToTempFilePath({
            canvasId: "myCanvas" + index,
            fileType: "png",
            quality: 1,
            success: function (res) {
              successFn && successFn(res.tempFilePath);
            },
          });
        });
      },
      fail: function (err) {
        uni.hideLoading();
        that.Tips({
          title: i18n.t(`Không thể lấy được thông tin hình ảnh`),
        });
      },
    });
  },
  /*
   * Tải lên một hình ảnh
   * @param chọn đối tượng
   * @param có thể gọi được thành côngCallback đã thực hiện thành công dữ liệu phương thức
   * @param lỗi có thể gọi Phương thức thực thi cuộc gọi lại không thành công
   */
  uploadImageOne: function (opt, successCallback, errorCallback) {
    let that = this;
    if (typeof opt === "string") {
      let url = opt;
      opt = {};
      opt.url = url;
    }
    let count = opt.count || 1,
      sizeType = opt.sizeType || ["compressed"],
      sourceType = opt.sourceType || ["album", "camera"],
      is_load = opt.is_load || true,
      uploadUrl = opt.url || "",
      inputName = opt.name || "pics",
      fileType = opt.fileType || "image";
    uni.chooseImage({
      count: count, //Tổng số hình ảnh tối đa có thể được chọn
      sizeType: sizeType, // Bạn có thể chỉ định xem đó là ảnh gốc hay ảnh nén và cả hai đều có sẵn theo mặc định.
      sourceType: sourceType, // Bạn có thể chỉ định nguồn là album ảnh hay máy ảnh và cả hai đều được bao gồm theo mặc định.
      success: function (res) {
        //Bắt đầu tải lên chờ...
        uni.showLoading({
          title: i18n.t(`Tải ảnh lên`),
        });
        uni.uploadFile({
          url: HTTP_REQUEST_URL + "/api/" + uploadUrl,
          filePath: res.tempFilePaths[0],
          fileType: fileType,
          name: inputName,
          formData: {
            filename: inputName,
          },
          header: {
            // #ifdef MP
            "Content-Type": "multipart/form-data",
            // #endif
            [TOKENNAME]: "Bearer " + store.state.app.token,
          },
          success: function (res) {
            uni.hideLoading();
            if (res.statusCode == 403) {
              that.Tips({
                title: res.data,
              });
            } else {
              let data = res.data ? JSON.parse(res.data) : {};
              if (data.status == 200) {
                successCallback && successCallback(data);
              } else {
                errorCallback && errorCallback(data);
                that.Tips({
                  title: data.msg,
                });
              }
            }
          },
          fail: function (res) {
            uni.hideLoading();
            that.Tips({
              title: i18n.t(`Không thể tải hình ảnh lên`),
            });
          },
        });
      },
    });
  },
  /*
   * Phiên bản nén tải lên một hình ảnh
   * @param chọn đối tượng
   * @param có thể gọi được thành côngCallback đã thực hiện thành công dữ liệu phương thức
   * @param lỗi có thể gọi Phương thức thực thi cuộc gọi lại không thành công
   */
  uploadImageChange: function (
    opt,
    successCallback,
    errorCallback,
    sizeCallback
  ) {
    let that = this;
    if (typeof opt === "string") {
      let url = opt;
      opt = {};
      opt.url = url;
    }
    let count = opt.count || 1,
      sizeType = opt.sizeType || ["compressed"],
      sourceType = opt.sourceType || ["album", "camera"],
      is_load = opt.is_load || true,
      uploadUrl = opt.url || "",
      inputName = opt.name || "pics",
      fileType = opt.fileType || "image";
    uni.chooseImage({
      count: count, //Tổng số hình ảnh tối đa có thể được chọn
      sizeType: sizeType, // Bạn có thể chỉ định xem đó là ảnh gốc hay ảnh nén và cả hai đều có sẵn theo mặc định.
      sourceType: sourceType, // Bạn có thể chỉ định nguồn là album ảnh hay máy ảnh và cả hai đều được bao gồm theo mặc định.
      success: function (res) {
        //Bắt đầu tải lên chờ...
        let imgSrc;
        uni.getImageInfo({
          src: res.tempFilePaths[0],
          success(ress) {
            uni.showLoading({
              title: i18n.t(`Tải ảnh lên`),
            });
            if (res.tempFiles[0].size <= 2097152) {
              uploadImg(ress.path);
              return;
            }
            // uploadImg(canvasPath.tempFilePath)
            let canvasWidth,
              canvasHeight,
              xs,
              maxWidth = 750;
            xs = ress.width / ress.height; // tỷ lệ khung hình
            if (ress.width > maxWidth) {
              canvasWidth = maxWidth; // Đây là chiều rộng giới hạn tối đa
              canvasHeight = maxWidth / xs;
            } else {
              canvasWidth = ress.width;
              canvasHeight = ress.height;
            }
            sizeCallback &&
              sizeCallback({
                w: canvasWidth,
                h: canvasHeight,
              });
            let canvas = uni.createCanvasContext("canvas");
            canvas.width = canvasWidth;
            canvas.height = canvasHeight;
            canvas.clearRect(0, 0, canvasWidth, canvasHeight);
            canvas.drawImage(ress.path, 0, 0, canvasWidth, canvasHeight);
            canvas.save();
            // Canvas drawImage ở đây là thuộc tính không đồng bộ. Có thể có vấn đề khi thực hiện vẽ trước khi vẽ, vì vậy hãy thêm độ trễ.
            setTimeout((e) => {
              canvas.draw(true, () => {
                uni.canvasToTempFilePath({
                  canvasId: "canvas",
                  fileType: "JPEG",
                  destWidth: canvasWidth,
                  destHeight: canvasHeight,
                  quality: 0.7,
                  success: function (canvasPath) {
                    uploadImg(canvasPath.tempFilePath);
                  },
                });
              });
            }, 200);
          },
        });
      },
    });

    function uploadImg(filePath) {
      uni.uploadFile({
        url: HTTP_REQUEST_URL + "/api/" + uploadUrl,
        filePath,
        fileType: fileType,
        name: inputName,
        formData: {
          filename: inputName,
        },
        header: {
          // #ifdef MP
          "Content-Type": "multipart/form-data",
          // #endif
          [TOKENNAME]: "Bearer " + store.state.app.token,
        },
        success: function (res) {
          uni.hideLoading();
          if (res.statusCode == 403) {
            that.Tips({
              title: res.data,
            });
          } else {
            let data = res.data ? JSON.parse(res.data) : {};
            if (data.status == 200) {
              successCallback && successCallback(data);
            } else {
              errorCallback && errorCallback(data);
              that.Tips({
                title: data.msg,
              });
            }
          }
        },
        fail: function (res) {
          uni.hideLoading();
          that.Tips({
            title: i18n.t(`Không thể tải hình ảnh lên`),
          });
        },
      });
    }
  },
  /**
   * Thu thập và tải lên avatar chương trình nhỏ
   * @param uploadUrl địa chỉ giao diện tải lên
   * @param filePath đường dẫn tệp tải lên
   * @param thành công Gọi lại gọi lại thành công
   * @param errorGọi lại lỗi gọi lại
   */
  uploadImgs(uploadUrl, filePath, successCallback, errorCallback) {
    let that = this;
    uni.uploadFile({
      url: HTTP_REQUEST_URL + "/api/" + uploadUrl,
      filePath: filePath,
      fileType: "image",
      name: "pics",
      formData: {
        filename: "pics",
      },
      header: {
        // #ifdef MP
        "Content-Type": "multipart/form-data",
        // #endif
        [TOKENNAME]: "Bearer " + store.state.app.token,
      },
      success: (res) => {
        uni.hideLoading();
        if (res.statusCode == 403) {
          that.Tips({
            title: res.data,
          });
        } else {
          let data = res.data ? JSON.parse(res.data) : {};
          if (data.status == 200) {
            successCallback && successCallback(data);
          } else {
            errorCallback && errorCallback(data);
            that.Tips({
              title: data.msg,
            });
          }
        }
      },
      fail: (err) => {
        uni.hideLoading();
        that.Tips({
          title: i18n.t(`Không thể tải hình ảnh lên`),
        });
      },
    });
  },
  /**
   * Chương trình mini so sánh thông tin phiên bản
   * @param v1 phiên bản hiện tại
   * @param v2 Phiên bản để so sánh với
   * @return boolen
   *
   */
  compareVersion(v1, v2) {
    v1 = v1.split(".");
    v2 = v2.split(".");
    const len = Math.max(v1.length, v2.length);

    while (v1.length < len) {
      v1.push("0");
    }
    while (v2.length < len) {
      v2.push("0");
    }

    for (let i = 0; i < len; i++) {
      const num1 = parseInt(v1[i]);
      const num2 = parseInt(v2[i]);

      if (num1 > num2) {
        return 1;
      } else if (num1 < num2) {
        return -1;
      }
    }

    return 0;
  },
  /*
   * Nhận thời gian hiện tại
   */
  getNowTime() {
    let today = new Date();
    let year = today.getFullYear(); // Lấy năm hiện tại
    let month = today.getMonth() + 1; // Lấy tháng hiện tại (lưu ý: tháng bắt đầu tính từ 0 nên bạn cần thêm 1）
    let day = today.getDate(); // Lấy ngày hiện tại (số）
    let hour = today.getHours(); // Lấy giờ hiện tại
    let minute = today.getMinutes(); // Lấy phút hiện tại
    let second = today.getSeconds(); // Lấy giây hiện tại

    //Định dạng và xuất ra thời gian hiện tại
    let nowTime =
      year + "/" + month + "/" + day + " " + hour + ":" + minute + ":" + second;
    return nowTime;
  },
  /**
   * Xử lý các thông số đưa vào bằng cách quét mã QR từ máy chủ
   * Mã quét thông số chuỗi @param để mang tham số
   * @param chuỗi k dấu phân cách tổng thể. Mặc định là: &
   * @param chuỗi p mặc định dấu phân cách đơn là：=
   * @return object
   *
   */
  // #ifdef MP
  getUrlParams: function (param, k, p) {
    if (typeof param != "string") return {};
    k = k ? k : "&"; //Dấu phân cách tham số tổng thể
    p = p ? p : "="; //dấu phân cách tham số đơn
    var value = {};
    if (param.indexOf(k) !== -1) {
      param = param.split(k);
      for (var val in param) {
        if (param[val].indexOf(p) !== -1) {
          var item = param[val].split(p);
          value[item[0]] = item[1];
        }
      }
    } else if (param.indexOf(p) !== -1) {
      var item = param.split(p);
      value[item[0]] = item[1];
    } else {
      return param;
    }
    return value;
  },
  // #endif
  /*
   * Hợp nhất mảng
   */
  SplitArray(list, sp) {
    if (typeof list != "object") return [];
    if (sp === undefined) sp = [];
    for (var i = 0; i < list.length; i++) {
      sp.push(list[i]);
    }
    return sp;
  },
  trim(backUrlCRshlcICwGdGY) {
    return String.prototype.trim.call(backUrlCRshlcICwGdGY);
  },
  $h: {
    //Hàm chia, dùng để lấy kết quả chia chính xác
    //Lưu ý: Kết quả phép chia của JavaScript sẽ có sai sót, lỗi này sẽ rõ hơn khi chia hai số có dấu phẩy động. Hàm này trả về kết quả chia chính xác hơn.
    //gọi：$h.Div(arg1,arg2)
    //Giá trị trả về: kết quả chính xác của việc chia arg1 cho arg2
    Div: function (arg1, arg2) {
      arg1 = parseFloat(arg1);
      arg2 = parseFloat(arg2);
      var t1 = 0,
        t2 = 0,
        r1,
        r2;
      try {
        t1 = arg1.toString().split(".")[1].length;
      } catch (e) {}
      try {
        t2 = arg2.toString().split(".")[1].length;
      } catch (e) {}
      r1 = Number(arg1.toString().replace(".", ""));
      r2 = Number(arg2.toString().replace(".", ""));
      return this.Mul(r1 / r2, Math.pow(10, t2 - t1));
    },
    //Hàm cộng, được sử dụng để có được kết quả cộng chính xác
    //Lưu ý: Kết quả phép cộng của JavaScript sẽ có lỗi, lỗi này sẽ rõ hơn khi cộng hai số dấu phẩy động. Hàm này trả về kết quả phép cộng chính xác hơn.
    //gọi：$h.Add(arg1,arg2)
    //Giá trị trả về: kết quả chính xác của arg1 cộng arg2
    Add: function (arg1, arg2) {
      arg2 = parseFloat(arg2);
      var r1, r2, m;
      try {
        r1 = arg1.toString().split(".")[1].length;
      } catch (e) {
        r1 = 0;
      }
      try {
        r2 = arg2.toString().split(".")[1].length;
      } catch (e) {
        r2 = 0;
      }
      m = Math.pow(100, Math.max(r1, r2));
      return (this.Mul(arg1, m) + this.Mul(arg2, m)) / m;
    },
    //Hàm trừ, dùng để có kết quả trừ chính xác
    //Lưu ý: Kết quả phép cộng của JavaScript sẽ có lỗi, lỗi này sẽ rõ hơn khi cộng hai số dấu phẩy động. Hàm này trả về kết quả trừ chính xác hơn.
    //gọi：$h.Sub(arg1,arg2)
    //Giá trị trả về: kết quả chính xác của arg1 trừ arg2
    Sub: function (arg1, arg2) {
      arg1 = parseFloat(arg1);
      arg2 = parseFloat(arg2);
      var r1, r2, m, n;
      try {
        r1 = arg1.toString().split(".")[1].length;
      } catch (e) {
        r1 = 0;
      }
      try {
        r2 = arg2.toString().split(".")[1].length;
      } catch (e) {
        r2 = 0;
      }
      m = Math.pow(10, Math.max(r1, r2));
      //Độ dài chính xác của điều khiển động
      n = r1 >= r2 ? r1 : r2;
      return ((this.Mul(arg1, m) - this.Mul(arg2, m)) / m).toFixed(n);
    },
    //Hàm nhân, dùng để lấy kết quả nhân chính xác
    //Lưu ý: Kết quả phép nhân của JavaScript sẽ có lỗi, lỗi này sẽ rõ hơn khi nhân hai số dấu phẩy động. Hàm này trả về kết quả nhân chính xác hơn.
    //gọi：$h.Mul(arg1,arg2)
    //Giá trị trả về: kết quả chính xác của phép nhân arg1 với arg2
    Mul: function (arg1, arg2) {
      arg1 = parseFloat(arg1);
      arg2 = parseFloat(arg2);
      var m = 0,
        s1 = arg1.toString(),
        s2 = arg2.toString();
      try {
        m += s1.split(".")[1].length;
      } catch (e) {}
      try {
        m += s2.split(".")[1].length;
      } catch (e) {}
      return (
        (Number(s1.replace(".", "")) * Number(s2.replace(".", ""))) /
        Math.pow(10, m)
      );
    },
  },
  // Nhận vị trí;
  $L: {
    async getLocation() {
      // #ifdef APP-PLUS
      let status = await this.checkPermission();
      if (status !== 1) {
        return;
      }
      // #endif
      // #ifdef MP-WEIXIN || MP-TOUTIAO || MP-QQ
      let status = await this.getSetting();
      if (status === 2) {
        this.openSetting();
        return;
      }
      // #endif

      this.doGetLocation();
    },
    doGetLocation() {
      uni.getLocation({
        success: (res) => {
          uni.removeStorageSync("CACHE_LONGITUDE");
          uni.removeStorageSync("CACHE_LATITUDE");
          uni.setStorageSync("CACHE_LONGITUDE", res.longitude);
          uni.setStorageSync("CACHE_LATITUDE", res.latitude);
        },
        fail: (err) => {
          // #ifdef MP-BAIDU
          if (err.errCode === 202 || err.errCode === 10003) {
            // 202Mô phỏng 10003 máy thật user deny
            this.openSetting();
          }
          // #endif
          // #ifndef MP-BAIDU
          if (err.errMsg.indexOf("auth deny") >= 0) {
            uni.showToast({
              title: i18n.t(`Quyền truy cập vào vị trí bị từ chối`),
            });
          } else {
            uni.showToast({
              title: err.errMsg,
            });
          }
          // #endif
        },
      });
    },
    getSetting: function () {
      return new Promise((resolve, reject) => {
        uni.getSetting({
          success: (res) => {
            if (res.authSetting["scope.userLocation"] === undefined) {
              resolve(0);
              return;
            }
            if (res.authSetting["scope.userLocation"]) {
              resolve(1);
            } else {
              resolve(2);
            }
          },
        });
      });
    },
    openSetting: function () {
      uni.openSetting({
        success: (res) => {
          if (res.authSetting && res.authSetting["scope.userLocation"]) {
            this.doGetLocation();
          }
        },
        fail: (err) => {},
      });
    },
    async checkPermission() {
      let status = permision.isIOS
        ? await permision.requestIOS("location")
        : await permision.requestAndroid(
            "android.permission.ACCESS_FINE_LOCATION"
          );

      if (status === null || status === 1) {
        status = 1;
      } else if (status === 2) {
        uni.showModal({
          content: i18n.t(`Định vị hệ thống bị tắt`),
          confirmText: i18n.t(`Chắc chắn`),
          showCancel: false,
          success: function (res) {},
        });
      } else if (status.code) {
        uni.showModal({
          content: status.message,
        });
      } else {
        uni.showModal({
          content: i18n.t(`Cần có sự cho phép vị trí`),
          confirmText: i18n.t(`Chắc chắn`),
          success: function (res) {
            if (res.confirm) {
              permision.gotoAppSetting();
            }
          },
        });
      }
      return status;
    },
  },
  /**
   * Chức năng đóng gói đường nhảy
   * Đường dẫn nhảy url @param
   */
  JumpPath: function (url) {
    let arr = url.split("@APPID=");
    if (arr.length > 1) {
      //#ifdef MP
      uni.navigateToMiniProgram({
        appId: arr[arr.length - 1], // Đây là khoản thanh toán chi phí sinh hoạtappid
        path: arr[0], // Đây là đường dẫn đến trang chủ thanh toán sinh hoạt
        envVersion: "release",
        success: (res) => {
          console.log("Mở thành công", res);
        },
        fail: (err) => {},
      });
      //#endif
      //#ifndef MP
      this.Tips({
        title: "h5Phía app không hỗ trợ nhảy sang các chương trình mini bên ngoài.",
      });
      //#endif
    } else {
      if (url.indexOf("http") != -1) {
        uni.navigateTo({
          url: `/pages/annex/web_view/index?url=${url}`,
        });
      } else {
        if (
          [
            "/pages/goods_cate/goods_cate",
            "/pages/order_addcart/order_addcart",
            "/pages/user/index",
            "/pages/index/index",
          ].indexOf(url) == -1
        ) {
          uni.navigateTo({
            url,
          });
        } else {
          uni.switchTab({
            url,
          });
        }
      }
    }
  },
  // Tính chiều cao điều hướng tùy chỉnh của đầu；
  getWXStatusHeight() {
    // Nhận khoảng cách
    const barTop = uni.getWindowInfo().statusBarHeight;
    // #ifdef MP
    // Nhận thông tin vị trí nút viên nang
    const menuButtonInfo = wx.getMenuButtonBoundingClientRect() || 0;
    // Nhận chiều cao thanh điều hướng
    const barHeight = menuButtonInfo.height + (menuButtonInfo.top - barTop) * 2;
    let barWidth = menuButtonInfo.width;
    // #endif
    // #ifndef MP
    // Nhận chiều cao thanh điều hướng
    const barHeight = parseInt(barTop) + 10;
    let barWidth = "100%";
    // #endif
    return {
      // #ifdef MP
      menuButtonInfo,
      // #endif
      barHeight,
      barTop,
      barWidth,
    };
  },
};
