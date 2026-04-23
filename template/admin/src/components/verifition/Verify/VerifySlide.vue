<template>
  <div style="position: relative">
    <div v-if="type === '2'" class="verify-img-out" :style="{ height: parseInt(setSize.imgHeight) + vSpace + 'px' }">
      <div class="verify-img-panel" :style="{ width: setSize.imgWidth, height: setSize.imgHeight }">
        <img
          :src="backImgBase ? 'data:image/png;base64,' + backImgBase : defaultImg"
          alt=""
          style="width: 100%; height: 100%; display: block"
        />
        <div v-show="showRefresh" class="verify-refresh" v-db-click @click="refresh">
          <i class="iconfont icon-refresh" />
        </div>
        <transition name="tips">
          <span v-if="tipWords" class="verify-tips" :class="passFlag ? 'suc-bg' : 'err-bg'">{{ tipWords }}</span>
        </transition>
      </div>
    </div>
    <!-- phần công cộng -->
    <div
      class="verify-bar-area"
      :style="{ width: setSize.imgWidth, height: barSize.height, 'line-height': barSize.height }"
    >
      <span class="verify-msg" v-text="text" />
      <div
        class="verify-left-bar"
        :style="{
          width: leftBarWidth !== undefined ? leftBarWidth : barSize.height,
          height: barSize.height,
          'border-color': leftBarBorderColor,
          transaction: transitionWidth,
        }"
      >
        <span class="verify-msg" v-text="finishText" />
        <div
          class="verify-move-block"
          :style="{
            width: barSize.height,
            height: barSize.height,
            'background-color': moveBlockBackgroundColor,
            left: moveBlockLeft,
            transition: transitionLeft,
          }"
          @touchstart="start"
          @mousedown="start"
        >
          <i :class="['verify-icon iconfont', iconClass]" :style="{ color: iconColor }" />
          <div
            v-if="type === '2'"
            class="verify-sub-block"
            :style="{
              width: Math.floor((parseInt(setSize.imgWidth) * 47) / 310) + 'px',
              height: setSize.imgHeight,
              top: '-' + (parseInt(setSize.imgHeight) + vSpace) + 'px',
              'background-size': setSize.imgWidth + ' ' + setSize.imgHeight,
            }"
          >
            <img
              :src="'data:image/png;base64,' + blockBackImgBase"
              alt=""
              style="width: 100%; height: 100%; display: block"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/babel">
/**
 * VerifySlide
 * @description thanh trượt
 * */
import { aesEncrypt } from './../utils/ase';
import { resetSize } from './../utils/util';
import { ajCaptcha, ajCaptchaCheck } from '../../../api/common';

//  "captchaType":"blockPuzzle",
export default {
  name: 'VerifySlide',
  props: {
    captchaType: {
      type: String,
    },
    type: {
      type: String,
      default: '1',
    },
    // bật lên, đã sửafixed
    mode: {
      type: String,
      default: 'fixed',
    },
    vSpace: {
      type: Number,
      default: 5,
    },
    explain: {
      type: String,
      default: 'Vuốt sang phải để hoàn tất xác minh',
    },
    imgSize: {
      type: Object,
      default() {
        return {
          width: '310px',
          height: '155px',
        };
      },
    },
    blockSize: {
      type: Object,
      default() {
        return {
          width: '50px',
          height: '50px',
        };
      },
    },
    barSize: {
      type: Object,
      default() {
        return {
          width: '310px',
          height: '40px',
        };
      },
    },
    defaultImg: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      secretKey: '', // Trường khóa mã hóa được trả về bởi chương trình phụ trợ
      passFlag: '', // Xác định có đậu hay không
      backImgBase: '', // Hình nền mã xác minh
      blockBackImgBase: '', // Xác thực hình nền thanh trượt
      backToken: '', // Giá trị mã thông báo duy nhất được chương trình phụ trợ trả về
      startMoveTime: '', // Thời gian bắt đầu chuyển động
      endMovetime: '', // Thời điểm kết thúc việc di chuyển
      tipsBackColor: '', // Màu nền của từ nhắc nhở
      tipWords: '',
      text: '',
      finishText: '',
      setSize: {
        imgHeight: 0,
        imgWidth: 0,
        barHeight: 0,
        barWidth: 0,
      },
      top: 0,
      left: 0,
      moveBlockLeft: undefined,
      leftBarWidth: undefined,
      // Phong cách khi di chuyển
      moveBlockBackgroundColor: undefined,
      leftBarBorderColor: '#ddd',
      iconColor: undefined,
      iconClass: 'icon-right',
      status: false, // Trạng thái chuột
      isEnd: false, // Chỉ cần hoàn thành việc xác minh là đủ
      showRefresh: true,
      transitionLeft: '',
      transitionWidth: '',
    };
  },
  computed: {
    barArea() {
      return this.$el.querySelector('.verify-bar-area');
    },
    resetSize() {
      return resetSize;
    },
  },
  watch: {
    // typeNhững thay đổi là một sự làm mới toàn diện
    type: {
      immediate: true,
      handler() {
        this.init();
      },
    },
  },
  mounted() {
    // Không kéo
    this.$el.onselectstart = function () {
      return false;
    };
  },
  methods: {
    init() {
      this.text = this.explain;
      this.getPictrue();
      this.$nextTick(() => {
        const setSize = this.resetSize(this); // Đặt lại chiều rộng và chiều cao
        for (const key in setSize) {
          this.$set(this.setSize, key, setSize[key]);
        }
        this.$parent.$emit('ready', this);
      });

      var _this = this;

      window.removeEventListener('touchmove', function (e) {
        _this.move(e);
      });
      window.removeEventListener('mousemove', function (e) {
        _this.move(e);
      });

      // Thả chuột
      window.removeEventListener('touchend', function () {
        _this.end();
      });
      window.removeEventListener('mouseup', function () {
        _this.end();
      });

      window.addEventListener('touchmove', function (e) {
        _this.move(e);
      });
      window.addEventListener('mousemove', function (e) {
        _this.move(e);
      });

      // Thả chuột
      window.addEventListener('touchend', function () {
        _this.end();
      });
      window.addEventListener('mouseup', function () {
        _this.end();
      });
    },

    // ép chuột
    start: function (e) {
      e = e || window.event;
      if (!e.touches) {
        // Tương thích với máy tính
        var x = e.clientX;
      } else {
        // Tương thích với các thiết bị di động
        var x = e.touches[0].pageX;
      }
      this.startLeft = Math.floor(x - this.barArea.getBoundingClientRect().left);
      this.startMoveTime = +new Date(); // Thời gian bắt đầu trượt
      if (this.isEnd == false) {
        this.text = '';
        this.moveBlockBackgroundColor = '#337ab7';
        this.leftBarBorderColor = '#337AB7';
        this.iconColor = '#fff';
        e.stopPropagation();
        this.status = true;
      }
    },
    // chuyển động của chuột
    move: function (e) {
      e = e || window.event;
      if (this.status && this.isEnd == false) {
        if (!e.touches) {
          // Tương thích với máy tính
          var x = e.clientX;
        } else {
          // Tương thích với các thiết bị di động
          var x = e.touches[0].pageX;
        }
        var bar_area_left = this.barArea.getBoundingClientRect().left;
        var move_block_left = x - bar_area_left; // Giá trị bên trái của hộp nhỏ so với phần tử cha
        if (move_block_left >= this.barArea.offsetWidth - parseInt(parseInt(this.blockSize.width) / 2) - 2) {
          move_block_left = this.barArea.offsetWidth - parseInt(parseInt(this.blockSize.width) / 2) - 2;
        }
        if (move_block_left <= 0) {
          move_block_left = parseInt(parseInt(this.blockSize.width) / 2);
        }
        // Giá trị bên trái của ô vuông nhỏ sau khi kéo
        this.moveBlockLeft = move_block_left - this.startLeft + 'px';
        this.leftBarWidth = move_block_left - this.startLeft + 'px';
      }
    },

    // Thả chuột
    end: function () {
      this.endMovetime = +new Date();
      var _this = this;
      // Xác định xem chúng có trùng nhau không
      if (this.status && this.isEnd == false) {
        var moveLeftDistance = parseInt((this.moveBlockLeft || '').replace('px', ''));
        moveLeftDistance = (moveLeftDistance * 310) / parseInt(this.setSize.imgWidth);
        const data = {
          captchaType: this.captchaType,
          pointJson: this.secretKey
            ? aesEncrypt(JSON.stringify({ x: moveLeftDistance, y: 5.0 }), this.secretKey)
            : JSON.stringify({ x: moveLeftDistance, y: 5.0 }),
          token: this.backToken,
        };
        ajCaptchaCheck(data)
          .then((res) => {
            this.moveBlockBackgroundColor = '#5cb85c';
            this.leftBarBorderColor = '#5cb85c';
            this.iconColor = '#fff';
            this.iconClass = 'icon-check';
            this.showRefresh = false;
            this.isEnd = true;
            if (this.mode == 'pop') {
              setTimeout(() => {
                this.$parent.clickShow = false;
                this.refresh();
              }, 1500);
            }
            this.passFlag = true;
            this.tipWords = `${((this.endMovetime - this.startMoveTime) / 1000).toFixed(2)}sXác minh thành công`;
            var captchaVerification = this.secretKey
              ? aesEncrypt(this.backToken + '---' + JSON.stringify({ x: moveLeftDistance, y: 5.0 }), this.secretKey)
              : this.backToken + '---' + JSON.stringify({ x: moveLeftDistance, y: 5.0 });
            setTimeout(() => {
              this.tipWords = '';
              this.$parent.closeBox();
              this.$parent.$emit('success', { captchaVerification });
            }, 1000);
          })
          .catch((res) => {
            this.moveBlockBackgroundColor = '#d9534f';
            this.leftBarBorderColor = '#d9534f';
            this.iconColor = '#fff';
            this.iconClass = 'icon-close';
            this.passFlag = false;
            setTimeout(function () {
              _this.refresh();
            }, 1000);
            this.$parent.$emit('error', this);
            this.tipWords = 'Xác thực không thành công';
            setTimeout(() => {
              this.tipWords = '';
            }, 1000);
          });
        this.status = false;
      }
    },

    refresh: function () {
      this.showRefresh = true;
      this.finishText = '';

      this.transitionLeft = 'left .3s';
      this.moveBlockLeft = 0;

      this.leftBarWidth = undefined;
      this.transitionWidth = 'width .3s';

      this.leftBarBorderColor = '#ddd';
      this.moveBlockBackgroundColor = '#fff';
      this.iconColor = '#000';
      this.iconClass = 'icon-right';
      this.isEnd = false;

      this.getPictrue();
      setTimeout(() => {
        this.transitionWidth = '';
        this.transitionLeft = '';
        this.text = this.explain;
      }, 300);
    },

    // Yêu cầu hình nền và hình ảnh xác minh
    getPictrue() {
      const data = {
        captchaType: this.captchaType,
        clientUid: localStorage.getItem('slider'),
        ts: Date.now(), // dấu thời gian hiện tại
      };
      ajCaptcha(data)
        .then((res) => {
          this.backImgBase = res.data.originalImageBase64;
          this.blockBackImgBase = res.data.jigsawImageBase64;
          this.backToken = res.data.token;
          this.secretKey = res.data.secretKey;
        })
        .catch((res) => {
          this.tipWords = res.msg;
          this.backImgBase = null;
          this.blockBackImgBase = null;
        });
    },
  },
};
</script>
