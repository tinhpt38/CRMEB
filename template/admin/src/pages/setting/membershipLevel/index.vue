<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mb-16" :body-style="{ padding: 0 }">
      <div class="padding-add">
        <el-form
          ref="formValidate"
          :model="formValidate"
          :label-width="labelWidth"
          :label-position="labelPosition"
          @submit.native.prevent
          inline
        >
          <el-form-item :label="$t('message.pages.setting.membershipLevel.isShow')">
            <el-select v-model="formValidate.status" clearable @change="search" class="form_content_width">
              <el-option value="" :label="$t('message.pages.setting.membershipLevel.all')"></el-option>
              <el-option :value="1" :label="$t('message.pages.setting.membershipLevel.show')"></el-option>
              <el-option :value="0" :label="$t('message.pages.setting.membershipLevel.hide')"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('message.pages.setting.membershipLevel.levelName')">
            <el-input
              clearable
              :placeholder="$t('message.pages.setting.membershipLevel.placeholderLevelName')"
              v-model="formValidate.keyword"
              class="form_content_width"
            />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" v-db-click @click="search">{{ $t('message.pages.setting.membershipLevel.query') }}</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <el-card :bordered="false" shadow="never" class="ivu-mt mt16">
      <el-button type="primary" v-db-click @click="groupAdd">{{ $t('message.pages.setting.membershipLevel.addLevel') }}</el-button>
      <el-table
        class="mt14"
        :data="tabList"
        ref="table"
        v-loading="loading"
        highlight-current-row
        :no-data-text="$t('message.pages.setting.membershipLevel.noData')"
        :no-filtered-data-text="$t('message.pages.setting.membershipLevel.noFilterResult')"
      >
        <el-table-column :label="$t('message.pages.setting.membershipLevel.id')" width="50">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.membershipLevel.productImage')" min-width="90">
          <template slot-scope="scope">
            <div class="tabBox_img" v-viewer>
              <img v-lazy="scope.row.image" />
            </div>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.membershipLevel.name')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.name }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.membershipLevel.grade')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.grade }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.membershipLevel.oneBrokerageRatio')" min-width="150">
          <template slot-scope="scope">
            <span
              >{{
                scope.row.one_brokerage_percent == '0.00'
                  ? scope.row.one_brokerage_ratio
                  : scope.row.one_brokerage_percent
              }}%</span
            >
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.membershipLevel.twoBrokerageRatio')" min-width="150">
          <template slot-scope="scope">
            <span
              >{{
                scope.row.two_brokerage_percent == '0.00'
                  ? scope.row.two_brokerage_ratio
                  : scope.row.two_brokerage_percent
              }}%</span
            >
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.membershipLevel.taskTotal')" min-width="150">
          <template slot-scope="scope">
            <span>{{ scope.row.task_total_num }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.membershipLevel.taskNum')" min-width="150">
          <template slot-scope="scope">
            <span>{{ scope.row.task_num }}</span>
          </template>
        </el-table-column>
        <!-- <el-table-column label="一级上浮比例" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.one_brokerage }}%</span>
          </template>
        </el-table-column>
        <el-table-column label="一级分佣比例(上浮后)" min-width="150">
          <template slot-scope="scope">
            <span>{{ scope.row.one_brokerage_ratio }}%</span>
          </template>
        </el-table-column>
        <el-table-column label="二级上浮比例" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.two_brokerage }}%</span>
          </template>
        </el-table-column>
        <el-table-column label="二级分佣比例(上浮后)" min-width="150">
          <template slot-scope="scope">
            <span>{{ scope.row.two_brokerage_ratio }}%</span>
          </template>
        </el-table-column> -->
        <el-table-column :label="$t('message.pages.setting.membershipLevel.isShowCol')" min-width="130">
          <template slot-scope="scope">
            <el-switch
              class="defineSwitch"
              :active-value="1"
              :inactive-value="0"
              v-model="scope.row.status"
              :value="scope.row.status"
              @change="onchangeIsShow(scope.row)"
              size="large"
              :active-text="$t('message.pages.setting.membershipLevel.show')"
              :inactive-text="$t('message.pages.setting.membershipLevel.hide')"
            >
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.membershipLevel.action')" fixed="right" width="170">
          <template slot-scope="scope">
            <a v-db-click @click="addTask(scope.row)">{{ $t('message.pages.setting.membershipLevel.levelTask') }}</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="edit(scope.row, $t('message.pages.setting.membershipLevel.edit'))">{{ $t('message.pages.setting.membershipLevel.edit') }}</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="del(scope.row, $t('message.pages.setting.membershipLevel.delInfoTitle'), scope.$index)">{{ $t('message.pages.setting.membershipLevel.del') }}</a>
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
    </el-card>
    <div class="task-modal">
      <el-dialog :visible.sync="modal2" :title="$t('message.pages.setting.membershipLevel.addTaskTitle')" width="1000px">
        <el-form :model="taskData" :label-width="labelWidth" :label-position="labelPosition" inline>
          <el-form-item :label="$t('message.pages.setting.membershipLevel.isShow')">
            <el-select v-model="taskData.status" class="form_content_width" clearable>
              <el-option :value="1" :label="$t('message.pages.setting.membershipLevel.show')"></el-option>
              <el-option :value="0" :label="$t('message.pages.setting.membershipLevel.hide')"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('message.pages.setting.membershipLevel.taskName')">
            <el-input v-model="taskData.keyword" :placeholder="$t('message.pages.setting.membershipLevel.placeholderTaskName')" clearable class="form_content_width" />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" v-db-click @click="searchTask">{{ $t('message.pages.setting.membershipLevel.search') }}</el-button>
          </el-form-item>
        </el-form>
        <div>
          <div class="add-task">
            <el-button type="primary" v-db-click @click="taskAdd()">{{ $t('message.pages.setting.membershipLevel.addLevelTask') }}</el-button>
            <el-button type="primary" v-db-click @click="taskEdit()">{{ $t('message.pages.setting.membershipLevel.setCompleteNum') }}</el-button>
          </div>
          <div>
            <el-table
              :data="taskTabList"
              ref="table"
              class="mt14"
              v-loading="taskLoading"
              highlight-current-row
              :no-data-text="$t('message.pages.setting.membershipLevel.noData')"
              :no-filtered-data-text="$t('message.pages.setting.membershipLevel.noFilterResult')"
            >
              <el-table-column :label="$t('message.pages.setting.membershipLevel.id')" width="80">
                <template slot-scope="scope">
                  <span>{{ scope.row.id }}</span>
                </template>
              </el-table-column>
              <el-table-column :label="$t('message.pages.setting.membershipLevel.name')" min-width="130">
                <template slot-scope="scope">
                  <span>{{ scope.row.name }}</span>
                </template>
              </el-table-column>
              <el-table-column :label="$t('message.pages.setting.membershipLevel.taskType')" min-width="80">
                <template slot-scope="scope">
                  <span>{{ scope.row.type_name }}</span>
                </template>
              </el-table-column>
              <el-table-column :label="$t('message.pages.setting.membershipLevel.limitNum')" min-width="80">
                <template slot-scope="scope">
                  <span>{{ scope.row.number }}</span>
                </template>
              </el-table-column>
              <el-table-column :label="$t('message.pages.setting.membershipLevel.isShowCol')" min-width="80">
                <template slot-scope="scope">
                  <el-switch
                    class="defineSwitch"
                    :active-value="1"
                    :inactive-value="0"
                    v-model="scope.row.status"
                    :value="scope.row.status"
                    @change="onchangeTaskIsShow(scope.row)"
                    :active-text="$t('message.pages.setting.membershipLevel.on')"
                    :inactive-text="$t('message.pages.setting.membershipLevel.off')"
                  >
                    <span slot="open">{{ $t('message.pages.setting.membershipLevel.on') }}</span>
                    <span slot="close">{{ $t('message.pages.setting.membershipLevel.off') }}</span>
                  </el-switch>
                </template>
              </el-table-column>
              <el-table-column :label="$t('message.pages.setting.membershipLevel.sort')" min-width="50">
                <template slot-scope="scope">
                  <span>{{ scope.row.sort }}</span>
                </template>
              </el-table-column>
              <el-table-column :label="$t('message.pages.setting.membershipLevel.action')" fixed="right" width="170">
                <template slot-scope="scope">
                  <a v-db-click @click="editTask(scope.row, $t('message.pages.setting.membershipLevel.edit'))">{{ $t('message.pages.setting.membershipLevel.edit') }}</a>
                  <el-divider direction="vertical"></el-divider>
                  <a v-db-click @click="delTask(scope.row, $t('message.pages.setting.membershipLevel.delInfoTitle'), scope.$index)">{{ $t('message.pages.setting.membershipLevel.del') }}</a>
                </template>
              </el-table-column>
            </el-table>
          </div>
        </div>
      </el-dialog>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import {
  membershipDataAddApi,
  membershipDataListApi,
  membershipDataEditApi,
  membershipSetApi,
  levelTaskSetApi,
  levelTaskListDataAddApi,
  levelTaskDataEditApi,
  levelTaskDataAddApi,
  getTaskNumFormApi,
} from '@/api/membershipLevel';
export default {
  name: 'list',
  data() {
    return {
      grid: {
        xl: 7,
        lg: 7,
        md: 12,
        sm: 24,
        xs: 24,
      },
      modal1: false,
      modal2: false,
      formValidate: {
        status: '',
        page: 1,
        limit: 20,
        gid: 0,
      },
      taskData: {
        keyword: '',
        page: 1,
        limit: 20,
        status: '',
      },
      total: 0,
      taskTotal: 0,
      tabList: [],
      taskTabList: [],
      columns1: [],
      columns2: [],
      FromData: null,
      loading: false,
      taskLoading: false,
      titleType: 'group',
      groupAll: [],
      theme3: 'light',
      labelSort: [],
      sortName: null,
      current: 0,
      model1: '',
      value1: '',
    };
  },
  computed: {
    ...mapState('admin/layout', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '80px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  watch: {
    $route(to, from) {
      if (this.$route.params.id) {
      } else {
      }
    },
  },
  mounted() {
    this.getList();
  },
  methods: {
    bindMenuItem(name, index) {
      this.current = index;
      this.formValidate.gid = name.id;
      this.getListHeader();
      this.getList();
    },
    // 列表
    getList() {
      this.loading = true;
      membershipDataListApi(this.formValidate)
        .then(async (res) => {
          let data = res.data;
          this.tabList = data.list;
          this.total = data.count;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    // 列表
    getTaskList() {
      this.taskLoading = true;
      levelTaskListDataAddApi(this.taskData)
        .then(async (res) => {
          let data = res.data;
          this.taskTabList = data.list;
          this.taskTotal = data.count;
          this.taskLoading = false;
        })
        .catch((res) => {
          this.taskLoading = false;
          this.$message.error(res.msg);
        });
    },
    // 表格搜索
    search() {
      this.formValidate.page = 1;
      this.getList();
    },
    searchTask() {
      this.taskData.page = 1;
      this.getTaskList();
    },
    taskEdit() {
      this.$modalForm(getTaskNumFormApi(this.id)).then(() => this.getList());
    },
    // 添加表单
    groupAdd() {
      this.$modalForm(membershipDataAddApi({}, '/agent/level/create')).then(() => this.getList());
    },
    taskAdd() {
      this.$modalForm(levelTaskDataAddApi({}, '/agent/level_task/create?level_id=' + this.taskData.id)).then(() =>
        this.getTaskList(),
      );
    },
    // 修改是否显示
    onchangeIsShow(row) {
      membershipSetApi(`agent/level/set_status/${row.id}/${row.status}`)
        .then(async (res) => {
          this.$message.success(res.msg);
          this.getList();
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 修改是否显示
    onchangeTaskIsShow(row) {
      levelTaskSetApi(`agent/level_task/set_status/${row.id}/${row.status}`)
        .then(async (res) => {
          this.$message.success(res.msg);
          this.getTaskList();
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    //添加等级任务
    addTask(row) {
      this.id = row.id;
      this.modal2 = true;
      this.taskData.id = row.id;
      this.getTaskList();
    },
    // 编辑
    edit(row) {
      let data = {
        gid: row.gid,
      };
      this.$modalForm(membershipDataEditApi(data, `agent/level/${row.id}/edit`)).then(() => this.getList());
    },
    // 编辑
    editTask(row) {
      let data = {
        gid: row.gid,
      };
      this.$modalForm(levelTaskDataEditApi(data, `agent/level_task/${row.id}/edit`)).then(() => this.getTaskList());
    },
    // 删除
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `agent/level/${row.id}`,
        method: 'DELETE',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.tabList.splice(num, 1);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 删除
    delTask(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `agent/level_task/${row.id}`,
        method: 'DELETE',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.taskTabList.splice(num, 1);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
  },
};
</script>

<style lang="scss" scoped>
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
  position: relative;
  display: flex;
  justify-content: space-between;
  word-break: break-all;
  .icon-box {
    z-index: 3;
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    display: none;
  }
  &:hover .icon-box {
    display: block;
  }
  .right-menu {
    z-index: 10;
    position: absolute;
    right: -106px;
    top: -11px;
    width: auto;
    min-width: 121px;
  }
}
.tabBox-img {
  width: 36px;
  height: 36px;
  border-radius: 4px;
  cursor: pointer;

  img {
    width: 100%;
    height: 100%;
  }
}
.ivu-menu {
  z-index: auto;
}
.header,
.headers {
  display: flex;
  flex-direction: column;
  background-color: #f2f2f2;
  padding: 8px;
  .search {
    display: flex;
    align-items: center;
    > div {
      margin-right: 10px;
    }
  }
}
.search ::v-deep .ivu-select-selection {
  border: 1px solid #dcdee2 !important;
}
.headers {
  background-color: #fff;
  margin-bottom: 20px;
}
::v-deep .ivu-modal-mask {
  z-index: 100 !important;
}
::v-deep .ivu-modal-wrap {
  z-index: 100 !important;
}
.add-task {
  margin: 10px 0;
}
</style>
