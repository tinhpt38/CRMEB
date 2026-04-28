<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <el-form
        ref="tableFrom"
        :model="tableFrom"
        :label-width="labelWidth"
        :label-position="labelPosition"
        @submit.native.prevent
      >
        <el-scope.row :gutter="24">
          <el-col>
            <el-form-item label="Loại hoạt động：" clearable>
              <el-select
                style="width: 200px"
                v-model="tableFrom.factor"
                placeholder="Vui lòng chọn loại hoạt động"
                clearable
                @change="userSearchs"
              >
                <el-option value="1" label="Trích xuất điểm"></el-option>
                <el-option value="3" label="Thanh toán đơn hàng"></el-option>
                <el-option value="4" label="Đánh giá đơn hàng"></el-option>
              </el-select>
            </el-form-item>
          </el-col>
          <el-col>
            <el-form-item label="Trạng thái hoạt động：" clearable>
              <el-select
                style="width: 200px"
                v-model="tableFrom.start_status"
                placeholder="Vui lòng chọn"
                clearable
                @change="userSearchs"
              >
                <el-option value="0" label="Chưa bắt đầu"></el-option>
                <el-option value="1" label="đang tiến hành"></el-option>
                <el-option value="-1" label="đã kết thúc"></el-option>
              </el-select>
            </el-form-item>
          </el-col>

          <el-col>
            <el-form-item label="Tình trạng kệ：">
              <el-select
                style="width: 200px"
                placeholder="Vui lòng chọn"
                v-model="tableFrom.status"
                clearable
                @change="userSearchs"
              >
                <el-option value="1" label="Trên kệ"></el-option>
                <el-option value="0" label="Đã xóa khỏi kệ"></el-option>
              </el-select>
            </el-form-item>
          </el-col>
          <el-col>
            <el-form-item label="Tìm kiếm xổ số：" label-for="store_name">
              <el-input
                search
                enter-button
                style="width: 200px"
                placeholder="Vui lòng nhập tên xổ số，ID"
                v-model="tableFrom.store_name"
                @on-search="userSearchs"
              />
            </el-form-item>
          </el-col>
        </el-scope.row>
        <el-scope.row class="mb20">
          <el-button v-auth="['marketing-store_bargain-create']" type="primary" v-db-click @click="add" class="mr10"
            >Thêm xổ số</el-button
          >
        </el-scope.row>
      </el-form>
      <el-table
        :data="tableList"
        v-loading="loading"
        highlight-scope.row
        no-userFrom-text="Chưa có dữ liệu"
        no-filtered-userFrom-text="Chưa có kết quả lọc nào"
      >
        <el-table-column label="ID" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Tên hoạt động" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.name }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Loại hoạt động" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.lottery_type }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Số lần tham gia" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.lottery_all }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Số người tham gia xổ số" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.lottery_people }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Số người chiến thắng" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.lottery_win }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Trạng thái hoạt động" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.status_name }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Tình trạng kệ" min-width="130">
          <template slot-scope="scope">
            <el-switch
              class="defineSwitch"
              :active-value="1"
              :inactive-value="0"
              v-model="scope.row.status"
              :value="scope.row.status"
              :disabled="scope.row.lottery_status == 2 ? true : false"
              @change="onchangeIsShow(scope.row)"
              size="large"
              active-text="Trên kệ"
              inactive-text="Đã xóa khỏi kệ"
            >
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column label="Thời gian hoạt động" min-width="130">
          <template slot-scope="scope">
            <div>Tăng lên：{{ scope.row.start_time || '--' }}</div>
            <div>Kết thúc：{{ scope.row.end_time || '--' }}</div>
          </template>
        </el-table-column>
        <el-table-column label="Trạng thái hoạt động" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.status_name }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Thao tác" fixed="right" width="170">
          <template slot-scope="scope">
            <a v-db-click @click="edit(scope.row)">Chỉnh sửa</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="del(scope.row, 'Xóa xổ số', scope.$index)">Xóa</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="copy(scope.row)">Sao chép</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="getRecording(scope.row)">Kỷ lục xổ số</a>
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination
          v-if="total"
          :total="total"
          :page.sync="tableFrom.page"
          :limit.sync="tableFrom.limit"
          @pagination="getList"
        />
      </div>
    </el-card>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import { lotteryListApi, lotteryStatusApi } from '@/api/lottery';
import { formatDate } from '@/utils/validate';
export default {
  name: 'storeBargain',
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
      loading: false,
      tableList: [],
      tableFrom: {
        start_status: '',
        status: '',
        store_name: '',
        export: 0,
        page: 1,
        factor: '',
        limit: 15,
      },
      total: 0,
    };
  },
  computed: {
    ...mapState('admin/layout', ['isMobile']),
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
    // Thêm vào
    add() {
      this.$router.push({ path: this.$routeProStr + '/marketing/lottery/create' });
    },
    // biên tập
    edit(row) {
      this.$router.push({
        name: 'marketing_create',
        query: {
          id: row.id,
        },
      });
    },
    // Sao chép bằng một cú nhấp chuột
    copy(row) {
      this.$router.push({
        name: 'marketing_create',
        query: {
          id: row.id,
          copy: 1,
        },
      });
    },
    // xóa bỏ
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `marketing/lottery/del/${row.id}`,
        method: 'DELETE',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.tableList.splice(num, 1);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    //Xem hồ sơ xổ số
    getRecording(row) {
      this.$router.push({
        path: this.$routeProStr + `/marketing/lottery/recording_list`,
        query: {
          id: row.id,
        },
      });
    },
    // danh sách
    getList() {
      this.loading = true;
      this.tableFrom.start_status = this.tableFrom.start_status || '';
      this.tableFrom.status = this.tableFrom.status || '';
      lotteryListApi(this.tableFrom)
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
    // tìm kiếm bảng
    userSearchs() {
      this.tableFrom.page = 1;
      this.getList();
    },
    // Sửa đổi xem có hiển thị hay không
    onchangeIsShow(row) {
      let data = {
        id: row.id,
        status: row.status,
      };
      lotteryStatusApi(data)
        .then(async (res) => {
          this.$message.success(res.msg);
          this.getList();
        })
        .catch((res) => {
          this.$message.error(res.msg);
          this.getList();
        });
    },
  },
};
</script>

<style lang="scss" scoped>
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
