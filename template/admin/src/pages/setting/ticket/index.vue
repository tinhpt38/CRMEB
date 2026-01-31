<template>
  <!-- 商品-商品参数 -->
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt" :body-style="{ padding: 0 }">
      <div class="padding-add">
        <!-- 筛选条件 -->
        <el-form
          ref="specsFrom"
          inline
          :model="specsFrom"
          :label-width="labelWidth"
          :label-position="labelPosition"
          @submit.native.prevent
        >
          <el-form-item :label="$t('message.pages.setting.ticket.printerName')">
            <el-input
              v-model="specsFrom.keyword"
              :placeholder="$t('message.pages.setting.ticket.printerNamePlaceholder')"
              class="form_content_width"
              clearable
              @clear="specsSearchs"
              @change="specsSearchs"
            ></el-input>
          </el-form-item>
          <el-form-item :label="$t('message.pages.setting.ticket.platformSelect')">
            <el-select
              class="form_content_width mr10"
              v-model="specsFrom.type"
              clearable
              @clear="specsSearchs"
              @change="specsSearchs"
            >
              <el-option v-for="(item, i) in optionsList" :value="item.value" :label="item.label" :key="i" />
            </el-select>
            <el-button type="primary" @click="specsSearchs">{{ $t('message.pages.setting.ticket.query') }}</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <el-card :bordered="false" shadow="never" class="ivu-mt mt16">
      <el-button type="primary" v-db-click @click="add">{{ $t('message.pages.setting.ticket.addPrinter') }}</el-button>
      <!-- 商品参数表格 -->
      <el-table
        :data="list"
        ref="table"
        class="mt25"
        :loading="loading"
        highlight-row
        :no-userFrom-text="$t('message.pages.setting.ticket.noData')"
        :no-filtered-userFrom-text="$t('message.pages.setting.ticket.noFilterResult')"
      >
        <el-table-column :label="$t('message.pages.setting.ticket.id')" min-width="50">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.ticket.printerNameCol')" min-width="120">
          <template slot-scope="scope">
            <span>{{ scope.row.print_name }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.ticket.platform')" min-width="100">
          <template slot-scope="scope">
            <span v-if="scope.row.type == 1">{{ $t('message.pages.setting.ticket.yly') }}</span>
            <span v-if="scope.row.type == 2">{{ $t('message.pages.setting.ticket.fey') }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.ticket.appAccount')" min-width="100">
          <template slot-scope="scope">
            <span v-if="scope.row.type == 1">{{ scope.row.yly_app_id }}</span>
            <span v-if="scope.row.type == 2">{{ scope.row.fey_user }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.ticket.printCopies')" min-width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.times }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.ticket.createTime')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.add_time }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.ticket.printSwitch')" min-width="100">
          <template slot-scope="scope">
            <el-switch
              class="defineSwitch"
              :active-value="1"
              :inactive-value="0"
              v-model="scope.row.status"
              :value="scope.row.status"
              @change="onchangeIsShow(scope.row)"
              size="large"
              :active-text="$t('message.pages.setting.ticket.on')"
              :inactive-text="$t('message.pages.setting.ticket.off')"
            >
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.pages.setting.ticket.action')" fixed="right" width="170">
          <template slot-scope="scope">
            <a @click="setting(scope.row.id)">{{ $t('message.pages.setting.ticket.design') }}</a>
            <el-divider direction="vertical" />
            <a @click="edit(scope.row.id)">{{ $t('message.pages.setting.ticket.edit') }}</a>
            <el-divider direction="vertical" />
            <a @click="del(scope.row, $t('message.pages.setting.ticket.delPrinterTitle'), scope.$index)">{{ $t('message.pages.setting.ticket.del') }}</a>
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination
          v-if="total"
          :total="total"
          :page.sync="specsFrom.page"
          :limit.sync="specsFrom.limit"
          @pagination="getList"
        />
      </div>
    </el-card>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import { printList, printForm, printSetStatus } from '@/api/setting';

export default {
  name: 'specs',
  data() {
    return {
      grid: {
        xl: 10,
        lg: 10,
        md: 12,
        sm: 24,
        xs: 24,
      },
      loading: false,
      optionsListKeys: [
        { value: '0', key: 'all' },
        { value: '1', key: 'yly' },
        { value: '2', key: 'fey' },
      ],
      columns: [
        { titleKey: 'id', key: 'id', width: 80 },
        { titleKey: 'printerNameCol', key: 'print_name', minWidth: 100 },
        { titleKey: 'platform', slot: 'type', minWidth: 100 },
        { titleKey: 'appAccount', slot: 'account', minWidth: 100 },
        { titleKey: 'printCopies', key: 'times', width: 200 },
        { titleKey: 'createTime', key: 'add_time', width: 200 },
        { titleKey: 'printSwitch', slot: 'status', width: 200 },
        { titleKey: 'action', slot: 'action', fixed: 'right', width: 140 },
      ],
      specsFrom: {
        page: 1,
        limit: 15,
        keyword: '',
        type: '0',
      },
      list: [],
      total: 0,
    };
  },
  computed: {
    ...mapState('admin/layout', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '96';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
    optionsList() {
      return this.optionsListKeys.map((item) => ({
        value: item.value,
        label: this.$t('message.pages.setting.ticket.' + item.key),
      }));
    },
  },
  created() {
    this.getList();
  },
  methods: {
    specsSearchs() {
      this.specsFrom.page = 1;
      this.list = [];
      this.getList();
    },

    // 单位列表
    getList() {
      this.loading = true;
      printList(this.specsFrom)
        .then((res) => {
          let data = res.data;
          this.list = data.list;
          this.total = data.count;
          this.loading = false;
        })
        .catch((err) => {
          this.loading = false;
          this.$message.error(err.msg);
        });
    },
    pageChange(index) {
      this.specsFrom.page = index;
      this.getList();
    },
    // 修改是否显示
    onchangeIsShow(row) {
      let data = {
        id: row.id,
        status: row.status,
      };
      printSetStatus(data)
        .then(async (res) => {
          this.$message.success(res.msg);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 添加
    add() {
      this.$modalForm(printForm(0)).then(() => {
        this.getList();
      });
    },
    //修改
    edit(id) {
      this.$modalForm(printForm(id)).then(() => {
        this.getList();
      });
    },
    setting(id) {
      this.$router.push({
        path: this.$routeProStr + '/setting/ticket/content',
        query: { id: id },
      });
    },
    // 删除
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `system/ticket/del/${row.id}`,
        method: 'DELETE',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.list.splice(num, 1);
          if (!this.list.length) {
            this.specsFrom.page = this.specsFrom.page == 1 ? 1 : this.specsFrom.page - 1;
          }
          this.getList();
        })
        .catch((err) => {
          this.$message.error(err.msg);
        });
    },
  },
};
</script>

<style lang="scss" scoped>
.input-add {
  width: 250px;
  margin-right: 14px;
}
</style>
