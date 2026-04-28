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
        <el-row :gutter="24">
          <el-col v-bind="grid">
            <el-form-item label="Trạng thái hoạt động trước khi bán：">
              <el-select placeholder="Vui lòng chọn trạng thái hoạt động" v-model="tableFrom.time_type" clearable @change="userSearchs">
                <el-option value="0" label="Tất cả"></el-option>
                <el-option value="1" label="Chưa bắt đầu"></el-option>
                <el-option value="2" label="đang diễn ra"></el-option>
                <el-option value="3" label="đã kết thúc"></el-option>
              </el-select>
            </el-form-item>
          </el-col>
          <el-col v-bind="grid">
            <el-form-item label="Trạng thái sản phẩm trước khi bán：">
              <el-select placeholder="Vui lòng chọn trạng thái sản phẩm" v-model="tableFrom.status" clearable @change="userSearchs">
                <el-option value="" label="Tất cả"></el-option>
                <el-option value="1" label="Trên kệ"></el-option>
                <el-option value="0" label="Đã xóa khỏi kệ"></el-option>
              </el-select>
            </el-form-item>
          </el-col>
          <el-col v-bind="grid">
            <el-form-item label="Tìm kiếm sản phẩm：" label-for="title">
              <el-input
                search
                enter-button
                placeholder="Vui lòng nhập tên sản phẩm/ID"
                v-model="tableFrom.title"
                @on-search="userSearchs"
              />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row class="mb20">
          <el-col v-bind="grid">
            <el-button
              v-auth="['marketing-store_bargain-create']"
              type="primary"
              icon="md-add"
              v-db-click
              @click="add"
              class="mr10"
              >Thêm các mặt hàng bán trước</el-button
            >
            <!-- <el-button
              v-auth="['export-storeBargain']"
              class="export"
              icon="ios-share-outline"
              v-db-click @click="exports"
              >Xuất file</el-button
            > -->
          </el-col>
        </el-row>
      </el-form>
      <el-table
        :data="tableList"
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
        <el-table-column label="Hình ảnh trước khi bán" min-width="90">
          <template slot-scope="scope">
            <div class="tabBox_img" v-viewer>
              <img v-lazy="scope.row.image" />
            </div>
          </template>
        </el-table-column>
        <el-table-column label="Tên bán trước" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.title }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Giá bán trước" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.price }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Số lượng mặt hàng đã bán" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.sales }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Phiên bản giới hạn" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.quota_show }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Số lượng còn lại có hạn" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.quota }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Thời gian hoạt động" min-width="130">
          <template slot-scope="scope">
            <div>Tăng lên: {{ scope.row.start_time | formatDate }}</div>
            <div>Kết thúc: {{ scope.row.stop_time | formatDate }}</div>
          </template>
        </el-table-column>
        <el-table-column label="Trạng thái trước khi bán" min-width="130">
          <template slot-scope="scope">
            <el-switch
              class="defineSwitch"
              :active-value="1"
              :inactive-value="0"
              v-model="scope.row.status"
              :value="scope.row.status"
              @change="onchangeIsShow(scope.row)"
              size="large"
              active-text="Trên kệ"
              inactive-text="Đã xóa khỏi kệ"
            >
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column label="Thao tác" fixed="right" width="170">
          <template slot-scope="scope">
            <a v-db-click @click="edit(scope.row)">Chỉnh sửa</a>
            <el-divider v-if="scope.row.stop_status === 0" direction="vertical" />
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="del(scope.row, 'Xóa các mặt hàng bán trước', scope.$index)">Xóa</a>
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
import { presellListApi, advanceSetStatusApi, stroeBargainApi } from '@/api/marketing';
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
      grid: {
        xl: 7,
        lg: 10,
        md: 12,
        sm: 24,
        xs: 24,
      },
      tableFrom: {
        status: '',
        time_type: 0,
        title: '',
        page: 1,
        limit: 15,
      },
      tableFrom2: {
        status: '',
        store_name: '',
        export: 1,
      },
      total: 0,
    };
  },
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '100px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'left';
    },
  },
  created() {
    this.getList();
  },
  methods: {
    // Thêm vào
    add() {
      this.$router.push({ path: this.$routeProStr + '/marketing/presell/create/0' });
    },
    // Xuất khẩu
    exports() {
      let formValidate = this.tableFrom;
      let data = {
        status: formValidate.status,
        store_name: formValidate.store_name,
      };
      stroeBargainApi(data)
        .then((res) => {
          location.href = res.data[0];
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // biên tập
    edit(row) {
      this.$router.push({
        path: this.$routeProStr + '/marketing/presell/create/' + row.id + '/0',
      });
    },
    // Sao chép bằng một cú nhấp chuột
    copy(row) {
      this.$router.push({
        path: this.$routeProStr + '/marketing/presell/create/' + row.id + '/1',
      });
    },
    // xóa bỏ
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `marketing/advance/${row.id}`,
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
    // danh sách
    getList() {
      this.loading = true;
      this.tableFrom.status = this.tableFrom.status || '';
      presellListApi(this.tableFrom)
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
      advanceSetStatusApi(data)
        .then(async (res) => {
          this.getList();
          this.$message.success(res.msg);
        })
        .catch((res) => {
          this.$message.error(res.msg);
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
