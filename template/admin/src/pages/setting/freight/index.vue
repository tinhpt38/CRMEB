<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mb-16" :body-style="{ padding: 0 }">
      <div class="padding-add">
        <el-form
          ref="levelFrom"
          :model="levelFrom"
          :label-width="labelWidth"
          label-position="right"
          @submit.native.prevent
          inline
        >
          <el-form-item label="Hiển thị">
            <el-select
              v-model="levelFrom.is_show"
              placeholder="Chọn trạng thái"
              clearable
              @change="userSearchs"
              class="form_content_width"
            >
              <el-option value="" label="Tất cả"></el-option>
              <el-option value="1" label="Đang hiển thị"></el-option>
              <el-option value="0" label="Đang ẩn"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="Tìm kiếm" label-for="keyword">
            <el-input
              class="form_content_width"
              v-model="levelFrom.keyword"
              placeholder="Tên hoặc mã công ty giao nhận"
            />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" v-db-click @click="userSearchs">Tìm kiếm</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <el-button type="primary" v-db-click @click="add">Thêm công ty giao nhận</el-button>
      <el-button v-db-click @click="syncExpress">Đồng bộ danh sách từ nền tảng</el-button>
      <el-table
        :data="levelLists"
        ref="table"
        class="mt14"
        v-loading="loading"
        no-userFrom-text="Chưa có dữ liệu"
        no-filtered-userFrom-text="Chưa có kết quả lọc nào"
      >
        <el-table-column label="ID" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Tên công ty" min-width="140">
          <template slot-scope="scope">
            <span>{{ scope.row.name }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Mã đơn vị" min-width="120">
          <template slot-scope="scope">
            <span>{{ scope.row.code }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Thứ tự" width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.sort }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Hiển thị" min-width="110">
          <template slot-scope="scope">
            <el-switch
              :active-value="1"
              :inactive-value="0"
              v-model="scope.row.is_show"
              :value="scope.row.is_show"
              @change="onchangeIsShow(scope.row)"
              size="large"
            >
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column label="Thao tác" fixed="right" width="80">
          <template slot-scope="scope">
            <a v-db-click @click="edit(scope.row)">Chỉnh sửa</a>
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination
          v-if="total"
          :total="total"
          :page.sync="levelFrom.page"
          :limit.sync="levelFrom.limit"
          @pagination="getList"
        />
      </div>
    </el-card>
  </div>
</template>
<script>
import { mapState } from 'vuex';
import {
  freightCreateApi,
  freightListApi,
  freightEditApi,
  freightStatusApi,
  freightSyncExpressApi,
} from '@/api/setting';
export default {
  name: 'setting_freight_express',
  data() {
    return {
      loading: false,
      levelFrom: {
        keyword: '',
        is_show: '',
        page: 1,
        limit: 20,
      },
      levelLists: [],
      total: 0,
      FromData: null,
    };
  },
  created() {
    this.getList();
  },
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '100px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  methods: {
    // xóa bỏ
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `freight/express/${row.id}`,
        method: 'DELETE',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.levelLists.splice(num, 1);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // Sửa đổi xem có hiển thị hay không
    onchangeIsShow(row) {
      let data = {
        id: row.id,
        status: row.is_show,
      };
      freightStatusApi(data)
        .then(async (res) => {
          this.$message.success(res.msg);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // Danh sách bậc
    getList() {
      this.loading = true;
      freightListApi(this.levelFrom)
        .then(async (res) => {
          let data = res.data;
          this.levelLists = data.list;
          this.total = data.count;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    // Thêm vào
    add() {
      this.$modalForm(freightCreateApi()).then(() => this.getList());
      // freightCreateApi().then(async res => {
      //     this.FromData = res.data;
      //     this.$refs.edits.modals = true;
      // }).catch(res => {
      //     this.$message.error(res.msg);
      // })
    },
    // biên tập
    edit(row) {
      this.$modalForm(freightEditApi(row.id)).then(() => this.getList());
      // freightEditApi(row.id).then(async res => {
      //     this.FromData = res.data;
      //     this.$refs.edits.modals = true;
      // }).catch(res => {
      //     this.$message.error(res.msg);
      // })
    },
    // tìm kiếm bảng
    userSearchs() {
      this.levelFrom.page = 1;
      this.getList();
    },
    syncExpress() {
      freightSyncExpressApi()
        .then(async (res) => {
          this.$message.success(res.msg);
          this.getList();
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
