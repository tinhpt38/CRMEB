<template>
  <div>
    <el-card :bordered="false" shadow="never">
      <el-alert type="warning" :closable="false">
        <template slot="title">
          {{ $t('message.systemMenus.customEventDescription') }}<br />
          {{ $t('message.systemMenus.customEventTip1') }}<br />
          {{ $t('message.systemMenus.customEventTip2') }}<br />
          {{ $t('message.systemMenus.customEventTip3') }}<br />
        </template>
      </el-alert>
      <el-button type="primary" v-db-click @click="addTask" class="mt14">{{ $t('message.systemMenus.addSystemEvent') }}</el-button>
      <el-table :data="tableData" v-loading="loading" class="ivu-mt">
        <el-table-column :label="$t('message.systemMenus.number')" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.systemMenus.eventName')" min-width="150">
          <template slot-scope="scope">
            <span>{{ scope.row.name }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.systemMenus.eventType')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.mark_name }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.systemMenus.isOpen')" min-width="130">
          <template slot-scope="scope">
            <el-switch
              class="defineSwitch"
              :active-value="1"
              :inactive-value="0"
              v-model="scope.row.is_open"
              size="large"
              @change="handleChange(scope.row)"
              :active-text="$t('message.setting.open')"
              :inactive-text="$t('message.setting.close')"
            >
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.systemMenus.createTime')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.add_time }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.systemMenus.operation')" width="100">
          <template slot-scope="scope">
            <a v-db-click @click="edit(scope.row.id)">{{ $t('message.systemMenus.edit') }}</a>
            <el-divider direction="vertical"></el-divider>
            <a v-permission="'seckill'" v-db-click @click="handleDelete(scope.row, $t('message.systemMenus.deleteCustomEvent'), scope.$index)"
              >{{ $t('message.systemMenus.delete') }}</a
            >
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination v-if="total" :total="total" :page.sync="page" :limit.sync="limit" @pagination="getList" />
      </div>
      <creatTask ref="addTask" :currentTab="currentTab" @submitAsk="getList"></creatTask>
    </el-card>
  </div>
</template>

<script>
import { eventIndex, eventShowTimer } from '@/api/system';
import creatTask from './createModal.vue';
import setting from '@/setting';
export default {
  name: 'system_event',
  components: { creatTask },
  data() {
    return {
      loading: false,
      tableData: [],
      page: 1,
      limit: 15,
      total: 1,
      apiBaseURL: '',
      headerList: [],
      currentTab: '0',
    };
  },
  created() {
    // Initialize headerList with i18n
    this.headerList = [
      { label: this.$t('message.systemMenus.systemTask'), value: '0' },
      { label: this.$t('message.systemMenus.customTask'), value: '1' },
    ];
    this.apiBaseURL = setting.apiBaseURL;
    this.getList();
  },
  methods: {
    // 列表
    getList() {
      this.loading = true;
      eventIndex({
        page: this.page,
        limit: this.limit,
      })
        .then((res) => {
          this.loading = false;
          let { count, list } = res.data;
          this.total = count;
          this.tableData = list;
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    addTask() {
      this.$refs.addTask.eventInfo();
    },
    edit(id) {
      this.$refs.addTask.eventInfo(id);
    },
    // 删除
    handleDelete(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `system/event/del/${row.id}`,
        method: 'delete',
        ids: '',
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
    // 是否开启
    handleChange({ id, is_open }) {
      eventShowTimer(id, is_open)
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
.ivu-mt {
  padding-top: 10px;
}
</style>
