require('./common/vendor.js');(global["webpackJsonp"] = global["webpackJsonp"] || []).push([["pages/points_mall/integral_goods_details"],{

/***/ 1055:
/*!******************************************************************************************************************************!*\
  !*** /Users/tinhp/Workspace/Projects/CRMEB/template/uni-app/main.js?{"page":"pages%2Fpoints_mall%2Fintegral_goods_details"} ***!
  \******************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function(wx, createPage) {

var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ 4);
__webpack_require__(/*! uni-pages */ 30);
var _vue = _interopRequireDefault(__webpack_require__(/*! vue */ 25));
var _integral_goods_details = _interopRequireDefault(__webpack_require__(/*! ./pages/points_mall/integral_goods_details.vue */ 1056));
// @ts-ignore
wx.__webpack_require_UNI_MP_PLUGIN__ = __webpack_require__;
createPage(_integral_goods_details.default);
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./node_modules/@dcloudio/uni-mp-weixin/dist/wx.js */ 1)["default"], __webpack_require__(/*! ./node_modules/@dcloudio/uni-mp-weixin/dist/index.js */ 2)["createPage"]))

/***/ }),

/***/ 1056:
/*!***********************************************************************************************************!*\
  !*** /Users/tinhp/Workspace/Projects/CRMEB/template/uni-app/pages/points_mall/integral_goods_details.vue ***!
  \***********************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _integral_goods_details_vue_vue_type_template_id_f54bc484___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./integral_goods_details.vue?vue&type=template&id=f54bc484& */ 1057);
/* harmony import */ var _integral_goods_details_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./integral_goods_details.vue?vue&type=script&lang=js& */ 1059);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _integral_goods_details_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _integral_goods_details_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[key]; }) }(__WEBPACK_IMPORT_KEY__));
/* harmony import */ var _integral_goods_details_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./integral_goods_details.vue?vue&type=style&index=0&lang=scss& */ 1061);
/* harmony import */ var _Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/vue-cli-plugin-uni/packages/vue-loader/lib/runtime/componentNormalizer.js */ 68);

var renderjs





/* normalize component */

var component = Object(_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _integral_goods_details_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _integral_goods_details_vue_vue_type_template_id_f54bc484___WEBPACK_IMPORTED_MODULE_0__["render"],
  _integral_goods_details_vue_vue_type_template_id_f54bc484___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null,
  false,
  _integral_goods_details_vue_vue_type_template_id_f54bc484___WEBPACK_IMPORTED_MODULE_0__["components"],
  renderjs
)

component.options.__file = "pages/points_mall/integral_goods_details.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ 1057:
/*!******************************************************************************************************************************************!*\
  !*** /Users/tinhp/Workspace/Projects/CRMEB/template/uni-app/pages/points_mall/integral_goods_details.vue?vue&type=template&id=f54bc484& ***!
  \******************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns, recyclableRender, components */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_17_0_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_template_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_uni_app_loader_page_meta_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_integral_goods_details_vue_vue_type_template_id_f54bc484___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/vue-cli-plugin-uni/packages/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--17-0!../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/webpack-uni-mp-loader/lib/template.js!../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-uni-app-loader/page-meta.js!../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/vue-cli-plugin-uni/packages/vue-loader/lib??vue-loader-options!../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/webpack-uni-mp-loader/lib/style.js!./integral_goods_details.vue?vue&type=template&id=f54bc484& */ 1058);
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_17_0_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_template_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_uni_app_loader_page_meta_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_integral_goods_details_vue_vue_type_template_id_f54bc484___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_17_0_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_template_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_uni_app_loader_page_meta_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_integral_goods_details_vue_vue_type_template_id_f54bc484___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "recyclableRender", function() { return _Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_17_0_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_template_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_uni_app_loader_page_meta_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_integral_goods_details_vue_vue_type_template_id_f54bc484___WEBPACK_IMPORTED_MODULE_0__["recyclableRender"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "components", function() { return _Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_17_0_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_template_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_uni_app_loader_page_meta_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_integral_goods_details_vue_vue_type_template_id_f54bc484___WEBPACK_IMPORTED_MODULE_0__["components"]; });



/***/ }),

/***/ 1058:
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/@dcloudio/vue-cli-plugin-uni/packages/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--17-0!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/template.js!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-uni-app-loader/page-meta.js!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/vue-loader/lib??vue-loader-options!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/style.js!/Users/tinhp/Workspace/Projects/CRMEB/template/uni-app/pages/points_mall/integral_goods_details.vue?vue&type=template&id=f54bc484& ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns, recyclableRender, components */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "recyclableRender", function() { return recyclableRender; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "components", function() { return components; });
var components
try {
  components = {
    jyfParser: function () {
      return Promise.all(/*! import() | components/jyf-parser/jyf-parser */[__webpack_require__.e("common/vendor"), __webpack_require__.e("components/jyf-parser/jyf-parser")]).then(__webpack_require__.bind(null, /*! @/components/jyf-parser/jyf-parser.vue */ 1343))
    },
  }
} catch (e) {
  if (
    e.message.indexOf("Cannot find module") !== -1 &&
    e.message.indexOf(".vue") !== -1
  ) {
    console.error(e.message)
    console.error("1. 排查组件名称拼写是否正确")
    console.error(
      "2. 排查组件是否符合 easycom 规范，文档：https://uniapp.dcloud.net.cn/collocation/pages?id=easycom"
    )
    console.error(
      "3. 若组件不符合 easycom 规范，需手动引入，并在 components 中注册该组件"
    )
  } else {
    throw e
  }
}
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  var m0 = _vm.$t("tích phân")
  var m1 = _vm.storeInfo.num > 0 ? _vm.$t("Có thể đổi tối đa") : null
  var m2 = _vm.storeInfo.num > 0 ? _vm.$t(_vm.storeInfo.unit_name) : null
  var m3 = _vm.$t("phiên bản giới hạn")
  var m4 = _vm.$t("Đã chuộc")
  var g0 = _vm.attribute.productAttr.length
  var g1 = g0 ? _vm.skuArr.length : null
  var l0 = g0 && g1 > 1 ? _vm.skuArr.slice(0, 4) : null
  var m5 = g0 && g1 > 1 ? _vm.$t("chung") : null
  var g2 = g0 && g1 > 1 ? _vm.skuArr.length : null
  var m6 = g0 && g1 > 1 ? _vm.$t("Thông số kỹ thuật có sẵn") : null
  var m7 = _vm.$t("Giới thiệu sản phẩm")
  var m8 = _vm.$t("trang đầu")
  var m9 =
    _vm.attribute.productSelect.quota > 0 &&
    _vm.attribute.productSelect.product_stock > 0
      ? _vm.$t("Đổi ngay bây giờ")
      : null
  var m10 = !(
    _vm.attribute.productSelect.quota > 0 &&
    _vm.attribute.productSelect.product_stock > 0
  )
    ? _vm.$t("Không thể đổi quà")
    : null
  _vm.$mp.data = Object.assign(
    {},
    {
      $root: {
        m0: m0,
        m1: m1,
        m2: m2,
        m3: m3,
        m4: m4,
        g0: g0,
        g1: g1,
        l0: l0,
        m5: m5,
        g2: g2,
        m6: m6,
        m7: m7,
        m8: m8,
        m9: m9,
        m10: m10,
      },
    }
  )
}
var recyclableRender = false
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ 1059:
/*!************************************************************************************************************************************!*\
  !*** /Users/tinhp/Workspace/Projects/CRMEB/template/uni-app/pages/points_mall/integral_goods_details.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_13_1_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_integral_goods_details_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/babel-loader/lib!../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--13-1!../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/webpack-uni-mp-loader/lib/script.js!../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/vue-cli-plugin-uni/packages/vue-loader/lib??vue-loader-options!../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/webpack-uni-mp-loader/lib/style.js!./integral_goods_details.vue?vue&type=script&lang=js& */ 1060);
/* harmony import */ var _Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_13_1_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_integral_goods_details_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_13_1_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_integral_goods_details_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_13_1_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_integral_goods_details_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_13_1_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_integral_goods_details_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_13_1_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_integral_goods_details_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ 1060:
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--13-1!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/script.js!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/vue-loader/lib??vue-loader-options!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/style.js!/Users/tinhp/Workspace/Projects/CRMEB/template/uni-app/pages/points_mall/integral_goods_details.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function(uni) {

var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ 4);
Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.default = void 0;
var _defineProperty2 = _interopRequireDefault(__webpack_require__(/*! @babel/runtime/helpers/defineProperty */ 11));
var _vuex = __webpack_require__(/*! vuex */ 42);
var _activity = __webpack_require__(/*! @/api/activity.js */ 134);
var _public = __webpack_require__(/*! @/api/public */ 38);
var _login = __webpack_require__(/*! @/libs/login.js */ 40);
var _user = __webpack_require__(/*! @/api/user.js */ 45);
var _color = _interopRequireDefault(__webpack_require__(/*! @/mixins/color */ 59));
var _computed$mixins$data;
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var app = getApp();
var productConSwiper = function productConSwiper() {
  __webpack_require__.e(/*! require.ensure | components/productConSwiper/index */ "components/productConSwiper/index").then((function () {
    return resolve(__webpack_require__(/*! @/components/productConSwiper/index.vue */ 1526));
  }).bind(null, __webpack_require__)).catch(__webpack_require__.oe);
};
var productWindow = function productWindow() {
  __webpack_require__.e(/*! require.ensure | pages/points_mall/component/productWindow */ "pages/points_mall/component/productWindow").then((function () {
    return resolve(__webpack_require__(/*! ./component/productWindow.vue */ 1664));
  }).bind(null, __webpack_require__)).catch(__webpack_require__.oe);
};
var userEvaluation = function userEvaluation() {
  __webpack_require__.e(/*! require.ensure | components/userEvaluation/index */ "components/userEvaluation/index").then((function () {
    return resolve(__webpack_require__(/*! @/components/userEvaluation/index.vue */ 1396));
  }).bind(null, __webpack_require__)).catch(__webpack_require__.oe);
};
var kefuIcon = function kefuIcon() {
  Promise.all(/*! require.ensure | components/kefuIcon/index */[__webpack_require__.e("common/vendor"), __webpack_require__.e("components/kefuIcon/index")]).then((function () {
    return resolve(__webpack_require__(/*! @/components/kefuIcon */ 1289));
  }).bind(null, __webpack_require__)).catch(__webpack_require__.oe);
};
var authorize = function authorize() {
  __webpack_require__.e(/*! require.ensure | components/Authorize */ "components/Authorize").then((function () {
    return resolve(__webpack_require__(/*! @/components/Authorize */ 1215));
  }).bind(null, __webpack_require__)).catch(__webpack_require__.oe);
};
var parser = function parser() {
  Promise.all(/*! require.ensure | components/jyf-parser/jyf-parser */[__webpack_require__.e("common/vendor"), __webpack_require__.e("components/jyf-parser/jyf-parser")]).then((function () {
    return resolve(__webpack_require__(/*! @/components/jyf-parser/jyf-parser */ 1343));
  }).bind(null, __webpack_require__)).catch(__webpack_require__.oe);
};
var countDown = function countDown() {
  __webpack_require__.e(/*! require.ensure | components/countDown/index */ "components/countDown/index").then((function () {
    return resolve(__webpack_require__(/*! @/components/countDown */ 1473));
  }).bind(null, __webpack_require__)).catch(__webpack_require__.oe);
};
var cusPreviewImg = function cusPreviewImg() {
  __webpack_require__.e(/*! require.ensure | components/cusPreviewImg/index */ "components/cusPreviewImg/index").then((function () {
    return resolve(__webpack_require__(/*! @/components/cusPreviewImg/index.vue */ 1261));
  }).bind(null, __webpack_require__)).catch(__webpack_require__.oe);
};
var homeList = function homeList() {
  __webpack_require__.e(/*! require.ensure | components/homeList/index */ "components/homeList/index").then((function () {
    return resolve(__webpack_require__(/*! @/components/homeList */ 1303));
  }).bind(null, __webpack_require__)).catch(__webpack_require__.oe);
};
var sysHeight = uni.getWindowInfo().statusBarHeight + "px";
var _default = (_computed$mixins$data = {
  computed: (0, _vuex.mapGetters)(["isLogin"]),
  mixins: [_color.default],
  data: function data() {
    return {
      dataShow: 0,
      id: 0,
      time: 0,
      countDownHour: "00",
      countDownMinute: "00",
      countDownSecond: "00",
      storeInfo: [],
      imgUrls: [],
      parameter: {
        navbar: "1",
        return: "1",
        title: this.$t("trang chi ti\u1EBFt mua g\u1EA5p"),
        color: false
      },
      attribute: {
        cartAttr: false,
        productAttr: [],
        productSelect: {}
      },
      productValue: [],
      isOpen: false,
      attr: this.$t("Vui l\xF2ng ch\u1ECDn"),
      attrValue: "",
      status: 1,
      isAuto: false,
      isShowAuth: false,
      iShidden: false,
      limitNum: 1,
      //Giới hạn số lượng sản phẩm có thuộc tính này；
      iSplus: false,
      replyCount: 0,
      //Tổng số bình luận
      reply: [],
      //Danh sách bình luận
      replyChance: 0,
      navH: "",
      navList: [this.$t("h\xE0ng h\xF3a"), this.$t("Chi ti\u1EBFt")],
      opacity: 0,
      scrollY: 0,
      topArr: [],
      toView: "",
      height: 0,
      heightArr: [],
      lock: false,
      scrollTop: 0,
      tagStyle: {
        img: "width:100%;display:block;",
        table: "width:100%",
        video: "width:100%"
      },
      datatime: "",
      navActive: 0,
      meunHeight: 0,
      backH: "",
      posters: false,
      weixinStatus: false,
      posterImageStatus: false,
      storeImage: "",
      //Áp phích hình ảnh sản phẩm
      PromotionCode: "",
      //hình ảnh mã QR
      posterImage: "",
      //con đường áp phích
      actionSheetHidden: false,
      cart_num: 1,
      homeTop: 20,
      returnShow: true,
      H5ShareBox: false,
      //Hình ảnh chia sẻ tài khoản công khai
      routineContact: 0,
      skuArr: [],
      selectSku: {},
      currentPage: false,
      sysHeight: sysHeight
    };
  },
  components: {
    productConSwiper: productConSwiper,
    productWindow: productWindow,
    userEvaluation: userEvaluation,
    kefuIcon: kefuIcon,
    "jyf-parser": parser,
    countDown: countDown,
    cusPreviewImg: cusPreviewImg,
    homeList: homeList,
    authorize: authorize
  }
}, (0, _defineProperty2.default)(_computed$mixins$data, "computed", (0, _vuex.mapGetters)(["isLogin"])), (0, _defineProperty2.default)(_computed$mixins$data, "watch", {
  isLogin: {
    handler: function handler(newV, oldV) {
      if (newV) {
        this.getIntegralProductDetail();
      }
    },
    deep: true
  }
}), (0, _defineProperty2.default)(_computed$mixins$data, "onLoad", function onLoad(options) {
  var _this = this;
  var that = this;
  var statusBarHeight = "";
  var pages = getCurrentPages();
  that.returnShow = pages.length === 1 ? false : true;
  //Đặt chiều cao danh sách sản phẩm
  uni.getSystemInfo({
    success: function success(res) {
      that.height = res.windowHeight;
      statusBarHeight = res.statusBarHeight;
      //res.windowHeight:Lấy chiều cao của toàn bộ cửa sổ là px, *2 là rpx; 98 là chiều cao chiếm giữ của đầu；
    }
  });

  this.navH = app.globalData.navHeight;
  var menuButtonInfo = uni.getMenuButtonBoundingClientRect();
  this.meunHeight = menuButtonInfo.height;
  this.backH = that.navH / 2 + this.meunHeight / 2;

  //Quét mã để thực hiện xử lý tham số
  if (options.scene) {
    var value = this.$util.getUrlParams(decodeURIComponent(options.scene));
    if (value.id) {
      this.id = value.id;
    } else {
      return this.$util.Tips({
        title: this.$t("Kh\xF4ng xem \u0111\u01B0\u1EE3c s\u1EA3n ph\u1EA9m do thi\u1EBFu th\xF4ng s\u1ED1")
      }, {
        tab: 3,
        url: 1
      });
    }
    //người quảng bá kỷ lụcuid
    if (value.pid) app.globalData.spid = value.pid;
    if (value.time) this.datatime = value.time;
  }
  if (options.id) {
    this.id = options.id;
    this.datatime = Number(options.time);
    this.status = options.status;
  }
  if (this.isLogin) {
    this.getIntegralProductDetail();
  } else {
    (0, _login.toLogin)();
  }
  this.$nextTick(function () {
    var menuButton = uni.getMenuButtonBoundingClientRect();
    var query = uni.createSelectorQuery().in(_this);
    query.select("#home").boundingClientRect(function (data) {
      _this.homeTop = menuButton.top * 2 + menuButton.height - data.height;
    }).exec();
  });
}), (0, _defineProperty2.default)(_computed$mixins$data, "onNavigationBarButtonTap", function onNavigationBarButtonTap(e) {
  this.currentPage = !this.currentPage;
}), (0, _defineProperty2.default)(_computed$mixins$data, "methods", {
  moreNav: function moreNav() {
    this.currentPage = !this.currentPage;
  },
  // appchia sẻ

  /**
   * Điền thủ công vào giỏ hàng
   *
   */
  iptCartNum: function iptCartNum(e) {
    this.$set(this.attribute.productSelect, "cart_num", e);
    this.$set(this, "cart_num", e);
  },
  // Mặt sau
  returns: function returns() {
    return uni.navigateBack({
      delta: 1
    });
  },
  onLoadFun: function onLoadFun(data) {
    if (this.isAuto) {
      this.isAuto = false;
      this.isShowAuth = false;
      this.getIntegralProductDetail();
    }
  },
  getIntegralProductDetail: function getIntegralProductDetail() {
    var _this2 = this;
    var that = this;
    (0, _activity.getIntegralProductDetail)(that.id).then(function (res) {
      _this2.dataShow = 1;
      var title = res.data.storeInfo.title;
      _this2.storeInfo = res.data.storeInfo;
      _this2.imgUrls = res.data.storeInfo.images;
      _this2.storeInfo.description = _this2.storeInfo.description.replace(/<img/gi, '<img style="max-width:100%;height:auto;float:left;display:block" ');
      _this2.attribute.productAttr = res.data.productAttr;
      _this2.productValue = res.data.productValue;
      _this2.attribute.productSelect.num = res.data.storeInfo.num;
      _this2.replyCount = res.data.replyCount;
      _this2.reply = res.data.reply ? [res.data.reply] : [];
      _this2.replyChance = res.data.replyChance;
      that.routineContact = Number(res.data.routine_contact_type);
      for (var key in res.data.productValue) {
        var obj = res.data.productValue[key];
        that.skuArr.push(obj);
      }
      _this2.$set(_this2, "selectSku", that.skuArr[0]);
      uni.setNavigationBarTitle({
        title: title.substring(0, 7) + "..."
      });
      that.DefaultSelect();
    }).catch(function (err) {
      that.$util.Tips({
        title: err
      }, {
        tab: 3
      });
    });
  },
  setShare: function setShare() {
    this.$wechat.isWeixin() && this.$wechat.wechatEvevt(["updateAppMessageShareData", "updateTimelineShareData", "onMenuShareAppMessage", "onMenuShareTimeline"], {
      desc: this.storeInfo.info,
      title: this.storeInfo.title,
      link: location.href,
      imgUrl: this.storeInfo.image
    }).then(function (res) {}).catch(function (err) {});
  },
  /**
   * Thuộc tính được chọn theo mặc định
   *
   */
  DefaultSelect: function DefaultSelect() {
    var self = this;
    var productAttr = self.attribute.productAttr;
    var value = [];
    for (var key in this.productValue) {
      if (this.productValue[key].quota > 0) {
        value = this.attribute.productAttr.length ? key.split(",") : [];
        break;
      }
    }
    for (var i = 0; i < productAttr.length; i++) {
      this.$set(productAttr[i], "index", value[i]);
    }
    //sort();Chức năng sắp xếp:Số-Ký tự Anh-Trung；
    var productSelect = this.productValue[value.join(",")];
    if (productSelect && productAttr.length) {
      self.$set(self.attribute.productSelect, "store_name", self.storeInfo.title);
      self.$set(self.attribute.productSelect, "image", productSelect.image);
      self.$set(self.attribute.productSelect, "price", productSelect.price);
      self.$set(self.attribute.productSelect, "stock", productSelect.stock);
      self.$set(self.attribute.productSelect, "unique", productSelect.unique);
      self.$set(self.attribute.productSelect, "quota", productSelect.quota);
      self.$set(self.attribute.productSelect, "quota_show", productSelect.quota_show);
      self.$set(self.attribute.productSelect, "product_stock", productSelect.product_stock);
      self.$set(self.attribute.productSelect, "cart_num", 1);
      self.$set(self, "attrValue", value.join(","));
      self.attrValue = value.join(",");
    } else if (!productSelect && productAttr.length) {
      self.$set(self.attribute.productSelect, "store_name", self.storeInfo.title);
      self.$set(self.attribute.productSelect, "image", self.storeInfo.image);
      self.$set(self.attribute.productSelect, "price", self.storeInfo.price);
      self.$set(self.attribute.productSelect, "quota", 0);
      self.$set(self.attribute.productSelect, "quota_show", 0);
      self.$set(self.attribute.productSelect, "product_stock", 0);
      self.$set(self.attribute.productSelect, "stock", 0);
      self.$set(self.attribute.productSelect, "unique", "");
      self.$set(self.attribute.productSelect, "cart_num", 0);
      self.$set(self, "attrValue", "");
      self.$set(self, "attrTxt", self.$t("Vui l\xF2ng ch\u1ECDn"));
    } else if (!productSelect && !productAttr.length) {
      self.$set(self.attribute.productSelect, "store_name", self.storeInfo.title);
      self.$set(self.attribute.productSelect, "image", self.storeInfo.image);
      self.$set(self.attribute.productSelect, "price", self.storeInfo.price);
      self.$set(self.attribute.productSelect, "stock", self.storeInfo.stock);
      self.$set(self.attribute.productSelect, "quota", self.storeInfo.quota);
      self.$set(self.attribute.productSelect, "product_stock", self.storeInfo.product_stock);
      self.$set(self.attribute.productSelect, "unique", self.storeInfo.unique || "");
      self.$set(self.attribute.productSelect, "cart_num", 1);
      self.$set(self.attribute.productSelect, "quota", productSelect.quota);
      self.$set(self.attribute.productSelect, "product_stock", productSelect.product_stock);
      self.$set(self, "attrValue", "");
      self.$set(self, "attrTxt", self.$t("Vui l\xF2ng ch\u1ECDn"));
    }
  },
  selecAttr: function selecAttr() {
    this.attribute.cartAttr = true;
  },
  onMyEvent: function onMyEvent() {
    this.$set(this.attribute, "cartAttr", false);
    this.$set(this, "isOpen", false);
  },
  /**
   * Số lượng giỏ hàng cộng số lượng trừ
   *
   */
  ChangeCartNum: function ChangeCartNum(changeValue) {
    //changeValue:Có nên thêm không|trừ đi
    //Lấy các thuộc tính đã thay đổi hiện tại
    var productSelect = this.productValue[this.attrValue];
    if (this.cart_num) {
      productSelect.cart_num = this.cart_num;
      this.attribute.productSelect.cart_num = this.cart_num;
    }
    //nếu không có thuộc tính,Chỉ định giá trị cho khoảng không quảng cáo mặc định của sản phẩm
    if (productSelect === undefined && !this.attribute.productAttr.length) productSelect = this.attribute.productSelect;
    //Không có giá trị thuộc tính, nghĩa là hàng tồn kho là 0; không có phép cộng hoặc phép trừ.；
    if (productSelect === undefined) return;
    var stock = productSelect.stock || 0;
    var quotaShow = productSelect.quota_show || 0;
    var quota = productSelect.quota || 0;
    var productStock = productSelect.product_stock || 0;
    var num = this.attribute.productSelect;
    var nums = this.storeInfo.num || 0;
    //Đặt dữ liệu mặc định
    if (productSelect.cart_num == undefined) productSelect.cart_num = 1;
    if (changeValue) {
      if (num.cart_num < this.attribute.productSelect.quota) {
        num.cart_num++;
        this.$set(this.attribute.productSelect, "cart_num", num.cart_num);
        this.$set(this, "cart_num", num.cart_num);
        this.$set(this.attribute.productSelect, "cart_num", num.cart_num);
      }
    } else {
      if (num.cart_num == 1) return;
      num.cart_num--;
      this.$set(this, "cart_num", num.cart_num);
      this.$set(this.attribute.productSelect, "cart_num", num.cart_num);
    }
  },
  attrVal: function attrVal(val) {
    this.attribute.productAttr[val.indexw].index = this.attribute.productAttr[val.indexw].attr_values[val.indexn];
  },
  /**
   * gán thay đổi thuộc tính
   *
   */
  ChangeAttr: function ChangeAttr(res) {
    this.$set(this, "cart_num", 1);
    var productSelect = this.productValue[res];
    this.$set(this, "selectSku", productSelect);
    if (productSelect) {
      this.$set(this.attribute.productSelect, "image", productSelect.image);
      this.$set(this.attribute.productSelect, "price", productSelect.price);
      this.$set(this.attribute.productSelect, "stock", productSelect.stock);
      this.$set(this.attribute.productSelect, "unique", productSelect.unique);
      this.$set(this.attribute.productSelect, "cart_num", 1);
      this.$set(this.attribute.productSelect, "quota", productSelect.quota);
      this.$set(this.attribute.productSelect, "quota_show", productSelect.quota_show);
      this.$set(this, "attrValue", res);
      this.attrTxt = this.$t("\u0110\xE3 ch\u1ECDn");
    } else {
      this.$set(this.attribute.productSelect, "image", this.storeInfo.image);
      this.$set(this.attribute.productSelect, "price", this.storeInfo.price);
      this.$set(this.attribute.productSelect, "stock", 0);
      this.$set(this.attribute.productSelect, "unique", "");
      this.$set(this.attribute.productSelect, "cart_num", 0);
      this.$set(this.attribute.productSelect, "quota", 0);
      this.$set(this.attribute.productSelect, "quota_show", 0);
      this.$set(this, "attrValue", "");
      this.attrTxt = this.$t("\u0110\xE3 ch\u1ECDn");
    }
  },
  scroll: function scroll(e) {
    var that = this,
      scrollY = e.detail.scrollTop;
    var opacity = scrollY / 200;
    opacity = opacity > 1 ? 1 : opacity;
    that.opacity = opacity;
    this.currentPage = false;
    that.scrollY = scrollY;
    if (that.lock) {
      that.lock = false;
      return;
    }
    for (var i = 0; i < that.topArr.length; i++) {
      if (scrollY < that.topArr[i] - app.globalData.navHeight / 2 + that.heightArr[i]) {
        that.navActive = i;
        break;
      }
    }
  },
  tap: function tap(item, index) {
    var id = item.id;
    var index = index;
    var that = this;
    // if (!this.data.good_list.length && id == "past2") {
    //   id = "past3"
    // }
    this.toView = id;
    this.navActive = index;
    this.lock = true;
    this.scrollTop = index > 0 ? that.topArr[index] - app.globalData.navHeight / 2 : that.topArr[index];
  },
  //Bấm vào hình ảnh sku để mở băng chuyền
  showImg: function showImg(index) {
    this.$refs.cusPreviewImg.open(this.selectSku.suk);
  },
  /**
   * Chia sẻ mở
   *
   */
  listenerActionSheet: function listenerActionSheet() {
    this.currentPage = false;
    if (this.isLogin === false) {
      (0, _login.toLogin)();
    } else {
      if (this.posterImage) {
        this.posters = true;
        return;
      }
      this.downloadFilePromotionCode();
      this.posters = true;
    }
  },
  //Băng chuyền trượt để chọn sản phẩm
  changeSwitch: function changeSwitch(e) {
    var productSelect = this.skuArr[e];
    this.$set(this, "selectSku", productSelect);
    var skuList = productSelect.suk.split(",");
    this.$set(this.attribute.productAttr[0], "index", skuList[0]);
    if (skuList.length == 2) {
      this.$set(this.attribute.productAttr[0], "index", skuList[0]);
      this.$set(this.attribute.productAttr[1], "index", skuList[1]);
    } else if (skuList.length == 3) {
      this.$set(this.attribute.productAttr[0], "index", skuList[0]);
      this.$set(this.attribute.productAttr[1], "index", skuList[1]);
      this.$set(this.attribute.productAttr[2], "index", skuList[2]);
    } else if (skuList.length == 4) {
      this.$set(this.attribute.productAttr[0], "index", skuList[0]);
      this.$set(this.attribute.productAttr[1], "index", skuList[1]);
      this.$set(this.attribute.productAttr[2], "index", skuList[2]);
      this.$set(this.attribute.productAttr[3], "index", skuList[3]);
    }
    if (productSelect) {
      this.$set(this.attribute.productSelect, "image", productSelect.image);
      this.$set(this.attribute.productSelect, "price", productSelect.price);
      this.$set(this.attribute.productSelect, "stock", productSelect.stock);
      this.$set(this.attribute.productSelect, "unique", productSelect.unique);
      this.$set(this.attribute.productSelect, "vipPrice", productSelect.vipPrice);
      this.$set(this, "attrTxt", this.$t("\u0110\xE3 ch\u1ECDn"));
      this.$set(this, "attrValue", productSelect.suk);
    }
  },
  /*
   *  đặt hàng
   */
  goCat: function goCat() {
    var that = this;
    this.currentPage = false;
    var productSelect = this.productValue[this.attrValue];
    //Mở thuộc tính
    if (this.isOpen) this.attribute.cartAttr = true;else this.attribute.cartAttr = !this.attribute.cartAttr;
    //Chỉ thêm vào giỏ hàng khi đóng cửa sổ bật lên thuộc tính
    if (this.attribute.cartAttr === true && this.isOpen == false) return this.isOpen = true;
    //Nếu có một thuộc tính,không có sự lựa chọn,Nhắc người dùng lựa chọn
    if (this.attribute.productAttr.length && productSelect === undefined && this.isOpen == true) return app.$util.Tips({
      title: this.$t("Vui l\xF2ng ch\u1ECDn m\u1ED9t thu\u1ED9c t\xEDnh")
    });
    if (this.cart_num <= 0) {
      return app.$util.Tips({
        title: this.$t("Vui l\xF2ng ch\u1ECDn s\u1ED1 l\u01B0\u1EE3ng")
      });
    }
    this.isOpen = false;
    uni.navigateTo({
      url: "/pages/points_mall/integral_order?unique=".concat(productSelect.unique, "&num=").concat(this.cart_num || 1)
    });
  }
}), _computed$mixins$data);
exports.default = _default;
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./node_modules/@dcloudio/uni-mp-weixin/dist/index.js */ 2)["default"]))

/***/ }),

/***/ 1061:
/*!*********************************************************************************************************************************************!*\
  !*** /Users/tinhp/Workspace/Projects/CRMEB/template/uni-app/pages/points_mall/integral_goods_details.vue?vue&type=style&index=0&lang=scss& ***!
  \*********************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_8_oneOf_1_0_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_css_loader_dist_cjs_js_ref_8_oneOf_1_1_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_loaders_stylePostLoader_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_2_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_3_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_sass_loader_dist_cjs_js_ref_8_oneOf_1_4_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_5_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_integral_goods_details_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/mini-css-extract-plugin/dist/loader.js??ref--8-oneOf-1-0!../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/css-loader/dist/cjs.js??ref--8-oneOf-1-1!../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/vue-cli-plugin-uni/packages/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--8-oneOf-1-2!../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/postcss-loader/src??ref--8-oneOf-1-3!../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/vue-cli-plugin-uni/packages/sass-loader/dist/cjs.js??ref--8-oneOf-1-4!../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--8-oneOf-1-5!../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/vue-cli-plugin-uni/packages/vue-loader/lib??vue-loader-options!../../../../../../../../../Applications/HBuilderX.app/Contents/HBuilderX/plugins/uniapp-cli/node_modules/@dcloudio/webpack-uni-mp-loader/lib/style.js!./integral_goods_details.vue?vue&type=style&index=0&lang=scss& */ 1062);
/* harmony import */ var _Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_8_oneOf_1_0_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_css_loader_dist_cjs_js_ref_8_oneOf_1_1_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_loaders_stylePostLoader_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_2_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_3_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_sass_loader_dist_cjs_js_ref_8_oneOf_1_4_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_5_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_integral_goods_details_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_8_oneOf_1_0_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_css_loader_dist_cjs_js_ref_8_oneOf_1_1_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_loaders_stylePostLoader_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_2_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_3_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_sass_loader_dist_cjs_js_ref_8_oneOf_1_4_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_5_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_integral_goods_details_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_8_oneOf_1_0_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_css_loader_dist_cjs_js_ref_8_oneOf_1_1_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_loaders_stylePostLoader_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_2_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_3_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_sass_loader_dist_cjs_js_ref_8_oneOf_1_4_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_5_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_integral_goods_details_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_8_oneOf_1_0_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_css_loader_dist_cjs_js_ref_8_oneOf_1_1_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_loaders_stylePostLoader_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_2_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_3_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_sass_loader_dist_cjs_js_ref_8_oneOf_1_4_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_5_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_integral_goods_details_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_8_oneOf_1_0_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_css_loader_dist_cjs_js_ref_8_oneOf_1_1_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_loaders_stylePostLoader_js_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_2_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_3_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_sass_loader_dist_cjs_js_ref_8_oneOf_1_4_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_5_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_vue_loader_lib_index_js_vue_loader_options_Applications_HBuilderX_app_Contents_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_style_js_integral_goods_details_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ 1062:
/*!*************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/mini-css-extract-plugin/dist/loader.js??ref--8-oneOf-1-0!./node_modules/css-loader/dist/cjs.js??ref--8-oneOf-1-1!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--8-oneOf-1-2!./node_modules/postcss-loader/src??ref--8-oneOf-1-3!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/sass-loader/dist/cjs.js??ref--8-oneOf-1-4!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--8-oneOf-1-5!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/vue-loader/lib??vue-loader-options!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/style.js!/Users/tinhp/Workspace/Projects/CRMEB/template/uni-app/pages/points_mall/integral_goods_details.vue?vue&type=style&index=0&lang=scss& ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin
    if(false) { var cssReload; }
  

/***/ })

},[[1055,"common/runtime","common/vendor"]]]);
//# sourceMappingURL=../../../.sourcemap/mp-weixin/pages/points_mall/integral_goods_details.js.map