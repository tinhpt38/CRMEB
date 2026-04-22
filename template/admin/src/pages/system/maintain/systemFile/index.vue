<template>
  <div>
    <div class="i-layout-page-header header-title">
      <span class="ivu-page-header-title">{{ $t($route.meta.title) }}</span>
    </div>
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <el-table ref="selection" :data="tabList" v-loading="loading"  :empty-text="$t('couponList.empty')" highlight-current-row>
        <el-table-column :label="$t('systemCommon.type')" width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.type }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.systemFile.filePath')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.filename }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.systemFile.checksum')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.cthash }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.systemFile.lastAccessTime')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.atime }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.systemFile.lastModifiedTime')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.mtime }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.systemFile.lastChangedTime')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.ctime }}</span>
          </template>
        </el-table-column>
      </el-table>
    </el-card>
  </div>
</template>

<script>
import { fileListApi } from '@/api/system';
export default {
  name: 'systemFile',
  data() {
    return {
      loading: false,
      tabList: [],
    };
  },
  created() {
    this.getList();
  },
  methods: {
    // 列表
    getList() {
      this.loading = true;
      fileListApi()
        .then(async (res) => {
          let data = res.data;
          this.tabList = data.list;
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

<style scoped></style>
