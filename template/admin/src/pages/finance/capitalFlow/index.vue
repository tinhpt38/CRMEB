<template>
  <div>
    <el-card :bordered="false" shadow="never" :body-style="{ padding: 0 }">
      <div class="padding-add">
        <el-form
          ref="formValidate"
          :model="formValidate"
          :label-width="labelWidth"
          label-position="right"
          inline
          @submit.native.prevent
        >
          <el-form-item label="Thời gian đặt hàng：">
            <el-date-picker
              clearable
              v-model="timeVal"
              type="daterange"
              :editable="false"
              @change="onchangeTime"
              format="yyyy/MM/dd"
              value-format="yyyy/MM/dd"
              start-placeholder="ngày bắt đầu"
              end-placeholder="ngày kết thúc"
              :picker-options="pickerOptions"
              style="width: 250px"
              class="mr20"
            ></el-date-picker>
          </el-form-item>
          <el-form-item label="Loại giao dịch：">
            <el-select v-model="formValidate.status" @change="selChange" class="form_content_width">
              <el-option :label="item" :value="index" v-for="(item, index) in withdrawal" :key="index"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="Đang chạy tìm kiếm：">
            <div class="acea-row row-middle">
              <el-input
                clearable
                placeholder="Mã đơn hàng/biệt hiệu/điện thoại/ID khách hàng"
                v-model="formValidate.keywords"
                class="form_content_width"
              />
            </div>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" v-db-click @click="selChange">Tìm kiếm</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <el-card :bordered="false" shadow="never" class="mt16">
      <el-table ref="table" :data="tabList" class="ivu-mt" v-loading="loading" empty-text="Chưa có dữ liệu">
        <el-table-column label="Số giao dịch" width="180">
          <template slot-scope="scope">
            <span>{{ scope.row.flow_id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Đơn hàng liên kết" min-width="120">
          <template slot-scope="scope">
            <span>{{ scope.row.order_id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Giờ giao dịch" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.add_time }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Số tiền giao dịch" min-width="90">
          <template slot-scope="scope">
            <div v-if="scope.row.price >= 0" class="z-price">+{{ scope.row.price }}</div>
            <div v-if="scope.row.price < 0" class="f-price">{{ scope.row.price }}</div>
          </template>
        </el-table-column>
        <el-table-column label="Người dùng giao dịch" min-width="90">
          <template slot-scope="scope">
            <span>{{ scope.row.nickname }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Phương thức thanh toán" min-width="90">
          <template slot-scope="scope">
            <div v-for="item in payment" :key="item.value">
              <span v-if="scope.row.pay_type == item.value"> {{ item.title }} </span>
            </div>
          </template>
        </el-table-column>
        <el-table-column label="Nhận xét" min-width="90">
          <template slot-scope="scope">
            <span>{{ scope.row.mark }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Thao tác" fixed="right" width="170">
          <template slot-scope="scope">
            <a v-db-click @click="setMark(scope.row)">Nhận xét</a>
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination
          v-if="total"
          :total="total"
          :page.sync="formValidate.page"
          :limit.sync="formValidate.limit"
          @pagination="getList"
        />
      </div>
    </el-card>
    <!-- từ chối vượt qua-->
    <el-dialog :visible.sync="modals" title="Nhận xét" :close-on-click-modal="false" width="540px">
      <el-input v-model="mark_msg.mark" type="textarea" :rows="4" placeholder="Vui lòng nhập nhận xét" />
      <span slot="footer" class="dialog-footer">
        <el-button type="primary" v-db-click @click.prevent="oks">Chắc chắn</el-button>
      </span>
    </el-dialog>
  </div>
</template>
<script>
import searchFrom from '@/components/publicSearchFrom';
import { mapState } from 'vuex';
import { getFlowList, cashEditApi, setMarks } from '@/api/finance';
import { formatDate } from '@/utils/validate';
export default {
  name: 'cashApply',
  components: { searchFrom },
  filters: {
    formatDate(time) {
      if (time !== 0) {
        let date = new Date(time * 1000);
        return formatDate(date, 'yyyy-MM-dd hh:mm');
      }
    },
  },
  data() {
    return {
      images: ['1.jpg', '2.jpg'],
      modal_loading: false,
      pickerOptions: this.$timeOptions,
      mark_msg: {
        mark: '',
      },
      modals: false,
      total: 0,
      loading: false,
      tabList: [],
      withdrawal: [],
      payment: [
        {
          title: 'Tất cả',
          value: '',
        },
        {
          title: 'WeChat',
          value: 'weixin',
        },
        {
          title: 'Alipay',
          value: 'alipay',
        },
        {
          title: 'thẻ ngân hàng',
          value: 'bank',
        },
        {
          title: 'Thanh toán ngoại tuyến',
          value: 'offline',
        },
      ],
      formValidate: {
        trading_type: 0,
        time: '',
        keywords: '',
        page: 1,
        limit: 20,
      },
      timeVal: [],
      FromData: null,
      extractId: 0,
    };
  },
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '80px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  mounted() {
    this.getList();
  },
  methods: {
    // Chắc chắn
    oks() {
      this.modal_loading = true;
      setMarks(this.extractId, this.mark_msg)
        .then((res) => {
          this.$message.success(res.msg);
          this.modal_loading = false;
          this.modals = false;
          this.getList();
        })
        .catch((err) => {
          this.modal_loading = false;
          this.$message.error(err.msg);
        });
    },
    // Nhận xét
    setMark(row) {
      this.modals = true;
      this.extractId = row.id;
      this.mark_msg.mark = row.mark;
    },
    // ngày cụ thể
    onchangeTime(e) {
      this.timeVal = e;
      this.formValidate.time = this.timeVal ? this.timeVal.join('-') : '';
      this.formValidate.page = 1;
      this.getList();
    },
    // chọn
    selChange(e) {
      this.formValidate.page = 1;
      this.getList();
    },
    // danh sách
    getList() {
      this.loading = true;
      getFlowList(this.formValidate)
        .then(async (res) => {
          let data = res.data;
          this.tabList = data.list;
          this.total = data.count;
          this.withdrawal = data.status;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    // Chỉnh sửa gửi thành công
    submitFail() {
      this.getList();
    },
  },
};
</script>
<style lang="scss" scoped>
.ivu-mt .type .item {
  margin: 3px 0;
}
.tabform {
}
.Refresh {
  font-size: 12px;
  color: var(--prev-color-primary);
  cursor: pointer;
}
.ivu-form-item {
  margin-bottom: 10px;
}
.status ::v-deep .item ~ .item {
  margin-left: 6px;
}
.status ::v-deep .statusVal {
  margin-bottom: 7px;
}

/* .ivu-mt ::v-deep .ivu-table-header */
/* border-top:1px dashed #ddd!important */
.type {
  padding: 3px 0;
  box-sizing: border-box;
}
.tabBox_img {
  width: 36px;
  height: 36px;
  border-radius: 4px;
  cursor: pointer;

  img {
    width: 100%;
    height: 100%;
  }
}
.z-price {
  color: red;
}
.f-price {
  color: green;
}
</style>
