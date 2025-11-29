<template>
  <div>
    <el-tabs v-model="currentTab" @tab-click="onClickTab" v-if="tablists">
      <el-tab-pane name="null" :label="$t('message.orderList.allTab')"></el-tab-pane>
      <el-tab-pane
        name="0"
        :label="orderChartType.un_paid > 0 ? `${$t('message.orderList.unpaid')}(${orderChartType.un_paid})` : $t('message.orderList.unpaid')"
      ></el-tab-pane>
      <el-tab-pane
        name="1"
        :label="orderChartType.un_send > 0 ? `${$t('message.orderList.unsend')}(${orderChartType.un_send})` : $t('message.orderList.unsend')"
      ></el-tab-pane>
      <el-tab-pane name="5" :label="$t('message.orderList.unverify')"></el-tab-pane>
      <el-tab-pane name="2" :label="$t('message.orderList.unreceive')"></el-tab-pane>
      <el-tab-pane name="3" :label="$t('message.orderList.uncomment')"></el-tab-pane>
      <el-tab-pane name="4" :label="$t('message.orderList.completed')"></el-tab-pane>
      <el-tab-pane name="-2" :label="$t('message.orderList.refunded')"></el-tab-pane>
      <el-tab-pane name="-4" :label="$t('message.orderList.deleted')"></el-tab-pane>
    </el-tabs>
    <div class="acea-row">
      <el-button v-auth="['order-write']" type="primary" v-db-click @click="writeOff">{{ $t('message.orderList.orderWriteoff') }}</el-button>
      <el-button v-db-click @click="batchShipmentModal = true">{{ $t('message.orderList.batchShipment') }}</el-button>
      <!-- <el-upload class="mr14" :action="expressUrl" :headers="header" :on-success="upExpress">
        <el-button class="export" type="primary">批量发货</el-button>
      </el-upload> -->
      <el-button v-auth="['order-dels']" v-db-click @click="delAll">{{ $t('message.orderList.batchDelete') }}</el-button>
      <el-button v-auth="['export-storeOrder']" class="export" v-db-click @click="exportList">{{ $t('message.orderList.orderExport') }}</el-button>
      <!-- <el-button class="export" v-db-click @click="exportDeliveryList">发货单导出</el-button> -->
    </div>
    <el-table
      :data="orderList"
      ref="table"
      v-loading="loading"
      :empty-text="$t('message.orderList.noData')"
      @select="handleSelectRow"
      @select-all="handleSelectRow"
      class="orderData mt14"
    >
      <el-table-column type="expand">
        <template slot-scope="scope">
          <expandRow :row="scope.row"></expandRow>
        </template>
      </el-table-column>
      <el-table-column type="selection" width="55"> </el-table-column>
      <el-table-column :label="$t('message.orderList.orderIdType')" width="200">
        <template slot-scope="scope">
          <div>{{ scope.row.order_id }}</div>
          <div class="pink_name" :style="{ color: scope.row.color }">{{ scope.row.pink_name }}</div>
          <span v-if="scope.row.is_del === 1" style="color: #ed4014; display: block">{{ $t('message.orderList.userDeleted') }}</span>
          <span v-if="scope.row.is_cancel === 1 && scope.row.is_del === 0" style="color: #ed4014; display: block"
            >{{ $t('message.orderList.userCancelled') }}</span
          >
          <span v-if="scope.row.refund_type === 6" style="color: #ed4014; display: block">{{ $t('message.orderList.orderRefunded') }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('message.orderList.productInfo')" min-width="250">
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
                  <span>{{ $t('message.orderList.productName') }}</span>
                  <span>{{ item.cart_info.productInfo.store_name || '--' }}</span>
                </div>
                <div>
                  <span>{{ $t('message.orderList.specName') }}</span>
                  <span>{{
                    item.cart_info.productInfo.attrInfo ? item.cart_info.productInfo.attrInfo.suk : '---'
                  }}</span>
                </div>
                <div>
                  <span>{{ $t('message.orderList.payPrice') }}</span>
                  <span>¥{{ item.cart_info.truePrice || '--' }}</span>
                </div>
                <div>
                  <span>{{ $t('message.orderList.buyQuantity') }}</span>
                  <span>{{ item.cart_info.cart_num || '--' }}</span>
                </div>
              </div>
              <span class="line2 w-250">{{ item.cart_info.productInfo.store_name }}</span>
            </el-tooltip>
          </div>
        </template>
      </el-table-column>
      <el-table-column :label="$t('message.orderList.userInfo')" min-width="150">
        <template slot-scope="scope">
          <span class="nickname">{{ scope.row.nickname }} | {{ scope.row.uid }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('message.orderList.actualPay')" min-width="100">
        <template slot-scope="scope">
          <span>{{ scope.row.paid ? scope.row.pay_price : $t('message.orderList.unpaidText') }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('message.orderList.payMethod')" min-width="100">
        <template slot-scope="scope">
          <span>{{ scope.row.pay_type_name || '--' }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('message.orderList.payTime')" min-width="150">
        <template slot-scope="scope">
          <span>{{ scope.row._pay_time || '--' }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('message.orderList.orderStatus')" min-width="100">
        <template slot-scope="scope">
          <div v-html="scope.row.status_name.status_name" class="pt5"></div>
          <div v-if="!scope.row.is_all_refund && scope.row.refund.length" class="trip">{{ $t('message.orderList.partialRefunding') }}</div>
          <div
            v-if="
              scope.row.refund_status == 0 &&
              scope.row.is_all_refund &&
              scope.row.refund.length &&
              scope.row.refund_type != 6
            "
            class="trip"
          >
            {{ $t('message.orderList.refunding') }}
          </div>
          <div class="img">
            <template v-if="scope.row.status_name.pics">
              <div v-viewer class="pictrue" v-for="(item, index) in scope.row.status_name.pics || []" :key="index">
                <img v-lazy="item" :src="item" />
              </div>
            </template>
          </div>
        </template>
      </el-table-column>
      <el-table-column :label="$t('message.orderList.operation')" fixed="right" width="130">
        <template slot-scope="scope">
          <a
            v-db-click
            @click="edit(scope.row)"
            v-if="scope.row._status === 1 && scope.row.is_del !== 1 && scope.row.is_cancel !== 1"
            >{{ $t('message.orderList.edit') }}</a
          >
          <el-divider
            direction="vertical"
            v-if="scope.row._status === 1 && scope.row.is_del !== 1 && scope.row.is_cancel !== 1"
          />
          <a
            v-db-click
            @click="sendOrder(scope.row)"
            v-if="
              (scope.row.status === 4 || scope.row._status === 2 || scope.row._status === 8) &&
              scope.row.shipping_type === 1 &&
              (scope.row.pinkStatus === null || scope.row.pinkStatus === 2) &&
              scope.row.is_del !== 1 &&
              scope.row.is_cancel !== 1 &&
              !scope.row.refund.length
            "
            >{{ $t('message.orderList.sendGoods') }}</a
          >
          <el-divider
            direction="vertical"
            v-if="
              (scope.row.status === 4 || scope.row._status === 2 || scope.row._status === 8) &&
              scope.row.shipping_type === 1 &&
              (scope.row.pinkStatus === null || scope.row.pinkStatus === 2) &&
              scope.row.is_del !== 1 &&
              scope.row.is_cancel !== 1 &&
              !scope.row.refund.length
            "
          />
          <a v-db-click @click="delivery(scope.row)" v-if="scope.row._status === 4 && !scope.row.split.length"
            >{{ $t('message.orderList.deliveryInfo') }}</a
          >
          <el-divider direction="vertical" v-if="scope.row._status === 4 && !scope.row.split.length" />
          <a
            v-db-click
            @click="bindWrite(scope.row)"
            v-if="
              scope.row.shipping_type == 2 &&
              scope.row.status == 0 &&
              scope.row.paid == 1 &&
              scope.row.refund_status === 0
            "
            >{{ $t('message.orderList.immediateWriteoff') }}</a
          >
          <el-divider
            direction="vertical"
            v-if="
              scope.row.shipping_type == 2 &&
              scope.row.status == 0 &&
              scope.row.paid == 1 &&
              scope.row.refund_status === 0
            "
          />
          <template>
            <el-dropdown size="small" @command="changeMenu(scope.row, $event)" :transfer="true">
              <span class="el-dropdown-link"> {{ $t('message.orderList.more') }}<i class="el-icon-arrow-down el-icon--right"></i> </span>
              <el-dropdown-menu slot="dropdown">
                <el-dropdown-item
                  command="1"
                  v-show="
                    scope.row._status === 1 &&
                    scope.row.paid === 0 &&
                    scope.row.pay_type === 'offline' &&
                    scope.row.is_del !== 1 &&
                    scope.row.is_cancel !== 1
                  "
                  >{{ $t('message.orderList.confirmPay') }}</el-dropdown-item
                >
                <el-dropdown-item command="2">{{ $t('message.orderList.orderDetails') }}</el-dropdown-item>
                <el-dropdown-item command="11" v-show="scope.row._status >= 3 && scope.row.express_dump"
                  >{{ $t('message.orderList.electronicPrint') }}</el-dropdown-item
                >
                <el-dropdown-item command="10" v-show="scope.row._status >= 2">{{ $t('message.orderList.receiptPrint') }}</el-dropdown-item>
                <el-dropdown-item
                  command="4"
                  v-show="
                    scope.row._status !== 1 ||
                    (scope.row._status === 3 &&
                      scope.row.use_integral > 0 &&
                      scope.row.use_integral >= scope.row.back_integral)
                  "
                  >{{ $t('message.orderList.orderRemark') }}</el-dropdown-item
                >
                <el-dropdown-item
                  command="5"
                  v-show="scope.row.paid == 1 && scope.row.refund_status == 0 && !scope.row.refund.length"
                  >{{ $t('message.orderList.immediateRefund') }}</el-dropdown-item
                >
                <!--                            <el-dropdown-item command="6"  v-show='scope.row._status !==1 && (scope.row.use_integral > 0 && scope.row.use_integral >= scope.row.back_integral) '>退积分</el-dropdown-item>-->
                <!--                            <el-dropdown-item command="7"  v-show='scope.row._status === 3'>不退款</el-dropdown-item>-->
                <el-dropdown-item command="8" v-show="scope.row._status === 4">{{ $t('message.orderList.received') }}</el-dropdown-item>
                <el-dropdown-item command="9">{{ $t('message.orderList.deleteOrder') }}</el-dropdown-item>
                <el-dropdown-item command="12" v-show="scope.row.kuaidi_label">{{ $t('message.orderList.expressPrint') }}</el-dropdown-item>
                <el-dropdown-item command="13" v-show="scope.row.paid">{{ $t('message.orderList.pickingPrint') }}</el-dropdown-item>
              </el-dropdown-menu>
            </el-dropdown>
          </template>
        </template>
      </el-table-column>
    </el-table>
    <div class="acea-row row-right page">
      <pagination v-if="total" :total="total" :page.sync="page.page" :limit.sync="page.limit" @pagination="getList" />
    </div>
    <!-- 编辑 退款 退积分 不退款-->
    <edit-from ref="edits" :FromData="FromData" @submitFail="submitFail"></edit-from>
    <!-- 详情 -->
    <details-from ref="details" :orderDatalist="orderDatalist" :orderId="orderId"></details-from>
    <!-- 备注 -->
    <order-remark ref="remarks" :orderId="orderId" @submitFail="submitFail"></order-remark>
    <!-- 取消寄件 -->
    <order-shipment ref="shipment" :orderId="orderId" @submitFail="submitFail"></order-shipment>
    <!-- 发送货 -->
    <order-send
      ref="send"
      :orderId="orderId"
      :status="status"
      :pay_type="pay_type"
      :virtual_type="virtual_type"
      @submitFail="submitFail"
      @clearId="
        () => {
          orderId = 0;
          virtual_type = null;
        }
      "
    ></order-send>
    <order-refund
      ref="refund"
      :orderId="orderId"
      :status="status"
      :pay_type="pay_type"
      :virtual_type="virtual_type"
      @submitFail="submitFail"
      @clearId="
        () => {
          orderId = 0;
          virtual_type = null;
        }
      "
    ></order-refund>
    <!--    -->
    <el-dialog
      :visible.sync="modals2"
      :title="$t('message.orderList.orderWriteoff')"
      class="paymentFooter"
      :show-close="true"
      width="540px"
      @closed="changeModal"
    >
      <el-form
        ref="writeOffFrom"
        :model="writeOffFrom"
        :rules="writeOffRules"
        label-width="80px"
        label-position="right"
        class="tabform"
        @submit.native.prevent
      >
        <el-form-item prop="code" :label="$t('message.orderList.writeoffCode')">
          <el-input
            style="width: 414px"
            type="text"
            :placeholder="$t('message.orderList.pleaseInputWriteoffCode')"
            v-model.number="writeOffFrom.code"
          />
        </el-form-item>
      </el-form>
      <div slot="footer">
        <el-button type="primary" v-db-click @click="ok('writeOffFrom')">{{ $t('message.orderList.immediateWriteoffBtn') }}</el-button>
        <el-button v-db-click @click="del('writeOffFrom')">{{ $t('message.orderList.cancel') }}</el-button>
      </div>
    </el-dialog>
    <el-dialog
      :visible.sync="batchShipmentModal"
      :title="$t('message.orderList.batchShipment')"
      class="paymentFooter"
      :show-close="true"
      width="540px"
      @closed="changeModal"
    >
      <!-- <el-upload :action="expressUrl" :headers="header" :on-success="upExpress">
        <el-button class="export" type="primary">批量发货</el-button>
      </el-upload> -->
      <el-alert type="warning" :closable="false">
        <p>{{ $t('message.orderList.step1') }}</p>
        <p>{{ $t('message.orderList.step2') }}</p>
        <p>{{ $t('message.orderList.step3') }}</p>
      </el-alert>
      <div class="acea-row row-middle mb10 mt10">
        <el-button v-db-click @click="exportDeliveryList">{{ $t('message.orderList.exportDeliveryList') }}</el-button>
        <div class="pl20 tips"></div>
      </div>
      <el-upload
        class="upload-demo"
        accept=".doc,.docx,.xls,.xlsx"
        drag
        :action="expressUrl"
        :headers="header"
        :on-success="upExpress"
        :before-upload="beforeUpload"
      >
        <i class="el-icon-upload"></i>
        <div class="el-upload__text">{{ $t('message.orderList.batchShipmentUpload') }}</div>
      </el-upload>
    </el-dialog>
  </div>
</template>

<script>
import expandRow from './tableExpand.vue';
import printJS from 'print-js';
import {
  orderList,
  getOrdeDatas,
  getDataInfo,
  getRefundFrom,
  getnoRefund,
  refundIntegral,
  getDistribution,
  writeUpdate,
  shipmentCancelOrder,
  putWrite,
  importExpress,
} from '@/api/order';
import { mapState, mapMutations } from 'vuex';
import editFrom from '../../../../components/from/from';
import detailsFrom from '../handle/orderDetails';
import orderRemark from '../handle/orderRemark';
import orderSend from '../handle/orderSend';
import orderRefund from '../handle/orderRefund';
import orderShipment from '../handle/orderShipment';
import { exportOrderList, exportOrderDeliveryList } from '@api/export';
import Setting from '@/setting';
import { getCookies } from '@/libs/util';
import createWorkBook from '@/vendor/newToExcel.js';
import { isFileUpload } from '@/utils';

export default {
  name: 'table_list',
  components: {
    expandRow,
    editFrom,
    detailsFrom,
    orderRemark,
    orderSend,
    orderShipment,
    orderRefund,
  },
  data() {
    const codeNum = (rule, value, callback) => {
      if (!value) {
        return callback(new Error(this.$t('message.orderList.pleaseInputWriteoffCode')));
      }
      // 模拟异步验证效果
      if (!Number.isInteger(value)) {
        callback(new Error(this.$t('message.orderList.pleaseInput12Digits')));
      } else {
        const reg = /\b\d{12}\b/;
        if (!reg.test(value)) {
          callback(new Error(this.$t('message.orderList.pleaseInput12Digits')));
        } else {
          callback();
        }
      }
    };
    return {
      batchShipmentModal: false,
      expressUrl: Setting.apiBaseURL + '/file/upload/1',
      header: {},
      delfromData: {},
      modal: false,
      orderList: [],
      orderCards: [],
      loading: false,
      orderId: 0,
      total_num: 0,
      virtual_type: 0,
      status: 0,
      pay_type: '',

      total: 0, // 总条数
      page: {
        page: 1, // 当前页
        limit: 15, // 每页显示条数
      },
      data: [],
      FromData: null,
      orderDatalist: null,
      // modalTitleSs: '',
      selectedIds: [], //选中合并项的id
      currentTab: 'null',
      spinShow: false,
      tablists: {
        all: '0',
        general: '0',
        pink: '0',
        seckill: '0',
        bargain: '0',
        advance: '0',
      },
      writeOffRules: {
        code: [{ validator: codeNum, trigger: 'blur', required: true }],
      },
      writeOffFrom: {
        code: '',
        confirm: 0,
      },
      modals2: false,
    };
  },
  computed: {
    ...mapState('order', [
      'orderPayType',
      'orderStatus',
      'orderTime',
      'real_name',
      'fieldKey',
      'orderType',
      'delIdList',
      'isDels',
      'orderChartType',
    ]),
  },
  mounted() {},
  created() {
    this.getTabs();
    this.onChangeTabs('');
    this.getList();
    this.getToken();
  },
  watch: {
    orderType: function () {
      this.page.page = 1;
      this.getList();
    },
  },
  methods: {
    ...mapMutations('order', ['getOrderStatus', 'onChangeTabs', 'getIsDel', 'getisDelIdListl']),
    batchShipment() {},
    beforeUpload(file) {
      return isFileUpload(file);
    },
    // 操作
    changeMenu(row, name) {
      this.orderId = row.id;
      switch (name) {
        case '1':
          this.delfromData = {
            title: this.$t('message.orderList.modifyOrderPaid'),
            url: `/order/pay_offline/${row.id}`,
            method: 'post',
            ids: '',
          };
          this.$modalSure(this.delfromData)
            .then((res) => {
              this.$message.success(res.msg);
              this.$emit('changeGetTabs');
              this.getList();
            })
            .catch((res) => {
              this.$message.error(res.msg);
            });
          // this.modalTitleSs = '修改立即支付';
          break;
        case '2':
          this.getData(row.id);
          break;
        case '4':
          this.$refs.remarks.modals = true;
          this.$refs.remarks.formValidate.remark = row.remark;
          break;
        case '5':
          // this.getRefundData(row.id);
          this.$refs.refund.total_num = row.total_num;
          this.$refs.refund.order_id = row.order_id;
          this.$refs.refund.formItem.refund_price = row.pay_price;
          this.virtual_type = row.virtual_type;
          this.$refs.refund.modals = true;
          this.orderId = row.id;
          this.status = row._status;
          this.pay_type = row.pay_type;
          break;
        // case '6':
        //   this.getRefundIntegral(row.id);
        //   break;
        // case '7':
        //   this.getNoRefundData(row.id);
        //   break;
        case '8':
          this.delfromData = {
            title: this.$t('message.orderList.modifyConfirmReceive'),
            url: `/order/take/${row.id}`,
            method: 'put',
            ids: '',
          };
          this.$modalSure(this.delfromData)
            .then((res) => {
              this.$message.success(res.msg);
              this.getList();
            })
            .catch((res) => {
              this.$message.error(res.msg);
            });
          // this.modalTitleSs = '修改确认收货';
          break;
        case '10':
          this.delfromData = {
            title: this.$t('message.orderList.printOrder'),
            url: `/order/print/${row.id}`,
            method: 'get',
            ids: '',
          };
          this.$modalSure(this.delfromData)
            .then((res) => {
              this.$message.success(res.msg);
              this.$emit('changeGetTabs');
              this.getList();
            })
            .catch((res) => {
              this.$message.error(res.msg);
            });
          break;
        case '11':
          this.delfromData = {
            title: this.$t('message.orderList.printElectronic'),
            info: this.$t('message.orderList.printElectronicConfirm'),
            url: `/order/order_dump/${row.id}`,
            method: 'get',
            ids: '',
          };
          this.$modalSure(this.delfromData)
            .then((res) => {
              this.$message.success(res.msg);
              this.getList();
            })
            .catch((res) => {
              this.$message.error(res.msg);
            });
          break;
        case '12':
          this.printImg(row.kuaidi_label);
          break;
        case '13':
          let pathInfo = this.$router.resolve({
            path: Setting.routePre + '/order/print',
            query: {
              id: row.order_id,
            },
          });
          window.open(pathInfo.href, '_blank');
          break;
        default:
          this.delfromData = {
            title: this.$t('message.orderList.deleteOrder'),
            url: `/order/del/${row.id}`,
            method: 'DELETE',
            ids: '',
          };
          // this.modalTitleSs = '删除订单';
          this.delOrder(row, this.delfromData);
      }
    },
    shipmentClear(row) {
      this.orderId = row.id;
      this.$refs.shipment.modals = true;
    },
    printImg(url) {
      printJS({
        printable: url,
        type: 'image',
        documentTitle: this.$t('message.orderList.expressInfo'),
        style: `img{
          width: 100%;
          height: 476px;
        }`,
      });
    },
    // 立即支付 /确认收货//删除单条订单
    submitModel() {
      this.getList();
    },
    // 订单列表
    getList(res) {
      this.page.page = res === 1 ? 1 : this.page.page;
      this.loading = true;
      orderList({
        page: this.page.page,
        limit: this.page.limit,
        status: this.orderStatus,
        pay_type: this.orderPayType,
        data: this.orderTime,
        real_name: this.real_name,
        field_key: this.fieldKey,
        type: this.orderType === 0 ? '' : this.orderType,
      })
        .then(async (res) => {
          let data = res.data;
          this.orderList = data.data;
          this.orderCards = data.stat;
          this.total = data.count;
          this.$nextTick(() => {
            //确保dom加载完毕
            this.setChecked();
          });
          this.$emit('on-changeCards', data.stat);
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    handleSelectRow(selection) {
      let ids = [];
      selection.map((e) => {
        ids.push(e.id);
      });
      this.selectedIds = ids;
      this.$nextTick(() => {
        //确保dom加载完毕
        this.setChecked();
      });
    },
    setChecked() {
      //将new Set()转化为数组
      let ids = [...this.selectedIds];
      this.getisDelIdListl(ids);
      // 找到绑定的table的ref对应的dom，找到table的objData对象，objData保存的是当前页的数据
      let objData = this.$refs.table.objData;
      for (let index in objData) {
        if (this.selectedIds.has(objData[index].id)) {
          objData[index]._isChecked = true;
        }
      }
    },
    isDel(selection) {
      if (selection.findIndex((target) => target.is_del === 0) == -1) {
        this.getIsDel(1);
      } else {
        this.getIsDel(0);
      }
    },
    // 编辑
    edit(row) {
      this.getOrderData(row.id);
    },
    // 删除单条订单
    delOrder(row, data) {
      if (row.is_del === 1) {
        this.$modalSure(data)
          .then((res) => {
            this.$message.success(res.msg);
            this.getList();
          })
          .catch((res) => {
            this.$message.error(res.msg);
          });
      } else {
        this.$message.error(this.$t('message.orderList.cannotDelete'));
      }
    },
    // 获取编辑表单数据
    getOrderData(id) {
      getOrdeDatas(id)
        .then(async (res) => {
          this.FromData = res.data;
          this.$refs.edits.modals = true;
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 获取详情表单数据
    getData(id) {
      getDataInfo(id)
        .then(async (res) => {
          this.$refs.details.modals = true;
          this.orderDatalist = res.data;
          if (this.orderDatalist.orderInfo.refund_reason_wap_img) {
            try {
              this.orderDatalist.orderInfo.refund_reason_wap_img = JSON.parse(
                this.orderDatalist.orderInfo.refund_reason_wap_img,
              );
            } catch (e) {
              this.orderDatalist.orderInfo.refund_reason_wap_img = [];
            }
          }
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 修改成功
    submitFail() {
      this.getList();
      this.$emit('changeGetTabs');
    },
    // 获取退款表单数据
    getRefundData(id) {
      this.$modalForm(getRefundFrom(id)).then(() => {
        this.getList();
        this.$emit('changeGetTabs');
      });
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
    // 不退款表单数据
    getNoRefundData(id) {
      this.$modalForm(getnoRefund(id)).then(() => {
        this.getList();
        this.$emit('changeGetTabs');
      });
    },
    // 发送货
    sendOrder(row) {
      if (row.user_address) {
        this.$refs.send.userSendmsg = {
          real_name: row.real_name,
          user_address: row.user_address,
          user_phone: row.user_phone,
        };
      }
      this.$refs.send.total_num = row.total_num;
      this.virtual_type = row.virtual_type;
      this.$refs.send.modals = true;
      this.orderId = row.id;
      this.status = row._status;
      this.pay_type = row.pay_type;
      this.$refs.send.getList();
      this.$refs.send.getDeliveryList();
      this.$nextTick((e) => {
        this.$refs.send.getCartInfo(row._status, row.id);
      });
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
    // 核销订单
    bindWrite(row) {
      let self = this;
      this.$msgbox({
        title: this.$t('message.orderList.tip'),
        message: this.$t('message.orderList.writeoffConfirmMessage'),
        showCancelButton: true,
        cancelButtonText: this.$t('message.orderList.cancel'),
        confirmButtonText: this.$t('message.orderList.confirm'),
        iconClass: 'el-icon-warning',
        confirmButtonClass: 'btn-custom-cancel',
      })
        .then(() => {
          writeUpdate(row.order_id)
            .then((res) => {
              self.$message.success(res.msg);
              self.getList();
            })
            .catch((res) => {
              self.$message.error(res.msg);
            });
        })
        .catch(() => {});
    },
    // 订单类型  @on-changeTabs="getChangeTabs"
    getTabs() {
      this.spinShow = true;
      this.$store
        .dispatch('order/getOrderTabs', {
          data: '',
        })
        .then((res) => {
          this.tablists = res.data;
          // this.onChangeChart(this.tablists)
          this.spinShow = false;
        })
        .catch((res) => {
          this.spinShow = false;
          this.$message.error(res.msg);
        });
    },
    onClickTab() {
      this.getOrderStatus(this.currentTab == 'null' ? '' : this.currentTab);
      this.getList();
    },
    // 批量删除
    delAll() {
      if (this.delIdList.length === 0) {
        this.$message.error(this.$t('message.orderList.pleaseSelectOrder'));
      } else {
        if (this.isDels) {
          let idss = {
            ids: this.delIdList,
          };
          let delfromData = {
            title: this.$t('message.orderList.deleteOrder'),
            url: `/order/dels`,
            method: 'post',
            ids: idss,
          };
          this.$modalSure(delfromData)
            .then((res) => {
              this.$message.success(res.msg);
              this.getList();
            })
            .catch((res) => {
              this.$message.error(res.msg);
            });
        } else {
          this.$message.error(this.$t('message.orderList.cannotDelete'));
        }
      }
    },
    // 下载批量发货模版
    async exportDeliveryList() {
      let [th, filekey, data, fileName] = [[], [], [], ''];
      let deliveryData = { page: 1, limit: 200 };
      for (let i = 0; i < deliveryData.page + 1; i++) {
        let expressData = await this.getDeliveryData(deliveryData);
        if (!fileName) fileName = expressData.filename;
        if (!filekey.length) {
          filekey = expressData.fileKey;
        }
        if (!th.length) th = expressData.header;
        if (expressData.export.length) {
          data = data.concat(expressData.export);
          deliveryData.page++;
        } else {
          this.$exportExcel(th, filekey, fileName, data);
          return;
        }
      }
    },
    getDeliveryData(deliveryData) {
      return new Promise((resolve, reject) => {
        exportOrderDeliveryList(deliveryData).then((res) => {
          resolve(res.data);
        });
      });
    },
    // 上传头部token
    getToken() {
      this.header['Authori-zation'] = 'Bearer ' + getCookies('token');
    },
    upExpress(data) {
      importExpress({ file: data.data.src })
        .then((res) => {
          this.$message.success(res.msg);
          this.getList();
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 导出
    async exportList() {
      let excelData = {
          page: 1,
          limit: 100,
          status: this.orderStatus,
          pay_type: this.orderPayType,
          data: this.orderTime,
          real_name: this.real_name,
          field_key: this.fieldKey,
          type: this.orderType === 0 ? '' : this.orderType,
          ids: this.delIdList,
        },
        data = [],
        lebData = {};
      for (let i = 1; i < excelData.page + 1; i++) {
        lebData = await this.getExcelData(excelData);
        if (lebData.export.length) {
          data = data.concat(lebData.export);
          if (lebData.export.length == excelData.limit) excelData.page++;
        }
      }
      createWorkBook(lebData.header, lebData.filename, data, '', lebData.filename);
    },
    getExcelData(excelData) {
      return new Promise((resolve, reject) => {
        exportOrderList(excelData).then((res) => {
          resolve(res.data);
        });
      });
    },
    // 订单核销
    writeOff() {
      this.modals2 = true;
    },
    // 订单核销
    ok(name) {
      if (!this.writeOffFrom.code) {
        this.$message.warning(this.$t('message.orderList.pleaseVerifyOrder'));
      } else {
        this.writeOffFrom.confirm = 1;
        putWrite(this.writeOffFrom)
          .then(async (res) => {
            if (res.status === 200) {
              this.$message.success(res.msg);
              this.modals2 = false;
              this.$refs[name].resetFields();
              this.getList();
            } else {
              this.$message.error(res.msg);
            }
          })
          .catch((res) => {
            this.$message.error(res.msg);
          });
      }
    },
    del(name) {
      this.modals2 = false;
      this.writeOffFrom.code = '';
      this.$refs[name].resetFields();
    },
    changeModal() {
      this.writeOffFrom.code = '';
    },
  },
};
</script>

<style lang="scss" scoped>
::v-deep .el-upload,
::v-deep .el-upload-dragger {
  width: 100%;
}

::v-deep .el-upload-list {
  display: none;
}

::v-deep .el-tabs__item {
  height: 54px;
  line-height: 54px;
}

img {
  height: 36px;
  display: block;
}

.tabBox {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  margin-bottom: 2px;

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
    margin: 0 10px 0 10px;
    letter-spacing: 1px;
    padding: 5px 0;
    box-sizing: border-box;
  }
}

.orderData ::v-deep .ivu-table-cell {
  padding-left: 0 !important;
}

.vertical-center-modal {
  display: flex;
  align-items: center;
  justify-content: center;
}

.nickname {
}

.uid {
  color: #2d8cf0;
}

.pink_name {
  color: #666;
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

.tips {
  color: #c0c4cc;
  font-size: 12px;
}
</style>
