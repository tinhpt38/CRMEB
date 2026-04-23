<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <el-row class="mb20">
        <el-col :span="24">
          <el-button type="primary" v-db-click @click="add" class="mr10">phiên bản phát hành</el-button>
        </el-col>
      </el-row>
      <el-table
        :data="tableList"
        v-loading="loading"
        highlight-current-row
        no-userFrom-text="Chưa có dữ liệu"
        no-filtered-userFrom-text="Chưa có kết quả lọc nào"
      >
        <el-table-column label="số phiên bản" width="80">
          <template slot-scope="scope">
            <el-tooltip
              effect="light"
              v-if="scope.row.is_new"
              trigger="hover"
              placement="top-start"
              content="Phiên bản trực tuyến mới nhất hiện nay!"
            >
              <i class="el-icon-s-promotion" style="font-size: 16px; color: red"></i>
            </el-tooltip>
            {{ scope.row.version }}
          </template>
        </el-table-column>
        <el-table-column label="loại nền tảng" min-width="90">
          <template slot-scope="scope">
            <div>
              <span>{{ scope.row.platform === 1 ? 'Android' : 'quả táo' }}</span>
            </div>
          </template>
        </el-table-column>
        <el-table-column label="Thông tin nâng cấp" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.info }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Có bắt buộc không?" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.is_force === 1 ? 'lực lượng' : 'Không bắt buộc' }}</span>
          </template>
        </el-table-column>
        <el-table-column label="ngày phát hành" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.add_time }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Địa chỉ tải xuống" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.url }}</span>
          </template>
        </el-table-column>
        <el-table-column label="vận hành" fixed="right" width="120">
          <template slot-scope="scope">
            <a v-db-click @click="edit(scope.row)">biên tập</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="del(scope.row, 'xóa phiên bản', scope.$index)">xóa bỏ</a>
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
import { versionList, versionCrate } from '@/api/system';
export default {
  name: 'index',
  computed: {
    ...mapState('media', ['isMobile']),
    ...mapState('userLevel', ['categoryId']),
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
    this.getList();
  },
  methods: {
    // Sửa đổi thành công
    submitFail() {
      this.getList();
    },
    // Lịch sử trò chuyện
    record(row) {
      this.rows = row;
      this.modals3 = true;
      this.isChat = true;
      this.getListRecord();
    },
    // Thêm vào
    add() {
      this.$modalForm(versionCrate(0)).then((res) => {
        this.getList();
      });
    },
    // Danh sách thông tin phiên bản
    getList() {
      this.loading = true;
      versionList()
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
      this.$modalForm(versionCrate(row.id)).then((res) => {
        this.getList();
      });
    },
    // xóa bỏ
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `system/version_del/${row.id}`,
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
    handleSubmit(name) {
      this.$refs[name].validate((valid) => {
        if (valid) {
          this.$message.success('thành công!');
        } else {
          this.$message.error('thất bại!');
        }
      });
    },
    handleReset(name) {
      this.$refs[name].resetFields();
    },
    pageChange() {},
  },
};
</script>

<style lang="scss" scoped></style>
