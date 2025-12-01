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
          <el-form-item :label="$t('message.setting.printerName') + '：'">
            <el-input
              v-model="specsFrom.keyword"
              :placeholder="$t('message.setting.pleaseInputPrinterName')"
              class="form_content_width"
              clearable
              @clear="specsSearchs"
              @change="specsSearchs"
            ></el-input>
          </el-form-item>
          <el-form-item :label="$t('message.setting.platformSelection') + '：'">
            <el-select
              class="form_content_width mr10"
              v-model="specsFrom.type"
              clearable
              @clear="specsSearchs"
              @change="specsSearchs"
            >
              <el-option v-for="(item, i) in optionsList" :value="item.value" :label="item.label" :key="i"></el-option>
            </el-select>
            <el-button type="primary" @click="specsSearchs">{{ $t('message.setting.query') }}</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <el-card :bordered="false" shadow="never" class="ivu-mt mt16">
      <el-button type="primary" v-db-click @click="add">{{ $t('message.setting.addPrinter') }}</el-button>
      <!-- 商品参数表格 -->
      <el-table
        :data="list"
        ref="table"
        class="mt25"
        :loading="loading"
        highlight-row
        :empty-text="$t('message.common.noData')"
      >
        <el-table-column :label="$t('message.common.id')" min-width="50">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.setting.printerName')" min-width="120">
          <template slot-scope="scope">
            <span>{{ scope.row.print_name }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.setting.platform')" min-width="100">
          <template slot-scope="scope">
            <span v-if="scope.row.type == 1">{{ $t('message.setting.yilianyun') }}</span>
            <span v-if="scope.row.type == 2">{{ $t('message.setting.feieyun') }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.setting.applicationAccount')" min-width="100">
          <template slot-scope="scope">
            <span v-if="scope.row.type == 1">{{ scope.row.yly_app_id }}</span>
            <span v-if="scope.row.type == 2">{{ scope.row.fey_user }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.setting.printCopies')" min-width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.times }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.setting.createTime')" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.add_time }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.setting.printSwitch')" min-width="100">
          <template slot-scope="scope">
            <el-switch
              class="defineSwitch"
              :active-value="1"
              :inactive-value="0"
              v-model="scope.row.status"
              :value="scope.row.status"
              @change="onchangeIsShow(scope.row)"
              size="large"
              :active-text="$t('message.setting.open')"
              :inactive-text="$t('message.setting.close')"
            >
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column :label="$t('message.common.operation')" fixed="right" width="170">
          <template slot-scope="scope">
            <a @click="setting(scope.row.id)">{{ $t('message.setting.design') }}</a>
            <el-divider direction="vertical" />
            <a @click="edit(scope.row.id)">{{ $t('message.setting.edit') }}</a>
            <el-divider direction="vertical" />
            <a @click="del(scope.row, $t('message.setting.deletePrinter'), scope.$index)">{{ $t('message.setting.delete') }}</a>
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
      optionsList: [],
      columns: [
        {
          title: 'ID',
          key: 'id',
          width: 80,
        },
        {
          title: '打印机名称',
          key: 'print_name',
          minWidth: 100,
        },
        {
          title: '平台',
          slot: 'type',
          minWidth: 100,
        },
        {
          title: '应用账号',
          slot: 'account',
          minWidth: 100,
        },
        {
          title: '打印联数',
          key: 'times',
          width: 200,
        },
        {
          title: '创建时间',
          key: 'add_time',
          width: 200,
        },
        {
          title: '打印开关',
          slot: 'status',
          width: 200,
        },
        {
          title: '操作',
          slot: 'action',
          fixed: 'right',
          width: 140,
        },
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
  },
  created() {
    this.initOptionsList();
    this.getList();
  },
  methods: {
    initOptionsList() {
      this.optionsList = [
        {
          value: '0',
          label: this.$t('message.setting.all'),
        },
        {
          value: '1',
          label: this.$t('message.setting.yilianyun'),
        },
        {
          value: '2',
          label: this.$t('message.setting.feieyun'),
        },
      ];
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
},
}
</script>

<style lang="scss" scoped>
.input-add {
  width: 250px;
  margin-right: 14px;
}
</style>
