(global["webpackJsonp"] = global["webpackJsonp"] || []).push([["subpackage/diyComponents/common/vendor"],{

/***/ 1767:
/*!**********************************************************************************!*\
  !*** /Users/tinhp/Workspace/Projects/CRMEB/template/uni-app/mixins/skuSelect.js ***!
  \**********************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function(uni) {

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.default = void 0;
var _store = __webpack_require__(/*! @/api/store.js */ 88);
var _order = __webpack_require__(/*! @/api/order.js */ 87);
var _login = __webpack_require__(/*! @/libs/login.js */ 40);
// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2021 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------
var _default = {
  data: function data() {
    return {
      attr: {
        cartAttr: false,
        productAttr: [],
        productSelect: {}
      },
      productValue: []
    };
  },
  created: function created() {},
  methods: {
    updateFun: function updateFun(e, num) {
      if (e.cartNum) {
        this.tempArr.forEach(function (item) {
          if (item.id == e.id) {
            item.cart_num = e.cartNum;
          }
        });
        // Chỉ được gọi khi trang cửa hàng xuất hiện
        if (num) {
          this.getCartNum();
        }
      }
    },
    /**
     * Thuộc tính được chọn theo mặc định
     *
     */
    DefaultSelect: function DefaultSelect() {
      var productAttr = this.attr.productAttr;
      var value = [];
      for (var key in this.productValue) {
        if (this.productValue[key].stock > 0) {
          value = this.attr.productAttr.length ? key.split(",") : [];
          break;
        }
      }
      for (var i = 0; i < productAttr.length; i++) {
        this.$set(productAttr[i], "index", value[i]);
      }
      //sort();Chức năng sắp xếp:Số-Ký tự Anh-Trung；
      var productSelect = this.productValue[value.join(",")];
      this.$set(this.attr.productSelect, "store_name", this.storeName);
      if (productSelect && productAttr.length) {
        this.$set(this.attr.productSelect, "image", productSelect.image);
        this.$set(this.attr.productSelect, "price", productSelect.price);
        this.$set(this.attr.productSelect, "stock", productSelect.stock);
        this.$set(this.attr.productSelect, "unique", productSelect.unique);
        this.$set(this.attr.productSelect, "cart_num", this.storeInfo.min_qty);
        this.$set(this.attr.productSelect, 'vip_price', productSelect.vip_price);
        this.$set(this, "attrValue", value.join(","));
      } else if (!productSelect && productAttr.length) {
        this.$set(this.attr.productSelect, "image", this.storeInfo.image);
        this.$set(this.attr.productSelect, "price", this.storeInfo.price);
        this.$set(this.attr.productSelect, "stock", 0);
        this.$set(this.attr.productSelect, "unique", "");
        this.$set(this.attr.productSelect, "cart_num", 0);
        this.$set(this, "attrValue", "");
        this.$set(this.attr.productSelect, 'vip_price', this.storeInfo.vip_price);
      } else if (!productSelect && !productAttr.length) {
        this.$set(this.attr.productSelect, "image", this.storeInfo.image);
        this.$set(this.attr.productSelect, "price", this.storeInfo.price);
        this.$set(this.attr.productSelect, "stock", this.storeInfo.stock);
        this.$set(this.attr.productSelect, "unique", this.storeInfo.unique || "");
        this.$set(this.attr.productSelect, "cart_num", this.storeInfo.min_qty);
        this.$set(this, "attrValue", "");
        this.$set(this.attr.productSelect, 'vip_price', this.storeInfo.vip_price);
      }
    },
    /**
     * gán thay đổi thuộc tính
     *
     */
    ChangeAttr: function ChangeAttr(res) {
      var productSelect = this.productValue[res];
      if (productSelect && productSelect.stock >= 0) {
        this.$set(this.attr.productSelect, "image", productSelect.image);
        this.$set(this.attr.productSelect, "price", productSelect.price);
        this.$set(this.attr.productSelect, "stock", productSelect.stock);
        this.$set(this.attr.productSelect, "unique", productSelect.unique);
        this.$set(this.attr.productSelect, 'vip_price', productSelect.vip_price);
        this.$set(this.attr.productSelect, "cart_num", this.storeInfo.min_qty);
        this.$set(this, "attrValue", res);
      } else {
        this.$set(this.attr.productSelect, 'image', this.storeInfo.image);
        this.$set(this.attr.productSelect, 'price', this.storeInfo.price);
        this.$set(this.attr.productSelect, 'stock', 0);
        this.$set(this.attr.productSelect, 'unique', '');
        this.$set(this.attr.productSelect, 'cart_num', 0);
        this.$set(this.attr.productSelect, 'vip_price', this.storeInfo.vip_price);
        this.$set(this, 'attrValue', '');
      }
    },
    attrVal: function attrVal(val) {
      this.$set(this.attr.productAttr[val.indexw], 'index', this.attr.productAttr[val.indexw].attr_values[val.indexn]);
    },
    /**
     * Điền thủ công vào giỏ hàng
     *
     */
    iptCartNum: function iptCartNum(e) {
      var _this = this;
      // this.$set(this.attr.productSelect, 'cart_num', e);
      if (e) {
        var number = this.storeInfo.min_qty;
        if (Number.isInteger(parseInt(e)) && parseInt(e) >= this.storeInfo.min_qty) {
          number = parseInt(e);
        }
        this.$nextTick(function (e) {
          _this.$set(_this.attr.productSelect, 'cart_num', e < 0 ? _this.storeInfo.min_qty : number);
        });
      }
    },
    onMyEvent: function onMyEvent() {
      this.$set(this.attr, 'cartAttr', false);
    },
    // Thay đổi giỏ hàng đa thuộc tính
    ChangeCartNumDuo: function ChangeCartNumDuo(changeValue) {
      //Nhận các thuộc tính đã thay đổi hiện tại
      var productSelect = this.productValue[this.attrValue];
      //nếu không có thuộc tính,Chỉ định giá trị cho khoảng không quảng cáo mặc định của sản phẩm
      if (productSelect === undefined && !this.attr.productAttr.length) productSelect = this.attr.productSelect;
      //Không có giá trị thuộc tính, nghĩa là hàng tồn kho là 0; không có phép cộng hoặc phép trừ.；
      if (productSelect === undefined) return;
      var stock = productSelect.stock || 0;
      var num = this.attr.productSelect;
      this.ChangeCartNum(changeValue, num, stock, 1);
    },
    // Thay đổi giỏ hàng thuộc tính duy nhất
    ChangeCartNumDan: function ChangeCartNumDan(changeValue, index, item) {
      var num = this.tempArr[index];
      var stock = this.tempArr[index].stock;
      this.ChangeCartNum(changeValue, num, stock, 0, item.id);
    },
    ChangeSubDel: function ChangeSubDel(event) {
      var that = this,
        list = that.cartData.cartList,
        ids = [];
      list.forEach(function (item) {
        ids.push(item.id);
      });
      (0, _order.cartDel)(ids.join(",")).then(function (res) {
        that.$set(that.cartData, 'cartList', []);
        that.cartData.iScart = false;
        that.totalPrice = 0.00;
        that.page = 1;
        that.loadend = false;
        that.tempArr = [];
        that.productslist();
        that.getCartNum();
      });
    },
    ChangeOneDel: function ChangeOneDel(id, index) {
      var that = this,
        list = that.cartData.cartList;
      (0, _order.cartDel)(id.toString()).then(function (res) {
        list.splice(index, 1);
        if (!list.length) {
          that.cartData.iScart = false;
          that.page = 1;
          that.loadend = false;
          that.tempArr = [];
          that.productslist();
        }
        ;
        that.getCartNum();
      });
    },
    // Thêm nhiều thông số kỹ thuật vào giỏ hàng；
    goCatNum: function goCatNum() {
      this.goCat(1, this.id, 1);
    },
    closeList: function closeList(e) {
      this.$set(this.cartData, 'iScart', e);
    },
    // Tính năng cộng trừ mua sắm khi thêm vào giỏ hàng；
    ChangeCartList: function ChangeCartList(changeValue, index) {
      var list = this.cartData.cartList;
      var num = list[index];
      var stock = list[index].trueStock;
      this.ChangeCartNum(changeValue, num, stock, 0, num.product_id, index, 1);
      if (!list.length) {
        this.cartData.iScart = false;
        this.page = 1;
        this.loadend = false;
        this.tempArr = [];
        this.productslist();
      }
    },
    // Chức năng tính toán cộng trừ giỏ hàng
    ChangeCartNum: function ChangeCartNum(changeValue, num, stock, isDuo, id, index, cart) {
      if (changeValue) {
        num.cart_num++;
        if (num.cart_num > stock) {
          if (isDuo) {
            this.$set(this.attr.productSelect, 'cart_num', stock ? stock : this.storeInfo.min_qty);
            this.$set(this, 'cart_num', stock ? stock : 1);
          } else {
            num.cart_num = stock ? stock : 0;
            this.$set(this, 'tempArr', this.tempArr);
            this.$set(this.cartData, 'cartList', this.cartData.cartList);
          }
          return this.$util.Tips({
            title: "Không còn hàng cho sản phẩm này"
          });
        } else {
          if (!isDuo) {
            if (cart) {
              this.goCat(0, id, 1, 1, num.product_attr_unique);
              this.getTotalPrice();
            } else {
              this.goCat(0, id, 1);
            }
          }
        }
      } else {
        num.cart_num--;
        if (num.cart_num == 0) {
          this.cartData.cartList.splice(index, 1);
          if (isDuo) {
            this.$set(this.attr.productSelect, 'cart_num', this.storeInfo.min_qty);
            this.$set(this, 'cart_num', 1);
          }
        }
        if (num.cart_num < 0) {
          if (isDuo) {
            this.$set(this.attr.productSelect, 'cart_num', this.storeInfo.min_qty);
            this.$set(this, 'cart_num', 1);
          } else {
            num.cart_num = 0;
            this.$set(this, 'tempArr', this.tempArr);
            this.$set(this.cartData, 'cartList', this.cartData.cartList);
          }
        } else {
          if (!isDuo) {
            if (cart) {
              this.goCat(0, id, 0, 1, num.product_attr_unique);
              this.getTotalPrice();
            } else {
              this.goCat(0, id, 0);
            }
          }
        }
      }
      this.tempArr.forEach(function (item) {
        if (item.id == id) {
          item.cart_num = num.cart_num;
        }
      });
    },
    /*
     * thêm vào giỏ hàng
     */
    goCat: function goCat(duo, id, type, cart, unique) {
      var that = this;
      if (duo) {
        var productSelect = that.productValue[this.attrValue];
        //Nếu có một thuộc tính,không có sự lựa chọn,Nhắc người dùng lựa chọn

        if (that.attr.productAttr.length && productSelect === undefined) {
          return that.$util.Tips({
            title: "Sản phẩm đã hết hàng, vui lòng chọn thuộc tính khác"
          });
        }
      }
      var q = {
        product_id: id,
        num: duo ? that.attr.productSelect.cart_num : 1,
        type: type,
        unique: duo ? that.attr.productSelect.unique : cart ? unique : ""
      };
      (0, _store.postCartNum)(q).then(function (res) {
        if (duo) {
          that.attr.cartAttr = false;
          // that.page = 1;
          // that.loadend = false;
          that.tempArr.forEach(function (item, index) {
            if (item.id == that.id) {
              var arrtStock = that.attr.productSelect.stock;
              var objNum = parseInt(item.cart_num) + parseInt(that.attr.productSelect.cart_num);
              item.cart_num = objNum > arrtStock ? arrtStock : objNum;
            }
          });
          // that.productslist();
        }

        that.$util.Tips({
          title: res.msg
        });
        that.getCartNum();
        if (!cart) {
          that.getCartList(1);
        }
      }).catch(function (err) {
        return that.$util.Tips({
          title: err
        });
      });
    },
    goCartDuo: function goCartDuo(item, num) {
      if (!this.isLogin) {
        (0, _login.toLogin)();
      } else {
        if (item.cart_button == 0) {
          if (item.is_presale_product) {
            uni.navigateTo({
              url: "/pages/activity/goods_details/index?id=".concat(item.id, "&type=6")
            });
          } else {
            //num:Cho biết mọi người đã nhấp vào từ trang chủ
            var page1 = "/pages/goods_details/index?id=".concat(item.id, "&fromType=1");
            var page2 = "/pages/goods_details/index?id=".concat(item.id);
            uni.navigateTo({
              url: num ? page2 : page1
            });
          }
        } else {
          this.storeName = item.store_name;
          this.getAttrs(item.id);
          this.$set(this, 'id', item.id);
          this.$set(this.attr, 'cartAttr', true);
        }
      }
    },
    // Nhấp vào giỏ hàng thuộc tính duy nhất mặc định
    goCartDan: function goCartDan(item, index, num) {
      if (!this.isLogin) {
        (0, _login.toLogin)();
      } else {
        if (item.cart_button == 0) {
          if (item.is_presale_product) {
            uni.navigateTo({
              url: "/pages/activity/goods_details/index?id=".concat(item.id, "&type=6")
            });
          } else {
            uni.navigateTo({
              url: "/pages/goods_details/index?id=".concat(item.id, "&fromType=1")
            });
          }
        } else {
          this.tempArr[index].cart_num = 1;
          // numNó có nghĩa là đến từ cửa hàng；
          // if(num){
          // 	this.$store.commit('indexData/setCartNum', parseInt(this.cartNum)+1)
          // }
          this.$set(this, 'tempArr', this.tempArr);
          this.goCat(0, item.id, 1);
        }
      }
    }
  }
};
exports.default = _default;
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./node_modules/@dcloudio/uni-mp-weixin/dist/index.js */ 2)["default"]))

/***/ }),

/***/ 1780:
/*!**********************************************************************************!*\
  !*** /Users/tinhp/Workspace/Projects/CRMEB/template/uni-app/static/images/f.png ***!
  \**********************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "data:image/png;base64,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"

/***/ })

}]);
//# sourceMappingURL=../../../../.sourcemap/mp-weixin/subpackage/diyComponents/common/vendor.js.map