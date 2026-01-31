<template>
  <div style="width: 100%">
    <el-dialog :visible.sync="modals" :title="$t('message.pages.finance.commissionDetails.userDetailTitle')" :close-on-click-modal="false" width="720px">
      <div class="" v-loading="spinShow">
        <div class="dashboard-workplace-header-tip">
          <div class="dashboard-workplace-header-tip-desc">
            <span class="dashboard-workplace-header-tip-desc-sp">{{ $t('message.pages.finance.commissionDetails.realName') }}{{ detailsData.nickname }}</span>
            <span class="dashboard-workplace-header-tip-desc-sp"
              >{{ $t('message.pages.finance.commissionDetails.superiorPromoter') }}{{ detailsData.spread_name ? detailsData.spread_name : $t('message.pages.finance.commissionDetails.none') }}</span
            >
            <span class="dashboard-workplace-header-tip-desc-sp">{{ $t('message.pages.finance.commissionDetails.commissionTotalIncome') }}{{ detailsData.number }}</span>
            <span class="dashboard-workplace-header-tip-desc-sp">{{ $t('message.pages.finance.commissionDetails.userBalance') }}{{ detailsData.now_money }}</span>
            <span class="dashboard-workplace-header-tip-desc-sp">{{ $t('message.pages.finance.commissionDetails.createTime') }}{{ detailsData.add_time }}</span>
          </div>
        </div>
      </div>
      <el-divider direction="vertical" dashed />
      <el-form
        ref="formValidate"
        label-width="75px"
        :label-position="labelPosition"
        class="tabform"
        @submit.native.prevent
      >
        <el-row :gutter="24">
          <el-col :span="12">
            <el-form-item :label="$t('message.pages.finance.commissionDetails.timeRange')" class="tab_data">
              <el-date-picker
                clearable
                :editable="false"
                @change="onchangeTime"
                format="yyyy-MM-dd"
                value-format="yyyy-MM-dd"
                type="daterange"
                :start-placeholder="$t('message.pages.finance.commissionDetails.startDate')"
                :end-placeholder="$t('message.pages.finance.commissionDetails.endDate')"
                style="width: 100%"
              ></el-date-picker>
            </el-form-item>
          </el-col>
          <el-col :span="4">
            <el-button type="primary" v-db-click @click="userSearchs">{{ $t('message.pages.finance.commissionDetails.search') }}</el-button>
          </el-col>
        </el-row>
      </el-form>
      <el-table
        :data="tabList"
        ref="table"
        v-loading="loading"
        :no-data-text="$t('message.pages.finance.commissionDetails.noData')"
        :no-filtered-data-text="$t('message.pages.finance.commissionDetails.noFilterResult')"
        class="table"
      >
        <el-table-column :label="$t('message.pages.finance.commissionDetails.commissionAmount')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.number }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.finance.commissionDetails.obtainTime')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row._add_time }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.finance.commissionDetails.remark')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.mark }}</span>
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination
          v-if="total"
          :total="total"
          :page.sync="formValidate.page"
          :limit.sync="formValidate.limit"
          @pagination="getList"
        />
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { commissionDetailApi, extractlistApi } from '@/api/finance';
import { mapState } from 'vuex';
export default {
  name: 'commissionDetails',
  data() {
    return {
      modals: false,
      spinShow: false,
      detailsData: {},
      Ids: 0,
      loading: false,
      formValidate: {
        nickname: '',
        start_time: '',
        end_time: '',
        page: 1, // 当前页
        limit: 20, // 每页显示条数
      },
      total: 0,
      tabList: [],
    };
  },
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '80px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  mounted() {
    if (this.Ids) {
      this.getList();
    }
  },
  methods: {
    // 时间
    onchangeTime(e) {
      this.formValidate.start_time = e[0];
      this.formValidate.end_time = e[1];
    },
    // 详情
    getDetails(id) {
      this.Ids = id;
      this.spinShow = true;
      commissionDetailApi(id)
        .then(async (res) => {
          if (res.status === 200) {
            let data = res.data;
            this.detailsData = data.user_info;
            this.spinShow = false;
          } else {
            this.spinShow = false;
            this.$message.error(res.msg);
          }
        })
        .catch((res) => {
          this.spinShow = false;
          this.$message.error(res.msg);
        });
    },
    // 列表
    getList() {
      this.loading = true;
      extractlistApi(this.Ids, this.formValidate)
        .then(async (res) => {
          let data = res.data;
          this.tabList = data.data;
          this.total = data.count;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    // 搜索
    userSearchs() {
      this.formValidate.page = 1;
      this.getList();
    },
  },
};
</script>

<style lang="scss">
.table {
  .ivu-table-default {
    overflow-y: auto;
    max-height: 350px;
  }
}
.dashboard-workplace {
  &-header {
    &-avatar {
      width: 64px;
      height: 64px;
      border-radius: 50%;
      margin-right: 16px;
      font-weight: 600;
    }

    &-tip {
      width: 100%;
      display: inline-block;
      vertical-align: middle;

      &-title {
        font-size: 13px;
        color: #000000;
        margin-bottom: 12px;
      }

      &-desc {
        &-sp {
          width: 33.33%;
          color: #17233d;
          font-size: 12px;
          display: inline-block;
          padding-bottom: 10px;
        }
      }
    }

    &-extra {
      .ivu-col {
        p {
          text-align: right;
        }

        p:first-child {
          span:first-child {
            margin-right: 4px;
          }

          span:last-child {
            color: #808695;
          }
        }

        p:last-child {
          font-size: 22px;
        }
      }
    }
  }
}
</style>
