<template>
  <div style="width: 100%">
    <el-drawer :visible.sync="modals" :title="$t('message.pages.user.list.detailTitle')" :wrapperClosable="false" :size="1100" @closed="draChange">
      <div class="acea-row head">
        <div class="avatar mr15"><img :src="psInfo.avatar" /></div>
        <div class="dashboard-workplace-header-tip">
          <p class="dashboard-workplace-header-tip-title f-w-b" v-text="psInfo.nickname || '-'"></p>
          <div class="dashboard-workplace-header-tip-desc">
            <span class="dashboard-workplace-header-tip-desc-sp" v-for="(item, index) in detailsData" :key="index">{{
              (item.titleKey ? $t('message.pages.user.list.' + item.titleKey) : item.title) + '：' + item.value + (item.unitKey ? $t('message.pages.user.list.' + item.unitKey) : (item.key || ''))
            }}</span>
          </div>
        </div>
        <div class="edit-btn" v-if="!this.psInfo.is_del">
          <el-button v-if="!isEdit" type="primary" v-db-click @click="edit">{{ $t('message.pages.user.list.editBtn') }}</el-button>
          <el-button v-if="isEdit" v-db-click @click="edit">{{ $t('message.pages.user.list.cancelBtn') }}</el-button>
          <el-button v-if="isEdit" type="primary" v-db-click @click="editSave">{{ $t('message.pages.user.list.saveBtn') }}</el-button>
        </div>
      </div>
      <el-row justify="space-between" class="mt14">
        <el-col :span="24">
          <el-tabs type="border-card" v-model="activeName" @tab-click="changeTab">
            <el-tab-pane name="user" :label="$t('message.pages.user.list.userInfoTab')">
              <userEditForm ref="editForm" :userId="userId" @success="getDetails(userId)" v-if="isEdit"></userEditForm>
              <user-info :ps-info="psInfo" v-else></user-info>
            </el-tab-pane>
            <el-tab-pane :name="item.val" v-for="(item, index) in list" :key="index" :label="$t('message.pages.user.list.' + item.labelKey)">
              <template>
                <el-table
                  class="mt20"
                  :data="userLists"
                  max-height="400"
                  ref="table"
                  v-loading="loading"
                  :empty-text="$t('message.pages.user.list.noDataText')"
                >
                  <el-table-column :label="item.title" min-width="120" v-for="(item, index) in columns" :key="index">
                    <template slot-scope="scope">
                      <template v-if="item.key">
                        <div>
                          <span>{{ scope.row[item.key] }}</span>
                        </div>
                      </template>
                      <template v-else-if="item.slot === 'number'">
                        <div :class="scope.row.pm ? 'plusColor' : 'reduceColor'">
                          {{ scope.row.pm ? '+' + scope.row.number : '-' + scope.row.number }}
                        </div>
                      </template>
                    </template>
                  </el-table-column>
                </el-table>
                <div class="acea-row row-right page">
                  <pagination
                    v-if="total"
                    :total="total"
                    :page.sync="userFrom.page"
                    :limit.sync="userFrom.limit"
                    @pagination="changeType"
                  />
                </div>
              </template>
            </el-tab-pane>
          </el-tabs>
        </el-col>

        <!-- <el-col :span="24">
          
        </el-col> -->
      </el-row>
    </el-drawer>
  </div>
</template>

<script>
import { detailsApi, infoApi } from '@/api/user';
import userInfo from './userInfo';
import userEditForm from './userEditForm';

export default {
  name: 'userDetails',
  components: { userInfo, userEditForm },
  data() {
    return {
      isEdit: false,
      theme2: 'light',
      list: [
        { val: 'order', labelKey: 'tabOrder' },
        { val: 'integral', labelKey: 'tabIntegral' },
        { val: 'sign', labelKey: 'tabSign' },
        { val: 'coupon', labelKey: 'tabCoupon' },
        { val: 'balance_change', labelKey: 'tabBalanceChange' },
        { val: 'spread', labelKey: 'tabSpread' },
      ],
      modals: false,
      spinShow: false,
      detailsData: [],
      userId: 0,
      loading: false,
      userFrom: {
        type: 'order',
        page: 1, // 当前页
        limit: 20, // 每页显示条数
      },
      total: 0,
      columns: [],
      userLists: [],
      psInfo: {},
      activeName: 'user',
    };
  },
  created() {},
  methods: {
    edit() {
      this.activeName = 'user';
      this.isEdit = !this.isEdit;
    },
    editSave() {
      this.$refs.editForm.setUser();
    },
    draChange() {
      this.isEdit = false;
    },
    // 会员详情
    getDetails(id) {
      this.activeName = 'user';
      this.userId = id;
      this.spinShow = true;
      this.isEdit = false;
      detailsApi(id)
        .then(async (res) => {
          if (res.status === 200) {
            let data = res.data;
            this.detailsData = data.headerList;
            this.psInfo = data.ps_info;
            // this.changeType('user');
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
    changeTab(tab) {
      this.activeName = tab.name;
      this.changeType();
    },
    // tab选项
    changeType() {
      this.loading = true;
      this.userFrom.type = this.activeName;
      this.isEdit = false;
      if (this.activeName == 'user') return;
      if (this.userFrom.type === '') {
        this.userFrom.type = 'order';
      }
      let data = {
        id: this.userId,
        datas: this.userFrom,
      };
      infoApi(data)
        .then(async (res) => {
          if (res.status === 200) {
            switch (this.userFrom.type) {
              case 'order':
                this.columns = [
                  { title: this.$t('message.pages.user.list.colOrderId'), key: 'order_id', minWidth: 160 },
                  { title: this.$t('message.pages.user.list.colReceiverName'), key: 'real_name', minWidth: 100 },
                  { title: this.$t('message.pages.user.list.colProductCount'), key: 'total_num', minWidth: 90 },
                  { title: this.$t('message.pages.user.list.colPayPrice'), key: 'pay_price', minWidth: 120 },
                  { title: this.$t('message.pages.user.list.colPayTime'), key: 'pay_time', minWidth: 120 },
                ];
                break;
              case 'integral':
                this.columns = [
                  { title: this.$t('message.pages.user.list.colSourceUsage'), key: 'title', minWidth: 120 },
                  { title: this.$t('message.pages.user.list.colIntegralChange'), slot: 'number', minWidth: 120 },
                  { title: this.$t('message.pages.user.list.colBalanceAfter'), key: 'balance', minWidth: 120 },
                  { title: this.$t('message.pages.user.list.colDate'), key: 'add_time', minWidth: 120 },
                  { title: this.$t('message.pages.user.list.colRemark'), key: 'mark', minWidth: 120 },
                ];
                break;
              case 'sign':
                this.columns = [
                  { title: this.$t('message.pages.user.list.colAction'), key: 'title', minWidth: 120 },
                  { title: this.$t('message.pages.user.list.colPoints'), key: 'number', minWidth: 120 },
                  { title: this.$t('message.pages.user.list.colSignTime'), key: 'add_time', minWidth: 120 },
                  { title: this.$t('message.pages.user.list.colRemark'), key: 'mark', minWidth: 120 },
                ];
                break;
              case 'coupon':
                this.columns = [
                  { title: this.$t('message.pages.user.list.colCouponName'), key: 'coupon_title', minWidth: 120 },
                  { title: this.$t('message.pages.user.list.colFaceValue'), key: 'coupon_price', minWidth: 120 },
                  { title: this.$t('message.pages.user.list.colValidDays'), key: 'coupon_time', minWidth: 120 },
                  { title: this.$t('message.pages.user.list.colReceiveTime'), key: '_add_time', minWidth: 120 },
                ];
                break;
              case 'balance_change':
                this.columns = [
                  { title: this.$t('message.pages.user.list.colAction'), key: 'title', minWidth: 120 },
                  { title: this.$t('message.pages.user.list.colBalanceChange'), slot: 'number', minWidth: 120 },
                  { title: this.$t('message.pages.user.list.colCurrentBalance'), key: 'balance', minWidth: 120 },
                  { title: this.$t('message.pages.user.list.colCreateTime'), key: 'add_time', minWidth: 120 },
                  { title: this.$t('message.pages.user.list.colRemark'), key: 'mark', minWidth: 120 },
                ];
                break;
              default:
                this.columns = [
                  { title: this.$t('message.pages.user.list.colId'), key: 'uid', minWidth: 120 },
                  { title: this.$t('message.pages.user.list.colNickname'), key: 'nickname', minWidth: 120 },
                  { title: this.$t('message.pages.user.list.colLevel'), key: 'type', minWidth: 120 },
                  { title: this.$t('message.pages.user.list.colJoinTime'), key: 'add_time', minWidth: 120 },
                ];
            }
            this.$nextTick((e) => {
              let data = res.data;
              this.userLists = data.list;
              this.total = data.count;
            });
            this.loading = false;
          } else {
            this.loading = false;
            this.$message.error(res.msg);
          }
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
.avatar {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  overflow: hidden;
  img {
    width: 100%;
    height: 100%;
  }
}
::v-deep .el-drawer__body {
  padding: 20px 0 !important;
}
::v-deep .el-tabs--border-card > .el-tabs__content {
  padding: 0 35px;
}
::v-deep .el-tabs--border-card > .el-tabs__header,
::v-deep .el-tabs--border-card > .el-tabs__header .el-tabs__item:active {
  border: none;
  height: 40px;
}
::v-deep .el-tabs--border-card > .el-tabs__header .el-tabs__item.is-active {
  border: none;
  border-top: 2px solid var(--prev-color-primary);
  font-size: 13px;
  font-weight: 500;
  color: #303133;
  line-height: 16px;
}
::v-deep .el-tabs--border-card > .el-tabs__header .el-tabs__item {
  border: none;
}
::v-deep .el-tabs--border-card > .el-tabs__header .el-tabs__item {
  margin-top: 0;
  transition: none;
  height: 40px !important;
  line-height: 40px !important;

  font-size: 13px;
  font-weight: 400;
  color: #303133;
  line-height: 16px;
}
::v-deep .el-tabs--border-card {
  border: none;
  box-shadow: none;
}
.head {
  position: relative;
  padding: 0 15px;
  .edit-btn {
    position: absolute;
    right: 20px;
    top: 0px;
    display: flex;
    align-items: center;
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
      width: 82%;
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
          font-size: 13px;
          display: inline-block;
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
<style lang="scss" scoped>
.user_menu ::v-deep .ivu-menu {
  width: 100% !important;
}
</style>
