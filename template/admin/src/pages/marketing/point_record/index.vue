<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt" :body-style="{ padding: 0 }">
      <div class="padding-add">
        <el-form
          ref="formValidate"
          :model="formValidate"
          :label-width="labelWidth"
          :label-position="labelPosition"
          @submit.native.prevent
          inline
        >
          <el-form-item label="Thời gian đặt hàng：">
            <el-date-picker
              clearable
              v-model="timeVal"
              type="daterange"
              :editable="false"
              @change="onchangeTime"
              format="dd/MM/yyyy"
              value-format="yyyy-MM-dd"
              start-placeholder="ngày bắt đầu"
              end-placeholder="ngày kết thúc"
              :picker-options="pickerOptions"
              style="width: 250px"
              class="mr20"
            ></el-date-picker>
          </el-form-item>
          <el-form-item label="Loại giao dịch：">
            <el-select
              type="button"
              v-model="formValidate.trading_type"
              @change="selChange"
              class="form_content_width"
              clearable
            >
              <el-option
                :label="item"
                :value="Object.keys(withdrawal)[index]"
                v-for="(item, index) in Object.values(withdrawal)"
                :key="index"
              ></el-option>
            </el-select>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <el-card :bordered="false" shadow="never" class="mt16">
      <el-table ref="table" :data="tabList" class="ivu-mt" v-loading="loading" empty-text="Chưa có dữ liệu">
        <el-table-column label="ID" min-width="70">
          <template slot-scope="scope">
            <div>{{ scope.row.id }}</div>
          </template>
        </el-table-column>
        <el-table-column label="Đơn hàng liên kết" min-width="100">
          <template slot-scope="scope">
            <div>{{ scope.row.relation }}</div>
          </template>
        </el-table-column>
        <el-table-column label="Giờ giao dịch" min-width="100">
          <template slot-scope="scope">
            <div>{{ scope.row.add_time }}</div>
          </template>
        </el-table-column>
        <el-table-column label="Điểm giao dịch" min-width="80">
          <template slot-scope="scope">
            <div v-if="scope.row.pm" class="z-price">+ {{ scope.row.number }}</div>
            <div v-else class="f-price">- {{ scope.row.number }}</div>
          </template>
        </el-table-column>
        <el-table-column label="Người dùng" min-width="80">
          <template slot-scope="scope">
            <div>{{ scope.row.nickname }}</div>
          </template>
        </el-table-column>
        <el-table-column label="Loại giao dịch" min-width="100">
          <template slot-scope="scope">
            <div>{{ scope.row.type_name }}</div>
          </template>
        </el-table-column>
        <el-table-column label="Nhận xét" min-width="100">
          <template slot-scope="scope">
            <div>{{ scope.row.mark }}</div>
          </template>
        </el-table-column>
        <el-table-column label="Nhận xét" width="100">
          <template slot-scope="scope">
            <a class="item" v-db-click @click="setMark(scope.row)">Sửa đổi nhận xét</a>
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
    <el-dialog :visible.sync="modals" title="Nhận xét" :close-on-click-modal="false" width="470px">
      <el-input v-model="mark_msg.mark" type="textarea" :rows="4" placeholder="Vui lòng nhập nhận xét" />
      <div slot="footer">
        <el-button type="primary" size="small" v-db-click @click="oks">Chắc chắn</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
import { mapState } from 'vuex';
import { pointRecordList, setPointRecordMark } from '@/api/marketing';
import { formatDate } from '@/utils/validate';
import dateRadio from '@/components/dateRadio';
export default {
  name: 'cashApply',
  components: { dateRadio },
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
      selectIndexTime: '',
      payment: [
        {
          title: 'Tất cả',
          value: '',
        },
        {
          title: 'Thanh toán online',
          value: 'weixin',
        },
        // {                                    // hidden — China
        //   title: 'Alipay',
        //   value: 'alipay',
        // },
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
        trading_type: '',
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
      this.mark_msg.mark = this.mark_msg.mark.trim();
      setPointRecordMark(this.extractId, this.mark_msg)
        .then(async (res) => {
          this.$message.success(res.msg);
          this.modal_loading = false;
          this.modals = false;
          this.getList();
        })
        .catch((res) => {
          this.modal_loading = false;
          this.$message.error(res.msg);
        });
    },
    // Nhận xét
    setMark(row) {
      this.modals = true;
      this.extractId = row.id;
      this.mark_msg.mark = row.mark;
    },
    onSelectDate(e) {
      this.formValidate.time = e;
      this.formValidate.page = 1;
      this.getList();
    },
    dateToMs(date) {
      let result = new Date(date).getTime();
      return result;
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
      this.formValidate.trading_type = e;
      this.getList();
    },
    // danh sách
    getList() {
      this.loading = true;
      pointRecordList(this.formValidate)
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
