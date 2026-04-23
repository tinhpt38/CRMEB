<template>
  <el-dialog :visible.sync="modal" title="danh sách nhiệm vụ" width="1000px">
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <el-form
        ref="formValidate"
        :model="formValidate"
        :label-width="labelWidth"
        :label-position="labelPosition"
        class="tabform"
        @submit.native.prevent
      >
        <el-row :gutter="24">
          <el-col span="10">
            <el-form-item label="Thời gian hoạt động：">
              <el-date-picker
                clearable
                :editable="false"
                @change="onchangeTime"
                v-model="timeVal"
                format="yyyy/MM/dd"
                type="datetimerange"
                value-format="yyyy/MM/dd"
                range-separator="-"
                start-placeholder="ngày bắt đầu"
                end-placeholder="ngày kết thúc"
                style="width: 90%"
                :options="options"
              ></el-date-picker>
            </el-form-item>
          </el-col>
          <el-col :span="7">
            <el-form-item label="kiểu：">
              <el-select v-model="formValidate.type" clearable @change="typeSearchs">
                <el-option
                  v-for="item in typeList"
                  :value="item.value"
                  :key="item.value"
                  :label="item.label"
                ></el-option>
              </el-select>
            </el-form-item>
          </el-col>
          <el-col :span="7">
            <el-form-item label="tình trạng：">
              <el-select v-model="formValidate.status" clearable @change="statusSearchs">
                <el-option
                  v-for="item in statusList"
                  :value="item.value"
                  :key="item.value"
                  :label="item.label"
                ></el-option>
              </el-select>
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
      <el-table class="mt14" height="530" :data="data1" v-loading="loading">
        <el-table-column label="ID" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Thời gian hoạt động" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.add_time }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Số lượng lô hàng" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.total_num }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Số lô hàng thành công" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.success_num }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Loại vận chuyển" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.title }}</span>
          </template>
        </el-table-column>
        <el-table-column label="tình trạng" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.status_cn }}</span>
          </template>
        </el-table-column>
        <el-table-column label="vận hành" fixed="right" width="170">
          <template slot-scope="scope">
            <template v-if="scope.row.is_show_log">
              <a v-db-click @click="deliveryLook(scope.row)">Kiểm tra</a>
              <el-divider direction="vertical"></el-divider>
            </template>
            <template>
              <el-dropdown size="small" @command="changeMenu(scope.row, $event)">
                <span class="el-dropdown-link">Hơn<i class="el-icon-arrow-down el-icon--right"></i> </span>
                <el-dropdown-menu slot="dropdown">
                  <el-dropdown-item command="1">tải về</el-dropdown-item>
                  <el-dropdown-item command="2">Thực hiện lại</el-dropdown-item>
                  <el-dropdown-item v-if="scope.row.is_stop_button" command="3">Dừng tác vụ</el-dropdown-item>
                  <el-dropdown-item v-if="scope.row.is_error_button" command="4">Xóa nhiệm vụ ngoại lệ</el-dropdown-item>
                </el-dropdown-menu>
              </el-dropdown>
            </template>
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination
          v-if="page1.total"
          :total="page1.total"
          :page.sync="page1.pageNum"
          :limit.sync="page1.pageSize"
          @pagination="getQueue"
        />
      </div>
    </el-card>
    <el-dialog :visible.sync="modal1" width="1000px">
      <el-table height="500" class="mt14" :data="data2" v-loading="loading2">
        <el-table-column
          :label="item.title"
          :min-width="item.minWidth || 100"
          v-for="(item, index) in columns4"
          :key="index"
        >
          <template slot-scope="scope">
            <template v-if="item.key">
              <div>
                <span>{{ scope.row[item.key] }}</span>
              </div>
            </template>
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination
          v-if="page2.total"
          :total="page2.total"
          :page.sync="page2.pageNum"
          :limit.sync="page2.pageSize"
          @pagination="getDeliveryLog"
        />
      </div>
    </el-dialog>
    <!-- </div> -->
  </el-dialog>
</template>

<script>
import { queueIndex, deliveryLog, queueAgain, queueDel, batchOrderDelivery, stopWrongQueue } from '@/api/order';
import { mapState } from 'vuex';

export default {
  data() {
    return {
      modal: false,
      data1: [],
      page1: {
        total: 0, // Tổng số mặt hàng
        pageNum: 1, // Trang hiện tại
        pageSize: 10, // Số mục được hiển thị trên mỗi trang
      },
      formValidate: {
        type: '',
        status: '',
        data: '',
      },
      options: {
        shortcuts: [
          {
            text: 'Hôm nay',
            value() {
              const end = new Date();
              const start = new Date();
              start.setTime(new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate()));
              return [start, end];
            },
          },
          {
            text: 'Hôm qua',
            value() {
              const end = new Date();
              const start = new Date();
              start.setTime(
                start.setTime(new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate() - 1)),
              );
              end.setTime(
                end.setTime(new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate() - 1)),
              );
              return [start, end];
            },
          },
          {
            text: '7 ngày qua',
            value() {
              const end = new Date();
              const start = new Date();
              start.setTime(
                start.setTime(new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate() - 6)),
              );
              return [start, end];
            },
          },
          {
            text: '30 ngày qua',
            value() {
              const end = new Date();
              const start = new Date();
              start.setTime(
                start.setTime(new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate() - 29)),
              );
              return [start, end];
            },
          },
          {
            text: 'tháng này',
            value() {
              const end = new Date();
              const start = new Date();
              start.setTime(start.setTime(new Date(new Date().getFullYear(), new Date().getMonth(), 1)));
              return [start, end];
            },
          },
          {
            text: 'năm nay',
            value() {
              const end = new Date();
              const start = new Date();
              start.setTime(start.setTime(new Date(new Date().getFullYear(), 0, 1)));
              return [start, end];
            },
          },
        ],
      },
      timeVal: [],
      typeList: [
        // {
        //     label: 'Phát hành phiếu giảm giá cho người dùng theo đợt',
        //     value: '1'
        // },
        // {
        //     label: 'Đặt nhóm người dùng theo đợt',
        //     value: '2'
        // },
        // {
        //     label: 'Đặt nhãn người dùng theo lô',
        //     value: '3'
        // },
        // {
        //     label: 'Loại bỏ sản phẩm theo lô',
        //     value: '4'
        // },
        // {
        //     label: 'Xóa thông số sản phẩm theo lô',
        //     value: '5'
        // },
        {
          label: 'Xóa đơn hàng theo đợt',
          value: '6',
        },
        {
          label: 'Giao hàng thủ công theo lô',
          value: '7',
        },
        {
          label: 'In các biểu mẫu điện tử theo lô',
          value: '8',
        },
        {
          label: 'Giao hàng số lượng lớn',
          value: '9',
        },
        {
          label: 'Lô hàng ảo hàng loạt',
          value: '10',
        },
      ],
      statusList: [
        {
          label: 'Chưa được xử lý',
          value: '0',
        },
        {
          label: 'Xử lý',
          value: '1',
        },
        {
          label: 'Hoàn thành',
          value: '2',
        },
        {
          label: 'Xử lý không thành công',
          value: '3',
        },
      ],
      columns2: [
        {
          title: 'Đặt hàngID',
          key: 'order_id',
        },
        {
          title: 'Công ty hậu cần',
          key: 'delivery_name',
        },
        {
          title: 'Số đơn hàng hậu cần',
          key: 'delivery_id',
        },
        {
          title: 'Trạng thái xử lý',
          key: 'status_cn',
        },
        {
          title: 'Nguyên nhân bất thường',
          key: 'error',
        },
      ],
      columns3: [
        {
          title: 'Đặt hàngID',
          key: 'order_id',
        },
        {
          title: 'Nhận xét',
          key: 'fictitious_content',
        },
        {
          title: 'Trạng thái xử lý',
          key: 'status_cn',
        },
        {
          title: 'Nguyên nhân bất thường',
          key: 'error',
        },
      ],
      columns5: [
        {
          title: 'Đặt hàngID',
          key: 'order_id',
        },
        {
          title: 'người giao hàng',
          key: 'delivery_name',
        },
        {
          title: 'Số điện thoại người giao hàng',
          key: 'delivery_id',
        },
        {
          title: 'Trạng thái xử lý',
          key: 'status_cn',
        },
        {
          title: 'Nguyên nhân bất thường',
          key: 'error',
        },
      ],
      columns4: [],
      data2: [],
      page2: {
        total: 0, // Tổng số mặt hàng
        pageNum: 1, // Trang hiện tại
        pageSize: 12, // Số mục được hiển thị trên mỗi trang
      },
      modal1: false,
      deliveryLog: null,
      deliveryLogId: 0,
      deliveryLogType: '',
      loading: false,
      loading2: false,
    };
  },
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '75px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  created() {
    this.getQueue();
  },
  methods: {
    getQueue() {
      let data = {
        page: this.page1.pageNum,
        limit: this.page1.pageSize,
      };
      if (this.formValidate.status) {
        data.status = this.formValidate.status;
      }
      if (this.formValidate.type) {
        data.type = this.formValidate.type;
      }
      if (this.formValidate.data) {
        data.data = this.formValidate.data;
      }
      this.loading = true;
      queueIndex(data)
        .then((res) => {
          this.loading = false;
          this.data1 = res.data.list;
          this.page1.total = res.data.count;
        })
        .catch((err) => {
          this.loading = false;
        });
    },
    // Thời gian thao tác tìm kiếm
    onchangeTime(time) {
      this.timeVal = time || [];
      this.formValidate.data = this.timeVal[0] ? (this.timeVal ? this.timeVal.join('-') : '') : '';
      this.page1.pageNum = 1;
      this.getQueue();
    },
    // Loại tìm kiếm
    typeSearchs() {
      this.page1.pageNum = 1;
      this.getQueue();
    },
    // Trạng thái tìm kiếm
    statusSearchs() {
      this.page1.pageNum = 1;
      this.getQueue();
    },
    // Xem-lấy dữ liệu
    getDeliveryLog() {
      this.loading2 = true;
      deliveryLog(this.deliveryLogId, this.deliveryLogType, {
        page: this.page2.pageNum,
        limit: this.page2.pageSize,
      })
        .then((res) => {
          this.loading2 = false;
          this.data2 = res.data.list;
          this.page2.total = res.data.count;
        })
        .catch((err) => {
          this.loading2 = false;
        });
    },
    // Kiểm tra
    deliveryLook(row) {
      this.modal1 = true;
      this.deliveryLogId = row.id;
      this.deliveryLogType = row.cache_type;
      this.deliveryLog = row;
      switch (row.type) {
        case 7:
        case 8:
          this.columns4 = this.columns2;
          break;
        case 9:
          this.columns4 = this.columns5;
          break;
        case 10:
          this.columns4 = this.columns3;
          break;
      }
      this.getDeliveryLog();
    },
    // Hơn
    changeMenu(row, $event) {
      switch ($event) {
        // tải về
        case '1':
          batchOrderDelivery(row.id, row.type, row.cache_type)
            .then((res) => {
              window.open(res.data[0]);
            })
            .catch((err) => {
              this.$message.error(err.msg);
            });
          break;
        // Thực hiện lại
        case '2':
          this.queueAgain(row.id, row.type);
          break;
        // Dừng tác vụ
        case '3':
          this.$msgbox({
            title: 'Tiến hành thận trọng',
            message: 'Xác nhận dừng tác vụ？',
            showCancelButton: true,
            cancelButtonText: 'Hủy bỏ',
            confirmButtonText: 'Chắc chắn',
            iconClass: 'el-icon-warning',
            confirmButtonClass: 'btn-custom-cancel',
          })
            .then(() => {
              this.stopQueue(row.id);
            })
            .catch(() => {});
          break;
        // Xóa nhiệm vụ ngoại lệ
        case '4':
          this.queueDel(row.id, row.type);
          break;
      }
    },
    // Thực hiện lại
    queueAgain(id, type) {
      queueAgain(id, type)
        .then((res) => {
          this.$message.success(res.msg);
          this.getQueue();
        })
        .catch((err) => {
          this.$message.error(err.msg);
        });
    },
    // Xóa nhiệm vụ ngoại lệ
    queueDel(id, type) {
      queueDel(id, type)
        .then((res) => {
          this.$message.success(res.msg);
          this.getQueue();
        })
        .catch((err) => {
          this.$message.error(err.msg);
        });
    },
    // Dừng tác vụ
    stopQueue(id) {
      stopWrongQueue(id)
        .then((res) => {
          this.$message.success(res.msg);
          this.getQueue();
        })
        .catch((err) => {
          this.$message.error(err.msg);
        });
    },
  },
};
</script>

<style></style>
