require('../common/vendor.js');(global["webpackJsonp"] = global["webpackJsonp"] || []).push([["pages/goods/order_confirm/index"],{

/***/ 273:
/*!***********************************************************************************************************************!*\
  !*** /Users/tinhp/Workspace/Projects/CRMEB/template/uni-app/main.js?{"page":"pages%2Fgoods%2Forder_confirm%2Findex"} ***!
  \***********************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function(wx, createPage) {

var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ 4);
__webpack_require__(/*! uni-pages */ 30);
var _vue = _interopRequireDefault(__webpack_require__(/*! vue */ 25));
var _index = _interopRequireDefault(__webpack_require__(/*! ./pages/goods/order_confirm/index.vue */ 274));
// @ts-ignore
wx.__webpack_require_UNI_MP_PLUGIN__ = __webpack_require__;
createPage(_index.default);
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./node_modules/@dcloudio/uni-mp-weixin/dist/wx.js */ 1)["default"], __webpack_require__(/*! ./node_modules/@dcloudio/uni-mp-weixin/dist/index.js */ 2)["createPage"]))

/***/ }),

/***/ 274:
/*!**************************************************************************************************!*\
  !*** /Users/tinhp/Workspace/Projects/CRMEB/template/uni-app/pages/goods/order_confirm/index.vue ***!
  \**************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _index_vue_vue_type_template_id_1a7ecab0_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./index.vue?vue&type=template&id=1a7ecab0&scoped=true& */ 275);
/* harmony import */ var _index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./index.vue?vue&type=script&lang=js& */ 277);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[key]; }) }(__WEBPACK_IMPORT_KEY__));
/* harmony import */ var _index_vue_vue_type_style_index_0_id_1a7ecab0_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./index.vue?vue&type=style&index=0&id=1a7ecab0&lang=scss&scoped=true& */ 280);
/* harmony import */ var _Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/vue-cli-plugin-uni/packages/vue-loader/lib/runtime/componentNormalizer.js */ 68);

var renderjs





/* normalize component */

var component = Object(_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _index_vue_vue_type_template_id_1a7ecab0_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _index_vue_vue_type_template_id_1a7ecab0_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "1a7ecab0",
  null,
  false,
  _index_vue_vue_type_template_id_1a7ecab0_scoped_true___WEBPACK_IMPORTED_MODULE_0__["components"],
  renderjs
)

component.options.__file = "pages/goods/order_confirm/index.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ 275:
/*!*********************************************************************************************************************************************!*\
  !*** /Users/tinhp/Workspace/Projects/CRMEB/template/uni-app/pages/goods/order_confirm/index.vue?vue&type=template&id=1a7ecab0&scoped=true& ***!
  \*********************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns, recyclableRender, components */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_17_0_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_template_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_uni_app_loader_page_meta_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_index_vue_vue_type_template_id_1a7ecab0_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/vue-cli-plugin-uni/packages/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--17-0!../../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/webpack-uni-mp-loader/lib/template.js!../../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-uni-app-loader/page-meta.js!../../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/vue-cli-plugin-uni/packages/vue-loader/lib??vue-loader-options!../../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/webpack-uni-mp-loader/lib/style.js!./index.vue?vue&type=template&id=1a7ecab0&scoped=true& */ 276);
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_17_0_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_template_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_uni_app_loader_page_meta_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_index_vue_vue_type_template_id_1a7ecab0_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_17_0_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_template_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_uni_app_loader_page_meta_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_index_vue_vue_type_template_id_1a7ecab0_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "recyclableRender", function() { return _Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_17_0_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_template_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_uni_app_loader_page_meta_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_index_vue_vue_type_template_id_1a7ecab0_scoped_true___WEBPACK_IMPORTED_MODULE_0__["recyclableRender"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "components", function() { return _Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_17_0_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_template_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_uni_app_loader_page_meta_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_index_vue_vue_type_template_id_1a7ecab0_scoped_true___WEBPACK_IMPORTED_MODULE_0__["components"]; });



/***/ }),

/***/ 276:
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/@dcloudio/vue-cli-plugin-uni/packages/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--17-0!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/template.js!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-uni-app-loader/page-meta.js!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/vue-loader/lib??vue-loader-options!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/style.js!/Users/tinhp/Workspace/Projects/CRMEB/template/uni-app/pages/goods/order_confirm/index.vue?vue&type=template&id=1a7ecab0&scoped=true& ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns, recyclableRender, components */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "recyclableRender", function() { return recyclableRender; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "components", function() { return components; });
var components
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  var m0 =
    !_vm.virtual_type &&
    (!_vm.is_gift || _vm.is_gift == 2) &&
    _vm.store_self_mention &&
    _vm.is_shipping
      ? _vm.$t("chuyển phát nhanh")
      : null
  var m1 =
    !_vm.virtual_type &&
    (!_vm.is_gift || _vm.is_gift == 2) &&
    _vm.store_self_mention &&
    _vm.is_shipping
      ? _vm.$t("Nhận tại cửa hàng")
      : null
  var m2 =
    !_vm.virtual_type &&
    (!_vm.is_gift || _vm.is_gift == 2) &&
    (!_vm.store_self_mention || !_vm.is_shipping) &&
    _vm.shippingType == 0
      ? _vm.$t("Nền tảng cung cấp cho bạn dịch vụ giao hàng")
      : null
  var m3 =
    !_vm.virtual_type &&
    (!_vm.is_gift || _vm.is_gift == 2) &&
    (!_vm.store_self_mention || !_vm.is_shipping) &&
    _vm.shippingType == 1
      ? _vm.$t("Đặt hàng trực tuyến và nhận hàng tại cửa hàng")
      : null
  var m4 =
    !_vm.virtual_type &&
    (!_vm.is_gift || _vm.is_gift == 2) &&
    (!_vm.store_self_mention || !_vm.is_shipping) &&
    _vm.shippingType == 0
      ? _vm.$t("Chuyển đổi địa chỉ")
      : null
  var m5 =
    !_vm.virtual_type &&
    (!_vm.is_gift || _vm.is_gift == 2) &&
    (!_vm.store_self_mention || !_vm.is_shipping) &&
    !(_vm.shippingType == 0)
      ? _vm.$t("Chuyển đổi cửa hàng")
      : null
  var m6 =
    !_vm.virtual_type &&
    (!_vm.is_gift || _vm.is_gift == 2) &&
    _vm.shippingType == 0 &&
    (_vm.addressInfo.real_name || "") &&
    _vm.addressInfo.is_default
      ? _vm.$t("mặc định")
      : null
  var m7 =
    !_vm.virtual_type &&
    (!_vm.is_gift || _vm.is_gift == 2) &&
    _vm.shippingType == 0 &&
    !(_vm.addressInfo.real_name || "")
      ? _vm.$t("Đặt địa chỉ giao hàng")
      : null
  var g0 =
    !_vm.virtual_type &&
    (!_vm.is_gift || _vm.is_gift == 2) &&
    !(_vm.shippingType == 0)
      ? _vm.storeList.length
      : null
  var m8 =
    !_vm.virtual_type &&
    (!_vm.is_gift || _vm.is_gift == 2) &&
    !(_vm.shippingType == 0) &&
    !(g0 > 0)
      ? _vm.$t("Chưa có thông tin cửa hàng")
      : null
  var g1 =
    !_vm.virtual_type &&
    (!_vm.is_gift || _vm.is_gift == 2) &&
    !(_vm.shippingType == 0)
      ? _vm.storeList.length
      : null
  var m9 = _vm.is_gift == 1 ? _vm.$t("Tin nhắn của bạn bè") : null
  var m10 =
    _vm.is_gift == 1 ? _vm.$t("Điền tin nhắn của bạn bè, tối đa 40 từ") : null
  var m11 =
    !_vm.is_gift && _vm.is_gift == 2 && _vm.shippingType == 1
      ? _vm.$t("Tên người dùng")
      : null
  var m12 =
    !_vm.is_gift && _vm.is_gift == 2 && _vm.shippingType == 1
      ? _vm.$t("Vui lòng nhập tên")
      : null
  var m13 =
    !_vm.is_gift && _vm.is_gift == 2 && _vm.shippingType == 1
      ? _vm.$t("Số liên lạc")
      : null
  var m14 =
    !_vm.is_gift && _vm.is_gift == 2 && _vm.shippingType == 1
      ? _vm.$t("Vui lòng nhập số điện thoại di động")
      : null
  var m15 =
    (!_vm.is_gift || _vm.is_gift == 1) &&
    !_vm.pinkId &&
    !_vm.BargainId &&
    !_vm.combinationId &&
    !_vm.seckillId &&
    !_vm.noCoupon &&
    !_vm.discountId &&
    !_vm.advanceId
      ? _vm.$t("Phiếu giảm giá")
      : null
  var m16 =
    (!_vm.is_gift || _vm.is_gift == 1) &&
    !_vm.pinkId &&
    !_vm.BargainId &&
    !_vm.combinationId &&
    !_vm.seckillId &&
    !_vm.advanceId &&
    _vm.integral_open
      ? _vm.$t("Trừ điểm")
      : null
  var m17 =
    (!_vm.is_gift || _vm.is_gift == 1) &&
    !_vm.pinkId &&
    !_vm.BargainId &&
    !_vm.combinationId &&
    !_vm.seckillId &&
    !_vm.advanceId &&
    _vm.integral_open &&
    _vm.useIntegral
      ? _vm.$t("điểm còn lại")
      : null
  var m18 =
    (!_vm.is_gift || _vm.is_gift == 1) &&
    !_vm.pinkId &&
    !_vm.BargainId &&
    !_vm.combinationId &&
    !_vm.seckillId &&
    !_vm.advanceId &&
    _vm.integral_open &&
    !_vm.useIntegral
      ? _vm.$t("Điểm hiện tại")
      : null
  var m19 =
    (!_vm.is_gift || _vm.is_gift == 1) &&
    (_vm.invoice_func || _vm.special_invoice)
      ? _vm.$t("Xuất hóa đơn")
      : null
  var m20 =
    (!_vm.is_gift || _vm.is_gift == 1) && _vm.shippingType == 1
      ? _vm.$t("Tên người dùng")
      : null
  var m21 =
    (!_vm.is_gift || _vm.is_gift == 1) && _vm.shippingType == 1
      ? _vm.$t("Vui lòng nhập tên")
      : null
  var m22 =
    (!_vm.is_gift || _vm.is_gift == 1) && _vm.shippingType == 1
      ? _vm.$t("Số liên lạc")
      : null
  var m23 =
    (!_vm.is_gift || _vm.is_gift == 1) && _vm.shippingType == 1
      ? _vm.$t("Vui lòng nhập số điện thoại di động")
      : null
  var m24 =
    (!_vm.is_gift || _vm.is_gift == 1) && _vm.textareaStatus
      ? _vm.$t("Bình luận")
      : null
  var m25 =
    (!_vm.is_gift || _vm.is_gift == 1) &&
    _vm.textareaStatus &&
    !_vm.coupon.coupon &&
    !_vm.inputTrip
      ? _vm.mark ||
        _vm.$t(
          "\u0110i\u1EC1n th\xF4ng tin nh\u1EADn x\xE9t, trong v\xF2ng 100 t\u1EEB"
        )
      : null
  var m26 =
    (!_vm.is_gift || _vm.is_gift == 1) &&
    _vm.textareaStatus &&
    !_vm.coupon.coupon &&
    _vm.inputTrip
      ? _vm.$t("Điền thông tin nhận xét, trong vòng 100 từ")
      : null
  var g2 = _vm.confirm.length && (!_vm.is_gift || _vm.is_gift == 1)
  var l0 = g2
    ? _vm.__map(_vm.confirm, function (item, index) {
        var $orig = _vm.__get_orig(item)
        var m27 =
          item.label == "text" ? _vm.$t("Vui lòng điền vào" + item.title) : null
        var m28 =
          item.label == "number"
            ? _vm.$t("Vui lòng điền vào" + item.title)
            : null
        var m29 =
          item.label == "email"
            ? _vm.$t("Vui lòng điền vào" + item.title)
            : null
        var m30 = item.label == "id" ? _vm.$t("Vui lòng điền vào") : null
        var m31 = item.label == "phone" ? _vm.$t("Vui lòng điền vào") : null
        var g3 = item.label == "img" ? item.value.length : null
        var m32 = item.label == "img" && g3 < 8 ? _vm.$t("Tải ảnh lên") : null
        return {
          $orig: $orig,
          m27: m27,
          m28: m28,
          m29: m29,
          m30: m30,
          m31: m31,
          g3: g3,
          m32: m32,
        }
      })
    : null
  var m33 =
    !_vm.is_gift || _vm.is_gift == 1 ? _vm.$t("Tổng giá sản phẩm") : null
  var m34 = !_vm.is_gift || _vm.is_gift == 1 ? _vm.$t("￥") : null
  var m35 =
    (!_vm.is_gift || _vm.is_gift == 1) &&
    _vm.is_gift &&
    _vm.priceGroup.giftPrice > 0
      ? _vm.$t("phụ phí quà tặng")
      : null
  var m36 =
    (!_vm.is_gift || _vm.is_gift == 1) &&
    _vm.is_gift &&
    _vm.priceGroup.giftPrice > 0
      ? _vm.$t("￥")
      : null
  var m37 =
    (!_vm.is_gift || _vm.is_gift == 1) &&
    _vm.is_gift &&
    _vm.priceGroup.giftPrice > 0
      ? parseFloat(_vm.priceGroup.giftPrice)
      : null
  var m38 =
    (!_vm.is_gift || _vm.is_gift == 1) &&
    (_vm.priceGroup.storePostage > 0 || _vm.priceGroup.storePostageDiscount > 0)
      ? _vm.$t("Phí vận chuyển")
      : null
  var m39 =
    (!_vm.is_gift || _vm.is_gift == 1) &&
    (_vm.priceGroup.storePostage > 0 || _vm.priceGroup.storePostageDiscount > 0)
      ? _vm.$t("￥")
      : null
  var g4 =
    (!_vm.is_gift || _vm.is_gift == 1) &&
    (_vm.priceGroup.storePostage > 0 || _vm.priceGroup.storePostageDiscount > 0)
      ? (
          parseFloat(_vm.priceGroup.storePostage) +
          parseFloat(_vm.priceGroup.storePostageDiscount)
        ).toFixed(2)
      : null
  var m40 =
    (!_vm.is_gift || _vm.is_gift == 1) &&
    _vm.priceGroup.levelPrice > 0 &&
    _vm.userInfo.vip &&
    !_vm.pinkId &&
    !_vm.BargainId &&
    !_vm.combinationId &&
    !_vm.seckillId &&
    !_vm.discountId
      ? _vm.$t("Giảm giá ở cấp độ người dùng")
      : null
  var m41 =
    (!_vm.is_gift || _vm.is_gift == 1) &&
    _vm.priceGroup.levelPrice > 0 &&
    _vm.userInfo.vip &&
    !_vm.pinkId &&
    !_vm.BargainId &&
    !_vm.combinationId &&
    !_vm.seckillId &&
    !_vm.discountId
      ? _vm.$t("￥")
      : null
  var g5 =
    (!_vm.is_gift || _vm.is_gift == 1) &&
    _vm.priceGroup.levelPrice > 0 &&
    _vm.userInfo.vip &&
    !_vm.pinkId &&
    !_vm.BargainId &&
    !_vm.combinationId &&
    !_vm.seckillId &&
    !_vm.discountId
      ? parseFloat(_vm.priceGroup.levelPrice).toFixed(2)
      : null
  var m42 =
    (!_vm.is_gift || _vm.is_gift == 1) &&
    _vm.priceGroup.memberPrice > 0 &&
    _vm.userInfo.vip &&
    !_vm.pinkId &&
    !_vm.BargainId &&
    !_vm.combinationId &&
    !_vm.seckillId &&
    !_vm.discountId
      ? _vm.$t("Lợi ích thành viên trả phí")
      : null
  var m43 =
    (!_vm.is_gift || _vm.is_gift == 1) &&
    _vm.priceGroup.memberPrice > 0 &&
    _vm.userInfo.vip &&
    !_vm.pinkId &&
    !_vm.BargainId &&
    !_vm.combinationId &&
    !_vm.seckillId &&
    !_vm.discountId
      ? _vm.$t("￥")
      : null
  var g6 =
    (!_vm.is_gift || _vm.is_gift == 1) &&
    _vm.priceGroup.memberPrice > 0 &&
    _vm.userInfo.vip &&
    !_vm.pinkId &&
    !_vm.BargainId &&
    !_vm.combinationId &&
    !_vm.seckillId &&
    !_vm.discountId
      ? parseFloat(_vm.priceGroup.memberPrice).toFixed(2)
      : null
  var m44 =
    (!_vm.is_gift || _vm.is_gift == 1) &&
    _vm.priceGroup.storePostageDiscount > 0
      ? _vm.$t("Giảm giá vận chuyển cho thành viên")
      : null
  var m45 =
    (!_vm.is_gift || _vm.is_gift == 1) &&
    _vm.priceGroup.storePostageDiscount > 0
      ? _vm.$t("￥")
      : null
  var g7 =
    (!_vm.is_gift || _vm.is_gift == 1) &&
    _vm.priceGroup.storePostageDiscount > 0
      ? parseFloat(_vm.priceGroup.storePostageDiscount).toFixed(2)
      : null
  var m46 =
    (!_vm.is_gift || _vm.is_gift == 1) && _vm.coupon_price > 0
      ? _vm.$t("Khấu trừ phiếu giảm giá")
      : null
  var m47 =
    (!_vm.is_gift || _vm.is_gift == 1) && _vm.coupon_price > 0
      ? _vm.$t("￥")
      : null
  var g8 =
    (!_vm.is_gift || _vm.is_gift == 1) && _vm.coupon_price > 0
      ? parseFloat(_vm.coupon_price).toFixed(2)
      : null
  var m48 =
    (!_vm.is_gift || _vm.is_gift == 1) && _vm.integral_price > 0
      ? _vm.$t("Trừ điểm")
      : null
  var m49 =
    (!_vm.is_gift || _vm.is_gift == 1) && _vm.integral_price > 0
      ? _vm.$t("￥")
      : null
  var g9 =
    (!_vm.is_gift || _vm.is_gift == 1) && _vm.integral_price > 0
      ? parseFloat(_vm.integral_price).toFixed(2)
      : null
  var m50 = !_vm.is_gift || _vm.is_gift == 1 ? _vm.$t("tổng cộng") : null
  var m51 = !_vm.is_gift || _vm.is_gift == 1 ? _vm.$t("￥") : null
  var g10 =
    !_vm.is_gift || _vm.is_gift == 1
      ? (_vm.valid_count > 0 && !_vm.discount_id) ||
        (_vm.valid_count == _vm.cartInfo.length && _vm.discount_id)
      : null
  var m52 =
    (!_vm.is_gift || _vm.is_gift == 1) && g10
      ? _vm.$t("Gửi đơn đặt hàng")
      : null
  var m53 =
    (!_vm.is_gift || _vm.is_gift == 1) && !g10
      ? _vm.$t("Gửi đơn đặt hàng")
      : null
  if (!_vm._isMounted) {
    _vm.e0 = function ($event) {
      _vm.inputTrip = false
    }
    _vm.e1 = function ($event) {
      $event.stopPropagation()
      _vm.Debounce(_vm.SubOrder())
    }
  }
  _vm.$mp.data = Object.assign(
    {},
    {
      $root: {
        m0: m0,
        m1: m1,
        m2: m2,
        m3: m3,
        m4: m4,
        m5: m5,
        m6: m6,
        m7: m7,
        g0: g0,
        m8: m8,
        g1: g1,
        m9: m9,
        m10: m10,
        m11: m11,
        m12: m12,
        m13: m13,
        m14: m14,
        m15: m15,
        m16: m16,
        m17: m17,
        m18: m18,
        m19: m19,
        m20: m20,
        m21: m21,
        m22: m22,
        m23: m23,
        m24: m24,
        m25: m25,
        m26: m26,
        g2: g2,
        l0: l0,
        m33: m33,
        m34: m34,
        m35: m35,
        m36: m36,
        m37: m37,
        m38: m38,
        m39: m39,
        g4: g4,
        m40: m40,
        m41: m41,
        g5: g5,
        m42: m42,
        m43: m43,
        g6: g6,
        m44: m44,
        m45: m45,
        g7: g7,
        m46: m46,
        m47: m47,
        g8: g8,
        m48: m48,
        m49: m49,
        g9: g9,
        m50: m50,
        m51: m51,
        g10: g10,
        m52: m52,
        m53: m53,
      },
    }
  )
}
var recyclableRender = false
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ 277:
/*!***************************************************************************************************************************!*\
  !*** /Users/tinhp/Workspace/Projects/CRMEB/template/uni-app/pages/goods/order_confirm/index.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_13_1_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/babel-loader/lib!../../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--13-1!../../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/webpack-uni-mp-loader/lib/script.js!../../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/vue-cli-plugin-uni/packages/vue-loader/lib??vue-loader-options!../../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/webpack-uni-mp-loader/lib/style.js!./index.vue?vue&type=script&lang=js& */ 278);
/* harmony import */ var _Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_13_1_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_13_1_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_13_1_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_13_1_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_13_1_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ 278:
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--13-1!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/script.js!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/vue-loader/lib??vue-loader-options!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/style.js!/Users/tinhp/Workspace/Projects/CRMEB/template/uni-app/pages/goods/order_confirm/index.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function(uni) {

var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ 4);
Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.default = void 0;
var _order = __webpack_require__(/*! @/api/order.js */ 87);
var _user = __webpack_require__(/*! @/api/user.js */ 45);
var _SubscribeMessage = __webpack_require__(/*! @/utils/SubscribeMessage.js */ 188);
var _store = __webpack_require__(/*! @/api/store.js */ 88);
var _cache = __webpack_require__(/*! @/config/cache.js */ 46);
var _login = __webpack_require__(/*! @/libs/login.js */ 40);
var _vuex = __webpack_require__(/*! vuex */ 42);
var _color = _interopRequireDefault(__webpack_require__(/*! @/mixins/color */ 59));
var _debounce = _interopRequireDefault(__webpack_require__(/*! @/mixins/debounce */ 279));
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
var couponListWindow = function couponListWindow() {
  Promise.all(/*! require.ensure | components/couponListWindow/index */[__webpack_require__.e("common/vendor"), __webpack_require__.e("components/couponListWindow/index")]).then((function () {
    return resolve(__webpack_require__(/*! @/components/couponListWindow */ 1275));
  }).bind(null, __webpack_require__)).catch(__webpack_require__.oe);
};
var addressWindow = function addressWindow() {
  __webpack_require__.e(/*! require.ensure | components/addressWindow/index */ "components/addressWindow/index").then((function () {
    return resolve(__webpack_require__(/*! @/components/addressWindow */ 1438));
  }).bind(null, __webpack_require__)).catch(__webpack_require__.oe);
};
var orderGoods = function orderGoods() {
  __webpack_require__.e(/*! require.ensure | components/orderGoods/index */ "components/orderGoods/index").then((function () {
    return resolve(__webpack_require__(/*! @/components/orderGoods */ 1445));
  }).bind(null, __webpack_require__)).catch(__webpack_require__.oe);
};
var home = function home() {
  Promise.all(/*! require.ensure | components/home/index */[__webpack_require__.e("common/vendor"), __webpack_require__.e("components/home/index")]).then((function () {
    return resolve(__webpack_require__(/*! @/components/home */ 1329));
  }).bind(null, __webpack_require__)).catch(__webpack_require__.oe);
};
var invoicePicker = function invoicePicker() {
  __webpack_require__.e(/*! require.ensure | pages/goods/components/invoicePicker/index */ "pages/goods/components/invoicePicker/index").then((function () {
    return resolve(__webpack_require__(/*! ../components/invoicePicker/index.vue */ 1452));
  }).bind(null, __webpack_require__)).catch(__webpack_require__.oe);
};
var authorize = function authorize() {
  __webpack_require__.e(/*! require.ensure | components/Authorize */ "components/Authorize").then((function () {
    return resolve(__webpack_require__(/*! @/components/Authorize */ 1215));
  }).bind(null, __webpack_require__)).catch(__webpack_require__.oe);
};
var payment = function payment() {
  Promise.all(/*! require.ensure | components/payment/index */[__webpack_require__.e("common/vendor"), __webpack_require__.e("components/payment/index")]).then((function () {
    return resolve(__webpack_require__(/*! @/components/payment */ 1459));
  }).bind(null, __webpack_require__)).catch(__webpack_require__.oe);
};
var _default = {
  components: {
    payment: payment,
    invoicePicker: invoicePicker,
    couponListWindow: couponListWindow,
    addressWindow: addressWindow,
    orderGoods: orderGoods,
    home: home,
    authorize: authorize
  },
  mixins: [_color.default, _debounce.default],
  data: function data() {
    var currentDate = this.getDate({
      format: true
    });
    return {
      confirm: '',
      //Tin nhắn tùy chỉnh
      date: this.$t("Vui l\xF2ng ch\u1ECDn"),
      time: this.$t("Vui l\xF2ng ch\u1ECDn"),
      canvasWidth: "",
      canvasHeight: "",
      canvasStatus: false,
      newImg: [],
      textareaStatus: true,
      //Phương thức thanh toán
      cartArr: [{
        "name": this.$t("WeChat tr\u1EA3 ti\u1EC1n"),
        "icon": "icon-weixin2",
        value: 'weixin',
        title: this.$t("S\u1EED d\u1EE5ng Thanh to\xE1n nhanh WeChat"),
        payStatus: 1
      }, {
        "name": this.$t("thanh to\xE1n Alipay"),
        "icon": "icon-zhifubao",
        value: 'alipay',
        title: this.$t("Thanh to\xE1n b\u1EB1ng Alipay"),
        payStatus: 1
      }, {
        "name": this.$t("thanh to\xE1n s\u1ED1 d\u01B0"),
        "icon": "icon-yuezhifu",
        value: 'yue',
        title: this.$t("s\u1ED1 d\u01B0 kh\u1EA3 d\u1EE5ng"),
        payStatus: 1
      }, {
        "name": this.$t("Thanh to\xE1n ngo\u1EA1i tuy\u1EBFn"),
        "icon": "icon-yuezhifu1",
        value: 'offline',
        title: this.$t("S\u1EED d\u1EE5ng thanh to\xE1n ngo\u1EA1i tuy\u1EBFn"),
        payStatus: 2
      }, {
        "name": this.$t("B\u1EA1n b\xE8 tr\u1EA3 ti\u1EC1n thay m\u1EB7t"),
        "icon": "icon-haoyoudaizhifu",
        value: 'friend',
        title: this.$t("Thanh to\xE1n v\u1EDBi b\u1EA1n b\xE8 WeChat"),
        payStatus: 1
      }],
      virtual_type: 0,
      allPrice: 0,
      formContent: '',
      payType: '',
      //Phương thức thanh toán
      openType: 1,
      //Cách mở phiếu giảm giá 1=sử dụng
      active: 0,
      //Chuyển đổi phương thức thanh toán
      coupon: {
        coupon: false,
        list: [],
        statusTile: this.$t("S\u1EED d\u1EE5ng ngay b\xE2y gi\u1EDD")
      },
      //Thành phần phiếu giảm giá
      address: {
        address: false
      },
      //thành phần địa chỉ
      addressInfo: {},
      //Thông tin địa chỉ
      pinkId: 0,
      //Chia sẻ nhómid
      addressId: 0,
      //Địa chỉid
      couponId: 0,
      //Phiếu giảm giáid
      cartId: '',
      //giỏ hàngid
      orderId: '',
      // Đặt hàngid, Truyền tham số trên trang nhận quà
      BargainId: 0,
      combinationId: 0,
      seckillId: 0,
      discountId: 0,
      userInfo: {},
      //Thông tin người dùng
      mark: '',
      //Bình luận
      couponTitle: this.$t("Vui l\xF2ng ch\u1ECDn"),
      //Phiếu giảm giá
      coupon_price: 0,
      //Số tiền khấu trừ phiếu giảm giá
      useIntegral: false,
      //Có nên sử dụng điểm không
      integral_price: 0,
      //Số tiền trừ điểm
      integral: 0,
      usable_integral: 0,
      ChangePrice: 0,
      //Sử dụng điểm để bù đắp số tiền đã thay đổi
      formIds: [],
      //sưu tầmformid
      status: 0,
      is_address: false,
      toPay: false,
      //Đã khắc phục sự cố trang bị ẩn và trang được làm mới khi nhập thanh toán
      shippingType: 0,
      system_store: {},
      storePostage: 0,
      advanceId: 0,
      gift_mark: 'Đây là một món quà dành cho bạn~',
      // tin nhắn quà tặng
      contacts: '',
      contactsTel: '',
      mydata: {},
      storeList: [],
      store_self_mention: 0,
      cartInfo: [],
      priceGroup: {},
      animated: false,
      totalPrice: 0,
      integralRatio: "0",
      pagesUrl: "",
      orderKey: "",
      // usableCoupon: {},
      offlinePostage: "",
      isAuto: false,
      //Nếu không có ủy quyền, nó sẽ không được ủy quyền tự động.
      isShowAuth: false,
      //Có ẩn ủy quyền hay không
      from: '',
      news: 1,
      // invTitle: 'Không xuất hóa đơn',
      invTitle: this.$t("Kh\xF4ng xu\u1EA5t h\xF3a \u0111\u01A1n"),
      special_invoice: false,
      invoice_func: false,
      header_type: '',
      invShow: false,
      invList: [],
      invChecked: '',
      urlQuery: '',
      pay_close: false,
      noCoupon: 0,
      valid_count: 0,
      discount_id: 0,
      is_shipping: true,
      inputTrip: false,
      focus: true,
      integral_open: false,
      jumpData: {},
      is_gift: 0,
      // 1 Mua quà 2Nhận quà
      giftData: null
    };
  },
  computed: (0, _vuex.mapGetters)(['isLogin']),
  // watch: {
  // 	startDate() {
  // 		return this.getDate('start');
  // 	},
  // 	endDate() {
  // 		return this.getDate('end');
  // 	}
  // },
  onLoad: function onLoad(options) {
    this.from = 'routine';
    if (!options.cartId && !options.order_id) return this.$util.Tips({
      title: this.$t("Vui l\xF2ng ch\u1ECDn s\u1EA3n ph\u1EA9m b\u1EA1n mu\u1ED1n mua")
    }, {
      tab: 3,
      url: 1
    });
    if (options.is_gift) {
      this.is_gift = Number(options.is_gift);
    }
    this.couponId = options.couponId || 0;
    this.noCoupon = Number(options.noCoupon) || 0;
    this.pinkId = options.pinkId ? parseInt(options.pinkId) : 0;
    this.addressId = options.addressId || 0;
    this.cartId = options.cartId;
    this.orderId = options.order_id || 0;
    this.is_address = options.is_address ? true : false;
    this.news = !options.new || options.new === '0' ? 0 : 1;
    this.invChecked = options.invoice_id || '';
    this.header_type = options.header_type || '1';
    this.couponTitle = options.couponTitle || this.$t("Vui l\xF2ng ch\u1ECDn");
    if (options.invoice_id) {
      var name = '';
      name += options.header_type == 1 ? this.$t("ri\xEAng t\u01B0") : this.$t("doanh nghi\u1EC7p");
      name += options.invoice_type == 1 ? this.$t("b\xECnh th\u01B0\u1EDDng") : this.$t("t\u1EADn t\u1EE5y");
      name += this.$t("h\xF3a \u0111\u01A1n");
      this.invTitle = name;
    }
    this.textareaStatus = true;
    if (this.isLogin && this.toPay == false && (this.is_gift == 0 || this.is_gift == 1)) {
      this.checkShipping();
    } else if (this.is_gift && this.isLogin) {
      this.getOrderDetail();
    } else {
      (0, _login.toLogin)();
    }
  },
  /**
   * Chức năng vòng đời - hiển thị trang giám sát
   */
  onShow: function onShow() {
    var _this = this;
    uni.$on("handClick", function (res) {
      if (res) {
        _this.system_store = res.address;
      }
      // nghe rõ ràng
      uni.$off('handClick');
    });

    // Nếu chế độ hiện tại là lấy hàng tại cửa hàng, hãy lấy lại vị trí để đảm bảo danh sách cửa hàng là chính xác
    if (this.shippingType == 1 && !this.system_store.name) {
      this.refreshLocationAndStores();
    }
  },
  methods: {
    /**
     * Làm mới định vị và cập nhật danh sách cửa hàng
     */
    refreshLocationAndStores: function refreshLocationAndStores() {
      var _this2 = this;
      var that = this;
      uni.getLocation({
        type: 'wgs84',
        success: function success(res) {
          uni.setStorageSync('user_latitude', res.latitude);
          uni.setStorageSync('user_longitude', res.longitude);
        },
        fail: function fail(err) {
          // Xử lý khi lấy vị trí không thành công, vẫn sử dụng vị trí được lưu trong bộ nhớ đệm
          console.log('Không thể lấy được vị trí:', err);
        },
        complete: function complete() {
          _this2.getList();
        }
      });
    },
    checkShipping: function checkShipping() {
      var _this3 = this;
      var that = this;
      (0, _order.checkShipping)(that.cartId, that.news).then(function (res) {
        if (res.data.type == 0) {
          that.is_shipping = true;
          that.shippingType = 0;
          _this3.getaddressInfo();
          _this3.getConfirm();
          _this3.$nextTick(function () {
            this.$refs.addressWindow.getAddressList();
          });
        } else {
          if (res.data.type == 1) {
            that.is_shipping = false;
            that.shippingType = 0;
            _this3.getaddressInfo();
            _this3.getConfirm();
            _this3.$nextTick(function () {
              this.$refs.addressWindow.getAddressList();
            });
          } else if (res.data.type == 2) {
            that.is_shipping = false;
            that.shippingType = 1;
            _this3.addressType(1);
            _this3.getConfirm();
            _this3.getList();
          }
        }
      }).catch(function (err) {
        uni.showToast({
          title: err,
          icon: 'none'
        });
      });
    },
    // Không xuất hóa đơn
    invCancel: function invCancel() {
      this.invChecked = '';
      this.invTitle = this.$t("Kh\xF4ng xu\u1EA5t h\xF3a \u0111\u01A1n");
      this.invShow = false;
    },
    // Chọn hóa đơn
    invChange: function invChange(id) {
      var name = '';
      this.invChecked = id;
      this.invShow = false;
      var result = this.invList.find(function (item) {
        return item.id === id;
      });
      name += result.header_type === 1 ? this.$t("ri\xEAng t\u01B0") : this.$t("doanh nghi\u1EC7p");
      name += result.type === 1 ? this.$t("b\xECnh th\u01B0\u1EDDng") : this.$t("t\u1EADn t\u1EE5y");
      name += this.$t("h\xF3a \u0111\u01A1n");
      this.invTitle = name;
    },
    openList: function openList() {
      if (this.shippingType == 0) {
        this.onAddress();
      } else {
        this.showStoreList();
      }
    },
    // Đóng hóa đơn
    invClose: function invClose() {
      this.invShow = false;
      this.getInvoiceList();
    },
    getInvoiceList: function getInvoiceList() {
      var _this4 = this;
      uni.showLoading({
        title: this.$t("\u0110ang t\u1EA3i")
      });
      (0, _user.invoiceList)().then(function (res) {
        uni.hideLoading();
        _this4.invList = res.data.map(function (item) {
          item.id = item.id.toString();
          return item;
        });
        var result = _this4.invList.find(function (item) {
          return item.id == _this4.invChecked;
        });
        if (result) {
          var name = '';
          name += result.header_type === 1 ? _this4.$t("ri\xEAng t\u01B0") : _this4.$t("doanh nghi\u1EC7p");
          name += result.type === 1 ? _this4.$t("b\xECnh th\u01B0\u1EDDng") : _this4.$t("t\u1EADn t\u1EE5y");
          name += _this4.$t("h\xF3a \u0111\u01A1n");
          _this4.invTitle = name;
        }
      }).catch(function (err) {
        uni.showToast({
          title: err,
          icon: 'none'
        });
      });
    },
    /**
     * Hóa đơn
     */
    goInvoice: function goInvoice() {
      this.getInvoiceList();
      this.invShow = true;
      this.urlQuery = "new=".concat(this.news, "&cartId=").concat(this.cartId, "&pinkId=").concat(this.pinkId, "&couponId=").concat(this.couponId, "&addressId=").concat(this.addressId, "&specialInvoice=").concat(this.special_invoice, "&couponTitle=").concat(this.couponTitle);
    },
    /**
     * Sự kiện gọi lại ủy quyền
     * 
     */
    onLoadFun: function onLoadFun() {
      this.getaddressInfo();
      this.getConfirm();
      //Gọi phương thức trang con để lấy danh sách địa chỉ sau khi được ủy quyền.
      // this.$scope.selectComponent('#address-window').getAddressList();
    },

    /**
     * gọi lại sự kiện
     *
     */
    onChangeFun: function onChangeFun(e) {
      var opt = e;
      var action = opt.action || null;
      var value = opt.value != undefined ? opt.value : null;
      action && this[action] && this[action](value);
    },
    payClose: function payClose() {
      this.pay_close = false;
    },
    goPay: function goPay() {
      this.pay_close = true;
    },
    payCheck: function payCheck(type) {
      this.payType = type;
      this.SubOrder();
    },
    /**
     * Lấy dữ liệu danh sách cửa hàng
     */
    getList: function getList() {
      var _this5 = this;
      var longitude = uni.getStorageSync("user_longitude") || ''; //kinh độ
      var latitude = uni.getStorageSync("user_latitude") || ''; //vĩ độ
      var data = {
        latitude: latitude,
        //vĩ độ
        longitude: longitude,
        //kinh độ
        page: 1,
        limit: 10
      };
      (0, _store.storeListApi)(data).then(function (res) {
        var list = res.data.list.list || [];
        _this5.$set(_this5, 'storeList', list);
        _this5.$set(_this5, 'system_store', list[0]);
      }).catch(function (err) {});
    },
    // Đóng cửa sổ bật lên địa chỉ；
    changeClose: function changeClose() {
      this.$set(this.address, 'address', false);
    },
    /*
     * Chuyển đến danh sách cửa hàng
     */
    showStoreList: function showStoreList() {
      var _this = this;
      if (this.storeList.length > 0) {
        uni.navigateTo({
          url: '/pages/goods/goods_details_store/index'
        });
      }
    },
    changePayType: function changePayType(type) {
      this.payType = type;
      this.computedPrice();
    },
    computedPrice: function computedPrice() {
      var _this6 = this;
      var shippingType = this.shippingType;
      var data = {
        addressId: this.addressId,
        useIntegral: this.useIntegral ? 1 : 0,
        couponId: this.couponId,
        shipping_type: parseInt(shippingType) + 1,
        payType: this.payType
      };
      if (this.is_gift) data.is_gift = this.is_gift;
      (0, _order.postOrderComputed)(this.orderKey, data).then(function (res) {
        var result = res.data.result;
        if (result) {
          _this6.totalPrice = result.pay_price;
          _this6.integral_price = result.deduction_price;
          _this6.coupon_price = result.coupon_price;
          _this6.integral = _this6.useIntegral ? result.SurplusIntegral : _this6.usable_integral;
          _this6.$set(_this6.priceGroup, 'storePostage', shippingType == 1 ? 0 : result.pay_postage);
          _this6.$set(_this6.priceGroup, 'storePostageDiscount', result.storePostageDiscount);
        }
      });
    },
    addressType: function addressType(e) {
      var _this7 = this;
      var index = e;
      var that = this;
      if (this.shippingType == parseInt(index)) return;
      this.shippingType = parseInt(index);
      if (index == 1) {
        uni.getLocation({
          type: 'wgs84',
          success: function success(res) {
            uni.setStorageSync('user_latitude', res.latitude);
            uni.setStorageSync('user_longitude', res.longitude);
          },
          complete: function complete() {
            _this7.getList();
          }
        });
      }
      ;
      this.$nextTick(function (e) {
        if (!_this7.is_gift) {
          _this7.getConfirm();
          _this7.computedPrice();
        }
      });
    },
    bindPickerChange: function bindPickerChange(e) {
      var value = e.detail.value;
      this.shippingType = value;
      this.computedPrice();
    },
    ChangCouponsClone: function ChangCouponsClone() {
      this.$set(this.coupon, 'coupon', false);
    },
    changeTextareaStatus: function changeTextareaStatus() {
      for (var i = 0, len = this.coupon.list.length; i < len; i++) {
        this.coupon.list[i].use_title = '';
        this.coupon.list[i].is_use = 0;
      }
      this.textareaStatus = true;
      this.status = 0;
      this.$set(this.coupon, 'list', this.coupon.list);
    },
    /**
     * Xử lý các sự kiện sau khi nhấp vào phiếu giảm giá
     * 
     */
    ChangCoupons: function ChangCoupons(e) {
      // this.usableCoupon = e
      // this.coupon.coupon = false
      var index = e,
        list = this.coupon.list,
        couponTitle = this.$t("Vui l\xF2ng ch\u1ECDn"),
        couponId = 0;
      for (var i = 0, len = list.length; i < len; i++) {
        if (i != index) {
          list[i].use_title = '';
          list[i].is_use = 0;
        }
      }
      if (list[index].is_use) {
        //Không có phiếu giảm giá nào được sử dụng
        list[index].use_title = '';
        list[index].is_use = 0;
      } else {
        //Sử dụng phiếu giảm giá
        list[index].use_title = this.$t("Kh\xF4ng \u0111\u01B0\u1EE3c s\u1EED d\u1EE5ng");
        list[index].is_use = 1;
        couponTitle = list[index].coupon_title;
        couponId = list[index].id;
      }
      this.couponTitle = couponTitle;
      this.couponId = couponId;
      this.$set(this.coupon, 'coupon', false);
      this.$set(this.coupon, 'list', list);
      this.computedPrice();
    },
    /**
     * Dùng điểm để trừ
     */
    ChangeIntegral: function ChangeIntegral() {
      this.useIntegral = !this.useIntegral;
      this.computedPrice();
    },
    /**
     * Thay đổi sự kiện sau khi chọn địa chỉ
     * @param object e
     */
    OnChangeAddress: function OnChangeAddress(e) {
      this.textareaStatus = true;
      this.addressId = e;
      this.address.address = false;
      this.getaddressInfo();
      if (!this.is_gift) {
        this.getConfirm();
        this.computedPrice();
      }
    },
    bindHideKeyboard: function bindHideKeyboard(e) {
      this.mark = e.detail.value;
    },
    getOrderDetail: function getOrderDetail() {
      var _this8 = this;
      (0, _order.getGiftOrderDetail)(this.orderId).then(function (res) {
        _this8.giftData = res.data;
        _this8.$set(_this8, 'cartInfo', res.data.cartInfo);
        _this8.store_self_mention = res.data.store_self_mention;
        if (res.data.type == 0) {
          _this8.is_shipping = true;
          _this8.shippingType = 0;
          _this8.getaddressInfo();
          _this8.$nextTick(function () {
            _this8.$refs.addressWindow.getAddressList();
          });
        } else {
          if (res.data.type == 1) {
            _this8.is_shipping = false;
            _this8.shippingType = 0;
            _this8.getaddressInfo();
            _this8.$nextTick(function () {
              _this8.$refs.addressWindow.getAddressList();
            });
          } else if (res.data.type == 2) {
            _this8.is_shipping = false;
            _this8.shippingType = 1;
            _this8.addressType(1);
            _this8.getList();
          }
        }
      });
    },
    /**
     * Nhận chi tiết đơn hàng hiện tại
     * 
     */
    getConfirm: function getConfirm() {
      var _this9 = this;
      var that = this;
      // return;
      uni.showLoading({
        title: that.$t("\u0110ang t\u1EA3i"),
        mask: true
      });
      var data = {
        cartId: that.cartId,
        new: that.news,
        addressId: that.addressId,
        shipping_type: that.shippingType + 1
      };
      if (that.is_gift) data.is_gift = that.is_gift;
      (0, _order.orderConfirm)(data).then(function (res) {
        that.$set(that, 'userInfo', res.data.userInfo);
        that.$set(that, 'confirm', res.data.custom_form || []);
        _this9.confirm.map(function (e) {
          if (e.label === 'img') e.value = [];
        });
        that.$set(that, 'integral', res.data.usable_integral);
        that.$set(that, 'usable_integral', res.data.usable_integral);
        that.$set(that, 'contacts', res.data.userInfo.real_name);
        that.$set(that, 'contactsTel', res.data.userInfo.record_phone === '0' ? res.data.userInfo.phone : res.data.userInfo.record_phone);
        that.$set(that, 'cartInfo', res.data.cartInfo);
        that.$set(that, 'integralRatio', res.data.integralRatio);
        that.$set(that, 'offlinePostage', res.data.offlinePostage);
        that.$set(that, 'orderKey', res.data.orderKey);
        that.$set(that, 'valid_count', res.data.valid_count);
        that.$set(that, 'discount_id', res.data.discount_id);
        that.$set(that, 'priceGroup', res.data.priceGroup);
        that.$set(that, 'totalPrice', that.$util.$h.Add(parseFloat(res.data.priceGroup.totalPrice), parseFloat(res.data.priceGroup.storePostage)));
        that.$set(that, 'allPrice', that.$util.$h.Add(parseFloat(res.data.priceGroup.totalPrice), parseFloat(res.data.priceGroup.vipPrice)).toFixed(2));
        that.$set(that, 'seckillId', parseInt(res.data.seckill_id));
        that.$set(that, 'invoice_func', res.data.invoice_func);
        that.$set(that, 'special_invoice', res.data.special_invoice);
        that.$set(that, 'store_self_mention', res.data.store_self_mention);
        that.$set(that, 'virtual_type', res.data.virtual_type || 0);
        that.$set(that, 'integral_open', res.data.integral_open);
        uni.hideLoading();
        //Thanh toán WeChat có được bật không?
        that.cartArr[0].payStatus = res.data.pay_weixin_open || 0;
        //Alipay có được kích hoạt không?
        that.cartArr[1].payStatus = res.data.ali_pay_status || 0;
        that.cartArr[1].payStatus = 0;

        //Thanh toán số dư có được kích hoạt không?
        // that.cartArr[2].title = 'số dư khả dụng:' + res.data.userInfo.now_money;
        that.cartArr[2].number = res.data.userInfo.now_money;
        that.cartArr[2].payStatus = res.data.yue_pay_status == 1 ? res.data.yue_pay_status : 0;
        if (res.data.offline_pay_status == 2 || res.data.deduction) {
          that.cartArr[3].payStatus = 0;
        } else {
          that.cartArr[3].payStatus = 1;
        }
        //Thanh toán cho bạn bè có được kích hoạt không?
        that.cartArr[4].payStatus = res.data.friend_pay_status || 0;
        // that.$set(that, 'cartArr', that.cartArr);
        that.$set(that, 'ChangePrice', that.totalPrice);
        that.getBargainId();
        setTimeout(function () {
          that.getCouponList();
        }, 500);
        if (_this9.addressId && !_this9.is_gift) {
          _this9.computedPrice();
        }
      }).catch(function (err) {
        uni.hideLoading();
        return _this9.$util.Tips({
          title: err
        });
      });
    },
    /*
     * Trích xuất thương lượng và chia sẻ nhómid
     */
    getBargainId: function getBargainId() {
      var that = this;
      var cartINfo = that.cartInfo;
      var BargainId = 0;
      var combinationId = 0;
      var discountId = 0;
      var advanceId = 0;
      cartINfo.forEach(function (value, index, cartINfo) {
        BargainId = cartINfo[index].bargain_id, combinationId = cartINfo[index].combination_id, discountId = cartINfo[index].discount_id, advanceId = cartINfo[index].advance_id;
      });
      that.$set(that, 'BargainId', parseInt(BargainId));
      that.$set(that, 'combinationId', parseInt(combinationId));
      that.$set(that, 'discountId', parseInt(discountId));
      that.$set(that, 'advanceId', parseInt(advanceId));
      if (that.cartArr.length == 3 && (BargainId || combinationId || that.seckillId || discountId)) {
        that.cartArr[2].payStatus = 0;
        that.$set(that, 'cartArr', that.cartArr);
      }
    },
    /**
     * Nhận phiếu giảm giá có sẵn cho số tiền hiện tại
     * 
     */
    getCouponList: function getCouponList() {
      var _this10 = this;
      var shippingType = this.shippingType;
      var that = this;
      var data = {
        cartId: this.cartId,
        'new': this.news,
        'shippingType': parseInt(shippingType) + 1
      };
      (0, _order.getCouponsOrderPrice)(this.totalPrice, data).then(function (res) {
        _this10.$set(_this10.coupon, 'list', res.data);
        if (!_this10.pinkId && !_this10.BargainId && !_this10.combinationId && !_this10.seckillId && !_this10.noCoupon && !_this10.discountId && !_this10.advanceId) {
          _this10.coupon.list.length && _this10.ChangCoupons(0);
        }
        _this10.openType = 1;
      });
    },
    /*
     * Nhận địa chỉ giao hàng mặc định hoặc lấy thông tin địa chỉ nhất định
     */
    getaddressInfo: function getaddressInfo() {
      var that = this;
      var fnc = that.addressId ? _user.getAddressDetail : _user.getAddressDefault;
      fnc(that.addressId).then(function (res) {
        if (!Array.isArray(res.data)) {
          res.data.is_default = parseInt(res.data.is_default);
          that.addressInfo = res.data || {};
          that.addressId = res.data.id || 0;
          that.address.addressId = res.data.id || 0;
        }
      });
    },
    onHaveAddressList: function onHaveAddressList() {
      this.haveAddressList = true;
    },
    payItem: function payItem(e) {
      var that = this;
      var active = e;
      that.active = active;
      that.animated = true;
      that.payType = that.cartArr[active].value;
      that.computedPrice();
      setTimeout(function () {
        that.car();
      }, 500);
    },
    couponTap: function couponTap() {
      var _this11 = this;
      this.coupon.coupon = true;
      this.coupon.list.forEach(function (item, index) {
        if (item.id == _this11.couponId) {
          item.is_use = 1;
        } else {
          item.is_use = 0;
        }
      });
      this.$set(this.coupon, 'list', this.coupon.list);
    },
    car: function car() {
      var that = this;
      that.animated = false;
    },
    onAddress: function onAddress() {
      var that = this;
      if (this.addressInfo.real_name || this.haveAddressList) {
        this.$refs.addressWindow.getAddressList();
        that.textareaStatus = false;
        that.address.address = true;
        that.pagesUrl = '/pages/users/user_address_list/index?news=' + this.news + '&cartId=' + this.cartId + '&pinkId=' + this.pinkId + '&couponId=' + this.couponId + '&is_gift=' + that.is_gift + '&order_id=' + that.orderId;
      } else {
        var url = '/pages/users/user_address/index?cartId=' + this.cartId + '&pinkId=' + this.pinkId + '&couponId=' + this.couponId + '&new=' + this.news + '&is_gift=' + that.is_gift + '&order_id=' + that.orderId;
        uni.navigateTo({
          url: url
        });
      }
    },
    formpost: function formpost(url, postData) {
      var tempform = document.createElement("form");
      tempform.action = url;
      tempform.method = "post";
      tempform.target = "_self";
      tempform.style.display = "none";
      for (var x in postData) {
        var opt = document.createElement("input");
        opt.name = x;
        opt.value = postData[x];
        tempform.appendChild(opt);
      }
      document.body.appendChild(tempform);
      this.$nextTick(function (e) {
        tempform.submit();
      });
    },
    payment: function payment(data) {
      var that = this;
      (0, _order.orderCreate)(that.orderKey, data).then(function (res) {
        var url = "/pages/goods/cashier/index?order_id=".concat(res.data.result.orderId, "&from_type=order");
        uni.reLaunch({
          url: url
        });
      }).catch(function (err) {
        uni.hideLoading();
        return that.$util.Tips({
          title: err
        });
      });
    },
    clickTextArea: function clickTextArea() {
      this.$refs.textarea.focus();
    },
    SubOrder: function SubOrder(e) {
      var that = this,
        data = {};
      if (!that.addressId && !that.shippingType && !that.virtual_type && !that.is_gift) return that.$util.Tips({
        title: that.$t("Vui l\xF2ng ch\u1ECDn \u0111\u1ECBa ch\u1EC9 giao h\xE0ng")
      });
      if (that.shippingType == 1) {
        if (that.contacts == "" || that.contactsTel == "") {
          return that.$util.Tips({
            title: that.$t("Vui l\xF2ng \u0111i\u1EC1n ng\u01B0\u1EDDi li\xEAn h\u1EC7 ho\u1EB7c s\u1ED1 \u0111i\u1EC7n tho\u1EA1i li\xEAn h\u1EC7")
          });
        }
        if (!/^1(3|4|5|7|8|9|6)\d{9}$/.test(that.contactsTel)) {
          return that.$util.Tips({
            title: that.$t("Vui l\xF2ng nh\u1EADp \u0111\xFAng s\u1ED1 \u0111i\u1EC7n tho\u1EA1i di \u0111\u1ED9ng")
          });
        }
        if (!that.contacts) {
          return that.$util.Tips({
            title: that.$t("Vui l\xF2ng nh\u1EADp t\xEAn")
          });
        }
        if (that.storeList.length == 0) return that.$util.Tips({
          title: that.$t("Ch\u01B0a c\xF3 c\u1EEDa h\xE0ng,Vui l\xF2ng ch\u1ECDn ph\u01B0\u01A1ng ph\xE1p kh\xE1c")
        });
      }
      for (var i = 0; i < that.confirm.length; i++) {
        var _data = that.confirm[i];
        if (_data.status) {
          if (_data.label === 'text' || _data.label === 'data' || _data.label === 'time' || _data.label === 'id') {
            if (!_data.value.trim()) {
              return uni.showToast({
                title: that.$t("Vui l\xF2ng nh\u1EADp") + "".concat(_data.title),
                icon: 'none'
              });
            }
          }
          if (_data.label === 'number') {
            if (_data.value <= 0) {
              return uni.showToast({
                title: that.$t("Vui l\xF2ng nh\u1EADp") + "".concat(_data.title),
                icon: 'none'
              });
            }
          }
          if (_data.label === 'email') {
            if (!/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(_data.value)) {
              return uni.showToast({
                title: that.$t("Vui l\xF2ng nh\u1EADp \u0111\xFAng") + "".concat(_data.title),
                icon: 'none'
              });
            }
          }
          if (_data.label === 'phone') {
            if (!/^1(3|4|5|7|8|9|6)\d{9}$/i.test(_data.value)) {
              return uni.showToast({
                title: that.$t("Vui l\xF2ng nh\u1EADp \u0111\xFAng") + "".concat(_data.title),
                icon: 'none'
              });
            }
          }
          if (_data.label === 'img') {
            if (!_data.value.length) {
              return uni.showToast({
                title: that.$t("Vui l\xF2ng t\u1EA3i l\xEAn") + "".concat(_data.title),
                icon: 'none'
              });
            }
          }
        }
      }
      data = {
        custom_form: that.confirm,
        gift_mark: that.gift_mark,
        // tin nhắn quà tặng
        real_name: that.contacts,
        phone: that.contactsTel,
        addressId: that.addressId,
        formId: '',
        couponId: that.couponId,
        useIntegral: that.useIntegral,
        bargainId: that.BargainId,
        combinationId: that.combinationId,
        discountId: that.discountId,
        pinkId: that.pinkId,
        advanceId: that.advanceId,
        seckill_id: that.seckillId,
        mark: that.mark,
        store_id: that.system_store ? that.system_store.id : 0,
        'from': that.from,
        shipping_type: that.$util.$h.Add(that.shippingType, 1),
        'new': that.news,
        'invoice_id': that.invChecked
      };
      if (that.is_gift) data.is_gift = that.is_gift;
      if (data.payType == 'yue' && parseFloat(that.userInfo.now_money) < parseFloat(that.totalPrice)) return that.$util.Tips({
        title: that.$t("S\u1ED1 d\u01B0 kh\xF4ng \u0111\u1EE7")
      });
      // uni.showLoading({
      // 	title: that.$t(`Đang thanh toán đơn hàng`)
      // });

      (0, _SubscribeMessage.openPaySubscribe)().then(function () {
        that.payment(data);
      });
    },
    receiveGift: function receiveGift() {
      var _this12 = this;
      var data = {
        gift_key: this.giftData.gift_key,
        shipping_type: this.$util.$h.Add(this.shippingType, 1),
        name: this.contacts,
        phone: this.contactsTel,
        address_id: this.addressId,
        store_id: this.system_store ? this.system_store.id : 0
      };
      (0, _order.orderReceiveGift)(this.orderId, data).then(function (res) {
        uni.reLaunch({
          url: "/pages/goods/receive_gifts_status/index?status=".concat(res.data.status, "&order_id=").concat(_this12.giftData.order_id)
        });
      }).catch(function (err) {
        uni.showToast({
          icon: 'none',
          title: err
        });
      });
    },
    bindDateChange: function bindDateChange(e, index) {
      this.confirm[index].value = e.target.value;
    },
    bindTimeChange: function bindTimeChange(e, index) {
      this.confirm[index].value = e.target.value;
    },
    getDate: function getDate(type) {
      var date = new Date();
      var year = date.getFullYear();
      var month = date.getMonth() + 1;
      var day = date.getDate();
      if (type === 'start') {
        year = year - 60;
      } else if (type === 'end') {
        year = year + 2;
      }
      month = month > 9 ? month : '0' + month;
      day = day > 9 ? day : '0' + day;
      return "".concat(year, "-").concat(month, "-").concat(day);
    },
    uploadpic: function uploadpic(index, item) {
      var _this13 = this;
      var that = this;
      this.canvasStatus = true;
      that.$util.uploadImageChange('upload/image', function (res) {
        item.value.push(res.data.url);
      }, function (res) {
        _this13.canvasStatus = false;
      }, function (res) {
        _this13.canvasWidth = res.w;
        _this13.canvasHeight = res.h;
      });
    },
    DelPic: function DelPic(index, indexs) {
      var that = this,
        pic = this.confirm[index].value;
      that.confirm[index].value.splice(indexs, 1);
      // that.$set(that, 'pics', that.pics);
    },
    inputTripClick: function inputTripClick() {
      this.inputTrip = true;
      // this.$refs.trip.foucs()
    },
    showMaoLocation: function showMaoLocation(e) {
      var self = this;
      uni.openLocation({
        latitude: Number(e.latitude),
        longitude: Number(e.longitude),
        name: e.name,
        address: "".concat(e.address, "-").concat(e.detailed_address),
        success: function success() {
          Number;
        }
      });
    },
    call: function call(phone) {
      uni.makePhoneCall({
        phoneNumber: phone
      });
    }
  }
};
exports.default = _default;
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./node_modules/@dcloudio/uni-mp-weixin/dist/index.js */ 2)["default"]))

/***/ }),

/***/ 280:
/*!************************************************************************************************************************************************************!*\
  !*** /Users/tinhp/Workspace/Projects/CRMEB/template/uni-app/pages/goods/order_confirm/index.vue?vue&type=style&index=0&id=1a7ecab0&lang=scss&scoped=true& ***!
  \************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_8_oneOf_1_0_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_css_loader_dist_cjs_js_ref_8_oneOf_1_1_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_loaders_stylePostLoader_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_2_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_3_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_sass_loader_dist_cjs_js_ref_8_oneOf_1_4_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_5_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_index_vue_vue_type_style_index_0_id_1a7ecab0_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/mini-css-extract-plugin/dist/loader.js??ref--8-oneOf-1-0!../../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/css-loader/dist/cjs.js??ref--8-oneOf-1-1!../../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/vue-cli-plugin-uni/packages/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--8-oneOf-1-2!../../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/postcss-loader/src??ref--8-oneOf-1-3!../../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/vue-cli-plugin-uni/packages/sass-loader/dist/cjs.js??ref--8-oneOf-1-4!../../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--8-oneOf-1-5!../../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/vue-cli-plugin-uni/packages/vue-loader/lib??vue-loader-options!../../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/webpack-uni-mp-loader/lib/style.js!./index.vue?vue&type=style&index=0&id=1a7ecab0&lang=scss&scoped=true& */ 281);
/* harmony import */ var _Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_8_oneOf_1_0_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_css_loader_dist_cjs_js_ref_8_oneOf_1_1_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_loaders_stylePostLoader_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_2_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_3_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_sass_loader_dist_cjs_js_ref_8_oneOf_1_4_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_5_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_index_vue_vue_type_style_index_0_id_1a7ecab0_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_8_oneOf_1_0_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_css_loader_dist_cjs_js_ref_8_oneOf_1_1_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_loaders_stylePostLoader_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_2_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_3_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_sass_loader_dist_cjs_js_ref_8_oneOf_1_4_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_5_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_index_vue_vue_type_style_index_0_id_1a7ecab0_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_8_oneOf_1_0_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_css_loader_dist_cjs_js_ref_8_oneOf_1_1_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_loaders_stylePostLoader_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_2_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_3_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_sass_loader_dist_cjs_js_ref_8_oneOf_1_4_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_5_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_index_vue_vue_type_style_index_0_id_1a7ecab0_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_8_oneOf_1_0_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_css_loader_dist_cjs_js_ref_8_oneOf_1_1_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_loaders_stylePostLoader_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_2_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_3_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_sass_loader_dist_cjs_js_ref_8_oneOf_1_4_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_5_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_index_vue_vue_type_style_index_0_id_1a7ecab0_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_8_oneOf_1_0_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_css_loader_dist_cjs_js_ref_8_oneOf_1_1_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_loaders_stylePostLoader_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_2_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_3_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_sass_loader_dist_cjs_js_ref_8_oneOf_1_4_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_5_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_index_vue_vue_type_style_index_0_id_1a7ecab0_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ 281:
/*!****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/mini-css-extract-plugin/dist/loader.js??ref--8-oneOf-1-0!./node_modules/css-loader/dist/cjs.js??ref--8-oneOf-1-1!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--8-oneOf-1-2!./node_modules/postcss-loader/src??ref--8-oneOf-1-3!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/sass-loader/dist/cjs.js??ref--8-oneOf-1-4!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--8-oneOf-1-5!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/vue-loader/lib??vue-loader-options!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/style.js!/Users/tinhp/Workspace/Projects/CRMEB/template/uni-app/pages/goods/order_confirm/index.vue?vue&type=style&index=0&id=1a7ecab0&lang=scss&scoped=true& ***!
  \****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin
    if(false) { var cssReload; }
  

/***/ })

},[[273,"common/runtime","common/vendor","pages/goods/common/vendor"]]]);
//# sourceMappingURL=../../../../.sourcemap/mp-weixin/pages/goods/order_confirm/index.js.map