<template>
  <div>
    <el-card :bordered="false" shadow="never" :body-style="{ padding: 0 }">
      <div class="padding-add">
        <el-form
          ref="formValidate"
          :model="formValidate"
          :label-width="labelWidth"
          :label-position="labelPosition"
          @submit.native.prevent
          inline
        >
          <el-form-item :label="$t('message.agent.search') + '：'">
            <el-input
              clearable
              :placeholder="$t('message.agent.pleaseInputNameOrUID')"
              v-model="formValidate.keyword"
              class="form_content_width"
            />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" v-db-click @click="userSearchs">{{ $t('message.agent.query') }}</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <el-card :bordered="false" shadow="never" class="ivu-mt mt16" :body-style="{ padding: '0 20px 20px' }">
      <el-row class="box-wrapper">
        <el-col :xs="24" :sm="24" ref="rightBox">
          <el-tabs v-model="formValidate.status" @tab-click="userSearchs">
            <el-tab-pane name="all" :label="$t('message.agent.all')"></el-tab-pane>
            <el-tab-pane v-for="(item, index) in statusList" :key="index" :label="item.status_name" :name="item.id"></el-tab-pane>
          </el-tabs>
          <el-table
            :data="userLists"
            ref="table"
            v-loading="loading"
            highlight-current-row
            :no-formValidate-text="$t('message.agent.noData')"
            :no-filtered-formValidate-text="$t('message.common.noFilteredResults')"
          >
            <el-table-column :label="$t('message.agent.number')" width="80">
              <template slot-scope="scope">
                <span>{{ scope.row.id }}</span>
              </template>
            </el-table-column>
            <el-table-column :label="$t('message.agent.userUID')" width="80">
              <template slot-scope="scope">
                <span>{{ scope.row.uid }}</span>
              </template>
            </el-table-column>
            <el-table-column :label="$t('message.agent.userNickname')" min-width="150">
              <template slot-scope="scope">
                <span>{{ scope.row.nickname }}</span>
              </template>
            </el-table-column>
            <el-table-column :label="$t('message.agent.distributorPhone')" min-width="150">
              <template slot-scope="scope">
                <span>{{ scope.row.phone }}</span>
              </template>
            </el-table-column>
            <el-table-column :label="$t('message.agent.distributorName')" min-width="150">
              <template slot-scope="scope">
                <span>{{ scope.row.real_name }}</span>
              </template>
            </el-table-column>
            <el-table-column :label="$t('message.agent.applicationStatus')" min-width="150">
              <template slot-scope="scope">
                <el-tag>{{ scope.row.status == 0 ? $t('message.agent.pending') : scope.row.status == 1 ? $t('message.agent.approved') : $t('message.agent.rejected') }}</el-tag>
              </template>
            </el-table-column>
            <el-table-column :label="$t('message.agent.applicationTime')" min-width="150">
              <template slot-scope="scope">
                <span>{{ scope.row.add_time }}</span>
              </template>
            </el-table-column>
            <el-table-column :label="$t('message.agent.applicationReason')" min-width="150">
              <template slot-scope="scope">
                <span>{{ scope.row.content }}</span>
              </template>
            </el-table-column>
            <el-table-column :label="$t('message.agent.operation')" fixed="right" width="170">
              <template slot-scope="scope">
                <a v-if="scope.row.status == 0" v-db-click @click="examine(scope.row.id, scope.row.uid, 1)">{{ $t('message.agent.agree') }}</a>
                <el-divider v-if="scope.row.status == 0" direction="vertical" />
                <a v-if="scope.row.status == 0" v-db-click @click="examine(scope.row.id, scope.row.uid, 2)">{{ $t('message.agent.reject') }}</a>
                <el-divider direction="vertical" v-if="scope.row.status == 0" />
                <a v-db-click @click="del(scope.row, $t('message.agent.deleteApplication'), scope.$index)">{{ $t('message.agent.delete') }}</a>
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
        </el-col>
      </el-row>
    </el-card>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import { spreadList, spreadFrom, clerkList } from '@/api/agent';
import { formatDate } from '@/utils/validate';
export default {
  name: 'agent_extra',
  data() {
    return {
      grid: {
        xl: 7,
        lg: 7,
        md: 12,
        sm: 24,
        xs: 24,
      },
      total: 0,
      total2: 0,
      userLists: [],
      formInline: {
        uid: 0,
        proportion: 0,
        image: '',
      },
      statusList: [],
      FromData: null,
      loading: false,
      current: 0,
      formValidate: {
        page: 1,
        limit: 15,
        keyword: '',
        status: 'all',
      },
      staffModal: false,
      clerkReqData: {
        uid: 0,
        page: 1,
        limit: 15,
      },
      clerkLists: [],
    };
  },
  filters: {
    formatDate(time) {
      if (time !== 0) {
        let date = new Date(time * 1000);
        return formatDate(date, 'yyyy-MM-dd hh:mm');
      }
    },
  },
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '50px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  mounted() {
    this.initStatusList();
    this.getList();
  },
  methods: {
    initStatusList() {
      this.statusList = [
        {
          status_name: this.$t('message.agent.pending'),
          id: '0',
        },
        {
          status_name: this.$t('message.agent.approved'),
          id: '1',
        },
        {
          status_name: this.$t('message.agent.rejected'),
          id: '2',
        },
      ];
    },
    userSearchs() {
      this.formValidate.page = 1;
      this.getList();
    },
    // 列表
    getList() {
      this.loading = true;
      spreadList(this.formValidate)
        .then(async (res) => {
          let data = res.data;
          this.userLists = data.list;
          this.total = data.count;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    pageChange(index) {
      this.formValidate.page = index;
      this.getList();
    },
    // 审核
    examine(id, uid, type) {
      if (type == 1) {
        let data = {
          title: type == 1 ? this.$t('message.agent.approveDistributorApplication') : this.$t('message.agent.rejectDistributorApplication'),
          url: `agent/spread/apply/examine/${id}/${uid}/${type}`,
          method: 'post',
          ids: '',
        };
        this.$modalSure(data)
          .then((res) => {
            this.$message.success(res.msg);
            this.getList();
          })
          .catch((res) => {
            this.$message.error(res.msg);
          });
      } else {
        this.$prompt(this.$t('message.agent.pleaseInputRejectionReason'), this.$t('message.agent.tip'), {
          confirmButtonText: this.$t('message.agent.confirm'),
          cancelButtonText: this.$t('message.agent.cancel'),
        })
          .then(({ value }) => {
            let data = {
              refusal_reason: value,
            };
            spreadFrom(id, uid, type, data).then((res) => {
              this.$message.success(res.msg);
              this.getList();
            });
          })
          .catch(() => {
            this.$message({
              type: 'info',
              message: this.$t('message.agent.cancelInput'),
            });
          });
      }
    },
    // 编辑
    edit(row) {},
    // 删除
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        method: 'DELETE',
        uid: row.id,
        url: `agent/spread/apply/del/${row.id}`,
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.userLists.splice(num, 1);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
  },
};
</script>

<style lang="scss" scoped>
.ivu-form-item {
  margin-bottom: 0;
}
.picBox {
  display: inline-block;
  cursor: pointer;
  .upLoad {
    width: 58px;
    height: 58px;
    line-height: 58px;
    border: 1px dotted rgba(0, 0, 0, 0.1);
    border-radius: 4px;
    background: rgba(0, 0, 0, 0.02);
  }
  .pictrue {
    width: 60px;
    height: 60px;
    border: 1px dotted rgba(0, 0, 0, 0.1);
    margin-right: 10px;

    img {
      width: 100%;
      height: 100%;
    }
  }
}
::v-deep .ivu-menu-vertical .ivu-menu-item-group-title {
  display: none;
}
::v-deep .ivu-menu-vertical.ivu-menu-light:after {
  display: none;
}
.left-wrapper {
  height: 904px;
  background: #fff;
  border-right: 1px solid #f2f2f2;
}
.menu-item {
  z-index: 50;
  position: relative;
  display: flex;
  justify-content: space-between;
  word-break: break-all;
  &:hover .icon-box {
    display: block;
  }
}
.icon-box {
  z-index: 3;
  position: absolute;
  right: 20px;
  top: 50%;
  transform: translateY(-50%);
  display: none;
}

::v-deep .el-tabs__item {
  height: 54px;
  line-height: 54px;
}
.right-menu {
  z-index: 10;
  position: absolute;
  right: -106px;
  top: -11px;
  width: auto;
  min-width: 121px;
}
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
.pictrue-box {
  display: flex;
  align-item: center;
}
.pictrue {
  width: 25px;
  height: 25px;
}
</style>
