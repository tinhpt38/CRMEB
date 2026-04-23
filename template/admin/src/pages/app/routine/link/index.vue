<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <el-row class="mb20">
        <el-col :span="24">
          <el-button type="primary" v-db-click @click="add" class="mr10">Tạo liên kết</el-button>
        </el-col>
      </el-row>
      <el-table
        :data="tableList"
        v-loading="loading"
        highlight-current-row
        no-userFrom-text="Chưa có dữ liệu"
        no-filtered-userFrom-text="Chưa có kết quả lọc nào"
      >
        <el-table-column label="số seri" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="tên" width="180">
          <template slot-scope="scope">
            <span>{{ scope.row.title }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Chuyển địa chỉ" width="180">
          <template slot-scope="scope">
            <span>{{ scope.row.path }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Liên kết hệ thống(Chỉnh sửa không thay đổi)" min-width="200">
          <template slot-scope="scope">
            <span>{{ scope.row.http_url }}</span>
            <a class="ml10" v-db-click @click="onCopy(scope.row.http_url)">sao chép</a>
          </template>
        </el-table-column>
        <el-table-column label="liên kết WeChat(Chỉnh sửa thay đổi)" min-width="200">
          <template slot-scope="scope">
            <span>{{ scope.row.url }}</span>
            <a class="ml10" v-db-click @click="onCopy(scope.row.url)">sao chép</a>
          </template>
        </el-table-column>
        <el-table-column label="Thêm thời gian" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.add_time }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Thời gian hết hạn" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.expire_time }}</span>
          </template>
        </el-table-column>
        <el-table-column label="vận hành" fixed="right" width="120">
          <template slot-scope="scope">
            <a v-db-click @click="edit(scope.row)">biên tập</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="del(scope.row, 'Xóa liên kết', scope.$index)">xóa bỏ</a>
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination
          v-if="total"
          :total="total"
          :page.sync="tableFrom.page"
          :limit.sync="tableFrom.limit"
          @pagination="routineSchemeList"
        />
      </div>
    </el-card>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import { routineSchemeList, routineSchemeForm, routineSchemeDel } from '@/api/app';
export default {
  name: 'index',
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '80px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  data() {
    return {
      verModal: false,
      total: 20,
      tableFrom: {
        page: 1,
        limit: 15,
      },
      loading: false,
      tableList: [],
    };
  },
  created() {
    this.routineSchemeList();
  },
  methods: {
    // Thêm vào
    add() {
      this.$modalForm(routineSchemeForm(0)).then((res) => {
        this.routineSchemeList();
      });
    },
    onCopy(copyData) {
      this.$copyText(copyData)
        .then((message) => {
          this.$message.success('Đã sao chép thành công');
        })
        .catch((err) => {
          this.$message.error('Sao chép không thành công');
        });
    },
    // danh sách
    routineSchemeList() {
      this.loading = true;
      routineSchemeList(this.tableFrom)
        .then((res) => {
          this.tableList = res.data.list;
          this.total = res.data.count;
          this.loading = false;
        })
        .catch((err) => {
          this.$message.error(err.msg);
          this.loading = false;
        });
    },
    // Thêm vào
    edit(row) {
      this.$modalForm(routineSchemeForm(row.id)).then((res) => {
        this.routineSchemeList();
      });
    },
    // xóa bỏ
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `app/routine/scheme_del/${row.id}`,
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
  },
};
</script>

<style lang="scss" scoped></style>
