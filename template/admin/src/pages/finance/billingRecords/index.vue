<template>
  <div>
    <el-card :bordered="false" shadow="never" :body-style="{ padding: 0 }">
      <div class="padding-add">
        <el-form
          ref="formValidate"
          :model="formValidate"
          :label-width="labelWidth"
          :label-position="labelPosition"
          @submit.native.prevent
        >
          <el-form-item label="thời gian sáng tạo：">
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
        </el-form>
      </div>
    </el-card>
    <el-card :bordered="false" shadow="never" class="mt16" :body-style="{ padding: '0 20px 20px' }">
      <div class="ivu-mt">
        <el-tabs v-model="tab" @tab-click="onClickTab">
          <el-tab-pane label="hóa đơn hàng ngày" name="day" />
          <el-tab-pane label="Hóa đơn hàng tuần" name="week" />
          <el-tab-pane label="hóa đơn hàng tháng" name="month" />
        </el-tabs>
      </div>
      <div class="table">
        <el-table
          :data="orderList"
          ref="table"
          v-loading="loading"
          highlight-current-row
          no-userFrom-text="Chưa có dữ liệu"
          no-filtered-userFrom-text="Chưa có kết quả lọc nào"
        >
          <el-table-column label="ID" width="80">
            <template slot-scope="scope">
              <span>{{ scope.row.id }}</span>
            </template>
          </el-table-column>
          <el-table-column label="tiêu đề" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row.title }}</span>
            </template>
          </el-table-column>
          <el-table-column label="ngày" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row.add_time }}</span>
            </template>
          </el-table-column>
          <el-table-column label="Số tiền thu nhập" min-width="130">
            <template slot-scope="scope">
              <span style="color: #f5222d">￥{{ scope.row.income_price }}</span>
            </template>
          </el-table-column>
          <el-table-column label="Số tiền chi tiêu" min-width="130">
            <template slot-scope="scope">
              <span style="color: #00c050">￥{{ scope.row.exp_price }}</span>
            </template>
          </el-table-column>
          <el-table-column label="Số tiền đã nhập" min-width="130">
            <template slot-scope="scope">
              <span>￥{{ scope.row.entry_price }}</span>
            </template>
          </el-table-column>
          <el-table-column label="vận hành" fixed="right" width="170">
            <template slot-scope="scope">
              <a v-db-click @click="Info(scope.row)">Chi tiết hóa đơn</a>
              <el-divider direction="vertical"></el-divider>
              <a v-db-click @click="download(scope.row)">tải về</a>
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
      </div>
    </el-card>
    <el-dialog :visible.sync="modals" title="Chi tiết hóa đơn" width="1000px">
      <commission-details v-if="modals" ref="commission" :ids="ids" :time="formValidate.time"></commission-details>
    </el-dialog>
  </div>
</template>

<script>
import exportExcel from '@/utils/newToExcel.js';
import commissionDetails from '../components/commissionDetails';
import { getRecord } from '@/api/statistic.js';
import { getFlowList } from '@/api/finance';

export default {
  name: 'bill',
  components: {
    commissionDetails,
  },
  data() {
    return {
      modals: false,
      pickerOptions: this.$timeOptions,
      ids: '',
      total: 0,
      loading: false,
      tab: 'day',
      orderList: [
        {
          id: '1',
          order_id: '200',
          pay_price: '200',
          status: 1,
          phone: '13000000000',
          address: '100',
        },
      ],
      formValidate: {
        store_id: '',
        time: '',
        page: 1,
        limit: 15,
      },
      timeVal: [],
    };
  },
  computed: {
    labelWidth() {
      return this.isMobile ? undefined : '80px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  mounted() {
    this.onClickTab(this.tab);
  },
  methods: {
    onClickTab() {
      this.getList();
    },
    search() {
      this.getList();
    },
    getList() {
      this.loading = true;
      let data = {
        type: this.tab,
        time: this.formValidate.time,
        page: this.formValidate.page,
        limit: this.formValidate.limit,
        store_id: this.formValidate.store_id,
      };
      getRecord(data).then((res) => {
        this.orderList = res.data.list;
        this.loading = false;
        this.total = res.data.count;
      });
    },
    // Chọn thời gian
    selectChange(tab) {
      this.formValidate.page = 1;
      this.formValidate.time = tab;
      this.timeVal = [];
      this.getList();
    },
    // ngày cụ thể
    onchangeTime(e) {
      this.timeVal = e || [];
      this.formValidate.time = this.timeVal[0] ? (this.timeVal ? this.timeVal.join('-') : '') : '';
      this.formValidate.page = 1;
      this.getList();
    },
    // Chi tiết hóa đơn
    Info(row) {
      this.ids = row.ids || '';
      this.modals = true;
    },
    cancel() {
      this.modals = false;
    },
    //tải về
    async download(row) {
      let [th, fileKey, data, fileName] = [[], [], [], ''];
      let excelData = {
        ids: row.ids,
        page: 1,
        export: 1,
        time: this.formValidate.time,
      };
      let lebData = await this.getExcelData(excelData);
      if (!fileName) fileName = lebData.fileName;
      if (!fileKey.length) {
        fileKey = lebData.fileKey;
      }
      if (!th.length) th = lebData.header;
      data = data.concat(lebData.list);
      exportExcel(th, fileKey, fileName, data);
      return;
    },
    getExcelData(excelData) {
      return new Promise((resolve, reject) => {
        getFlowList(excelData).then((res) => {
          return resolve(res.data);
        });
      });
    },
  },
};
</script>

<style scoped lang="scss">
::v-deep .el-tabs__item {
  height: 54px !important;
  line-height: 54px !important;
}
::v-deep .ivu-card-body {
  padding: 0;
}

.tabbox {
  padding: 16px 20px 0px;
}
.box {
  padding: 20px;
  padding-bottom: 1px;
}
.tablebox {
  margin-top: 15px;
  padding-bottom: 10px;
}
.btnbox {
  padding: 20px 0px 0px 30px;
  .btns {
    width: 99px;
    height: 32px;
    background: var(--prev-color-primary);
    border-radius: 4px;
    text-align: center;
    line-height: 32px;
    color: #ffffff;
    cursor: pointer;
  }
}
</style>
