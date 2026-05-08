<template>
  <div class="article-manager">
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <el-form
        ref="formValidate"
        :model="formValidate"
        :label-width="labelWidth"
        :label-position="labelPosition"
        @submit.native.prevent
      >
        <el-row :gutter="24">
          <el-col :span="24">
            <el-form-item label="Lựa chọn thời gian：">
              <el-radio-group
                v-model="formValidate.data"
                type="button"
                @input="selectChange(formValidate.data)"
                class="mr"
              >
                <el-radio :label="item.val" v-for="(item, i) in fromList.fromTxt" :key="i">{{ item.text }}</el-radio>
              </el-radio-group>
              <el-date-picker
                clearable
                :editable="false"
                @change="onchangeTime"
                v-model="timeVal"
                format="dd/MM/yyyy"
                type="daterange"
                value-format="yyyy-MM-dd"
                range-separator="-"
                start-placeholder="ngày bắt đầu"
                end-placeholder="ngày kết thúc"
              ></el-date-picker>
            </el-form-item>
          </el-col>
          <el-col v-bind="grid">
            <el-form-item label="Trạng thái trước khi bán：">
              <el-select v-model="formValidate.status" placeholder="Vui lòng chọn" clearable @change="userSearchs">
                <el-option :value="1" label="đang tiến hành"></el-option>
                <el-option :value="2" label="thất bại"></el-option>
                <el-option :value="3" label="thành công"></el-option>
              </el-select>
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
      <el-table
        :data="tableList"
        class="mt14"
        v-loading="loading"
        highlight-current-row
        no-userFrom-text="Chưa có dữ liệu"
        no-filtered-userFrom-text="Chưa có kết quả lọc nào"
      >
        <el-table-column label="Hình đại diện" min-width="100">
          <template slot-scope="scope">
            <div class="tabBox_img" v-viewer>
              <img v-lazy="scope.row.avatar" />
            </div>
          </template>
        </el-table-column>
        <el-table-column label="Bắt đầu người dùng" min-width="130">
          <template slot-scope="scope">
            <span> {{ scope.row.nickname + ' / ' + scope.row.uid }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Bật thời gian" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.add_time }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Các mặt hàng bán trước" min-width="300">
          <template slot-scope="scope">
            <span>{{ scope.row.title }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Giá thấp nhất" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.bargain_price_min }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Giá hiện tại" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.now_price }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Tổng số lượng bán trước" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.people_num }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Số lượng đặt trước còn lại" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.num }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Thời gian kết thúc" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.datatime }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Trạng thái" min-width="130">
          <template slot-scope="scope">
            <el-tag color="blue" v-show="scope.row.status === 1">Đang tiến hành</el-tag>
            <el-tag color="volcano" v-show="scope.row.status === 2">Thất bại</el-tag>
            <el-tag color="cyan" v-show="scope.row.status === 3">Thành công</el-tag>
          </template>
        </el-table-column>
        <el-table-column label="Thao tác" fixed="right" width="100">
          <template slot-scope="scope">
            <a v-db-click @click="Info(scope.row)">Kiểm tra chi tiết</a>
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

    <!-- Hộp phương thức chi tiết-->
    <el-dialog :visible.sync="modals" class="tableBox" title="Kiểm tra chi tiết" :close-on-click-modal="false" width="720px">
      <el-table
        ref="selection"
        :data="tabList3"
        v-loading="loading2"
        empty-text="Chưa có dữ liệu"
        highlight-current-row
        max-height="600"
        size="small"
      >
        <el-table-column label="ID người dùng" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.uid }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Hình đại diện của người dùng" min-width="90">
          <template slot-scope="scope">
            <div class="tabBox_img" v-viewer>
              <img v-lazy="scope.row.avatar" />
            </div>
          </template>
        </el-table-column>
        <el-table-column label="Tên người dùng" min-width="130">
          <template slot-scope="scope">
            <span> {{ scope.row.nickname + ' / ' + scope.row.uid }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Số tiền đặt trước" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.price }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Thời gian bán trước" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.add_time }}</span>
          </template>
        </el-table-column>
      </el-table>
    </el-dialog>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import { formatDate } from '@/utils/validate';
import { bargainUserListApi, bargainUserInfoApi } from '@/api/marketing';
export default {
  name: 'bargainList',
  filters: {
    formatDate(time) {
      if (time !== 0) {
        let date = new Date(time * 1000);
        return formatDate(date, 'yyyy-MM-dd hh:mm');
      }
    },
  },
  // components: { cardsData },
  data() {
    return {
      cardLists: [],
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
      },
      grid: {
        xl: 7,
        lg: 10,
        md: 12,
        sm: 12,
        xs: 24,
      },
      loading: false,
      formValidate: {
        status: '',
        data: '',
        page: 1,
        limit: 15,
      },
      columns1: [
        {
          title: 'hình đại diện',
          slot: 'avatar',
          minWidth: 100,
        },
        {
          title: 'Bắt đầu người dùng',
          slot: 'nickname',
          minWidth: 150,
        },
        {
          title: 'Bật thời gian',
          key: 'add_time',
          minWidth: 150,
        },
        {
          title: 'Các mặt hàng bán trước',
          key: 'title',
          minWidth: 300,
        },
        {
          title: 'giá thấp nhất',
          key: 'bargain_price_min',
          minWidth: 120,
        },
        {
          title: 'giá hiện tại',
          key: 'now_price',
          minWidth: 100,
        },
        {
          title: 'Tổng số lượng bán trước',
          key: 'people_num',
          minWidth: 100,
        },
        {
          title: 'Số lượng đặt trước còn lại',
          key: 'num',
          minWidth: 100,
        },
        {
          title: 'thời gian kết thúc',
          key: 'datatime',
          minWidth: 150,
        },
        {
          title: 'Trạng thái',
          slot: 'status',
          minWidth: 100,
        },
        {
          title: 'Thao tác',
          slot: 'action',
          fixed: 'right',
          minWidth: 170,
        },
      ],
      tableList: [],
      total: 0,
      timeVal: [],
      loading2: false,
      tabList3: [],
      rows: {},
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
  created() {
    this.getList();
  },
  methods: {
    // kiểm tra chi tiết
    Info(row) {
      this.modals = true;
      this.rows = row;
      bargainUserInfoApi(row.id)
        .then(async (res) => {
          let data = res.data;
          this.tabList3 = data.list;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    // ngày cụ thể
    onchangeTime(e) {
      this.timeVal = e || [];
      this.formValidate.data = this.timeVal[0] ? (this.timeVal ? this.timeVal.join('-') : '') : '';
      this.formValidate.page = 1;
      this.getList();
    },
    // Chọn thời gian
    selectChange(tab) {
      this.formValidate.page = 1;
      this.formValidate.data = tab;
      this.timeVal = [];
      this.getList();
    },
    // danh sách
    getList() {
      this.loading = true;
      this.formValidate.status = this.formValidate.status || '';
      bargainUserListApi(this.formValidate)
        .then(async (res) => {
          let data = res.data;
          this.tableList = data.list;
          this.total = res.data.count;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    pageChange(index) {
      this.formValidate.page = index;
      this.getList();
    },
    // tìm kiếm bảng
    userSearchs() {
      this.formValidate.page = 1;
      this.getList();
    },
  },
};
</script>

<style lang="scss" scoped>
::v-deep .ivu-tag-cyan .ivu-tag-text {
  color: #19be6b !important;
}
.ivu-tag-cyan {
  background: rgba(25, 190, 170, 0.1);
  border-color: #19be6b !important;
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
</style>
