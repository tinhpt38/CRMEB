<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mb-16" :body-style="{ padding: 0 }">
      <div class="padding-add">
        <el-form
          ref="formValidate"
          :model="formValidate"
          label-width="80px"
          label-position="right"
          @submit.native.prevent
          inline
        >
          <el-form-item :label="$t('message.pages.setting.systemAdmin.status')" label-for="status1">
            <el-select v-model="status" :placeholder="$t('message.pages.setting.systemAdmin.pleaseSelect')" @change="userSearchs" clearable class="form_content_width">
              <el-option value="all" :label="$t('message.pages.setting.systemAdmin.all')"></el-option>
              <el-option value="1" :label="$t('message.pages.setting.systemAdmin.on')"></el-option>
              <el-option value="0" :label="$t('message.pages.setting.systemAdmin.off')"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('message.pages.setting.systemAdmin.search')" label-for="status2">
            <el-input
              clearable
              :placeholder="$t('message.pages.setting.systemAdmin.placeholderNameAccount')"
              v-model="formValidate.name"
              class="form_content_width"
            />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" v-db-click @click="userSearchs">{{ $t('message.pages.setting.systemAdmin.query') }}</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <el-button v-auth="['setting-system_admin-add']" type="primary" v-db-click @click="add">{{ $t('message.pages.setting.systemAdmin.addAdmin') }}</el-button>
      <el-table
        :data="list"
        class="mt14"
        :no-data-text="$t('message.pages.setting.systemAdmin.noData')"
        :no-filtered-data-text="$t('message.pages.setting.systemAdmin.noFilterResult')"
        v-loading="loading"
        highlight-current-row
      >
        <el-table-column :label="$t('message.pages.setting.systemAdmin.realName')" width="120">
          <template slot-scope="scope">
            <span>{{ scope.row.real_name }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.systemAdmin.account')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.account }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.systemAdmin.role')" min-width="130">
          <template slot-scope="scope">
            <div v-if="scope.row.roles.length !== 0">
              <el-tag v-for="(item, index) in scope.row.roles.split(',')" :key="index">{{ item }}</el-tag>
            </div>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.systemAdmin.lastLoginTime')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row._last_time }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.systemAdmin.lastLoginIp')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.last_ip }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.systemAdmin.onCol')" min-width="70">
          <template slot-scope="scope">
            <el-switch
              class="defineSwitch"
              :active-value="1"
              :inactive-value="0"
              v-model="scope.row.status"
              :value="scope.row.status"
              @change="onchangeIsShow(scope.row)"
              size="large"
              :active-text="$t('message.pages.setting.systemAdmin.on')"
              :inactive-text="$t('message.pages.setting.systemAdmin.off')"
            >
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.systemAdmin.action')" fixed="right" width="100">
          <template slot-scope="scope">
            <a v-db-click @click="edit(scope.row)">{{ $t('message.pages.setting.systemAdmin.edit') }}</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="del(scope.row, $t('message.pages.setting.systemAdmin.delAdminTitle'), scope.$index)">{{ $t('message.pages.setting.systemAdmin.del') }}</a>
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
    <!-- 添加 编辑 -->
    <admin-from :FromData="FromData" ref="adminfrom" @submitFail="submitFail"></admin-from>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import { adminListApi, adminFromApi, adminEditFromApi, setShowApi } from '@/api/systemAdmin';
import adminFrom from '../../../components/from/from';
export default {
  name: 'systemAdmin',
  components: { adminFrom },
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
      loading: false,
      roleData: {
        status1: '',
      },
      formValidate: {
        roles: '',
        status: '',
        name: '',
        page: 1, // 当前页
        limit: 20, // 每页显示条数
      },
      status: '',
      list: [],
      FromData: null,
      modalTitleSs: '',
      ids: Number,
    };
  },
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '50px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'left';
    },
  },
  created() {
    this.getList();
  },
  methods: {
    // 修改是否开启
    onchangeIsShow(row) {
      let data = {
        id: row.id,
        status: row.status,
      };
      setShowApi(data)
        .then(async (res) => {
          this.$message.success(res.msg);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 请求列表
    submitFail() {
      this.getList();
    },
    // 列表
    getList() {
      this.loading = true;
      this.formValidate.roles = this.formValidate.roles || '';
      adminListApi(this.formValidate)
        .then(async (res) => {
          this.total = res.data.count;
          this.list = res.data.list;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    // 添加表单
    add() {
      adminFromApi()
        .then(async (res) => {
          this.FromData = res.data;
          this.$refs.adminfrom.modals = true;
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 编辑
    edit(row) {
      adminEditFromApi(row.id)
        .then(async (res) => {
          if (res.data.status === false) {
            return this.$authLapse(res.data);
          }
          this.FromData = res.data;
          this.$refs.adminfrom.modals = true;
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 删除
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `setting/admin/${row.id}`,
        method: 'DELETE',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.list.splice(num, 1);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 表格搜索
    userSearchs() {
      this.formValidate.status = this.status === 'all' ? '' : this.status;
      this.formValidate.page = 1;
      this.list = [];
      this.getList();
    },
  },
};
</script>

<style scoped></style>
