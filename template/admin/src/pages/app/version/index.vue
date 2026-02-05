<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <el-row class="mb20">
        <el-col :span="24">
          <el-button type="primary" v-db-click @click="add" class="mr10">{{ $t('message.pages.app.version.publishVersion') }}</el-button>
        </el-col>
      </el-row>
      <el-table
        :data="tableList"
        v-loading="loading"
        highlight-current-row
        :no-userFrom-text="$t('message.pages.app.version.noData')"
        :no-filtered-userFrom-text="$t('message.pages.app.version.noFilterResult')"
      >
        <el-table-column :label="$t('message.pages.app.version.versionNo')" width="80">
          <template slot-scope="scope">
            <el-tooltip
              effect="light"
              v-if="scope.row.is_new"
              trigger="hover"
              placement="top-start"
              :content="$t('message.pages.app.version.latestTip')"
            >
              <i class="el-icon-s-promotion" style="font-size: 16px; color: red"></i>
            </el-tooltip>
            {{ scope.row.version }}
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.app.version.platformType')" min-width="90">
          <template slot-scope="scope">
            <div>
              <span>{{ scope.row.platform === 1 ? $t('message.pages.app.version.android') : $t('message.pages.app.version.apple') }}</span>
            </div>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.app.version.upgradeInfo')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.info }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.app.version.isForce')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.is_force === 1 ? $t('message.pages.app.version.force') : $t('message.pages.app.version.notForce') }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.app.version.publishDate')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.add_time }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.app.version.downloadUrl')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.url }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.app.version.action')" fixed="right" width="120">
          <template slot-scope="scope">
            <a v-db-click @click="edit(scope.row)">{{ $t('message.pages.app.version.edit') }}</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="del(scope.row, $t('message.pages.app.version.delVersionTitle'), scope.$index)">{{ $t('message.pages.app.version.del') }}</a>
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
    // 修改成功
    submitFail() {
      this.getList();
    },
    // 聊天记录
    record(row) {
      this.rows = row;
      this.modals3 = true;
      this.isChat = true;
      this.getListRecord();
    },
    // 添加
    add() {
      this.$modalForm(versionCrate(0)).then((res) => {
        this.getList();
      });
    },
    // 版本信息列表
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
    // 添加
    edit(row) {
      this.$modalForm(versionCrate(row.id)).then((res) => {
        this.getList();
      });
    },
    // 删除
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
          this.$message.success('成功!');
        } else {
          this.$message.error('失败!');
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
