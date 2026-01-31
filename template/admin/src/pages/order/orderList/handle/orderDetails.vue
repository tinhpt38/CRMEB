<template>
  <div>
    <el-drawer :title="$t('message.pages.order.details.title')" :size="1000" :visible.sync="modals" wrapperClosable :before-close="handleClose">
      <div v-if="orderDatalist">
        <div class="head">
          <div class="full">
            <img class="order_icon" :src="orderImg" alt="" />
            <div class="text">
              <div class="title">{{ $t('message.pages.order.details.normalOrder') }}</div>
              <div>
                <span class="mr20">{{ $t('message.pages.order.details.orderNo') }}{{ orderDatalist.orderInfo.order_id }}</span>
              </div>
            </div>
          </div>
          <ul class="list">
            <li class="item">
              <div class="title">{{ $t('message.pages.order.details.orderStatus') }}</div>
              <div>
                {{ orderDatalist.orderInfo._status._title }}
                {{
                  orderDatalist.orderInfo.refund &&
                  orderDatalist.orderInfo.refund.length &&
                  orderDatalist.orderInfo.refund_status < 2
                    ? orderDatalist.orderInfo.is_all_refund
                      ? $t('message.pages.order.list.refunding')
                      : $t('message.pages.order.list.partialRefunding')
                    : ''
                }}
              </div>
            </li>
            <li class="item">
              <div class="title">{{ $t('message.pages.order.details.actualPay') }}</div>
              <div>¥ {{ orderDatalist.orderInfo.pay_price || '0.0' }}</div>
            </li>
            <li class="item" v-if="orderDatalist.orderInfo.refund_status == 2">
              <div class="title">{{ $t('message.pages.order.details.actualRefund') }}</div>
              <div>¥ {{ orderDatalist.orderInfo.refunded_price || '0.0' }}</div>
            </li>
            <li class="item">
              <div class="title">{{ $t('message.pages.order.details.payType') }}</div>
              <div>{{ getPayTypeName(orderDatalist.orderInfo.pay_type) }}</div>
            </li>
            <li class="item">
              <div class="title">{{ $t('message.pages.order.details.payTime') }}</div>
              <div>{{ orderDatalist.orderInfo._pay_time }}</div>
            </li>
          </ul>
        </div>
        <el-tabs type="border-card" v-model="activeName" @tab-click="tabClick">
          <el-tab-pane :label="$t('message.pages.order.details.orderInfoTab')" name="detail">
            <div class="section">
              <div class="title">{{ $t('message.pages.order.details.userInfo') }}</div>
              <ul class="list">
                <li class="item">
                  <div>{{ $t('message.pages.order.details.userName') }}</div>
                  <div class="value">{{ orderDatalist.userInfo.real_name }}</div>
                </li>
                <li class="item">
                  <div>{{ $t('message.pages.order.details.bindPhone') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.user_phone || '' }}</div>
                </li>
              </ul>
            </div>
            <div class="section">
              <div class="title">{{ $t('message.pages.order.details.receiverInfo') }}</div>
              <ul class="list">
                <li class="item">
                  <div>{{ $t('message.pages.order.details.receiverName') }}</div>
                  <div class="value">
                    {{ orderDatalist.orderInfo.real_name ? orderDatalist.orderInfo.real_name : '-' }}
                  </div>
                </li>
                <li class="item">
                  <div>{{ $t('message.pages.order.details.receiverPhone') }}</div>
                  <div class="value">
                    {{ orderDatalist.orderInfo.user_phone ? orderDatalist.orderInfo.user_phone : '-' }}
                  </div>
                </li>
                <li class="item">
                  <div>{{ $t('message.pages.order.details.receiverAddress') }}</div>
                  <div class="value">
                    {{ orderDatalist.orderInfo.user_address ? orderDatalist.orderInfo.user_address : '-' }}
                  </div>
                </li>
              </ul>
            </div>
            <div class="section">
              <div class="title">{{ $t('message.pages.order.details.orderInfoSection') }}</div>
              <ul class="list">
                <li class="item">
                  <div>{{ $t('message.pages.order.details.createTime') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo._add_time }}</div>
                </li>
                <li class="item">
                  <div>{{ $t('message.pages.order.details.totalNum') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.total_num }}</div>
                </li>
                <li class="item">
                  <div>{{ $t('message.pages.order.details.totalPrice') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.total_price }}</div>
                </li>
                <li class="item">
                  <div>{{ $t('message.pages.order.details.couponAmount') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.coupon_price }}</div>
                </li>
                <li class="item">
                  <div>{{ $t('message.pages.order.details.deductionAmount') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.deduction_price || '0.0' }}</div>
                </li>
                <li class="item">
                  <div>{{ $t('message.pages.order.details.postage') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.pay_postage }}</div>
                </li>
                <li class="item">
                  <div>{{ $t('message.pages.order.details.levelDiscount') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.levelPrice || '0.0' }}</div>
                </li>
                <li class="item">
                  <div>{{ $t('message.pages.order.details.memberDiscount') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.memberPrice || '0.0' }}</div>
                </li>
                <li class="item">
                  <div>{{ $t('message.pages.order.details.actualPayLabel') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.pay_price || '0.0' }}</div>
                </li>
              </ul>
            </div>
            <div class="section">
              <div class="title">{{ $t('message.pages.order.details.brokerageInfo') }}</div>
              <ul class="list">
                <li class="item">
                  <div>{{ $t('message.pages.order.details.oneBrokerage') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.one_brokerage }} | {{ orderDatalist.orderInfo.spread_uid }}</div>
                </li>
                <li class="item">
                  <div>{{ $t('message.pages.order.details.twoBrokerage') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.two_brokerage }} | {{ orderDatalist.orderInfo.spread_two_uid }}</div>
                </li>
                <li class="item">
                  <div>{{ $t('message.pages.order.details.staffBrokerage') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.staff_brokerage }} | {{ orderDatalist.orderInfo.staff_id }}</div>
                </li>
                <li class="item">
                  <div>{{ $t('message.pages.order.details.agentBrokerage') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.agent_brokerage }} | {{ orderDatalist.orderInfo.agent_id }}</div>
                </li>
                <li class="item">
                  <div>{{ $t('message.pages.order.details.divisionBrokerage') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.division_brokerage }} | {{ orderDatalist.orderInfo.division_id }}</div>
                </li>
              </ul>
            </div>
            <div class="section" v-if="orderDatalist.orderInfo.delivery_name">
              <div class="title">
                {{ orderDatalist.orderInfo.delivery_type == 'express' ? $t('message.pages.order.details.logisticsInfo') : $t('message.pages.order.details.deliveryPersonInfo') }}
              </div>
              <ul class="list">
                <li class="item">
                  <div>{{ orderDatalist.orderInfo.delivery_type == 'express' ? $t('message.pages.order.details.logisticsCompany') : $t('message.pages.order.details.deliveryPersonName') }}</div>
                  <div class="value">
                    {{ orderDatalist.orderInfo.delivery_name ? orderDatalist.orderInfo.delivery_name : '-' }}
                  </div>
                </li>
                <li class="item">
                  <div>{{ orderDatalist.orderInfo.delivery_type == 'express' ? $t('message.pages.order.details.logisticsNo') : $t('message.pages.order.details.deliveryPersonPhone') }}</div>
                  <div class="value">
                    {{ orderDatalist.orderInfo.delivery_id }}
                    <a v-if="orderDatalist.orderInfo.delivery_type == 'express'" v-db-click @click="openLogistics"
                      >{{ $t('message.pages.order.details.logisticsQuery') }}</a
                    >
                  </div>
                </li>
              </ul>
            </div>
            <div class="section" v-if="orderDatalist.orderInfo.invoice">
              <div class="title">{{ $t('message.pages.order.details.invoiceInfo') }}</div>
              <ul class="list">
                <li class="item">
                  <div>{{ $t('message.pages.order.details.invoiceTitle') }}</div>
                  <div class="value">
                    {{ orderDatalist.orderInfo.invoice.name }}
                  </div>
                </li>
                <li
                  class="item"
                  v-if="orderDatalist.orderInfo.invoice.header_type === 2 && orderDatalist.orderInfo.invoice.type === 1"
                >
                  <div>{{ $t('message.pages.order.details.dutyNumber') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.invoice.duty_number }}</div>
                </li>
                <li
                  class="item"
                  v-if="orderDatalist.orderInfo.invoice.header_type === 2 && orderDatalist.orderInfo.invoice.type === 1"
                >
                  <div>{{ $t('message.pages.order.details.invoiceType') }}</div>
                  <div class="value">{{ $t('message.pages.order.details.electronicInvoice') }}</div>
                </li>
                <li
                  class="item"
                  v-if="orderDatalist.orderInfo.invoice.header_type === 2 && orderDatalist.orderInfo.invoice.type === 1"
                >
                  <div>{{ $t('message.pages.order.details.invoiceTitleType') }}</div>
                  <div class="value">{{ $t('message.pages.order.details.company') }}</div>
                </li>
                <li
                  class="item"
                  v-if="orderDatalist.orderInfo.invoice.header_type === 1 && orderDatalist.orderInfo.invoice.type === 1"
                >
                  <div>{{ $t('message.pages.order.details.realName') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.invoice.name || '' }}</div>
                </li>
                <li
                  class="item"
                  v-if="orderDatalist.orderInfo.invoice.header_type === 1 && orderDatalist.orderInfo.invoice.type === 1"
                >
                  <div>{{ $t('message.pages.order.details.contactPhone') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.invoice.drawer_phone || '' }}</div>
                </li>
                <li
                  class="item"
                  v-if="orderDatalist.orderInfo.invoice.header_type === 2 && orderDatalist.orderInfo.invoice.type === 1"
                >
                  <div>{{ $t('message.pages.order.details.contactPhone') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.invoice.user_phone || '' }}</div>
                </li>
                <li
                  class="item"
                  v-if="orderDatalist.orderInfo.invoice.header_type === 2 && orderDatalist.orderInfo.invoice.type === 1"
                >
                  <div>{{ $t('message.pages.order.details.contactEmail') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.invoice.email || '' }}</div>
                </li>
                <li
                  class="item"
                  v-if="orderDatalist.orderInfo.invoice.header_type === 2 && orderDatalist.orderInfo.invoice.type === 1"
                >
                  <div>{{ $t('message.pages.order.details.invoiceStatus') }}</div>
                  <div class="value">{{ orderDatalist.orderInfo.invoice.is_invoice ? $t('message.pages.order.details.invoiced') : $t('message.pages.order.details.notInvoiced') }}</div>
                </li>
              </ul>
            </div>
            <div class="section">
              <div class="title">{{ $t('message.pages.order.details.buyerRemark') }}</div>
              <ul class="list">
                <li class="item">
                  <div>{{ orderDatalist.orderInfo.mark ? orderDatalist.orderInfo.mark : '-' }}</div>
                </li>
              </ul>
            </div>
            <div class="section" v-if="orderDatalist.orderInfo.custom_form.length">
              <div class="title">{{ $t('message.pages.order.details.formInfo') }}</div>
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
              <div class="title">{{ $t('message.pages.order.details.orderRemarkSection') }}</div>
              <ul class="list">
                <li class="item">
                  <div>{{ orderDatalist.orderInfo.remark ? orderDatalist.orderInfo.remark : '-' }}</div>
                </li>
              </ul>
            </div>
          </el-tab-pane>
          <el-tab-pane :label="$t('message.pages.order.details.goodsInfoTab')" name="goods">
            <el-table class="mt20" :data="orderDatalist.orderInfo.cartInfo">
              <el-table-column :label="$t('message.pages.order.details.goodsInfoCol')" min-width="300">
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
                        {{ $t('message.pages.order.details.spec') }}{{ scope.row.productInfo.attrInfo ? scope.row.productInfo.attrInfo.suk : $t('message.pages.order.details.default') }}
                      </div>
                    </div>
                  </div>
                </template>
              </el-table-column>
              <el-table-column :label="$t('message.pages.order.details.payPriceCol')" min-width="90">
                <template slot-scope="scope">
                  <div class="tab">
                    <div class="line1">
                      {{ scope.row.truePrice }}
                    </div>
                  </div>
                </template>
              </el-table-column>
              <el-table-column :label="$t('message.pages.order.details.buyNumCol')" min-width="90">
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
          <el-tab-pane :label="$t('message.pages.order.details.recordTab')" name="orderList">
            <el-table class="mt20" :data="recordData" v-loading="loading" :empty-text="$t('message.common.noData')" highlight-current-row>
              <el-table-column :label="$t('message.pages.order.details.orderIdCol')" min-width="100">
                <template slot-scope="scope">
                  <span>{{ scope.row.oid }}</span>
                </template>
              </el-table-column>
              <el-table-column :label="$t('message.pages.order.details.operationRecord')" min-width="100">
                <template slot-scope="scope">
                  <span>{{ scope.row.change_message }}</span>
                </template>
              </el-table-column>
              <el-table-column :label="$t('message.pages.order.details.operationTime')" min-width="100">
                <template slot-scope="scope">
                  <span>{{ scope.row.change_time }}</span>
                </template>
              </el-table-column>
            </el-table>
          </el-tab-pane>
        </el-tabs>
      </div>
    </el-drawer>
    <el-drawer :visible.sync="modal2" scrollable :title="$t('message.pages.order.details.logisticsQueryTitle')" width="350px" class="order_box2">
      <div class="logistics acea-row row-top" v-if="orderDatalist">
        <div class="logistics_img">
          <img src="../../../../assets/images/expressi.jpg" />
        </div>
        <div class="logistics_cent">
          <span>{{ $t('message.pages.order.details.logisticsCompanyCol') }}{{ orderDatalist.orderInfo.delivery_name }}</span>
          <span>{{ $t('message.pages.order.details.logisticsNoCol') }}{{ orderDatalist.orderInfo.delivery_id }}</span>
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
  methods: {
    getPayTypeName(val) {
      if (!val) return '--';
      const key = { yue: 'yue', weixin: 'weixin', alipay: 'alipay', offline: 'offline' }[val];
      return key ? this.$t('message.pages.order.payType.' + key) : val;
    },
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
