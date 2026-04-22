<template>
  <div class="main">
    <el-alert class="mb20" closable>
      <template v-slot:title>{{ $t('message.systemCodeGen.crudGenerateTitle') }}</template>
      <template>{{ $t('message.systemCodeGen.crudGenerateTip') }}</template>
    </el-alert>
    <el-form ref="foundation" :model="foundation" :rules="foundationRules" label-width="100px">
      <el-form-item :label="$t('message.systemCodeGen.menuLabel')">
        <el-cascader
          class="form-width"
          v-model="foundation.pid"
          size="small"
          :options="menusList"
          :props="{ checkStrictly: true, multiple: false, emitPath: false }"
          clearable
        ></el-cascader>
        <div class="tip">{{ $t('message.systemCodeGen.menuTip') }}</div>
      </el-form-item>
      <el-form-item :label="$t('message.systemCodeGen.menuNameLabel')">
        <el-input class="form-width" v-model="foundation.menuName" :placeholder="$t('message.systemCodeGen.enterMenuName')"></el-input>
        <div class="tip">
          {{ $t('message.systemCodeGen.menuNameTip') }}
        </div>
      </el-form-item>
      <el-form-item :label="$t('message.systemCodeGen.moduleNameLabel')" prop="modelName">
        <el-input class="form-width" v-model="foundation.modelName" :placeholder="$t('message.systemCodeGen.enterModuleName')"></el-input>
        <div class="tip">{{ $t('message.systemCodeGen.moduleNameTip') }}</div>
      </el-form-item>
      <el-form-item :label="$t('message.systemCodeGen.tableNameLabel')" prop="tableName">
        <el-input class="form-width" v-model="foundation.tableName" :placeholder="$t('message.systemCodeGen.enterTableName')"></el-input>
        <div class="tip">
          {{ $t('message.systemCodeGen.tableNameTip') }}
        </div>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { crudMenus, crudColumnType, crudFilePath } from '@/api/systemCodeGeneration';

export default {
  name: '',
  props: {
    foundation: {
      type: Object,
      default: () => {
        return {};
      },
    },
  },
  data() {
    return {
      foundationRules: {
        // pid: [{ required: true, message: '请输入菜单', trigger: 'blur' }],
        tableName: [{ required: true, message: this.$t('message.systemCodeGen.enterTableName'), trigger: 'blur' }],
        modelName: [{ required: true, message: this.$t('message.systemCodeGen.enterModuleName'), trigger: 'blur' }],
      },
      menusList: [],
      columnTypeList: [],
      fromTypeList: [
        {
          value: '0',
          label: '不生成',
        },
        {
          value: 'input',
          label: 'input',
        },
        {
          value: 'textarea',
          label: 'textarea',
        },
        // {
        //   value: 'select',
        //   label: 'select',
        // },
        {
          value: 'radio',
          label: 'radio',
        },
        {
          value: 'number',
          label: 'number',
        },
        {
          value: 'frameImageOne',
          label: 'frameImageOne',
        },
        {
          value: 'frameImages',
          label: 'frameImages',
        },
      ],
      loading: false,
      tableField: [],
    };
  },
  created() {
    this.getCrudMenus();
  },
  mounted() {},
  methods: {
    disabledInput(index) {
      let fieldInfo = this.tableField[index];
      let res = ['addTimestamps', 'addSoftDelete'].includes(this.tableField[index].field_type);
      if (fieldInfo.primaryKey) {
        res = true;
      }
      if (fieldInfo.field === 'delete_time' && fieldInfo.field_type === 'timestamp') {
        res = true;
      }
      return res;
    },
    initfield() {
      this.tableField = [];
    },
    changeItemField(e, i) {
      if (e === 'addSoftDelete') {
        this.$set(this.tableField[i], 'comment', this.$t('message.systemCodeGen.softDelete'));
      }
      if (e === 'addTimestamps') {
        this.$set(this.tableField[i], 'comment', this.$t('message.systemCodeGen.createAndUpdateTime'));
      }
    },
    getCrudMenus() {
      crudMenus().then((res) => {
        this.menusList = res.data;
      });
      crudColumnType().then((res) => {
        this.columnTypeList = res.data.types;
      });
    },
    del(index) {
      this.tableField.splice(index, 1);
    },
  },
};
</script>
<style lang="scss" scoped>
.form-width {
  width: 500px;
}
.item {
  display: flex;
  margin-bottom: 10px;
  .row {
    width: 140px;
    margin-right: 10px;
  }
}
</style>
