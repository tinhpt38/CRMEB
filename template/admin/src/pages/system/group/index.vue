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
          <el-form-item :label="$t('message.systemMenus.dataSearch') + '：'">
            <el-input
              clearable
              :placeholder="$t('message.systemMenus.pleaseInputIdKeyDataGroupNameIntro')"
              v-model="formValidate.title"
              class="form_content_width"
            />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" v-db-click @click="userSearchs">{{ $t('message.systemMenus.query') }}</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <el-button type="primary" v-db-click @click="groupAdd($t('message.systemMenus.addDataGroup'))" class="mr20">{{ $t('message.systemMenus.addDataGroup') }}</el-button>
      <el-table
        :data="tabList"
        ref="table"
        class="mt14"
        v-loading="loading"
        highlight-current-row
        :no-userFrom-text="$t('message.common.noData')"
        :no-filtered-userFrom-text="$t('message.common.noFilteredResults')"
      >
        <el-table-column label="ID" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="KEY" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.config_name }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.systemMenus.dataGroupName')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.name }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.systemMenus.intro')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.info }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.systemMenus.operation')" fixed="right" width="170">
          <template slot-scope="scope">
            <a v-db-click @click="goList(scope.row)">{{ $t('message.systemMenus.dataList') }}</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="edit(scope.row, $t('message.systemMenus.edit'))">{{ $t('message.systemMenus.edit') }}</a>
            <el-divider direction="vertical"></el-divider>
            <a v-db-click @click="del(scope.row, $t('message.systemMenus.deleteDataGroup'), scope.$index)">{{ $t('message.systemMenus.delete') }}</a>
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
    <!-- 新增 编辑-->
    <group-from
      ref="groupfroms"
      :titleFrom="titleFrom"
      :groupId="groupId"
      :addId="addId"
      @getList="getList"
    ></group-from>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import groupFrom from './components/groupFrom';
import { groupListApi } from '@/api/system';
export default {
  name: 'group',
  components: { groupFrom },
  data() {
    return {
      grid: {
        xl: 7,
        lg: 7,
        md: 12,
        sm: 24,
        xs: 24,
      },
      formValidate: {
        page: 1,
        limit: 20,
        title: '',
      },
      loading: false,
      tabList: [],
      total: 0,

      FromData: null,
      titleFrom: '',
      groupId: 0,
      addId: '',
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
    this.getList();
  },
  methods: {
    // 跳转到组合数据列表页面
    goList(row) {
      this.$router.push({
        path: this.$routeProStr + '/system/config/system_group/list/' + row.id,
      });
    },
    // 列表
    getList() {
      this.loading = true;
      groupListApi(this.formValidate)
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
    // 表格搜索
    userSearchs() {
      this.formValidate.page = 1;
      this.getList();
    },
    // 点击添加
    groupAdd(title) {
      this.$refs.groupfroms.modals = true;
      this.titleFrom = title;
      this.addId = 'addId';
      this.groupId = 0;
    },
    // 删除
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `setting/group/${row.id}`,
        method: 'DELETE',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.tabList.splice(num, 1);
          this.getList();
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 编辑
    edit(row, title) {
      this.titleFrom = title;
      this.groupId = row.id;
      this.$refs.groupfroms.fromData(row.id);
      this.$refs.groupfroms.modals = true;
      this.addId = '';
    },
  },
};
</script>

<style scoped></style>
