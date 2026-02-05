<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt" :body-style="{ padding: 0 }">
      <div class="padding-add">
        <el-form
          ref="formValidate"
          :model="formValidate"
          :label-width="labelWidth"
          :label-position="labelPosition"
          @submit.native.prevent
          inline
        >
          <el-form-item :label="$t('message.pages.division.applyList.search')">
            <el-input
              clearable
              :placeholder="$t('message.pages.division.applyList.placeholderNameUid')"
              v-model="formValidate.keyword"
              class="form_content_width"
            />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" v-db-click @click="userSearchs">{{ $t('message.pages.division.applyList.query') }}</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <el-card :bordered="false" shadow="never" class="ivu-mt mt16" :body-style="{ padding: '0 20px 20px' }">
      <el-row class="ivu-mt box-wrapper">
        <el-col :xs="24" :sm="24" ref="rightBox">
          <el-tabs v-model="formValidate.status" @tab-click="userSearchs">
            <el-tab-pane name="all" :label="$t('message.pages.division.applyList.all')"></el-tab-pane>
            <el-tab-pane
              v-for="(item, index) in statusList"
              :key="index"
              :label="getApplyStatusLabel(item.id)"
              :name="item.id"
            ></el-tab-pane>
          </el-tabs>
          <el-table
            :data="userLists"
            ref="table"
            v-loading="loading"
            highlight-current-row
            :no-formValidate-text="$t('message.pages.division.applyList.noData')"
            :no-filtered-formValidate-text="$t('message.pages.division.applyList.noFilterResult')"
          >
            <el-table-column :label="$t('message.pages.division.applyList.userUid')" width="100">
              <template slot-scope="scope">
                <span>{{ scope.row.uid }}</span>
              </template>
            </el-table-column>
            <el-table-column :label="$t('message.pages.division.applyList.agentName')" min-width="150">
              <template slot-scope="scope">
                <span>{{ scope.row.agent_name }}</span>
              </template>
            </el-table-column>
            <el-table-column :label="$t('message.pages.division.applyList.agentPhone')" min-width="150">
              <template slot-scope="scope">
                <span>{{ scope.row.phone }}</span>
              </template>
            </el-table-column>
            <el-table-column :label="$t('message.pages.division.applyList.divisionName')" min-width="150">
              <template slot-scope="scope">
                <span>{{ scope.row.division_name }}</span>
              </template>
            </el-table-column>
            <el-table-column :label="$t('message.pages.division.applyList.applyImages')" min-width="150">
              <template slot-scope="scope">
                <div class="pictrue-box" v-if="scope.row.images.length">
                  <div v-viewer v-for="(item, index) in scope.row.images || []" :key="index">
                    <img class="pictrue mr10" v-lazy="item" :src="item" />
                  </div>
                </div>
              </template>
            </el-table-column>
            <el-table-column :label="$t('message.pages.division.applyList.applyTime')" min-width="150">
              <template slot-scope="scope">
                <span>{{ scope.row.add_time }}</span>
              </template>
            </el-table-column>
            <el-table-column :label="$t('message.pages.division.applyList.applyStatus')" min-width="150">
              <template slot-scope="scope">
                <el-tag>{{ scope.row.status == 0 ? $t('message.pages.division.applyList.statusApplying') : scope.row.status == 1 ? $t('message.pages.division.applyList.statusAgreed') : $t('message.pages.division.applyList.statusRejected') }}</el-tag>
              </template>
            </el-table-column>
            <el-table-column :label="$t('message.pages.division.applyList.inviteCode')" min-width="150">
              <template slot-scope="scope">
                <el-tag>{{ scope.row.division_invite }}</el-tag>
              </template>
            </el-table-column>
            <el-table-column :label="$t('message.pages.division.applyList.action')" fixed="right" width="170">
              <template slot-scope="scope">
                <a v-if="scope.row.status == 0" v-db-click @click="groupAdd(scope.row.id, 1)">{{ $t('message.pages.division.applyList.agree') }}</a>
                <el-divider v-if="scope.row.status == 0" direction="vertical" />
                <a v-if="scope.row.status == 0" v-db-click @click="groupAdd(scope.row.id, 0)">{{ $t('message.pages.division.applyList.reject') }}</a>
                <el-divider direction="vertical" v-if="scope.row.status == 0" />
                <a v-db-click @click="del(scope.row, $t('message.pages.division.applyList.delApplyTitle'), scope.$index)">{{ $t('message.pages.division.applyList.del') }}</a>
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
import { divisionList, divisionFrom, isShowApi, clerkList } from '@/api/agent';
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
      statusList: [
        { id: '0' },
        { id: '1' },
        { id: '2' },
      ],
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
    this.getList();
  },
  methods: {
    getApplyStatusLabel(id) {
      const map = {
        '0': 'message.pages.division.applyList.statusApplying',
        '1': 'message.pages.division.applyList.statusAgreed',
        '2': 'message.pages.division.applyList.statusRejected',
      };
      return this.$t(map[id] || '');
    },
    userSearchs() {
      this.formValidate.page = 1;
      this.getList();
    },
    jump(uid) {
      this.clerkReqData.uid = uid;
      this.getClerkList();
    },
    getClerkList() {
      this.clerkReqData.division_type = 3;
      clerkList(this.clerkReqData).then((res) => {
        this.clerkLists = res.data.list;
        this.total2 = res.data.count;
        this.staffModal = true;
      });
    },
    // 列表
    getList() {
      this.loading = true;
      divisionList(this.formValidate)
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
    clerkPageChange() {
      this.clerkReqData.page = index;
      this.getClerkList();
    },
    // 添加表单
    groupAdd(id, type) {
      this.$modalForm(divisionFrom(id, type))
        .then((res) => {
          this.getList();
        })
        .catch((err) => {});
    },
    // 修改是否显示
    onchangeIsShow(row) {
      let data = {
        id: row.uid,
        status: row.division_status,
      };
      isShowApi(data)
        .then(async (res) => {
          this.$message.success(res.msg);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 编辑
    edit(row) {},
    // 删除
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        method: 'DELETE',
        uid: row.id,
        url: `agent/division/del_apply/${row.id}`,
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
}
::v-deep .el-tabs__item {
  height: 54px;
  line-height: 54px;
}
.icon-box {
  z-index: 3;
  position: absolute;
  right: 20px;
  top: 50%;
  transform: translateY(-50%);
  display: none;
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
