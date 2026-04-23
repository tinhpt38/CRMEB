<template>
  <el-dialog :visible.sync="modals" scrollable title="Hồ sơ đặt hàng" width="720px" class="order_box">
    <el-card :bordered="false" shadow="never">
      <el-table :columns="columns" :data="recordData" v-loading="loading" empty-text="Chưa có dữ liệu" highlight-current-row>
        <el-table-column label="Đặt hàngID" width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.oid }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Hồ sơ hoạt động" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.change_message }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Thời gian hoạt động" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.change_time }}</span>
          </template>
        </el-table-column>
      </el-table>
    </el-card>
  </el-dialog>
</template>

<script>
import { getIntegralOrderRecord } from '@/api/marketing';
export default {
  name: 'orderRecord',
  data() {
    return {
      modals: false,
      loading: false,
      recordData: [],
      page: {
        page: 1, // Trang hiện tại
        limit: 15, // Số mục được hiển thị trên mỗi trang
      },
      columns: [
        {
          title: 'Đặt hàngID',
          key: 'oid',
          align: 'center',
          minWidth: 40,
        },
        {
          title: 'Hồ sơ hoạt động',
          key: 'change_message',
          align: 'center',
          minWidth: 280,
        },
        {
          title: 'Thời gian hoạt động',
          key: 'change_time',
          align: 'center',
          minWidth: 100,
        },
      ],
    };
  },
  methods: {
    pageChange(index) {
      this.page.pageNum = index;
      this.getList();
    },
    getList(id) {
      let data = {
        id: id,
        datas: this.page,
      };
      this.loading = true;
      getIntegralOrderRecord(data)
        .then(async (res) => {
          this.recordData = res.data;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
  },
};
</script>

<style lang="scss" scoped>
.ivu-table-wrapper {
  border-left: 1px solid #dcdee2;
  border-top: 1px solid #dcdee2;
}
.order_box ::v-deep .ivu-table th {
  background: #f8f8f9 !important;
}
</style>
