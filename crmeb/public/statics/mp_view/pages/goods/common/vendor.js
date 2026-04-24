(global["webpackJsonp"] = global["webpackJsonp"] || []).push([["pages/goods/common/vendor"],{

/***/ 1379:
/*!*****************************************************************************************************************!*\
  !*** /Users/tinhp/Workspace/Projects/CRMEB/template/uni-app/pages/goods/components/lottery/js/grids_lottery.js ***!
  \*****************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function LotteryDraw(obj, callback) {
  this.timer = null; //hẹn giờ
  this.startIndex = obj.startIndex - 1 || 0; //Xổ số sẽ bắt đầu từ vị trí nào? [Mặc định là 0]
  this.count = 0; //đếm, chạy vòng
  this.winingIndex = obj.winingIndex || 0; //Vị trí đạt giải thưởng
  this.totalCount = obj.totalCount || 6; //Số vòng chạy xổ số
  this.speed = obj.speed || 100;
  this.domData = obj.domData;
  this.rollFn();
  this.callback = callback;
}
LotteryDraw.prototype = {
  rollFn: function rollFn() {
    var that = this;
    // Giá trị chỉ số hoạt động tăng lên, nghĩa là chuyển sang lưới tiếp theo
    this.startIndex++;

    //startIndexGiờ cuối cùng rồi. Hoàn thành vòng tròn và bắt đầu lại.
    if (this.startIndex >= this.domData.length - 1) {
      this.startIndex = 0;
      this.count++;
    }

    // Dừng khi số vòng chạy bằng số vòng đã đặt và giá trị chỉ số của hoạt động là vị trí của giải thưởng
    if (this.count >= this.totalCount && this.startIndex === this.winingIndex) {
      if (typeof this.callback === 'function') {
        setTimeout(function () {
          that.callback(that.startIndex, that.count); //Thực hiện chức năng gọi lại và hoàn thành các thao tác liên quan của xổ số
        }, 400);
      }
      clearInterval(this.timer);
    } else {
      //Bắt đầu lại một vòng tròn
      if (this.count >= this.totalCount - 1) {
        this.speed += 30;
      }
      this.timer = setTimeout(function () {
        that.callback(that.startIndex, that.count);
        that.rollFn();
      }, this.speed);
    }
  }
};
module.exports = LotteryDraw;

/***/ }),

/***/ 214:
/*!*****************************************************************************!*\
  !*** /Users/tinhp/Workspace/Projects/CRMEB/template/uni-app/api/lottery.js ***!
  \*****************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ 4);
Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.getLotteryData = getLotteryData;
exports.getLotteryList = getLotteryList;
exports.receiveLottery = receiveLottery;
exports.startLottery = startLottery;
var _request = _interopRequireDefault(__webpack_require__(/*! @/utils/request.js */ 39));
// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2024 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

/**
 * Nhận chi tiết xổ số
 * 
 */
function getLotteryData(type, lottery_id) {
  return _request.default.get("v2/lottery/info/".concat(type).concat(lottery_id ? '/' + lottery_id : ''));
}

/**
 * Tham gia xổ số
 * 
 */
function startLottery(data) {
  return _request.default.post("v2/lottery", data);
}

/**
 * Nhận giải thưởng
 * 
 */
function receiveLottery(data) {
  return _request.default.post("v2/lottery/receive", data);
}

/**
 * Nhận kỷ lục chiến thắng
 * 
 */
function getLotteryList(data) {
  return _request.default.get("v2/lottery/record", data);
}

/***/ }),

/***/ 279:
/*!*********************************************************************************!*\
  !*** /Users/tinhp/Workspace/Projects/CRMEB/template/uni-app/mixins/debounce.js ***!
  \*********************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.default = void 0;
// +----------------------------------------------------------------------
// | CRMEB [ CRMEBTrao quyền cho các nhà phát triển và giúp doanh nghiệp phát triển ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2024 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEBĐây không phải là phần mềm miễn phí và không thể xóa bản quyền liên quan đến CRMEB nếu không được phép.
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------
var _default = {
  data: function data() {
    return {};
  },
  created: function created() {},
  methods: {
    Debounce: function Debounce(fn, t) {
      var delay = t || 500;
      var timer;
      return function () {
        var _this = this;
        var args = arguments;
        if (timer) {
          clearTimeout(timer);
        }
        timer = setTimeout(function () {
          timer = null;
          fn.apply(_this, args);
        }, delay);
      };
    }
  }
};
exports.default = _default;

/***/ })

}]);
//# sourceMappingURL=../../../../.sourcemap/mp-weixin/pages/goods/common/vendor.js.map