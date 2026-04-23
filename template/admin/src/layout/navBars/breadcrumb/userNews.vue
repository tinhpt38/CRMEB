<template>
  <div class="layout-navbars-breadcrumb-user-news">
    <div class="head-box">
      <div class="head-box-title">Thông báo hệ thống</div>
      <!-- <div class="head-box-btn" v-if="newsList.length > 0" v-db-click @click="onAllReadClick">Tất cả đã đọc</div> -->
    </div>
    <div class="content-box">
      <template v-if="newsList.length > 0">
        <div class="content-box-item" v-for="(v, k) in newsList" :key="k" v-db-click @click="jumpUrl(v.url)">
          <img class="icon" :src="icon(v.type)" alt="" />
          <div class="content-box-right">
            <div class="content-box-type">{{ v.type | msgType }}</div>
            <div class="content-box-msg">
              {{ v.title }}
            </div>
          </div>

          <!-- <div class="content-box-time">{{ v.time }}</div> -->
        </div>
      </template>
      <div class="content-box-empty" v-else>
        <div class="content-box-empty-margin">
          <img class="no-msg" src="@/assets/images/no-msg.png" alt="" />
          <div class="mt15">Chưa có thông báo</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
let newOrderAudioLink = new Audio(require('@/assets/video/newOrderAudioLink.mp3'));
import { jnoticeRequest } from '@/api/common';
import { adminSocket } from '@/libs/socket';
import { getCookies, removeCookies, setCookies } from '@/libs/util';
export default {
  name: 'layoutBreadcrumbUserNews',
  props: {},
  data() {
    return {
      newsList: [],
      newOrderAudioLink: null,
      messageList: [],
    };
  },
  mounted() {
    this.getNotict();
    this.newOrderAudioLink = newOrderAudioLink;
    adminSocket.then((ws) => {
      ws.send({
        type: 'login',
        data: getCookies('token'),
      });
      let that = this;
      ws.$on('ADMIN_NEW_PUSH', function (data) {
        that.getNotict();
      });

      ws.$on('NEW_ORDER', function (data) {
        that.$notify.info({
          title: 'trật tự mới',
          message: 'Bạn có một đơn đặt hàng mới,IDvì(' + data.order_id + '),Vui lòng kiểm tra',
        });
        if (newOrderAudioLink) newOrderAudioLink.play();
        that.messageList.push({
          title: 'Nhắc nhở đơn hàng mới',
          icon: 'md-bulb',
          iconColor: '#87d068',
          time: 0,
          read: 0,
        });
      });
      ws.$on('NEW_REFUND_ORDER', function (data) {
        that.$notify.info({
          title: 'Nhắc nhở hoàn tiền đơn hàng',
          message: 'Bạn có đơn đặt hàng yêu cầu hoàn lại tiền,IDvì(' + data.order_id + '),Vui lòng kiểm tra',
        });
        if (newOrderAudioLink) newOrderAudioLink.play();
        that.messageList.push({
          title: 'Nhắc nhở hoàn tiền đơn hàng',
          icon: 'md-information',
          iconColor: '#fe5c57',
          time: 0,
          read: 0,
        });
      });
      ws.$on('WITHDRAW', function (data) {
        // that.$Notice.warning({
        //   title: 'Nhắc nhở rút tiền',
        //   duration: 8,
        //   desc: 'Một người dùng đã đăng ký rút tiền,Đánh số(' + data.id + '),Vui lòng kiểm tra',
        // });
        that.$notify.info({
          title: 'Nhắc nhở rút tiền',
          message: 'Một người dùng đã đăng ký rút tiền,Đánh số(' + data.id + '),Vui lòng kiểm tra',
        });
        that.messageList.push({
          title: 'Nhắc nhở hoàn tiền đơn hàng',
          icon: 'md-people',
          iconColor: '#f06292',
          time: 0,
          read: 0,
        });
      });
      ws.$on('STORE_STOCK', function (data) {
        that.$notify.info({
          title: 'Cảnh báo chứng khoán',
          message: 'ID sản phẩm là(' + data.id + ')Hàng tồn kho thấp,Vui lòng kiểm tra~',
        });
        that.messageList.push({
          title: 'Cảnh báo chứng khoán',
          icon: 'md-information',
          iconColor: '#fe5c57',
          time: 0,
          read: 0,
        });
      });
      ws.$on('PAY_SMS_SUCCESS', function (data) {
        that.$notify.info({
          title: 'Nạp SMS thành công',
          message: 'Chúc mừng bạn đã nạp tiền' + data.price + 'Nhân dân tệ, lấy' + data.number + 'tin nhắn SMS',
        });
        that.messageList.push({
          title: 'Nạp SMS thành công',
          icon: 'md-bulb',
          iconColor: '#87d068',
          time: 0,
          read: 0,
        });
      });
    });
  },
  filters: {
    // 1 Đang chờ giao hàng 2 Báo động tồn kho 3 Phản hồi bình luận 4 Đơn xin rút tiền
    msgType(type) {
      let typeName;
      switch (type) {
        case 1:
          typeName = 'Nhắc nhở các đơn hàng chờ vận chuyển';
          break;
        case 2:
          typeName = 'Báo động tồn kho';
          break;
        case 3:
          typeName = 'Trả lời bình luận';
          break;
        case 4:
          typeName = 'Đơn xin rút tiền';
          break;
        default:
          typeName = 'khác';
      }
      return typeName;
    },
  },
  methods: {
    // Tất cả nhấp vào đọc
    onAllReadClick() {
      this.newsList = [];
      this.$emit('haveNews', !!this.newsList.length);
    },
    // Đi đến trung tâm thông báo và nhấp vào
    onGoToGiteeClick() {},
    getNotict() {
      jnoticeRequest()
        .then((res) => {
          this.newsList = res.data || [];
          this.$emit('haveNews', !!this.newsList.length);
        })
        .catch(() => {});
    },
    jumpUrl(path) {
      if (!path) return;
      // Liên kết ngoài mở trực tiếp trong cửa sổ mới
      if (/^https?:\/\//.test(path)) {
        window.open(path, '_blank');
        return;
      }
      // Tương thích trong các môi trường kết xuất đặc biệt như lớp đàn hồi this.$router Các trường hợp có thể không xác định
      const router = this.$router || (this.$root && this.$root.$router);
      if (router && typeof router.push === 'function') {
        router.push({ path });
      } else {
        // Tóm lại: Nhảy trực tiếp
        window.location.href = path;
      }
    },
    icon(type) {
      return require(`@/assets/images/news-${type}.png`);
    },
  },
};
</script>

<style scoped lang="scss">
.layout-navbars-breadcrumb-user-news {
  width: 320px;
  padding: 8px 14px 14px;
  .head-box {
    display: flex;
    // border-bottom: 1px solid var(--prev-border-color-lighter);
    box-sizing: border-box;
    color: var(--prev-color-text-primary);
    justify-content: space-between;
    // height: 35px;
    align-items: center;
    .head-box-title {
      font-size: 13px;
      font-weight: 500;
      color: #333333;
      line-height: 13px;
    }
    .head-box-btn {
      color: var(--prev-color-primary);
      font-size: 13px;
      cursor: pointer;
      opacity: 0.8;
      font-weight: 400;
      line-height: 13px;
      &:hover {
        opacity: 1;
      }
    }
  }
  .content-box {
    font-size: 13px;
    .content-box-item {
      padding-top: 24px;
      cursor: pointer;
      display: flex;
      align-items: center;
      &:last-of-type {
        // padding-bottom: 12px;
      }
      .icon {
        width: 26px;
        height: 26px;
        margin-right: 10px;
      }
      .content-box-right {
      }
      .content-box-type {
        font-size: 13px;
        font-weight: 500;
        color: #333333;
        line-height: 13px;
      }
      .content-box-msg {
        margin-top: 6px;
        font-size: 13px;
        font-weight: 400;
        color: #666666;
        line-height: 13px;
      }
      .content-box-time {
        color: var(--prev-color-text-secondary);
      }
    }
    .content-box-empty {
      width: 292px;
      // height: 200px;
      display: flex;
      align-items: center;
      justify-content: center;
      .content-box-empty-margin {
        text-align: center;
        font-size: 13px;
        color: #999999;
        i {
          color: var(--prev-color-primary);
          font-size: 60px;
        }
        .no-msg {
          width: 180px;
          height: 138px;
        }
      }
    }
  }
  .foot-box {
    height: 35px;
    color: var(--prev-color-primary);
    font-size: 13px;
    cursor: pointer;
    opacity: 0.8;
    display: flex;
    align-items: center;
    justify-content: center;
    border-top: 1px solid var(--prev-border-color-lighter);
    &:hover {
      opacity: 1;
    }
  }
  ::v-deep(.el-empty__description p) {
    font-size: 13px;
  }
}
</style>
