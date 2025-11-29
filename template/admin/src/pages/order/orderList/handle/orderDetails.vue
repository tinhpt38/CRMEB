<template>
  <div>
    <el-drawer :title="$t('message.orderList.orderDetails')" :size="1000" :visible.sync="modals" wrapperClosable :before-close="handleClose">
      <div v-if="orderDatalist">
        <div class="head">
          <div class="full">
            <img class="order_icon" :src="orderImg" alt="" />
            <div class="text">
              <div class="title">{{ $t('message.orderList.normalOrder') }}</div>
              <div>
                <span class="mr20">{{ $t('message.orderList.orderNumber') }}{{ orderDatalist.orderInfo.order_id }}</span>
              </div>
            </div>
          </div>
          <ul class="list">
            <li class="item">
              <div class="title">{{ $t('message.orderList.orderStatus') }}</div>
              <div>
                {{ orderDatalist.orderInfo._status._title }}
                {{
                  orderDatalist.orderInfo.refund &&
                  orderDatalist.orderInfo.refund.length &&
                  orderDatalist.orderInfo.refund_status < 2
                    ? orderDatalist.orderInfo.is_all_refund
                      ? $t('message.orderList.refunding')
                      : $t('message.orderList.partialRefunding')
                    : ''
                }}
              </div>
            </li>
            <li class="item">
              <div class="title">{{ $t('message.orderList.actualPay') }}</div>
              <div>¥ {{ orderDatalist.orderInfo.pay_price || '0.0' }}</div>
            </li>
            <li class="item" v-if="orderDatalist.orderInfo.refund_status == 2">
              <div class="title">{{ $t('message.orderList.actualRefund') }}</div>
              <div>¥ {{ orderDatalist.orderInfo.refunded_price || '0.0' }}</div>
            </li>
            <li class="item">
              <div class="title">{{ $t('message.orderList.payMethod') }}</div>
              <div>{{ orderDatalist.orderInfo.pay_type | payType }}</div>
            </li>
            <li class="item">
              <div class="title">{{ $t('message.orderList.payTime') }}</div>
              <div>{{ orderDatalist.orderInfo._pay_time }}</div>
            </li>
          </ul>
        </div>
        <el-tabs type="border-card" v-model="activeName" @tab-click="tabClick">
          <el-tab-pane :label="$t('message.orderList.orderInfo')" name="detail">
            <div class="section">
              <div class="title">{{ $t('message.orderList.userInfo') }}</div>
              <ul class="list">
                <li class="item">
                  <div>{{ $t('message.orderList.userName') }}</div>
                  <div class="value">{{ orderDatalist.userInfo.real_name }}</div>
                </li>
                <li class="item">
                  <div>{{ $t('message.orderList.bindPhone') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.user_phone || '' }}</div>
                </li>
              </ul>
            </div>
            <div class="section">
              <div class="title">{{ $t('message.orderList.receiveInfo') }}</div>
              <ul class="list">
                <!-- <li class="item">
                  <div>收货信息：</div>
                  <div class="value">{{ orderDatalist.orderInfo.user_address || '' }}</div>
                </li> -->
                <li class="item">
                  <div>{{ $t('message.orderList.receiver') }}</div>
                  <div class="value">
                    {{ orderDatalist.orderInfo.real_name ? orderDatalist.orderInfo.real_name : '-' }}
                  </div>
                </li>
                <li class="item">
                  <div>{{ $t('message.orderList.receivePhone') }}</div>
                  <div class="value">
                    {{ orderDatalist.orderInfo.user_phone ? orderDatalist.orderInfo.user_phone : '-' }}
                  </div>
                </li>
                <li class="item">
                  <div>{{ $t('message.orderList.receiveAddress') }}</div>
                  <div class="value">
                    {{ orderDatalist.orderInfo.user_address ? orderDatalist.orderInfo.user_address : '-' }}
                  </div>
                </li>
              </ul>
            </div>
            <div class="section">
              <div class="title">{{ $t('message.orderList.orderInfo') }}</div>
              <ul class="list">
                <li class="item">
                  <div>{{ $t('message.orderList.createTime') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo._add_time }}</div>
                </li>
                <li class="item">
                  <div>{{ $t('message.orderList.totalProducts') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.total_num }}</div>
                </li>
                <li class="item">
                  <div>{{ $t('message.orderList.totalPrice') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.total_price }}</div>
                </li>
                <li class="item">
                  <div>{{ $t('message.orderList.couponAmount') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.coupon_price }}</div>
                </li>
                <li class="item">
                  <div>{{ $t('message.orderList.pointsDeduction') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.deduction_price || '0.0' }}</div>
                </li>
                <li class="item">
                  <div>{{ $t('message.orderList.deliveryFee') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.pay_postage }}</div>
                </li>
                <li class="item">
                  <div>{{ $t('message.orderList.userLevelDiscount') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.levelPrice || '0.0' }}</div>
                </li>
                <li class="item">
                  <div>{{ $t('message.orderList.vipDiscount') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.memberPrice || '0.0' }}</div>
                </li>
                <li class="item">
                  <div>{{ $t('message.orderList.actualPayAmount') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.pay_price || '0.0' }}</div>
                </li>
              </ul>
            </div>
            <div class="section" v-if="orderDatalist.orderInfo.delivery_name">
              <div class="title">
                {{ orderDatalist.orderInfo.delivery_type == 'express' ? $t('message.orderList.logisticsInfo') : $t('message.orderList.deliveryPersonInfo') }}
              </div>
              <ul class="list">
                <li class="item">
                  <div>{{ orderDatalist.orderInfo.delivery_type == 'express' ? $t('message.orderList.logisticsCompany') : $t('message.orderList.deliveryPersonName') }}</div>
                  <div class="value">
                    {{ orderDatalist.orderInfo.delivery_name ? orderDatalist.orderInfo.delivery_name : '-' }}
                  </div>
                </li>
                <li class="item">
                  <div>{{ orderDatalist.orderInfo.delivery_type == 'express' ? $t('message.orderList.logisticsNumber') : $t('message.orderList.deliveryPersonPhone') }}</div>
                  <div class="value">
                    {{ orderDatalist.orderInfo.delivery_id }}
                    <a v-if="orderDatalist.orderInfo.delivery_type == 'express'" v-db-click @click="openLogistics"
                      >{{ $t('message.orderList.logisticsQuery') }}</a
                    >
                  </div>
                </li>
              </ul>
            </div>
            <div class="section" v-if="orderDatalist.orderInfo.invoice">
              <div class="title">{{ $t('message.orderList.invoiceInfo') }}</div>
              <ul class="list">
                <li class="item">
                  <div>{{ $t('message.orderList.invoiceTitle') }}</div>
                  <div class="value">
                    {{ orderDatalist.orderInfo.invoice.name }}
                  </div>
                </li>
                <li
                  class="item"
                  v-if="orderDatalist.orderInfo.invoice.header_type === 2 && orderDatalist.orderInfo.invoice.type === 1"
                >
                  <div>{{ $t('message.orderList.taxNumber') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.invoice.duty_number }}</div>
                </li>
                <li
                  class="item"
                  v-if="orderDatalist.orderInfo.invoice.header_type === 2 && orderDatalist.orderInfo.invoice.type === 1"
                >
                  <div>{{ $t('message.orderList.invoiceType') }}</div>
                  <div class="value">{{ $t('message.orderList.electronicInvoice') }}</div>
                </li>
                <li
                  class="item"
                  v-if="orderDatalist.orderInfo.invoice.header_type === 2 && orderDatalist.orderInfo.invoice.type === 1"
                >
                  <div>{{ $t('message.orderList.invoiceTitleType') }}</div>
                  <div class="value">{{ $t('message.orderList.enterprise') }}</div>
                </li>
                <li
                  class="item"
                  v-if="orderDatalist.orderInfo.invoice.header_type === 1 && orderDatalist.orderInfo.invoice.type === 1"
                >
                  <div>{{ $t('message.orderList.realName') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.invoice.name || '' }}</div>
                </li>
                <li
                  class="item"
                  v-if="orderDatalist.orderInfo.invoice.header_type === 1 && orderDatalist.orderInfo.invoice.type === 1"
                >
                  <div>{{ $t('message.orderList.contactPhone') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.invoice.drawer_phone || '' }}</div>
                </li>
                <li
                  class="item"
                  v-if="orderDatalist.orderInfo.invoice.header_type === 2 && orderDatalist.orderInfo.invoice.type === 1"
                >
                  <div>{{ $t('message.orderList.contactPhone') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.invoice.user_phone || '' }}</div>
                </li>
                <li
                  class="item"
                  v-if="orderDatalist.orderInfo.invoice.header_type === 2 && orderDatalist.orderInfo.invoice.type === 1"
                >
                  <div>{{ $t('message.orderList.contactEmail') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.invoice.email || '' }}</div>
                </li>
                <li
                  class="item"
                  v-if="orderDatalist.orderInfo.invoice.header_type === 2 && orderDatalist.orderInfo.invoice.type === 1"
                >
                  <div>{{ $t('message.orderList.invoiceStatus') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.invoice.is_invoice ? $t('message.orderList.invoiced') : $t('message.orderList.notInvoiced') }}</div>
                </li>
              </ul>
            </div>
            <div class="section">
              <div class="title">{{ $t('message.orderList.buyerMessage') }}</div>
              <ul class="list">
                <li class="item">
                  <div>{{ orderDatalist.orderInfo.mark ? orderDatalist.orderInfo.mark : '-' }}</div>
                </li>
              </ul>
            </div>
            <div class="section" v-if="orderDatalist.orderInfo.custom_form.length">
              <div class="title">{{ $t('message.orderList.formInfo') }}</div>
              <ul class="list">
                <li
                  class="item"
                  :class="{ pic: item.label == 'img' }"
                  :span="item.label !== 'text' ? 12 : 24"
                  v-for="(item, index) in orderDatalist.orderInfo.custom_form"
                  :key="index"
                >
                  <template v-if="item.label !== 'img'">
                    <div>{{ item.title }}：{{ item.value }}</div>
                  </template>
                  <template v-else>
                    <div>{{ item.title }}：</div>
                    <div v-for="(img, i) in item.value" :key="i" class="img">
                      <img v-viewer :src="img" alt="" />
                    </div>
                  </template>
                </li>
              </ul>
            </div>
            <div class="section">
              <div class="title">{{ $t('message.orderList.orderRemark') }}</div>
              <ul class="list">
                <li class="item">
                  <div>{{ orderDatalist.orderInfo.remark ? orderDatalist.orderInfo.remark : '-' }}</div>
                </li>
              </ul>
            </div>
          </el-tab-pane>
          <el-tab-pane :label="$t('message.orderList.productInfo')" name="goods">
            <el-table class="mt20" :data="orderDatalist.orderInfo.cartInfo">
              <el-table-column :label="$t('message.orderList.productInfo')" min-width="300">
                <template slot-scope="scope">
                  <div class="tab">
                    <div class="demo-image__preview">
                      <el-image
                        :src="
                          scope.row.productInfo.attrInfo
                            ? scope.row.productInfo.attrInfo.image
                            : scope.row.productInfo.image
                        "
                        :preview-src-list="[scope.row.productInfo.attrInfo.image]"
                      />
                    </div>
                    <div>
                      <div class="line">{{ scope.row.productInfo.store_name }}</div>
                      <div class="line1 gary">
                        {{ $t('message.orderList.spec') }}：{{ scope.row.productInfo.attrInfo ? scope.row.productInfo.attrInfo.suk : $t('message.orderList.default') }}
                      </div>
                    </div>
                  </div>
                </template>
              </el-table-column>
              <el-table-column :label="$t('message.orderList.payPrice')" min-width="90">
                <template slot-scope="scope">
                  <div class="tab">
                    <div class="line1">
                      {{ scope.row.truePrice }}
                    </div>
                  </div>
                </template>
              </el-table-column>
              <el-table-column :label="$t('message.orderList.buyQuantity')" min-width="90">
                <template slot-scope="scope">
                  <div class="tab">
                    <div class="line1">
                      {{ scope.row.cart_num }}
                    </div>
                  </div>
                </template>
              </el-table-column>
              <!-- <el-table-column label="库存" min-width="70">
                <template slot-scope="scope">
                  <div class="tab">
                    <div class="line1">
                      {{ scope.row.productInfo.stock }}
                    </div>
                  </div>
                </template>
              </el-table-column> -->
            </el-table>
          </el-tab-pane>
          <el-tab-pane :label="$t('message.orderList.orderRecord')" name="orderList">
            <el-table class="mt20" :data="recordData" v-loading="loading" :empty-text="$t('message.orderList.noData')" highlight-current-row>
              <el-table-column :label="$t('message.orderList.orderId')" min-width="100">
                <template slot-scope="scope">
                  <span>{{ scope.row.oid }}</span>
                </template>
              </el-table-column>
              <el-table-column :label="$t('message.orderList.operationRecord')" min-width="100">
                <template slot-scope="scope">
                  <span>{{ scope.row.change_message }}</span>
                </template>
              </el-table-column>
              <el-table-column :label="$t('message.orderList.operationTime')" min-width="100">
                <template slot-scope="scope">
                  <span>{{ scope.row.change_time }}</span>
                </template>
              </el-table-column>
            </el-table>
          </el-tab-pane>
        </el-tabs>
      </div>
    </el-drawer>
    <el-drawer :visible.sync="modal2" scrollable :title="$t('message.orderList.logisticsQuery')" width="350px" class="order_box2">
      <div class="logistics acea-row row-top" v-if="orderDatalist">
        <div class="logistics_img">
          <img src="../../../../assets/images/expressi.jpg" />
        </div>
        <div class="logistics_cent">
          <span>{{ $t('message.orderList.logisticsCompany') }}：{{ orderDatalist.orderInfo.delivery_name }}</span>
          <span>{{ $t('message.orderList.logisticsNumber') }}：{{ orderDatalist.orderInfo.delivery_id }}</span>
        </div>
      </div>
      <div class="acea-row row-column-around trees-coadd">
        <div class="scollhide">
          <el-timeline>
            <el-timeline-item v-for="(item, i) in result" :key="i">
              <p class="time" v-text="item.time"></p>
              <p class="content" v-text="item.status"></p>
            </el-timeline-item>
          </el-timeline>
        </div>
      </div>
    </el-drawer>
  </div>
</template>
<script>
import { getExpress } from '@/api/order';
import { getOrderRecord } from '@/api/order';
export default {
  name: 'orderDetails',
  data() {
    return {
      activeName: 'detail',
      modal2: false,
      modals: false,
      grid: {
        xl: 8,
        lg: 8,
        md: 12,
        sm: 24,
        xs: 24,
      },
      result: [],
      orderImg: require('@/assets/images/order_icon.png'),
      recordData: [],
      page: {
        page: 1, // 当前页
        limit: 15, // 每页显示条数
      },
      loading: false,
    };
  },
  props: {
    orderDatalist: {
      type: Object,
      default: () => {
        orderInfo: {
        }
      },
    },
    orderId: Number,
    is_refund: {
      type: Number,
      default: 0,
    },
  },
  watch: {
    modals(val) {
      if (val) {
        this.activeName = 'detail';
      }
    },
  },
  filters: {
    payType(val) {
      let obj = {
        yue: this.$t('message.orderList.balance'),
        weixin: this.$t('message.orderList.wechatPay'),
        alipay: this.$t('message.orderList.alipay'),
        offline: this.$t('message.orderList.offlinePay'),
      };
      return obj[val];
    },
  },
  methods: {
    openLogistics() {
      this.getOrderData();
    },
    // 获取订单物流信息
    getOrderData() {
      getExpress(this.orderId)
        .then(async (res) => {
          this.result = res.data.result;
          this.modal2 = true;
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    tabClick(tab) {
      if (tab.name == 'orderList') {
        this.getRecordList();
      }
    },
    handleClose() {
      this.modals = false;
    },
    getRecordList() {
      let data = {
        id: this.is_refund ? this.orderDatalist.orderInfo.store_order_id : this.orderDatalist.orderInfo.id,
        datas: this.page,
      };
      this.loading = true;
      getOrderRecord(data)
        .then(async (res) => {
          this.recordData = res.data;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
  },
};
</script>
<style lang="scss" scoped>
::v-deep .el-tabs--border-card > .el-tabs__header .el-tabs__item.is-active {
  border-bottom: none;
}
::v-deep .el-tabs__item {
  height: 40px !important;
  line-height: 40px !important;
}
.head {
  padding: 0 30px 24px;

  .full {
    display: flex;
    align-items: center;
    .order_icon {
      width: 60px;
      height: 60px;
    }
    .iconfont {
      color: var(--prev-color-primary);
      &.sale-after {
        color: #90add5;
      }
    }
    .text {
      align-self: center;
      flex: 1;
      min-width: 0;
      padding-left: 12px;
      font-size: 13px;
      color: #606266;
      .title {
        margin-bottom: 10px;
        font-weight: 500;
        font-size: 16px;
        line-height: 16px;
        color: rgba(0, 0, 0, 0.85);
      }
      .order-num {
        padding-top: 10px;
        white-space: nowrap;
      }
    }
  }
  .list {
    display: flex;
    margin-top: 20px;
    overflow: hidden;
    list-style: none;
    padding: 0;
    .item {
      flex: none;
      width: 200px;
      font-size: 14px;
      line-height: 14px;
      color: rgba(0, 0, 0, 0.85);
      .title {
        margin-bottom: 12px;
        font-size: 13px;
        line-height: 13px;
        color: #666666;
      }
      .value1 {
        color: #f56022;
      }

      .value2 {
        color: #1bbe6b;
      }

      .value3 {
        color: var(--prev-color-primary);
      }

      .value4 {
        color: #6a7b9d;
      }

      .value5 {
        color: #f5222d;
      }
    }
  }
}
.section {
  padding: 25px 0;
  border-bottom: 1px dashed #eeeeee;
  .title {
    padding-left: 10px;
    border-left: 3px solid var(--prev-color-primary);
    font-size: 15px;
    line-height: 15px;
    color: #303133;
  }
  .list {
    display: flex;
    flex-wrap: wrap;
    list-style: none;
    padding: 0;
  }
  .item {
    flex: 0 0 calc(100% / 3);
    display: flex;
    margin-top: 16px;
    font-size: 13px;
    color: #666666;
    &:nth-child(3n + 1) {
      padding-right: 20px;
    }

    &:nth-child(3n + 2) {
      padding-right: 10px;
      padding-left: 10px;
    }

    &:nth-child(3n + 3) {
      padding-left: 20px;
    }
  }
  .value {
    flex: 1;
    image {
      display: inline-block;
      width: 40px;
      height: 40px;
      margin: 0 12px 12px 0;
      vertical-align: middle;
    }
  }
  .item.pic {
    display: flex;
    img {
      width: 80px;
      height: 80px;
    }
  }
}
.tab {
  display: flex;
  align-items: center;
  .el-image {
    width: 36px;
    height: 36px;
    margin-right: 10px;
  }
}
::v-deep .el-drawer__body {
  // padding: 0;
  overflow: auto;
}
.gary {
  color: #aaa;
}
::v-deep .el-drawer__body {
  padding: 20px 0;
}
::v-deep .el-tabs--border-card > .el-tabs__content {
  padding: 0 35px;
}
::v-deep .el-tabs--border-card > .el-tabs__header,
::v-deep .el-tabs--border-card > .el-tabs__header .el-tabs__item:active {
  border: none;
  height: 40px;
}
::v-deep .el-tabs--border-card > .el-tabs__header .el-tabs__item.is-active {
  border: none;
  border-top: 2px solid var(--prev-color-primary);
  font-size: 13px;
  font-weight: 500;
  color: #303133;
  line-height: 16px;
}
::v-deep .el-tabs--border-card > .el-tabs__header .el-tabs__item {
  border: none;
}
::v-deep .el-tabs--border-card > .el-tabs__header .el-tabs__item {
  margin-top: 0;
  transition: none;
  height: 40px !important;
  line-height: 40px !important;
  width: 92px !important;
  font-size: 13px;
  font-weight: 400;
  color: #303133;
  line-height: 16px;
}
::v-deep .el-tabs--border-card {
  border: none;
  box-shadow: none;
}

.logistics {
  align-items: center;
  padding: 10px 20px;

  .logistics_img {
    width: 45px;
    height: 45px;
    margin-right: 12px;

    img {
      width: 100%;
      height: 100%;
    }
  }

  .logistics_cent {
    span {
      display: block;
      font-size: 12px;
    }
  }
}
.trees-coadd {
  width: 100%;
  height: 400px;
  border-radius: 4px;
  overflow: hidden;

  .scollhide {
    width: 100%;
    height: 100%;
    overflow: auto;
    margin-left: 18px;
    padding: 10px 0 10px 0;
    box-sizing: border-box;

    .content {
      font-size: 12px;
    }

    .time {
      font-size: 12px;
      color: #2d8cf0;
    }
  }
}
</style>
