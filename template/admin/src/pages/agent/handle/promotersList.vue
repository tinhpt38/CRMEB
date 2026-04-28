<template>
  <div>
    <el-dialog
      :visible.sync="modals"
      :title="ListTitle === 'man' ? 'Thống kê danh sách các nhà quảng bá' : 'Đơn hàng khuyến mại'"
      :close-on-click-modal="false"
      width="1000px"
      @closed="onCancel"
    >
      <div class="table_box">
        <el-form
          ref="formValidate"
          :model="formValidate"
          :label-width="labelWidth"
          :label-position="labelPosition"
          @submit.native.prevent
          inline
        >
          <el-form-item label="Lựa chọn thời gian：">
            <el-date-picker
              clearable
              :editable="false"
              @change="onchangeTime"
              v-model="timeVal"
              value-format="yyyy/MM/dd"
              type="daterange"
              start-placeholder="ngày bắt đầu"
              end-placeholder="ngày kết thúc"
              style="width: 250px"
            ></el-date-picker>
          </el-form-item>
          <el-form-item label="Loại người dùng：">
            <el-select v-model="formValidate.type" clearable class="form_content_width">
              <el-option
                v-for="(item, i) in listTitle === 'man' ? fromList.fromTxt2 : fromList.fromTxt3"
                :key="i"
                :value="item.val"
                :label="item.text"
              ></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="Tìm kiếm：" v-if="listTitle === 'man'">
            <el-input
              clearable
              placeholder="Vui lòng nhập tên và số điện thoại của bạn、UID"
              v-model="formValidate.nickname"
              class="form_content_width"
            ></el-input>
          </el-form-item>
          <el-form-item label="Số đơn hàng：" v-if="listTitle === 'order'">
            <el-input
              clearable
              placeholder="Vui lòng nhập mã đơn hàng"
              v-model="formValidate.order_id"
              class="form_content_width"
            ></el-input>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" v-db-click @click="userSearchs">Tìm kiếm</el-button>
          </el-form-item>
        </el-form>
      </div>
      <el-table
        ref="selection"
        :data="tabList"
        v-loading="loading"
        empty-text="Chưa có dữ liệu"
        highlight-current-row
        max-height="400"
      >
        <template v-if="listTitle === 'man'">
          <el-table-column label="UID" width="80">
            <template slot-scope="scope">
              <span>{{ scope.row.uid }}</span>
            </template>
          </el-table-column>
          <el-table-column label="Hình đại diện" min-width="90">
            <template slot-scope="scope">
              <div class="tabBox_img" v-viewer>
                <img v-lazy="scope.row.avatar ? scope.row.avatar : require('../../../assets/images/moren.jpg')" />
              </div>
            </template>
          </el-table-column>
          <el-table-column label="Thông tin người dùng" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row.nickname }}</span>
            </template>
          </el-table-column>
          <el-table-column label="Nhà quảng bá hay không?" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row.promoter_name }}</span>
            </template>
          </el-table-column>
          <el-table-column label="Số khuyến mãi" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row.spread_count }}</span>
            </template>
          </el-table-column>
          <el-table-column label="Số lượng đơn đặt hàng" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row.order_count }}</span>
            </template>
          </el-table-column>
          <el-table-column label="Thời gian ràng buộc" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row.spread_time | formatDate }}</span>
            </template>
          </el-table-column>
        </template>
        <template v-else>
          <el-table-column label="Đặt hàngID" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row.order_id }}</span>
            </template>
          </el-table-column>
          <el-table-column label="Thông tin người dùng" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row.user_info }}</span>
            </template>
          </el-table-column>
          <el-table-column label="Thời gian" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row._add_time }}</span>
            </template>
          </el-table-column>
          <el-table-column label="Số tiền hoàn lại" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row.brokerage_price || 0 }}</span>
            </template>
          </el-table-column>
          <el-table-column label="Số tiền giảm giá của Đơn vị kinh doanh" min-width="130" v-if="rowsList.division_type == 1">
            <template slot-scope="scope">
              <span>{{ scope.row.division_brokerage || 0 }}</span>
            </template>
          </el-table-column>
          <el-table-column label="Số tiền chiết khấu đại lý" min-width="130" v-if="rowsList.division_type == 2">
            <template slot-scope="scope">
              <span>{{ scope.row.agent_brokerage || 0 }}</span>
            </template>
          </el-table-column>
          <el-table-column label="Số tiền giảm giá cho nhân viên" min-width="130" v-if="rowsList.division_type == 3">
            <template slot-scope="scope">
              <span>{{ scope.row.staff_brokerage || 0 }}</span>
            </template>
          </el-table-column>
        </template>
      </el-table>
      <div class="acea-row row-right page">
        <pagination
          v-if="total"
          :total="total"
          :page.sync="formValidate.page"
          :limit.sync="formValidate.limit"
          @pagination="pageChange"
        />
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import { stairListApi } from '@/api/agent';
import { formatDate } from '@/utils/validate';
export default {
  name: 'promotersList',
  // props: {
  //     listTitle: {
  //         type: String,
  //         default: ''
  //     }
  // },
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
      modals: false,
      fromList: {
        title: 'Chọn thời gian',
        custom: true,
        fromTxt: [
          { text: 'Tất cả', val: '' },
          { text: 'Hôm nay', val: 'today' },
          { text: 'Hôm qua', val: 'yesterday' },
          { text: '7 ngày qua', val: 'lately7' },
          { text: '30 ngày qua', val: 'lately30' },
          { text: 'tháng này', val: 'month' },
          { text: 'năm nay', val: 'year' },
        ],
        fromTxt2: [
          { text: 'Tất cả', val: '' },
          { text: 'Người quảng bá cấp độ đầu tiên', val: 1 },
          { text: 'Nhà quảng bá cấp hai', val: 2 },
        ],
        fromTxt3: [
          { text: 'Tất cả', val: '' },
          { text: 'Đơn hàng quảng bá cấp độ đầu tiên', val: 1 },
          { text: 'Đơn hàng quảng cáo thứ cấp', val: 2 },
          { text: 'Lệnh khuyến mãi của bộ phận kinh doanh', val: 3 },
          { text: 'Lệnh khuyến mãi đại lý', val: 4 },
        ],
      },
      formValidate: {
        limit: 15,
        page: 1,
        nickname: '',
        data: '',
        type: '',
        order_id: '',
        uid: 0,
      },
      loading: false,
      tabList: [],
      total: 0,
      timeVal: [],
      columns4: [],
      listTitle: '',
      rowsList: {
        division_type: 0,
      },
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
  methods: {
    onCancel() {
      this.formValidate = {
        limit: 15,
        page: 1,
        nickname: '',
        data: '',
        type: '',
        order_id: '',
        uid: 0,
      };
      this.timeVal = [];
      rowsList: {
      }
    },
    // ngày cụ thể
    onchangeTime(e) {
      this.timeVal = e;
      this.formValidate.data = this.timeVal ? this.timeVal.join('-') : '';
      this.getList(this.rowsList, this.listTitle);
    },
    // Chọn thời gian
    selectChange(tab) {
      this.formValidate.data = tab;
      this.timeVal = [];
      this.getList(this.rowsList, this.listTitle);
    },
    // danh sách
    getList(row, tit) {
      this.listTitle = tit;
      this.rowsList = row;
      this.loading = true;
      let url = '';
      if (this.listTitle === 'man') {
        url = 'agent/stair';
      } else {
        url = 'agent/stair/order';
      }
      this.formValidate.uid = row.uid;
      stairListApi(url, this.formValidate)
        .then(async (res) => {
          let data = res.data;
          this.tabList = data.list;
          this.total = data.count;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.tabList = [];
          this.$message.error(res.msg);
        });
    },
    pageChange() {
      this.getList(this.rowsList, this.listTitle);
    },
    // tìm kiếm
    userSearchs() {
      this.formValidate.page = 1;
      this.getList(this.rowsList, this.listTitle);
    },
  },
};
</script>

<style scoped></style>
