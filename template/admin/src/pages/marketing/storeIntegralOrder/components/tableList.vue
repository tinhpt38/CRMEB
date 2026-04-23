<template>
  <div>
    <el-table
      :data="orderList"
      ref="table"
      v-loading="loading"
      highlight-current-row
      empty-text="Chưa có dữ liệu"
      @select="selectAll"
      @select-all="selectAll"
      class="orderData"
    >
      <!-- <el-table-column type="selection" width="55"> </el-table-column> -->
      <el-table-column label="Số đơn hàng" min-width="150">
        <template slot-scope="scope">
          <span v-text="scope.row.order_id" style="display: block"></span>
          <span v-if="scope.row.is_del == 1" style="color: #ed4014; display: block">Người dùng đã bị xóa</span>
        </template>
      </el-table-column>
      <el-table-column label="Thông tin người dùng" min-width="100">
        <template slot-scope="scope"> {{ scope.row.nickname }}/{{ scope.row.uid }} </template>
      </el-table-column>
      <el-table-column label="Thông tin sản phẩm" min-width="330">
        <template slot-scope="scope">
          <div class="tabBox">
            <div class="tabBox_img" v-viewer>
              <img v-lazy="scope.row.image" />
            </div>
            <span class="tabBox_tit"> {{ scope.row.store_name + ' | ' }}{{ scope.row.suk ? scope.row.suk : '' }} </span>
            <span class="tabBox_pice">{{ 'tích phân' + scope.row.total_price + ' x ' + scope.row.total_num }}</span>
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Đổi điểm" min-width="100">
        <template slot-scope="scope">
          <span>{{ scope.row.total_price }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Trạng thái đơn hàng" min-width="100">
        <template slot-scope="scope">
          <span>{{ scope.row.status_name }}</span>
        </template>
      </el-table-column>
      <el-table-column label="thời gian đặt hàng" min-width="100">
        <template slot-scope="scope">
          <span>{{ scope.row.add_time }}</span>
        </template>
      </el-table-column>
      <el-table-column label="vận hành" fixed="right" width="150">
        <template slot-scope="scope">
          <a v-db-click @click="sendOrder(scope.row)" v-if="scope.row.status === 1">Gửi hàng</a>
          <a v-db-click @click="delivery(scope.row)" v-if="scope.row.status === 2">Thông tin vận chuyển</a>
          <el-divider direction="vertical" v-if="scope.row.status === 1 || scope.row.status === 2" />
          <template>
            <el-dropdown size="small" @command="changeMenu(scope.row, $event)" :transfer="true">
              <span class="el-dropdown-link">Hơn<i class="el-icon-arrow-down el-icon--right"></i> </span>

              <el-dropdown-menu slot="dropdown">
                <el-dropdown-item command="2">Chi tiết đặt hàng</el-dropdown-item>
                <el-dropdown-item command="3">Hồ sơ đặt hàng</el-dropdown-item>
                <el-dropdown-item command="11" v-show="scope.row.status >= 1 && scope.row.express_dump"
                  >In biểu mẫu điện tử</el-dropdown-item
                >
                <!-- <el-dropdown-item command="10" v-show="scope.row.status >= 1">In biên lai</el-dropdown-item> -->
                <!-- <el-dropdown-item name="10" v-show="scope.row._status >= 2">In đơn hàng</el-dropdown-item> -->
                <el-dropdown-item command="4" v-show="scope.row.status !== 4">Ghi chú đặt hàng</el-dropdown-item>
                <el-dropdown-item command="8" v-show="scope.row.status === 2">Hàng đã nhận</el-dropdown-item>
                <el-dropdown-item command="9" v-show="scope.row.is_del === 1">Xóa đơn hàng</el-dropdown-item>
              </el-dropdown-menu>
            </el-dropdown>
          </template>
        </template>
      </el-table-column>
    </el-table>
    <div class="acea-row row-right page">
      <pagination
        v-if="total"
        :total="total"
        :page.sync="page.pageNum"
        :limit.sync="page.pageSize"
        @pagination="getList"
      />
    </div>
    <!-- Chỉnh sửa Hoàn tiền Điểm hoàn tiền Không hoàn tiền-->
    <edit-from ref="edits" :FromData="FromData" @submitFail="submitFail"></edit-from>
    <!-- Chi tiết thành viên-->
    <user-details ref="userDetails"></user-details>
    <!-- Chi tiết -->
    <details-from ref="detailss" :orderDatalist="orderDatalist" :orderId="orderId"></details-from>
    <!-- Nhận xét -->
    <order-remark ref="remarks" :orderId="orderId" @submitFail="submitFail"></order-remark>
    <!-- Ghi -->
    <order-record ref="record"></order-record>
    <!-- Gửi hàng -->
    <order-send ref="send" :orderId="orderId" @submitFail="submitFail"></order-send>
  </div>
</template>

<script>
import expandRow from './tableExpand.vue';
import {
  orderList,
  getOrdeDatas,
  getDataInfo,
  getRefundFrom,
  getnoRefund,
  refundIntegral,
  getDistribution,
  writeUpdate,
} from '@/api/order';
import { getIntegralOrderDataInfo, integralOrderList, getIntegralOrderDistribution } from '@/api/marketing';
import { mapState, mapMutations } from 'vuex';
import editFrom from '../../../../components/from/from';
import detailsFrom from '../handle/orderDetails';
import orderRemark from '../handle/orderRemark';
import orderRecord from '../handle/orderRecord';
import orderSend from '../handle/orderSend';
import userDetails from '@/pages/user/list/handle/userDetails';

export default {
  name: 'table_list',
  components: {
    expandRow,
    editFrom,
    detailsFrom,
    orderRemark,
    orderRecord,
    orderSend,
    userDetails,
  },
  props: ['where', 'isAll'],
  data() {
    return {
      delfromData: {},
      modal: false,
      orderList: [],
      orderCards: [],
      loading: false,
      orderId: 0,
      total: 0, // Tổng số mặt hàng
      page: {
        pageNum: 1, // Trang hiện tại
        pageSize: 10, // Số mục được hiển thị trên mỗi trang
      },
      data: [],
      FromData: null,
      orderDatalist: null,
      modalTitleSs: '',
      isDelIdList: [],
      checkBox: false,
      formSelection: [],
      selectionCopy: [],
      display: 'none',
      autoDisabled: false,
      // isAll: -1,
    };
  },
  computed: {
    ...mapState('integralOrder', ['orderPayType', 'orderStatus', 'orderTime', 'orderNum', 'fieldKey', 'orderType']),
  },
  mounted() {
    this.getList();
  },
  activated() {
    this.getList();
  },
  watch: {
    orderType: function () {
      this.page.pageNum = 1;
      this.getList();
    },
    formSelection(value) {
      this.$emit('order-select', value);
      if (value.length) {
        this.$emit('auto-disabled', 0);
      } else {
        this.$emit('auto-disabled', 1);
      }
      let isDel = value.some((item) => {
        return item.is_del === 1;
      });
      this.getIsDel(isDel);
      this.getisDelIdListl(value);
    },
    orderList: {
      deep: true,
      handler(value) {
        value.forEach((item) => {
          this.formSelection.forEach((itm) => {
            if (itm.id === item.id) {
              item.checkBox = true;
            }
          });
        });
        const arr = this.orderList.filter((item) => item.checkBox);
        if (this.orderList.length) {
          this.checkBox = this.orderList.length === arr.length;
        } else {
          this.checkBox = false;
        }
      },
    },
  },
  methods: {
    ...mapMutations('integralOrder', ['getIsDel', 'getisDelIdListl']),
    selectAll(row) {
      if (row.length) {
        this.formSelection = row;
        this.selectionCopy = row;
      }
      this.selectionCopy.forEach((item, index) => {
        item.checkBox = this.checkBox;
        this.$set(this.orderList, index, item);
      });
    },
    showUserInfo(row) {
      this.$refs.userDetails.modals = true;
      this.$refs.userDetails.getDetails(row.uid);
    },
    // vận hành
    changeMenu(row, name) {
      this.orderId = row.id;
      switch (name) {
        case '2':
          this.getData(row.id);
          break;
        case '3':
          this.$refs.record.modals = true;
          this.$refs.record.getList(row.id);
          break;
        case '4':
          this.$refs.remarks.modals = true;
          this.$refs.remarks.formValidate.remark = row.remark;
          break;
        case '5':
          this.getRefundData(row.id);
          break;
        case '6':
          this.getRefundIntegral(row.id);
          break;
        case '7':
          this.getNoRefundData(row.id);
          break;
        case '8':
          this.delfromData = {
            title: 'Sửa đổi xác nhận đã nhận',
            url: `marketing/integral/order/take/${row.id}`,
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
            info: 'Bạn có chắc chắn in đơn hàng này không??',
            url: `marketing/integral/order/print/${row.id}`,
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
        default:
          this.delfromData = {
            title: 'Xóa đơn hàng',
            url: `marketing/integral/order/del/${row.id}`,
            method: 'DELETE',
            ids: '',
          };
          // this.modalTitleSs = 'Xóa đơn hàng';
          this.delOrder(row, this.delfromData);
      }
    },
    // Thanh toán ngay/Xác nhận đã nhận//Xóa đơn hàng
    submitModel() {
      this.getList();
    },
    // danh sách đặt hàng
    getList(res) {
      this.page.pageNum = res === 1 ? 1 : this.page.pageNum;
      this.loading = true;
      integralOrderList({
        page: this.page.pageNum,
        limit: this.page.pageSize,
        status: this.orderStatus,
        pay_type: this.orderPayType,
        data: this.orderTime,
        real_name: this.orderNum,
        field_key: this.fieldKey,
        type: this.orderType === 0 ? '' : this.orderType,
        product_id: this.$route.query.product_id,
      })
        .then(async (res) => {
          let data = res.data;
          // this.orderList = data.data;
          this.orderList = data.data.map((item) => {
            // item.checkBox = false;
            if (this.isAll === 1) {
              item.checkBox = true;
            } else {
              item.checkBox = false;
            }
            return item;
          });
          this.orderCards = data.stat;
          this.total = data.count;
          this.$emit('on-changeCards', data.stat);
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    // Chọn tất cả
    onSelectTab(selection) {
      this.formSelection = selection;
      let isDel = selection.some((item) => {
        return item.is_del === 1;
      });
      this.getIsDel(isDel);
      this.getisDelIdListl(selection);
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
          if (res.data.status === false) {
            return this.$authLapse(res.data);
          }
          this.$authLapse(res.data);
          this.FromData = res.data;
          this.$refs.edits.modals = true;
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // Nhận dữ liệu biểu mẫu chi tiết
    getData(id) {
      getIntegralOrderDataInfo(id)
        .then(async (res) => {
          this.$refs.detailss.modals = true;
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
    // Sửa đổi thành công
    submitFail() {
      this.$emit('updata');
      this.getList();
    },
    // Gửi hàng
    sendOrder(row) {
      this.$refs.send.modals = true;
      this.$refs.send.getList();
      this.$refs.send.getDeliveryList();
      // this.$refs.send.getSheetInfo();
      this.orderId = row.id;
    },
    // Dữ liệu biểu mẫu thông tin vận chuyển
    delivery(row) {
      getIntegralOrderDistribution(row.id)
        .then(async (res) => {
          this.FromData = res.data;
          this.$refs.edits.modals = true;
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    change(status) {},
    // Xuất dữ liệu；
    exportData: function () {
      this.$refs.table.exportCsv({
        filename: 'Danh sách sản phẩm',
      });
    },
    onSelectCancel(selection, row) {},
  },
};
</script>

<style lang="scss" scoped>
img {
  height: 36px;
  display: block;
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
.orderData ::v-deep .ivu-table-cell {
  padding-left: 0 !important;
}
.vertical-center-modal {
  display: flex;
  align-items: center;
  justify-content: center;
}
.orderData .ivu-table {
  overflow: visible !important;
}
.orderData .ivu-table th {
  overflow: visible !important;
}
.orderData .ivu-table-header {
  overflow: visible !important;
}
::v-deep .ivu-table-header {
}
::v-deep .ivu-table th {
  overflow: visible;
}
::v-deep .select-item:hover {
  background-color: #f3f3f3;
}
::v-deep .select-on {
  display: block;
}
::v-deep .select-item.on {
  background: #f3f3f3;
}
</style>
