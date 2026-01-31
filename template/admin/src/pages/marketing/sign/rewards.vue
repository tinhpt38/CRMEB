<template>
  <div>
    <el-card :bordered="false" shadow="never" :body-style="{ padding: '0 20px 20px' }">
      <el-tabs v-model="signFrom.type" @tab-click="onClickTab">
        <el-tab-pane :label="item.name" :name="item.type" v-for="(item, index) in tabList" :key="index" />
      </el-tabs>
      <el-button v-db-click @click="add" type="primary">{{
        signFrom.type == 0 ? $t('message.pages.marketing.sign.rewards.addContinuous') : $t('message.pages.marketing.sign.rewards.addCumulative')
      }}</el-button>
      <el-table
        :data="tableData"
        ref="table"
        class="mt14"
        v-loading="loading"
        highlight-current-row
        :no-data-text="$t('message.pages.marketing.common.noData')"
        :no-filtered-data-text="$t('message.pages.marketing.common.noFilterResult')"
      >
        <el-table-column :label="$t('message.pages.marketing.sign.rewards.type')" min-width="80">
          <template slot-scope="scope">
            <span>{{
              scope.row.type == 0 ? $t('message.pages.marketing.sign.rewards.continuousReward').replace('N', scope.row.days) : $t('message.pages.marketing.sign.rewards.cumulativeReward').replace('N', scope.row.days)
            }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.sign.rewards.days')" min-width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.days }} ({{ $t('message.pages.marketing.sign.rewards.dayUnit') }})</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.sign.rewards.rewardPoint')" min-width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.point }} ({{ $t('message.pages.marketing.sign.rewards.pointUnit') }})</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.sign.rewards.rewardExp')" min-width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.exp }} ({{ $t('message.pages.marketing.sign.rewards.expUnit') }})</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.marketing.common.action')" fixed="right" width="100">
          <template slot-scope="scope">
            <a v-db-click @click="edit(scope.row)">{{ $t('message.pages.marketing.sign.rewards.edit') }}</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="del(scope.row)">{{ $t('message.pages.marketing.sign.rewards.del') }}</a>
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination
          v-if="total"
          :total="total"
          :page.sync="signFrom.page"
          :limit.sync="signFrom.limit"
          @pagination="pageChange"
        />
      </div>
    </el-card>
  </div>
</template>
<script>
import { addSignRewards, signRewards, editSignRewards } from '@/api/marketing.js';
export default {
  name: '',
  data() {
    return {
      signFrom: {
        type: 0,
        page: 1,
        limit: 20,
      },
      tabList: [],
      total: 0,
      tableData: [],
      loading: false,
    };
  },
  created() {
    this.getList();
  },
  mounted() {},
  methods: {
    onClickTab() {
      this.signFrom.page = 1;
      this.getList();
    },
    getList() {
      this.loading = true;
      signRewards(this.signFrom)
        .then((res) => {
          this.tableData = res.data.list;
          this.total = res.data.count;
          this.loading = false;
        })
        .catch((err) => {
          this.$message.error(err.msg);
          this.loading = false;
        });
    },
    pageChange(val) {
      this.signFrom.page = val;
      this.getList();
    },
    add() {
      this.$modalForm(addSignRewards({ type: this.signFrom.type })).then((res) => {
        this.getList();
      });
    },
    edit(row) {
      this.$modalForm(editSignRewards(row.id)).then((res) => {
        this.getList();
      });
    },
    del(row) {
      const t = (key) => this.$t(key);
      const p = 'message.pages.marketing.sign.rewards.';
      const title = row.type == 0 ? t(p + 'delContinuousConfirm').replace('N', row.days) : t(p + 'delCumulativeConfirm').replace('N', row.days);
      let delfromData = {
        title,
        url: `/marketing/sign/del_rewards/${row.id}`,
        method: 'DELETE',
      };
      this.$modalSure(delfromData)
        .then((res) => {
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
::v-deep .el-tabs__item {
  height: 54px !important;
  line-height: 54px !important;
}
</style>
