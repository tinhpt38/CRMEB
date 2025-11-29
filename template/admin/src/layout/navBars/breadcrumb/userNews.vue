<template>
  <div class="layout-navbars-breadcrumb-user-news">
    <div class="head-box">
      <div class="head-box-title">{{ $t('message.common.systemNotification') }}</div>
      <div class="head-box-btn" v-if="newsList.length > 0" v-db-click @click="onAllReadClick">{{ $t('message.common.allRead') }}</div>
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
          <div class="mt15">{{ $t('message.common.noNotification') }}</div>
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
          title: that.$t('message.common.newOrder'),
          message: that.$t('message.common.newOrderMessage', { orderId: data.order_id }),
        });
        if (newOrderAudioLink) newOrderAudioLink.play();
        that.messageList.push({
          title: that.$t('message.common.newOrderReminder'),
          icon: 'md-bulb',
          iconColor: '#87d068',
          time: 0,
          read: 0,
        });
      });
      ws.$on('NEW_REFUND_ORDER', function (data) {
        that.$notify.info({
          title: that.$t('message.common.refundOrderReminder'),
          message: that.$t('message.common.refundOrderMessage', { orderId: data.order_id }),
        });
        if (newOrderAudioLink) newOrderAudioLink.play();
        that.messageList.push({
          title: that.$t('message.common.refundOrderReminder'),
          icon: 'md-information',
          iconColor: '#fe5c57',
          time: 0,
          read: 0,
        });
      });
      ws.$on('WITHDRAW', function (data) {
        // that.$Notice.warning({
        //   title: '提现提醒',
        //   duration: 8,
        //   desc: '有用户申请提现,编号为(' + data.id + '),请注意查看',
        // });
        that.$notify.info({
          title: that.$t('message.common.withdrawReminder'),
          message: that.$t('message.common.withdrawMessage', { id: data.id }),
        });
        that.messageList.push({
          title: that.$t('message.common.refundOrderReminder'),
          icon: 'md-people',
          iconColor: '#f06292',
          time: 0,
          read: 0,
        });
      });
      ws.$on('STORE_STOCK', function (data) {
        that.$notify.info({
          title: that.$t('message.common.stockWarning'),
          message: that.$t('message.common.stockWarningMessage', { id: data.id }),
        });
        that.messageList.push({
          title: that.$t('message.common.stockWarning'),
          icon: 'md-information',
          iconColor: '#fe5c57',
          time: 0,
          read: 0,
        });
      });
      ws.$on('PAY_SMS_SUCCESS', function (data) {
        that.$notify.info({
          title: that.$t('message.common.smsRechargeSuccess'),
          message: that.$t('message.common.smsRechargeMessage', { price: data.price, number: data.number }),
        });
        that.messageList.push({
          title: that.$t('message.common.smsRechargeSuccess'),
          icon: 'md-bulb',
          iconColor: '#87d068',
          time: 0,
          read: 0,
        });
      });
    });
  },
  filters: {
    // 1 待发货 2 库存报警  3评论回复  4提现申请
    msgType(type) {
      let typeName;
      switch (type) {
        case 1:
          typeName = this.$t('message.common.pendingShipmentOrderReminder');
          break;
        case 2:
          typeName = this.$t('message.common.stockAlert');
          break;
        case 3:
          typeName = this.$t('message.common.commentReply');
          break;
        case 4:
          typeName = this.$t('message.common.withdrawApplication');
          break;
        default:
          typeName = this.$t('message.common.other');
      }
      return typeName;
    },
  },
  methods: {
    // 全部已读点击
    onAllReadClick() {
      this.newsList = [];
      this.$emit('haveNews', !!this.newsList.length);
    },
    // 前往通知中心点击
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
      this.$router.push({
        path,
      });
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
