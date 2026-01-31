<template>
  <el-drawer :visible.sync="modals" :title="$t('message.pages.order.details.recordTab')" :wrapperClosable="false" :size="700">
    <el-card :bordered="false" shadow="never">
      <el-table :data="recordData" v-loading="loading" :empty-text="$t('message.pages.order.list.noData')" highlight-current-row>
        <el-table-column :label="$t('message.pages.order.details.orderIdCol')" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.oid }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.order.details.operationRecord')" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.change_message }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.order.details.operationTime')" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.change_time }}</span>
          </template>
        </el-table-column>
      </el-table>
    </el-card>
  </el-drawer>
</template>

<script>
import { getOrderRecord } from '@/api/order';
export default {
  name: 'orderRecord',
  data() {
    return {
      modals: false,
      loading: false,
      recordData: [],
      page: {
        page: 1, // 当前页
        limit: 15, // 每页显示条数
      },
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
      getOrderRecord(data)
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
