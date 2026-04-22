<template>
  <el-dialog :visible.sync="modals" :title="$t('userLevelTask.levelTask')" :close-on-click-modal="false" width="1000px" @closed="handleReset">
    <el-form
      ref="levelFrom"
      :model="levelFrom"
      :label-width="labelWidth"
      :label-position="labelPosition"
      @submit.native.prevent
    >
      <el-row :gutter="24">
        <el-col v-bind="grid">
          <el-form-item :label="$t('userLevelTask.levelStatus') + '：'">
            <el-select v-model="levelFrom.is_show" :placeholder="$t('userLevelTask.isShow')" clearable @change="userSearchs">
              <el-option value="1" :label="$t('userLevelTask.show')"></el-option>
              <el-option value="0" :label="$t('userLevelTask.hide')"></el-option>
            </el-select>
          </el-form-item>
        </el-col>
        <el-col v-bind="grid">
          <el-form-item :label="$t('userLevelTask.levelName') + '：'" prop="status2" label-for="status2">
            <el-input
              search
              enter-button
              v-model="levelFrom.name"
              :placeholder="$t('userLevelTask.pleaseInputLevelName')"
              @on-search="userSearchs"
              style="width: 100%"
            />
          </el-form-item>
        </el-col>
      </el-row>
    </el-form>
    <el-divider direction="vertical" dashed />
    <el-row>
      <el-col v-bind="grid" class="mb15">
        <el-button type="primary" v-db-click @click="add">{{ $t('userLevelTask.addLevelTask') }}</el-button>
      </el-col>
      <el-col :span="24" class="userAlert">
        <el-alert show-icon closable>
          <template slot="title">
            {{ $t('userLevelTask.alertText') }}
          </template>
        </el-alert>
      </el-col>
    </el-row>
    <el-divider direction="vertical" dashed />
    <el-table
      :data="levelLists"
      ref="table"
      v-loading="loading"
      :no-userFrom-text="$t('userLevelTask.noData')"
      :no-filtered-userFrom-text="$t('userLevelTask.noFilteredResult')"
    >
      <el-table-column :label="$t('userLevelTask.id')" width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('userLevelTask.levelName')" min-width="130">
        <template slot-scope="scope">
          <span>{{ scope.row.level_name }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('userLevelTask.taskName')" min-width="130">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('userLevelTask.isShow')" min-width="130">
        <template slot-scope="scope">
          <el-switch
            class="defineSwitch"
            :active-value="1"
            :inactive-value="0"
            v-model="scope.row.is_show"
            :value="scope.row.is_show"
            size="large"
            @change="onchangeIsShow(scope.row)"
            :active-text="$t('userLevelTask.show')"
            :inactive-text="$t('userLevelTask.hide')"
          >
          </el-switch>
        </template>
      </el-table-column>
      <el-table-column :label="$t('userLevelTask.mustAchieve')" min-width="130">
        <template slot-scope="scope">
          <el-switch
            class="defineSwitch"
            :active-value="1"
            :inactive-value="0"
            v-model="scope.row.is_must"
            :value="scope.row.is_must"
            :true-value="1"
            :false-value="0"
            size="large"
            @change="onchangeIsMust(scope.row)"
            :active-text="$t('userLevelTask.all')"
            :inactive-text="$t('userLevelTask.oneOf')"
          >
          </el-switch>
        </template>
      </el-table-column>
      <el-table-column :label="$t('userLevelTask.taskDesc')" min-width="130">
        <template slot-scope="scope">
          <span>{{ scope.row.illustrate }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('userLevelTask.operation')" fixed="right" width="170">
        <template slot-scope="scope">
          <a v-db-click @click="edit(scope.row)">{{ $t('userLevelTask.edit') }} | </a>
          <a v-db-click @click="del(scope.row, $t('userLevelTask.deleteLevelTask'), index)"> {{ $t('userLevelTask.delete') }}</a>
        </template>
      </el-table-column>
    </el-table>
    <div class="acea-row row-right page">
      <pagination
        v-if="total"
        :total="total"
        :page.sync="levelFrom.page"
        :limit.sync="levelFrom.limit"
        @pagination="getList"
      />
    </div>
    <!-- 新建 编辑表单-->
    <edit-from ref="edits" :FromData="FromData" @submitFail="submitFail" :titleType="titleType"></edit-from>
  </el-dialog>
</template>

<script>
import { mapState, mapMutations } from 'vuex';
import { taskListApi, setTaskShowApi, setTaskMustApi, createTaskApi } from '@/api/user';
import editFrom from '@/components/from/from';
export default {
  name: 'task',
  components: { editFrom },
  data() {
    return {
      grid: {
        xl: 10,
        lg: 10,
        md: 12,
        sm: 24,
        xs: 24,
      },
      modals: false,
      levelFrom: {
        is_show: '',
        name: '',
        page: 1,
        limit: 20,
      },
      total: 0,
      levelLists: [],
      loading: false,
      FromData: null,
      ids: 0,
      titleType: 'task',
    };
  },
  computed: {
    ...mapState('media', ['isMobile']),
    ...mapState('userLevel', ['levelId']),
    labelWidth() {
      return this.isMobile ? undefined : '80px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  methods: {
    ...mapMutations('userLevel', ['getTaskId', 'getlevelId']),
    // 添加
    add() {
      this.ids = '';
      this.getFrom();
    },
    // 新建 编辑表单
    getFrom() {
      let data = {
        id: this.ids,
        level_id: this.levelId,
      };
      this.$modalForm(createTaskApi(data)).then(() => this.getList());
    },
    // 编辑
    edit(row) {
      this.ids = row.id;
      this.getFrom();
    },
    // 关闭模态框
    handleReset() {
      this.modals = false;
    },
    // 表格搜索
    userSearchs() {
      this.getList();
    },
    // 任务列表
    getList() {
      this.loading = true;
      this.levelFrom.is_show = this.levelFrom.is_show || '';
      taskListApi(this.levelId, this.levelFrom)
        .then(async (res) => {
          let data = res.data;
          this.levelLists = data.list;
          this.total = res.data.count;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    // 修改显示隐藏
    onchangeIsShow(row) {
      let data = {
        id: row.id,
        is_show: row.is_show,
      };
      setTaskShowApi(data)
        .then(async (res) => {
          this.$message.success(res.msg);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 设置任务是否达成
    onchangeIsMust(row) {
      let data = {
        id: row.id,
        is_must: row.is_must,
      };
      setTaskMustApi(data)
        .then(async (res) => {
          this.$message.success(res.msg);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 新建编辑提交成功
    submitFail() {
      this.getList();
    },
    // 删除任务
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `user/user_level/delete_task/${row.id}`,
        method: 'DELETE',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.levelLists.splice(num, 1);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
  },
};
</script>

<style scoped></style>
