<template>
  <div class="main">
    <el-alert class="mb20" closable>
      <template v-slot:title>{{ $t('message.systemMenus.crudGenerationDescription') }}</template>
      <template> {{ $t('message.systemMenus.crudGenerationTip') }} </template>
    </el-alert>
    <el-form ref="foundation" :model="foundation" :rules="foundationRules" label-width="100px">
      <el-form-item :label="$t('message.systemMenus.menu') + '：'">
        <el-cascader
          class="form-width"
          v-model="foundation.pid"
          size="small"
          :options="menusList"
          :props="{ checkStrictly: true, multiple: false, emitPath: false }"
          clearable
        ></el-cascader>
        <div class="tip">{{ $t('message.systemMenus.menuSelectionTip') }}</div>
      </el-form-item>
      <el-form-item :label="$t('message.systemMenus.menuName') + '：'">
        <el-input class="form-width" v-model="foundation.menuName" :placeholder="$t('message.systemMenus.pleaseInputMenuName')"></el-input>
        <div class="tip">
          {{ $t('message.systemMenus.menuNameTip') }}
        </div>
      </el-form-item>
      <el-form-item :label="$t('message.systemMenus.moduleName') + '：'" prop="modelName">
        <el-input class="form-width" v-model="foundation.modelName" :placeholder="$t('message.systemMenus.pleaseInputModuleName')"></el-input>
        <div class="tip">{{ $t('message.systemMenus.moduleNameTip') }}</div>
      </el-form-item>
      <el-form-item :label="$t('message.systemMenus.tableName') + '：'" prop="tableName">
        <el-input class="form-width" v-model="foundation.tableName" :placeholder="$t('message.systemMenus.pleaseInputTableName')"></el-input>
        <div class="tip">
          {{ $t('message.systemMenus.tableNameTip') }}
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
      foundationRules: {},
      menusList: [],
      columnTypeList: [],
      fromTypeList: [
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
    // Initialize foundationRules with i18n
    this.foundationRules = {
      tableName: [{ required: true, message: this.$t('message.systemMenus.pleaseInputTableName'), trigger: 'blur' }],
      modelName: [{ required: true, message: this.$t('message.systemMenus.pleaseInputModuleName'), trigger: 'blur' }],
    };
    // Initialize fromTypeList
    this.fromTypeList = [
      {
        value: '0',
        label: this.$t('message.systemMenus.notGenerate'),
      },
      {
        value: 'input',
        label: 'input',
      },
      {
        value: 'textarea',
        label: 'textarea',
      },
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
    ];
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
        this.$set(this.tableField[i], 'comment', this.$t('message.systemMenus.softDelete'));
      }
      if (e === 'addTimestamps') {
        this.$set(this.tableField[i], 'comment', this.$t('message.systemMenus.addAndModifyTime'));
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
