<template>
  <div>
    <el-tabs v-model="currentTab" @tab-click="onClickTab" v-if="tablists" class="tabs-vi">
      <el-tab-pane name="null" label="Tất cả"></el-tab-pane>
      <el-tab-pane
        name="0"
        :label="orderChartType.un_paid > 0 ? `Đã thanh toán(${orderChartType.un_paid})` : `Đã thanh toán`"
      ></el-tab-pane>
      <el-tab-pane
        name="1"
        :label="orderChartType.un_send > 0 ? `Đang chờ vận chuyển(${orderChartType.un_send})` : `Đang chờ vận chuyển`"
      ></el-tab-pane>
      <el-tab-pane name="5" label="Chờ xử lý"></el-tab-pane>
      <el-tab-pane name="2" label="Đang chờ nhận"></el-tab-pane>
      <el-tab-pane name="3" label="Đang chờ đánh giá"></el-tab-pane>
      <el-tab-pane name="4" label="Hoàn thành"></el-tab-pane>
      <el-tab-pane name="-2" label="Đã hoàn tiền"></el-tab-pane>
      <el-tab-pane name="-4" label="Đã xóa"></el-tab-pane>
    </el-tabs>
    <div class="acea-row">
      <el-button v-auth="['order-write']" type="primary" v-db-click @click="writeOff">Xóa đơn hàng</el-button>
      <el-button v-db-click @click="batchShipmentModal = true">Giao hàng loạt</el-button>
      <!-- <el-upload class="mr14" :action="expressUrl" :headers="header" :on-success="upExpress">
        <el-button class="export" type="primary">Giao hàng loạt</el-button>
      </el-upload> -->
      <el-button v-auth="['order-dels']" v-db-click @click="delAll">Xóa hàng loạt</el-button>
      <el-button v-auth="['export-storeOrder']" class="export" v-db-click @click="exportList">Xuất đơn hàng</el-button>
      <!-- <el-button class="export" v-db-click @click="exportDeliveryList">Xuất hóa đơn</el-button> -->
    </div>
    <el-table
      :data="orderList"
      ref="table"
      v-loading="loading"
      empty-text="Chưa có dữ liệu"
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
      <el-table-column label="Số đơn hàng | kiểu" min-width="220" show-overflow-tooltip>
        <template slot-scope="scope">
          <div>{{ scope.row.order_id }}</div>
          <div class="pink_name" :style="{ color: scope.row.color }">{{ scope.row.pink_name }}</div>
          <span v-if="scope.row.is_del === 1" style="color: #ed4014; display: block">Người dùng đã bị xóa</span>
          <span v-if="scope.row.is_cancel === 1 && scope.row.is_del === 0" style="color: #ed4014; display: block"
            >Người dùng đã hủy</span
          >
          <span v-if="scope.row.refund_type === 6" style="color: #ed4014; display: block">Đơn hàng đã được hoàn lại</span>
        </template>
      </el-table-column>
      <el-table-column label="Thông tin sản phẩm" min-width="280">
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
                  <span>Tên sản phẩm：</span>
                  <span>{{ item.cart_info.productInfo.store_name || '--' }}</span>
                </div>
                <div>
                  <span>Tên đặc điểm kỹ thuật：</span>
                  <span>{{
                    item.cart_info.productInfo.attrInfo ? item.cart_info.productInfo.attrInfo.suk : '---'
                  }}</span>
                </div>
                <div>
                  <span>Trả giá：</span>
                  <span>¥{{ item.cart_info.truePrice || '--' }}</span>
                </div>
                <div>
                  <span>Số lượng mua：</span>
                  <span>{{ item.cart_info.cart_num || '--' }}</span>
                </div>
              </div>
              <span class="line2 w-250">{{ item.cart_info.productInfo.store_name }}</span>
            </el-tooltip>
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Thông tin người dùng" min-width="180" show-overflow-tooltip>
        <template slot-scope="scope">
          <span class="nickname">{{ scope.row.nickname }} | {{ scope.row.uid }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Thanh toán thực tế" min-width="100">
        <template slot-scope="scope">
          <span>{{ scope.row.paid ? scope.row.pay_price : 'Chưa thanh toán' }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Phương thức thanh toán" min-width="100">
        <template slot-scope="scope">
          <span>{{ scope.row.pay_type_name || '--' }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Thời gian thanh toán" min-width="150">
        <template slot-scope="scope">
          <span>{{ scope.row._pay_time || '--' }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Trạng thái đơn hàng" min-width="140">
        <template slot-scope="scope">
          <div v-html="scope.row.status_name.status_name" class="pt5"></div>
          <div v-if="!scope.row.is_all_refund && scope.row.refund.length" class="trip">Đang hoàn lại một phần</div>
          <div
            v-if="
              scope.row.refund_status == 0 &&
              scope.row.is_all_refund &&
              scope.row.refund.length &&
              scope.row.refund_type != 6
            "
            class="trip"
          >
            Đang hoàn tiền
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
      <el-table-column label="Thao tác" fixed="right" width="210">
        <template slot-scope="scope">
          <a v-db-click @click="changeMenu(scope.row, '2')">Chi tiết</a>
          <el-divider direction="vertical" />
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
            >Gửi hàng</a
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
            >Thông tin vận chuyển</a
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
            >Viết tắt ngay lập tức</a
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
              <span class="el-dropdown-link"> Thêm<i class="el-icon-arrow-down el-icon--right"></i> </span>
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
                  >Xác nhận thanh toán</el-dropdown-item
                >
                <el-dropdown-item v-show="scope.row._status === 1 && scope.row.is_del !== 1 && scope.row.is_cancel !== 1" command="15">Chỉnh sửa đơn hàng</el-dropdown-item>
                <el-dropdown-item command="11" v-show="scope.row._status >= 3 && scope.row.express_dump"
                  >In biểu mẫu điện tử</el-dropdown-item
                >
                <el-dropdown-item command="10" v-show="scope.row._status >= 2">In phiếu giao hàng</el-dropdown-item>
                <el-dropdown-item command="14" v-show="scope.row.status === 0">Sửa đổi địa chỉ</el-dropdown-item>
                <el-dropdown-item
                  command="4"
                  v-show="
                    scope.row._status !== 1 ||
                    (scope.row._status === 3 &&
                      scope.row.use_integral > 0 &&
                      scope.row.use_integral >= scope.row.back_integral)
                  "
                  >Ghi chú đơn hàng</el-dropdown-item
                >
                <el-dropdown-item
                  command="5"
                  v-show="scope.row.paid == 1 && scope.row.refund_status == 0 && !scope.row.refund.length"
                  >Hoàn tiền ngay lập tức</el-dropdown-item
                >
                <!--                            <el-dropdown-item command="6"  v-show='scope.row._status !==1 && (scope.row.use_integral > 0 && scope.row.use_integral >= scope.row.back_integral) '>Điểm hoàn tiền</el-dropdown-item>-->
                <!--                            <el-dropdown-item command="7"  v-show='scope.row._status === 3'>Không hoàn lại tiền</el-dropdown-item>-->
                <el-dropdown-item command="8" v-show="scope.row._status === 4">Hàng đã nhận</el-dropdown-item>
                <el-dropdown-item command="9">Xóa đơn hàng</el-dropdown-item>
                <el-dropdown-item command="12" v-show="scope.row.kuaidi_label">In mã vận đơn</el-dropdown-item>
                <el-dropdown-item command="13" v-show="scope.row.paid">In phiếu giao hàng</el-dropdown-item>
              </el-dropdown-menu>
            </el-dropdown>
          </template>
        </template>
      </el-table-column>
    </el-table>
    <div class="acea-row row-right page">
      <pagination v-if="total" :total="total" :page.sync="page.page" :limit.sync="page.limit" @pagination="getList" />
    </div>
    <!-- Chỉnh sửa Hoàn tiền Điểm hoàn tiền Không hoàn tiền-->
    <edit-from ref="edits" :FromData="FromData" @submitFail="submitFail"></edit-from>
    <!-- Chi tiết -->
    <details-from ref="details" :orderDatalist="orderDatalist" :orderId="orderId"></details-from>
    <!-- Nhận xét -->
    <order-remark ref="remarks" :orderId="orderId" @submitFail="submitFail"></order-remark>
    <!-- Hủy lô hàng -->
    <order-shipment ref="shipment" :orderId="orderId" @submitFail="submitFail"></order-shipment>
    <!-- Gửi hàng -->
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
      title="Xóa đơn hàng"
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
        <el-form-item prop="code" label="Mã xác nhận：">
          <el-input
            style="width: 414px"
            type="text"
            placeholder="Vui lòng nhập mã xác minh gồm 12 chữ số"
            v-model.number="writeOffFrom.code"
          />
        </el-form-item>
      </el-form>
      <div slot="footer">
        <el-button type="primary" v-db-click @click="ok('writeOffFrom')">Viết tắt ngay lập tức</el-button>
        <el-button v-db-click @click="del('writeOffFrom')">Hủy bỏ</el-button>
      </div>
    </el-dialog>
    <el-dialog
      :visible.sync="batchShipmentModal"
      title="Giao hàng loạt"
      class="paymentFooter"
      :show-close="true"
      width="540px"
      @closed="changeModal"
    >
      <!-- <el-upload :action="expressUrl" :headers="header" :on-success="upExpress">
        <el-button class="export" type="primary">Giao hàng loạt</el-button>
      </el-upload> -->
      <el-alert type="warning" :closable="false">
        <p>Bước 1: Xuất hóa đơn</p>
        <p>Bước 2: Điền mã đơn hàng logistics vào phiếu giao hàng</p>
        <p>Bước 3 Tải lên phiếu giao hàng</p>
      </el-alert>
      <div class="acea-row row-middle mb10 mt10">
        <el-button v-db-click @click="exportDeliveryList">Xuất hoá đơn</el-button>
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
        <div class="el-upload__text">Đơn hàng vận chuyển số lượng lớn,Kéo và thả để tải lên hoặc<em>Bấm để tải lên</em></div>
      </el-upload>
    </el-dialog>
    <orderAddress ref="address" :addressData="addressData" @submitSuccess="submitSuccess"></orderAddress>
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
  editAddress,
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
import orderAddress from '../handle/orderAddress.vue';
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
    orderAddress,
  },
  data() {
    const codeNum = (rule, value, callback) => {
      if (!value) {
        return callback(new Error('Vui lòng điền mã xác minh'));
      }
      // Mô phỏng hiệu ứng xác minh không đồng bộ
      if (!Number.isInteger(value)) {
        callback(new Error('Vui lòng điền 12 chữ số'));
      } else {
        const reg = /\b\d{12}\b/;
        if (!reg.test(value)) {
          callback(new Error('Vui lòng điền 12 chữ số'));
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

      total: 0, // Tổng số mặt hàng
      page: {
        page: 1, // Trang hiện tại
        limit: 15, // Số mục được hiển thị trên mỗi trang
      },
      data: [],
      FromData: null,
      orderDatalist: null,
      // modalTitleSs: '',
      selectedIds: [], //Các mục đã hợp nhất đã chọnid
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
      addressData: {},
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
    // vận hành
    changeMenu(row, name) {
      this.orderId = row.id;
      switch (name) {
        case '1':
          this.delfromData = {
            title: 'Sửa đổi đơn hàng như đã thanh toán',
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
          // this.modalTitleSs = 'Sửa đổi và thanh toán ngay';
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
            title: 'Sửa đổi xác nhận đã nhận',
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
          // this.modalTitleSs = 'Sửa đổi xác nhận đã nhận';
          break;
        case '10':
          this.delfromData = {
            title: 'In đơn đặt hàng của bạn bây giờ',
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
            title: 'In biểu mẫu điện tử ngay bây giờ',
            info: 'Bạn có chắc chắn in mẫu đơn điện tử này không??',
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
        case '14':
          this.addressData = {
            id: row.id,
            real_name: row.real_name,
            user_phone: row.user_phone,
            user_address: row.user_address,
          };
          this.$refs.address.modals = true;
          break;
        case '15':
          this.edit(row);
          break;  
        default:
          this.delfromData = {
            title: 'Xóa đơn hàng',
            url: `/order/del/${row.id}`,
            method: 'DELETE',
            ids: '',
          };
          // this.modalTitleSs = 'Xóa đơn hàng';
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
        documentTitle: 'Thể hiện thông tin',
        style: `img{
          width: 100%;
          height: 476px;
        }`,
      });
    },
    // Thanh toán ngay/Xác nhận đã nhận//Xóa đơn hàng
    submitModel() {
      this.getList();
    },
    // danh sách đặt hàng
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
            //Hãy chắc chắn rằng dom đã được tải
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
        //Hãy chắc chắn rằng dom đã được tải
        this.setChecked();
      });
    },
    setChecked() {
      //Sẽnew Set()Chuyển đổi thành mảng
      let ids = [...this.selectedIds];
      this.getisDelIdListl(ids);
      // Tìm DOM tương ứng với tham chiếu của bảng bị ràng buộc và tìm đối tượng objData của bảng. ObjData lưu dữ liệu của trang hiện tại.
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
    // biên tập
    edit(row) {
      this.getOrderData(row.id);
    },
    // Xóa một đơn hàng
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
        this.$message.error('Đơn hàng bạn chọn có đơn hàng chưa được người dùng xóa và đơn hàng chưa được người dùng xóa không thể xóa được.！');
      }
    },
    // Nhận dữ liệu biểu mẫu chỉnh sửa
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
    // Nhận dữ liệu biểu mẫu chi tiết
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
    submitSuccess() {
      editAddress(this.addressData).then(() => {
        this.$message.success('Sửa đổi thành công');
        this.addressData = {};
        this.$refs.address.modals = false;
      });
    },
    // Sửa đổi thành công
    submitFail() {
      this.getList();
      this.$emit('changeGetTabs');
    },
    // Nhận dữ liệu biểu mẫu hoàn tiền
    getRefundData(id) {
      this.$modalForm(getRefundFrom(id)).then(() => {
        this.getList();
        this.$emit('changeGetTabs');
      });
    },
    // Nhận dữ liệu biểu mẫu hoàn trả điểm
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
    // Không có dữ liệu biểu mẫu hoàn tiền
    getNoRefundData(id) {
      this.$modalForm(getnoRefund(id)).then(() => {
        this.getList();
        this.$emit('changeGetTabs');
      });
    },
    // Gửi hàng
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
    // Dữ liệu biểu mẫu thông tin vận chuyển
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
    // Xác nhận đơn hàng
    bindWrite(row) {
      let self = this;
      this.$msgbox({
        title: 'gợi ý',
        message: 'Bạn có chắc chắn muốn xóa đơn đặt hàng này không?？',
        showCancelButton: true,
        cancelButtonText: 'Hủy bỏ',
        confirmButtonText: 'Chắc chắn',
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
    // Loại đơn hàng  @on-changeTabs="getChangeTabs"
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
    // Xóa hàng loạt
    delAll() {
      if (this.delIdList.length === 0) {
        this.$message.error('Hãy chọn thứ tự xóa trước！');
      } else {
        if (this.isDels) {
          let idss = {
            ids: this.delIdList,
          };
          let delfromData = {
            title: 'Xóa đơn hàng',
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
          this.$message.error('Đơn hàng bạn chọn có đơn hàng chưa được người dùng xóa và đơn hàng chưa được người dùng xóa không thể xóa được.！');
        }
      }
    },
    // Tải xuống mẫu vận chuyển số lượng lớn
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
    // Tải tiêu đề lêntoken
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
    // Xuất khẩu
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
    // Xóa đơn hàng
    writeOff() {
      this.modals2 = true;
    },
    // Xóa đơn hàng
    ok(name) {
      if (!this.writeOffFrom.code) {
        this.$message.warning('Vui lòng xác minh đơn hàng trước！');
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

::v-deep .tabs-vi .el-tabs__item {
  height: 40px;
  line-height: 40px;
  white-space: nowrap;
  padding: 0 14px;
}
::v-deep .tabs-vi .el-tabs__nav-wrap::after {
  height: 1px;
}
::v-deep .tabs-vi .el-tabs__nav-scroll {
  overflow-x: auto;
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
