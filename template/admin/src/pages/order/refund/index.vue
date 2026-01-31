<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt" :body-style="{ padding: 0 }">
      <div class="padding-add">
        <el-form
          ref="pagination"
          :model="pagination"
          :label-width="labelWidth"
          :label-position="labelPosition"
          @submit.native.prevent
          inline
        >
          <el-form-item :label="$t('message.pages.order.refundList.refundStatus')">
            <el-select
              v-model="pagination.refund_type"
              clearable
              class="form_content_width"
              @change="selectChange2"
              :placeholder="$t('message.pages.order.refundList.all')"
            >
              <el-option v-for="(item, index) in num" :value="index" :key="index" :label="getRefundStatusFilterLabel(index)">{{
                getRefundStatusFilterLabel(index)
              }}</el-option>
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('message.pages.order.refundList.refundTime')">
            <el-date-picker
              clearable
              v-model="timeVal"
              type="daterange"
              :editable="false"
              @change="onchangeTime"
              format="yyyy/MM/dd"
              value-format="yyyy/MM/dd"
              :start-placeholder="$t('message.pages.order.refundList.startDate')"
              :end-placeholder="$t('message.pages.order.refundList.endDate')"
              :picker-options="pickerOptions"
              style="width: 250px"
              class="mr20"
            ></el-date-picker>
          </el-form-item>
          <el-form-item :label="$t('message.pages.order.refundList.orderSearch')" label-for="title">
            <el-input clearable v-model="pagination.order_id" :placeholder="$t('message.pages.order.refundList.inputOrderNo')" class="form_content_width" />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" v-db-click @click="orderSearch">{{ $t('message.pages.order.refundList.query') }}</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <el-card :bordered="false" shadow="never" class="ivu-mt mt16">
      <el-table
        :data="tbody"
        ref="table"
        v-loading="loading"
        highlight-current-row
        :no-userFrom-text="$t('message.pages.order.refundList.noData')"
        :no-filtered-userFrom-text="$t('message.pages.order.refundList.noFilterResult')"
      >
        <el-table-column :label="$t('message.pages.order.refundList.refundOrderNo')" min-width="150">
          <template slot-scope="scope">
            <span
              class="cup hover-pimary"
              v-text="scope.row.order_id"
              style="display: block"
              @click="changeMenu(scope.row, '2')"
            ></span>
            <span v-if="scope.row.is_del === 1" style="color: #ed4014; display: block">{{ $t('message.pages.order.list.userDeleted') }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.order.refundList.originalOrderNo')" min-width="150">
          <template slot-scope="scope">
            <span
              class="cup hover-pimary"
              v-text="scope.row.store_order_order_id"
              style="display: block"
              @click="changeMenu(scope.row, '3')"
            ></span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.order.refundList.goodsInfo')" min-width="250">
          <template slot-scope="scope">
            <div class="tab" v-for="(item, i) in scope.row._info" :key="i">
              <img
                v-lazy="
                  item.cart_info.productInfo.attrInfo
                    ? item.cart_info.productInfo.attrInfo.image
                    : item.cart_info.productInfo.image
                "
              />
              <el-tooltip placement="top" :open-delay="300">
                <div slot="content">
                  <div>
                    <span>{{ $t('message.pages.order.refundList.productName') }}</span>
                    <span>{{ item.cart_info.productInfo.store_name || '--' }}</span>
                  </div>
                  <div>
                    <span>{{ $t('message.pages.order.refundList.specName') }}</span>
                    <span>{{
                      item.cart_info.productInfo.attrInfo ? item.cart_info.productInfo.attrInfo.suk : '---'
                    }}</span>
                  </div>
                  <div>
                    <span>{{ $t('message.pages.order.refundList.price') }}</span>
                    <span>¥{{ item.cart_info.truePrice || '--' }}</span>
                  </div>
                  <div>
                    <span>{{ $t('message.pages.order.refundList.num') }}</span>
                    <span>{{ item.cart_info.cart_num || '--' }}</span>
                  </div>
                </div>
                <span class="line2 w-250">{{ item.cart_info.productInfo.store_name }}</span>
              </el-tooltip>
            </div>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.order.refundList.userInfo')" min-width="100">
          <template slot-scope="scope">
            <span class="cup hover-pimary" @click="userDetail(scope.row, '2')">{{ scope.row.nickname }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.order.refundList.actualPay')" min-width="70">
          <template slot-scope="scope">
            <span>{{ scope.row.pay_price }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.order.refundList.refundStartTime')" min-width="120">
          <template slot-scope="scope">
            <span>{{ scope.row.add_time }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.order.refundList.refundStatusCol')" min-width="100">
          <template slot-scope="scope">
            <div v-if="scope.row.refund_type == 1">{{ $t('message.pages.order.refundList.refundOnly') }}</div>
            <div v-else-if="scope.row.refund_type == 2">{{ $t('message.pages.order.refundList.returnRefund') }}</div>
            <div v-else-if="scope.row.refund_type == 3">
              <div>{{ $t('message.pages.order.refundList.refuseRefund') }}</div>
              <div class="c-red">{{ $t('message.pages.order.refundList.reason') }}{{ scope.row.refuse_reason }}</div>
            </div>
            <div v-else-if="scope.row.refund_type == 4">{{ $t('message.pages.order.refundList.goodsToReturn') }}</div>
            <div v-else-if="scope.row.refund_type == 5">
              <div>{{ $t('message.pages.order.refundList.returnToReceive') }}</div>
              <div>{{ $t('message.pages.order.refundList.expressNo') }}{{ scope.row.refund_express }}</div>
            </div>
            <div v-else-if="scope.row.refund_type == 6">{{ $t('message.pages.order.refundList.refunded') }}</div>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.order.refundList.orderStatus')" min-width="120">
          <template slot-scope="scope">
            <span>{{ scope.row.store_order_status }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.order.refundList.refundInfo')" min-width="120">
          <template slot-scope="scope">
            <div v-html="scope.row.refund_reason" class="pt5"></div>
            <div class="pictrue-box" v-if="scope.row.refund_img.length">
              <div v-viewer v-for="(item, index) in scope.row.refund_img || []" :key="index">
                <img class="pictrue mr10" v-lazy="item" :src="item" />
              </div>
            </div>
          </template>
        </el-table-column>
        <el-table-column fixed="right" :label="$t('message.pages.order.refundList.action')" width="80">
          <template slot-scope="scope">
            <el-dropdown size="small" @command="changeMenu(scope.row, $event)">
              <span class="el-dropdown-link">{{ $t('message.pages.order.refundList.more') }}<i class="el-icon-arrow-down el-icon--right"></i> </span>
              <el-dropdown-menu slot="dropdown">
                <el-dropdown-item
                  command="1"
                  ref="ones"
                  v-show="scope.row._status === 1 && scope.row.paid === 0 && scope.row.pay_type === 'offline'"
                  >{{ $t('message.pages.order.refundList.payNow') }}</el-dropdown-item
                >
                <el-dropdown-item command="2">{{ $t('message.pages.order.refundList.orderDetail') }}</el-dropdown-item>
                <el-dropdown-item
                  command="4"
                  v-show="
                    scope.row._status !== 1 ||
                    (scope.row._status === 3 &&
                      scope.row.use_integral > 0 &&
                      scope.row.use_integral >= scope.row.back_integral)
                  "
                  >{{ $t('message.pages.order.refundList.afterSaleRemark') }}</el-dropdown-item
                >
                <el-dropdown-item
                  command="5"
                  v-show="
                    [1, 2, 5].includes(scope.row.refund_type) &&
                    (parseFloat(scope.row.pay_price) > parseFloat(scope.row.refunded_price) || scope.row.pay_price == 0)
                  "
                  >{{ scope.row.refund_type == 2 ? $t('message.pages.order.refundList.agreeReturn') : $t('message.pages.order.refundList.refundNow') }}</el-dropdown-item
                >
                <el-dropdown-item
                  command="7"
                  v-show="[1, 2].includes(scope.row.refund_type) && scope.row.is_pink_cancel === 0"
                  >{{ $t('message.pages.order.refundList.noRefund') }}</el-dropdown-item
                >
                <el-dropdown-item command="8" v-show="scope.row.is_del == 1">{{ $t('message.pages.order.refundList.delOrder') }}</el-dropdown-item>
              </el-dropdown-menu>
            </el-dropdown>
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination
          v-if="total"
          :total="total"
          :page.sync="pagination.page"
          :limit.sync="pagination.limit"
          @pagination="getOrderList"
        />
      </div>
    </el-card>
    <!-- 编辑 退款 退积分 不退款-->
    <edit-from ref="edits" :FromData="FromData" @submitFail="submitFail"></edit-from>
    <!-- 会员详情-->
    <user-details ref="userDetails"></user-details>
    <!-- 详情 -->
    <details-from ref="detailss" :orderDatalist="orderDatalist" :orderId="orderId" :is_refund="1"></details-from>
    <!-- 备注 -->
    <order-remark ref="remarks" remarkType="refund" :orderId="orderId" @submitFail="submitFail"></order-remark>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import {
  orderRefundList,
  getDataInfo,
  getDataInfoNew,
  getNewRefundFrom,
  getNewnoRefundFrom,
  refundIntegral,
  getDistribution,
} from '@/api/order';
import userDetails from '@/pages/user/list/handle/userDetails';

import editFrom from '@/components/from/from';
import detailsFrom from '../orderList/handle/orderDetails';
import orderRemark from '../orderList/handle/orderRemark';
import timeOptions from '@/libs/timeOptions';
export default {
  components: { editFrom, detailsFrom, orderRemark, userDetails },
  data() {
    return {
      grid: {
        xl: 7,
        lg: 8,
        md: 12,
        sm: 24,
        xs: 24,
      },
      tbody: [],
      num: [],
      orderDatalist: null,
      loading: false,
      FromData: null,
      total: 0,
      orderId: 0,
      animal: 1,
      pagination: {
        page: 1,
        limit: 15,
        order_id: '',
        time: '',
        refund_type: 0,
      },
      timeVal: [],
      modal: false,
      qrcode: null,
      name: '',
      spin: false,
      pickerOptions: timeOptions,
    };
  },
  computed: {
    ...mapState('order', ['orderChartType']),
    // ...mapState("admin/layout", ["isMobile"]),
    labelWidth() {
      return this.isMobile ? undefined : '80px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  created() {
    this.getOrderList();
  },
  methods: {
    onchangeCode(e) {
      this.animal = e;
      this.qrcodeShow();
    },
    // 具体日期搜索()；
    onchangeTime(e) {
      this.pagination.page = 1;
      this.timeVal = e || [];
      this.pagination.time = this.timeVal[0] ? (this.timeVal ? this.timeVal.join('-') : '') : '';
      this.getOrderList();
    },
    userDetail(row) {
      this.$refs.userDetails.modals = true;
      this.$refs.userDetails.getDetails(row.uid);
    },
    // 操作
    changeMenu(row, name) {
      this.orderId = row.id;
      switch (name) {
        case '1':
          this.delfromData = {
            title: this.$t('message.pages.order.refundList.modifyPayTitle'),
            url: `/order/pay_offline/${row.id}`,
            method: 'post',
            ids: '',
          };
          this.$modalSure(this.delfromData)
            .then((res) => {
              this.$message.success(res.msg);
              this.getOrderList();
            })
            .catch((res) => {
              this.$message.error(res.msg);
            });
          // this.modalTitleSs = '修改立即支付';
          break;
        case '2':
          this.getData(row.order_id, 2);
          break;
        case '3':
          this.getData(row.store_order_id, 3);
          break;
        case '4':
          this.$refs.remarks.modals = true;
          this.$refs.remarks.formValidate.remark = row.remark;
          break;
        case '5':
          this.getRefundData(row.id, row.refund_type);
          break;
        case '6':
          this.getRefundIntegral(row.id);
          break;
        case '7':
          this.getNoRefundData(row.id);
          break;
        case '8':
          this.delfromData = {
            title: this.$t('message.pages.order.refundList.delOrderTitle'),
            url: `/order/del/${row.store_order_id}`,
            method: 'DELETE',
            ids: '',
          };
          this.delOrder(row, this.delfromData);
          break;
        case '10':
          this.delfromData = {
            title: this.$t('message.pages.order.refundList.printOrderTitle'),
            info: this.$t('message.pages.order.refundList.printOrderConfirm'),
            url: `/order/print/${row.id}`,
            method: 'get',
            ids: '',
          };
          this.$modalSure(this.delfromData)
            .then((res) => {
              this.$message.success(res.msg);
              this.$emit('changeGetTabs');
              this.getOrderList();
            })
            .catch((res) => {
              this.$message.error(res.msg);
            });
          break;
        case '11':
          this.delfromData = {
            title: this.$t('message.pages.order.refundList.printExpressTitle'),
            info: this.$t('message.pages.order.refundList.printExpressConfirm'),
            url: `/order/order_dump/${row.id}`,
            method: 'get',
            ids: '',
          };
          this.$modalSure(this.delfromData)
            .then((res) => {
              this.$message.success(res.msg);
              this.getOrderList();
            })
            .catch((res) => {
              this.$message.error(res.msg);
            });
          break;
        default:
          this.delfromData = {
            title: this.$t('message.pages.order.refundList.delOrderTitle'),
            url: `/order/del/${row.id}`,
            method: 'DELETE',
            ids: '',
          };
          // this.modalTitleSs = '删除订单';
          this.delOrder(row, this.delfromData);
      }
    },
    // 获取退款表单数据
    getRefundData(id, refund_type) {
      if (refund_type == 2) {
        this.delfromData = {
          title: this.$t('message.pages.order.refundList.agreeReturnRefundTitle'),
          url: `/refund/agree/${id}`,
          method: 'get',
        };
        this.$modalSure(this.delfromData)
          .then((res) => {
            this.$message.success(res.msg);
            this.getOrderList();
          })
          .catch((res) => {
            this.$message.error(res.msg);
          });
      } else {
        this.$modalForm(getNewRefundFrom(id)).then(() => {
          this.getOrderList();
          this.$emit('changeGetTabs');
        });
      }
    },
    // 获取退积分表单数据
    getRefundIntegral(id) {
      refundIntegral(id)
        .then(async (res) => {
          this.FromData = res.data;
          this.$refs.edits.modals = true;
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 获取详情表单数据
    getData(id, type) {
      let fun;
      if (type == 2) {
        fun = getDataInfoNew;
      } else {
        fun = getDataInfo;
      }
      fun(id)
        .then(async (res) => {
          this.orderDatalist = res.data;
          // if (this.orderDatalist.orderInfo.refund_img.length) {
          //   try {
          //     this.orderDatalist.orderInfo.refund_img = this.orderDatalist.orderInfo.refund_img;
          //   } catch (e) {
          //     this.orderDatalist.orderInfo.refund_img = [];
          //   }
          // }
          this.$nextTick((e) => {
            this.$refs.detailss.modals = true;
          });
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 删除单条订单
    delOrder(row, data) {
      if (row.is_del === 1) {
        this.$modalSure(data)
          .then((res) => {
            this.$message.success(res.msg);
            this.getOrderList();
          })
          .catch((res) => {
            this.$message.error(res.msg);
          });
      } else {
        this.$message.error(this.$t('message.pages.order.refundList.msgUserNotDel'));
      }
    },
    // 修改成功
    submitFail() {
      this.getOrderList();
    },
    // 退款状态下拉选项文案（i18n）
    getRefundStatusFilterLabel(index) {
      const keys = ['all', 'refundOnly', 'returnRefund', 'refuseRefund', 'goodsToReturn', 'returnToReceive', 'refunded'];
      return this.$t('message.pages.order.refundList.' + (keys[index] || 'all'));
    },
    // 订单选择状态
    selectChange2(tab) {
      this.pagination.page = 1;
      this.getOrderList(tab);
    },
    // 不退款表单数据
    getNoRefundData(id) {
      this.$modalForm(getNewnoRefundFrom(id)).then(() => {
        this.getOrderList();
        this.$emit('changeGetTabs');
      });
    },
    // 订单列表
    getOrderList() {
      this.loading = true;
      orderRefundList(this.pagination)
        .then((res) => {
          this.loading = false;
          const { count, list, num } = res.data;
          this.total = count;
          this.tbody = list;
          this.num = num;
        })
        .catch((err) => {
          this.loading = false;
          this.$message.error(err.msg);
        });
    },
    nameSearch() {
      this.pagination.page = 1;
      this.getOrderList();
    },
    // 订单搜索
    orderSearch() {
      this.pagination.page = 1;
      this.getOrderList();
    },
    // 配送信息表单数据
    delivery(row) {
      getDistribution(row.id)
        .then(async (res) => {
          this.FromData = res.data;
          this.$refs.edits.modals = true;
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
  },
};
</script>

<style lang="scss" scoped>
.code {
  position: relative;
}
.ivu-form-item {
}
.QRpic {
  width: 180px;
  height: 259px;

  img {
    width: 100%;
    height: 100%;
  }
}
.tabBox {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  .tabBox_img {
    width: 36px;
    height: 36px;

    img {
      width: 100%;
      height: 100%;
    }
  }
  .tabBox_tit {
    width: 60%;
    font-size: 12px !important;
    margin: 0 2px 0 10px;
    letter-spacing: 1px;
    padding: 5px 0;
    box-sizing: border-box;
  }
}
.pictrue-box {
  display: flex;
  align-item: center;
}
.pictrue {
  width: 25px;
  height: 25px;
}
.tab {
  display: flex;
  align-items: center;

  img {
    width: 36px;
    height: 36px;
    margin-right: 10px;
  }
}
.w-250 {
  max-width: 250px;
}
.w-120 {
  width: 120px;
}
</style>
